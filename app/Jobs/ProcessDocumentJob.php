<?php

namespace App\Jobs;

use App\Models\RagDocument;
use App\Services\ChunkingService;
use App\Services\DocumentParserService;
use App\Services\EmbeddingService;
use App\Services\QdrantService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;use Illuminate\Support\Str;
class ProcessDocumentJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(public RagDocument $document)
    {
        $this->onQueue('documents');
    }

    public function handle(
        DocumentParserService $parserService,
        ChunkingService $chunkingService,
        EmbeddingService $embeddingService,
        QdrantService $qdrantService
    ): void
    {
        $this->document->refresh();
        $this->document->update(['processing_status' => 'processing']);

        $filePath = storage_path('app/' . $this->document->stored_path);

        if (! file_exists($filePath)) {
            Log::error('Document file not found during indexing.', [
                'document_id' => $this->document->id,
                'stored_path' => $this->document->stored_path,
            ]);
            $this->document->update(['processing_status' => 'failed']);

            return;
        }

        try {
            $text = $parserService->parse($filePath, $this->document->mime_type);
            $chunks = $chunkingService->split($text);

            $this->document->update(['status' => 'processed', 'processing_status' => 'parsed']);

            if (empty($chunks)) {
                Log::warning('Document produced no chunks for indexing.', [
                    'document_id' => $this->document->id,
                ]);
                $this->document->update(['processing_status' => 'completed-without-chunks']);

                return;
            }

            $qdrantService->ensureCollection();

            $points = [];
            foreach ($chunks as $index => $chunk) {
                $embedding = $embeddingService->embed($chunk);

                $points[] = [
                    'id' => (string) Str::uuid(),
                    'vector' => $embedding,
                    'payload' => [
                        'document_id' => $this->document->id,
                        'title' => $this->document->title,
                        'chunk_text' => $chunk,
                        'chunk_index' => $index,
                        'mime_type' => $this->document->mime_type,
                    ],
                ];
            }

            if (! empty($points)) {
                $qdrantService->upsertPoints('documents', $points);
            }

            $this->document->update(['processing_status' => 'embedded']);
        } catch (\Throwable $exception) {
            Log::error('Document indexing failed.', [
                'document_id' => $this->document->id,
                'message' => $exception->getMessage(),
                'trace' => $exception->getTraceAsString(),
            ]);
            $this->document->update(['processing_status' => 'failed']);

            throw $exception;
        }
    }
}

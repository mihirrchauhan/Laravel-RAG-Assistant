<?php

namespace App\Services;

use App\Jobs\ProcessDocumentJob;
use App\Models\RagDocument;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;

class DocumentStorageService
{
    public function storeDocument(array $data, UploadedFile $file, ?int $uploadedBy = null): RagDocument
    {
        $originalName = $file->getClientOriginalName();
        $storedPath = $file->storeAs(
            'knowledge-base',
            $this->buildUniqueFileName($originalName),
            'local'
        );

        $document = RagDocument::create([
            'title' => $data['title'] ?? pathinfo($originalName, PATHINFO_FILENAME),
            'description' => $data['description'] ?? null,
            'category' => $data['category'] ?? null,
            'original_filename' => $originalName,
            'stored_path' => $storedPath,
            'mime_type' => $file->getClientMimeType(),
            'uploaded_by' => $uploadedBy,
            'status' => 'uploaded',
            'processing_status' => 'pending',
        ]);

        $document->update(['processing_status' => 'queued']);

        ProcessDocumentJob::dispatch($document);

        return $document;
    }

    private function buildUniqueFileName(string $originalName): string
    {
        $extension = pathinfo($originalName, PATHINFO_EXTENSION);
        $basename = pathinfo($originalName, PATHINFO_FILENAME);
        $timestamp = now()->format('YmdHis');
        $random = substr(md5(uniqid('', true)), 0, 8);

        return sprintf('%s-%s-%s.%s', Str::slug($basename), $timestamp, $random, $extension);
    }
}

<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Jobs\ProcessDocumentJob;
use App\Models\RagDocument;
use App\Services\DocumentStorageService;
use App\Services\QdrantService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;

class KnowledgeBaseController extends Controller
{
    public function __construct(protected DocumentStorageService $documentStorageService)
    {
    }

    public function documents(Request $request)
    {
        $documents = \App\Models\RagDocument::query()
            ->latest()
            ->get();

        $stats = [
            'total' => $documents->count(),
            'indexed' => $documents->where('processing_status', 'embedded')->count(),
            'processing' => $documents->whereIn('processing_status', ['pending', 'queued', 'processing', 'parsed', 'chunked'])->count(),
            'failed' => $documents->where('processing_status', 'failed')->count(),
        ];

        return view('admin.pages.documents', compact('documents', 'stats'));
    }

    public function create()
    {
        return view('admin.pages.documents-create');
    }

    public function show(RagDocument $document)
    {
        return view('admin.pages.documents-show', compact('document'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'document' => ['required', 'file', 'mimes:pdf,doc,docx,txt', 'max:20480'],
        ]);

        $this->documentStorageService->storeDocument(
            $request->only(['title', 'description', 'category']),
            $request->file('document'),
            auth()->id()
        );

        return Redirect::route('admin.documents')->with('success', 'Document uploaded and queued for indexing.');
    }

    public function reindex(RagDocument $document)
    {
        $document->update([
            'status' => 'uploaded',
            'processing_status' => 'queued',
        ]);

        ProcessDocumentJob::dispatch($document);

        return Redirect::route('admin.documents')->with('success', 'Document reindexed successfully.');
    }

    public function health()
    {
        $ollamaUrl = config('services.ollama.embedding_url', 'http://localhost:11434/api/embeddings');
        $qdrantUrl = rtrim(config('services.qdrant.url', 'http://localhost:6333'), '/');

        $ollamaStatus = false;
        $qdrantStatus = false;

        try {
            $ollamaResponse = Http::timeout(5)->post($ollamaUrl, [
                'model' => config('services.ollama.embedding_model', 'nomic-embed-text'),
                'prompt' => 'health check',
            ]);
            $ollamaStatus = $ollamaResponse->successful();
        } catch (\Throwable $exception) {
            $ollamaStatus = false;
        }

        try {
            $qdrantResponse = Http::timeout(5)->get($qdrantUrl.'/collections');
            $qdrantStatus = $qdrantResponse->successful();
        } catch (\Throwable $exception) {
            $qdrantStatus = false;
        }

        return response()->json([
            'ollama' => $ollamaStatus,
            'qdrant' => $qdrantStatus,
        ]);
    }

    public function destroy(RagDocument $document, QdrantService $qdrantService)
    {
        $qdrantService->deletePoints('documents', $document->id);

        if ($document->stored_path && Storage::disk('local')->exists($document->stored_path)) {
            Storage::disk('local')->delete($document->stored_path);
        }

        $document->delete();

        return Redirect::route('admin.documents')->with('success', 'Document deleted successfully.');
    }
}

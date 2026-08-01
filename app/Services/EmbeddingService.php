<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use RuntimeException;

class EmbeddingService
{
    public function embed(string $text): array
    {
        $response = Http::timeout(120)
            ->post(config('services.ollama.embedding_url', 'http://localhost:11434/api/embeddings'), [
                'model' => config('services.ollama.embedding_model', 'nomic-embed-text'),
                'prompt' => $text,
            ]);

        if ($response->failed()) {
            throw new RuntimeException('Failed to generate embedding from Ollama: '.$response->body());
        }

        $data = $response->json();

        if (! isset($data['embedding']) || ! is_array($data['embedding'])) {
            throw new RuntimeException('Invalid embedding response from Ollama.');
        }

        return $data['embedding'];
    }
}

<?php

namespace App\Services;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;

class RagService
{
    public function __construct(
        protected EmbeddingService $embeddingService,
        protected QdrantService $qdrantService,
        protected GroqService $groqService
    ) {
    }

    public function answerQuestion(string $question): string
    {
        $queryEmbedding = $this->embeddingService->embed($question);
        Log::info('Query embedding length', ['length' => is_array($queryEmbedding) ? count($queryEmbedding) : null]);

        $results = $this->qdrantService->search('documents', $queryEmbedding, 5);

        // Log a compact summary of retrieval results for debugging RAG behavior
        $compact = array_map(function ($r) {
            return [
                'id' => $r['id'] ?? null,
                'score' => $r['score'] ?? ($r['payload']['score'] ?? null),
                'title' => $r['payload']['title'] ?? null,
                'chunk_preview' => isset($r['payload']['chunk_text']) ? mb_strimwidth($r['payload']['chunk_text'], 0, 200, '...') : null,
            ];
        }, $results);
        Log::info('RAG search results', ['count' => count($results), 'results' => $compact]);

        $context = $this->buildContext($results);

        $messages = [[
            'role' => 'user',
            'content' => $this->buildPrompt($question, $context),
        ]];

        $response = '';
        foreach ($this->groqService->chatStream($messages) as $chunk) {
            $response .= $chunk;
        }

        return $response;
    }

    private function buildContext(array $results): string
    {
        if (empty($results)) {
            return '';
        }

        return collect($results)
            ->map(function (array $result) {
                $payload = $result['payload'] ?? [];

                return sprintf(
                    "Document: %s\nChunk: %s",
                    $payload['title'] ?? 'Unknown document',
                    $payload['chunk_text'] ?? ''
                );
            })
            ->implode("\n\n");
    }

    private function buildPrompt(string $question, string $context): string
    {
        $contextSection = $context !== ''
            ? "Use the following retrieved context to answer the question. If the answer is not present in the context, say \"I don't know.\" Do not hallucinate or provide internal reasoning.\n\nContext:\n{$context}"
            : "There is no retrieved context available. Answer: I don't know.";

        return <<<PROMPT
You are a helpful assistant for a RAG-based knowledge base.

Question: {$question}

{$contextSection}
PROMPT;
    }
}

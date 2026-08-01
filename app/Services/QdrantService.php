<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use RuntimeException;

class QdrantService
{
    protected string $baseUrl;

    public function __construct(?string $baseUrl = null)
    {
        $this->baseUrl = rtrim($baseUrl ?? config('services.qdrant.url', 'http://localhost:6333'), '/');
    }

    public function ensureCollection(string $collectionName = 'documents'): void
    {
        $response = Http::get($this->baseUrl.'/collections/'.$collectionName);

        if ($response->ok()) {
            return;
        }

        if (! $response->clientError() || $response->status() !== 404) {
            throw new RuntimeException('Unable to check Qdrant collection: '.$response->body());
        }

        $createResponse = Http::put($this->baseUrl.'/collections/'.$collectionName, [
            'vectors' => [
                'size' => config('services.qdrant.vector_size', 768),
                'distance' => 'Cosine',
            ],
        ]);

        if ($createResponse->failed()) {
            throw new RuntimeException('Unable to create Qdrant collection: '.$createResponse->body());
        }
    }

    public function upsertPoints(string $collectionName, array $points): void
    {
        $response = Http::put($this->baseUrl.'/collections/'.$collectionName.'/points?wait=true', [
            'points' => $points,
        ]);

        if ($response->failed()) {
            throw new RuntimeException('Unable to upsert points into Qdrant: '.$response->body());
        }
    }

    public function search(string $collectionName, array $vector, int $limit = 5): array
    {
        $response = Http::post($this->baseUrl.'/collections/'.$collectionName.'/points/query', [
            'vector' => $vector,
            'limit' => $limit,
            'with_payload' => true,
        ]);

        if ($response->failed()) {
            throw new RuntimeException('Unable to query Qdrant: '.$response->body());
        }

        $json = $response->json();

        // Common Qdrant response shapes:
        // 1) { "result": [ { point }, ... ] }
        // 2) { "result": { "points": [ { point }, ... ] } }
        // 3) { "result": { "points": { ... } } } (single-point keyed)
        // Normalize to an indexed array of point objects.

        $result = $json['result'] ?? $json;

        if (isset($result['points'])) {
            // points can be indexed array or associative map; return values
            return is_array($result['points']) ? array_values($result['points']) : [];
        }

        // If result itself is a single point object, wrap it
        if (isset($result['id']) || isset($result['payload'])) {
            return [$result];
        }

        // If result is already an indexed array, return as-is
        if (is_array($result)) {
            // ensure numeric array
            $keys = array_keys($result);
            $isNumeric = $keys === array_keys($keys);

            return $isNumeric ? $result : array_values($result);
        }

        return [];
    }

    public function deletePoints(string $collectionName, int $documentId): void
    {
        $response = Http::post($this->baseUrl.'/collections/'.$collectionName.'/points/delete', [
            'filter' => [
                'must' => [
                    [
                        'key' => 'document_id',
                        'match' => ['value' => $documentId],
                    ],
                ],
            ],
        ]);

        if ($response->failed()) {
            throw new RuntimeException('Unable to delete points from Qdrant: '.$response->body());
        }
    }
}

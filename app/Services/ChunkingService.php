<?php

namespace App\Services;

class ChunkingService
{
    public function split(string $text, int $chunkSize = 1000, int $overlap = 150): array
    {
        $normalized = preg_replace('/\s+/', ' ', trim($text));

        if ($normalized === null || trim($normalized) === '') {
            return [];
        }

        $words = preg_split('/\s+/', $normalized);

        if ($words === false || count($words) === 0) {
            return [];
        }

        $chunks = [];
        $index = 0;

        while ($index < count($words)) {
            $chunkWords = array_slice($words, $index, $chunkSize);

            if (empty($chunkWords)) {
                break;
            }

            $chunks[] = implode(' ', $chunkWords);
            $index += max(1, $chunkSize - $overlap);
        }

        return $chunks;
    }
}

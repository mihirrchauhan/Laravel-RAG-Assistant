<?php

namespace Tests\Unit;

use App\Services\ChunkingService;
use App\Services\EmbeddingService;
use App\Services\GroqService;
use App\Services\QdrantService;
use App\Services\RagService;
use Mockery;
use PHPUnit\Framework\TestCase;

class RagServiceTest extends TestCase
{
    protected function tearDown(): void
    {
        Mockery::close();
    }

    public function test_chunking_service_splits_text_into_expected_chunks(): void
    {
        $service = new ChunkingService();
        $text = 'one two three four five six seven eight nine ten eleven twelve';

        $chunks = $service->split($text, 4, 1);

        $this->assertCount(4, $chunks);
        $this->assertSame('one two three four', $chunks[0]);
        $this->assertSame('four five six seven', $chunks[1]);
        $this->assertSame('seven eight nine ten', $chunks[2]);
        $this->assertSame('ten eleven twelve', $chunks[3]);
    }

    public function test_rag_service_builds_context_from_query_results(): void
    {
        $embeddingService = Mockery::mock(EmbeddingService::class);
        $embeddingService->shouldReceive('embed')->once()->andReturn([0.1, 0.2]);

        $qdrantService = Mockery::mock(QdrantService::class);
        $qdrantService->shouldReceive('search')->once()->andReturn([
            [
                'payload' => [
                    'title' => 'Example Doc',
                    'chunk_text' => 'Relevant chunk text',
                ],
            ],
        ]);

        $groqService = Mockery::mock(GroqService::class);
        $groqService->shouldReceive('chatStream')->once()->andReturn((function () {
            yield 'A';
            yield 'B';
        })());

        $service = new RagService($embeddingService, $qdrantService, $groqService);

        $response = $service->answerQuestion('What is this about?');

        $this->assertSame('AB', $response);
    }
}

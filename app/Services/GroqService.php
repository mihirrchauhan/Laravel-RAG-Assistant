<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class GroqService
{
    public function chatStream(array $messages): \Generator
    {
        $messages = array_merge([
            [
                'role' => 'system',
                'content' => <<<'PROMPT'
You are a helpful AI assistant.

Formatting Rules

Always return valid GitHub-Flavored Markdown (GFM).

Rules:
- Use #, ## and ### headings where appropriate.
- Separate paragraphs with blank lines.
- Use numbered and bullet lists correctly.
- Use **bold** for important concepts.
- Use *italic* sparingly.
- Always wrap code in fenced code blocks.
- Always specify the language of every code block.
- Use tables for comparisons.
- Use blockquotes for notes or warnings.
- Never output raw HTML.
- Never wrap the entire response inside one giant code block.
- Ensure all Markdown is valid and properly closed.

When the user asks for code, explain first, then provide the code.
PROMPT
            ]
        ], $messages);

        $response = Http::withToken(config('services.groq.api_key'))
            ->withOptions([
                'stream' => true,
            ])
            ->post('https://api.groq.com/openai/v1/chat/completions', [
                'model' => 'llama-3.3-70b-versatile',
                'messages' => $messages,
                'stream' => true,
            ]);

        if ($response->failed()) {
            throw new \Exception($response->body());
        }

        $body = $response->toPsrResponse()->getBody();

        while (! $body->eof()) {

            $line = trim($body->read(1024));

            if ($line === '') {
                continue;
            }

            $lines = explode("\n", $line);

            foreach ($lines as $item) {

                if (! str_starts_with($item, 'data: ')) {
                    continue;
                }

                $json = substr($item, 6);

                if ($json === '[DONE]') {
                    return;
                }

                $payload = json_decode($json, true);

                if (! isset($payload['choices'][0]['delta']['content'])) {
                    continue;
                }

                yield $payload['choices'][0]['delta']['content'];
            }
        }
    }
}

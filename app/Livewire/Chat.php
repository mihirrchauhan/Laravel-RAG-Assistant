<?php

namespace App\Livewire;

use App\Services\GroqService;
use Livewire\Component;

class Chat extends Component
{
    public $count = 0;
    public $message = '';
    public $messages = [];
    public bool $streaming = false;

    public function render()
    {
        return view('livewire.chat');
    }

    public function sendMessage(GroqService $groq)
    {
        $this->message = trim($this->message);

        if ($this->message === '') {
            return;
        }

        // Add user message
        $this->messages[] = [
            'role' => 'user',
            'content' => $this->message,
        ];

        $this->message = '';

        // Create an empty assistant message
        $this->messages[] = [
            'role' => 'assistant',
            'content' => '',
        ];

        $assistantIndex = array_key_last($this->messages);
        $this->streaming = true;
        try {

            foreach ($groq->chatStream($this->messages) as $chunk) {

                $this->messages[$assistantIndex]['content'] .= $chunk;

                // Stream only this message to the browser
                $this->stream(
                    to: "assistant-{$assistantIndex}",
                    content: $this->messages[$assistantIndex]['content'],
                    replace: true,
                );
            }
        } catch (\Throwable $e) {

            $this->messages[$assistantIndex]['content'] =
                'Sorry, something went wrong.';

            report($e);
        } finally {

            $this->streaming = false;
        }
    }
}

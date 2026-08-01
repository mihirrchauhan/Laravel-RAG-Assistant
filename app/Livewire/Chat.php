<?php

namespace App\Livewire;

use App\Services\RagService;
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

    public function sendMessage(RagService $ragService)
    {
        $userMessage = $this->message;

        if ($userMessage === '') {
            return;
        }

        // Add user message
        $this->messages[] = [
            'role' => 'user',
            'content' => $userMessage,
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

            $answer = $ragService->answerQuestion($userMessage);

            $this->messages[$assistantIndex]['content'] = $answer;

            $this->stream(
                to: "assistant-{$assistantIndex}",
                content: $this->messages[$assistantIndex]['content'],
                replace: true,
            );
        } catch (\Throwable $e) {
            $this->messages[$assistantIndex]['content'] =
                'Sorry, something went wrong.';

            report($e);
        } finally {

            $this->streaming = false;
        }
    }
}

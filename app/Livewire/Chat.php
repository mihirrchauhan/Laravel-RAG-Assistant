<?php

namespace App\Livewire;

use Livewire\Component;

class Chat extends Component
{
    public $count = 0;
    public $message = '';
    public $messages = [];

    public function render()
    {
        return view('livewire.chat');
    }

public function sendMessage()
{
    $this->message = trim($this->message);

    if ($this->message === '') return;

    $this->messages[] = [
        'role' => 'user',
        'content' => $this->message,
    ];

    $this->messages[] = [
        'role' => 'ai',
        'content' => 'You said: ' . $this->message,
    ];

    $this->message = '';
}
}

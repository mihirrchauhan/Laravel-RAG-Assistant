<div class="ai-shell">

    <style>
        body {
            margin: 0;
            background: #0b0f19;
            font-family: ui-sans-serif, system-ui;
        }

        .ai-shell {
            max-width: 780px;
            margin: 0 auto;
            height: 100vh;
            display: flex;
            flex-direction: column;
            background: #0f172a;
            color: #e5e7eb;
        }

        /* Top bar */
        .topbar {
            display: flex;
            justify-content: space-between;
            padding: 14px 18px;
            border-bottom: 1px solid #1f2937;
            background: #0b1220;
        }

        .brand {
            font-weight: 600;
        }

        .status {
            font-size: 12px;
            color: #22c55e;
        }

        /* Chat area */
        .chat-area {
            flex: 1;
            overflow-y: auto;
            padding: 20px;
            display: flex;
            flex-direction: column;
            gap: 14px;
        }
.row {
    display: flex;
    width: 100%;
    align-items: flex-end;
    gap: 10px;
}

/* LEFT SIDE (AI) */
.row.ai {
    justify-content: flex-start;
}

/* RIGHT SIDE (USER) */
.row.user {
    justify-content: flex-end;
}

/* BUBBLE (AUTO SIZE FIX) */
.bubble {
    display: inline-block;
    max-width: 70%;
    padding: 10px 14px;
    border-radius: 14px;
    line-height: 1.5;
    font-size: 14px;
    /* white-space: pre-wrap; */
    word-break: break-word;
}

/* USER */
.row.user .bubble {
    background: #2563eb;
    color: white;
    border-top-right-radius: 6px;
}

/* AI */
.row.ai .bubble {
    background: #111827;
    border: 1px solid #1f2937;
    border-top-left-radius: 6px;
}

        /* Input */
        .composer {
            display: flex;
            padding: 14px;
            border-top: 1px solid #1f2937;
            background: #0b1220;
            gap: 10px;
        }

        .composer input {
            flex: 1;
            padding: 12px 14px;
            border-radius: 10px;
            border: 1px solid #1f2937;
            background: #0f172a;
            color: white;
            outline: none;
        }

        .composer button {
            width: 44px;
            border-radius: 10px;
            border: none;
            background: #2563eb;
            color: white;
            cursor: pointer;
        }
    </style>

    <!-- Top bar -->
    <div class="topbar">
        <div class="brand">AI Assistant</div>
        <div class="status">● Online</div>
    </div>

    <!-- Chat -->
  <div class="chat-area">
    @foreach($messages as $msg)

        <div class="row {{ $msg['role'] === 'user' ? 'user' : 'ai' }}">

            @if($msg['role'] !== 'user')
                <div class="avatar">AI</div>
            @endif

            <div class="bubble">
                {{ trim($msg['content']) }}
            </div>

            @if($msg['role'] === 'user')
                <div class="avatar">You</div>
            @endif

        </div>

    @endforeach
</div>

    <!-- Input -->
    <div class="composer">
        <input
            type="text"
            wire:model="message"
            wire:keydown.enter="sendMessage"
            placeholder="Message AI..." />

        <button wire:click="sendMessage">➤</button>
    </div>

</div>
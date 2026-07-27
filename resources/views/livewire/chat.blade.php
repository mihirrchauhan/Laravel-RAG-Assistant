@php
use Illuminate\Support\Str;
@endphp

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

        /* Markdown Styling */
        .bubble h1,
        .bubble h2,
        .bubble h3,
        .bubble h4,
        .bubble h5,
        .bubble h6 {
            margin: 10px 0 8px 0;
            font-weight: 600;
            line-height: 1.3;
        }

        .bubble h1 {
            font-size: 1.5em;
        }

        .bubble h2 {
            font-size: 1.3em;
        }

        .bubble h3 {
            font-size: 1.1em;
        }

        .bubble h4,
        .bubble h5,
        .bubble h6 {
            font-size: 1em;
        }

        .bubble p {
            margin: 6px 0;
        }

        .bubble ul,
        .bubble ol {
            margin: 8px 0;
            padding-left: 24px;
        }

        .bubble li {
            margin: 4px 0;
        }

        .bubble code {
            background: #1f2937;
            padding: 2px 6px;
            border-radius: 4px;
            font-family: 'Courier New', monospace;
            font-size: 0.9em;
            color: #fbbf24;
        }

        .bubble pre {
            background: #1f2937;
            padding: 10px;
            border-radius: 6px;
            overflow-x: auto;
            margin: 8px 0;
            border: 1px solid #374151;
        }

        .bubble pre code {
            background: none;
            padding: 0;
            color: #e5e7eb;
            font-size: 0.85em;
            line-height: 1.4;
        }

        .bubble blockquote {
            border-left: 3px solid #3b82f6;
            padding-left: 12px;
            margin: 8px 0;
            color: #9ca3af;
            font-style: italic;
        }

        .bubble strong {
            font-weight: 600;
            color: #f3f4f6;
        }

        .bubble em {
            font-style: italic;
        }

        .bubble a {
            color: #3b82f6;
            text-decoration: none;
        }

        .bubble a:hover {
            text-decoration: underline;
        }

        .bubble hr {
            border: none;
            border-top: 1px solid #374151;
            margin: 12px 0;
        }

        .bubble table {
            border-collapse: collapse;
            margin: 8px 0;
            width: 100%;
            font-size: 0.9em;
        }

        .bubble table th,
        .bubble table td {
            border: 1px solid #374151;
            padding: 6px 8px;
            text-align: left;
        }

        .bubble table th {
            background: #1f2937;
            font-weight: 600;
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

            <div class="bubble markdown-body"
                x-data
                x-init="
        $nextTick(() => {
            const html = DOMPurify.sanitize(marked.parse(@js($msg['content'])));
            $el.innerHTML = html;

            $el.querySelectorAll('pre code').forEach((block) => {
                hljs.highlightElement(block);
            });
        });
     ">
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
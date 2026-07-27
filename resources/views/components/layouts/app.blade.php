<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laravel RAG Assistant</title>
    <script src="https://cdn.tailwindcss.com"></script>

    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap"
        rel="stylesheet">
        <!-- Highlight.js -->
<link rel="stylesheet"
      href="https://cdnjs.cloudflare.com/ajax/libs/highlight.js/11.11.1/styles/github-dark.min.css">

<script src="https://cdnjs.cloudflare.com/ajax/libs/highlight.js/11.11.1/highlight.min.js"></script>

<!-- Marked -->
<script src="https://cdn.jsdelivr.net/npm/marked/marked.min.js"></script>

<!-- DOMPurify -->
<script src="https://cdn.jsdelivr.net/npm/dompurify@3.2.6/dist/purify.min.js"></script>

<!-- Alpine (if not already included) -->
<script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <style>

        .markdown-body {
    line-height: 1.7;
    font-size: 15px;
}

.markdown-body h1,
.markdown-body h2,
.markdown-body h3,
.markdown-body h4 {
    margin-top: 18px;
    margin-bottom: 10px;
    font-weight: 700;
}

.markdown-body p {
    margin: 10px 0;
}

.markdown-body ul,
.markdown-body ol {
    padding-left: 24px;
    margin: 12px 0;
}

.markdown-body ul {
    list-style: disc;
}

.markdown-body ol {
    list-style: decimal;
}

.markdown-body li {
    margin: 6px 0;
}

.markdown-body blockquote {
    border-left: 4px solid #3b82f6;
    padding-left: 16px;
    color: #9ca3af;
    margin: 16px 0;
}

.markdown-body pre {
    background: #0d1117;
    border-radius: 10px;
    padding: 16px;
    overflow-x: auto;
    margin: 14px 0;
}

.markdown-body pre code {
    background: transparent;
    color: inherit;
    font-size: 14px;
}

.markdown-body code:not(pre code) {
    background: #1f2937;
    padding: 2px 6px;
    border-radius: 5px;
    color: #fbbf24;
}

.markdown-body table {
    width: 100%;
    border-collapse: collapse;
    margin: 16px 0;
    display: block;
    overflow-x: auto;
}

.markdown-body th,
.markdown-body td {
    border: 1px solid #374151;
    padding: 8px 12px;
}

.markdown-body th {
    background: #1f2937;
}

.markdown-body img {
    max-width: 100%;
    border-radius: 8px;
}

.markdown-body a {
    color: #60a5fa;
    text-decoration: none;
}

.markdown-body a:hover {
    text-decoration: underline;
}

.markdown-body hr {
    border: none;
    border-top: 1px solid #374151;
    margin: 20px 0;
}
        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, #EEF2FF, #C7D2FE);
        }

        body.dark {
            background: linear-gradient(135deg, #1E1B4B, #312E81);
        }

        .glass {
            backdrop-filter: blur(18px);
            -webkit-backdrop-filter: blur(18px);
            background: rgba(255, 255, 255, .75);
            border: 1px solid rgba(255, 255, 255, .2);
        }

        body.dark .glass {
            background: rgba(15, 23, 42, .85);
        }

        .chat-scroll::-webkit-scrollbar {
            width: 6px;
        }

        .chat-scroll::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 999px;
        }

        body.dark .chat-scroll::-webkit-scrollbar-thumb {
            background: #334155;
        }

        .message {
            animation: fade .3s ease;
        }

        @keyframes fade {
            from {
                opacity: 0;
                transform: translateY(20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .typing-dot {
            width: 8px;
            height: 8px;
            border-radius: 999px;
            background: #6366f1;
            animation: bounce 1.4s infinite;
        }

        .typing-dot:nth-child(2) {
            animation-delay: .2s;
        }

        .typing-dot:nth-child(3) {
            animation-delay: .4s;
        }

        @keyframes bounce {

            0%,
            80%,
            100% {
                transform: scale(.5);
                opacity: .5;
            }

            40% {
                transform: scale(1);
                opacity: 1;
            }
        }

        .chip {
            padding: 8px 15px;
            border-radius: 999px;
            background: white;
            font-size: 12px;
            font-weight: 600;
            transition: .3s;
            cursor: pointer;
            white-space: nowrap;
        }

        .chip:hover {
            background: #6366f1;
            color: white;
        }

        body.dark .chip {
            background: #1e293b;
            color: white;
        }
.bubble h1,
.bubble h2,
.bubble h3,
.bubble h4 {
    margin-top: 18px;
    margin-bottom: 10px;
    font-weight: 700;
}

.bubble h1 {
    font-size: 28px;
}

.bubble h2 {
    font-size: 22px;
}

.bubble h3 {
    font-size: 18px;
}

.bubble p {
    margin: 10px 0;
}

.bubble ul,
.bubble ol {
    margin: 10px 0;
    padding-left: 22px;
}

.bubble li {
    margin: 6px 0;
}

.bubble strong {
    font-weight: 700;
}

.bubble em {
    font-style: italic;
}

.bubble pre {
    background: #0b1220;
    border: 1px solid #1f2937;
    border-radius: 10px;
    padding: 14px;
    overflow-x: auto;
}

.bubble code {
    font-family: Consolas, Monaco, monospace;
}

.bubble :not(pre) > code {
    background: #1f2937;
    padding: 2px 5px;
    border-radius: 4px;
}

.bubble blockquote {
    border-left: 4px solid #3b82f6;
    margin: 12px 0;
    padding-left: 14px;
    color: #9ca3af;
}

.bubble table {
    width: 100%;
    border-collapse: collapse;
    margin: 16px 0;
}

.bubble th,
.bubble td {
    border: 1px solid #374151;
    padding: 8px;
}

.bubble th {
    background: #1f2937;
}

.bubble a {
    color: #60a5fa;
}

.bubble hr {
    border: none;
    border-top: 1px solid #374151;
    margin: 20px 0;
}
    </style>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>

<body>

    {{ $slot }}

    @livewireScripts
</body>
<script>
document.addEventListener('livewire:navigated', renderMarkdown);

document.addEventListener('livewire:update', renderMarkdown);

function renderMarkdown() {
    document.querySelectorAll('.markdown-body').forEach(el => {
        const markdown = el.dataset.markdown;

        if (!markdown) return;

        el.innerHTML = DOMPurify.sanitize(marked.parse(markdown));

        el.querySelectorAll('pre code').forEach(block => {
            hljs.highlightElement(block);
        });
    });
}
</script>

</html>
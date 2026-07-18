<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Livewire App</title>
    <script src="https://cdn.tailwindcss.com"></script>

    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap"
        rel="stylesheet">

    <style>
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
    document.addEventListener('livewire:init', () => {

        Livewire.on('generate-ai', () => {

            Livewire.dispatch('start-stream');

        });

    });
</script>

</html>
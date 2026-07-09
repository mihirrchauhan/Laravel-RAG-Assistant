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
            background: linear-gradient(135deg,#EEF2FF,#C7D2FE);
        }

        body.dark {
            background: linear-gradient(135deg,#1E1B4B,#312E81);
        }

        .glass {
            backdrop-filter: blur(18px);
            -webkit-backdrop-filter: blur(18px);
            background: rgba(255,255,255,.75);
            border:1px solid rgba(255,255,255,.2);
        }

        body.dark .glass{
            background: rgba(15,23,42,.85);
        }

        .chat-scroll::-webkit-scrollbar{
            width:6px;
        }

        .chat-scroll::-webkit-scrollbar-thumb{
            background:#cbd5e1;
            border-radius:999px;
        }

        body.dark .chat-scroll::-webkit-scrollbar-thumb{
            background:#334155;
        }

        .message{
            animation:fade .3s ease;
        }

        @keyframes fade{
            from{
                opacity:0;
                transform:translateY(20px);
            }
            to{
                opacity:1;
                transform:translateY(0);
            }
        }

        .typing-dot{
            width:8px;
            height:8px;
            border-radius:999px;
            background:#6366f1;
            animation:bounce 1.4s infinite;
        }

        .typing-dot:nth-child(2){
            animation-delay:.2s;
        }

        .typing-dot:nth-child(3){
            animation-delay:.4s;
        }

        @keyframes bounce{
            0%,80%,100%{
                transform:scale(.5);
                opacity:.5;
            }
            40%{
                transform:scale(1);
                opacity:1;
            }
        }

        .chip{
            padding:8px 15px;
            border-radius:999px;
            background:white;
            font-size:12px;
            font-weight:600;
            transition:.3s;
            cursor:pointer;
            white-space:nowrap;
        }

        .chip:hover{
            background:#6366f1;
            color:white;
        }

        body.dark .chip{
            background:#1e293b;
            color:white;
        }
    </style>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>
<body>

    {{ $slot }}

    @livewireScripts
</body>
</html>
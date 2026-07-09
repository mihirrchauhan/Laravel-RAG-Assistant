<div class="max-w-5xl mx-auto h-full md:h-[92vh] glass rounded-none md:rounded-[32px] shadow-2xl overflow-hidden flex flex-col">

    <!-- Header -->

    <div class="px-6 py-5 border-b flex items-center justify-between">

        <div class="flex items-center gap-4">

            <div
                class="w-12 h-12 rounded-2xl bg-indigo-600 text-white flex items-center justify-center shadow-xl">

                🤖

            </div>

            <div>

                <h2 class="font-bold text-xl text-slate-800 dark:text-white">
                    AI Assistant
                </h2>

                <p class="text-xs text-indigo-600 uppercase tracking-widest">
                    Active System
                </p>

            </div>

        </div>

        <div class="flex gap-2">

            <button id="themeBtn"
                class="h-11 w-11 rounded-xl bg-slate-100 hover:bg-slate-200 transition">

                🌙

            </button>

            <button
                onclick="window.location.reload()"
                class="h-11 w-11 rounded-xl bg-red-100 hover:bg-red-200 transition">

                🗑️

            </button>

        </div>

    </div>

    <!-- Chat Area -->

    <div
        id="chatBox"
        class="flex-1 overflow-y-auto chat-scroll px-6 py-6 space-y-6">
                @forelse($messages as $msg)

            <div
                class="message flex {{ $msg['role'] === 'user' ? 'justify-end' : 'justify-start' }}">

                @if($msg['role'] !== 'user')

                    <!-- AI Avatar -->
                    <div class="flex items-end mr-3">

                        <div
                            class="w-10 h-10 rounded-2xl bg-indigo-600 text-white flex items-center justify-center shadow-lg">

                            🤖

                        </div>

                    </div>

                @endif


                <!-- Message Bubble -->

                <div
                    class="max-w-[80%] rounded-3xl px-5 py-4 shadow-md

                    {{ $msg['role'] === 'user'
                        ? 'bg-indigo-600 text-white rounded-br-md'
                        : 'bg-white text-slate-700 border border-slate-200 rounded-bl-md dark:bg-slate-800 dark:text-slate-100 dark:border-slate-700'
                    }}">

                    @if($msg['role'] === 'assistant')

                        <div
                            class="leading-7 whitespace-pre-wrap break-words"
                            wire:stream="assistant-{{ $loop->index }}">

                            {{ trim($msg['content']) }}

                        </div>

                    @else

                        <div class="leading-7 whitespace-pre-wrap break-words">

                            {{ trim($msg['content']) }}

                        </div>

                    @endif

                    <div
                        class="mt-3 text-[11px] opacity-60">

                        {{ now()->format('h:i A') }}

                    </div>

                </div>


                @if($msg['role'] === 'user')

                    <!-- User Avatar -->

                    <div class="flex items-end ml-3">

                        <div
                            class="w-10 h-10 rounded-2xl bg-slate-700 text-white flex items-center justify-center shadow-lg">

                            👤

                        </div>

                    </div>

                @endif

            </div>

        @empty

            <!-- Welcome Screen -->

            <div class="flex flex-col items-center justify-center h-full py-24">

                <div
                    class="w-24 h-24 rounded-full bg-indigo-600 text-white text-4xl flex items-center justify-center shadow-xl">

                    🤖

                </div>

                <h2 class="mt-6 text-3xl font-bold text-slate-800 dark:text-white">

                    Welcome

                </h2>

                <p
                    class="mt-2 text-slate-500 dark:text-slate-400 text-center max-w-md">

                    Ask me anything. I can help with programming,
                    Laravel, AI, writing, debugging, documentation,
                    and much more.

                </p>

            </div>

        @endforelse


        <!-- Typing Indicator -->

        <div
            wire:loading.flex
            wire:target="sendMessage"
            class="items-center gap-3">

            <div
                class="w-10 h-10 rounded-2xl bg-indigo-600 text-white flex items-center justify-center">

                🤖

            </div>

            <div
                class="bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-3xl rounded-bl-md px-5 py-4 shadow">

                <div class="flex gap-2 items-center">

                    <span class="typing-dot"></span>
                    <span class="typing-dot"></span>
                    <span class="typing-dot"></span>

                    <span
                        class="text-xs text-slate-500 ml-2">

                        AI is thinking...

                    </span>

                </div>

            </div>

        </div>

    </div>
        <!-- Composer -->
    <div class="border-t border-slate-200 dark:border-slate-700 bg-white/80 dark:bg-slate-900/70 p-6">

        <div class="relative">

            <input
                id="messageInput"
                type="text"
                wire:model="message"
                wire:keydown.enter="sendMessage"
                wire:loading.attr="disabled"
                autocomplete="off"
                placeholder="Message AI Assistant..."
                class="w-full rounded-2xl border border-slate-300 dark:border-slate-700
                       bg-slate-100 dark:bg-slate-800
                       py-4 pl-6 pr-16
                       outline-none
                       focus:ring-2
                       focus:ring-indigo-500
                       dark:text-white">

            <button
                wire:click="sendMessage"
                wire:loading.attr="disabled"
                class="absolute right-2 top-2 h-12 w-12 rounded-xl
                       bg-indigo-600 hover:bg-indigo-700
                       text-white transition shadow-lg
                       flex items-center justify-center">

                ➤

            </button>

        </div>

        <!-- Quick Prompt Chips -->

        <div class="flex gap-3 mt-5 overflow-x-auto pb-2">

            <button
                class="chip"
                onclick="fillMessage('What can you do?')">

                🚀 Capabilities

            </button>

            <button
                class="chip"
                onclick="fillMessage('Explain Laravel Service Container')">

                💻 Laravel

            </button>

            <button
                class="chip"
                onclick="fillMessage('Write PHP code')">

                🐘 PHP

            </button>

            <button
                class="chip"
                onclick="fillMessage('Explain AI in simple words')">

                🤖 AI

            </button>

            <button
                class="chip"
                onclick="fillMessage('Tell me a joke')">

                😂 Joke

            </button>

        </div>

    </div>

</div>
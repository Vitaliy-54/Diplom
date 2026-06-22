<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight" id="current-section">
                {{ __('Уведомления') }}
            </h2>
            <div class="text-sm text-gray-800 dark:text-gray-400 animate__animated animate__fadeIn">
                {{ now()->translatedFormat('d F Y') }}
            </div>
        </div>
    </x-slot>

    <x-slot name="title">
        {{ __('Уведомления') }}
    </x-slot>

    <style>
        /* Убираем горизонтальный скролл */
        html,
        body {
            overflow-x: hidden;
            max-width: 100%;
        }

        /* Плавная анимация для разворачивания */
        .transition-all {
            transition: max-height 0.3s ease-in-out;
        }

        [id^="description-"] {
            max-height: 6rem;
            overflow: hidden;
        }

        /* Скрываем контент до загрузки */
        .hidden-until-loaded {
            opacity: 0;
            visibility: hidden;
            transition: opacity 0.5s ease-in-out, visibility 0.5s ease-in-out;
        }

        /* Показываем контент после загрузки */
        .loaded .hidden-until-loaded {
            opacity: 1;
            visibility: visible;
        }

        /* Убираем ограничение высоты для заголовка */
        .note-title {
            white-space: normal;
            word-wrap: break-word;
        }

        /* Стили для CKEditor */
        .ck-editor__editable {
    background-color: var(--ck-editor-bg, #ffffff) !important;
    color: var(--ck-editor-text, #000000) !important;
}

.ck-content {
    font-family: Arial, sans-serif;
    font-size: 14px;
    line-height: 1.6;
    color: var(--ck-content-text, #000000);
}

.ck-content p {
    margin-bottom: 1em;
}

.ck-content img {
    max-width: 100%;
    height: auto;
    border-radius: 4px;
}

.ck-content table {
    width: 100%;
    border-collapse: collapse;
    margin-bottom: 1em;
    overflow-x: auto;
    display: block;
}

.ck-content table,
.ck-content th,
.ck-content td {
    border: 1px solid var(--ck-table-border, #d1d5db);
}

.ck-content th,
.ck-content td {
    padding: 8px;
    text-align: left;
    background-color: var(--ck-table-bg, #f9fafb);
    color: var(--ck-table-text, #000000);
}

.ck-content a {
    color: var(--ck-link-color, #3b82f6);
    text-decoration: underline;
}

.ck-content a:hover {
    color: var(--ck-link-hover-color, #2563eb);
}

.ck-content blockquote {
    border-left: 4px solid var(--ck-blockquote-border, #d1d5db);
    padding-left: 1em;
    margin: 1em 0;
    color: var(--ck-blockquote-text, #4b5563);
}

.ck-content ul,
.ck-content ol {
    margin-bottom: 1em;
    padding-left: 2em;
}

.ck-content ul {
    list-style-type: disc;
}

.ck-content ol {
    list-style-type: decimal;
}

.ck-content h1,
.ck-content h2,
.ck-content h3,
.ck-content h4,
.ck-content h5,
.ck-content h6 {
    font-weight: bold;
    margin-top: 1.5em;
    margin-bottom: 0.5em;
    color: var(--ck-heading-text, #000000);
}

.ck-content h1 {
    font-size: 2em;
}

.ck-content h2 {
    font-size: 1.75em;
}

.ck-content h3 {
    font-size: 1.5em;
}

.ck-content h4 {
    font-size: 1.25em;
}

.ck-content h5 {
    font-size: 1em;
}

.ck-content h6 {
    font-size: 0.875em;
}

/* Стили для подсветки синтаксиса */
.ck-content pre {
    padding: 1em;
    border-radius: 4px;
    overflow-x: auto;
    background-color: var(--ck-pre-bg, #f3f4f6);
    color: var(--ck-pre-text, #000000);
}

.ck-content code {
    font-family: Consolas, Monaco, 'Andale Mono', 'Ubuntu Mono', monospace;
    font-size: 12px;
    color: var(--ck-code-text, #000000);
}

/* Стили для светлой темы */
:root {
    --ck-editor-bg: #ffffff;
    --ck-editor-text: #000000;
    --ck-content-text: #000000;
    --ck-table-border: #d1d5db;
    --ck-table-bg: #f9fafb;
    --ck-table-text: #000000;
    --ck-link-color: #3b82f6;
    --ck-link-hover-color: #2563eb;
    --ck-blockquote-border: #d1d5db;
    --ck-blockquote-text: #4b5563;
    --ck-heading-text: #000000;
    --ck-pre-bg: gray-200;
    --ck-pre-text: #000000;
    --ck-code-text:rgb(95, 95, 95);
}

/* Стили для темной темы */
.dark {
    --ck-editor-bg: #1f2937;
    --ck-editor-text:#f3f4f6;
    --ck-content-text: #f3f4f6;
    --ck-table-border: #4b5563;
    --ck-table-bg: #374151;
    --ck-table-text: #f3f4f6;
    --ck-link-color: #3b82f6;
    --ck-link-hover-color: #2563eb;
    --ck-blockquote-border: #4b5563;
    --ck-blockquote-text: #9ca3af;
    --ck-heading-text: #f3f4f6;
    --ck-pre-bg: gray-800;
    --ck-pre-text: #f3f4f6;
    --ck-code-text: #f3f4f6;
}

        /* Прокрутка для маленьких экранов */
        @media (max-width: 640px) {
            .ck-content table,
            .ck-content pre {
                width: 100%;
                overflow-x: auto;
                display: block;
            }
        }

        .notification-content {
            max-height: 300px;
            overflow: hidden;
            transition: max-height 0.5s ease;
        }

        .toggle-notification[hidden] {
            display: none;
        }
    </style>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Изменение фона на gray-400 в светлой теме -->
            <div class="bg-gray-300 dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <h1 class="text-2xl font-bold mb-4">{{ __('Ваши уведомления') }}</h1>

                    @if ($notifications->isEmpty())
                    <p>{{ __('У вас нет уведомлений.') }}</p>
                    @else
                    <div class="space-y-4 hidden-until-loaded">
                        @foreach ($notifications as $notification)
                        <div class="p-4 bg-gray-200 dark:bg-gray-700 rounded-lg">
                            <div class="flex justify-between items-center">
                                <div class="w-full overflow-x-auto">
                                    <!-- Отображаем HTML-сообщение -->
                                    <div class="text-sm text-gray-700 dark:text-gray-300">
                                        <div class="mb-1 text-gray-700 dark:text-gray-400">
                                            От: Admin
                                        </div>
                                        <!-- Контент CKEditor с ограничением высоты -->
                                        <div class="ck-content notification-content" style="max-height: 300px; overflow: hidden; transition: max-height 0.5s ease;">
                                            {!! $notification->data['message'] !!}
                                        </div>
                                        <!-- Кнопка "Развернуть" -->
                                        <div class="text-center mt-2 mb-2">
                                            <button class="text-blue-600 hover:text-blue-700 text-xs toggle-notification" hidden>
                                                <!-- Иконка стрелки вниз для "Развернуть" -->
                                                <svg class="h-4 w-4 inline-block transform transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                                </svg>
                                            </button>
                                        </div>
                                    </div>
                                    <!-- Кнопка "Пометить как прочитанное" -->
                                    @if ($notification->unread())
                                    <form method="POST" action="{{ route('admin.notifications.mark-as-read', $notification->id) }}">
                                        @csrf
                                        <button type="submit" class="text-blue-500 hover:text-blue-700 text-xs">
                                            {{ __('Пометить как прочитанное') }}
                                        </button>
                                    </form>
                                    @endif
                                    <small class="text-xs text-gray-700 dark:text-gray-400">
                                        {{ $notification->created_at->diffForHumans() }}
                                    </small>
                                </div>
                                <!-- Кнопка "Удалить" -->
                                <form method="POST" action="{{ route('admin.notifications.destroy', $notification->id) }}" onsubmit="return confirm('Вы уверены, что хотите удалить это уведомление?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-800">
                                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                        </svg>
                                    </button>
                                </form>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Подключение highlight.js для подсветки синтаксиса -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/highlight.js/11.7.0/styles/monokai-sublime.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/highlight.js/11.7.0/highlight.min.js" defer></script>
    <script defer>
        document.addEventListener('DOMContentLoaded', function () {
            // Инициализация highlight.js
            document.querySelectorAll('pre code').forEach((block) => {
                hljs.highlightBlock(block);
            });

            // Обработка кнопок "Развернуть" и "Свернуть"
            document.querySelectorAll('.toggle-notification').forEach((button) => {
                const content = button.closest('.p-4').querySelector('.notification-content');
                const icon = button.querySelector('svg');

                // Показываем кнопку только если контент превышает 300px
                if (content.scrollHeight > 300) {
                    button.removeAttribute('hidden');
                }

                let scrollPosition = 0; // Переменная для сохранения позиции прокрутки

                button.addEventListener('click', function () {
                    if (content.style.maxHeight === '300px') {
                        // Сохраняем текущую позицию прокрутки перед разворачиванием
                        scrollPosition = window.scrollY;

                        // Разворачиваем контент
                        content.style.maxHeight = content.scrollHeight + 'px';
                        icon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"></path>';
                    } else {
                        // Сворачиваем контент
                        // Сначала восстанавливаем позицию прокрутки
                        window.scrollTo({
                            top: scrollPosition,
                            behavior: 'smooth'
                        });

                        // Затем, через небольшой таймаут, сворачиваем контент
                        setTimeout(() => {
                            content.style.maxHeight = '300px';
                            icon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>';
                        }, 300); // Таймаут должен быть чуть больше времени анимации прокрутки
                    }
                });
            });

            // Показываем контент после загрузки
            document.body.classList.add('loaded');
        });
    </script>
</x-app-layout>
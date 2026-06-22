<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Редактирование уведомления') }}
        </h2>
    </x-slot>

    <x-slot name="title">
        {{ __('Редактирование уведомления') }}
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

        /* Стили для CKEditor */
        .ck-editor__editable {
            background-color: #1f2937 !important; /* Тёмный фон редактора */
            color: #f3f4f6 !important; /* Светлый текст */
        }
        .ck-content {
            font-family: Arial, sans-serif;
            font-size: 14px;
            line-height: 1.6;
            color: #f3f4f6; /* Цвет текста */
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
            overflow-x: auto; /* Прокрутка для таблиц */
            display: block; /* Для корректной работы прокрутки */
        }
        .ck-content table, .ck-content th, .ck-content td {
            border: 1px solid #4b5563; /* Тёмные границы */
        }
        .ck-content th, .ck-content td {
            padding: 8px;
            text-align: left;
        }
        .ck-content a {
            color: #3b82f6;
            text-decoration: underline;
        }
        .ck-content a:hover {
            color: #2563eb;
        }
        .ck-content blockquote {
            border-left: 4px solid #4b5563; /* Тёмные границы */
            padding-left: 1em;
            margin: 1em 0;
            color: #9ca3af; /* Светлый текст */
        }
        .ck-content ul, .ck-content ol {
            margin-bottom: 1em;
            padding-left: 2em;
        }
        .ck-content ul {
            list-style-type: disc;
        }
        .ck-content ol {
            list-style-type: decimal;
        }
        .ck-content h1, .ck-content h2, .ck-content h3, .ck-content h4, .ck-content h5, .ck-content h6 {
            font-weight: bold;
            margin-top: 1.5em;
            margin-bottom: 0.5em;
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
            background-color: #2d3748 !important; /* Тёмный фон для блоков кода */
            padding: 1em;
            border-radius: 4px;
            overflow-x: auto; /* Прокрутка для блоков кода */
        }
        .ck-content code {
            font-family: Consolas, Monaco, 'Andale Mono', 'Ubuntu Mono', monospace;
            font-size: 12px;
            color: #f3f4f6; /* Светлый текст */
        }
    </style>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-gray-300 dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100 hidden-until-loaded">
                    <h1 class="text-2xl font-bold mb-4">{{ __('Редактирование уведомления') }}</h1>

                    <!-- Форма редактирования уведомления -->
                    <form action="{{ route('admin.notifications.update', $notification) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <!-- Поле для редактирования сообщения -->
                        <div class="mb-4">
                            <label for="message" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                {{ __('Сообщение') }}
                            </label>
                            <textarea id="message" name="message" rows="4" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-300">{{ $notification->data['message'] }}</textarea>
                        </div>

                        <div class="flex items-center justify-between">
                            <button type="submit" class="bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                                {{ __('Сохранить изменения') }}
                            </button>
                            <a href="{{ route('admin.users.sent-notifications') }}" class="bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                                {{ __('Отмена') }}
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Подключение CKEditor -->
    <script src="https://cdn.ckeditor.com/4.22.1/standard-all/ckeditor.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Инициализация CKEditor
            CKEDITOR.replace('message', {
                skin: 'moono',
                height: 300,
                removePlugins: 'image',
                extraPlugins: 'image2,colorbutton,font,justify,showblocks,iframe,flash,smiley,pagebreak,templates,div,tableresize,codesnippet',
                toolbar: [
                    { name: 'clipboard', items: ['Undo', 'Redo', '-', 'Cut', 'Copy', '-', 'Templates'] },
                    '/',
                    { name: 'basicstyles', items: ['Bold', 'Italic', 'Underline', 'Strike', '-', 'RemoveFormat'] },
                    { name: 'paragraph', items: ['NumberedList', 'BulletedList', '-', 'JustifyLeft', 'JustifyCenter', 'JustifyRight', 'JustifyBlock'] },
                    { name: 'links', items: ['Link', 'Unlink'] },
                    { name: 'insert', items: ['Image', 'Table', 'HorizontalRule', 'Smiley', 'SpecialChar', 'Iframe', 'CodeSnippet'] },
                    '/',
                    { name: 'styles', items: ['Styles', 'Format', 'Font', 'FontSize'] },
                    { name: 'colors', items: ['TextColor', 'BGColor'] },
                    { name: 'tools', items: ['Maximize', 'ShowBlocks'] },
                ],
                contentsCss: 'body { font-family: Arial, sans-serif; font-size: 14px; }',
                codeSnippet_theme: 'monokai_sublime', // Тема для подсветки синтаксиса
                on: {
                    instanceReady: function(evt) {
                        evt.editor.on('notificationShow', function(evt) {
                            // Отключаем все предупреждения, связанные с версией
                            if (evt.data.message.includes('This CKEditor')) {
                                evt.cancel();
                            }
                        });
                    }
                }
            });

            // Инициализация highlight.js
            document.querySelectorAll('pre code').forEach((block) => {
                hljs.highlightBlock(block);
            });

            // Показываем контент после загрузки
            document.body.classList.add('loaded');
        });
    </script>

    <!-- Подключение highlight.js для подсветки синтаксиса -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/highlight.js/11.7.0/styles/monokai-sublime.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/highlight.js/11.7.0/highlight.min.js"></script>
</x-app-layout>
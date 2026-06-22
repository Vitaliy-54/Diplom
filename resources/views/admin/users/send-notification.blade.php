<!DOCTYPE html>
<html lang="ru" class="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ __('Отправить уведомление') }}</title>
    <!-- Подключение CKEditor 4.22.1 через CDN -->
    <script src="https://cdn.ckeditor.com/4.22.1/standard-all/ckeditor.js"></script>
    <!-- Подключение Tailwind CSS (если используется) -->
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <!-- Подключение highlight.js для подсветки синтаксиса -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/highlight.js/11.7.0/styles/monokai-sublime.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/highlight.js/11.7.0/highlight.min.js"></script>
    <!-- Стили для CKEditor и плавной загрузки -->
    <style>
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
            background-color: #1f2937 !important;
            color: #f3f4f6 !important;
        }
        .ck-content {
            font-family: Arial, sans-serif;
            font-size: 14px;
        }
        .dark .bg-white {
            background-color: #1f2937 !important;
        }
        .dark .text-gray-900 {
            color: #f3f4f6 !important;
        }
        .dark .border-gray-300 {
            border-color: #374151 !important;
        }
        .dark .bg-gray-100 {
            background-color: #111827 !important;
        }
    </style>
</head>
<body class="bg-gray-100 dark:bg-gray-900">
    <x-app-layout>
        <x-slot name="header">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Отправить уведомление пользователю') }}
            </h2>
        </x-slot>

        <x-slot name="title">
            {{ __('Отправить уведомление') }}
        </x-slot>

        <div class="py-6 px-4">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-gray-300 dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900 dark:text-gray-100 hidden-until-loaded">
                        <form method="POST" action="{{ route('admin.users.send-notification') }}" enctype="multipart/form-data">
                            @csrf

                            <input type="hidden" name="send_to_all" value="0">
                            <div class="mb-4">
                                <label class="inline-flex items-center">
                                    <input type="checkbox" id="send_to_all" name="send_to_all" value="1" class="form-checkbox h-5 w-5 text-blue-600 dark:text-blue-500">
                                    <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">{{ __('Отправить всем пользователям') }}</span>
                                </label>
                            </div>

                            <div class="mb-4" id="users_list">
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                    {{ __('Выберите пользователей') }}
                                </label>
                                <div class="mt-2 max-h-64 overflow-y-auto border border-gray-400 dark:border-gray-600 rounded-md p-2">
                                    @foreach($users as $user)
                                        <label class="flex items-center w-full mb-2">
                                            <input type="checkbox" name="user_ids[]" value="{{ $user->id }}" class="form-checkbox h-5 w-5 text-blue-600 dark:text-blue-500">
                                            <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">{{ $user->name }} ({{ $user->email }})</span>
                                        </label>
                                    @endforeach
                                </div>
                            </div>

                            <div class="mb-4">
                                <label for="message" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                    {{ __('Сообщение') }}
                                </label>
                                <textarea id="message" name="message" rows="4" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-100" required></textarea>
                            </div>

                            <div class="flex items-center justify-between">
                                <button type="submit" class="bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                                    {{ __('Отправить') }}
                                </button>
                                <a href="{{ route('admin.index') }}" class="bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                                    {{ __('Отмена') }}
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <script>
            document.addEventListener('DOMContentLoaded', function () {
                const form = document.querySelector('form');
                const sendToAllCheckbox = document.getElementById('send_to_all');
                const usersList = document.getElementById('users_list');

                form.addEventListener('submit', function (event) {
                    let isValid = true;

                    // Проверка, что текст сообщения не пустой
                    const messageContent = CKEDITOR.instances.message.getData();
                    if (messageContent.trim() === '') {
                        isValid = false;
                        alert('Введите текст сообщения.');
                    }

                    // Проверка, что выбраны получатели
                    if (!sendToAllCheckbox.checked) {
                        const selectedUsers = usersList.querySelectorAll('input[type="checkbox"]:checked');
                        if (selectedUsers.length === 0) {
                            isValid = false;
                            alert('Выберите хотя бы одного пользователя.');
                        }
                    }

                    // Если форма невалидна, отменяем отправку
                    if (!isValid) {
                        event.preventDefault();
                    }
                });

                // Обработка изменения состояния чекбокса "Отправить всем"
                sendToAllCheckbox.addEventListener('change', function () {
                    if (this.checked) {
                        usersList.style.display = 'none';
                    } else {
                        usersList.style.display = 'block';
                    }
                });

                // Инициализация CKEditor с новой конфигурацией
                CKEDITOR.replace('message', {
                    skin: 'moono',
                    height: 300,
                    removePlugins: 'image',
                    extraPlugins: 'image2,colorbutton,font,justify,showblocks,iframe,flash,smiley,pagebreak,templates,div,tableresize,codesnippet',
                    toolbar: [
                        { name: 'clipboard', items: ['Undo', 'Redo', '-', 'Cut', 'Copy', '-', 'Templates','CodeSnippet'] },
                        '/',
                        { name: 'basicstyles', items: ['Bold', 'Italic', 'Underline', 'Strike', '-', 'RemoveFormat'] },
                        { name: 'paragraph', items: ['NumberedList', 'BulletedList', '-', 'JustifyLeft', 'JustifyCenter', 'JustifyRight', 'JustifyBlock'] },
                        { name: 'links', items: ['Link', 'Unlink'] },
                        { name: 'insert', items: ['Image', 'Table', 'HorizontalRule', 'Smiley', 'SpecialChar', 'Iframe'] },
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

                // Показываем контент после загрузки
                document.body.classList.add('loaded');
            });
        </script>
    </x-app-layout>
</body>
</html>
<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Редактирование заметки') }}
            </h2>
            <div class="text-sm text-gray-800 dark:text-gray-400 animate__animated animate__fadeIn">
                {{ now()->translatedFormat('d F Y') }}
            </div>
        </div>
    </x-slot>

    <x-slot name="title">
        {{ __('Редактирование заметки') }}
    </x-slot>

    <!-- Подключение CKEditor -->
    <script src="https://cdn.ckeditor.com/4.22.1/standard-all/ckeditor.js"></script>
    <!-- Подключение highlight.js -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/highlight.js/11.7.0/styles/monokai-sublime.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/highlight.js/11.7.0/highlight.min.js"></script>
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Стили для страницы -->
    @assets
    <style>
        /* Основные стили */
        .hidden-until-loaded {
            opacity: 0;
            visibility: hidden;
            transition: opacity 0.5s ease-in-out, visibility 0.5s ease-in-out;
        }

        .loaded .hidden-until-loaded {
            opacity: 1;
            visibility: visible;
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

        /* Прокрутка для маленьких экранов */
        @media (max-width: 640px) {
            .ck-content table,
            .ck-content pre {
                width: 100%;
                overflow-x: auto;
                display: block;
            }
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
            --ck-code-text: rgb(95, 95, 95);
        }

        /* Стили для темной темы */
        .dark {
            --ck-editor-bg: #1f2937;
            --ck-editor-text: #f3f4f6;
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

        /* Стили для тегов */
        .tags-container {
            border: 1px solid var(--tags-border-color,rgb(169, 170, 172));
            border-radius: 0.5rem;
            padding: 1rem;
            background-color: var(--tags-bg-color, #f3f4f6);
            transition: all 0.2s ease;
        }

        .dark .tags-container {
            --tags-border-color: #4b5563;
            --tags-bg-color: #1f2937;
        }

        .tags-container.border-red-500 {
            border-color: #ef4444;
            box-shadow: 0 0 0 1px rgba(239, 68, 68, 0.2);
        }

        /* Стили для выпадающего списка тегов */
        #tags-dropdown {
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
        }

        .dark #tags-dropdown {
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.3), 0 2px 4px -1px rgba(0, 0, 0, 0.2);
            color: white;
        }

        /* Стили для выбранных тегов */
        .tag-chip {
            transition: all 0.2s ease;
        }

        .tag-chip:hover {
            transform: translateY(-1px);
        }

        /* Анимации */
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(5px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .fade-in {
            animation: fadeIn 0.3s ease-out forwards;
        }

        button[type="submit"]:disabled {
            opacity: 0.7;
            cursor: not-allowed;
        }

        /* Стили для индикатора загрузки */
        #upload-progress-container {
            transition: opacity 0.3s ease;
        }

        #upload-progress-container.show {
            display: block;
            opacity: 1;
        }

        #upload-progress-bar {
            transition: width 0.3s ease;
        }

        @keyframes spin {
            from { transform: rotate(0deg); }
            to { transform: rotate(360deg); }
        }

        .animate-spin {
            animation: spin 1s linear infinite;
        }
        
        /* Стили для удаляемых файлов */
        .file-to-delete {
            opacity: 0.5;
            border-color: #ef4444 !important;
        }

        /* Скрываем контент до загрузки */
        .hidden-until-loaded {
            opacity: 0;
            visibility: hidden;
            transition: opacity 0.7s ease-in-out, visibility 0.5s ease-in-out;
        }

        /* Показываем контент после загрузки */
        .loaded .hidden-until-loaded {
            opacity: 1;
            visibility: visible;
        }
    </style>
    @endassets

    @php
    function formatFileSize($bytes) {
        if ($bytes === 0) return '0 Bytes';
        $k = 1024;
        $sizes = ['Bytes', 'KB', 'MB', 'GB'];
        $i = floor(log($bytes) / log($k));
        return round($bytes / pow($k, $i), 2) . ' ' . $sizes[$i];
    }
    @endphp

    <div class="py-6 px-4">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 overflow-hidden hidden-until-loaded">
            <div class="bg-gray-300 dark:bg-gray-800 shadow-lg rounded-lg overflow-hidden transition-all duration-200">
                <div class="p-6">
                    <form action="{{ route('notes.update', $note) }}" method="POST" enctype="multipart/form-data" id="note-form">
                        @csrf
                        @method('PUT')
                        
                        <!-- Поле "Заголовок" -->
                        <div class="mb-6">
                            <label for="title" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                {{ __('Заголовок') }} <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="title" id="title" value="{{ old('title', $note->title) }}"
                                placeholder="{{ __('Введите название заметки') }}"
                                class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-lime-500 focus:border-lime-500 transition-shadow shadow-sm border-gray-400 dark:bg-gray-700 dark:border-gray-600 dark:text-white dark:focus:ring-lime-600 dark:focus:border-lime-600"
                                required>
                            @error('title')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Поле "Описание" с CKEditor -->
                        <div class="mb-6">
                            <label for="description" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                {{ __('Описание') }} <span class="text-red-500">*</span>
                            </label>
                            <textarea name="description" id="description" rows="6" class="hidden">{{ old('description', $note->description) }}</textarea>
                            <div id="editor">{!! old('description', $note->description) !!}</div>
                            @error('description')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Поле "Теги" -->
                        <div class="mb-8">
                            <div class="tags-container">
                                <label for="tags" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    {{ __('Теги') }} <span class="text-red-500">*</span>
                                    <span class="text-xs text-gray-500 dark:text-gray-400 ml-1">(Выберите из списка или создайте новые)</span>
                                </label>
                                
                                <!-- Основной select для тегов -->
                                <select name="tags[]" id="tags" multiple class="hidden">
                                    @foreach($tags as $tag)
                                        <option value="{{ $tag->id }}" {{ in_array($tag->id, old('tags', $note->tags->pluck('id')->toArray())) ? 'selected' : '' }}>{{ $tag->name }}</option>
                                    @endforeach
                                </select>

                                <!-- Контейнер для выбранных тегов -->
                                <div id="selected-tags-container" class="flex flex-wrap gap-2 mb-3 min-h-10">
                                    @foreach($note->tags as $tag)
                                        <div class="flex items-center bg-blue-600 dark:bg-blue-900 text-white dark:text-blue-100 px-3 py-1 rounded-full text-sm tag-chip">
                                            {{ $tag->name }}
                                            <button type="button" data-id="{{ $tag->id }}" class="ml-2 text-white hover:text-white dark:text-blue-500 dark:hover:text-blue-300">
                                                <i class="fas fa-times text-xs"></i>
                                            </button>
                                        </div>
                                    @endforeach
                                </div>

                                <div class="flex flex-col sm:flex-row gap-3">
                                    <!-- Поле для ввода нового тега -->
                                    <div class="relative flex-1">
                                        <input type="text" id="tags-input" placeholder="Введите название нового тега"
                                            class="w-full px-4 py-2 rounded-lg border border-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-shadow shadow-sm dark:bg-gray-800 dark:border-gray-600 dark:text-white dark:focus:ring-blue-600 dark:focus:border-blue-600">
                                    </div>
                                    
                                    <button type="button" id="add-tag-btn"
                                        class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-all duration-200 shadow-sm whitespace-nowrap">
                                        <i class="fas fa-plus mr-2"></i>Добавить тег
                                    </button>
                                </div>
                                
                                <!-- Блок с доступными тегами -->
                                <div class="mt-4">
                                    <h4 class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Доступные теги:</h4>
                                    <div class="flex flex-wrap gap-2">
                                        @foreach($tags as $tag)
                                            <button type="button" 
                                                class="tag-option {{ in_array($tag->id, old('tags', $note->tags->pluck('id')->toArray())) ? 'bg-blue-300 dark:bg-blue-900' : 'bg-gray-200 dark:bg-gray-700' }} hover:bg-gray-300 dark:hover:bg-gray-600 text-gray-800 dark:text-gray-200 px-3 py-1 rounded-full text-sm transition-colors"
                                                data-id="{{ $tag->id }}"
                                                onclick="toggleTag(this, {{ $tag->id }}, '{{ $tag->name }}')">
                                                {{ $tag->name }}
                                                <span class="tag-check ml-1 {{ in_array($tag->id, old('tags', $note->tags->pluck('id')->toArray())) ? '' : 'hidden' }}">
                                                    <i class="fas fa-check text-xs text-green-700 dark:text-green-500"></i>
                                                </span>
                                            </button>
                                        @endforeach
                                    </div>
                                </div>
                                
                                @error('tags')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- Поле для загрузки файлов -->
                        <div class="mb-6">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                {{ __('Файлы') }}
                                <span class="text-xs text-gray-500 dark:text-gray-400 ml-1">(Необязательное поле)</span>
                            </label>
                        
                            <!-- Существующие файлы -->
                            <div id="existing-files" class="mb-4 grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-3">
                                @foreach($note->files as $file)
                                <div class="flex items-center p-2 border border-gray-400 dark:border-gray-600 rounded-lg">
                                    <div class="h-12 w-12 flex items-center justify-center bg-gray-100 dark:bg-gray-700 rounded">
                                        <i class="{{ $file->getFileIcon() }} text-2xl text-gray-500 dark:text-gray-400"></i>
                                    </div>
                                    <div class="ml-3 flex-1 min-w-0">
                                        <p class="text-sm font-medium text-gray-900 dark:text-white truncate">{{ $file->name }}</p>
                                        <p class="text-xs text-gray-500 dark:text-gray-400">{{ $file->getFormattedSize() }}</p>
                                    </div>
                                    <button type="button" 
                                            class="file-remove-btn text-red-500 hover:text-red-700 dark:text-red-400 dark:hover:text-red-300 ml-2" 
                                            onclick="removeExistingFile(this, {{ $file->id }})" 
                                            title="Удалить файл">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                </div>
                                @endforeach
                            </div>
                            
                            <!-- Загрузка новых файлов -->
                            <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-400 dark:border-gray-600 border-dashed rounded-md">
                                <div class="space-y-1 text-center">
                                    <svg class="mx-auto h-12 w-12 text-gray-500 dark:text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48" aria-hidden="true">
                                        <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                    </svg>
                                    <div class="text-sm text-gray-600 dark:text-gray-400">
                                        <label for="files" class="relative cursor-pointer bg-gray-300 dark:bg-gray-800 rounded-md font-medium text-lime-600 dark:text-lime-600 hover:text-lime-500 dark:hover:text-lime-500 focus-within:outline-none">
                                            <span>{{ __('Загрузить файлы') }}</span>
                                            <input id="files" name="files[]" type="file" multiple class="sr-only">
                                        </label>
                                    </div>
                                    <p class="text-xs text-gray-500 dark:text-gray-400">
                                        {{ __('PNG, JPG, PDF, DOCX, XLSX и другие') }}
                                    </p>
                                    <p class="text-xs text-gray-500 dark:text-gray-400">
                                        {{ __('Максимальный размер всех файлов загружаемых пользователем на сайт 150МБ') }}
                                    </p>
                                     <p class="text-xs text-gray-500 dark:text-gray-400">
                                        {{ __('При редактировании заметки, размер добавленных в заметку файлов не должен превышать 20МБ') }}
                                    </p>
                                </div>
                            </div>
                            
                            <!-- Предпросмотр новых файлов -->
                            <div id="file-preview" class="mt-4 grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-3 hidden"></div>
                            
                            <!-- Скрытое поле для удаляемых файлов -->
                            <input type="hidden" name="deleted_files" id="deleted-files" value="">
                            
                            @error('files.*')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Информация о хранилище -->
                        <div class="mb-6 bg-gray-200 dark:bg-gray-700 border border-gray-500 dark:border-gray-600 p-4 rounded-lg">
                            <div class="flex justify-between items-center mb-1">
                                <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Использовано хранилища:</span>
                                <span class="text-sm font-medium text-gray-700 dark:text-gray-300" id="storage-used">{{ formatFileSize(auth()->user()->getTotalStorageUsed()) }}</span>
                            </div>
                            <div class="w-full bg-gray-300 rounded-full h-2.5 dark:bg-gray-600">
                            <div id="storage-progress" class="bg-blue-600 h-2.5 rounded-full" 
                                style="width: {{ min(100, (auth()->user()->getTotalStorageUsed() / (150 * 1024 * 1024)) * 100) }}%"></div>
                            </div>
                            <div class="flex justify-between mt-1">
                                <span class="text-xs text-gray-500 dark:text-gray-400">0 MB</span>
                                <span class="text-xs text-gray-500 dark:text-gray-400">150 MB</span>
                            </div>
                        </div>

                        <!-- Чекбокс "Публичная заметка" -->
                        <div class="mb-6">
                            <label for="is_public" class="flex items-center">
                                <input type="checkbox" name="is_public" id="is_public" value="1" {{ old('is_public', $note->is_public) ? 'checked' : '' }} class="w-5 h-5 rounded border-gray-300 text-blue-600 focus:ring-blue-500 shadow-sm transition-colors dark:bg-gray-700 dark:border-gray-600 dark:focus:ring-blue-600">
                                <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">
                                    {{ __('Публичная заметка (заметка будет видна всем пользователям)') }}
                                </span>
                            </label>
                        </div>

                        <!-- Индикатор загрузки -->
                        <div id="upload-progress-container" class="hidden mb-4">
                            <div class="flex justify-between items-center mb-1">
                                <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Отправка данных...</span>
                                <span class="text-sm font-medium text-gray-700 dark:text-gray-300" id="upload-progress-percent">0%</span>
                            </div>
                            <div class="w-full bg-gray-200 rounded-full h-2.5 dark:bg-gray-600">
                                <div id="upload-progress-bar" class="bg-lime-600 h-2.5 rounded-full" style="width: 0%"></div>
                            </div>
                        </div>

                        <!-- Кнопки действий -->
                        <div class="flex flex-col sm:flex-row justify-end space-y-3 sm:space-y-0 sm:space-x-3">
                            <a href="{{ route('notes.index') }}" class="inline-flex items-center justify-center px-6 py-3 border border-transparent text-sm font-medium rounded-lg shadow-sm text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition duration-200">
                                {{ __('Отмена') }}
                            </a>
                            <button type="submit" class="inline-flex items-center justify-center px-6 py-3 border border-transparent text-sm font-medium rounded-lg shadow-sm text-white bg-lime-600 hover:bg-lime-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition duration-200">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                                </svg>
                                {{ __('Обновить заметку') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        // Инициализация CKEditor
        CKEDITOR.replace('editor', {
            skin: 'moono',
            height: 300,
            removePlugins: 'image',
            extraPlugins: 'image2,colorbutton,font,justify,showblocks,iframe,flash,smiley,pagebreak,templates,div,tableresize,codesnippet',
            toolbar: [
                { name: 'clipboard', items: ['Undo', 'Redo', '-', 'Cut', 'Copy', '-', 'Templates', 'CodeSnippet'] },
                '/',
                { name: 'basicstyles', items: ['Bold', 'Italic', 'Underline', 'Strike', '-', 'RemoveFormat'] },
                { name: 'paragraph', items: ['NumberedList', 'BulletedList', '-', 'JustifyLeft', 'JustifyCenter', 'JustifyRight', 'JustifyBlock'] },
                { name: 'links', items: ['Link', 'Unlink'] },
                { name: 'insert', items: ['Image', 'Table', 'HorizontalRule', 'Smiley', 'SpecialChar', 'Iframe'] },
                '/',
                { name: 'styles', items: ['Styles', 'Format', 'Font', 'FontSize'] },
                { name: 'colors', items: ['TextColor', 'BGColor'] },
                { name: 'tools', items: ['Maximize', 'ShowBlocks'] }
            ],
            contentsCss: 'body { font-family: Arial, sans-serif; font-size: 14px; }',
            codeSnippet_theme: 'monokai_sublime',
            on: {
                instanceReady: function(evt) {
                    evt.editor.on('notificationShow', function(evt) {
                        if (evt.data.message.includes('This CKEditor')) {
                            evt.cancel();
                        }
                    });
                }
            }
        });

        // Показать контент после загрузки
        document.body.classList.add('loaded');

        // Элементы DOM для работы с тегами
        const tagSelect = document.getElementById('tags');
        const tagsInput = document.getElementById('tags-input');
        const selectedTagsContainer = document.getElementById('selected-tags-container');
        const addTagBtn = document.getElementById('add-tag-btn');
        const tagsContainer = document.querySelector('.tags-container');
        
        // Все доступные теги (в нижнем регистре для сравнения)
        const allTags = @json($tags->map(fn($tag) => [
            'id' => $tag->id, 
            'name' => $tag->name,
            'lowerName' => strtolower($tag->name)
        ]));

        // Глобальная функция для переключения тега
        window.toggleTag = function(button, tagId, tagName) {
            const option = Array.from(tagSelect.options).find(opt => opt.value == tagId);
            
            if (option) {
                option.selected = !option.selected;
                updateTagAppearance(tagId, option.selected);
                renderSelectedTags();
            }
        };

        // Обновление внешнего вида тега
        function updateTagAppearance(tagId, isSelected) {
            const tagButton = document.querySelector(`.tag-option[data-id="${tagId}"]`);
            if (tagButton) {
                const checkIcon = tagButton.querySelector('.tag-check');
                
                if (isSelected) {
                    tagButton.classList.add('bg-blue-300', 'dark:bg-blue-900');
                    tagButton.classList.remove('bg-gray-200', 'dark:bg-gray-700');
                    checkIcon.classList.remove('hidden');
                } else {
                    tagButton.classList.remove('bg-blue-300', 'dark:bg-blue-900');
                    tagButton.classList.add('bg-gray-200', 'dark:bg-gray-700');
                    checkIcon.classList.add('hidden');
                }
            }
        }

        // При загрузке страницы отмечаем выбранные теги
        function markSelectedTags() {
            const selectedOptions = Array.from(tagSelect.selectedOptions);
            
            document.querySelectorAll('.tag-option').forEach(button => {
                const tagId = button.dataset.id;
                const isSelected = selectedOptions.some(opt => opt.value == tagId);
                updateTagAppearance(tagId, isSelected);
            });
        }
        
        // Отображаем выбранные теги
        function renderSelectedTags() {
            selectedTagsContainer.innerHTML = '';
            const selectedOptions = Array.from(tagSelect.selectedOptions);
            
            if (selectedOptions.length === 0) {
                selectedTagsContainer.innerHTML = '<span class="text-red-600 dark:text-red-500 text-xxs">Теги не выбраны</span>';
                return;
            }
            
            selectedOptions.forEach(option => {
                const tagElement = document.createElement('div');
                tagElement.className = 'flex items-center bg-blue-600 dark:bg-blue-900 text-white dark:text-blue-100 px-3 py-1 rounded-full text-sm tag-chip fade-in';
                tagElement.innerHTML = `
                    ${option.text}
                    <button type="button" data-id="${option.value}" class="ml-2 text-white hover:text-white dark:text-blue-500 dark:hover:text-blue-300">
                        <i class="fas fa-times text-xs"></i>
                    </button>
                `;
                selectedTagsContainer.appendChild(tagElement);
            });
        }
        
        // Проверяем, существует ли тег (без учета регистра)
        function tagExists(tagName) {
            const lowerTagName = tagName.toLowerCase();
            return allTags.some(tag => tag.lowerName === lowerTagName);
        }

        async function createNewTag(tagName) {
            if (!tagName) return;
            
            // Проверяем, не существует ли уже такой тег
            if (tagExists(tagName)) {
                showToast('Такой тег уже существует', 'error');
                return;
            }
            
            try {
                const response = await fetch('{{ route("tags.store") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({ name: tagName })
                });
                
                const data = await response.json();
                
                if (data.success) {
                    // Добавляем новый тег в список
                    const newTag = {
                        id: data.tag.id,
                        name: data.tag.name,
                        lowerName: data.tag.name.toLowerCase()
                    };
                    allTags.push(newTag);
                    
                    // 1. Добавляем option в select
                    const option = new Option(data.tag.name, data.tag.id, true, true);
                    tagSelect.appendChild(option);
                    
                    // 2. Добавляем кнопку тега в список доступных тегов
                    const availableTagsContainer = document.querySelector('.mt-4 .flex.flex-wrap.gap-2');
                    const newTagButton = document.createElement('button');
                    newTagButton.type = 'button';
                    newTagButton.className = 'tag-option bg-blue-300 dark:bg-blue-900 hover:bg-blue-400 dark:hover:bg-blue-800 text-gray-800 dark:text-gray-200 px-3 py-1 rounded-full text-sm transition-colors';
                    newTagButton.dataset.id = data.tag.id;
                    newTagButton.setAttribute('onclick', `toggleTag(this, ${data.tag.id}, '${data.tag.name.replace(/'/g, "\\'")}')`);
                    newTagButton.innerHTML = `
                        ${data.tag.name}
                        <span class="tag-check ml-1">
                            <i class="fas fa-check text-xs text-green-500"></i>
                        </span>
                    `;
                    
                    // Вставляем новый тег в начало списка доступных тегов
                    availableTagsContainer.insertBefore(newTagButton, availableTagsContainer.firstChild);
                    
                    // 3. Обновляем отображение выбранных тегов
                    updateTagAppearance(data.tag.id, true);
                    renderSelectedTags();
                    tagsInput.value = '';
                    
                    showToast('Тег успешно создан', 'success');
                } else {
                    showToast(data.message || 'Ошибка при создании тега', 'error');
                }
            } catch (error) {
                console.error('Error:', error);
                showToast('Произошла ошибка при создании тега', 'error');
            }
        }
        
        // Обработчик кнопки "Добавить тег"
        addTagBtn.addEventListener('click', () => {
            const tagName = tagsInput.value.trim();
            if (tagName) {
                createNewTag(tagName);
            } else {
                tagsInput.focus();
            }
        });

        // Добавление тега по нажатию Enter
        tagsInput.addEventListener('keypress', (e) => {
            if (e.key === 'Enter') {
                e.preventDefault();
                const tagName = tagsInput.value.trim();
                if (tagName) {
                    createNewTag(tagName);
                }
            }
        });

        // Удаление выбранного тега
        selectedTagsContainer.addEventListener('click', (e) => {
            const removeBtn = e.target.closest('button[data-id]');
            if (removeBtn) {
                const tagId = removeBtn.dataset.id;
                const option = Array.from(tagSelect.options).find(opt => opt.value == tagId);
                if (option) {
                    option.selected = false;
                    updateTagAppearance(tagId, false);
                    renderSelectedTags();
                }
            }
        });
        
        // Обработка загрузки файлов
        const fileInput = document.getElementById('files');
        const filePreview = document.getElementById('file-preview');
        const existingFilesContainer = document.getElementById('existing-files');
        const deletedFilesInput = document.getElementById('deleted-files');
        let allFiles = [];
        let deletedFiles = [];

        fileInput.addEventListener('change', function() {
            Array.from(this.files).forEach(file => {
                if (!file) return;
                
                const isAlreadyAdded = allFiles.some(f => 
                    f.name === file.name && 
                    f.size === file.size && 
                    f.lastModified === file.lastModified
                );
                
                if (!isAlreadyAdded) {
                    allFiles.push(file);
                    
                    const preview = createFilePreview(file);
                    filePreview.appendChild(preview);
                    filePreview.classList.remove('hidden');
                }
            });
            
            updateFileInput();
            updateStorageInfo();
        });

               // Функция для создания превью файла
        function createFilePreview(file) {
            const preview = document.createElement('div');
            preview.className = 'flex items-center p-2 border border-gray-400 dark:border-gray-600 rounded-lg mb-2';
            
            let previewContent = '';
            if (file.type.startsWith('image/')) {
                previewContent = `
                    <img src="${URL.createObjectURL(file)}" class="h-12 w-12 object-cover rounded" alt="${file.name}">
                `;
            } else {
                const icon = getFileIcon(file);
                previewContent = `
                    <div class="h-12 w-12 flex items-center justify-center bg-gray-100 dark:bg-gray-700 rounded">
                        <i class="${icon} text-2xl text-gray-500 dark:text-gray-400"></i>
                    </div>
                `;
            }
            
            preview.innerHTML = `
                ${previewContent}
                <div class="ml-3 flex-1 min-w-0">
                    <p class="text-sm font-medium text-gray-900 dark:text-white truncate">${file.name}</p>
                    <p class="text-xs text-gray-500 dark:text-gray-400">${formatFileSize(file.size)}</p>
                </div>
                <button type="button" class="file-remove-btn text-red-500 hover:text-red-700 dark:text-red-400 dark:hover:text-red-300 ml-2" 
                        onclick="removeFilePreview(this, '${file.name}', ${file.size}, ${file.lastModified})" 
                        title="Удалить файл">
                    <i class="fas fa-trash-alt"></i>
                </button>
            `;
            
            return preview;
        }

        // Функция для обновления input files
        function updateFileInput() {
            const dataTransfer = new DataTransfer();
            allFiles.forEach(file => dataTransfer.items.add(file));
            fileInput.files = dataTransfer.files;
        }

        // Глобальная функция для удаления файла из предпросмотра
        window.removeFilePreview = function(button, fileName, fileSize, fileLastModified) {
            const fileIndex = allFiles.findIndex(f => 
                f.name === fileName && 
                f.size === fileSize && 
                f.lastModified === fileLastModified
            );
            
            if (fileIndex !== -1) {
                allFiles.splice(fileIndex, 1);
                button.closest('div').remove();
                updateFileInput();
                updateStorageInfo();
                
                if (allFiles.length === 0) {
                    filePreview.classList.add('hidden');
                }
            }
        };

 // Обновление информации о хранилище
function updateStorageInfo() {
    const totalNewSize = allFiles.reduce((sum, file) => sum + file.size, 0);
    const usedStorage = {{ auth()->user()->getTotalStorageUsed() }};
    const totalSize = usedStorage + totalNewSize;
    const maxSize = 157286400; // 150MB in bytes
    
    // Форматируем размер для отображения
    const formatSize = (bytes) => {
        if (bytes === 0) return '0 Bytes';
        const k = 1024;
        const sizes = ['Bytes', 'KB', 'MB', 'GB'];
        const i = Math.floor(Math.log(bytes) / Math.log(k));
        return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
    };
    
    document.getElementById('storage-used').textContent = formatSize(totalSize);
    
    const progressPercent = Math.min(100, (totalSize / maxSize) * 100);
    const progressBar = document.getElementById('storage-progress');
    progressBar.style.width = `${progressPercent}%`;
    
    if (progressPercent > 90) {
        progressBar.classList.remove('bg-blue-600');
        progressBar.classList.add('bg-red-500');
    } else {
        progressBar.classList.remove('bg-red-500');
        progressBar.classList.add('bg-blue-600');
    }
    
    // Блокируем кнопку отправки, если превышен лимит
    const submitBtn = document.querySelector('button[type="submit"]');
    if (totalSize > maxSize) {
        submitBtn.disabled = true;
        submitBtn.classList.remove('bg-lime-600', 'hover:bg-lime-700');
        submitBtn.classList.add('bg-gray-400', 'cursor-not-allowed');
        showToast('Превышен лимит хранилища (150МБ)', 'error');
    } else {
        submitBtn.disabled = false;
        submitBtn.classList.add('bg-lime-600', 'hover:bg-lime-700');
        submitBtn.classList.remove('bg-gray-400', 'cursor-not-allowed');
    }
}

// Функция для удаления существующего файла
window.removeExistingFile = function(button, fileId) {
    // Добавляем ID файла в массив удаляемых
    if (!deletedFiles.includes(fileId)) {
        deletedFiles.push(fileId);
        deletedFilesInput.value = JSON.stringify(deletedFiles);
    }
    
    // Добавляем класс для визуального обозначения удаления
    const fileDiv = button.closest('div');
    fileDiv.classList.add('opacity-35', 'border-red-400', 'dark:border-red-400');
    
    // Делаем кнопку неактивной
    button.disabled = true;
};

        // Вспомогательные функции
        function getFileIcon(file) {
            if (file.type.startsWith('image/')) return 'far fa-image';
            if (file.type.includes('pdf')) return 'far fa-file-pdf';
            if (file.type.includes('word') || file.type.includes('document')) return 'far fa-file-word';
            if (file.type.includes('excel') || file.type.includes('spreadsheet')) return 'far fa-file-excel';
            if (file.type.includes('zip') || file.type.includes('compressed')) return 'far fa-file-archive';
            return 'far fa-file';
        }

        function formatFileSize(bytes) {
            if (bytes === 0) return '0 Bytes';
            const k = 1024;
            const sizes = ['Bytes', 'KB', 'MB', 'GB'];
            const i = Math.floor(Math.log(bytes) / Math.log(k));
            return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
        }

        // Функция для показа уведомлений
        function showToast(message, type = 'info') {
            const toast = document.createElement('div');
            toast.className = `fixed bottom-4 right-4 px-4 py-2 rounded-lg shadow-lg text-white ${
                type === 'error' ? 'bg-red-500' : 
                type === 'success' ? 'bg-green-500' : 'bg-blue-500'
            } flex items-center fade-in`;
            toast.innerHTML = `
                <i class="fas ${
                    type === 'error' ? 'fa-exclamation-circle' : 
                    type === 'success' ? 'fa-check-circle' : 'fa-info-circle'
                } mr-2"></i>
                ${message}
            `;
            document.body.appendChild(toast);
            
            setTimeout(() => {
                toast.classList.add('opacity-0', 'transition-opacity', 'duration-300');
                setTimeout(() => toast.remove(), 300);
            }, 3000);
        }

// Валидация формы перед отправкой
const form = document.getElementById('note-form');
form.addEventListener('submit', function(event) {
    const editorData = CKEDITOR.instances.editor.getData();
    document.getElementById('description').value = editorData;

    // Проверка обязательных полей
    if (editorData.trim() === '') {
        event.preventDefault();
        showToast('Введите описание заметки', 'error');
        return;
    }

    if (tagSelect.selectedOptions.length === 0) {
        event.preventDefault();
        tagsContainer.classList.add('border-red-500');
        showToast('Выберите хотя бы один тег', 'error');
        return;
    } else {
        tagsContainer.classList.remove('border-red-500');
    }

    // Показываем индикатор загрузки
    const progressContainer = document.getElementById('upload-progress-container');
    const progressBar = document.getElementById('upload-progress-bar');
    const progressPercent = document.getElementById('upload-progress-percent');
    
    progressContainer.classList.remove('hidden');
    progressBar.style.width = '0%';
    progressPercent.textContent = '0%';

    // Отключаем кнопку отправки
    const submitBtn = form.querySelector('button[type="submit"]');
    submitBtn.disabled = true;
    submitBtn.innerHTML = `
        <svg class="animate-spin -ml-1 mr-2 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
        </svg>
        Отправка...
    `;

    // Создаем XMLHttpRequest для отслеживания прогресса
    const xhr = new XMLHttpRequest();
    const formData = new FormData(form);

    xhr.open('POST', form.action, true);
    xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');

    xhr.upload.onprogress = function(e) {
        if (e.lengthComputable) {
            const percentComplete = Math.round((e.loaded / e.total) * 100);
            progressBar.style.width = percentComplete + '%';
            progressPercent.textContent = percentComplete + '%';
            
            if (percentComplete === 100) {
                progressPercent.textContent = 'Обработка...';
            }
        }
    };

    xhr.onload = function() {
    if (xhr.status >= 200 && xhr.status < 300) {
        // Перенаправление на /notes
        window.location.href = '/notes';
    } else {
        // В случае ошибки
        progressContainer.classList.add('hidden');
        submitBtn.disabled = false;
        submitBtn.innerHTML = `
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
            </svg>
            {{ __('Добавить заметку') }}
        `;
        showToast('Произошла ошибка при отправке данных', 'error');
    }
};

    xhr.onerror = function() {
        progressContainer.classList.add('hidden');
        submitBtn.disabled = false;
        submitBtn.innerHTML = `
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
            </svg>
            {{ __('Добавить заметку') }}
        `;
        showToast('Ошибка соединения', 'error');
    };

    xhr.send(formData);
    
    // Отменяем стандартную отправку формы
    event.preventDefault();
});

        // Инициализация при загрузке страницы
        renderSelectedTags();
        markSelectedTags();
    });
    </script>
</x-app-layout>
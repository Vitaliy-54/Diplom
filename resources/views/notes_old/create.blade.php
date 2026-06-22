<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Создание заметки') }}
            </h2>
            <div class="text-sm text-gray-800 dark:text-gray-400 animate__animated animate__fadeIn">
                {{ now()->translatedFormat('d F Y') }}
            </div>
        </div>
    </x-slot>

    <x-slot name="title">
        {{ __('Создание заметки') }}
    </x-slot>

    <!-- Подключение CKEditor 4.22.1 через CDN -->
    <script src="https://cdn.ckeditor.com/4.22.1/standard-all/ckeditor.js"></script>
    <!-- Подключение highlight.js для подсветки синтаксиса -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/highlight.js/11.7.0/styles/monokai-sublime.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/highlight.js/11.7.0/highlight.min.js"></script>
    <!-- Подключение Font Awesome для иконок -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Подключение Tailwind CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/tailwindcss/2.2.19/tailwind.min.css">

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
            background-color: var(--tags-bg-color, #ffffff);
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
    </style>
    @endassets

    <div class="py-6 px-4">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-gray-300 dark:bg-gray-800 shadow-lg rounded-lg overflow-hidden transition-all duration-200">
                <div class="p-6 hidden-until-loaded">
                    <form action="{{ route('notes.store') }}" method="POST">
                        @csrf
                        
                        <!-- Поле "Заголовок" -->
                        <div class="mb-6">
                            <label for="title" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                {{ __('Заголовок') }} <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="title" id="title" value="{{ old('title') }}"
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
                            <textarea name="description" id="description" rows="6" class="hidden">{{ old('description') }}</textarea>
                            <div id="editor">{!! old('description') !!}</div>
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
                <option value="{{ $tag->id }}" {{ in_array($tag->id, old('tags', [])) ? 'selected' : '' }}>{{ $tag->name }}</option>
            @endforeach
        </select>

        <!-- Контейнер для выбранных тегов -->
        <div id="selected-tags-container" class="flex flex-wrap gap-2 mb-3 min-h-10">
            <!-- Здесь будут отображаться выбранные теги -->
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
                        class="tag-option bg-gray-200 dark:bg-gray-700 hover:bg-gray-300 dark:hover:bg-gray-600 text-gray-800 dark:text-gray-200 px-3 py-1 rounded-full text-sm transition-colors"
                        data-id="{{ $tag->id }}"
                        onclick="toggleTag(this, {{ $tag->id }}, '{{ $tag->name }}')">
                        {{ $tag->name }}
                        <span class="tag-check ml-1 hidden">
                            <i class="fas fa-check text-xs text-green-500"></i>
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


                        <!-- Чекбокс "Публичная заметка" -->
                        <div class="mb-6">
                            <label for="is_public" class="flex items-center">
                                <input type="checkbox" name="is_public" id="is_public" value="1" {{ old('is_public') ? 'checked' : '' }} class="w-5 h-5 rounded border-gray-300 text-lime-600 focus:ring-lime-500 shadow-sm transition-colors dark:bg-gray-700 dark:border-gray-600 dark:focus:ring-lime-600">
                                <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">
                                    {{ __('Публичная заметка (заметка будет видна всем пользователям)') }}
                                </span>
                            </label>
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
                                {{ __('Добавить заметку') }}
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
        toolbar: [{
                name: 'clipboard',
                items: ['Undo', 'Redo', '-', 'Cut', 'Copy', '-', 'Templates', 'CodeSnippet']
            },
            '/',
            {
                name: 'basicstyles',
                items: ['Bold', 'Italic', 'Underline', 'Strike', '-', 'RemoveFormat']
            },
            {
                name: 'paragraph',
                items: ['NumberedList', 'BulletedList', '-', 'JustifyLeft', 'JustifyCenter', 'JustifyRight', 'JustifyBlock']
            },
            {
                name: 'links',
                items: ['Link', 'Unlink']
            },
            {
                name: 'insert',
                items: ['Image', 'Table', 'HorizontalRule', 'Smiley', 'SpecialChar', 'Iframe']
            },
            '/',
            {
                name: 'styles',
                items: ['Styles', 'Format', 'Font', 'FontSize']
            },
            {
                name: 'colors',
                items: ['TextColor', 'BGColor']
            },
            {
                name: 'tools',
                items: ['Maximize', 'ShowBlocks']
            },
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
    
    // Валидация при отправке формы
    const form = document.querySelector('form');
    form.addEventListener('submit', function(event) {
        const editorData = CKEDITOR.instances.editor.getData();
        document.getElementById('description').value = editorData;

        if (editorData.trim() === '') {
            event.preventDefault();
            showToast('Введите описание заметки', 'error');
        }

        if (tagSelect.selectedOptions.length === 0) {
            event.preventDefault();
            tagsContainer.classList.add('border-red-500');
            showToast('Выберите хотя бы один тег', 'error');
        } else {
            tagsContainer.classList.remove('border-red-500');
        }
    });
    
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

    // Инициализация при загрузке страницы
    renderSelectedTags();
    markSelectedTags();
    document.body.classList.add('loaded');
});
</script>
</x-app-layout>
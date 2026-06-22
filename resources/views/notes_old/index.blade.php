<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight" id="current-section">
                {{ __('Все заметки') }}
            </h2>
            <div class="text-sm text-gray-800 dark:text-gray-400">{{ now()->format('d.m.Y') }}</div>
        </div>
    </x-slot>

    <x-slot name="title">
        {{ __('Заметки') }}
    </x-slot>

    <!-- Подключение необходимых библиотек -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/highlight.js/11.7.0/styles/monokai-sublime.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/highlight.js/11.7.0/highlight.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

    <style>
        /* Убираем горизонтальный скролл */
        html,
        body {
            overflow-x: hidden;
            max-width: 100%;
        }

        /* Добавляем вертикальную полосу прокрутки всегда */
        html {
            overflow-y: scroll;
        }

        /* Плавная анимация для разворачивания */
        .transition-all {
            transition: max-height 0.3s ease-in-out;
        }

        /* Скрываем описание по умолчанию */
        [id^="description-"] {
            max-height: 0;
            overflow: hidden;
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

        /* Убираем ограничение высоты для заголовка */
        .note-title {
            white-space: normal;
            word-wrap: break-word;
        }

        /* Стиль для ссылки на заголовок */
        .note-title a {
            color: inherit;
            text-decoration: none;
        }

        .note-title a:hover {
            text-decoration: underline;
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

        /* Стили для блоков кода */
        .ck-content pre {
            position: relative;
            border-radius: 4px;
            margin-top: 1rem;
            overflow-x: auto;
            background-color: var(--ck-pre-bg, #f3f4f6);
            color: var(--ck-pre-text, #000000);
        }

        .ck-content code {
            display: block;
            padding: 0;
            font-family: Consolas, Monaco, 'Andale Mono', 'Ubuntu Mono', monospace;
            font-size: 12px;
            color: var(--ck-code-text, #000000);
        }

        /* Стили для кнопки копирования (всегда видимой) */
        .copy-button {
            position: absolute;
            top: 10px;
            right: 10px;
            border: none;
            cursor: pointer;
            font-size: 14px;
            width: 28px;
            height: 28px;
            border-radius: 4px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            transition: all 0.2s ease;
        }

        .copy-button:hover {
            background: rgba(255, 255, 255, 0.3);
        }

        .copy-button.success {
            color: #4ade80;
        }

        /* Темная тема для кнопки копирования */
        .dark .copy-button {
            color: #f3f4f6;
        }

        .dark .copy-button:hover {
            background: rgba(255, 255, 255, 0.3);
        }

        .dark .copy-button.success {
            color: #4ade80;
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
            --ck-code-text: #f3f4f6;
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

        .toggle-note[hidden] {
            display: none;
        }

        /* Стили для табов */
        .tabs {
            display: flex;
            margin-bottom: 1rem;
            border-bottom: 1px solid #d1d5db;
        }

        .tab {
            padding: 0.5rem 1rem;
            cursor: pointer;
            border-bottom: 2px solid transparent;
            transition: all 0.3s ease;
        }

        .tab:hover {
            color: #3b82f6;
            border-bottom-color: #93c5fd;
        }

        .tab.active {
            color: #3b82f6;
            border-bottom-color: #3b82f6;
            font-weight: 500;
        }

        .dark .tabs {
            border-bottom-color: #4b5563;
        }

        .dark .tab:hover {
            color: #60a5fa;
            border-bottom-color: #60a5fa;
        }

        .dark .tab.active {
            color: #60a5fa;
            border-bottom-color: #60a5fa;
        }

        .dark .tab {
            color: rgb(207, 206, 206);
        }

        /* Стили для описания заметки с плавной анимацией */
        [id^="description-"] {
            max-height: 0;
            overflow: hidden;
            transition: max-height 0.3s ease-in-out;
        }

        /* Стили для контейнера заметок */
        #notes-container {
            transition: opacity 0.3s ease;
        }

        /* Стили для кнопки переключения */
        .toggle-button {
            transition: all 0.3s ease;
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

        /* Индикатор загрузки */
        .loading-indicator {
            display: none;
            text-align: center;
            padding: 10px;
            color: #666;
            font-style: italic;
        }
        
        /* Индикатор загрузки вверху */
        #loading-indicator-top {
            margin-bottom: 1rem;
        }
        
        /* Индикатор загрузки внизу */
        #loading-indicator-bottom {
            margin-top: 1rem;
        }
        
        .dark .loading-indicator {
            color: #9ca3af;
        }
    </style>

    <div class="py-6 px-4 overflow-hidden hidden-until-loaded">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Кнопка создания новой заметки -->
            <div class="mb-6">
                <a href="{{ route('notes.create') }}"
                    class="inline-block px-4 py-2 bg-lime-600 text-white rounded-lg hover:bg-lime-700 transition duration-300 text-sm sm:text-base sm:px-6 sm:py-2">
                    {{ __('Создать новую заметку') }}
                </a>
            </div>

            <!-- Табы для переключения между разделами -->
            <div class="tabs mb-6">
                <div class="tab active" data-tab="all" onclick="switchTab('all')">
                    {{ __('Все заметки') }}
                </div>
                <div class="tab" data-tab="my" onclick="switchTab('my')">
                    {{ __('Мои заметки') }}
                </div>
                @if (auth()->user()->isAdmin())
                <div class="tab" data-tab="private" onclick="switchTab('private')">
                    {{ __('Приватные заметки') }}
                </div>
                @endif
            </div>

            <!-- Форма поиска и фильтрации -->
            <div class="mb-6 bg-gray-400 dark:bg-gray-800 p-4 rounded-lg">
                <form id="search-form" class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <!-- Поле поиска -->
                    <div>
                        <label for="search" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Поиск по заголовку</label>
                        <input type="text" name="search" id="search" value="{{ $search ?? '' }}"
                            class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white"
                            placeholder="Введите текст...">
                    </div>

                    <!-- Фильтр по тегам -->
                    <div>
                        <label for="tags" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Фильтр по тегам</label>
                        <select name="tag" id="tags"
                            class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white">
                            <option value="">Все теги</option>
                            @foreach($allTags as $tag)
                            <option value="{{ $tag->id }}" {{ request('tag') == $tag->id ? 'selected' : '' }}>{{ $tag->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Удален блок сортировки -->
                    <input type="hidden" name="sort" value="created_at_desc">
                </form>
                
                <!-- Добавьте этот блок с кнопкой сброса -->
                <div class="justify-center mt-3">
                    <button type="button" id="reset-filters"
                        class="px-3 py-2 bg-gray-500 text-white rounded-lg hover:bg-gray-600 transition duration-300 text-sm">
                        <i class="fas fa-redo mr-2"></i> Сбросить фильтры
                    </button>
                </div>
            </div>

            <!-- Информационный блок -->
            <div id="info-block" class="mb-6 p-4 bg-gray-400 dark:bg-gray-800 rounded-lg text-sm text-gray-800 dark:text-gray-300">
                <p>Вы можете открыть заметку, нажав на её заголовок, или кликнув на значок, расположенный под заголовком, чтобы отобразить содержимое заметки. Также у вас есть возможность изменить или удалить свою заметку.</p>
            </div>

            <!-- Индикатор загрузки вверху (только для первой загрузки) -->
            <div id="loading-indicator-top" class="loading-indicator">
                <i class="fas fa-spinner fa-spin mr-2"></i> Загрузка заметок...
            </div>

            <!-- Список заметок -->
            <div class="space-y-6" id="notes-container">
                @include('notes.partials.notes_list', ['notes' => $notes])
            </div>

            <!-- Индикатор загрузки внизу (для подгрузки новых заметок) -->
            <div id="loading-indicator-bottom" class="loading-indicator" style="display: none;">
                <i class="fas fa-spinner fa-spin mr-2"></i> Загрузка дополнительных заметок...
            </div>
        </div>
    </div>

    <script>
        // Текущая активная вкладка
        let activeTab = 'all';

        // ID текущего пользователя
        const currentUserId = @json(auth() -> id());

        // Таймер для задержки запроса при изменении фильтров
        let filterTimeout;
        let choicesInstance;
        let isLoading = false;

        // Функция для показа индикатора загрузки
        function showLoading(isInitialLoad = false) {
            if (isInitialLoad) {
                document.getElementById('loading-indicator-top').style.display = 'block';
            } else {
                document.getElementById('loading-indicator-bottom').style.display = 'block';
            }
        }

        // Функция для скрытия индикатора загрузки
        function hideLoading() {
            document.getElementById('loading-indicator-top').style.display = 'none';
            document.getElementById('loading-indicator-bottom').style.display = 'none';
        }

        // Функция для переключения вкладок
        function switchTab(tab) {
    if (activeTab === tab) return;
    activeTab = tab;

     // Сбрасываем фильтр тегов
     document.getElementById('tags').value = '';

    // Обновляем заголовок
    const headerMap = {
        'all': 'Все заметки',
        'my': 'Мои заметки',
        'private': 'Приватные заметки  всех пользователей'
    };
    document.getElementById('current-section').textContent = headerMap[tab] || 'Все заметки';

    // Обновляем стили табов
    document.querySelectorAll('.tab').forEach(tabElement => {
        tabElement.classList.toggle('active', tabElement.dataset.tab === tab);
    });

    loadFilteredNotes();
}

 // В функции loadFilteredNotes
async function loadFilteredNotes() {
    if (isLoading) return;
    isLoading = true;

    const container = document.getElementById('notes-container');
    const tagsSelect = document.getElementById('tags');
    const currentTag = tagsSelect.value;

    // Показываем верхний индикатор только при первой загрузке
    const isInitialLoad = container.innerHTML.trim() === '';
    if (isInitialLoad) {
        showLoading(true);
    }

    // Плавно скрываем контейнер перед загрузкой (если это не первая загрузка)
    if (!isInitialLoad) {
        container.style.opacity = '0';
        container.style.transition = 'opacity 0.3s ease';
        await new Promise(resolve => setTimeout(resolve, 300));
    }

    try {
        const form = document.getElementById('search-form');
        const formData = new FormData(form);
        formData.append('tab', activeTab);  // Добавляем текущую активную вкладку
        formData.append('sort', 'created_at_desc');

        const params = new URLSearchParams(formData).toString();

                // Используем относительный URL с текущим протоколом
        const url = `{{ route('notes.index') }}?${params}`.replace(/^http:/, window.location.protocol);

        const response = await fetch(url, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            },
            credentials: 'same-origin' // Добавляем куки в запрос
        });


        if (!response.ok) throw new Error('Network response was not ok');

        const data = await response.json();

        // Скрываем индикатор загрузки сразу после получения ответа
        hideLoading();

        if (data.html) {
            container.innerHTML = data.html;
            nextPageUrl = data.next_page;
            activeTab = data.tab || 'all'; // Обновляем активную вкладку

            // Обновляем список тегов в фильтре
            if (data.allTags) {
                tagsSelect.innerHTML = '<option value="">Все теги</option>';

                data.allTags.forEach(tag => {
                    const option = document.createElement('option');
                    option.value = tag.id;
                    option.textContent = tag.name;
                    option.selected = (currentTag == tag.id);
                    tagsSelect.appendChild(option);
                });
            }

            initCopyButtons();
            collapseAllNotes();
        }
    } catch (error) {
        console.error('Error loading notes:', error);
        container.innerHTML = '<div class="p-4 bg-red-100 text-red-700 rounded-lg mb-6">Ошибка загрузки заметок</div>';
        hideLoading();
    } finally {
        // Плавно показываем контейнер после загрузки
        container.style.opacity = '1';
        container.style.transition = 'opacity 0.3s ease';

        isLoading = false;
    }
}

        // Функция для переключения описания заметки
        function toggleDescription(noteId) {
            const description = document.getElementById(`description-${noteId}`);
            const toggleButton = document.getElementById(`toggle-button-${noteId}`);
            const icon = toggleButton.querySelector('i');

            // Сохраняем позицию прокрутки в атрибуте кнопки
            if (!toggleButton.dataset.scrollPosition) {
                toggleButton.dataset.scrollPosition = window.scrollY;
            }

            if (description.style.maxHeight === "0px" || !description.style.maxHeight) {
                // Показываем полное описание
                description.style.maxHeight = `${description.scrollHeight}px`;
                icon.classList.remove('fa-angle-double-down');
                icon.classList.add('fa-angle-double-up');
                toggleButton.title = "Свернуть";

                // Инициализируем кнопки копирования после раскрытия
                initCopyButtons(description);
            } else {
                // Восстанавливаем позицию прокрутки перед сворачиванием
                const scrollPosition = parseInt(toggleButton.dataset.scrollPosition);
                window.scrollTo({
                    top: scrollPosition,
                    behavior: 'smooth'
                });

                // Сворачиваем описание после завершения прокрутки
                setTimeout(() => {
                    description.style.maxHeight = "0px";
                    icon.classList.remove('fa-angle-double-up');
                    icon.classList.add('fa-angle-double-down');
                    toggleButton.title = "Развернуть";
                }, 300);

                // Очищаем сохранённую позицию прокрутки
                delete toggleButton.dataset.scrollPosition;
            }
        }

        // Функция для сворачивания всех заметок
        function collapseAllNotes() {
            const descriptions = document.querySelectorAll('[id^="description-"]');
            const toggleButtons = document.querySelectorAll('[id^="toggle-button-"]');

            descriptions.forEach(description => {
                description.style.maxHeight = "0px";
            });

            toggleButtons.forEach(button => {
                const icon = button.querySelector('i');
                icon.classList.remove('fa-angle-double-up');
                icon.classList.add('fa-angle-double-down');
                button.title = "Развернуть";
            });
        }

        function initCopyButtons(container = document) {
            container.querySelectorAll('pre code').forEach((block) => {
                if (block.parentNode.querySelector('.copy-button')) return;

                hljs.highlightElement(block);

                const copyButton = document.createElement('button');
                copyButton.innerHTML = '<i class="far fa-copy"></i>';
                copyButton.className = 'copy-button';
                copyButton.title = 'Копировать код';
                block.parentNode.appendChild(copyButton);

                copyButton.addEventListener('click', (e) => {
                    e.stopPropagation();
                    const code = block.innerText;

                    if (navigator.clipboard) {
                        navigator.clipboard.writeText(code).then(() => {
                            showCopySuccess(copyButton);
                        }).catch(() => {
                            fallbackCopy(code, copyButton);
                        });
                    } else {
                        fallbackCopy(code, copyButton);
                    }
                });
            });
        }

        function fallbackCopy(text, button) {
            const textarea = document.createElement('textarea');
            textarea.value = text;
            textarea.style.position = 'fixed';
            document.body.appendChild(textarea);
            textarea.select();

            try {
                document.execCommand('copy');
                showCopySuccess(button);
            } catch (err) {
                console.error('Ошибка при копировании:', err);
                // Можно показать пользователю сообщение об ошибке
                button.innerHTML = '<i class="fas fa-times"></i>';
                setTimeout(() => {
                    button.innerHTML = '<i class="far fa-copy"></i>';
                }, 2000);
            } finally {
                document.body.removeChild(textarea);
            }
        }

        function showCopySuccess(button) {
            button.innerHTML = '<i class="fas fa-check"></i>';
            button.classList.add('success');
            setTimeout(() => {
                button.innerHTML = '<i class="far fa-copy"></i>';
                button.classList.remove('success');
            }, 2000);
        }

        // Добавляем в конец скрипта
        let isScrolling = false;
        let nextPageUrl = null;


        // Функция для загрузки следующей страницы
        async function loadNextPage() {
    if (!nextPageUrl || isScrolling) return;

    isScrolling = true;
    showLoading(false);

    try {
        // Добавляем текущую вкладку в URL
       const url = new URL(nextPageUrl.replace(/^http:/, window.location.protocol));
        url.searchParams.set('tab', activeTab);
        
        const response = await fetch(url, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            }
        });

        if (!response.ok) throw new Error('Network response was not ok');

        const data = await response.json();

        if (data.html) {
            const container = document.getElementById('notes-container');
            const tempDiv = document.createElement('div');
            tempDiv.innerHTML = data.html;
            
            container.insertAdjacentHTML('beforeend', data.html);
            initCopyButtons(tempDiv);
            nextPageUrl = data.next_page;
            
            // Обновляем активную вкладку
            if (data.tab) {
                activeTab = data.tab;
            }
        }
    } catch (error) {
        console.error('Error loading next page:', error);
    } finally {
        isScrolling = false;
        hideLoading();
    }
}

        // Обработчик скролла для бесконечной прокрутки
        function handleScroll() {
            const {
                scrollTop,
                scrollHeight,
                clientHeight
            } = document.documentElement;
            const scrollPosition = scrollTop + clientHeight;

            // Загружаем следующую страницу, когда пользователь прокрутил до 70% страницы
            if (scrollPosition > scrollHeight * 0.7) {
                loadNextPage();
            }
        }

        // При загрузке страницы
        document.addEventListener('DOMContentLoaded', function() {
            const tagsSelect = document.getElementById('tags');

            document.getElementById('reset-filters').addEventListener('click', function() {
                // Сброс поля поиска
                document.getElementById('search').value = '';

                // Сброс выбора тегов
                document.getElementById('tags').value = '';

                // Загрузка заметок с сброшенными фильтрами
                loadFilteredNotes();
            });

            document.getElementById('tags').addEventListener('change', function() {
                clearTimeout(filterTimeout);
                filterTimeout = setTimeout(loadFilteredNotes, 500);
            });

            // Обработчики изменений для полей поиска и сортировки
            document.getElementById('search').addEventListener('input', function() {
                clearTimeout(filterTimeout);
                filterTimeout = setTimeout(loadFilteredNotes, 500);
            });

            // Инициализация кнопок копирования
            initCopyButtons();

            // Показываем контент после загрузки
            document.body.classList.add('loaded');

            // Инициализируем бесконечную прокрутку
            nextPageUrl = document.querySelector('.pagination a[rel="next"]')?.href;
            window.addEventListener('scroll', handleScroll);

            // Обновляем nextPageUrl при фильтрации
            document.getElementById('search-form').addEventListener('submit', function(e) {
                e.preventDefault();
                loadFilteredNotes().then(() => {
                    nextPageUrl = document.querySelector('.pagination a[rel="next"]')?.href;
                });
            });
        });
    </script>
</x-app-layout>
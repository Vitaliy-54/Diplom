<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ 'Просмотр заметки' }}
            </h2>
            <div class="text-sm text-gray-800 dark:text-gray-400 animate__animated animate__fadeIn">
                {{ now()->translatedFormat('d F Y') }}
            </div>
        </div>
    </x-slot>

    <x-slot name="title">
        {{ 'Просмотр заметки' }}
    </x-slot>

    <!-- Подключение внешних ресурсов -->
    <script src="https://cdn.ckeditor.com/4.22.1/standard-all/ckeditor.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/highlight.js/11.7.0/styles/monokai-sublime.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/highlight.js/11.7.0/highlight.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/mammoth/1.4.14/mammoth.browser.min.js"></script>

    <style>
        /* Убираем горизонтальный скролл */
        html, body {
            overflow-x: hidden;
            max-width: 100%;
        }

        html {
            overflow-y: scroll;
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

        .ck-content:after {
            content: "";
            display: table;
            clear: both;
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
            margin-top: 0.5rem;
            overflow-x: auto;
            background-color: var(--ck-pre-bg, #f3f4f6);
            color: var(--ck-pre-text, #000000);
        }

        .ck-content code {
            display: block;
            padding: 0;
            font-family: Consolas, Monaco, 'Andale Mono', 'Ubuntu Mono', monospace;
            font-size: 12px;
            color: var(--ck-code-text, #f3f4f6);
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

        /* Стили для цветных реакций */
        .reaction-btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 0.5rem 0.7rem;
            border-radius: 9999px;
            background-color: transparent;
            transition: all 0.2s ease;
            border: none;
            cursor: pointer;
            color: #6b7280;
        }

        .reaction-btn.active.like { color: #3b82f6; }
        .reaction-btn.active.dislike { color: #ef4444; }
        .reaction-btn.active.heart { color: #ec4899; }
        .reaction-btn.active.laugh { color: #f59e0b; }
        .reaction-btn.active.wow { color: #10b981; }

        .reaction-btn:hover.like { color: #3b82f6; }
        .reaction-btn:hover.dislike { color: #ef4444; }
        .reaction-btn:hover.heart { color: #ec4899; }
        .reaction-btn:hover.laugh { color: #f59e0b; }
        .reaction-btn:hover.wow { color: #10b981; }

        .dark .reaction-btn { color: #9ca3af; }
        .dark .reaction-btn.active.like { color: #60a5fa; }
        .dark .reaction-btn.active.dislike { color: #f87171; }
        .dark .reaction-btn.active.heart { color: #f472b6; }
        .dark .reaction-btn.active.laugh { color: #fbbf24; }
        .dark .reaction-btn.active.wow { color: #34d399; }
        .dark .reaction-btn:hover.like { color: #60a5fa; }
        .dark .reaction-btn:hover.dislike { color: #f87171; }
        .dark .reaction-btn:hover.heart { color: #f472b6; }
        .dark .reaction-btn:hover.laugh { color: #fbbf24; }
        .dark .reaction-btn:hover.wow { color: #34d399; }

        .count {
            font-size: 0.875rem;
            font-weight: 500;
            margin-left: 0.25rem;
        }

        /* Стили для тултипа с пользователями */
        .reaction-tooltip {
            position: absolute;
            bottom: 100%;
            left: 50%;
            transform: translateX(-50%);
            background-color: white;
            border: 1px solid #e5e7eb;
            border-radius: 0.5rem;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
            z-index: 10;
            min-width: 160px;
            max-width: 90%;
            max-height: 50vh;
            overflow-y: auto;
            display: none;
        }
        
        .dark .reaction-tooltip {
            background-color: #374151;
            border-color: #4b5563;
        }
        
        /* Тултип показываем только на десктопе при наведении */
        @media (min-width: 769px) {
            .reaction-group:hover .reaction-tooltip {
                display: block;
            }
        }
        
        .reaction-title {
            font-weight: bold;
            text-align: center;
            font-size: 1.0rem;
            color: rgb(57, 57, 58);
            border-bottom: 1px solid rgb(165, 165, 170);
            padding: 8px;
            position: sticky;
            top: 0;
            background-color: inherit;
            z-index: 1;
        }
        
        .dark .reaction-title {
            color: rgb(204, 204, 206);
            border-bottom-color: rgb(128, 128, 129);
        }
        
        .users-list {
            padding: 8px;
            flex-direction: column;
            gap: 0.5rem;
            max-height: calc(50vh - 45px);
            overflow-y: auto;
        }
        
        .user-item {
            display: flex;
            flex-direction: column;
            border-radius: 0.25rem;
            font-size: 0.875rem;
            padding: 0.15rem;
            color: #374151;
        }
        
        .dark .user-item { color: #f3f4f6; }
        
        .user-name {
            font-weight: 500;
            color: rgb(26, 26, 26);
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .dark .user-name { color: rgb(224, 221, 221); }
        
        .reaction-date {
            font-size: 0.75rem;
            color: rgb(26, 26, 26);
        }
        
        .dark .reaction-date { color: #9ca3af; }

        .info-btn {
            background: none;
            border: none;
            color: #6b7280;
            cursor: pointer;
            font-size: 0.7rem;
            margin-top: 4px;
            display: none;
            padding: 4px 8px;
            border-radius: 20px;
            transition: all 0.2s ease;
        }

        .info-btn:hover {
            color: #10b981;
            background-color: rgba(16, 185, 129, 0.1);
        }
        
        /* Показываем кнопки информации только на мобильных устройствах */
        @media (max-width: 768px) {
            .info-btn {
                display: flex;
                align-items: center;
                gap: 4px;
            }
        }
        
        .preview-btn span {
            display: inline-block;
            word-break: break-word;
            overflow-wrap: break-word;
            text-align: left;
        }

        .preview-btn:hover span {
            text-decoration: underline;
        }

        /* Скрываем контент до загрузки */
        .hidden-until-loaded {
            opacity: 0;
            visibility: hidden;
            transition: opacity 0.5s ease-in-out, visibility 0.5s ease-in-out;
        }

        .loaded .hidden-until-loaded {
            opacity: 1;
            visibility: visible;
        }

        /* Блок реакций по центру */
        .reactions-wrapper {
            display: flex;
            flex-wrap: wrap;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
        }

        .reaction-group {
            display: flex;
            flex-direction: column;
            align-items: center;
            position: relative;
        }

        /* Стили для модального окна */
        #reactions-modal {
            display: none !important;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            z-index: 1000;
            align-items: center;
            justify-content: center;
        }

        #reactions-modal.show {
            display: flex !important;
        }
    </style>

    <div class="py-6 px-4">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 hidden-until-loaded">
            <!-- Основной контейнер заметки -->
            <div class="group relative overflow-hidden rounded-2xl border border-gray-400/70 bg-white/95 shadow-md transition-all duration-300 hover:shadow-xl dark:border-gray-500/50 dark:bg-gray-900/95">
                
                <!-- Header -->
                <div class="relative flex items-center justify-between border-b border-gray-200/70 bg-gradient-to-r from-gray-400/60 to-gray-400/40 px-5 py-4 dark:border-gray-700/60 dark:from-gray-700/60 dark:to-gray-800">
                    
                    <a href="{{ route('user.info', ['user' => $note->user->id]) }}"
                       class="group/user flex items-center gap-3">
                        
                        <div>
                            <x-avatar :user="$note->user" />
                        </div>
                        
                        <div>
                            <p class="text-base font-semibold text-gray-900 transition-colors duration-300 group-hover/user:text-indigo-600 dark:text-white dark:group-hover/user:text-indigo-400">
                                {{ $note->user->name }}
                            </p>
                            
                            <p class="text-xs text-gray-500 dark:text-gray-400">
                                {{ __('Автор заметки') }}
                            </p>
                        </div>
                    </a>
                    
                    @if($note->is_public)
                        <span class="inline-flex items-center gap-1 rounded-full border border-green-500/10 bg-green-500/20 px-3 py-1.5 text-xs font-medium text-green-700 dark:text-green-300">
                            <span class="h-2 w-2 rounded-full bg-green-500"></span>
                            {{ __('Публичная') }}
                        </span>
                    @else
                        <span class="inline-flex items-center gap-1 rounded-full border border-red-500/20 bg-red-500/10 px-3 py-1.5 text-xs font-medium text-red-700 dark:text-red-300">
                            <span class="h-2 w-2 rounded-full bg-red-500"></span>
                            {{ __('Приватная') }}
                        </span>
                    @endif
                </div>

                <!-- Content -->
                <div class="relative px-5 py-4">
                    
                    <h2 class="mb-3 text-xl font-bold leading-tight text-gray-900 dark:text-white">
                        {{ $note->title }}
                    </h2>
                    
                    <div class="relative">
                        <div class="ck-content note-content text-base leading-relaxed text-gray-700 dark:text-gray-300">
                            {!! $note->description !!}
                        </div>
                    </div>
                    
                    <!-- Meta (Теги и файлы) -->
                    <div class="mt-4 flex flex-wrap items-center gap-2.5">
                        @if($note->tags->count() > 0)
                            @foreach($note->tags as $tag)
                                <span class="inline-flex items-center rounded-full border border-blue-500/20 bg-blue-500/10 px-3 py-1.5 text-xs font-medium text-blue-700 dark:text-blue-300">
                                    <i class="fas fa-hashtag mr-1 text-[10px]"></i>
                                    {{ $tag->name }}
                                </span>
                            @endforeach
                        @endif
                    </div>

                    <!-- Прикрепленные файлы (детальный список) -->
                    @if($note->files->count() > 0)
                        <div class="mt-4 pt-3 border-t border-gray-200/70 dark:border-gray-700/60">
                            <h6 class="text-sm font-semibold text-gray-800 dark:text-gray-300 mb-1">
                                <i class="fas fa-paperclip mr-2"></i>Прикрепленные файлы
                            </h6>
                            <p class="text-xs text-gray-600 dark:text-gray-400 mb-3">
                        Нажмите на название файла для предпросмотра
                    </p>
                            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-3">
                                @foreach($note->files as $file)
                                    <div class="flex items-center p-2 border border-gray-400/50 hover:border-gray-500 dark:border-gray-600 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-800 transition-colors">
                                        <div class="flex-shrink-0">
                                            <i class="{{ $file->getFileIcon() }} text-2xl text-gray-500 dark:text-gray-400"></i>
                                        </div>
                                        <div class="ml-3 flex-1 min-w-0">
                                            <button class="text-sm font-medium text-gray-900 dark:text-white preview-btn"
                                                    data-file-url="{{ route('notes.files.download', ['note' => $note, 'file' => $file]) }}"
                                                    data-file-type="{{ $file->mime_type }}"
                                                    data-file-name="{{ $file->name }}">
                                                <span class="break-words whitespace-normal">{{ $file->name }}</span>
                                            </button>
                                            <p class="text-xs text-gray-700 dark:text-gray-400">
                                                {{ $file->getFormattedSize() }}
                                            </p>
                                        </div>
                                        <div class="flex items-center">
                                            <a href="{{ route('notes.files.download', ['note' => $note, 'file' => $file]) }}"
                                               class="text-gray-600 hover:text-gray-800 dark:text-gray-400 dark:hover:text-gray-300 ml-2"
                                               title="Скачать">
                                                <i class="fas fa-download"></i>
                                            </a>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>

                <!-- Блок реакций ПО ЦЕНТРУ -->
                <div class="border-t border-gray-200/70 dark:border-gray-700/60 py-4">
                    <div class="reactions-wrapper">
                        <!-- Лайк -->
                        <div class="reaction-group">
                            <button class="reaction-btn like {{ $userReaction === 'like' ? 'active' : '' }}" 
                                    data-reaction="like">
                                <i class="{{ $userReaction === 'like' ? 'fas' : 'far' }} fa-thumbs-up text-xl"></i>
                                <span id="count-like" class="count">{{ $reactionsData['like'] }}</span>
                            </button>
                            <button class="info-btn" data-reaction-type="like">
                                <i class="fas fa-info-circle"></i> <span>Info</span>
                            </button>
                            <div class="reaction-tooltip">
                                <div class="reaction-title">Нравится</div>
                                <div class="users-list">
                                    @foreach($reactionsUsers['like'] as $reaction)
                                        <div class="user-item">
                                            <span class="user-name">{{ $reaction->user->name }}</span>
                                            <span class="reaction-date">{{ $reaction->created_at->format('d.m.Y H:i') }}</span>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                        
                        <!-- Дизлайк -->
                        <div class="reaction-group">
                            <button class="reaction-btn dislike {{ $userReaction === 'dislike' ? 'active' : '' }}" 
                                    data-reaction="dislike">
                                <i class="{{ $userReaction === 'dislike' ? 'fas' : 'far' }} fa-thumbs-down text-xl"></i>
                                <span id="count-dislike" class="count">{{ $reactionsData['dislike'] }}</span>
                            </button>
                            <button class="info-btn" data-reaction-type="dislike">
                                <i class="fas fa-info-circle"></i> <span>Info</span>
                            </button>
                            <div class="reaction-tooltip">
                                <div class="reaction-title">Не нравится</div>
                                <div class="users-list">
                                    @foreach($reactionsUsers['dislike'] as $reaction)
                                        <div class="user-item">
                                            <span class="user-name">{{ $reaction->user->name }}</span>
                                            <span class="reaction-date">{{ $reaction->created_at->format('d.m.Y H:i') }}</span>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                        
                        <!-- Сердце -->
                        <div class="reaction-group">
                            <button class="reaction-btn heart {{ $userReaction === 'heart' ? 'active' : '' }}" 
                                    data-reaction="heart">
                                <i class="{{ $userReaction === 'heart' ? 'fas' : 'far' }} fa-heart text-xl"></i>
                                <span id="count-heart" class="count">{{ $reactionsData['heart'] }}</span>
                            </button>
                            <button class="info-btn" data-reaction-type="heart">
                                <i class="fas fa-info-circle"></i> <span>Info</span>
                            </button>
                            <div class="reaction-tooltip">
                                <div class="reaction-title">Люблю</div>
                                <div class="users-list">
                                    @foreach($reactionsUsers['heart'] as $reaction)
                                        <div class="user-item">
                                            <span class="user-name">{{ $reaction->user->name }}</span>
                                            <span class="reaction-date">{{ $reaction->created_at->format('d.m.Y H:i') }}</span>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                        
                        <!-- Смех -->
                        <div class="reaction-group">
                            <button class="reaction-btn laugh {{ $userReaction === 'laugh' ? 'active' : '' }}" 
                                    data-reaction="laugh">
                                <i class="{{ $userReaction === 'laugh' ? 'fas' : 'far' }} fa-smile text-xl"></i>
                                <span id="count-laugh" class="count">{{ $reactionsData['laugh'] }}</span>
                            </button>
                            <button class="info-btn" data-reaction-type="laugh">
                                <i class="fas fa-info-circle"></i> <span>Info</span>
                            </button>
                            <div class="reaction-tooltip">
                                <div class="reaction-title">Радость</div>
                                <div class="users-list">
                                    @foreach($reactionsUsers['laugh'] as $reaction)
                                        <div class="user-item">
                                            <span class="user-name">{{ $reaction->user->name }}</span>
                                            <span class="reaction-date">{{ $reaction->created_at->format('d.m.Y H:i') }}</span>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                        
                        <!-- Удивление -->
                        <div class="reaction-group">
                            <button class="reaction-btn wow {{ $userReaction === 'wow' ? 'active' : '' }}" 
                                    data-reaction="wow">
                                <i class="{{ $userReaction === 'wow' ? 'fas' : 'far' }} fa-surprise text-xl"></i>
                                <span id="count-wow" class="count">{{ $reactionsData['wow'] }}</span>
                            </button>
                            <button class="info-btn" data-reaction-type="wow">
                                <i class="fas fa-info-circle"></i> <span>Info</span>
                            </button>
                            <div class="reaction-tooltip">
                                <div class="reaction-title">Удивительно</div>
                                <div class="users-list">
                                    @foreach($reactionsUsers['wow'] as $reaction)
                                        <div class="user-item">
                                            <span class="user-name">{{ $reaction->user->name }}</span>
                                            <span class="reaction-date">{{ $reaction->created_at->format('d.m.Y H:i') }}</span>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Footer -->
                <div class="relative border-t border-gray-200/70 bg-gray-50/80 px-5 py-4 dark:border-gray-700/60 dark:bg-gray-900/60">
                    
                    <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
                        
                        <!-- Dates -->
                        <div class="flex flex-col gap-1.5 text-sm text-gray-600 dark:text-gray-400">
                            <p class="flex items-center gap-2">
                                <i class="far fa-calendar-plus text-green-500"></i>
                                {{ __('Создана:') }}
                                <span class="font-medium">
                                    {{ $note->created_at->format('d.m.Y H:i') }}
                                </span>
                            </p>
                            
                            <p class="flex items-center gap-2">
                                <i class="far fa-clock text-blue-500"></i>
                                {{ __('Изменена:') }}
                                <span class="font-medium">
                                    {{ $note->updated_at->format('d.m.Y H:i') }}
                                </span>
                            </p>
                        </div>
                        
<!-- Actions -->
<div class="flex items-center justify-between gap-2.5 lg:justify-between">
    
    <!-- Кнопка назад слева -->
    <div class="flex items-center">
        <a href="{{ route('notes.index') }}"
           class="inline-flex h-10 w-10 items-center justify-center rounded-xl bg-gray-500 text-base text-white shadow-sm transition-all duration-300 hover:bg-gray-800 dark:bg-gray-600 dark:hover:bg-gray-800"
           title="{{ __('Назад') }}">
            <i class="fas fa-arrow-left"></i>
        </a>
    </div>

    <!-- Кнопки справа -->
    @if ($note->user_id === auth()->id() || (auth()->user() && auth()->user()->isAdmin()))
        <div class="flex items-center gap-2 justify-end ml-auto">
            
            <a href="{{ route('notes.edit', $note) }}"
               class="inline-flex h-10 w-10 items-center justify-center rounded-xl bg-yellow-500 text-base text-white shadow-sm transition-all duration-300 hover:bg-yellow-600 dark:bg-yellow-600 dark:hover:bg-yellow-700"
               title="{{ __('Изменить') }}">
                <i class="fas fa-pen"></i>
            </a>

            <form action="{{ route('notes.destroy', $note) }}"
                  method="POST"
                  onsubmit="return confirm('Вы уверены, что хотите удалить эту заметку?');">
                @csrf
                @method('DELETE')

                <button type="submit"
                        class="inline-flex h-10 w-10 items-center justify-center rounded-xl bg-red-500 text-base text-white shadow-sm transition-all duration-300 hover:bg-red-600 dark:bg-red-600 dark:hover:bg-red-700"
                        title="{{ __('Удалить') }}">
                    <i class="fas fa-trash"></i>
                </button>
            </form>

        </div>
    @endif

</div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Комментарии -->
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 mt-6 hidden-until-loaded">
            @livewire('comments', ['note' => $note])
        </div>
    </div>

    <!-- Модальное окно для предпросмотра файлов -->
    <div id="preview-modal" class="fixed hidden inset-0 bg-black bg-opacity-75 flex items-center justify-center z-50 p-4">
        <div class="bg-gray-300 dark:bg-gray-800 rounded-lg shadow-xl w-full max-w-4xl max-h-[90vh] flex flex-col">
            <div class="flex justify-between items-center p-4 border-b border-gray-400 dark:border-gray-700">
                <h3 id="preview-title" class="text-lg font-semibold text-gray-900 dark:text-white"></h3>
                <button id="close-preview" class="text-red-500 hover:text-red-800 dark:text-red-400 dark:hover:text-red-700">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div id="preview-content" class="flex-1 overflow-auto p-4"></div>
            <div class="p-4 border-t border-gray-400 dark:border-gray-700 flex justify-end">
                <a id="download-full" href="#" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 transition">
                    <i class="fas fa-download mr-2"></i>Скачать файл
                </a>
            </div>
        </div>
    </div>

    <!-- Модальное окно для информации о реакциях -->
    <div id="reactions-modal" class="fixed hidden inset-0 bg-black bg-opacity-50 z-50 items-center justify-center">
        <div class="bg-white dark:bg-gray-800 rounded-lg p-4 w-full max-w-md m-4">
            <div class="flex justify-between items-center mb-4">
                <h3 id="modal-title" class="text-lg font-bold text-gray-900 dark:text-white">Информация о реакции</h3>
                <button id="close-modal" class="text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-300">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>
            <div id="modal-users-list" class="space-y-2 max-h-96 overflow-y-auto"></div>
        </div>
    </div>

    <script>
document.addEventListener('DOMContentLoaded', function() {
    // Инициализация подсветки синтаксиса
    const codeBlocks = document.querySelectorAll('.ck-content pre code');
    if (codeBlocks.length > 0) {
        codeBlocks.forEach((block) => {
            hljs.highlightElement(block);
            addCopyButton(block);
        });
    }

    // Инициализация реакций
    initReactions();

    // Функция для отображения модального окна с информацией о реакциях
    window.showReactionsModal = function(reactionType) {
        const modal = document.getElementById('reactions-modal');
        const modalTitle = document.getElementById('modal-title');
        const modalUsersList = document.getElementById('modal-users-list');
        
        if (!modal || !modalTitle || !modalUsersList) return;
        
        modalUsersList.innerHTML = '<div class="text-center py-4"><i class="fas fa-spinner fa-spin fa-2x text-gray-500"></i><p class="mt-2 text-gray-500">Загрузка...</p></div>';
        modal.classList.add('show');
        
        fetch(`/notes/{{ $note->id }}/reactions/${reactionType}`)
            .then(response => {
                if (!response.ok) throw new Error('Ошибка загрузки данных');
                return response.json();
            })
            .then(data => {
                modalTitle.textContent = getReactionTitle(reactionType);
                modalUsersList.innerHTML = '';
                
                if (data.users.length === 0) {
                    modalUsersList.innerHTML = '<p class="text-gray-500 dark:text-gray-400 text-center py-4">Никто ещё не поставил эту реакцию</p>';
                } else {
                    data.users.forEach(user => {
                        const userItem = document.createElement('div');
                        userItem.className = 'flex justify-between items-center p-3 bg-gray-100 dark:bg-gray-700 rounded-lg';
                        userItem.innerHTML = `
                            <span class="font-medium text-gray-900 dark:text-white">${escapeHtml(user.name)}</span>
                            <span class="text-sm text-gray-500 dark:text-gray-400">${formatDate(user.created_at)}</span>
                        `;
                        modalUsersList.appendChild(userItem);
                    });
                }
            })
            .catch(error => {
                console.error('Ошибка:', error);
                modalUsersList.innerHTML = `<p class="text-red-500 text-center py-4">Ошибка загрузки данных</p>`;
            });
    };

    function getReactionTitle(reactionType) {
        const titles = {
            'like': '👍 Нравится',
            'dislike': '👎 Не нравится',
            'heart': '❤️ Люблю',
            'laugh': '😄 Радость',
            'wow': '😲 Удивительно'
        };
        return titles[reactionType] || 'Реакции';
    }

    function formatDate(dateString) {
        const date = new Date(dateString);
        return date.toLocaleString('ru-RU', {
            day: '2-digit',
            month: '2-digit',
            year: 'numeric',
            hour: '2-digit',
            minute: '2-digit'
        });
    }

    function escapeHtml(text) {
        const div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    }

    // Обработчики для кнопок "Информация"
    const infoBtns = document.querySelectorAll('.info-btn');
    infoBtns.forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.stopPropagation();
            const reactionType = this.getAttribute('data-reaction-type');
            if (reactionType) {
                window.showReactionsModal(reactionType);
            }
        });
    });
    
    // Закрыть модальное окно
    const closeModalBtn = document.getElementById('close-modal');
    if (closeModalBtn) {
        closeModalBtn.addEventListener('click', function() {
            const modal = document.getElementById('reactions-modal');
            if (modal) modal.classList.remove('show');
        });
    }

    const modal = document.getElementById('reactions-modal');
    if (modal) {
        modal.addEventListener('click', function(e) {
            if (e.target === modal) modal.classList.remove('show');
        });
    }

    document.body.classList.add('loaded');

    function addCopyButton(block) {
        const copyButton = document.createElement('button');
        copyButton.innerHTML = '<i class="far fa-copy"></i>';
        copyButton.className = 'copy-button';
        copyButton.title = 'Копировать код';
        block.parentNode.appendChild(copyButton);

        copyButton.addEventListener('click', async (e) => {
            e.stopPropagation();
            const code = block.innerText;
            
            try {
                if (navigator.clipboard) {
                    await navigator.clipboard.writeText(code);
                    showCopySuccess(copyButton);
                } else {
                    fallbackCopyTextToClipboard(code, copyButton);
                }
            } catch (err) {
                fallbackCopyTextToClipboard(code, copyButton);
            }
        });
    }

    function fallbackCopyTextToClipboard(text, button) {
        const textArea = document.createElement('textarea');
        textArea.value = text;
        textArea.style.position = 'fixed';
        textArea.style.top = '0';
        textArea.style.left = '0';
        textArea.style.opacity = '0';
        
        document.body.appendChild(textArea);
        textArea.focus();
        textArea.select();

        try {
            const successful = document.execCommand('copy');
            if (successful) {
                showCopySuccess(button);
            } else {
                showCopyError(button, 'Не удалось скопировать');
            }
        } catch (err) {
            showCopyError(button, 'Ошибка при копировании');
        } finally {
            document.body.removeChild(textArea);
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

    function showCopyError(button, message) {
        const originalHtml = button.innerHTML;
        button.innerHTML = '<i class="fas fa-times"></i>';
        button.classList.add('error');
        button.title = message;
        
        setTimeout(() => {
            button.innerHTML = originalHtml;
            button.classList.remove('error');
            button.title = 'Копировать код';
        }, 2000);
    }

    function initReactions() {
        const noteId = {{ $note->id }};
        const userId = {{ auth()->id() ?? 'null' }};

        document.querySelectorAll('.reaction-btn').forEach(btn => {
            btn.removeEventListener('click', reactionHandler);
            btn.addEventListener('click', reactionHandler);
            
            async function reactionHandler() {
                if (!userId) {
                    alert('Для оценки войдите в систему');
                    return;
                }

                const reaction = this.dataset.reaction;
                const isActive = this.classList.contains('active');

                try {
                    const response = await fetch(`/notes/${noteId}/reactions`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({
                            reaction: reaction,
                            remove: isActive
                        })
                    });

                    if (!response.ok) throw new Error('Ошибка сохранения реакции');
                    
                    const data = await response.json();
                    
                    Object.entries(data.reactions).forEach(([reaction, count]) => {
                        const counter = document.getElementById(`count-${reaction}`);
                        if (counter) counter.textContent = count;
                    });

                    document.querySelectorAll('.reaction-btn').forEach(btn => {
                        if (btn.dataset.reaction === reaction) {
                            if (isActive) {
                                btn.classList.remove('active');
                                const icon = btn.querySelector('i');
                                if (icon && icon.classList.contains('fas')) {
                                    icon.classList.replace('fas', 'far');
                                }
                            } else {
                                btn.classList.add('active');
                                const icon = btn.querySelector('i');
                                if (icon && icon.classList.contains('far')) {
                                    icon.classList.replace('far', 'fas');
                                }
                            }
                        } else if (!isActive) {
                            btn.classList.remove('active');
                            const icon = btn.querySelector('i');
                            if (icon && icon.classList.contains('fas')) {
                                icon.classList.replace('fas', 'far');
                            }
                        }
                    });

                    if (data.reactionsUsers) {
                        updateUsersTooltips(data.reactionsUsers);
                    }
                } catch (error) {
                    console.error('Error:', error);
                }
            }
        });

        function updateUsersTooltips(usersData) {
            const reactionTitles = {
                'like': 'Нравится',
                'dislike': 'Не нравится',
                'heart': 'Люблю',
                'laugh': 'Радость',
                'wow': 'Удивительно'
            };

            for (const [reaction, reactions] of Object.entries(usersData)) {
                const reactionGroup = document.querySelector(`.reaction-group .reaction-btn[data-reaction="${reaction}"]`)?.closest('.reaction-group');
                if (reactionGroup) {
                    const tooltip = reactionGroup.querySelector('.reaction-tooltip');
                    if (tooltip) {
                        const titleElement = tooltip.querySelector('.reaction-title');
                        const usersListElement = tooltip.querySelector('.users-list');
                        
                        if (titleElement) titleElement.textContent = reactionTitles[reaction];
                        
                        if (usersListElement) {
                            usersListElement.innerHTML = reactions.map(item => `
                                <div class="user-item">
                                    <span class="user-name">${escapeHtml(item.user)}</span>
                                    <span class="reaction-date">${new Date(item.created_at).toLocaleString('ru-RU', {
                                        day: '2-digit',
                                        month: '2-digit',
                                        year: 'numeric',
                                        hour: '2-digit',
                                        minute: '2-digit'
                                    })}</span>
                                </div>
                            `).join('');
                        }
                    }
                }
            }
        }
    }

    // Обработчики для кнопок предпросмотра файлов
    const previewBtns = document.querySelectorAll('.preview-btn');
    previewBtns.forEach(btn => {
        btn.addEventListener('click', function() {
            const fileUrl = this.dataset.fileUrl;
            const fileType = this.dataset.fileType;
            const fileName = this.dataset.fileName;
            showPreviewModal(fileUrl, fileType, fileName);
        });
    });
    
    const closePreviewBtn = document.getElementById('close-preview');
    if (closePreviewBtn) {
        closePreviewBtn.addEventListener('click', function() {
            const modal = document.getElementById('preview-modal');
            if (modal) modal.classList.add('hidden');
        });
    }
    
    function showPreviewModal(fileUrl, fileType, fileName) {
        const modal = document.getElementById('preview-modal');
        const title = document.getElementById('preview-title');
        const content = document.getElementById('preview-content');
        const downloadBtn = document.getElementById('download-full');
        
        if (!modal || !title || !content || !downloadBtn) return;
        
        title.textContent = fileName;
        downloadBtn.href = fileUrl;
        content.innerHTML = '<div class="flex justify-center items-center h-full"><i class="fas fa-spinner fa-spin fa-2x text-gray-500"></i></div>';
        modal.classList.remove('hidden');
        
        if (fileType.startsWith('image/')) {
            content.innerHTML = `<img src="${fileUrl}" alt="${fileName}" class="max-w-full max-h-[70vh] mx-auto">`;
        } else if (fileType.startsWith('text/') || fileType === 'application/json' || fileType === 'application/javascript') {
            fetch(fileUrl)
                .then(response => response.text())
                .then(text => {
                    const pre = document.createElement('pre');
                    const code = document.createElement('code');
                    code.textContent = text;
                    pre.appendChild(code);
                    content.innerHTML = '';
                    content.appendChild(pre);
                    if (typeof hljs !== 'undefined') hljs.highlightElement(code);
                })
                .catch(error => {
                    content.innerHTML = `<p class="text-red-500">Не удалось загрузить файл: ${error.message}</p>`;
                });
        } else {
            content.innerHTML = `
                <div class="flex flex-col items-center justify-center h-full text-gray-500">
                    <i class="fas fa-eye-slash fa-3x mb-4"></i>
                    <p class="text-center">Предпросмотр недоступен для этого типа файла</p>
                    <p class="text-sm mt-2 text-center">Попробуйте скачать файл для просмотра</p>
                </div>
            `;
        }
    }
    
    document.addEventListener('click', function(event) {
        const modal = document.getElementById('preview-modal');
        if (modal && event.target === modal) modal.classList.add('hidden');
    });
});
    </script>
</x-app-layout>
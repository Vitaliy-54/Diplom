<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ 'Просмотр заметки' }}
            </h2>
            <div class="text-sm text-gray-800 dark:text-gray-400">{{ now()->format('d.m.Y') }}</div>
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
            display: flex;
            align-items: center;
            padding: 0.5rem 0.7rem;
            border-radius: 9999px;
            background-color: transparent;
            transition: all 0.2s ease;
            border: none;
            cursor: pointer;
            color: #6b7280;
        }

        /* Цвета для активных реакций */
        .reaction-btn.active.like {
            color: #3b82f6;
        }
        .reaction-btn.active.dislike {
            color: #ef4444;
        }
        .reaction-btn.active.heart {
            color: #ec4899;
        }
        .reaction-btn.active.laugh {
            color: #f59e0b;
        }
        .reaction-btn.active.wow {
            color: #10b981;
        }

        /* Цвета при наведении (даже для неактивных) */
        .reaction-btn:hover.like {
            color: #3b82f6;
        }
        .reaction-btn:hover.dislike {
            color: #ef4444;
        }
        .reaction-btn:hover.heart {
            color: #ec4899;
        }
        .reaction-btn:hover.laugh {
            color: #f59e0b;
        }
        .reaction-btn:hover.wow {
            color: #10b981;
        }

        /* Темная тема */
        .dark .reaction-btn {
            color: #9ca3af;
        }
        .dark .reaction-btn.active.like {
            color: #60a5fa;
        }
        .dark .reaction-btn.active.dislike {
            color: #f87171;
        }
        .dark .reaction-btn.active.heart {
            color: #f472b6;
        }
        .dark .reaction-btn.active.laugh {
            color: #fbbf24;
        }
        .dark .reaction-btn.active.wow {
            color: #34d399;
        }
        .dark .reaction-btn:hover.like {
            color: #60a5fa;
        }
        .dark .reaction-btn:hover.dislike {
            color: #f87171;
        }
        .dark .reaction-btn:hover.heart {
            color: #f472b6;
        }
        .dark .reaction-btn:hover.laugh {
            color: #fbbf24;
        }
        .dark .reaction-btn:hover.wow {
            color: #34d399;
        }

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
        
        /* Скрываем тултип на мобильных устройствах */
        @media (max-width: 640px) {
            .reaction-tooltip {
                display: none !important;
            }
        }
        
        .dark .reaction-tooltip {
            background-color: #374151;
            border-color: #4b5563;
        }
        
        .group:hover .reaction-tooltip {
            display: block;
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
        
        .dark .user-item {
            color: #f3f4f6;
        }
        
        .user-name {
            font-weight: 500;
            color: rgb(26, 26, 26);
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .dark .user-name {
            color: rgb(224, 221, 221);
        }
        
        .reaction-date {
            font-size: 0.75rem;
            color: rgb(26, 26, 26);
        }
        
        .dark .reaction-date {
            color: #9ca3af;
        }
        
        /* Адаптивные стили для мобильных устройств */
        @media (max-width: 640px) {
            .reactions-mobile {
                display: flex;
                justify-content: center;
                margin-top: 0.5rem;
                padding-top: 0.5rem;
                border-top: 1px solid #e5e7eb;
            }
            .dark .reactions-mobile {
                border-top-color: #4b5563;
            }
            
            .actions-desktop .reactions-container {
                display: none;
            }
        }
        
        @media (min-width: 641px) {
            .reactions-mobile {
                display: none;
            }
            
            .actions-desktop .reactions-container {
                display: flex;
            }

            .info-btn {
                display: none;
            }
        }

        /* Стили для модального окна */
        #reactions-modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: 100;
            
        }

        #reactions-modal.show {
            display: flex;
        }

        .info-btn {
            background: none;
            border: none;
            color: #6b7280;
            cursor: pointer;
            margin-left: 13px;  
        }

        .info-btn:hover {
            color: #10b981;
        }
    </style>

    <div class="py-6 px-4">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Основной контейнер заметки -->
            <div class="bg-gray-300 dark:bg-gray-800 shadow-lg rounded-lg overflow-hidden hidden-until-loaded">
                
                <!-- Шапка заметки -->
                <div class="bg-gray-400 dark:bg-gray-700 p-4 flex items-center justify-between">
                    <p class="text-sm text-gray-700 dark:text-gray-400">
                        {{ __('Пользователь:') }} {{ $note->user->name }}
                    </p>
                    @if(!$note->is_public)
                        <span class="px-2 py-1 bg-red-700 dark:bg-red-900 text-red-100 dark:text-red-200 text-xs rounded-full">
                            {{ __('Приватная') }}
                        </span>
                    @else
                        <span class="px-2 py-1 bg-green-700 dark:bg-green-900 text-green-100 dark:text-green-200 text-xs rounded-full">
                            {{ __('Публичная') }}
                        </span>
                    @endif
                </div>

                <!-- Контент заметки -->
                <div class="p-4">
                    <h5 class="text-xl font-bold text-gray-900 dark:text-white note-title">
                        <p>{{ $note->title }}</p>
                    </h5>

                    <div class="text-gray-900 dark:text-gray-300 ck-content">
                        {!! $note->description !!}
                    </div>
                </div>

                <!-- Теги -->
                @if($note->tags->count() > 0)
                <div class="flex flex-wrap gap-2 p-2 border-t border-gray-400 dark:border-gray-700">
                    <span class="ml-2 rounded-lg text-sm text-gray-800 dark:text-gray-300">
                        Теги:
                    </span>
                    @foreach($note->tags as $tag)
                    <span class="px-2 py-1 bg-blue-600 dark:bg-blue-900 text-blue-200 dark:text-blue-200 text-xs rounded-full">
                        {{ $tag->name }}
                    </span>
                    @endforeach
                </div>
                @endif

                <!-- Мета-информация -->
                <div class="p-4 border-t border-gray-400 dark:border-gray-700">
                    <div class="flex items-center justify-between text-sm text-gray-700 dark:text-gray-400">
                        <p>{{ __('Создана:') }} {{ $note->created_at->format('d.m.Y H:i') }}</p>
                        <p>{{ __('Изменена:') }} {{ $note->updated_at->format('d.m.Y H:i') }}</p>
                    </div>
                    
                    <!-- Реакции для мобильных устройств -->
                    <div class="reactions-mobile mt-2">
                        <div class="flex justify-center">
                            <!-- Лайк -->
                            <div class="relative group">
                                <button class="reaction-btn like {{ $userReaction === 'like' ? 'active' : '' }}" 
                                        data-reaction="like">
                                    <i class="{{ $userReaction === 'like' ? 'fas' : 'far' }} fa-thumbs-up text-xl"></i>
                                    <span id="count-mobile-like" class="count">{{ $reactionsData['like'] }}</span>
                                </button>
                                <button class="info-btn" title="Информация">
                                    <i class="fas fa-info-circle text-sm"></i>
                                </button>
                                <div class="reaction-tooltip">
                                    <div class="reaction-title">Нравится</div>
                                    <div class="divider"></div>
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
                            <div class="relative group">
                                <button class="reaction-btn dislike {{ $userReaction === 'dislike' ? 'active' : '' }}" 
                                        data-reaction="dislike">
                                    <i class="{{ $userReaction === 'dislike' ? 'fas' : 'far' }} fa-thumbs-down text-xl"></i>
                                    <span id="count-mobile-dislike" class="count">{{ $reactionsData['dislike'] }}</span>
                                </button>
                                <button class="info-btn" title="Информация">
                                    <i class="fas fa-info-circle text-sm"></i>
                                </button>
                                <div class="reaction-tooltip">
                                    <div class="reaction-title">Не нравится</div>
                                    <div class="divider"></div>
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
                            <div class="relative group">
                                <button class="reaction-btn heart {{ $userReaction === 'heart' ? 'active' : '' }}" 
                                        data-reaction="heart">
                                    <i class="{{ $userReaction === 'heart' ? 'fas' : 'far' }} fa-heart text-xl"></i>
                                    <span id="count-mobile-heart" class="count">{{ $reactionsData['heart'] }}</span>
                                </button>
                                <button class="info-btn" title="Информация">
                                    <i class="fas fa-info-circle text-sm"></i>
                                </button>
                                <div class="reaction-tooltip">
                                    <div class="reaction-title">Люблю</div>
                                    <div class="divider"></div>
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
                            <div class="relative group">
                                <button class="reaction-btn laugh {{ $userReaction === 'laugh' ? 'active' : '' }}" 
                                        data-reaction="laugh">
                                    <i class="{{ $userReaction === 'laugh' ? 'fas' : 'far' }} fa-smile text-xl"></i>
                                    <span id="count-mobile-laugh" class="count">{{ $reactionsData['laugh'] }}</span>
                                </button>
                                <button class="info-btn" title="Информация">
                                    <i class="fas fa-info-circle text-sm"></i>
                                </button>
                                <div class="reaction-tooltip">
                                    <div class="reaction-title">Радость</div>
                                    <div class="divider"></div>
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
                            <div class="relative group">
                                <button class="reaction-btn wow {{ $userReaction === 'wow' ? 'active' : '' }}" 
                                        data-reaction="wow">
                                    <i class="{{ $userReaction === 'wow' ? 'fas' : 'far' }} fa-surprise text-xl"></i>
                                    <span id="count-mobile-wow" class="count">{{ $reactionsData['wow'] }}</span>
                                </button>
                                <button class="info-btn" title="Информация">
                                    <i class="fas fa-info-circle text-sm"></i>
                                </button>
                                <div class="reaction-tooltip">
                                    <div class="reaction-title">Удивительно</div>
                                    <div class="divider"></div>
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
                </div>

                <!-- Футер с действиями -->
                <div class="p-4 border-t border-gray-400 dark:border-gray-700">
                    <div class="flex items-center justify-between">
                        <a href="{{ route('notes.index') }}" 
                           class="text-gray-600 hover:text-gray-800 dark:text-gray-400 dark:hover:text-gray-200 transition duration-300" 
                           title="{{ __('Назад') }}">
                            <i class="fas fa-arrow-left fa-lg"></i>                     
                        </a>

                        <!-- Реакции для десктопов -->
                        <div class="actions-desktop">
                            <div class="reactions-container flex space-x-2">
                                <!-- Лайк -->
                                <div class="relative group">
                                    <button class="reaction-btn like {{ $userReaction === 'like' ? 'active' : '' }}" 
                                            data-reaction="like">
                                        <i class="{{ $userReaction === 'like' ? 'fas' : 'far' }} fa-thumbs-up text-xl"></i>
                                        <span id="count-like" class="count">{{ $reactionsData['like'] }}</span>
                                    </button>
                                    <button class="info-btn" title="Информация">
                                        <i class="fas fa-info-circle text-sm"></i>
                                    </button>
                                    <div class="reaction-tooltip">
                                        <div class="reaction-title">Нравится</div>
                                        <div class="divider"></div>
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
                                <div class="relative group">
                                    <button class="reaction-btn dislike {{ $userReaction === 'dislike' ? 'active' : '' }}" 
                                            data-reaction="dislike">
                                        <i class="{{ $userReaction === 'dislike' ? 'fas' : 'far' }} fa-thumbs-down text-xl"></i>
                                        <span id="count-dislike" class="count">{{ $reactionsData['dislike'] }}</span>
                                    </button>
                                    <button class="info-btn" title="Информация">
                                        <i class="fas fa-info-circle text-sm"></i>
                                    </button>
                                    <div class="reaction-tooltip">
                                        <div class="reaction-title">Не нравится</div>
                                        <div class="divider"></div>
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
                                <div class="relative group">
                                    <button class="reaction-btn heart {{ $userReaction === 'heart' ? 'active' : '' }}" 
                                            data-reaction="heart">
                                        <i class="{{ $userReaction === 'heart' ? 'fas' : 'far' }} fa-heart text-xl"></i>
                                        <span id="count-heart" class="count">{{ $reactionsData['heart'] }}</span>
                                    </button>
                                    <button class="info-btn" title="Информация">
                                        <i class="fas fa-info-circle text-sm"></i>
                                    </button>
                                    <div class="reaction-tooltip">
                                        <div class="reaction-title">Люблю</div>
                                        <div class="divider"></div>
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
                                <div class="relative group">
                                    <button class="reaction-btn laugh {{ $userReaction === 'laugh' ? 'active' : '' }}" 
                                            data-reaction="laugh">
                                        <i class="{{ $userReaction === 'laugh' ? 'fas' : 'far' }} fa-smile text-xl"></i>
                                        <span id="count-laugh" class="count">{{ $reactionsData['laugh'] }}</span>
                                    </button>
                                    <button class="info-btn" title="Информация">
                                        <i class="fas fa-info-circle text-sm"></i>
                                    </button>
                                    <div class="reaction-tooltip">
                                        <div class="reaction-title">Радость</div>
                                        <div class="divider"></div>
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
                                <div class="relative group">
                                    <button class="reaction-btn wow {{ $userReaction === 'wow' ? 'active' : '' }}" 
                                            data-reaction="wow">
                                        <i class="{{ $userReaction === 'wow' ? 'fas' : 'far' }} fa-surprise text-xl"></i>
                                        <span id="count-wow" class="count">{{ $reactionsData['wow'] }}</span>
                                    </button>
                                    <button class="info-btn" title="Информация">
                                        <i class="fas fa-info-circle text-sm"></i>
                                    </button>
                                    <div class="reaction-tooltip">
                                        <div class="reaction-title">Удивительно</div>
                                        <div class="divider"></div>
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

                        <!-- Кнопки управления -->
                        <div class="flex items-center gap-2">
                            @if ($note->user_id === auth()->id() || auth()->user()->isAdmin())
                                <a href="{{ route('notes.edit', $note) }}" 
                                   class="text-yellow-600 hover:text-yellow-700 transition duration-300 mr-4" 
                                   title="{{ __('Изменить') }}">
                                    <i class="fas fa-edit fa-lg"></i>
                                </a>
                                
                                <form action="{{ route('notes.destroy', $note) }}" 
                                      method="POST" 
                                      onsubmit="return confirm('Вы уверены, что хотите удалить эту заметку?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" 
                                            class="text-red-600 hover:text-red-700 transition duration-300" 
                                            title="{{ __('Удалить') }}">
                                        <i class="fas fa-trash fa-lg"></i>
                                    </button>
                                </form>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

   <!-- Модальное окно -->
<div id="reactions-modal" class="fixed hidden inset-0 bg-black bg-opacity-50 flex items-center justify-center">
    <div class="bg-white dark:bg-gray-800 rounded-lg p-4 w-full max-w-md m-4"> <!-- Добавлен класс m-4 -->
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-lg font-bold text-gray-900 dark:text-white">Информация о реакции</h3>
            <button id="close-modal" class="text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-300">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <div id="modal-content" class="space-y-4">
            <!-- Содержимое модального окна будет добавлено динамически -->
        </div>
    </div>
</div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Инициализация подсветки синтаксиса
            document.querySelectorAll('pre code').forEach((block) => {
                hljs.highlightElement(block);
                addCopyButton(block);
            });

            // Инициализация реакций
            if (document.querySelector('.reaction-btn')) {
                initReactions();
            }

            // Позиционирование тултипов для мобильных устройств
            const positionTooltips = () => {
                if (window.innerWidth <= 640) {
                    document.querySelectorAll('.group').forEach(group => {
                        const tooltip = group.querySelector('.reaction-tooltip');
                        if (!tooltip) return;
                        
                        const groupRect = group.getBoundingClientRect();
                        const tooltipWidth = tooltip.offsetWidth;
                        
                        // Проверяем, выходит ли тултип за правую границу экрана
                        if (groupRect.left + tooltipWidth > window.innerWidth) {
                            tooltip.classList.add('left-edge');
                        } else {
                            tooltip.classList.remove('left-edge');
                        }
                    });
                }
            };
            
            // Вызываем при загрузке и при ресайзе
            positionTooltips();
            window.addEventListener('resize', positionTooltips);

            // Функция для отображения модального окна с информацией о реакциях
function showReactionsModal(reactionType) {
    const modal = document.getElementById('reactions-modal');
    const modalContent = document.getElementById('modal-content');
    
    // Очистить предыдущее содержимое
    modalContent.innerHTML = '';
    
    // Добавить загрузчик
    const loading = document.createElement('div');
    loading.className = 'text-center py-4';
    loading.innerHTML = '<i class="fas fa-spinner fa-spin fa-2x text-gray-500"></i>';
    modalContent.appendChild(loading);
    
    // Запросить данные с сервера
    fetch(`/notes/{{ $note->id }}/reactions/${reactionType}`)
        .then(response => {
            if (!response.ok) {
                throw new Error('Ошибка загрузки данных');
            }
            return response.json();
        })
        .then(data => {
            // Очистить загрузчик
            modalContent.innerHTML = '';
            
            // Добавить заголовок
            const title = document.createElement('h4');
            title.className = 'text-xl font-bold mb-2 text-gray-900 dark:text-white border-t border-gray-400 dark:border-gray-600';
            title.textContent = getReactionTitle(reactionType);
            modalContent.appendChild(title);
            
            // Добавить список пользователей
            const userList = document.createElement('div');
            userList.className = 'space-y-2';
            
            if (data.users.length === 0) {
                const noUsers = document.createElement('p');
                noUsers.className = 'text-gray-500 dark:text-white';
                noUsers.textContent = 'Никто ещё не поставил реакцию';
                userList.appendChild(noUsers);
            } else {
                data.users.forEach(user => {
                    const userItem = document.createElement('div');
                    userItem.className = 'flex justify-between items-center p-2 bg-gray-300 dark:bg-gray-700 rounded';
                    
                    const userName = document.createElement('span');
                    userName.className = 'font-medium text-gray-900 dark:text-white';
                    userName.textContent = user.name;
                    
                    const reactionDate = document.createElement('span');
                    reactionDate.className = 'text-sm text-gray-500 dark:text-gray-400';
                    reactionDate.textContent = formatDate(user.created_at);
                    
                    userItem.appendChild(userName);
                    userItem.appendChild(reactionDate);
                    userList.appendChild(userItem);
                });
            }
            
            modalContent.appendChild(userList);
        })
        .catch(error => {
            console.error('Ошибка:', error);
            modalContent.innerHTML = `<p class="text-red-500">Ошибка загрузки данных: ${error.message}</p>`;
        })
        .finally(() => {
            // Показать модальное окно
            modal.classList.add('show');
        });
}

            // Функция для получения заголовка реакции
            function getReactionTitle(reactionType) {
                const titles = {
                    'like': 'Нравится',
                    'dislike': 'Не нравится',
                    'heart': 'Люблю',
                    'laugh': 'Радость',
                    'wow': 'Удивительно'
                };
                return titles[reactionType] || 'Реакции';
            }

            // Функция для получения списка пользователей для реакции
function getReactionUsers(reactionType) {
    // Преобразуем PHP-переменную в JavaScript
    const reactionsUsers = JSON.parse('{!! json_encode($reactionsUsers) !!}');
    
    // Получаем пользователей для текущей реакции
    return reactionsUsers[reactionType] || [];
}

            // Функция для форматирования даты
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

            // Инициализация кнопок "Информация"
            document.querySelectorAll('.info-btn').forEach(btn => {
                btn.addEventListener('click', function() {
                    const reactionType = this.closest('.group').querySelector('.reaction-btn').dataset.reaction;
                    showReactionsModal(reactionType);
                });
            });
            
            // Закрыть модальное окно
            document.getElementById('close-modal').addEventListener('click', function() {
                document.getElementById('reactions-modal').classList.remove('show');
            });

            // Закрыть модальное окно при клике вне его
document.addEventListener('click', function(event) {
    const modal = document.getElementById('reactions-modal');
    const modalContent = document.getElementById('modal-content');
    
    // Проверяем, кликнули ли мы на фоновую область или вне модального окна
    if (modal.classList.contains('show') && 
        !modalContent.contains(event.target) && 
        event.target !== modalContent) {
        modal.classList.remove('show');
    }
});

            // Показать контент после загрузки
            document.body.classList.add('loaded');
        });

        // Функция для добавления кнопки копирования с поддержкой HTTP
        function addCopyButton(block) {
            // Создаем кнопку копирования
            const copyButton = document.createElement('button');
            copyButton.innerHTML = '<i class="far fa-copy"></i>';
            copyButton.className = 'copy-button';
            copyButton.title = 'Копировать код';

            // Добавляем кнопку внутрь pre, но поверх code
            block.parentNode.appendChild(copyButton);

            // Обработчик клика для копирования кода
            copyButton.addEventListener('click', async (e) => {
                e.stopPropagation();
                const code = block.innerText;
                
                try {
                    // Пробуем modern Clipboard API
                    if (navigator.clipboard) {
                        await navigator.clipboard.writeText(code);
                        showCopySuccess(copyButton);
                    } else {
                        // Fallback для HTTP/браузеров без Clipboard API
                        fallbackCopyTextToClipboard(code, copyButton);
                    }
                } catch (err) {
                    console.error('Ошибка при копировании:', err);
                    // Если modern API не сработал, пробуем fallback
                    fallbackCopyTextToClipboard(code, copyButton);
                }
            });
        }

                // Fallback метод для копирования текста (работает в HTTP)
                function fallbackCopyTextToClipboard(text, button) {
            // Создаем временный textarea
            const textArea = document.createElement('textarea');
            textArea.value = text;
            textArea.style.position = 'fixed';  // Убираем из потока документа
            textArea.style.top = '0';
            textArea.style.left = '0';
            textArea.style.opacity = '0'; // Делаем невидимым
            
            document.body.appendChild(textArea);
            textArea.focus();
            textArea.select();

            try {
                // Старый метод копирования
                const successful = document.execCommand('copy');
                if (successful) {
                    showCopySuccess(button);
                } else {
                    showCopyError(button, 'Не удалось скопировать');
                }
            } catch (err) {
                console.error('Fallback copy failed:', err);
                showCopyError(button, 'Ошибка при копировании');
                // Альтернатива - показать текст для ручного копирования
                // prompt('Скопируйте текст:', text);
            } finally {
                document.body.removeChild(textArea);
            }
        }

                // Показать успешное копирование
                function showCopySuccess(button) {
            button.innerHTML = '<i class="fas fa-check"></i>';
            button.classList.add('success');
            setTimeout(() => {
                button.innerHTML = '<i class="far fa-copy"></i>';
                button.classList.remove('success');
            }, 2000);
        }

        // Показать ошибку копирования
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
                btn.addEventListener('click', async function() {
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
                        
                        // Обновляем счетчики для всех устройств
                        Object.entries(data.reactions).forEach(([reaction, count]) => {
                            const counter = document.getElementById(`count-${reaction}`);
                            const mobileCounter = document.getElementById(`count-mobile-${reaction}`);
                            if (counter) counter.textContent = count;
                            if (mobileCounter) mobileCounter.textContent = count;
                        });

                        // Обновляем состояние кнопок для всех устройств
                        document.querySelectorAll('.reaction-btn').forEach(btn => {
                            if (btn.dataset.reaction === reaction) {
                                if (isActive) {
                                    btn.classList.remove('active');
                                    btn.querySelector('i').classList.replace('fas', 'far');
                                } else {
                                    btn.classList.add('active');
                                    btn.querySelector('i').classList.replace('far', 'fas');
                                }
                            } else {
                                btn.classList.remove('active');
                                const icon = btn.querySelector('i');
                                if (icon.classList.contains('fas')) {
                                    icon.classList.replace('fas', 'far');
                                }
                            }
                        });

                        // Обновляем список пользователей
                        if (data.reactionsUsers) {
                            updateUsersTooltips(data.reactionsUsers);
                        }
                    } catch (error) {
                        console.error('Error:', error);
                    }
                });
            });

            // Функция для обновления тултипов с пользователями
            function updateUsersTooltips(usersData) {
                const reactionTitles = {
                    'like': 'Нравится',
                    'dislike': 'Не нравится',
                    'heart': 'Люблю',
                    'laugh': 'Радость',
                    'wow': 'Удивительно'
                };

                for (const [reaction, reactions] of Object.entries(usersData)) {
                    // Обновляем тултипы для десктопной версии
                    const desktopTooltip = document.querySelector(`.actions-desktop .reaction-btn[data-reaction="${reaction}"]`)
                        ?.closest('.group')
                        ?.querySelector('.reaction-tooltip');
                    
                    if (desktopTooltip) {
                        updateTooltipContent(desktopTooltip, reactionTitles[reaction], reactions);
                    }

                    // Обновляем тултипы для мобильной версии
                    const mobileTooltip = document.querySelector(`.reactions-mobile .reaction-btn[data-reaction="${reaction}"]`)
                        ?.closest('.group')
                        ?.querySelector('.reaction-tooltip');
                    
                    if (mobileTooltip) {
                        updateTooltipContent(mobileTooltip, reactionTitles[reaction], reactions);
                    }
                }
            }

            function updateTooltipContent(tooltip, title, reactions) {
                const titleElement = tooltip.querySelector('.reaction-title');
                const usersListElement = tooltip.querySelector('.users-list');
                
                titleElement.textContent = title;
                
                usersListElement.innerHTML = reactions.map(item => `
                    <div class="user-item">
                        <span class="user-name">${item.user}</span>
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
    </script>
</x-app-layout>
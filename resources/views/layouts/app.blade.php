<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @isset($note)
        <meta name="note-id" content="{{ $note->id }}">
    @endisset

    <title>{{ $title ?? config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Подключение Vite -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Inline-скрипт для предотвращения мерцания -->
    <script>
        (function() {
            const savedTheme = localStorage.getItem('theme');
            const systemTheme = window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light';
            document.documentElement.classList.toggle('dark', savedTheme === 'dark' || (!savedTheme && systemTheme === 'dark'));
        })();
    </script>
    
    @stack('styles')

    @livewireStyles
</head>

<body class="font-sans antialiased">
    <div class="bg-gray-200 min-h-screen dark:bg-gray-900">
        <livewire:layout.navigation />

        @if (isset($header))
        <header class="bg-gray-400 dark:bg-gray-800 shadow">
            <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                {{ $header }}
            </div>
        </header>
        @endif

        <main class="bg-gray-200 dark:bg-gray-900">
            <div class="snowblock"></div>
            {{ $slot }}
        </main>
    </div>

    @livewireScripts

@push('scripts')
<script>
document.addEventListener('livewire:init', () => {
    // Глобальный кэш
    window.avatarCache = window.avatarCache || new Map();
    
    // Восстановление из localStorage
    try {
        const saved = localStorage.getItem('avatar_cache');
        if (saved) {
            Object.entries(JSON.parse(saved)).forEach(([id, data]) => {
                if (!window.avatarCache.has(id)) {
                    window.avatarCache.set(id, data);
                }
            });
        }
    } catch(e) {}

    // Обновление кэша при загрузке изображения
    window.updateAvatarCache = (img) => {
        const userId = img.dataset.userId;
        if (userId && img.src && !img.src.includes('ui-avatars.com')) {
            window.avatarCache.set(userId, {
                url: img.src,
                hash: img.dataset.avatarHash,
                timestamp: Date.now()
            });
            // Сохраняем в localStorage
            try {
                const cache = JSON.parse(localStorage.getItem('avatar_cache') || '{}');
                cache[userId] = { url: img.src, hash: img.dataset.avatarHash };
                localStorage.setItem('avatar_cache', JSON.stringify(cache));
            } catch(e) {}
        }
    };

    // Применение кэша к новым аватаркам
    window.processNewAvatars = (root = document) => {
        root.querySelectorAll('img[data-user-id]:not([data-cached="true"])').forEach(img => {
            const userId = img.dataset.userId;
            const cached = window.avatarCache.get(userId);
            
            if (cached?.url && cached.url !== img.src) {
                // Проверяем хэш, если есть
                if (!img.dataset.avatarHash || cached.hash === img.dataset.avatarHash) {
                    img.src = cached.url;
                    img.dataset.cached = 'true';
                }
            }
            
            // Слушаем загрузку для обновления кэша
            if (img.complete) {
                window.updateAvatarCache(img);
            } else {
                img.addEventListener('load', () => window.updateAvatarCache(img), { once: true });
            }
        });
    };

    // Хуки Livewire для точного обновления
    Livewire.hook('morph.added', ({el}) => {
        if (el.matches?.('img[data-user-id]') || el.querySelector?.('img[data-user-id]')) {
            window.processNewAvatars(el.matches('img[data-user-id]') ? el.parentElement : el);
        }
    });

    Livewire.hook('request.finished', ({status, response}) => {
        if (status === 200) {
            setTimeout(() => window.processNewAvatars(), 50);
        }
    });

    // Инициализация при загрузке
    document.addEventListener('DOMContentLoaded', () => {
        window.processNewAvatars();
    });
});
</script>
@endpush

    <script src="https://cdn.jsdelivr.net/npm/@laragear/webpass@2/dist/webpass.js" defer></script>
    
    @stack('scripts')
</body>

</html>
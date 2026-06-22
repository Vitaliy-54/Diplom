<!DOCTYPE html>
<html lang="<?php echo e(str_replace('_', '-', app()->getLocale())); ?>" class="dark">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <?php if(isset($note)): ?>
        <meta name="note-id" content="<?php echo e($note->id); ?>">
    <?php endif; ?>

    <title><?php echo e($title ?? config('app.name', 'Laravel')); ?></title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Подключение Vite -->
    <?php echo app('Illuminate\Foundation\Vite')(['resources/css/app.css', 'resources/js/app.js']); ?>

    <!-- Inline-скрипт для предотвращения мерцания -->
    <script>
        (function() {
            const savedTheme = localStorage.getItem('theme');
            const systemTheme = window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light';
            document.documentElement.classList.toggle('dark', savedTheme === 'dark' || (!savedTheme && systemTheme === 'dark'));
        })();
    </script>
    
    <?php echo $__env->yieldPushContent('styles'); ?>

    <?php echo \Livewire\Mechanisms\FrontendAssets\FrontendAssets::styles(); ?>

</head>

<body class="font-sans antialiased">
    <div class="bg-gray-200 min-h-screen dark:bg-gray-900">
        <?php
$__split = function ($name, $params = []) {
    return [$name, $params];
};
[$__name, $__params] = $__split('layout.navigation', []);

$__html = app('livewire')->mount($__name, $__params, 'lw-2913031548-0', $__slots ?? [], get_defined_vars());

echo $__html;

unset($__html);
unset($__name);
unset($__params);
unset($__split);
if (isset($__slots)) unset($__slots);
?>

        <?php if(isset($header)): ?>
        <header class="bg-gray-400 dark:bg-gray-800 shadow">
            <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                <?php echo e($header); ?>

            </div>
        </header>
        <?php endif; ?>

        <main class="bg-gray-200 dark:bg-gray-900">
            <div class="snowblock"></div>
            <?php echo e($slot); ?>

        </main>
    </div>

    <?php echo \Livewire\Mechanisms\FrontendAssets\FrontendAssets::scripts(); ?>


<?php $__env->startPush('scripts'); ?>
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
<?php $__env->stopPush(); ?>

    <script src="https://cdn.jsdelivr.net/npm/@laragear/webpass@2/dist/webpass.js" defer></script>
    
    <?php echo $__env->yieldPushContent('scripts'); ?>
</body>

</html><?php /**PATH C:\Users\Desktop_54\Desktop\neocalc.site_share_link_30.05\resources\views/layouts/app.blade.php ENDPATH**/ ?>
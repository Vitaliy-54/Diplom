
<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames((['user', 'size' => 'w-8 h-8']));

foreach ($attributes->all() as $__key => $__value) {
    if (in_array($__key, $__propNames)) {
        $$__key = $$__key ?? $__value;
    } else {
        $__newAttributes[$__key] = $__value;
    }
}

$attributes = new \Illuminate\View\ComponentAttributeBag($__newAttributes);

unset($__propNames);
unset($__newAttributes);

foreach (array_filter((['user', 'size' => 'w-8 h-8']), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars); ?>

<?php
    $avatarDir = "avatars/{$user->id}";
    $avatarFile = collect(Storage::files($avatarDir))
        ->first(fn($f) => preg_match('/^avatars\/' . $user->id . '\/avatar\.(jpg|jpeg|png|svg|gif)$/i', $f));
    
    $avatarUrl = $avatarFile 
        ? route('avatar.serve', ['user' => $user->id, 'filename' => basename($avatarFile)]) 
        : null;
    
    $timestamp = $avatarFile && file_exists(Storage::path($avatarFile)) 
        ? filemtime(Storage::path($avatarFile)) 
        : ($user->updated_at?->timestamp ?? time());
    
    $defaultAvatarUrl = 'https://ui-avatars.com/api/?background=4F46E5&color=fff&name=' . urlencode($user->name);
    $avatarHash = md5($user->id . '_' . $timestamp);
    
    // ✅ Убрали static $instanceCount — не нужен, создаёт нестабильные ключи в Livewire
    $uniqueKey = "avatar-{$user->id}-{$avatarHash}";
?>

<div 
    class="relative flex-shrink-0 avatar-container" 
    <?php echo e($attributes); ?>

    
    wire:ignore.self
    wire:key="<?php echo e($uniqueKey); ?>"
>
    <?php if($avatarUrl): ?>
        <img 
            src="<?php echo e($avatarUrl); ?>?v=<?php echo e($timestamp); ?>"
            alt="<?php echo e($user->name); ?>"
            class="<?php echo e($size); ?> rounded-full object-cover bg-gray-200 dark:bg-gray-700 avatar-image"
            loading="lazy"
            decoding="async"
            
            wire:key="img-<?php echo e($uniqueKey); ?>"
            data-user-id="<?php echo e($user->id); ?>"
            data-avatar-hash="<?php echo e($avatarHash); ?>"
            
            onload="window.updateAvatarCache?.(this)"
            
            onerror="this.onerror=null; this.src='<?php echo e($defaultAvatarUrl); ?>'; this.dataset.fallback='true'; window.updateAvatarCache?.(this);"
        >
    <?php else: ?>
        
        <img 
            src="<?php echo e($defaultAvatarUrl); ?>"
            alt="<?php echo e($user->name); ?>"
            class="<?php echo e($size); ?> rounded-full object-cover avatar-default"
            loading="lazy"
            decoding="async"
            wire:key="img-default-<?php echo e($user->id); ?>"
            data-user-id="<?php echo e($user->id); ?>"
            data-avatar-hash="<?php echo e($avatarHash); ?>"
            data-fallback="true"
            onload="window.updateAvatarCache?.(this)"
        >
    <?php endif; ?>
</div>

<style>
    /* ✅ Плавное появление через CSS, а не inline-стили */
    .avatar-image,
    .avatar-default {
        opacity: 0;
        transition: opacity 0.2s ease-in-out;
    }
    .avatar-image[onload],
    .avatar-default[onload],
    .avatar-image.loaded,
    .avatar-default.loaded {
        opacity: 1;
    }
    /* Для уже загруженных из кэша браузером */
    .avatar-image[complete],
    .avatar-default[complete] {
        opacity: 1;
    }
</style>


<?php $__env->startPush('scripts'); ?>
<script>
document.addEventListener('alpine:init', () => {
    Alpine.magic('avatar', el => ({
        init(img) {
            if (img.complete) {
                img.classList.add('loaded');
                window.updateAvatarCache?.(img);
            } else {
                img.addEventListener('load', () => {
                    img.classList.add('loaded');
                    window.updateAvatarCache?.(img);
                }, { once: true });
                img.addEventListener('error', () => {
                    img.classList.add('loaded');
                    window.updateAvatarCache?.(img);
                }, { once: true });
            }
        }
    }));
});
</script>
<?php $__env->stopPush(); ?><?php /**PATH C:\Users\Desktop_54\Desktop\neocalc.site_share_link_30.05\resources\views/components/avatar.blade.php ENDPATH**/ ?>
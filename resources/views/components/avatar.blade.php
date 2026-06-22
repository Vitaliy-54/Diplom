{{-- resources/views/components/avatar.blade.php --}}
@props(['user', 'size' => 'w-8 h-8'])

@php
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
@endphp

<div 
    class="relative flex-shrink-0 avatar-container" 
    {{ $attributes }}
    {{-- ✅ wire:ignore.self: не морфим контейнер, если аватар не меняется динамически --}}
    wire:ignore.self
    wire:key="{{ $uniqueKey }}"
>
    @if($avatarUrl)
        <img 
            src="{{ $avatarUrl }}?v={{ $timestamp }}"
            alt="{{ $user->name }}"
            class="{{ $size }} rounded-full object-cover bg-gray-200 dark:bg-gray-700 avatar-image"
            loading="lazy"
            decoding="async"
            {{-- ✅ Стабильный wire:key + data-атрибуты для кэша --}}
            wire:key="img-{{ $uniqueKey }}"
            data-user-id="{{ $user->id }}"
            data-avatar-hash="{{ $avatarHash }}"
            {{-- ✅ Упрощённый onload: только обновление кэша, без манипуляций с opacity --}}
            onload="window.updateAvatarCache?.(this)"
            {{-- ✅ Fallback тоже имеет data-user-id для кэширования --}}
            onerror="this.onerror=null; this.src='{{ $defaultAvatarUrl }}'; this.dataset.fallback='true'; window.updateAvatarCache?.(this);"
        >
    @else
        {{-- ✅ Дефолтный аватар тоже имеет data-атрибуты! --}}
        <img 
            src="{{ $defaultAvatarUrl }}"
            alt="{{ $user->name }}"
            class="{{ $size }} rounded-full object-cover avatar-default"
            loading="lazy"
            decoding="async"
            wire:key="img-default-{{ $user->id }}"
            data-user-id="{{ $user->id }}"
            data-avatar-hash="{{ $avatarHash }}"
            data-fallback="true"
            onload="window.updateAvatarCache?.(this)"
        >
    @endif
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

{{-- ✅ Alpine.js для надёжного управления состоянием (опционально, если используете Alpine) --}}
@push('scripts')
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
@endpush
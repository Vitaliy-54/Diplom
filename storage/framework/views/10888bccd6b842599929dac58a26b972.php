<?php

use App\Livewire\Forms\LoginForm;
use Illuminate\Support\Facades\Session;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

?>

    <?php
        $__assetKey = '1917712681-0';

        ob_start();
    ?>
<style>
    /* Основные переменные цветов */
    :root {
        --primary-color: #6366f1;
        --primary-hover: #4f46e5;
        --text-color: #f3f4f6;
        --text-secondary: #9ca3af;
        --bg-color: #1f2937;
        --bg-secondary: #374151;
        --border-color: #4b5563;
        --error-color: #ef4444;
        --success-color: #10b981;
    }

    /* Анимация появления */
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }

    .auth-form {
        animation: fadeIn 0.6s ease-out forwards;
    }

    /* Заголовок формы */
    .auth-title {
        font-size: 1.75rem;
        font-weight: 700;
        color: var(--text-color);
        text-align: center;
        margin-bottom: 2rem;
        position: relative;
        padding-bottom: 1rem;
    }

    .auth-title::after {
        content: '';
        position: absolute;
        bottom: 0;
        left: 50%;
        transform: translateX(-50%);
        width: 40%;
        height: 3px;
        background: linear-gradient(90deg, var(--primary-color), transparent);
        border-radius: 3px;
    }

    /* Поля ввода */
    .input-group {
        margin-bottom: 1.5rem;
        position: relative;
    }

    /* Иконка глаза для пароля */
    .password-toggle {
        position: absolute;
        right: 12px;
        top: 50%;
        transform: translateY(-50%);
        cursor: pointer;
        color: var(--text-secondary);
        transition: all 0.2s ease;
    }

    .password-toggle:hover {
        color: var(--primary-color);
        transform: translateY(-50%) scale(1.1);
    }

    /* Чекбокс "Запомнить меня" */
    .remember-me {
        display: flex;
        align-items: center;
        margin: 1.5rem 0;
    }

    .remember-checkbox {
        appearance: none;
        width: 18px;
        height: 18px;
        border: 1px solid var(--border-color);
        border-radius: 4px;
        margin-right: 0.75rem;
        cursor: pointer;
        position: relative;
        transition: all 0.3s ease;
    }

    .remember-checkbox:checked {
        background-color: var(--primary-color);
        border-color: var(--primary-color);
    }

    .remember-checkbox::after {
        content: '';
        position: absolute;
        top: 2px;
        left: 5px;
        width: 4px;
        height: 8px;
        border: solid white;
        border-width: 0 2px 2px 0;
        transform: rotate(45deg);
        opacity: 0;
        transition: opacity 0.2s ease;
    }

    .remember-checkbox:checked::after {
        opacity: 1;
    }

    .remember-label {
        color: var(--text-secondary);
        font-size: 0.9rem;
        cursor: pointer;
        transition: color 0.2s ease;
    }

    .remember-label:hover {
        color: var(--text-color);
    }

    /* Кнопка входа */
    .submit-btn {
        width: 100%;
        padding: 0.75rem;
        background: linear-gradient(135deg, var(--primary-color), var(--primary-hover));
        color: white;
        border: none;
        border-radius: 8px;
        font-size: 1rem;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
        box-shadow: 0 4px 15px rgba(99, 102, 241, 0.3);
    }

    .submit-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(99, 102, 241, 0.4);
    }

    .submit-btn:active {
        transform: translateY(0);
    }

/* Стиль для кнопки WebAuthn - светло-серый */
.webauthn-btn {
    background: rgba(235, 236, 239, 0.12);  /* серый 12% прозрачности */
    border: 1px solid rgba(156, 163, 175, 0.2);
    margin-top: 0rem;
    transition: all 0.3s ease;
    color: #cfd0d1;  /* цвет текста */
}

.webauthn-btn:hover {
    background: rgba(156, 163, 175, 0.2);
    border-color: rgba(8, 80, 212, 0.4);
    color: #e4e4e4;  /* цвет текста при наведении */
}

    .webauthn-btn:disabled {
        opacity: 0.7;
        cursor: not-allowed;
        transform: none;
    }

    /* Разделитель */
    .divider {
        display: flex;
        align-items: center;
        text-align: center;
        margin: 1.5rem 0 1rem;
    }

    .divider::before,
    .divider::after {
        content: '';
        flex: 1;
        border-bottom: 1px solid var(--border-color);
    }

    .divider span {
        padding: 0 0.75rem;
        color: var(--text-secondary);
        font-size: 0.875rem;
    }

    /* Эффект волны при клике */
    .submit-btn::after {
        content: '';
        position: absolute;
        top: 50%;
        left: 50%;
        width: 5px;
        height: 5px;
        background: rgba(255, 255, 255, 0.5);
        opacity: 0;
        border-radius: 100%;
        transform: scale(1, 1) translate(-50%);
        transform-origin: 50% 50%;
    }

    .submit-btn:focus:not(:active)::after {
        animation: ripple 0.6s ease-out;
    }

    @keyframes ripple {
        0% {
            transform: scale(0, 0);
            opacity: 0.5;
        }
        100% {
            transform: scale(20, 20);
            opacity: 0;
        }
    }

    /* Индикатор загрузки */
    .loading-spinner {
        display: inline-block;
        width: 16px;
        height: 16px;
        border: 2px solid rgba(255, 255, 255, 0.3);
        border-radius: 50%;
        border-top-color: white;
        animation: spin 0.8s linear infinite;
        margin-left: 8px;
        vertical-align: middle;
    }

    @keyframes spin {
        to { transform: rotate(360deg); }
    }

    /* Ссылки под формой */
    .auth-links {
        display: flex;
        justify-content: space-between;
        margin-top: 1.5rem;
        font-size: 0.9rem;
    }

    .auth-link {
        color: var(--text-secondary);
        text-decoration: none;
        position: relative;
        transition: all 0.3s ease;
    }

    .auth-link:hover {
        color: var(--primary-color);
    }

    .auth-link::after {
        content: '';
        position: absolute;
        bottom: -2px;
        left: 0;
        width: 0;
        height: 1px;
        background-color: var(--primary-color);
        transition: width 0.3s ease;
    }

    .auth-link:hover::after {
        width: 100%;
    }

    /* Сообщения об ошибках */
    .error-message {
        color: var(--error-color);
        font-size: 0.8rem;
        margin-top: 0.5rem;
        animation: shake 0.4s ease;
    }

    @keyframes shake {
        0%, 100% { transform: translateX(0); }
        20%, 60% { transform: translateX(-5px); }
        40%, 80% { transform: translateX(5px); }
    }

    /* Custom styles for inputs */
    .block {
        display: block;
    }
    
    .w-full {
        width: 100%;
    }
    
    .mt-1 {
        margin-top: 0.25rem;
    }
    
    .mt-2 {
        margin-top: 0.5rem;
    }
    
    /* Стили для текстовых полей */
    .custom-text-input {
        width: 100%;
        padding: 0.85rem 1.25rem;
        background-color: rgba(255, 255, 255, 0.05);
        border: 1px solid var(--border-color);
        border-radius: 8px;
        color: var(--text-color);
        font-size: 0.95rem;
        transition: all 0.3s ease;
    }
    
    .custom-text-input:focus {
        outline: none;
        border-color: var(--primary-color);
        box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.2);
        background-color: rgba(255, 255, 255, 0.08);
    }
    
    .custom-text-input::placeholder {
        color: var(--text-secondary);
        opacity: 0.7;
    }
    
    /* Стили для лейблов */
    .custom-input-label {
        display: block;
        margin-bottom: 0.5rem;
        color: var(--text-color);
        font-size: 0.9rem;
        font-weight: 500;
        transition: all 0.3s ease;
    }

    /* Стили для сканера отпечатка пальца */
    .fingerprint-wrapper {
        position: relative;
        width: 32px;
        height: 32px;
    }

    .fingerprint-icon {
        width: 100%;
        height: 100%;
        position: relative;
        z-index: 2;
    }

    .scan-overlay {
        position: absolute;
        inset: 0;
        overflow: hidden;
        pointer-events: none;
        z-index: 3;
        border-radius: 50%;
    }

    .scan-line {
        position: absolute;
        left: 0;
        width: 100%;
        height: 1px;
        background: #22d3ee;
        box-shadow: 0 0 10px #22d3ee;
        animation: scanFinger 3s linear infinite;
    }

    /* Анимация при наведении на кнопку - ускоряем сканер */
    .webauthn-btn:hover .scan-line {
        animation: scanFinger 3s linear infinite;
        background: #22d3ee;
        box-shadow: 0 0 10px #22d3ee;
    }

    @keyframes scanFinger {
        0% {
            top: -10%;
            opacity: 0;
        }
        10% {
            opacity: 1;
        }
        50% {
            top: 50%;
            opacity: 1;
        }
        90% {
            opacity: 1;
        }
        100% {
            top: 110%;
            opacity: 0;
        }
    }

    /* Анимация при загрузке */
    .webauthn-btn.scanning .scan-line {
        animation: scanFinger 0.6s linear infinite;
        background: #f59e0b;
        box-shadow: 0 0 10px #f59e0b;
    }

    /* Адаптивность для мобильных */
    @media (max-width: 480px) {
        
        .auth-title {
            font-size: 1.5rem;
            margin-bottom: 1.25rem;
            padding-bottom: 0.75rem;
        }
        
        .auth-title::after {
            width: 60%;
        }
        
        .input-group {
            margin-bottom: 1.25rem;
        }
        
        .custom-text-input {
            padding: 0.75rem 1rem;
            font-size: 0.9rem;
        }
        
        .submit-btn {
            padding: 0.85rem;
            font-size: 0.95rem;
        }
        
        .remember-me {
            margin: 1.25rem 0;
        }
        
        .remember-checkbox {
            width: 16px;
            height: 16px;
        }
        
        .remember-label {
            font-size: 0.85rem;
        }
        
        .auth-links {
            flex-direction: column;
            gap: 0.75rem;
            margin-top: 1.25rem;
        }
        
        .auth-link {
            text-align: center;
        }
        
        .password-toggle svg {
            width: 18px;
            height: 18px;
        }
    }

    /* Для очень маленьких экранов */
    @media (max-width: 360px) {  
        .auth-title {
            font-size: 1.35rem;
        }
        
        .submit-btn {
            padding: 0.75rem;
        }
    }
</style>
    <?php
        $__output = ob_get_clean();

        // If the asset has already been loaded anywhere during this request, skip it...
        if (in_array($__assetKey, \Livewire\Features\SupportScriptsAndAssets\SupportScriptsAndAssets::$alreadyRunAssetKeys)) {
            // Skip it...
        } else {
            \Livewire\Features\SupportScriptsAndAssets\SupportScriptsAndAssets::$alreadyRunAssetKeys[] = $__assetKey;

            // Check if we're in a Livewire component or not and store the asset accordingly...
            if (isset($this)) {
                \Livewire\store($this)->push('assets', $__output, $__assetKey);
            } else {
                \Livewire\Features\SupportScriptsAndAssets\SupportScriptsAndAssets::$nonLivewireAssets[$__assetKey] = $__output;
            }
        }
    ?>

<div class="auth-container">
     <?php $__env->slot('title', null, []); ?> 
        <?php echo e(__('Вход')); ?>

     <?php $__env->endSlot(); ?>

    <div class="auth-form">
        <h1 class="auth-title">
            <?php echo e(__('Добро пожаловать')); ?>

        </h1>

        <!-- Session Status -->
        <?php if (isset($component)) { $__componentOriginal7c1bf3a9346f208f66ee83b06b607fb5 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal7c1bf3a9346f208f66ee83b06b607fb5 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.auth-session-status','data' => ['class' => 'mb-4','status' => session('status')]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('auth-session-status'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'mb-4','status' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(session('status'))]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal7c1bf3a9346f208f66ee83b06b607fb5)): ?>
<?php $attributes = $__attributesOriginal7c1bf3a9346f208f66ee83b06b607fb5; ?>
<?php unset($__attributesOriginal7c1bf3a9346f208f66ee83b06b607fb5); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal7c1bf3a9346f208f66ee83b06b607fb5)): ?>
<?php $component = $__componentOriginal7c1bf3a9346f208f66ee83b06b607fb5; ?>
<?php unset($__componentOriginal7c1bf3a9346f208f66ee83b06b607fb5); ?>
<?php endif; ?>

        <form wire:submit="login" id="login-form">
            <!-- Email Address -->
            <div class="input-group">
                <?php if (isset($component)) { $__componentOriginale3da9d84bb64e4bc2eeebaafabfb2581 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginale3da9d84bb64e4bc2eeebaafabfb2581 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.input-label','data' => ['for' => 'email','value' => __('Электронная почта'),'class' => 'custom-input-label']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('input-label'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['for' => 'email','value' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(__('Электронная почта')),'class' => 'custom-input-label']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginale3da9d84bb64e4bc2eeebaafabfb2581)): ?>
<?php $attributes = $__attributesOriginale3da9d84bb64e4bc2eeebaafabfb2581; ?>
<?php unset($__attributesOriginale3da9d84bb64e4bc2eeebaafabfb2581); ?>
<?php endif; ?>
<?php if (isset($__componentOriginale3da9d84bb64e4bc2eeebaafabfb2581)): ?>
<?php $component = $__componentOriginale3da9d84bb64e4bc2eeebaafabfb2581; ?>
<?php unset($__componentOriginale3da9d84bb64e4bc2eeebaafabfb2581); ?>
<?php endif; ?>
                <?php if (isset($component)) { $__componentOriginal18c21970322f9e5c938bc954620c12bb = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal18c21970322f9e5c938bc954620c12bb = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.text-input','data' => ['wire:model' => 'form.email','id' => 'email','class' => 'custom-text-input','type' => 'email','name' => 'email','required' => true,'autocomplete' => 'username','placeholder' => 'your@email.com']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('text-input'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['wire:model' => 'form.email','id' => 'email','class' => 'custom-text-input','type' => 'email','name' => 'email','required' => true,'autocomplete' => 'username','placeholder' => 'your@email.com']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal18c21970322f9e5c938bc954620c12bb)): ?>
<?php $attributes = $__attributesOriginal18c21970322f9e5c938bc954620c12bb; ?>
<?php unset($__attributesOriginal18c21970322f9e5c938bc954620c12bb); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal18c21970322f9e5c938bc954620c12bb)): ?>
<?php $component = $__componentOriginal18c21970322f9e5c938bc954620c12bb; ?>
<?php unset($__componentOriginal18c21970322f9e5c938bc954620c12bb); ?>
<?php endif; ?>
                <?php if (isset($component)) { $__componentOriginalf94ed9c5393ef72725d159fe01139746 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalf94ed9c5393ef72725d159fe01139746 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.input-error','data' => ['messages' => $errors->get('form.email'),'class' => 'error-message']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('input-error'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['messages' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($errors->get('form.email')),'class' => 'error-message']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalf94ed9c5393ef72725d159fe01139746)): ?>
<?php $attributes = $__attributesOriginalf94ed9c5393ef72725d159fe01139746; ?>
<?php unset($__attributesOriginalf94ed9c5393ef72725d159fe01139746); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalf94ed9c5393ef72725d159fe01139746)): ?>
<?php $component = $__componentOriginalf94ed9c5393ef72725d159fe01139746; ?>
<?php unset($__componentOriginalf94ed9c5393ef72725d159fe01139746); ?>
<?php endif; ?>
            </div>

            <!-- Password -->
            <div class="input-group">
                <?php if (isset($component)) { $__componentOriginale3da9d84bb64e4bc2eeebaafabfb2581 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginale3da9d84bb64e4bc2eeebaafabfb2581 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.input-label','data' => ['for' => 'password','value' => __('Пароль'),'class' => 'custom-input-label']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('input-label'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['for' => 'password','value' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(__('Пароль')),'class' => 'custom-input-label']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginale3da9d84bb64e4bc2eeebaafabfb2581)): ?>
<?php $attributes = $__attributesOriginale3da9d84bb64e4bc2eeebaafabfb2581; ?>
<?php unset($__attributesOriginale3da9d84bb64e4bc2eeebaafabfb2581); ?>
<?php endif; ?>
<?php if (isset($__componentOriginale3da9d84bb64e4bc2eeebaafabfb2581)): ?>
<?php $component = $__componentOriginale3da9d84bb64e4bc2eeebaafabfb2581; ?>
<?php unset($__componentOriginale3da9d84bb64e4bc2eeebaafabfb2581); ?>
<?php endif; ?>
                <div class="relative">
                    <?php if (isset($component)) { $__componentOriginal18c21970322f9e5c938bc954620c12bb = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal18c21970322f9e5c938bc954620c12bb = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.text-input','data' => ['wire:model' => 'form.password','id' => 'password','class' => 'custom-text-input pr-10','type' => 'password','name' => 'password','required' => true,'autocomplete' => 'current-password','placeholder' => '••••••••']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('text-input'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['wire:model' => 'form.password','id' => 'password','class' => 'custom-text-input pr-10','type' => 'password','name' => 'password','required' => true,'autocomplete' => 'current-password','placeholder' => '••••••••']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal18c21970322f9e5c938bc954620c12bb)): ?>
<?php $attributes = $__attributesOriginal18c21970322f9e5c938bc954620c12bb; ?>
<?php unset($__attributesOriginal18c21970322f9e5c938bc954620c12bb); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal18c21970322f9e5c938bc954620c12bb)): ?>
<?php $component = $__componentOriginal18c21970322f9e5c938bc954620c12bb; ?>
<?php unset($__componentOriginal18c21970322f9e5c938bc954620c12bb); ?>
<?php endif; ?>
                    <span class="password-toggle" onclick="togglePasswordVisibility()">
                        <svg id="eye-icon" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                        </svg>
                    </span>
                </div>
                <?php if (isset($component)) { $__componentOriginalf94ed9c5393ef72725d159fe01139746 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalf94ed9c5393ef72725d159fe01139746 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.input-error','data' => ['messages' => $errors->get('form.password'),'class' => 'error-message']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('input-error'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['messages' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($errors->get('form.password')),'class' => 'error-message']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalf94ed9c5393ef72725d159fe01139746)): ?>
<?php $attributes = $__attributesOriginalf94ed9c5393ef72725d159fe01139746; ?>
<?php unset($__attributesOriginalf94ed9c5393ef72725d159fe01139746); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalf94ed9c5393ef72725d159fe01139746)): ?>
<?php $component = $__componentOriginalf94ed9c5393ef72725d159fe01139746; ?>
<?php unset($__componentOriginalf94ed9c5393ef72725d159fe01139746); ?>
<?php endif; ?>
            </div>

            <!-- Remember Me -->
            <div class="remember-me">
                <input wire:model="form.remember" id="remember" type="checkbox" class="remember-checkbox">
                <label for="remember" class="remember-label"><?php echo e(__('Запомнить меня')); ?></label>
            </div>

            <!-- Submit Button -->
            <button type="submit" class="submit-btn">
                <?php echo e(__('Войти')); ?>

                <span wire:loading wire:target="login" class="loading-spinner"></span>
            </button>
        </form>

        <!-- WebAuthn Section -->
        <div class="divider" style="margin-top: 0.75rem; margin-bottom: 0.75rem;">
            <span>или</span>
        </div>

        <button 
            type="button" 
            id="loginWithPasskey" 
            class="submit-btn webauthn-btn group relative overflow-hidden"
        >
            <span class="relative flex items-center justify-center gap-3">
                <!-- Контейнер с отпечатком и анимацией сканера -->
                <div class="fingerprint-wrapper">
                    <!-- SVG отпечатка пальца -->
                    <svg class="fingerprint-icon w-8 h-8 transition-transform duration-300 group-hover:scale-110" viewBox="0 0 192 192" xmlns="http://www.w3.org/2000/svg" fill="none">
                        <path fill="currentColor" stroke="currentColor" stroke-width="4" d="M140.424 38.019a3.6 3.6 0 0 1-1.777-.462C123.81 29.934 110.983 26.7 95.528 26.7c-15.223 0-29.75 3.619-42.964 10.857-1.854 1.001-4.172.308-5.254-1.54-1.005-1.848-.31-4.235 1.545-5.236C63.228 22.85 78.992 19 95.528 19c16.537 0 30.91 3.619 46.673 11.55 1.932 1.155 2.628 3.465 1.623 5.313-.695 1.386-1.932 2.156-3.4 2.156ZM29.846 78.444a4.036 4.036 0 0 1-2.24-.693c-1.624-1.232-2.165-3.619-.928-5.39 7.65-10.78 17.386-19.25 28.977-25.179 24.419-12.474 55.328-12.551 79.669-.077 11.591 5.929 21.327 14.245 28.977 25.025 1.237 1.694.773 4.158-.927 5.39-1.777 1.232-4.173.847-5.409-.77-6.955-9.856-15.764-17.479-26.196-22.792-22.177-11.319-50.536-11.319-72.636.077-10.51 5.39-19.319 13.09-26.273 22.715-.618 1.155-1.778 1.694-3.014 1.694Zm48.296 92.939c-1.005 0-1.932-.385-2.705-1.155-6.722-6.699-10.354-11.011-15.532-20.328-5.332-9.471-8.113-21.021-8.113-33.418 0-22.869 19.627-41.503 43.736-41.503 24.11 0 43.737 18.634 43.737 41.503 0 1.021-.407 2-1.132 2.722a3.87 3.87 0 0 1-5.464 0 3.844 3.844 0 0 1-1.131-2.722c0-18.634-16.15-33.803-36.01-33.803-19.859 0-36.01 15.169-36.01 33.803 0 11.088 2.474 21.329 7.187 29.568 4.946 8.932 8.346 12.705 14.296 18.711a3.943 3.943 0 0 1 0 5.467c-.927.77-1.855 1.155-2.86 1.155Zm55.405-14.245c-9.196 0-17.309-2.31-23.955-6.853-11.514-7.777-18.391-20.405-18.391-33.803 0-1.021.407-2 1.132-2.722a3.871 3.871 0 0 1 5.464 0 3.843 3.843 0 0 1 1.131 2.722c0 10.857 5.564 21.098 14.991 27.412 5.487 3.696 11.9 5.467 19.628 5.467 1.854 0 4.945-.231 8.036-.77 2.087-.385 4.173 1.001 4.482 3.157.386 2.002-1.005 4.081-3.168 4.466-4.405.847-8.268.924-9.35.924ZM118.015 173h-1.005c-12.286-3.542-20.323-8.085-28.745-16.324-10.819-10.626-16.769-24.948-16.769-40.194 0-12.474 10.664-22.638 23.8-22.638 13.137 0 23.801 10.164 23.801 22.638 0 8.239 7.341 14.938 16.072 14.938 8.887 0 16.073-6.699 16.073-14.938 0-29.029-25.114-52.591-56.023-52.591-21.945 0-42.191 12.166-51.077 31.031-3.014 6.237-4.56 13.552-4.56 21.56 0 6.006.541 15.477 5.178 27.797.773 2.002-.232 4.235-2.241 4.928-2.01.693-4.25-.308-4.946-2.233-3.863-10.087-5.64-20.174-5.64-30.492 0-9.24 1.777-17.633 5.254-24.948 10.277-21.483 33.073-35.42 58.032-35.42 35.082 0 63.751 27.027 63.751 60.291 0 12.474-10.664 22.638-23.801 22.638-13.136 0-23.8-10.164-23.8-22.638 0-8.239-7.186-14.938-16.073-14.938-8.886 0-16.072 6.699-16.072 14.938 0 13.167 5.1 25.487 14.45 34.727 7.341 7.238 14.373 11.242 25.268 14.168 2.086.616 3.246 2.772 2.705 4.774-.387 1.771-2.009 2.926-3.632 2.926Z"/>
                    </svg>
                    <!-- Анимация сканера -->
                    <div class="scan-overlay">
                        <div class="scan-line"></div>
                    </div>
                </div>
                <span>Войти по биометрии</span>
            </span>
        </button>

        <!-- Кнопка инструкции -->
        <div class="mt-3 text-center">
            <a href="<?php echo e(route('passkey.instruction')); ?>" class="text-xs text-gray-500 hover:text-indigo-400 transition-colors duration-200 inline-flex items-center gap-1">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <span>Как создать ключ? Инструкция</span>
            </a>
        </div>

        <!-- Additional Links -->
        <div class="auth-links">
            <!--[if BLOCK]><![endif]--><?php if(Route::has('password.request')): ?>
                <a class="auth-link" href="<?php echo e(route('password.request')); ?>" wire:navigate>
                    <?php echo e(__('Забыли пароль?')); ?>

                </a>
            <?php endif; ?><!--[if ENDBLOCK]><![endif]-->

            <!--[if BLOCK]><![endif]--><?php if(Route::has('register')): ?>
                <a class="auth-link" href="<?php echo e(secure_url('register')); ?>" wire:navigate>
                    <?php echo e(__('Создать аккаунт')); ?>

                </a>
            <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
        </div>
    </div>
</div>

<script>
    // Функция переключения видимости пароля
    function togglePasswordVisibility() {
        const passwordInput = document.getElementById('password');
        const eyeIcon = document.getElementById('eye-icon');
        const isMobile = window.innerWidth <= 480;
        
        if (isMobile) {
            eyeIcon.parentElement.style.transform = 'translateY(-50%) scale(0.95)';
            setTimeout(() => {
                eyeIcon.parentElement.style.transform = 'translateY(-50%) scale(1)';
            }, 150);
        } else {
            eyeIcon.parentElement.style.transform = 'translateY(-50%) scale(0.9)';
            setTimeout(() => {
                eyeIcon.parentElement.style.transform = 'translateY(-50%) scale(1.1)';
            }, 50);
            setTimeout(() => {
                eyeIcon.parentElement.style.transform = 'translateY(-50%) scale(1)';
            }, 150);
        }

        if (passwordInput.type === 'password') {
            passwordInput.type = 'text';
            eyeIcon.innerHTML = `
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                      d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />
            `;
        } else {
            passwordInput.type = 'password';
            eyeIcon.innerHTML = `
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                      d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                      d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
            `;
        }
    }

// Полный блок WebAuthn логина (исправленная версия для neocalc.site с поддержкой Livewire)

// Функция инициализации WebAuthn кнопки
function initWebAuthnButton() {
    const passkeyBtn = document.getElementById('loginWithPasskey');
    
    // Удаляем старый обработчик, если есть
    if (passkeyBtn && passkeyBtn._webauthnHandler) {
        passkeyBtn.removeEventListener('click', passkeyBtn._webauthnHandler);
        delete passkeyBtn._webauthnHandler;
    }
    
    if (!passkeyBtn) return;
    
    // Сохраняем оригинальный HTML для восстановления
    const originalHTML = passkeyBtn.innerHTML;
    
    // Создаем обработчик
    const handler = async () => {
        try {
            // Меняем содержимое кнопки на состояние загрузки
            passkeyBtn.innerHTML = `
                <span class="relative flex items-center justify-center gap-3">
                    <svg class="w-8 h-8 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                    </svg>
                    <span>Подтверждение на устройстве...</span>
                </span>
            `;
            passkeyBtn.disabled = true;
            
            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
            
            // 1. Получаем опции аутентификации
            const optionsResp = await fetch('/webauthn/login/options', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json',
                    'Content-Type': 'application/json'
                }
            });
            
            if (!optionsResp.ok) {
                throw new Error(`Сервер вернул ошибку ${optionsResp.status}`);
            }
            
            const options = await optionsResp.json();
            console.log('Options:', options);
            
            // 2. Преобразуем challenge
            let challenge = options.publicKey.challenge.replace(/-/g, '+').replace(/_/g, '/');
            while (challenge.length % 4) challenge += '=';
            
            const publicKey = {
                challenge: Uint8Array.from(atob(challenge), c => c.charCodeAt(0)),
                rpId: options.publicKey.rpId,
                userVerification: options.publicKey.userVerification || 'preferred',
                timeout: options.publicKey.timeout || 60000
            };
            
            // 3. Добавляем allowCredentials если есть
            if (options.publicKey.allowCredentials && options.publicKey.allowCredentials.length > 0) {
                publicKey.allowCredentials = options.publicKey.allowCredentials.map(cred => {
                    let credId = cred.id.replace(/-/g, '+').replace(/_/g, '/');
                    while (credId.length % 4) credId += '=';
                    return {
                        id: Uint8Array.from(atob(credId), c => c.charCodeAt(0)),
                        type: 'public-key',
                        transports: cred.transports || []
                    };
                });
            }
            
            // 4. Запрашиваем аутентификацию
            const credential = await navigator.credentials.get({ publicKey });
            
            // 5. Отправляем на сервер
            const loginResp = await fetch('/webauthn/login', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json',
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    id: credential.id,
                    type: credential.type,
                    response: {
                        authenticatorData: arrayBufferToBase64Url(credential.response.authenticatorData),
                        clientDataJSON: arrayBufferToBase64Url(credential.response.clientDataJSON),
                        signature: arrayBufferToBase64Url(credential.response.signature),
                        userHandle: credential.response.userHandle ? arrayBufferToBase64Url(credential.response.userHandle) : null
                    }
                })
            });
            
            const result = await loginResp.json();

if (result.success || result.redirect) {
    window.location.href = result.redirect || '/dashboard';
} else {
    // Обработка ошибки "ключ не найден"
    if (result.error === 'credential_not_found') {
        showToast(
            'Этот отпечаток больше не зарегистрирован.\nВозможно, ключ был удален. Пожалуйста, войдите по паролю.',
            'warning'
        );
        // Не восстанавливаем кнопку сразу, даем время прочитать сообщение
        setTimeout(() => {
            passkeyBtn.innerHTML = originalHTML;
            passkeyBtn.disabled = false;
        }, 6000);
        return;
    }
    
    throw new Error(result.message || 'Ошибка входа');
}
            
        } catch (err) {
            console.error('Error:', err);
            
            // Обработка различных ошибок
            let errorMessage = err.message || 'Не удалось войти';
            if (err.name === 'NotAllowedError') {
                errorMessage = 'Операция отменена. Попробуйте еще раз';
            } else if (err.name === 'SecurityError') {
                errorMessage = 'Ошибка безопасности. Используйте HTTPS';
            } else if (err.name === 'NotSupportedError') {
                errorMessage = 'Ваш браузер не поддерживает WebAuthn';
            }
            
            showToast(errorMessage, 'error');
            
            // Восстанавливаем кнопку
            passkeyBtn.innerHTML = originalHTML;
            passkeyBtn.disabled = false;
        }
    };
    
    // Сохраняем обработчик и добавляем событие
    passkeyBtn._webauthnHandler = handler;
    passkeyBtn.addEventListener('click', handler);
}

// Вспомогательная функция для преобразования ArrayBuffer в Base64URL
function arrayBufferToBase64Url(buffer) {
    const bytes = new Uint8Array(buffer);
    let binary = '';
    for (let i = 0; i < bytes.byteLength; i++) {
        binary += String.fromCharCode(bytes[i]);
    }
    return btoa(binary).replace(/\+/g, '-').replace(/\//g, '_').replace(/=+$/, '');
}

// Функция показа уведомлений (Toast)
// Функция показа уведомлений (Toast)
function showToast(message, type = 'error') {
    const colors = {
        success: 'bg-emerald-500',
        error: 'bg-red-500',
        info: 'bg-blue-500',
        warning: 'bg-amber-500'  // Добавьте эту строку
    };
    
    const toast = document.createElement('div');
    toast.className = `fixed bottom-4 right-4 ${colors[type] || colors.error} text-white px-4 py-3 rounded-lg shadow-lg z-50 animate-slide-up max-w-md text-sm whitespace-pre-line`;
    toast.textContent = message;
    document.body.appendChild(toast);
    
    setTimeout(() => {
        toast.classList.add('animate-fade-out');
        setTimeout(() => toast.remove(), 300);
    }, 4000);
}

// Добавляем CSS анимации (если ещё нет)
if (!document.querySelector('#webauthn-animations')) {
    const style = document.createElement('style');
    style.id = 'webauthn-animations';
    style.textContent = `
        @keyframes slide-up {
            from { transform: translateY(100%); opacity: 0; }
            to { transform: translateY(0); opacity: 1; }
        }
        @keyframes fade-out {
            from { opacity: 1; }
            to { opacity: 0; }
        }
        @keyframes spin {
            from { transform: rotate(0deg); }
            to { transform: rotate(360deg); }
        }
        .animate-slide-up {
            animation: slide-up 0.3s ease-out;
        }
        .animate-fade-out {
            animation: fade-out 0.3s ease-out forwards;
        }
        .animate-spin {
            animation: spin 1s linear infinite;
        }
    `;
    document.head.appendChild(style);
}

// Принудительный HTTPS (если нужно)
if (window.location.hostname !== 'localhost' && 
    !window.location.hostname.startsWith('127.') &&
    window.location.hostname !== 'neocalc.site' &&
    window.location.protocol === 'http:') {
    window.location.href = window.location.href.replace('http:', 'https:');
}

// Оптимизация для мобильной клавиатуры
if (window.innerWidth <= 480) {
    const inputs = document.querySelectorAll('input[type="email"], input[type="password"]');
    inputs.forEach(input => {
        input.addEventListener('focus', () => {
            setTimeout(() => {
                input.scrollIntoView({ behavior: 'smooth', block: 'center' });
            }, 300);
        });
    });
}

// Инициализация WebAuthn кнопки
initWebAuthnButton();

// Инициализация после навигации Livewire
document.addEventListener('livewire:navigated', function() {
    initWebAuthnButton();
});

// Инициализация после обновления компонентов Livewire
if (typeof Livewire !== 'undefined') {
    Livewire.hook('element.updated', function() {
        setTimeout(initWebAuthnButton, 50);
    });
    
    Livewire.hook('morph.updated', function() {
        setTimeout(initWebAuthnButton, 50);
    });
}
</script><?php /**PATH C:\Users\Desktop_54\Desktop\neocalc.site_share_link_30.05\resources\views\livewire/pages/auth/login.blade.php ENDPATH**/ ?>
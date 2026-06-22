<?php

use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Locked;
use Livewire\Volt\Component;

new #[Layout('layouts.guest')] class extends Component
{
    #[Locked]
    public string $token = '';
    public string $email = '';
    public string $password = '';
    public string $password_confirmation = '';

    /**
     * Инициализация компонента.
     */
    public function mount(string $token): void
    {
        $this->token = $token;
        $this->email = request()->string('email');
    }

    /**
     * Сбросить пароль для данного пользователя.
     */
    public function resetPassword(): void
    {
        $this->validate([
            'token' => ['required'],
            'email' => ['required', 'string', 'email'],
            'password' => ['required', 'string', 'confirmed', Rules\Password::defaults()],
        ]);

        $status = Password::reset(
            $this->only('email', 'password', 'password_confirmation', 'token'),
            function ($user) {
                $user->forceFill([
                    'password' => Hash::make($this->password),
                    'remember_token' => Str::random(60),
                ])->save();

                event(new PasswordReset($user));
            }
        );

        if ($status != Password::PASSWORD_RESET) {
            $this->addError('email', __($status));
            return;
        }

        Session::flash('status', __($status));
        $this->redirectRoute('login', navigate: true);
    }
}; ?>

@assets
<style>
    /* Используем те же переменные, что и в форме входа */
    .reset-pass {
        --primary-color: #6366f1;
        --primary-hover: #4f46e5;
        --text-color: #f3f4f6;
        --text-secondary: #9ca3af;
        --bg-color: #1f2937;
        --bg-secondary: #374151;
        --border-color: #4b5563;
        --error-color: #ef4444;
        --success-color: #10b981;
        --disabled-color: #6b7280;
        --disabled-bg: #f3f4f6;
    }

    /* Анимация появления */
    .reset-pass .auth-form {
        animation: reset-pass-fadeIn 0.6s ease-out forwards;
        max-width: 480px;
        margin: 0 auto;
    }

    @keyframes reset-pass-fadeIn {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }

    /* Заголовок формы */
    .reset-pass .auth-title {
        font-size: 1.75rem;
        font-weight: 700;
        color: var(--text-color);
        text-align: center;
        margin-bottom: 2rem;
        position: relative;
        padding-bottom: 1rem;
    }

    .reset-pass .auth-title::after {
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
    .reset-pass .input-group {
        margin-bottom: 1.5rem;
        position: relative;
    }

    /* Стиль для disabled поля */
    .reset-pass .disabled-input {
        background-color: var(--disabled-bg) !important;
        color: var(--disabled-color) !important;
        cursor: not-allowed !important;
    }

    /* Кнопка сброса */
    .reset-pass .submit-btn {
        width: 100%;
        padding: 1rem;
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
        margin-top: 1rem;
    }

    .reset-pass .submit-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(99, 102, 241, 0.4);
    }

    .reset-pass .submit-btn:active {
        transform: translateY(0);
    }

    /* Индикатор загрузки */
    .reset-pass .loading-spinner {
        display: inline-block;
        width: 16px;
        height: 16px;
        border: 2px solid rgba(255, 255, 255, 0.3);
        border-radius: 50%;
        border-top-color: white;
        animation: reset-pass-spin 0.8s linear infinite;
        margin-left: 8px;
        vertical-align: middle;
    }

    @keyframes reset-pass-spin {
        to { transform: rotate(360deg); }
    }

    /* Стили для текстовых полей */
    .reset-pass .custom-text-input {
        width: 100%;
        padding: 0.85rem 1.25rem;
        background-color: rgba(255, 255, 255, 0.05);
        border: 1px solid var(--border-color);
        border-radius: 8px;
        color: var(--text-color);
        font-size: 0.95rem;
        transition: all 0.3s ease;
    }
    
    .reset-pass .custom-text-input:focus {
        outline: none;
        border-color: var(--primary-color);
        box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.2);
        background-color: rgba(255, 255, 255, 0.08);
    }
    
    .reset-pass .custom-text-input::placeholder {
        color: var(--text-secondary);
        opacity: 0.7;
    }
    
    /* Стили для лейблов */
    .reset-pass .custom-input-label {
        display: block;
        margin-bottom: 0.5rem;
        color: var(--text-color);
        font-size: 0.9rem;
        font-weight: 500;
        transition: all 0.3s ease;
    }

    /* Сообщения об ошибках */
    .reset-pass .error-message {
        color: var(--error-color);
        font-size: 0.8rem;
        margin-top: 0.5rem;
        animation: reset-pass-shake 0.4s ease;
    }

    @keyframes reset-pass-shake {
        0%, 100% { transform: translateX(0); }
        20%, 60% { transform: translateX(-5px); }
        40%, 80% { transform: translateX(5px); }
    }

    /* Иконка глаза для пароля */
    .reset-pass .password-toggle {
        position: absolute;
        right: 12px;
        top: 50%;
        transform: translateY(-50%);
        cursor: pointer;
        color: var(--text-secondary);
        transition: all 0.2s ease;
        z-index: 10;
    }

    .reset-pass .password-toggle:hover {
        color: var(--primary-color);
        transform: translateY(-50%) scale(1.1);
    }

    /* Адаптивность для мобильных */
    @media (max-width: 480px) {
        .reset-pass .auth-title {
            font-size: 1.5rem;
            margin-bottom: 1.25rem;
            padding-bottom: 0.75rem;
        }
        
        .reset-pass .auth-title::after {
            width: 60%;
        }
        
        .reset-pass .input-group {
            margin-bottom: 1.25rem;
        }
        
        .reset-pass .custom-text-input {
            padding: 0.75rem 1rem;
            font-size: 0.9rem;
        }
        
        .reset-pass .submit-btn {
            padding: 0.85rem;
            font-size: 0.95rem;
        }
    }

    /* Для очень маленьких экранов */
    @media (max-width: 360px) {  
        .reset-pass .auth-title {
            font-size: 1.35rem;
        }
        
        .reset-pass .submit-btn {
            padding: 0.75rem;
        }
    }
</style>
@endassets

<div class="reset-pass">
    <div class="auth-container">
        <x-slot name="title">
            {{ __('Сброс пароля') }}
        </x-slot>

        <div class="auth-form">
            <h1 class="auth-title">
                {{ __('Установите новый пароль') }}
            </h1>

            <!-- Session Status -->
            <x-auth-session-status class="mb-4" :status="session('status')" />

            <form wire:submit.prevent="resetPassword">
                <!-- Email Address (disabled) -->
                <div class="input-group">
                    <x-input-label for="email" :value="__('Электронная почта')" class="custom-input-label" />
                    <x-text-input wire:model="email" id="email" 
                                 class="custom-text-input disabled-input" 
                                 type="email" 
                                 name="email" 
                                 required 
                                 disabled
                                 autocomplete="username"
                                 placeholder="your@email.com" />
                    <x-input-error :messages="$errors->get('email')" class="error-message" />
                </div>

                <!-- Password -->
                <div class="input-group">
                    <x-input-label for="password" :value="__('Новый пароль')" class="custom-input-label" />
                    <div class="relative">
                        <x-text-input wire:model="password" id="password" 
                                     class="custom-text-input pr-10"
                                     type="password"
                                     name="password"
                                     required 
                                     autocomplete="new-password"
                                     placeholder="••••••••" />
                        <span class="password-toggle" onclick="togglePasswordVisibility('password', 'eye-icon-password')">
                            <svg id="eye-icon-password" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                            </svg>
                        </span>
                    </div>
                    <x-input-error :messages="$errors->get('password')" class="error-message" />
                </div>

                <!-- Confirm Password -->
                <div class="input-group">
                    <x-input-label for="password_confirmation" :value="__('Подтвердите пароль')" class="custom-input-label" />
                    <div class="relative">
                        <x-text-input wire:model="password_confirmation" id="password_confirmation" 
                                     class="custom-text-input pr-10"
                                     type="password"
                                     name="password_confirmation"
                                     required 
                                     autocomplete="new-password"
                                     placeholder="••••••••" />
                        <span class="password-toggle" onclick="togglePasswordVisibility('password_confirmation', 'eye-icon-confirm')">
                            <svg id="eye-icon-confirm" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                            </svg>
                        </span>
                    </div>
                    <x-input-error :messages="$errors->get('password_confirmation')" class="error-message" />
                </div>

                <!-- Submit Button -->
                <button type="submit" class="submit-btn">
                    {{ __('Сбросить пароль') }}
                    <span wire:loading wire:target="resetPassword" class="loading-spinner"></span>
                </button>
            </form>
        </div>
    </div>
</div>

<script>
    // Функция переключения видимости пароля
    function togglePasswordVisibility(fieldId, eyeIconId) {
        const passwordInput = document.getElementById(fieldId);
        const eyeIcon = document.getElementById(eyeIconId);
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

    // Инициализация после загрузки DOM
    document.addEventListener('DOMContentLoaded', function() {
        // Перенаправление на HTTPS в продакшене
        if (window.location.hostname !== 'localhost' && 
            !window.location.hostname.startsWith('127.') &&
            window.location.protocol === 'http:') {
            window.location.href = window.location.href.replace('http:', 'https:');
        }
        
        // Оптимизация для мобильной клавиатуры
        if (window.innerWidth <= 480) {
            const inputs = document.querySelectorAll('.reset-pass input[type="password"]');
            
            inputs.forEach(input => {
                input.addEventListener('focus', () => {
                    setTimeout(() => {
                        input.scrollIntoView({ behavior: 'smooth', block: 'center' });
                    }, 300);
                });
            });
        }
    });
</script>
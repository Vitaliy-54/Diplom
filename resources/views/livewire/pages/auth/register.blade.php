<?php

use App\Models\User;
use App\Models\RegistrationSetting;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('layouts.guest')] class extends Component
{
    public string $name = '';
    public string $email = '';
    public string $password = '';
    public string $password_confirmation = '';
    public bool $registrationDisabled = false;

    /**
     * Инициализация компонента.
     */
    public function mount(): void
    {
        $registrationSetting = RegistrationSetting::first();
        if (!$registrationSetting || !$registrationSetting->registration_enabled) {
            $this->registrationDisabled = true;
        }
    }

    /**
     * Handle an incoming registration request.
     */
    public function register(): void
    {
        $registrationSetting = RegistrationSetting::first();
        if (!$registrationSetting || !$registrationSetting->registration_enabled) {
            $this->registrationDisabled = true;
            return;
        }

        $validated = $this->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:' . User::class],
            'password' => ['required', 'string', 'confirmed', Rules\Password::defaults()],
        ]);

        $validated['password'] = Hash::make($validated['password']);

        event(new Registered($user = User::create($validated)));

        Auth::login($user);

        $this->redirect(route('dashboard', absolute: false), navigate: true);
    }

    /**
     * Закрыть модальное окно и перенаправить на главную страницу.
     */
    public function closeModal(): void
    {
        $this->redirect('/');
    }
}; ?>

@assets
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

    .register .auth-form {
        animation: fadeIn 0.6s ease-out forwards;
    }

    /* Заголовок формы */
    .register .auth-title {
        font-size: 1.75rem;
        font-weight: 700;
        color: var(--text-color);
        text-align: center;
        margin-bottom: 2rem;
        position: relative;
        padding-bottom: 1rem;
    }

    .register .auth-title::after {
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
    .register .input-group {
        margin-bottom: 0.5rem;
        position: relative;
    }

    /* Иконка глаза для пароля */
    .register .password-toggle {
        position: absolute;
        right: 12px;
        top: 50%;
        transform: translateY(-50%);
        cursor: pointer;
        color: var(--text-secondary);
        transition: all 0.2s ease;
    }

    .register .password-toggle:hover {
        color: var(--primary-color);
        transform: translateY(-50%) scale(1.1);
    }

    /* Кнопка регистрации */
    .register .submit-btn {
        width: 100%;
        padding: 1rem;
        background: linear-gradient(135deg, var(--primary-color), var(--primary-hover));
        color: white;
        border: none;
        border-radius: 8px;
        margin-top: 1rem;
        font-size: 1rem;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
        box-shadow: 0 4px 15px rgba(99, 102, 241, 0.3);
    }

    .register .submit-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(99, 102, 241, 0.4);
    }

    .register .submit-btn:active {
        transform: translateY(0);
    }

    /* Эффект волны при клике */
    .register .submit-btn::after {
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

    .register .submit-btn:focus:not(:active)::after {
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


    @keyframes spin {
        to { transform: rotate(360deg); }
    }

    /* Ссылки под формой */
    .register .auth-links {
        display: flex;
        justify-content: center;
        margin-top: 1.5rem;
        font-size: 0.9rem;
    }

    .register .auth-link {
        color: var(--text-secondary);
        text-decoration: none;
        position: relative;
        transition: all 0.3s ease;
    }

    .register .auth-link:hover {
        color: var(--primary-color);
    }

    .register .auth-link::after {
        content: '';
        position: absolute;
        bottom: -2px;
        left: 0;
        width: 0;
        height: 1px;
        background-color: var(--primary-color);
        transition: width 0.3s ease;
    }

    .register .auth-link:hover::after {
        width: 100%;
    }

    /* Сообщения об ошибках */
    .register .error-message {
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
    .register .custom-text-input {
        width: 100%;
        padding: 0.85rem 1.25rem;
        background-color: rgba(255, 255, 255, 0.05);
        border: 1px solid var(--border-color);
        border-radius: 8px;
        color: var(--text-color);
        font-size: 0.95rem;
        transition: all 0.3s ease;
    }
    
    .register .custom-text-input:focus {
        outline: none;
        border-color: var(--primary-color);
        box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.2);
        background-color: rgba(255, 255, 255, 0.08);
    }
    
    .register .custom-text-input::placeholder {
        color: var(--text-secondary);
        opacity: 0.7;
    }
    
    /* Стили для лейблов */
    .register .custom-input-label {
        display: block;
        margin-bottom: 0.5rem;
        color: var(--text-color);
        font-size: 0.9rem;
        font-weight: 500;
        transition: all 0.3s ease;
    }

    /* Модальное окно */
    .register .modal-overlay {
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background-color: rgb(17, 24, 39);
        display: flex;
        align-items: center;
        justify-content: center;
        z-index: 50;
    }

    .register .modal-container {
        background-color:rgb(32, 42, 56);
        border-radius: 12px;
        padding: 2rem;
        width: 90%;
        max-width: 400px;
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2);
        animation: modalFadeIn 0.3s ease-out;
    }

    @keyframes modalFadeIn {
        from { opacity: 0; transform: translateY(-20px); }
        to { opacity: 1; transform: translateY(0); }
    }

    .register .modal-title {
        font-size: 1.5rem;
        font-weight: 600;
        color: var(--text-color);
        margin-bottom: 1rem;
        text-align: center;
    }

    .register .modal-text {
        color: var(--text-secondary);
        margin-bottom: 1.5rem;
        text-align: center;
        line-height: 1.5;
    }

    .register .modal-btn {
        display: block;
        width: 100%;
        padding: 0.75rem;
        background-color: var(--primary-color);
        color: white;
        border: none;
        border-radius: 8px;
        font-size: 1rem;
        font-weight: 500;
        cursor: pointer;
        transition: all 0.2s ease;
        text-align: center;
    }

    .register .modal-btn:hover {
        background-color: var(--primary-hover);
        transform: translateY(-2px);
    }

    .register .modal-btn:active {
        transform: translateY(0);
    }

    .register .telegram-link {
        display: flex;
        align-items: center;
        justify-content: center;
        color: rgb(255, 255, 255);
        margin: 1.5rem 0;
        text-decoration: none;
    }

    .register .telegram-link:hover {
        color: rgb(145, 190, 226);
    }

    .register .telegram-icon {
        width: 24px;
        height: 24px;
        margin-right: 0.5rem;
    }

    /* Адаптивность для мобильных */
    @media (max-width: 480px) {
        .register .auth-title {
            font-size: 1.5rem;
            margin-bottom: 1.25rem;
            padding-bottom: 0.75rem;
        }
        
        .register .auth-title::after {
            width: 60%;
        }
        
        .register .input-group {
            margin-bottom: 1.25rem;
        }
        
        .register .custom-text-input {
            padding: 0.75rem 1rem;
            font-size: 0.9rem;
        }
        
        .register .submit-btn {
            padding: 0.85rem;
            font-size: 0.95rem;
        }
        
        .register .auth-links {
            flex-direction: column;
            gap: 0.75rem;
            margin-top: 1.25rem;
        }
        
        .register .auth-link {
            text-align: center;
        }
        
        .register .password-toggle svg {
            width: 18px;
            height: 18px;
        }

        .register .modal-container {
            padding: 1.5rem;
        }

        .register .modal-title {
            font-size: 1.3rem;
        }
    }

    /* Для очень маленьких экранов */
    @media (max-width: 360px) {  
        .register .auth-title {
            font-size: 1.35rem;
        }
        
        .register .submit-btn {
            padding: 0.75rem;
        }
    }
</style>
@endassets

<div class="auth-container register">
    <x-slot name="title">
        {{ __('Регистрация') }}
    </x-slot>

    @if ($registrationDisabled)
        <!-- Модальное окно, если регистрация отключена -->
        <div class="modal-overlay">
            <div class="modal-container">
                <h2 class="modal-title">Регистрация временно отключена</h2>
                <p class="modal-text">
                    Администратор отключил возможность регистрации новых пользователей.
                    Пожалуйста, свяжитесь с администратором для получения дополнительной информации.
                </p>
                
                <a href="https://t.me/Vitaliy_5454" target="_blank" class="telegram-link">
                    <svg class="telegram-icon" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M12 0c-6.627 0-12 5.373-12 12s5.373 12 12 12 12-5.373 12-12-5.373-12-12-12zm5.894 8.221l-1.97 9.28c-.145.658-.537.818-1.084.508l-3-2.21-1.446 1.394c-.14.14-.26.26-.51.26l.213-3.053 5.56-5.022c.24-.213-.054-.334-.373-.121l-6.869 4.326-2.96-.924c-.64-.203-.658-.64.135-.954l11.566-4.458c.534-.196 1.002.128.832.941z"/>
                    </svg>
                    <span>@Vitaliy_5454</span>
                </a>
                
                <button wire:click="closeModal" class="modal-btn">
                    Закрыть
                </button>
            </div>
        </div>
    @else
        <!-- Форма регистрации -->
        <div class="auth-form">
            <h1 class="auth-title">
                {{ __('Создать аккаунт') }}
            </h1>

            <form wire:submit="register">
                <!-- Name -->
                <div class="input-group">
                    <x-input-label for="name" :value="__('Имя')" class="custom-input-label" />
                    <x-text-input wire:model="name" id="name" 
                                 class="custom-text-input" 
                                 type="text" 
                                 name="name" 
                                 required 
                                 autofocus 
                                 autocomplete="name"
                                 placeholder="Ваше имя" />
                    <x-input-error :messages="$errors->get('name')" class="error-message" />
                </div>

                <!-- Email Address -->
                <div class="input-group">
                    <div class="flex items-center">
                        <x-input-label for="email" :value="__('Email')" class="custom-input-label" />
                        <button type="button" class="ml-2 custom-input-label" style="color: var(--primary-color);" onclick="showEmailInfo()">
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </button>
                    </div>
                    <x-text-input wire:model="email" id="email" 
                                 class="custom-text-input" 
                                 type="email" 
                                 name="email" 
                                 required 
                                 autocomplete="username"
                                 placeholder="your@email.com" />
                    <x-input-error :messages="$errors->get('email')" class="error-message" />
                </div>

                <!-- Password -->
                <div class="input-group">
                    <x-input-label for="password" :value="__('Пароль')" class="custom-input-label" />
                    <div class="relative">
                        <x-text-input wire:model="password" id="password" 
                                     class="custom-text-input pr-10"
                                     type="password"
                                     name="password"
                                     required 
                                     autocomplete="new-password"
                                     placeholder="••••••••" />
                        <span class="password-toggle" onclick="togglePasswordVisibility('password')">
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
                        <span class="password-toggle" onclick="togglePasswordVisibility('password_confirmation')">
                            <svg id="eye-icon-password_confirmation" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                            </svg>
                        </span>
                    </div>
                    <x-input-error :messages="$errors->get('password_confirmation')" class="error-message" />
                </div>

                <!-- Submit Button -->
                <button type="submit" class="submit-btn">
                    {{ __('Зарегистрироваться') }}
                    <span wire:loading wire:target="register" class="loading-spinner"></span>
                </button>

                <!-- Link to Login -->
                <div class="auth-links">
                    <a class="auth-link" href="{{ route('login') }}" wire:navigate>
                        {{ __('Уже есть аккаунт? Войти') }}
                    </a>
                </div>
            </form>
        </div>
    @endif
</div>

<script>
    function showEmailInfo() {
        alert('Пожалуйста, введите ваш действительный email адрес. Он будет использоваться для входа и восстановления пароля. Также он будет использоваться для подтвержения почты, без которой невозможно завершить регистрацию.');
    }

    function togglePasswordVisibility(fieldId) {
        const passwordInput = document.getElementById(fieldId);
        const eyeIcon = document.getElementById(`eye-icon-${fieldId}`);
        const isMobile = window.innerWidth <= 480;
        
        // Анимация иконки
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

        // Переключение видимости пароля
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
            const inputs = document.querySelectorAll('input[type="text"], input[type="email"], input[type="password"]');
            
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
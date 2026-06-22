<?php

use Illuminate\Support\Facades\Password;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('layouts.guest')] class extends Component
{
    public string $email = '';

    /**
     * Send a password reset link to the provided email address.
     */
    public function sendPasswordResetLink(): void
    {
        $this->validate([
            'email' => ['required', 'string', 'email'],
        ]);

        $status = Password::sendResetLink(
            $this->only('email')
        );

        if ($status !== Password::RESET_LINK_SENT) {
            $this->addError('email', __($status));

            return;
        }

        $this->reset('email');

        session()->flash('status', __($status));
    }
}; 
?>

@assets
<style>
/* Основные переменные цветов */
.forgot {
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

/* Заголовок формы */
.forgot .auth-title {
    font-size: 1.75rem;
    font-weight: 700;
    color: var(--text-color);
    text-align: center;
    margin-bottom: 2rem;
    position: relative;
    padding-bottom: 1rem;
}

.forgot .auth-title::after {
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
.forgot .input-group {
    margin-bottom: 1.5rem;
    position: relative;
}

/* Стили для текстовых полей */
.forgot .custom-text-input {
    width: 100%;
    padding: 0.85rem 1.25rem;
    background-color: rgba(255, 255, 255, 0.05);
    border: 1px solid var(--border-color);
    border-radius: 8px;
    color: var(--text-color);
    font-size: 0.95rem;
    transition: all 0.3s ease;
}

.forgot .custom-text-input:focus {
    outline: none;
    border-color: var(--primary-color);
    box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.2);
    background-color: rgba(255, 255, 255, 0.08);
}

.forgot .custom-text-input::placeholder {
    color: var(--text-secondary);
    opacity: 0.7;
}

/* Стили для лейблов */
.forgot .custom-input-label {
    display: block;
    margin-bottom: 0.5rem;
    color: var(--text-color);
    font-size: 0.9rem;
    font-weight: 500;
    transition: all 0.3s ease;
}

/* Сообщения об ошибках */
.forgot .error-message {
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

/* Информационный текст */
.forgot .reset-info {
    color: var(--text-secondary);
    font-size: 0.95rem;
    line-height: 1.5;
    margin-bottom: 2rem;
    text-align: center;
    animation: fadeIn 0.5s ease-out;
}

/* Группа кнопок */
.forgot .button-group {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-top: 2rem;
    gap: 1rem;
}

/* Кнопка назад */
.forgot .back-btn {
    background: var(--bg-secondary);
    color: var(--text-color);
    border: none;
    border-radius: 8px;
    padding: 0 1.5rem;
    font-size: 0.95rem;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s ease;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    height: 48px;
    flex: 1;
}

.forgot .back-btn:hover {
    background: var(--border-color);
    transform: translateY(-1px);
}

.forgot .back-btn:active {
    transform: translateY(0);
}

.forgot .back-btn svg {
    margin-right: 8px;
    width: 16px;
    height: 16px;
}

/* Кнопка отправки */
.forgot .submit-btn {
    width: 100%;
    height: 48px;
    padding: 0 1.5rem;
    background: linear-gradient(135deg, var(--primary-color), var(--primary-hover));
    color: white;
    border: none;
    border-radius: 8px;
    font-size: 0.95rem;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s ease;
    position: relative;
    overflow: hidden;
    box-shadow: 0 4px 15px rgba(99, 102, 241, 0.3);
    display: flex;
    align-items: center;
    justify-content: center;
    flex: 1;
}

.forgot .submit-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(99, 102, 241, 0.4);
}

.forgot .submit-btn:active {
    transform: translateY(0);
}

@keyframes spin {
    to { transform: rotate(360deg); }
}

/* Адаптивность для мобильных */
@media (max-width: 480px) {
    .forgot .auth-title {
        font-size: 1.5rem;
        margin-bottom: 1.25rem;
        padding-bottom: 0.75rem;
    }
    
    .forgot .auth-title::after {
        width: 60%;
    }
    
    .forgot .input-group {
        margin-bottom: 1.25rem;
    }
    
    .forgot .custom-text-input {
        padding: 0.75rem 1rem;
        font-size: 0.9rem;
    }
    
    .forgot .reset-info {
        font-size: 0.9rem;
        margin-bottom: 1.5rem;
    }
    
    .forgot .button-group {
        flex-direction: column-reverse;
        gap: 0.75rem;
        height: 96px;
    }
    
    .forgot .back-btn,
    .forgot .submit-btn {
        width: 100%;
        padding: 0 1rem;
    }
}

/* Для очень маленьких экранов */
@media (max-width: 360px) {  
    .forgot .auth-form {
        padding: 1.25rem;
    }
}
</style>
@endassets

<div class="forgot auth-container">
    <x-slot name="title">
        {{ __('Сброс пароля') }}
    </x-slot>

    <div class="auth-form">
        <h1 class="auth-title">
            {{ __('Восстановление пароля') }}
        </h1>

        <div class="reset-info">
            {{ __('Забыли свой пароль? Не беспокойтесь. Просто сообщите нам свой адрес электронной почты, и мы отправим вам ссылку для сброса пароля.') }}
        </div>

        <!-- Session Status -->
        <x-auth-session-status class="mb-4" :status="session('status')" />

        <form wire:submit="sendPasswordResetLink">
            <!-- Email Address -->
            <div class="input-group">
                <x-input-label for="email" :value="__('Электронная почта')" class="custom-input-label" />
                <x-text-input wire:model="email" id="email" 
                             class="custom-text-input" 
                             type="email" 
                             name="email" 
                             required 
                             autofocus 
                             placeholder="your@email.com" />
                <x-input-error :messages="$errors->get('email')" class="error-message" />
            </div>

            <div class="button-group">
                <!-- Back Button -->
                <a href="{{ route('login') }}" class="back-btn" wire:navigate>
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    {{ __('Назад') }}
                </a>

                <!-- Submit Button -->
                <button type="submit" class="submit-btn">
                    {{ __('Отправить ссылку') }}
                    <span wire:loading wire:target="sendPasswordResetLink"></span>
                </button>
            </div>
        </form>
    </div>
</div>
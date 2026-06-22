<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Illuminate\Validation\ValidationException;
use Livewire\Volt\Component;

new class extends Component
{
    public string $current_password = '';
    public string $password = '';
    public string $password_confirmation = '';

    public bool $showCurrentPassword = false;
    public bool $showNewPassword = false;
    public bool $showConfirmPassword = false;

    public array $passwordStrength = [
        'percent' => 0,
        'label' => 'Слабый',
        'color' => 'bg-red-500',
    ];

    public function toggleCurrentPasswordVisibility(): void
    {
        $this->showCurrentPassword = !$this->showCurrentPassword;
    }

    public function toggleNewPasswordVisibility(): void
    {
        $this->showNewPassword = !$this->showNewPassword;
    }

    public function toggleConfirmPasswordVisibility(): void
    {
        $this->showConfirmPassword = !$this->showConfirmPassword;
    }

    public function updatedPassword(): void
    {
        $this->passwordStrength = $this->calculatePasswordStrength($this->password);
    }

    private function calculatePasswordStrength(string $password): array
    {
        $score = 0;

        if (strlen($password) >= 8) $score += 1;
        if (preg_match('/[A-Z]/', $password)) $score += 1;
        if (preg_match('/[a-z]/', $password)) $score += 1;
        if (preg_match('/[0-9]/', $password)) $score += 1;
        if (preg_match('/[\W]/', $password)) $score += 1;

        return match (true) {
            $score <= 2 => ['percent' => 30, 'label' => 'Слабый', 'color' => 'bg-red-500'],
            $score === 3 => ['percent' => 60, 'label' => 'Средний', 'color' => 'bg-yellow-500'],
            $score >= 4 => ['percent' => 100, 'label' => 'Надёжный', 'color' => 'bg-green-500'],
        };
    }

    public function updatePassword(): void
    {
        $validated = $this->validate([
            'current_password' => ['required', 'string', 'current_password'],
            'password' => ['required', 'string', Password::defaults(), 'confirmed'],
        ]);

        Auth::user()->update([
            'password' => Hash::make($validated['password']),
        ]);

        $this->reset('current_password', 'password', 'password_confirmation');
        $this->passwordStrength = ['percent' => 0, 'label' => 'Слабый', 'color' => 'bg-red-500'];
        $this->dispatch('password-updated');
    }
};
?>

@php
$inputTypeCurrent = $this->showCurrentPassword ? 'text' : 'password';
$inputTypeNew = $this->showNewPassword ? 'text' : 'password';
$inputTypeConfirm = $this->showConfirmPassword ? 'text' : 'password';
@endphp

<div class="space-y-6">
    {{-- Заголовок с иконкой --}}
    <div class="flex items-center gap-3">
        <div class="p-2 bg-gradient-to-br from-blue-500 to-cyan-600 rounded-xl shadow-lg">
            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
            </svg>
        </div>
        <div>
            <h2 class="text-xl font-semibold text-gray-900 dark:text-white">
                {{ __('Обновить пароль') }}
            </h2>
            <p class="text-sm text-gray-500 dark:text-gray-400">
                {{ __('Используйте длинный случайный пароль для обеспечения безопасности.') }}
            </p>
        </div>
    </div>

    {{-- Основная форма --}}
    <div class="bg-gradient-to-r from-gray-200 to-gray-300 dark:from-gray-900/50 dark:to-gray-800/50 border border-gray-900/20 dark:border-gray-600 rounded-xl p-4 sm:p-6">
        <form wire:submit="updatePassword" class="space-y-6">
            <!-- Текущий пароль -->
<div>
    <x-input-label for="current_password" :value="__('Текущий пароль')" class="text-sm font-medium text-gray-700 dark:text-gray-300" />
    <div class="relative mt-1">
        <x-text-input wire:model="current_password" id="current_password" name="current_password"
            type="{{ $inputTypeCurrent }}" 
            placeholder="Введите текущий пароль"
            class="block w-full pr-10 rounded-lg border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 focus:ring-2 focus:ring-blue-500 focus:border-blue-500" 
            autocomplete="current-password" />
        <button type="button" wire:click="toggleCurrentPasswordVisibility"
            class="absolute inset-y-0 right-0 px-3 flex items-center text-gray-500 hover:text-gray-700 dark:hover:text-gray-300 transition-colors">
            @if($showCurrentPassword)
            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none"
                viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
            </svg>
            @else
            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none"
                viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.542-7a9.957 9.957 0 012.332-3.708M6.404 6.404A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.542 7a9.964 9.964 0 01-4.325 5.064M3 3l18 18" />
            </svg>
            @endif
        </button>
    </div>
    <x-input-error :messages="$errors->get('current_password')" class="mt-2" />
</div>

            <!-- Новый пароль -->
            <div>
                <x-input-label for="new_password" :value="__('Новый пароль')" class="text-sm font-medium text-gray-700 dark:text-gray-300" />
                <div class="relative mt-1">
                    <x-text-input wire:model.live="password" id="new_password" name="password"
                        type="{{ $inputTypeNew }}" placeholder="Введите новый пароль" class="block w-full pr-10 rounded-lg border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 focus:ring-2 focus:ring-blue-500 focus:border-blue-500" 
                        autocomplete="new-password" />
                    <button type="button" wire:click="toggleNewPasswordVisibility"
                        class="absolute inset-y-0 right-0 px-3 flex items-center text-gray-500 hover:text-gray-700 dark:hover:text-gray-300 transition-colors">
                        @if($showNewPassword)
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                        </svg>
                        @else
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.542-7a9.957 9.957 0 012.332-3.708M6.404 6.404A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.542 7a9.964 9.964 0 01-4.325 5.064M3 3l18 18" />
                        </svg>
                        @endif
                    </button>
                </div>
                <x-input-error :messages="$errors->get('password')" class="mt-2" />

                {{-- Индикатор надёжности --}}
                <div class="mt-3">
                    <div class="flex justify-between items-center mb-1">
                        <span class="text-xs text-gray-500 dark:text-gray-400">Надёжность пароля</span>
                        <span class="text-xs font-medium {{ $this->passwordStrength['color'] === 'bg-red-500' ? 'text-red-500' : ($this->passwordStrength['color'] === 'bg-yellow-500' ? 'text-yellow-500' : 'text-green-500') }}">
                            {{ $this->passwordStrength['label'] }}
                        </span>
                    </div>
                    <div class="w-full bg-gray-300 dark:bg-gray-700 rounded-full h-2 overflow-hidden">
                        <div class="{{ $this->passwordStrength['color'] }} h-2 rounded-full transition-all duration-300"
                            style="width: {{ $this->passwordStrength['percent'] }}%"></div>
                    </div>
                </div>
            </div>

            <!-- Подтверждение пароля -->
            <div>
                <x-input-label for="confirm_password" :value="__('Подтвердите новый пароль')" class="text-sm font-medium text-gray-700 dark:text-gray-300" />
                <div class="relative mt-1">
                    <x-text-input wire:model="password_confirmation" id="confirm_password" name="password_confirmation"
                        type="{{ $inputTypeConfirm }}" placeholder="Подтвердите новый пароль" class="block w-full pr-10 rounded-lg border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 focus:ring-2 focus:ring-blue-500 focus:border-blue-500" 
                        autocomplete="new-password" />
                    <button type="button" wire:click="toggleConfirmPasswordVisibility"
                        class="absolute inset-y-0 right-0 px-3 flex items-center text-gray-500 hover:text-gray-700 dark:hover:text-gray-300 transition-colors">
                        @if($showConfirmPassword)
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                        </svg>
                        @else
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.542-7a9.957 9.957 0 012.332-3.708M6.404 6.404A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.542 7a9.964 9.964 0 01-4.325 5.064M3 3l18 18" />
                        </svg>
                        @endif
                    </button>
                </div>
                <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
            </div>

            <div class="flex items-center gap-4 pt-2">
                <button type="submit"
                    class="group relative inline-flex items-center px-5 py-2.5 bg-gradient-to-r from-blue-600 to-cyan-600 hover:from-blue-700 hover:to-cyan-700 rounded-lg font-medium text-sm text-white shadow-md hover:shadow-lg transition-all duration-200 transform hover:-translate-y-0.5 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 dark:focus:ring-offset-gray-900">
                    <svg class="w-4 h-4 mr-2 transition-transform duration-200 group-hover:scale-110" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                    </svg>
                    {{ __('Обновить пароль') }}
                </button>

                <x-action-message class="text-green-600 dark:text-green-400" on="password-updated">
                    {{ __('Пароль успешно обновлён!') }}
                </x-action-message>
            </div>
        </form>
    </div>
</div>
<?php

use App\Livewire\Actions\Logout;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Cache;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('layouts.guest')] class extends Component
{
    public string $code = '';

    /**
     * Verify the user's email with the code.
     */
    public function verify(): void
    {
        $this->validate([
            'code' => 'required|numeric|digits:6',
        ]);

        $user = Auth::user();

        if ($user->hasVerifiedEmail()) {
            $this->redirectIntended(default: route('dashboard', absolute: false), navigate: true);
            return;
        }

        $cachedData = Cache::get('email_verification:' . $user->email);

        if (!$cachedData || $cachedData['code'] != $this->code) {
            $this->addError('code', 'Неверный код подтверждения.');
            return;
        }

        if ($cachedData['user_id'] != $user->id) {
            $this->addError('code', 'Неверный код подтверждения.');
            return;
        }

        $user->markEmailAsVerified();
        Cache::forget('email_verification:' . $user->email);

        $this->redirectIntended(default: route('dashboard', absolute: false), navigate: true);
    }

    /**
     * Send an email verification notification to the user.
     */
    public function sendVerification(): void
    {
        if (Auth::user()->hasVerifiedEmail()) {
            $this->redirectIntended(default: route('dashboard', absolute: false), navigate: true);
            return;
        }

        Auth::user()->sendEmailVerificationNotification();

        Session::flash('status', 'verification-link-sent');
    }

    /**
     * Log the current user out of the application.
     */
    public function logout(Logout $logout): void
    {
        $logout();

        $this->redirect('/', navigate: true);
    }
}; ?>



<div class="w-full sm:max-w-md mt-2 px-2 bg-white dark:bg-gray-800 overflow-hidden sm:rounded-lg">
    <x-slot name="title">
        {{ __('Подтверждение почты') }}
    </x-slot>

    <div class="mb-4 text-center">
        <h2 class="text-2xl font-bold text-gray-800 dark:text-gray-200">
            {{ __('Подтверждение Email') }}
        </h2>
    </div>

    <div class="mb-6 text-sm text-gray-600 dark:text-gray-400">
        {{ __('Мы отправили 6-значный код подтверждения на вашу электронную почту. Введите его ниже:') }}
    </div>

    <form wire:submit.prevent="verify">
        <div class="mb-6">
            <x-input-label for="code" :value="__('Код подтверждения')" />
            <div class="relative mt-1">
                <x-text-input
                    wire:model="code"
                    id="code"
                    class="block w-full text-center text-xl tracking-[0.5em] font-mono h-12"
                    type="text"
                    name="code"
                    required
                    autofocus
                    inputmode="numeric"
                    pattern="[0-9]*"
                    maxlength="6"
                    placeholder="••••••"
                    x-data="{
                handleInput(e) {
                    // Allow only numbers
                    e.target.value = e.target.value.replace(/\D/g, '');
                    // Auto focus next input (if you switch to individual inputs)
                },
                handlePaste(e) {
                    const paste = e.clipboardData.getData('text');
                    const numbers = paste.replace(/\D/g, '');
                    if (numbers.length === 6) {
                        this.$wire.set('code', numbers);
                        e.preventDefault();
                    }
                }
            }"
                    x-on:input="handleInput"
                    x-on:paste="handlePaste" />
                <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                    <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                    </svg>
                </div>
            </div>
            <x-input-error :messages="$errors->get('code')" class="mt-2" />
        </div>

        <x-primary-button class="w-full justify-center">
    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
    </svg>
    {{ __('Подтвердить') }}
</x-primary-button>
    </form>

    @if (session('status') == 'verification-link-sent')
    <div class="mb-6 mt-6 font-medium text-sm text-green-600 dark:text-green-400">
        {{ __('Новый код подтверждения был отправлен на ваш email.') }}
    </div>
    @endif

    <div class="mt-6 flex flex-col sm:flex-row gap-4">
        <x-secondary-button wire:click="sendVerification" class="w-full justify-center">
            {{ __('Отправить код ещё раз') }}
        </x-secondary-button>

        <x-danger-button wire:click="logout" class="w-full justify-center">
            {{ __('Выйти') }}
        </x-danger-button>
    </div>

    <div class="mt-8 text-sm text-gray-600 dark:text-gray-400">
        <p class="mb-2">{{ __('Не получили письмо?') }}</p>
        <ul class="list-disc pl-5 space-y-1">
            <li>{{ __('Проверьте папку "Спам"') }}</li>
            <li>{{ __('Убедитесь, что письмо отправлено на правильный email') }}</li>
            <li>{{ __('Подождите несколько минут, письмо может приходить с задержкой') }}</li>
        </ul>
    </div>
</div>
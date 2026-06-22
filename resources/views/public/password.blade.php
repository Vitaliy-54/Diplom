<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Расчет защищен паролем') }}
            </h2>
            <div class="text-sm text-gray-800 dark:text-gray-400 animate__animated animate__fadeIn">
                {{ now()->translatedFormat('d F Y') }}
            </div>
        </div>
    </x-slot>

    <x-slot name="title">
        {{ __('Расчет защищен паролем') }}
    </x-slot>

    <div class="py-12">
        <div class="max-w-md mx-auto sm:px-6 lg:px-8">
            <div class="bg-gray-300/70 dark:bg-gray-800 border border-gray-400 dark:border-gray-700 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <!-- Иконка замка -->
                    <div class="flex justify-center mb-6">
                        <div class="w-20 h-20 bg-yellow-100 dark:bg-yellow-900/30 rounded-full flex items-center justify-center">
                            <svg class="h-10 w-10 text-yellow-600 dark:text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                            </svg>
                        </div>
                    </div>

                    <!-- Заголовок -->
                    <h3 class="text-xl font-bold text-center text-gray-900 dark:text-white mb-2">
                        {{ __('Введите пароль') }}
                    </h3>
                    <p class="text-sm text-center text-gray-700 dark:text-gray-400 mb-6">
                        {{ __('Данный расчет защищен паролем. Пожалуйста, введите его для продолжения.') }}
                    </p>

                    <!-- Сообщение об ошибке -->
                    <div id="error-message" class="hidden mb-4 p-3 bg-red-100 dark:bg-red-900/30 border border-red-400 dark:border-red-700 rounded-lg text-red-700 dark:text-red-300 text-sm">
                    </div>

                    <!-- Форма ввода пароля -->
                    <form id="password-form">
                        @csrf
<div class="mb-4">
    <label class="block text-sm font-medium text-gray-800 dark:text-gray-300 mb-2">
        {{ __('Пароль') }}
    </label>
    
    <div class="relative w-full">
        <!-- Иконка замка слева -->
        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
            <svg class="h-5 w-5 text-gray-500 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
            </svg>
        </div>
        
        <!-- Поле ввода -->
        <x-text-input wire:model="form.password" id="password" 
            class="custom-text-input pl-10 pr-10 w-full"
            type="password"
            name="password"
            required 
            autocomplete="current-password"
            placeholder="Введите пароль" />
    </div>
</div>

                        <button type="submit" id="submit-btn" 
                            class="w-full inline-flex justify-center items-center px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white font-medium rounded-lg transition-colors duration-150">
                            {{ __('Разблокировать расчет') }}
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.getElementById('password-form').addEventListener('submit', async (e) => {
            e.preventDefault();
            
            const password = document.getElementById('password').value;
            const token = '{{ $token }}';
            const btn = document.getElementById('submit-btn');
            const errorDiv = document.getElementById('error-message');
            
            // Показываем загрузку
            btn.disabled = true;
            btn.innerHTML = `
                <svg class="animate-spin h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                {{ __('Проверка...') }}
            `;
            
            try {
                const response = await fetch(`/share/${token}/password`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({ password: password })
                });
                
                const data = await response.json();
                
                if (data.success) {
                    // Успешный вход - перенаправляем
                    window.location.href = data.redirect || `/share/${token}`;
                } else {
                    // Ошибка - показываем сообщение
                    errorDiv.classList.remove('hidden');
                    errorDiv.textContent = data.error || '{{ __("Неверный пароль. Пожалуйста, попробуйте еще раз.") }}';
                    btn.disabled = false;
                    btn.innerHTML = '{{ __("Разблокировать расчет") }}';
                    
                    // Очищаем поле пароля
                    document.getElementById('password').value = '';
                    document.getElementById('password').focus();
                }
            } catch (error) {
                console.error('Error:', error);
                errorDiv.classList.remove('hidden');
                errorDiv.textContent = '{{ __("Сетевая ошибка. Пожалуйста, попробуйте еще раз.") }}';
                btn.disabled = false;
                btn.innerHTML = '{{ __("Разблокировать расчет") }}';
            }
        });
    </script>
</x-app-layout>
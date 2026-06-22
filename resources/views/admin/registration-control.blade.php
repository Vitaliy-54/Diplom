<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Управление регистрацией') }}
            </h2>
            <div class="text-sm text-gray-800 dark:text-gray-400 animate__animated animate__fadeIn">
                {{ now()->translatedFormat('d F Y') }}
            </div>
        </div>
    </x-slot>

    <x-slot name="title">
        {{ __('Управление регистрацией') }}
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            <!-- Основная карточка -->
            <div class="bg-gray-300 dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-6 sm:p-8">
                    <!-- Заголовок с иконкой -->
                    <div class="flex items-center mb-6">
                        <div class="p-3 rounded-full bg-blue-100 dark:bg-blue-900/50 mr-4">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-blue-600 dark:text-blue-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                            </svg>
                        </div>
                        <div>
                            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">{{ __('Настройки регистрации') }}</h1>
                            <p class="text-sm text-gray-500 dark:text-gray-400">{{ __('Управление доступом новых пользователей к системе') }}</p>
                        </div>
                    </div>

                    <!-- Блок статуса -->
                    <div class="bg-gray-200 dark:bg-gray-700 rounded-lg p-6 mb-8">
                        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
                            <div class="mb-4 sm:mb-0">
                                <h3 class="text-lg font-medium text-gray-900 dark:text-white">{{ __('Текущий статус') }}</h3>
                                <div class="flex items-center mt-1">
                                    <div class="h-3 w-3 rounded-full {{ $registrationSetting->registration_enabled ? 'bg-green-500' : 'bg-red-500' }} mr-2"></div>
                                    <span class="text-gray-700 dark:text-gray-300">
                                        {{ $registrationSetting->registration_enabled ? 'Регистрация включена' : 'Регистрация отключена' }}
                                    </span>
                                </div>
                            </div>

                            <form action="{{ route('admin.toggle-registration') }}" method="POST">
                                @csrf
                                <input type="hidden" name="registration_enabled" value="{{ $registrationSetting->registration_enabled ? 0 : 1 }}">
                                <button type="submit" class="w-full sm:w-auto px-6 py-3 rounded-lg font-medium flex items-center justify-center transition-all duration-200 {{ $registrationSetting->registration_enabled ? 'bg-red-600 hover:bg-red-700 text-white' : 'bg-green-600 hover:bg-green-700 text-white' }}">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $registrationSetting->registration_enabled ? 'M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z' : 'M8 11V7a4 4 0 118 0m-4 8v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2z' }}" />
                                    </svg>
                                    {{ $registrationSetting->registration_enabled ? 'Отключить регистрацию' : 'Включить регистрацию' }}
                                </button>
                            </form>
                        </div>
                    </div>

                    <!-- Информационные карточки -->
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <!-- Карточка 1 -->
                        <div class="bg-gray-200 dark:bg-gray-700 rounded-lg p-6 border-l-4 border-blue-500">
                            <div class="flex items-start">
                                <div class="flex-shrink-0 h-10 w-10 rounded-full bg-blue-100 dark:bg-blue-900/30 flex items-center justify-center text-blue-600 dark:text-blue-400 mr-4">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">{{ __('Функция переключателя') }}</h3>
                                    <p class="text-gray-600 dark:text-gray-400">
                                        {{ __('Используйте кнопку выше для управления регистрацией. Это мгновенно применяет изменения для всех посетителей сайта.') }}
                                    </p>
                                </div>
                            </div>
                        </div>

                        <!-- Карточка 2 -->
                        <div class="bg-gray-200 dark:bg-gray-700 rounded-lg p-6 border-l-4 border-purple-500">
                            <div class="flex items-start">
                                <div class="flex-shrink-0 h-10 w-10 rounded-full bg-purple-100 dark:bg-purple-900/30 flex items-center justify-center text-purple-600 dark:text-purple-400 mr-4">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">{{ __('Когда отключать?') }}</h3>
                                    <p class="text-gray-600 dark:text-gray-400">
                                        {{ __('Отключайте регистрацию при технических работах, тестировании системы или для ограничения доступа.') }}
                                    </p>
                                </div>
                            </div>
                        </div>

                        <!-- Карточка 3 -->
                        <div class="bg-gray-200 dark:bg-gray-700 rounded-lg p-6 border-l-4 border-green-500">
                            <div class="flex items-start">
                                <div class="flex-shrink-0 h-10 w-10 rounded-full bg-green-100 dark:bg-green-900/30 flex items-center justify-center text-green-600 dark:text-green-400 mr-4">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">{{ __('Что происходит?') }}</h3>
                                    <p class="text-gray-600 dark:text-gray-400">
                                        {{ __('При отключенной регистрации, при нажатии на кнопку "Регистрация" пользователь увидит сообщение о том, что регистрация отключена.') }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Дополнительная информация -->
                    <div class="mt-8 bg-blue-200 dark:bg-blue-900/20 border border-blue-400 dark:border-blue-800 rounded-lg p-6">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-blue-500 dark:text-blue-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <div class="ml-3">
                                <h3 class="text-lg font-medium text-blue-800 dark:text-blue-200">{{ __('Важно знать') }}</h3>
                                <div class="mt-2 text-blue-700 dark:text-blue-300">
                                    <p class="mb-2">{{ __('Отключение регистрации не влияет на:') }}</p>
                                    <ul class="list-disc pl-5 space-y-1">
                                        <li>{{ __('Уже зарегистрированных пользователей') }}</li>
                                        <li>{{ __('Возможность входа в систему') }}</li>
                                        <li>{{ __('Функциональность администраторов') }}</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
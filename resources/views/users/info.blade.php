<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Карточка пользователя') }}
            </h2>
            <div class="text-sm text-gray-800 dark:text-gray-400 animate__animated animate__fadeIn">
                {{ now()->translatedFormat('d F Y') }}
            </div>
        </div>
    </x-slot>

    <x-slot name="title">
        {{ __('Карточка пользователя') }}
    </x-slot>

    <!-- Предварительная загрузка стилей -->
    <style>
        /* Скрываем контент до полной загрузки */
        body:not(.loaded) #content-wrapper {
            opacity: 0;
            visibility: hidden;
        }
        
        /* Плавное появление после загрузки */
        body.loaded #content-wrapper {
            opacity: 1;
            visibility: visible;
            transition: opacity 0.3s ease-in-out;
        }
        
        /* Анимация для аватара */
        .avatar-image {
            opacity: 0;
            transition: opacity 0.5s ease-in-out;
        }
        
        .avatar-image.loaded {
            opacity: 1;
        }
    </style>

    <div class="py-6" id="content-wrapper">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center text-sm sm:text-base mb-4 sm:mb-2 px-2 sm:px-0 text-gray-900 dark:text-gray-400">
                <span>Актуальные данные на: {{ now()->format('d.m.Y H:i') }}</span>

                <a href="{{ route('user.info', ['user' => $user->id]) }}"
                    class="p-2 rounded-lg hover:bg-gray-300 dark:hover:bg-gray-700 text-gray-500 dark:text-gray-400"
                    title="Обновить данные">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="#4299e1">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                    </svg>
                </a>
            </div>

            <div class="bg-gray-300 dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg p-4 sm:p-6">
                <div class="flex flex-col sm:flex-row items-center sm:items-start space-y-1 sm:space-y-0 sm:space-x-6">

                    <div class="w-20 h-20 sm:w-24 sm:h-24 rounded-full overflow-hidden bg-gray-200 dark:bg-gray-700 flex items-center justify-center flex-shrink-0">
                        @if ($avatarFile)
                        <img src="{{ route('avatar.serve', ['user' => $user->id, 'filename' => basename($avatarFile)]) }}"
                            alt="Avatar"
                            class="w-full h-full object-cover avatar-image"
                            onload="this.classList.add('loaded')">
                        @else
                        @if($user->role === 'admin')
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 sm:h-12 sm:w-12 text-purple-500 avatar-image loaded" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
                        @else
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 sm:h-12 sm:w-12 text-gray-500 avatar-image loaded" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
                        @endif
                        @endif
                    </div>

                    <div class="w-full sm:flex-1 text-center sm:text-left">
                        <div class="flex flex-col sm:flex-row sm:items-center sm:space-x-2">
                            <div class="text-xl sm:text-xl font-semibold text-gray-900 dark:text-white">
                                {{ $user->name }}
                            </div>

                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full self-center sm:self-auto border border-gray-400 dark:border-gray-600
            {{ $user->role === 'admin' 
                ? 'bg-purple-200 dark:bg-purple-900 text-purple-900 dark:text-purple-200' 
                : 'bg-green-200 dark:bg-green-900 text-green-900 dark:text-green-200' }}">
                                {{ $user->role === 'admin' ? __('Администратор') : __('Пользователь') }}
                            </span>
                        </div>

                        @if ($user->email)
                        <div class="text-gray-700 dark:text-gray-400 text-base sm:text-base">
                            {{ $user->email }}
                        </div>
                        @else
                        <div class="h-5 w-40 bg-gray-200 dark:bg-gray-600 animate-pulse rounded"></div>
                        @endif

                        @php
                        $isOnline = $user->id === auth()->id() || ($user->lastSession && $user->lastSession->last_activity > now()->subMinutes(5)->timestamp);
                        @endphp
                        <div class="mt-2">
                            <span class="inline-flex items-center text-base sm:text-base font-medium
            {{ $isOnline ? 'text-green-600 dark:text-green-500' : 'text-red-600 dark:text-red-500' }}">
                                <span class="h-3 w-3 sm:h-4 sm:w-4 rounded-full mr-2 {{ $isOnline ? 'bg-green-500' : 'bg-red-500' }}"></span>
                                {{ $isOnline ? __('Онлайн') : __('Оффлайн') }}
                            </span>
                        </div>

                        <div class="mt-2 text-sm sm:text-sm text-gray-700 dark:text-gray-400">
                            {{ __('Дата регистрации:') }} {{ $user->created_at->format('d.m.Y H:i') }}
                        </div>

                        <div class="text-sm sm:text-sm text-gray-700 dark:text-gray-400">
                            @if ($user->logs->first() && $user->logs->first()->last_activity_at)
                            {{ __('Последняя активность:') }} {{ \Carbon\Carbon::parse($user->logs->first()->last_activity_at)->format('d.m.Y H:i') }}
                            @else
                            {{ __('Нет данных о последней активности') }}
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Скрипт для плавного отображения после загрузки -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Добавляем класс loaded к body после полной загрузки страницы
            window.addEventListener('load', function() {
                document.body.classList.add('loaded');
            });
            
            // На случай, если событие load не сработает
            setTimeout(function() {
                document.body.classList.add('loaded');
            }, 500);
        });
    </script>
</x-app-layout>
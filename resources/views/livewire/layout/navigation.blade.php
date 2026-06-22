<?php

use App\Livewire\Actions\Logout;
use Livewire\Volt\Component;

new class extends Component
{
    /**
     * Log the current user out of the application.
     */
    public function logout(Logout $logout): void
    {
        $logout();

        $this->redirect('/', navigate: true);
    }
}; ?>

<nav x-data="{ open: false }" class="bg-gray-300 dark:bg-gray-800 border-b border-gray-100 dark:border-gray-700">
<style>
        .invert-light {
            filter: invert(1);
        }

        .dark .invert-light {
            filter: invert(0);
        }
    </style>
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                <a href="{{ route('dashboard') }}" wire:navigate>
    <x-application-logo class="block h-9 w-auto fill-current text-gray-800 dark:text-gray-200 invert-light" />
</a>
                </div>

                <!-- Navigation Links -->
                <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                    <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')" wire:navigate>
                        {{ __('Главная') }}
                    </x-nav-link>

                    <x-nav-link :href="route('literature.index')" :active="request()->routeIs('literature.index')" wire:navigate>
                        {{ __('Справочные материалы') }}
                    </x-nav-link>

                    <x-nav-link :href="route('notes.index')" :active="request()->routeIs('notes.index')">
                        {{ __('Заметки') }}
                    </x-nav-link>

                    <!-- Ссылка для администратора -->
                    @auth
                    @if (auth()->user()->role === 'admin')
                    <x-nav-link :href="route('admin.index')" :active="request()->routeIs('admin.index')" wire:navigate>
                        {{ __('Управление сайтом') }}
                    </x-nav-link>
                    @endif
                    @endauth
                </div>
            </div>

            <!-- Settings Dropdown and Notifications -->
            <div class="hidden sm:flex sm:items-center sm:ms-6">
                <!-- Кнопка уведомлений -->
                <div class="relative ms-4">
                    <button
                        @click="openNotifications = window.location.href = '{{ route('admin.notifications.index') }}'"
                        class="p-2 text-gray-700 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-300 focus:outline-none">
                        <!-- Иконка уведомлений -->
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
                        </svg>
                        <!-- Бейдж с количеством непрочитанных уведомлений -->
                        @if(auth()->user()->unreadNotifications->count() > 0)
                        <span class="absolute top-0 right-0 inline-flex items-center justify-center px-2 py-1 text-xs font-bold leading-none text-white bg-red-600 rounded-full">
                            {{ auth()->user()->unreadNotifications->count() }}
                        </span>
                        @endif
                    </button>
                </div>

                <!-- Кнопка задач -->
                <div class="relative ms-2 me-2">
                    <a href="{{ route('tasks.index') }}"
                       class="p-2 text-gray-700 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-300 focus:outline-none">
                        <!-- Иконка задач -->
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path>
                        </svg>
                    </a>
                </div>

                <!-- Settings Dropdown -->
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-700 dark:text-gray-400 gray-400 dark:bg-gray-800 hover:text-gray-900 dark:hover:text-gray-300 focus:outline-none transition ease-in-out duration-150">
                            <div x-data="{{ json_encode(['name' => auth()->user()->name]) }}" x-text="name" x-on:profile-updated.window="name = $event.detail.name"></div>

                            <div class="ms-1">
                                <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </div>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <x-dropdown-link :href="route('profile')" wire:navigate>
                            {{ __('Профиль') }}
                        </x-dropdown-link>

                         <x-dropdown-link :href="route('my-files')" wire:navigate>
                            {{ __('Мои файлы') }}
                        </x-dropdown-link>

                        <x-dropdown-link :href="route('shared-links')" wire:navigate>
                            {{ __('Мои ссылки') }}
                        </x-dropdown-link>

                        <x-dropdown-link id="theme-toggle" class="cursor-pointer">
                            {{ __('Сменить тему') }}
                        </x-dropdown-link>

                        <!-- Authentication -->
                        <button wire:click="logout" class="w-full text-start">
                            <x-dropdown-link>
                                {{ __('Выйти') }}
                            </x-dropdown-link>
                        </button>
                    </x-slot>
                </x-dropdown>
            </div>

            <!-- Hamburger and Notifications (Mobile) -->
            <div class="-me-2 flex items-center sm:hidden">
                <!-- Кнопка уведомлений -->
                <div class="relative ms-4">
                    <button
                        @click="openNotifications = window.location.href = '{{ route('admin.notifications.index') }}'"
                        class="p-2 text-gray-900 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300 focus:outline-none">
                        <!-- Иконка уведомлений -->
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
                        </svg>
                        <!-- Бейдж с количеством непрочитанных уведомлений -->
                        @if(auth()->user()->unreadNotifications->count() > 0)
                        <span class="absolute top-0 right-0 inline-flex items-center justify-center px-2 py-1 text-xs font-bold leading-none text-white bg-red-600 rounded-full">
                            {{ auth()->user()->unreadNotifications->count() }}
                        </span>
                        @endif
                    </button>
                </div>

                <!-- Кнопка задач -->
                <div class="relative ms-2 me-2">
                    <a href="{{ route('tasks.index') }}"
                       class="p-2 text-gray-900 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300 focus:outline-none">
                        <!-- Иконка задач -->
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path>
                        </svg>
                    </a>
                </div>

                <!-- Кнопка меню (Гамбургер) -->
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-gray-900 dark:text-gray-500 hover:text-gray-900 dark:hover:text-gray-400 hover:bg-gray-500 dark:hover:bg-gray-900 focus:outline-none focus:bg-gray-500 dark:focus:bg-gray-900 focus:text-gray-900 dark:focus:text-gray-400 transition duration-150 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden">
        <div class="pt-2 pb-3 space-y-1">
            <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')" wire:navigate>
                {{ __('Главная') }}
            </x-responsive-nav-link>

            <x-responsive-nav-link :href="route('literature.index')" :active="request()->routeIs('literature.index')" wire:navigate>
                {{ __('Справочные материалы') }}
            </x-responsive-nav-link>

            <x-responsive-nav-link :href="route('notes.index')" :active="request()->routeIs('notes.index')">
                {{ __('Заметки') }}
            </x-responsive-nav-link>

            <!-- Ссылка для администратора -->
            @auth
            @if (auth()->user()->role === 'admin')
            <x-responsive-nav-link :href="route('admin.index')" :active="request()->routeIs('admin.index')" wire:navigate>
                {{ __('Управление сайтом') }}
                </x-nav-link>
                @endif
                @endauth
        </div>

        <!-- Responsive Settings Options -->
        <div class="pt-4 pb-1 border-t border-gray-700 dark:border-gray-600">
            <div class="px-4">
                <div class="font-medium text-base text-gray-900 dark:text-gray-200" x-data="{{ json_encode(['name' => auth()->user()->name]) }}" x-text="name" x-on:profile-updated.window="name = $event.detail.name"></div>
                <div class="font-medium text-sm text-gray-700 dark:text-gray-500">{{ auth()->user()->email }}</div>
            </div>

            <div class="mt-3 space-y-1">
                <x-responsive-nav-link :href="route('profile')" wire:navigate>
                    {{ __('Профиль') }}
                </x-responsive-nav-link>

                 <x-responsive-nav-link :href="route('my-files')" wire:navigate>
                    {{ __('Мои файлы') }}
                </x-responsive-nav-link>

                <x-responsive-nav-link :href="route('shared-links')" wire:navigate>
                    {{ __('Мои ссылки') }}
                </x-responsive-nav-link>

                <x-responsive-nav-link href="#" id="theme-toggle-mobile" class="cursor-pointer">
                    {{ __('Сменить тему') }}
                </x-responsive-nav-link>

                <!-- Authentication -->
                <button wire:click="logout" class="bg-red-600 text-white w-full text-start">
                    <x-responsive-nav-link>
                        {{ __('Выйти') }}
                    </x-responsive-nav-link>
                </button>
            </div>
        </div>
    </div>
 
        <!-- Скрипт для смены темы -->
        <script>
        // Определяем функцию в глобальной области видимости
        window.toggleTheme = function() {
            const htmlElement = document.documentElement;
            if (htmlElement.classList.contains('dark')) {
                htmlElement.classList.remove('dark');
                localStorage.setItem('theme', 'light');
            } else {
                htmlElement.classList.add('dark');
                localStorage.setItem('theme', 'dark');
            }
        };

        // Функция для инициализации темы
        function initializeTheme() {
            const savedTheme = localStorage.getItem('theme') || 'light';
            document.documentElement.classList.toggle('dark', savedTheme === 'dark');
        }

        // Инициализация темы при загрузке страницы
        initializeTheme();

        // Обработчик для мобильной версии
        document.getElementById('theme-toggle-mobile')?.addEventListener('click', function(event) {
            event.preventDefault();
            toggleTheme();
        });

        // Обработчик для десктопной версии
        document.getElementById('theme-toggle')?.addEventListener('click', function(event) {
            event.preventDefault();
            toggleTheme();
        });

        // Livewire хук для переподключения обработчиков после обновления DOM
        Livewire.hook('morph.updated', () => {
            document.getElementById('theme-toggle-mobile')?.addEventListener('click', function(event) {
                event.preventDefault();
                toggleTheme();
            });

            document.getElementById('theme-toggle')?.addEventListener('click', function(event) {
                event.preventDefault();
                toggleTheme();
            });

            // Повторная инициализация темы после обновления DOM
            initializeTheme();
        });
    </script>
</nav>
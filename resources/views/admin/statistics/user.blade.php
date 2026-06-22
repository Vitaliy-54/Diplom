<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Статистика посещений') }}: {{ $user->name }}
            </h2>
            <div class="text-sm text-gray-800 dark:text-gray-400 animate__animated animate__fadeIn">
                {{ now()->translatedFormat('d F Y') }}
            </div>
        </div>
    </x-slot>

    <x-slot name="title">
        {{ __('Статистика пользователя') }}: {{ $user->name }}
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-sm mb-4 sm:text-base flex justify-between items-center">
                <span class="text-gray-900 dark:text-gray-400">
                    Актуальные данные на: {{ now()->format('d.m.Y H:i') }}
                </span>
                <button wire:navigate href="{{ route('admin.statistics.user', $user->id) }}"
                    class="p-2 rounded-lg hover:bg-gray-300 dark:hover:bg-gray-700 text-gray-500 dark:text-gray-400"
                    title="Обновить данные">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="#4299e1">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                    </svg>
                </button>
            </div>

            <!-- Информация о пользователе -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                <!-- Общая информация -->
                <div class="bg-gray-300 dark:bg-gray-800 shadow rounded-lg p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-medium text-gray-900 dark:text-white flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-2 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M12 2a10 10 0 100 20 10 10 0 000-20z" />
                            </svg>
                            {{ __('Общая информация:') }}
                        </h3>
                    </div>
                    <div class="space-y-3">
                        <div class="flex justify-between">
                            <span class="text-gray-700 dark:text-gray-400">{{ __('Посещено страниц') }}:</span>
                            <span class="font-medium text-gray-900 dark:text-white">{{ $statistics->count() }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-700 dark:text-gray-400">{{ __('Последний визит') }}:</span>
                            <span class="font-medium text-gray-900 dark:text-white">
                                {{ $statistics->isNotEmpty() && $statistics->first()->last_activity_at ? \Carbon\Carbon::parse($statistics->first()->last_activity_at)->format('d.m.Y H:i:s') : 'нет данных' }}
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Информация о пользователе -->
                <div class="bg-gray-300 dark:bg-gray-800 shadow rounded-lg p-6">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4 flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-2 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
                        {{ __('Пользователь:') }}
                    </h3>
                    <div class="space-y-3">
                        @php
                        $avatarDir = "avatars/{$user->id}";
                        $avatarFile = collect(Storage::files($avatarDir))
                        ->first(fn($f) => preg_match('/avatar\.(jpg|jpeg|png|svg|gif)$/i', basename($f)));
                        @endphp

                        <div class="flex items-center space-x-4">
                            <div class="h-24 w-24 rounded-full bg-gray-200 dark:bg-gray-600 overflow-hidden flex items-center justify-center">
                                @if($avatarFile)
                                <img
                                    src="{{ route('avatar.serve', ['user' => $user->id, 'filename' => basename($avatarFile)]) }}"
                                    alt="Avatar"
                                    class="h-full w-full object-cover">
                                @else
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                </svg>
                                @endif
                            </div>

                            <div>
                                <span class="block text-lg font-semibold text-gray-800 dark:text-white">{{ $user->name }}</span>
                                <span class="block text-lg text-sm font-semibold text-gray-800 dark:text-white" id="user-status-text-{{ $user->id }}">
                                    @php
                                    $isOnline = $user->id === auth()->id() || ($user->lastSession && $user->lastSession->last_activity > now()->subMinutes(5)->timestamp);
                                    @endphp
                                    <span class="{{ $isOnline ? 'text-green-500' : 'text-red-500' }} font-semibold">
                                        {{ $isOnline ? __('Онлайн') : __('Оффлайн') }}
                                    </span>
                                </span>
                                <span class="inline-block mt-1 px-2 py-0.5 text-xs font-semibold rounded-full                            
                {{ $user->role === 'admin' ? 'bg-purple-200 dark:bg-purple-900 text-purple-900 dark:text-purple-200' : 'bg-green-200 dark:bg-green-900 text-green-900 dark:text-green-200' }}">
                                    {{ $user->role === 'admin' ? __('Администратор') : __('Пользователь') }}
                                </span>
                            </div>
                        </div>

                        <div class="flex items-center mt-2">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-gray-700 dark:text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                            </svg>
                            <span class="text-gray-800 dark:text-white">{{ $user->email }}</span>
                        </div>
                    </div>
                </div>

                <!-- Популярные страницы -->
                <div class="bg-gray-300 dark:bg-gray-800 shadow rounded-lg p-6">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4 flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-2 text-purple-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z" />
                        </svg>
                        {{ __('Топ страниц:') }}
                    </h3>
                    <div class="space-y-2">
                        @foreach($pageStats->take(5) as $stat)
                        <div>
                            <div class="flex justify-between text-sm mb-1">
                                <a href="{{ $stat->page }}"
                                    target="_blank"
                                    title="{{ $stat->page }}"
                                    class="text-gray-900 dark:text-gray-200 hover:underline hover:text-blue-700 dark:hover:text-blue-400 truncate max-w-xs inline-block">
                                    {{ $stat->page }}
                                </a>
                                <span class="text-gray-700 dark:text-gray-400">{{ $stat->visits }} {{ trans_choice('раз|раза|раз', $stat->visits) }}</span>
                            </div>
                            <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-2">
                                <div class="bg-blue-500 h-2 rounded-full" style="width: {{ ($stat->visits / $pageStats->max('visits')) * 100 }}%"></div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Фильтр по дате и поиску -->
            <form method="GET" class="mb-8 p-6 bg-gray-300 dark:bg-gray-800 rounded-2xl shadow-md">
                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-4">
                    <!-- Поиск по странице -->
                    <div>
                        <label for="page" class="block text-sm font-semibold text-gray-700 dark:text-gray-200 mb-1">Страница</label>
                        <input type="text" name="page" id="page" value="{{ request('page') }}"
                            placeholder="Введите URL"
                            class="w-full rounded-xl border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 px-3 py-2 shadow-sm focus:ring-2 focus:ring-blue-500 focus:outline-none">
                    </div>

                    <!-- Дата от -->
                    <div>
                        <label for="from" class="block text-sm font-semibold text-gray-700 dark:text-gray-200 mb-1">От</label>
                        <input type="datetime-local" name="from" id="from" value="{{ request('from') }}"
                            class="w-full rounded-xl border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 px-3 py-2 shadow-sm focus:ring-2 focus:ring-blue-500 focus:outline-none">
                    </div>

                    <!-- Дата до -->
                    <div>
                        <label for="to" class="block text-sm font-semibold text-gray-700 dark:text-gray-200 mb-1">До</label>
                        <input type="datetime-local" name="to" id="to" value="{{ request('to') }}"
                            class="w-full rounded-xl border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 px-3 py-2 shadow-sm focus:ring-2 focus:ring-blue-500 focus:outline-none">
                    </div>

                    <!-- Кнопки -->
                    <div class="flex flex-col sm:flex-row sm:items-end gap-2">
                        <button type="submit"
                            class="flex items-center justify-center px-4 py-2.5 bg-blue-600 text-white font-semibold rounded-xl hover:bg-blue-700 transition w-full sm:w-auto">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                            Поиск
                        </button>

                        <a href="{{ route('admin.statistics.user', $user->id) }}"
                            class="flex items-center justify-center px-4 py-2.5 bg-gray-500 text-white font-semibold rounded-xl hover:bg-gray-600 transition w-full sm:w-auto">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                            </svg>
                            Сбросить
                        </a>
                    </div>
                </div>
            </form>

            <!-- Таблица истории посещений -->
            <div class="bg-gray-400 border-2 border-gray-300 dark:bg-gray-800 dark:border-gray-700 shadow overflow-hidden sm:rounded-lg mb-6">
                <div class="px-4 py-5 sm:px-6 border-b border-gray-200 dark:border-gray-700">
                    <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-2 text-yellow-400 dark:text-yellow-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        {{ __('История посещений') }}
                    </h3>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-400 dark:divide-gray-700">
                        <thead class="bg-gray-300 dark:bg-gray-700">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-700 dark:text-gray-300 uppercase tracking-wider">
                                    {{ __('Страница') }}
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-700 dark:text-gray-300 uppercase tracking-wider">
                                    {{ __('Время посещения') }}
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-700 dark:text-gray-300 uppercase tracking-wider">
                                    {{ __('IP-адрес') }}
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-gray-200 dark:bg-gray-800 divide-y divide-gray-300 dark:divide-gray-700">
                            @forelse($statistics as $log)
                            <tr class="hover:bg-white dark:hover:bg-gray-700 transition">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-blue-800 dark:text-blue-400 truncate max-w-xs">
                                        <a href="{{ $log->page }}"
                                            target="_blank"
                                            title="{{ $log->page }}"
                                            class="text-gray-900 dark:text-gray-200 hover:underline hover:text-blue-700 dark:hover:text-blue-400 truncate max-w-xs inline-block">
                                            {{ Str::limit($log->page, 60) }}
                                        </a>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700 dark:text-gray-300">
                                    {{ $log->last_activity_at ? \Carbon\Carbon::parse($log->last_activity_at)->format('d.m.Y H:i:s') : 'нет данных' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700 dark:text-gray-300">
                                    {{ $log->ip_address }}
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="px-6 py-4 text-center text-sm text-red-600 dark:text-red-400">
                                    {{ __('Ничего не найдено') }}
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Кнопка назад -->
            <div class="flex justify-end">
                <a href="{{ route('visits.deletePage') }}" class="inline-flex mr-6 mb-16 items-center px-4 py-2 border border-red-400 dark:border-red-600 text-sm font-medium rounded-md shadow-sm text-gray-800 dark:text-gray-200 bg-red-300 dark:bg-red-700 hover:bg-red-400 dark:hover:bg-red-600">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                    </svg>
                    {{ __('Удалить историю') }}
                </a>

                <a href="{{ route('admin.statistics.visits') }}" class="inline-flex mb-16 items-center px-4 py-2 border border-gray-400 dark:border-gray-600 text-sm font-medium rounded-md shadow-sm text-gray-800 dark:text-gray-200 bg-gray-300 dark:bg-gray-700 hover:bg-gray-400 dark:hover:bg-gray-600">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    {{ __('Назад к статистике') }}
                </a>
            </div>
        </div>
    </div>

    <!-- Кнопка прокрутки (исправленная версия) -->
    <div x-data="{
        visible: false,
        isAtBottom: false,
        scrollTimeout: null,
        SCROLL_DURATION: 1000,
        
        init() {
            this.checkPosition();
            window.addEventListener('scroll', () => {
                this.debounceCheckPosition();
            });
        },
        
        debounceCheckPosition() {
            clearTimeout(this.scrollTimeout);
            this.scrollTimeout = setTimeout(() => {
                this.checkPosition();
            }, 100);
        },
        
        checkPosition() {
            const scrollPosition = window.innerHeight + window.scrollY;
            const documentHeight = document.body.offsetHeight;
            const buffer = 100;
            
            this.isAtBottom = scrollPosition >= documentHeight - buffer;
            this.visible = documentHeight > window.innerHeight;
        },
        
        toggleScroll() {
            if (this.isAtBottom) {
                this.smoothScrollTo(0, this.SCROLL_DURATION);
            } else {
                this.smoothScrollTo(document.body.scrollHeight, this.SCROLL_DURATION);
            }
        },
        
        smoothScrollTo(targetPosition, duration) {
            const startPosition = window.scrollY;
            const distance = targetPosition - startPosition;
            const startTime = performance.now();
            
            const animateScroll = (currentTime) => {
                const elapsedTime = currentTime - startTime;
                const progress = Math.min(elapsedTime / duration, 1);
                const easeProgress = this.easeInOutCubic(progress);
                
                window.scrollTo(0, startPosition + (distance * easeProgress));
                
                if (progress < 1) {
                    requestAnimationFrame(animateScroll);
                }
            };
            
            requestAnimationFrame(animateScroll);
        },
        
        easeInOutCubic(t) {
            return t < 0.5 ?
                4 * t * t * t :
                1 - Math.pow(-2 * t + 2, 3) / 2;
        }
    }"
    x-init="init"
    x-show="visible"
    x-transition
    class="fixed bottom-6 right-6 z-50">
        <button
            @click="toggleScroll()"
            class="bg-blue-600 hover:bg-blue-700 text-white p-3 rounded-full shadow-lg focus:outline-none focus:ring-2 focus:ring-blue-400"
            :title="isAtBottom ? 'Прокрутить вверх' : 'Прокрутить вниз'"
            aria-label="Прокрутить">
            <svg x-show="!isAtBottom" xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
            </svg>
            <svg x-show="isAtBottom" xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M5 15l7-7 7 7" />
            </svg>
        </button>
    </div>
</x-app-layout>

<!-- Подключение Alpine.js (если еще не подключено в layout) -->
@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
@endpush
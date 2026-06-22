<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Статистика посещений') }}
            </h2>
            <div class="text-sm text-gray-800 dark:text-gray-400 animate__animated animate__fadeIn">
                {{ now()->translatedFormat('d F Y') }}
            </div>
        </div>
    </x-slot>

    <x-slot name="title">
        {{ __('Статистика посещений') }}
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-sm mb-4 sm:text-base flex justify-between items-center">
                <span class="text-gray-900 dark:text-gray-400">
                    Актуальные данные на: {{ now()->format('d.m.Y H:i') }}
                </span>
                <button wire:navigate href="{{ route('admin.statistics.visits') }}"
                    class="p-2 rounded-lg hover:bg-gray-300 dark:hover:bg-gray-700 text-gray-500 dark:text-gray-400"
                    title="Обновить данные">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="#4299e1">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                    </svg>
                </button>
            </div>

            <!-- Карточки статистики -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                <!-- Общая информация -->
                <div class="bg-gray-300 dark:bg-gray-800 shadow rounded-lg p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-medium text-gray-900 dark:text-white flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-2 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M12 2a10 10 0 100 20 10 10 0 000-20z" />
                            </svg>
                            {{ __('Общая информация') }}<br>
                            {{ __('(все пользователи):') }}
                        </h3>
                    </div>
                    <div class="space-y-3">
                        <div class="flex justify-between">
                            <span class="text-gray-700 dark:text-gray-400">{{ __('Всего посещений') }}:</span>
                            <span class="font-medium text-gray-900 dark:text-white">{{ $statistics->count() }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-700 dark:text-gray-400">{{ __('Пользователей в истории') }}:</span>
                            <span class="font-medium text-gray-900 dark:text-white">{{ $statistics->groupBy('user_id')->count() }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-700 dark:text-gray-400">{{ __('Последнее посещение') }}:</span>
                            <span class="font-medium text-gray-900 dark:text-white">
                                {{ $statistics->isNotEmpty() && $statistics->first()->last_activity_at ? \Carbon\Carbon::parse($statistics->first()->last_activity_at)->format('d.m.Y H:i:s') : 'нет данных' }}
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Популярные страницы -->
                <div class="bg-gray-300 dark:bg-gray-800 shadow rounded-lg p-6">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4 flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-2 text-purple-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z" />
                        </svg>
                        {{ __('Топ страниц (общий):') }}
                    </h3>
                    <div class="space-y-2">
                        @foreach($pageStats->take(5) as $stat)
                        <div>
                            <div class="flex justify-between text-sm mb-1">
                                <a href="{{ $stat->page }}"
                                    target="_blank"
                                    title="{{ $stat->page }}"
                                    class="text-gray-900 dark:text-gray-200 hover:underline hover:text-blue-700 dark:hover:text-blue-400 truncate max-w-xs inline-block">
                                    {{ Str::limit($stat->page, 40) }}
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

                <!-- Активные пользователи -->
                <div class="bg-gray-300 dark:bg-gray-800 shadow rounded-lg p-6">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4 flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-2 text-green-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                        </svg>
                        {{ __('Активные пользователи:') }}
                    </h3>
                    <div class="space-y-2">
                        @foreach($userStats->take(5) as $stat)
                        <div>
                            <div class="flex justify-between text-sm mb-1">
                                <a href="{{ route('admin.statistics.user', $stat->user_id) }}"
                                    class="text-gray-900 dark:text-gray-200 hover:underline hover:text-blue-700 dark:hover:text-blue-400 truncate max-w-xs inline-block">
                                    {{ $stat->user->name ?? 'Гость' }}
                                </a>
                                <span class="text-gray-700 dark:text-gray-400">{{ $stat->visits }} {{ trans_choice('визит|визита|визитов', $stat->visits) }}</span>
                            </div>
                            <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-2">
                                <div class="bg-green-500 h-2 rounded-full" style="width: {{ ($stat->visits / $userStats->max('visits')) * 100 }}%"></div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Фильтры -->
            <div class="bg-gray-300 dark:bg-gray-800 shadow rounded-lg mb-6 p-6">
                <form method="GET" action="{{ route('admin.statistics.visits') }}" class="space-y-4 sm:space-y-0 sm:grid sm:grid-cols-4 sm:gap-4">
                    <!-- Пользователь -->
                    <div>
                        <label for="user_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                            {{ __('Пользователь') }}
                        </label>
                        <select
                            name="user_id"
                            id="user_id"
                            class="block w-full rounded-xl border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 px-3 py-2 shadow-sm focus:ring-2 focus:ring-blue-500 focus:outline-none">
                            <option value="">{{ __('Все пользователи') }}</option>
                            @foreach($users as $user)
                            <option value="{{ $user->id }}" {{ request('user_id') == $user->id ? 'selected' : '' }}>
                                {{ $user->name }} ({{ $user->email }})
                            </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Дата и время от -->
                    <div>
                        <label for="date_from" class="block text-sm font-semibold text-gray-700 dark:text-gray-200 mb-1">
                            {{ __('Дата и время от') }}
                        </label>
                        <input
                            type="datetime-local"
                            name="date_from"
                            id="date_from"
                            value="{{ request('date_from') }}"
                            class="w-full rounded-xl border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 px-3 py-2 shadow-sm focus:ring-2 focus:ring-blue-500 focus:outline-none">
                    </div>

                    <!-- Дата и время до -->
                    <div>
                        <label for="date_to" class="block text-sm font-semibold text-gray-700 dark:text-gray-200 mb-1">
                            {{ __('Дата и время до') }}
                        </label>
                        <input
                            type="datetime-local"
                            name="date_to"
                            id="date_to"
                            value="{{ request('date_to') }}"
                            class="w-full rounded-xl border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 px-3 py-2 shadow-sm focus:ring-2 focus:ring-blue-500 focus:outline-none">
                    </div>

                    <!-- Кнопки -->
                    <div class="flex items-end space-x-2">
                        <button type="submit" class="inline-flex items-center px-4 py-3 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
                            </svg>
                            {{ __('Фильтровать') }}
                        </button>
                        <a href="{{ route('admin.statistics.visits') }}" class="inline-flex items-center px-4 py-2.5 border border-gray-300 dark:border-gray-700 text-sm font-medium rounded-md shadow-sm text-white dark:text-gray-200 bg-gray-500 dark:bg-gray-700 hover:bg-gray-600 dark:hover:bg-gray-600">
                            {{ __('Сбросить') }}
                        </a>
                    </div>
                </form>
            </div>

            <!-- График посещений по дням -->
            <div class="bg-white dark:bg-gray-800 shadow-lg rounded-2xl mb-6 p-6">
                <h3 class="text-xl font-semibold text-gray-900 dark:text-white mb-4 flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-2 text-indigo-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                    </svg>
                    {{ __('График посещений по дням (последний месяц)') }}
                </h3>
                <div x-data="visitsChartComponent" class="relative">
                    <div class="overflow-x-auto" style="padding-bottom: 1rem;">
                        <div style="min-width: 600px; height: 320px;"> <!-- Минимальная ширина и высота -->
                            <canvas id="visitsChart" style="height: 100%; width: 100%; display: block;"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Таблица статистики -->
            <div class="bg-gray-200 dark:bg-gray-800 shadow overflow-hidden sm:rounded-lg">
                <div class="bg-gray-400 border-2 border-gray-300 dark:bg-gray-800 dark:border-gray-700 rounded-t-lg">
                    <div class="px-4 py-5 sm:px-6 border-b border-gray-200 dark:border-gray-700">
                        <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-2 text-yellow-400 dark:text-yellow-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            {{ __('История посещений') }}
                        </h3>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-300 dark:divide-gray-700">
                            <thead class="bg-gray-400 dark:bg-gray-700">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-800 dark:text-gray-300 uppercase tracking-wider">
                                        {{ __('Пользователь') }}
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-800 dark:text-gray-300 uppercase tracking-wider">
                                        {{ __('Страница') }}
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-800 dark:text-gray-300 uppercase tracking-wider">
                                        {{ __('Время посещения') }}
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-800 dark:text-gray-300 uppercase tracking-wider">
                                        {{ __('IP-адрес') }}
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-gray-200 dark:bg-gray-800 divide-y divide-gray-300 dark:divide-gray-700">
                                @forelse($statistics as $stat)
                                <tr class="hover:bg-white dark:hover:bg-gray-700 transition">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div class="flex-shrink-0 h-10 w-10 text-gray-400">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                                </svg>
                                            </div>
                                            <div class="ml-4">
                                                <a href="{{ route('admin.statistics.user', $stat->user_id) }}" class="text-sm font-medium text-blue-600 dark:text-blue-400 hover:underline">
                                                    {{ $stat->user->name ?? 'Гость' }}
                                                </a>
                                                <div class="text-sm text-gray-500 dark:text-gray-400">
                                                    {{ $stat->user->email ?? 'Не аутентифицирован' }}
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-blue-700 dark:text-blue-400 truncate max-w-xs">
                                            <a href="{{ $stat->page }}" target="_blank" title="{{ $stat->page }}" class="hover:underline">
                                                {{ Str::limit($stat->page, 60) }}
                                            </a>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                        {{ \Carbon\Carbon::parse($stat->last_activity_at)->format('d.m.Y H:i:s') }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                        {{ $stat->ip_address }}
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
            </div>
        </div>
    </div>


    <!-- Кнопка прокрутки -->
    <div x-data="scrollButton()"
        x-init="init()"
        class="fixed bottom-6 right-6 z-50">
        <button
            @click="toggleScroll()"
            x-show="visible"
            x-transition
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

    <!-- Подключаем Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <!-- Скрипты -->
    <script>
        // Скрипт для графика
        document.addEventListener('alpine:init', () => {
            Alpine.data('visitsChartComponent', () => ({
                visitsChart: null,

                init() {
                    this.createChart(@json($chartData));

                    // Обновляем график после каждого Livewire-обновления
                    Livewire.hook('message.processed', () => {
                        // Получаем свежие данные из глобальной переменной, атрибута или подставь актуальные данные
                        // Если данные приходят с сервера через dispatchBrowserEvent, слушай событие отдельно

                        // Пример: допустим, данные доступны в window.updatedChartData
                        if (window.updatedChartData) {
                            this.updateChart(window.updatedChartData);
                        }
                    });
                },

                createChart(chartData) {
                    const ctx = document.getElementById('visitsChart').getContext('2d');
                    if (this.visitsChart) {
                        this.visitsChart.destroy();
                    }
                    this.visitsChart = new Chart(ctx, {
                        type: 'line',
                        data: {
                            labels: chartData.labels,
                            datasets: [{
                                label: 'Количество посещений',
                                data: chartData.data,
                                backgroundColor: 'rgba(79, 70, 229, 0.1)',
                                borderColor: 'rgba(79, 70, 229, 0.8)',
                                borderWidth: 2,
                                tension: 0.3,
                                fill: true
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            plugins: {
                                legend: {
                                    display: true,
                                    position: 'top',
                                    labels: {
                                        color: '#6b7280'
                                    }
                                },
                                tooltip: {
                                    mode: 'index',
                                    intersect: false
                                }
                            },
                            scales: {
                                x: {
                                    grid: {
                                        color: '#595959'
                                    },
                                    ticks: {
                                        color: '#6b7280'
                                    }
                                },
                                y: {
                                    beginAtZero: true,
                                    grid: {
                                        color: '#595959'
                                    },
                                    ticks: {
                                        color: '#6b7280',
                                        stepSize: 1
                                    }
                                }
                            }
                        }
                    });
                },

                updateChart(newData) {
                    if (!this.visitsChart) return;

                    this.visitsChart.data.labels = newData.labels;
                    this.visitsChart.data.datasets[0].data = newData.data;
                    this.visitsChart.update();
                }
            }));
        });

        // Скрипт для кнопки прокрутки
        document.addEventListener('alpine:init', () => {
            Alpine.data('scrollButton', () => ({
                visible: true,
                isAtBottom: false,
                scrollTimeout: null,
                SCROLL_DURATION: 1000, // 1 секунда

                init() {
                    this.checkPosition();
                    window.addEventListener('scroll', () => {
                        this.debounceCheckPosition();
                    });

                    // Слушаем события Livewire
                    window.addEventListener('livewire:load', () => {
                        Livewire.hook('message.processed', () => {
                            this.checkPosition();
                        });
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
            }));
        });
    </script>
</x-app-layout>
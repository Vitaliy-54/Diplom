@php
    if (!function_exists('trans_choice')) {
        function trans_choice($text, $number) {
            $forms = explode('|', $text);
            $number = abs($number) % 100;
            $remainder = $number % 10;
            
            if ($number > 10 && $number < 20) return $forms[2];
            if ($remainder > 1 && $remainder < 5) return $forms[1];
            if ($remainder == 1) return $forms[0];
            return $forms[2];
        }
    }
@endphp

<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                    {{ __('Статистика расчёта') }}
                </h2>
                <p class="text-sm text-gray-700 dark:text-gray-400 mt-1">Аналитика публичной ссылки</p>
            </div>
            <div class="text-sm text-gray-800 dark:text-gray-400">
                {{ now()->translatedFormat('d F Y') }}
            </div>
        </div>
    </x-slot>

    <x-slot name="title">
        {{ __('Статистика расчёта') }}
    </x-slot>

    <div class="py-6 px-4 sm:px-6">
        <div class="max-w-7xl mx-auto">
            
            <!-- Информация о расчёте -->
            <div class="mb-6">
                <div class="flex items-center gap-2 mb-3 ml-2">
                    <div class="h-6 w-1 bg-indigo-500 rounded-full"></div>
                    <span class="text-sm font-medium text-indigo-700 dark:text-indigo-400 uppercase tracking-wider">Информация о расчёте:</span>
                </div>

                <div class="bg-gray-300/30 border border-gray-400/70 dark:border-gray-800 dark:bg-gray-800/50 rounded-lg p-4 space-y-2">
                    <!-- Название -->
                    <div class="flex items-center gap-3">
                        <svg class="h-4 w-4 text-indigo-700 dark:text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        <span class="text-sm text-gray-900 dark:text-gray-400 min-w-[70px]">Название:</span>
                        <span class="text-sm font-medium text-gray-900 dark:text-white">{{ $shareLink->display_title }}</span>
                    </div>

                    <!-- Описание -->
                    @if($shareLink->description)
                    <div class="flex items-start gap-3">
                        <svg class="h-4 w-4 text-indigo-700 dark:text-indigo-400 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h7"></path>
                        </svg>
                        <span class="text-sm text-gray-900 dark:text-gray-400 min-w-[70px] flex-shrink-0">Описание:</span>
                        <span class="text-sm text-gray-700 dark:text-gray-400 break-words whitespace-normal flex-1">{{ $shareLink->description }}</span>
                    </div>
                    @endif

                    <!-- Ссылка -->
                    <div class="flex items-center gap-3">
                        <svg class="h-4 w-4 text-indigo-700 dark:text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.368 2.684 3 3 0 00-5.368-2.684z"></path>
                        </svg>
                        <span class="text-sm text-gray-900 dark:text-gray-400 min-w-[70px]">Ссылка:</span>
                        <div class="flex items-center gap-2 flex-1">
                            <code class="text-sm text-gray-700 dark:text-gray-300 border border-gray-400/70 dark:border-gray-800 bg-gray-200 dark:bg-gray-700 px-2 py-1 rounded break-all flex-1 font-mono">
                                {{ $shareLink->url }}
                            </code>
                            <button onclick="copyToClipboard('{{ $shareLink->url }}')" class="flex-shrink-0 text-indigo-600 dark:text-indigo-400 hover:text-indigo-800 dark:hover:text-indigo-300 transition-colors" title="Скопировать ссылку">
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
                                </svg>
                            </button>
                            <a href="{{ $shareLink->url }}" target="_blank" class="flex-shrink-0 text-indigo-600 dark:text-indigo-400 hover:text-indigo-800 dark:hover:text-indigo-300 transition-colors" title="Открыть расчёт">
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path>
                                </svg>
                            </a>
                        </div>
                    </div>

                    <!-- Поделился -->
                    <div class="flex items-center gap-3">
                        <svg class="h-4 w-4 text-indigo-700 dark:text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
                        <span class="text-sm text-gray-900 dark:text-gray-400 min-w-[70px]">Поделился:</span>
                        <span class="text-sm text-gray-900 dark:text-white">{{ $shareLink->creator->name ?? 'User' }}</span>
                    </div>

                    <!-- Создан -->
                    <div class="flex items-center gap-3">
                        <svg class="h-4 w-4 text-indigo-700 dark:text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                        <span class="text-sm text-gray-900 dark:text-gray-400 min-w-[70px]">Создан:</span>
                        <span class="text-sm text-gray-700 dark:text-gray-400">{{ $shareLink->created_at->translatedFormat('d F Y H:i') }}</span>
                    </div>

                    <!-- Срок действия -->
                    <div class="flex items-center gap-3">
                        <svg class="h-4 w-4 text-indigo-700 dark:text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <span class="text-sm text-gray-900 dark:text-gray-400 min-w-[70px]">Истекает:</span>
                        @if($shareLink->expires_at)
                            <span class="text-sm text-yellow-700 dark:text-yellow-400">
                                {{ $shareLink->expires_at->translatedFormat('d F Y H:i') }}
                                <span class="text-xs text-gray-700 dark:text-gray-400 ml-1">({{ $shareLink->expires_at->diffForHumans() }})</span>
                            </span>
                        @else
                            <span class="text-sm text-green-700 dark:text-green-400">Бессрочно</span>
                        @endif
                    </div>

                    <!-- Статус -->
                    <div class="flex items-center gap-3">
                        <svg class="h-4 w-4 text-indigo-700 dark:text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <span class="text-sm text-gray-900 dark:text-gray-400 min-w-[70px]">Статус:</span>
                        @if($shareLink->is_active && (!$shareLink->expires_at || $shareLink->expires_at > now()))
                            <span class="inline-flex items-center gap-1 text-sm text-green-700 dark:text-green-400">
                                <span class="w-2 h-2 bg-green-500 rounded-full"></span>
                                Активна
                            </span>
                        @elseif(!$shareLink->is_active)
                            <span class="inline-flex items-center gap-1 text-sm text-red-600 dark:text-red-400">
                                <span class="w-2 h-2 bg-red-500 rounded-full"></span>
                                Отозвана
                            </span>
                        @else
                            <span class="inline-flex items-center gap-1 text-sm text-yellow-600 dark:text-yellow-400">
                                <span class="w-2 h-2 bg-yellow-500 rounded-full"></span>
                                Истекла
                            </span>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Статистика карточки -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
                <!-- Всего просмотров -->
                <div class="bg-gradient-to-br from-indigo-500 to-indigo-600 rounded-xl p-5 text-white shadow-lg">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm opacity-90">Всего просмотров</p>
                            <p class="text-3xl font-bold mt-1">{{ number_format($stats['total_views']) }}</p>
                            <p class="text-xs opacity-75 mt-1">за всё время</p>
                        </div>
                        <svg class="h-10 w-10 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                        </svg>
                    </div>
                </div>
                
                <!-- Уникальные посетители -->
                <div class="bg-gradient-to-br from-emerald-500 to-emerald-600 rounded-xl p-5 text-white shadow-lg">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm opacity-90">Уникальные посетители</p>
                            <p class="text-3xl font-bold mt-1">{{ number_format($stats['unique_views']) }}</p>
                            <p class="text-xs opacity-75 mt-1">уникальных IP</p>
                        </div>
                        <svg class="h-10 w-10 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                        </svg>
                    </div>
                </div>
                
                <!-- Среднее в день -->
                <div class="bg-gradient-to-br from-amber-500 to-amber-600 rounded-xl p-5 text-white shadow-lg">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm opacity-90">В среднем в день</p>
                            <p class="text-3xl font-bold mt-1">{{ number_format(round($stats['total_views'] / max($shareLink->created_at->diffInDays(now()), 1))) }}</p>
                            <p class="text-xs opacity-75 mt-1">просмотров/день</p>
                        </div>
                        <svg class="h-10 w-10 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                        </svg>
                    </div>
                </div>
                
                <!-- Последний просмотр -->
                <div class="bg-gradient-to-br from-purple-500 to-purple-600 rounded-xl p-5 text-white shadow-lg">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm opacity-90">Последний просмотр</p>
                            <p class="text-xl font-bold mt-1">{{ $shareLink->last_accessed_at ? $shareLink->last_accessed_at->diffForHumans() : 'Нет данных' }}</p>
                            <p class="text-xs opacity-75 mt-1">
                                @if($shareLink->last_accessed_at)
                                    {{ $shareLink->last_accessed_at->translatedFormat('d F Y H:i') }}
                                @endif
                            </p>
                        </div>
                        <svg class="h-10 w-10 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                </div>
            </div>
            
            <!-- График просмотров -->
            <div class="bg-gray-300 dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6">
                    <div class="pb-4 mb-4">
                        <h2 class="text-lg font-semibold text-gray-900 dark:text-white flex items-center gap-2">
                            <svg class="h-5 w-5 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                            </svg>
                            Динамика просмотров
                        </h2>
                        <p class="text-sm text-gray-700 dark:text-gray-400 mt-1">Последние 30 дней</p>
                    </div>
                    <canvas id="viewsChart" height="150" class="w-full"></canvas>
                </div>
            </div>   


<!-- История копирования в аккаунт -->
<div class="bg-gray-300 dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg mb-6">
    <div class="p-6">
        <div class="border-b border-gray-400 dark:border-gray-700 pb-4 mb-4">
            <h2 class="text-lg font-semibold text-gray-900 dark:text-white flex items-center gap-2">
                <svg class="h-5 w-5 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
                </svg>
                История копирования в аккаунт
                @if(isset($stats['copy_log']) && count($stats['copy_log']) > 0)
                <span class="text-xs text-gray-600 dark:text-gray-400 mt-0.5">{{ count($stats['copy_log']) }} {{ trans_choice('копирование|копирования|копирований', count($stats['copy_log'])) }}</span>
                @endif
            </h2>
        </div>
        
        @if(isset($stats['copy_log']) && count($stats['copy_log']) > 0)
            <!-- Таблица с данными -->
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-300 dark:divide-gray-700">
                    <thead class="bg-gray-400 dark:bg-gray-700">
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-800 dark:text-gray-300 uppercase tracking-wider">Дата и время</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-800 dark:text-gray-300 uppercase tracking-wider">Пользователь</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-800 dark:text-gray-300 uppercase tracking-wider">Статус</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-400 dark:divide-gray-700">
                        @foreach($stats['copy_log'] as $log)
                        <tr class="hover:bg-gray-200/70 dark:hover:bg-gray-700/50 transition-colors duration-150">
                            <td class="px-4 py-2 text-sm text-gray-900 dark:text-gray-100 whitespace-nowrap">
                                {{ \Carbon\Carbon::parse($log['copied_at'])->translatedFormat('d F Y H:i:s') }}
                            </td>
                            <td class="px-4 py-2 text-sm">
                                @php
                                    $userId = $log['user_id'] ?? null;
                                    $user = $userId ? \App\Models\User::find($userId) : null;
                                @endphp
                                
                                @if($user)
                                    <a href="{{ route('user.info', ['user' => $user->id]) }}" 
                                       class="text-gray-900 dark:text-gray-300 hover:text-blue-800 dark:hover:text-blue-300 hover:underline transition-colors duration-150">
                                        {{ $user->name }}
                                    </a>
                                @else
                                    <span class="text-gray-500 dark:text-gray-400 italic">Пользователь удалён</span>
                                @endif
                            </td>
                            <td class="px-4 py-2 text-sm">
                                @if(($log['success'] ?? true) || ($log['status'] ?? 'success') === 'success')
                                    <span class="inline-flex items-center gap-1 text-green-700 dark:text-green-400">
                                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                        </svg>
                                        Успешно
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1 text-red-700 dark:text-red-400">
                                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                        </svg>
                                        {{ $log['error'] ?? 'Ошибка' }}
                                    </span>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <!-- Пустое состояние -->
            <div class="text-center py-8">
                <div class="w-16 h-16 mx-auto bg-gray-100 dark:bg-gray-700 rounded-full flex items-center justify-center mb-3">
                    <svg class="h-8 w-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
                    </svg>
                </div>
                <p class="text-gray-700 dark:text-gray-400 text-sm">Нет данных о копировании</p>
                <p class="text-xs text-gray-800 dark:text-gray-500 mt-1">Пользователи ещё не копировали этот расчёт в свои аккаунты</p>
            </div>
        @endif
    </div>
</div>
            
            <!-- Лог доступа -->
            @if(!empty($stats['access_log']) && count($stats['access_log']) > 0)
            <div class="bg-gray-300 dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6">
                    <div class="border-b border-gray-400 dark:border-gray-700 pb-4 mb-4">
                        <h2 class="text-lg font-semibold text-gray-900 dark:text-white flex items-center gap-2">
                            <svg class="h-5 w-5 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            История просмотров
                            <span class="text-xs text-gray-600 dark:text-gray-400 mt-0.5">{{ count($stats['access_log']) }} {{ trans_choice('запись|записи|записей', count($stats['access_log'])) }}</span>
                        </h2>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-300 dark:divide-gray-700">
                            <thead class="bg-gray-400 dark:bg-gray-700">
                                <tr>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-800 dark:text-gray-300 uppercase tracking-wider">Дата и время</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-800 dark:text-gray-300 uppercase tracking-wider">IP-адрес</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-800 dark:text-gray-300 uppercase tracking-wider">Пользователь</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-800 dark:text-gray-300 uppercase tracking-wider">Источник</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-400 dark:divide-gray-700">
                                @foreach($stats['access_log'] as $log)
                                <tr class="hover:bg-gray-200/70 dark:hover:bg-gray-700/50 transition-colors duration-150">
                                    <td class="px-4 py-2 text-sm text-gray-900 dark:text-gray-100 whitespace-nowrap">
                                        {{ \Carbon\Carbon::parse($log['accessed_at'])->translatedFormat('d F Y H:i:s') }}
                                    </td>
                                    <td class="px-4 py-2 text-sm text-gray-700 dark:text-gray-300 font-mono">
                                        {{ $log['ip'] ?? '-' }}
                                    </td>
                                    <td class="px-4 py-2 text-sm text-gray-700 dark:text-gray-300">
                                        @php
                                            $userId = $log['user_id'] ?? null;
                                            $user = $userId ? \App\Models\User::find($userId) : null;
                                        @endphp
                                        
                                        @if($user)
                                            <a href="{{ route('user.info', ['user' => $user->id]) }}" 
                                            class="text-gray-900 dark:text-gray-300 hover:text-blue-800 dark:hover:text-blue-300 hover:underline transition-colors duration-150">
                                                {{ $user->name }}
                                            </a>
                                        @else
                                            <span class="text-gray-500 dark:text-gray-400 italic">Гость</span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-2 text-sm text-gray-900 dark:text-gray-400 max-w-[300px] break-words" title="{{ $log['referer'] ?? '' }}">
                                        @php
                                            $referer = $log['referer'] ?? '';
                                            $isDirect = empty($referer);
                                        @endphp
                                        
                                        @if($isDirect)
                                            <span class="text-gray-800 dark:text-gray-300 italic flex items-center gap-1">
                                                Прямой переход
                                            </span>
                                        @else
                                            <a href="{{ $referer }}" 
                                            target="_blank" 
                                            rel="noopener noreferrer"
                                            class="text-gray-900 dark:text-gray-300 hover:text-blue-800 dark:hover:text-blue-300 hover:underline transition-colors duration-150 break-words"
                                            title="{{ $referer }}">
                                                {{ $referer }}
                                            </a>
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>               
            </div>
            @endif

<div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">                              
    <!-- Типы устройств -->
    <div class="bg-gray-300 dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6">
            <div class="border-b border-gray-400 dark:border-gray-700 pb-4 mb-4">
                <h2 class="text-lg font-semibold text-gray-900 dark:text-white flex items-center gap-2">
                    <svg class="h-5 w-5 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                    </svg>
                    Типы устройств
                </h2>
            </div>
            @if(count($stats['user_agents']) > 0)
                <div class="flex flex-col lg:flex-row gap-6 items-center">
                    <!-- Круговая диаграмма -->
                    <div class="flex-shrink-0 relative">
                        <div class="relative" style="width: 220px; height: 220px;">
                            <canvas id="deviceChart" width="220" height="220"></canvas>
                            <div class="absolute inset-0 flex items-center justify-center">
                                <div class="text-center">
                                    <div class="text-3xl font-bold text-gray-900 dark:text-white">
                                        {{ number_format(array_sum($stats['user_agents'])) }}
                                    </div>
                                    <div class="text-xs text-gray-700 dark:text-gray-400">
                                        @php
                                            $totalCount = array_sum($stats['user_agents']);
                                            $remainder10 = $totalCount % 10;
                                            $remainder100 = $totalCount % 100;
                                        @endphp
                                        @if($remainder100 >= 11 && $remainder100 <= 19)
                                            всего
                                        @elseif($remainder10 == 1)
                                            всего
                                        @else
                                            всего
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Легенда и статистика -->
                    <div class="flex-1 grid grid-cols-1 sm:grid-cols-3 gap-3">
                        @foreach($stats['user_agents'] as $type => $count)
                            @php
                                $total = array_sum($stats['user_agents']);
                                $percentage = round($count / $total * 100);
                                
                                // Функция склонения для просмотров
                                $viewsText = function($n) {
                                    $remainder10 = $n % 10;
                                    $remainder100 = $n % 100;
                                    
                                    if ($remainder100 >= 11 && $remainder100 <= 19) {
                                        return 'просмотров';
                                    }
                                    if ($remainder10 == 1) {
                                        return 'просмотр';
                                    }
                                    if ($remainder10 >= 2 && $remainder10 <= 4) {
                                        return 'просмотра';
                                    }
                                    return 'просмотров';
                                };
                                
                                $config = [
                                    'desktop' => [
                                        'name' => 'Компьютер',
                                        'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>',
                                        'color' => 'from-indigo-500 to-indigo-600',
                                    ],
                                    'mobile' => [
                                        'name' => 'Телефон',
                                        'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"/>',
                                        'color' => 'from-emerald-500 to-emerald-600',
                                    ],
                                    'tablet' => [
                                        'name' => 'Планшет',
                                        'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>',
                                        'color' => 'from-amber-500 to-amber-600',
                                    ],
                                    'bot' => [
                                        'name' => 'Бот',
                                        'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/>',
                                        'color' => 'from-gray-500 to-gray-600',
                                    ],
                                    'other' => [
                                        'name' => 'Другое',
                                        'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16l2.879-2.879m0 0a3 3 0 104.243-4.242 3 3 0 00-4.243 4.242zM21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>',
                                        'color' => 'from-purple-500 to-purple-600',
                                    ]
                                ];
                                $cfg = $config[$type] ?? $config['other'];
                            @endphp
                            <div class="group relative overflow-hidden bg-gradient-to-br {{ $cfg['color'] }} rounded-xl p-4 text-white shadow-lg hover:shadow-xl transition-all duration-300">
                                <div class="absolute top-0 right-0 w-20 h-20 bg-white/10 rounded-bl-full"></div>
                                <div class="relative z-10">
                                    <div class="flex items-center justify-between mb-3">
                                        <div class="w-6 h-6 flex items-center justify-center">
                                            <svg class="w-5 h-5 text-white/80" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="min-width: 20px; min-height: 20px;">
                                                {!! $cfg['icon'] !!}
                                            </svg>
                                        </div>
                                        <span class="text-2xl font-bold">{{ $percentage }}%</span>
                                    </div>
                                    <p class="text-sm font-medium text-white/90">{{ $cfg['name'] }}</p>
                                    <div class="flex items-baseline gap-1 mt-2">
                                        <span class="text-2xl font-bold">{{ number_format($count) }}</span>
                                        <span class="text-xs text-white/70">{{ $viewsText($count) }}</span>
                                    </div>
                                    <div class="w-full bg-white/20 rounded-full h-1.5 mt-3">
                                        <div class="bg-white rounded-full h-1.5 transition-all duration-500" style="width: {{ $percentage }}%"></div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @else
                <div class="text-center py-12">
                    <div class="w-20 h-20 mx-auto bg-gray-100 dark:bg-gray-700 rounded-full flex items-center justify-center mb-4">
                        <svg class="h-10 w-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                        </svg>
                    </div>
                    <p class="text-gray-700 dark:text-gray-400 text-sm">Нет данных об устройствах</p>
                    <p class="text-xs text-gray-800 dark:text-gray-500 mt-1">Данные появятся после первых переходов</p>
                </div>
            @endif
        </div>
    </div>
</div>
            
            <!-- Footer -->
            <div class="mt-8 pt-6 border-t border-gray-200 dark:border-gray-700 text-center text-xs text-gray-700 dark:text-gray-400">
                <p>Статистика расчёта — Аналитика публичной ссылки</p>
                <p class="mt-1">Ссылка создана: {{ $shareLink->created_at->translatedFormat('d F Y H:i') }}</p>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        // Copy to clipboard function
        function copyToClipboard(text) {
            navigator.clipboard.writeText(text);
            showToast('Ссылка скопирована!');
        }
        
        function showToast(message) {
            const toast = document.createElement('div');
            toast.className = 'fixed bottom-4 right-4 px-4 py-2 bg-green-600 text-white text-sm rounded-lg shadow-lg z-50';
            toast.textContent = message;
            document.body.appendChild(toast);
            setTimeout(() => toast.remove(), 2000);
        }
        

        // Views Chart
        @if(!empty($chartData['views_over_time']['labels']) || !empty($chartData['daily_views']['labels']))
        const viewsCtx = document.getElementById('viewsChart');
        if (viewsCtx) {
            const isDark = document.documentElement.classList.contains('dark');
            
        // Функция склонения слова "просмотр" (оставляем для tooltip)
        function declineViews(count) {
            const remainder10 = count % 10;
            const remainder100 = count % 100;
            
            if (remainder100 >= 11 && remainder100 <= 19) {
                return `${count} просмотров`;
            }
            if (remainder10 === 1) {
                return `${count} просмотр`;
            }
            if (remainder10 >= 2 && remainder10 <= 4) {
                return `${count} просмотра`;
            }
            return `${count} просмотров`;
        }
            
            // Получаем сырые даты
            const rawLabels = {!! json_encode($chartData['views_over_time']['labels'] ?? $chartData['daily_views']['labels'] ?? []) !!};
            const rawValues = {!! json_encode($chartData['views_over_time']['values'] ?? $chartData['daily_views']['values'] ?? []) !!};
            
            // Форматируем даты в дд.мм.гггг
            const formattedLabels = rawLabels.map(label => {
                // Если дата в формате YYYY-MM-DD
                if (label && label.match(/^\d{4}-\d{2}-\d{2}/)) {
                    const parts = label.split('-');
                    return `${parts[2]}.${parts[1]}.${parts[0]}`;
                }
                // Если дата в другом формате, пробуем создать объект Date
                try {
                    const date = new Date(label);
                    if (!isNaN(date.getTime())) {
                        const day = String(date.getDate()).padStart(2, '0');
                        const month = String(date.getMonth() + 1).padStart(2, '0');
                        const year = date.getFullYear();
                        return `${day}.${month}.${year}`;
                    }
                } catch(e) {}
                return label;
            });
            
            new Chart(viewsCtx, {
                type: 'line',
                data: {
                    labels: formattedLabels,
                    datasets: [{
                        label: 'Просмотры',
                        data: rawValues,
                        borderColor: '#4f46e5',
                        backgroundColor: 'rgba(79, 70, 229, 0.1)',
                        fill: true,
                        tension: 0.3,
                        pointRadius: 4,
                        pointHoverRadius: 6,
                        pointBackgroundColor: '#4f46e5',
                        pointBorderColor: '#fff',
                        pointBorderWidth: 2
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: true,
                    plugins: {
                        legend: {
                            display: false
                        },
                        tooltip: {
                            backgroundColor: isDark ? '#1f2937' : '#ffffff',
                            titleColor: isDark ? '#f3f4f6' : '#1f2937',
                            bodyColor: isDark ? '#d1d5db' : '#6b7280',
                            borderColor: '#4f46e5',
                            borderWidth: 1,
                            callbacks: {
                                label: (context) => declineViews(context.parsed.y)
                            }
                        }
                    },
                    scales: {
                        x: {
                            ticks: { 
                                color: isDark ? '#9ca3af' : '#6b7280',
                                maxRotation: 45,
                                autoSkip: true
                            },
                            grid: { display: false }
                        },
                        y: {
                           ticks: { 
                                color: isDark ? '#9ca3af' : '#6b7280',
                                stepSize: 1,
                                callback: function(val) {
                                    return val;
                                },
                                padding: 10  // Отступ слева в пикселях
                            },
                            grid: { 
                                color: isDark ? '#4c5461' : '#4c5461' 
                            },
                            title: {
                                display: true,
                                text: 'Просмотры',
                                color: isDark ? '#b9bcc0' : '#6b7280'
                            }
                        }
                    }
                }
            });
        }
        @endif
        
        // Device Chart
// Device Chart
@if(count($stats['user_agents']) > 0)
let deviceChart = null;

function initDeviceChart() {
    const deviceCtx = document.getElementById('deviceChart');
    if (!deviceCtx) return;
    
    const isDark = document.documentElement.classList.contains('dark');
    const labels = {!! json_encode(array_keys($stats['user_agents'])) !!};
    const displayLabels = labels.map(l => {
        const names = {'desktop': 'Компьютер', 'mobile': 'Телефон', 'tablet': 'Планшет', 'bot': 'Бот', 'other': 'Другое'};
        return names[l] || l;
    });
    const values = {!! json_encode(array_values($stats['user_agents'])) !!};
    const colors = {
        'desktop': '#4f46e5',
        'mobile': '#10b981', 
        'tablet': '#f59e0b',
        'bot': '#6b7280',
        'other': '#8b5cf6'
    };
    
    const backgroundColors = labels.map(l => colors[l] || '#8b5cf6');
    
    if (deviceChart) {
        deviceChart.destroy();
    }
    
    deviceChart = new Chart(deviceCtx, {
        type: 'doughnut',
        data: {
            labels: displayLabels,
            datasets: [{
                data: values,
                backgroundColor: backgroundColors,
                borderWidth: 0,
                hoverOffset: 15,
                cutout: '65%',
                borderRadius: 8,
                spacing: 3
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            plugins: {
                legend: {
                    display: false
                },
                tooltip: {
                    backgroundColor: isDark ? '#1f2937' : '#ffffff',
                    titleColor: isDark ? '#f3f4f6' : '#1f2937',
                    bodyColor: isDark ? '#d1d5db' : '#6b7280',
                    borderColor: '#4f46e5',
                    borderWidth: 1,
                    padding: 10,
                    cornerRadius: 8,
                    callbacks: {
                        label: (context) => {
                            const total = context.dataset.data.reduce((a, b) => a + b, 0);
                            const percentage = Math.round((context.raw / total) * 100);
                            return `${context.label}: ${context.raw.toLocaleString()} просмотров (${percentage}%)`;
                        }
                    }
                }
            },
            cutout: '65%'
        }
    });
}

// Инициализация
document.addEventListener('DOMContentLoaded', function() {
    initDeviceChart();
});

// Обновление при смене темы
const deviceThemeObserver = new MutationObserver(function(mutations) {
    mutations.forEach(function(mutation) {
        if (mutation.attributeName === 'class') {
            initDeviceChart();
        }
    });
});
deviceThemeObserver.observe(document.documentElement, {
    attributes: true,
    attributeFilter: ['class']
});
@endif
    </script>
</x-app-layout>
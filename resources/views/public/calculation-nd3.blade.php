<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                      {{ __('Просмотр открытого расчёта') }}
                </h2>
            </div>
            <div class="text-sm text-gray-800 dark:text-gray-400 animate__animated animate__fadeIn">
                {{ now()->translatedFormat('d F Y') }}
            </div>
        </div>
    </x-slot>

    <x-slot name="title">
        Просмотр открытого расчёта - ND³⁺
    </x-slot>

    <div class="py-6 px-4 sm:px-6">
        <div class="max-w-7xl mx-auto">

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
                        <span class="text-sm text-gray-700 dark:text-gray-400">{{ $shareLink->created_at->translatedFormat('d F Y') }}</span>
                    </div>
                </div>
            </div>

            <!-- Info Banner -->
            <div class="bg-blue-200 dark:bg-blue-900/30 rounded-lg p-4 mb-4 border border-blue-400 dark:border-blue-800">
                <div class="flex items-start gap-3">
                    <svg class="h-5 w-5 text-blue-500 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <div class="text-sm text-blue-800 dark:text-blue-200">
                        <span class="font-medium">Открытый расчёт</span> —
                        автор поделился им публично.
                        @if($allowCopy && auth()->guest())
                        <a href="{{ route('login') }}" class="underline hover:text-blue-600">Войдите</a> чтобы скопировать в свой аккаунт.
                        @elseif($allowCopy && auth()->check())
                        <button onclick="copyToAccount()" class="underline hover:text-blue-600 dark:hover:text-blue-600 cursor-pointer">Нажмите здесь</button> для копирования в ваш аккаунт.
                        @endif
                    </div>
                </div>
            </div>

            <div class="relative group mb-6">
                <!-- Кнопка копирования -->
                <div class="relative inline-block">
                    <button onclick="copyLink()" class="inline-flex items-center gap-1 px-3 py-1.5 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium rounded-lg transition-colors duration-150">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
                        </svg>
                        Скопировать ссылку
                    </button>
                    <div id="copy-toast" class="absolute top-full left-1/2 transform -translate-x-1/2 mt-2 px-3 py-1 bg-green-600 text-white text-xs rounded-lg opacity-0 pointer-events-none transition-opacity duration-200 whitespace-nowrap z-50">
                        Скопировано!
                    </div>
                </div>

                <!-- Информация об истечении -->
                <div class="text-sm text-gray-800 dark:text-gray-200 mt-2">
                    @if($shareLink->expires_at)
                    <span class="block text-xs flex items-center gap-1">
                        <svg class="h-3 w-3 text-yellow-600 dark:text-yellow-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        Срок действия: до {{ $shareLink->expires_at->format('d.m.Y H:i') }}
                    </span>
                    @else
                    <span class="block text-xs flex items-center gap-1 text-green-600 dark:text-green-400">
                        <svg class="h-3 w-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        Бессрочная ссылка
                    </span>
                    @endif
                </div>
            </div>

            <!-- Tabs Navigation -->
            <div class="border-b border-gray-400 dark:border-gray-700 mb-6">
                <nav class="flex space-x-4 overflow-x-auto" style="scrollbar-width: thin;">
                    <button class="tab-btn active px-4 py-2 text-sm font-medium text-indigo-600 dark:text-indigo-400 border-b-2 border-indigo-600" data-tab="input">
                        Входные параметры
                    </button>
                    @if($experimentalData && count($experimentalData) > 0)
                    <button class="tab-btn px-4 py-2 text-sm font-medium text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-300 border-b-2 border-transparent" data-tab="experimental">
                        Экспериментальные данные
                    </button>
                    @endif
                    <button class="tab-btn px-4 py-2 text-sm font-medium text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-300 border-b-2 border-transparent" data-tab="results">
                        Результаты
                    </button>
                    @if($optimizationResult && isset($optimizationResult['success']))
                    <button class="tab-btn px-4 py-2 text-sm font-medium text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-300 border-b-2 border-transparent" data-tab="optimization">
                        Оптимизация
                    </button>
                    @endif
                </nav>
            </div>

            <!-- Tab Content -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">

                    <!-- Input Parameters Tab -->
                    <div id="input-tab" class="tab-pane active">
                        <div class="border-b border-gray-400 dark:border-gray-700 pb-4 mb-4">
                            <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Входные параметры</h2>
                        </div>

                        <div class="space-y-6">
                            <!-- 4F3.2 Multiplet -->
                            <div class="bg-gray-300/70 dark:bg-gray-900 rounded-lg p-4">
                                <h3 class="text-md font-medium text-gray-900 dark:text-white mb-3 flex items-center gap-2">
                                    <svg class="h-5 w-5 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                                    </svg>
                                    4F₃/₂ Мультиплет
                                </h3>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div>
                                        <p class="text-sm text-gray-700 dark:text-gray-400 mb-1">Количество компонент:</p>
                                        <p class="text-lg font-semibold text-gray-900 dark:text-white">{{ $inputData['fn'] ?? '-' }}</p>
                                    </div>
                                <div>
                                    <p class="text-sm text-gray-700 dark:text-gray-400 mb-1">Энергии (cm⁻¹):</p>
                                    <div class="flex flex-wrap gap-2">
                                        @if(isset($inputData['fe']) && is_array($inputData['fe']))
                                        @foreach($inputData['fe'] as $index => $energy)
                                        @php
                                            $value = floatval($energy);
                                            $formatted = $value == floor($value) 
                                                ? number_format($value, 0, '.', '') 
                                                : rtrim(rtrim(number_format($value, 2, '.', ''), '0'), '.');
                                        @endphp
                                        <span class="inline-flex items-center px-2 py-1 border border-gray-400/70 dark:border-gray-700 rounded-md text-sm font-mono bg-gray-200 dark:bg-gray-700 text-gray-900 dark:text-gray-100">
                                            E{{ $index + 1 }}: {{ $formatted }}
                                        </span>
                                        @endforeach
                                        @else
                                        <span class="text-gray-400">-</span>
                                        @endif
                                    </div>
                                </div>
                                </div>
                            </div>

                            <!-- 4J9/2 Multiplet -->
                            <div class="bg-gray-300/70 dark:bg-gray-900 rounded-lg p-4">
                                <h3 class="text-md font-medium text-gray-900 dark:text-white mb-3 flex items-center gap-2">
                                    <svg class="h-5 w-5 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                                    </svg>
                                    4J₉/₂ Мультиплет
                                </h3>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div>
                                        <p class="text-sm text-gray-700 dark:text-gray-400 mb-1">Количество компонент:</p>
                                        <p class="text-lg font-semibold text-gray-900 dark:text-white">{{ $inputData['j9n'] ?? '-' }}</p>
                                    </div>
                                    <div>
                                        <p class="text-sm text-gray-700 dark:text-gray-400 mb-1">Энергии (cm⁻¹):</p>
                                    <div class="flex flex-wrap gap-2">
                                        @if(isset($inputData['j9e']) && is_array($inputData['j9e']))
                                        @foreach($inputData['j9e'] as $index => $energy)
                                        @php
                                            $value = floatval($energy);
                                            $formatted = $value == floor($value) 
                                                ? number_format($value, 0, '.', '') 
                                                : rtrim(rtrim(number_format($value, 2, '.', ''), '0'), '.');
                                        @endphp
                                        <span class="inline-flex items-center px-2 py-1 border border-gray-400/70 dark:border-gray-700 rounded-md text-sm font-mono bg-gray-200 dark:bg-gray-700 text-gray-900 dark:text-gray-100">
                                            E{{ $index + 1 }}: {{ $formatted }}
                                        </span>
                                        @endforeach
                                        @else
                                        <span class="text-gray-400">-</span>
                                        @endif
                                    </div>
                                    </div>
                                </div>
                                <div class="mt-3">
                                    <p class="text-sm text-gray-700 dark:text-gray-400 mb-1">Коэффициент J9C:</p>
                                    <p class="text-lg font-mono font-semibold text-gray-900 dark:text-white">
                                        {{ isset($inputData['j9c']) ? rtrim(rtrim(number_format(floatval($inputData['j9c']), 10, '.', ''), '0'), '.') : '-' }}
                                    </p>
                                </div>
                            </div>

                            <!-- 4J11/2 Multiplet -->
                            <div class="bg-gray-300/70 dark:bg-gray-900 rounded-lg p-4">
                                <h3 class="text-md font-medium text-gray-900 dark:text-white mb-3 flex items-center gap-2">
                                    <svg class="h-5 w-5 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                                    </svg>
                                    4J₁₁/₂ Мультиплет
                                </h3>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div>
                                        <p class="text-sm text-gray-700 dark:text-gray-400 mb-1">Количество компонент:</p>
                                        <p class="text-lg font-semibold text-gray-900 dark:text-white">{{ $inputData['j11n'] ?? '-' }}</p>
                                    </div>
                                    <div>
                                        <p class="text-sm text-gray-700 dark:text-gray-400 mb-1">Энергии (cm⁻¹):</p>
                                    <div class="flex flex-wrap gap-2">
                                        @if(isset($inputData['j11e']) && is_array($inputData['j11e']))
                                        @foreach($inputData['j11e'] as $index => $energy)
                                        @php
                                            $value = floatval($energy);
                                            $formatted = $value == floor($value) 
                                                ? number_format($value, 0, '.', '') 
                                                : rtrim(rtrim(number_format($value, 2, '.', ''), '0'), '.');
                                        @endphp
                                        <span class="inline-flex items-center px-2 py-1 border border-gray-400/70 dark:border-gray-700 rounded-md text-sm font-mono bg-gray-200 dark:bg-gray-700 text-gray-900 dark:text-gray-100">
                                            E{{ $index + 1 }}: {{ $formatted }}
                                        </span>
                                        @endforeach
                                        @else
                                        <span class="text-gray-400">-</span>
                                        @endif
                                    </div>
                                    </div>
                                </div>
                                <div class="mt-3">
                                    <p class="text-sm text-gray-700 dark:text-gray-400 mb-1">Коэффициент J11C:</p>
                                    <p class="text-lg font-mono font-semibold text-gray-900 dark:text-white">
                                        {{ isset($inputData['j11c']) ? rtrim(rtrim(number_format(floatval($inputData['j11c']), 10, '.', ''), '0'), '.') : '-' }}
                                    </p>
                                </div>
                            </div>

                            <!-- Kc Coefficients -->
                            <div class="bg-gray-300/70 dark:bg-gray-900 rounded-lg p-4">
                                <h3 class="text-md font-medium text-gray-900 dark:text-white mb-3 flex items-center gap-2">
                                    <svg class="h-5 w-5 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                                    </svg>
                                    Коэффициенты Kc
                                    @if($isOptimized)
                                    <span class="text-xs font-normal text-blue-600 dark:text-blue-400 ml-2">(оптимизировано)</span>
                                    @endif
                                </h3>

                                <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-3">
                                    @if(isset($inputData['kc']) && is_array($inputData['kc']))
                                    @foreach($inputData['kc'] as $index => $kc)
                                    @php
                                    $originalKc = $inputOriginal['kc'][$index] ?? null;
                                    $isOptimizedValue = $isOptimized && $originalKc !== null && floatval($originalKc) != floatval($kc);
                                    @endphp
                                    <div class="bg-white border border-gray-400/70 dark:border-gray-700 dark:bg-gray-800 rounded-lg p-3 text-center shadow-sm hover:shadow-md transition-shadow duration-200">
                                        <p class="text-xs text-gray-700 dark:text-gray-400 mb-1">Kc[{{ $index }}]</p>
                                        <p class="text-sm font-mono font-semibold {{ $index == 0 ? 'text-gray-800 dark:text-gray-400' : 'text-green-700 dark:text-green-400' }}">
                                            {{ rtrim(rtrim(number_format(floatval($kc), 10, '.', ''), '0'), '.') }}
                                        </p>
                                        @if($index == 0)
                                        <p class="text-xs text-gray-700 dark:text-gray-400 mt-1">(Зафиксировано на 1)</p>
                                        @else
                                        @if($isOptimizedValue)
                                        <p class="text-xs text-gray-700 dark:text-gray-400 mt-1 line-through">
                                            Оригинал: {{ rtrim(rtrim(number_format(floatval($originalKc), 10, '.', ''), '0'), '.') }}
                                        </p>
                                        <p class="text-xs text-green-700 dark:text-green-400 mt-0.5">
                                            ✓ оптимизировано
                                        </p>
                                        @elseif($isOptimized && $originalKc !== null)
                                        <p class="text-xs text-gray-700 dark:text-gray-400 mt-1">
                                            (без изменений)
                                        </p>
                                        @endif
                                        @endif
                                    </div>
                                    @endforeach
                                    @else
                                    <p class="text-gray-400 col-span-full">Данные отсутствуют</p>
                                    @endif
                                </div>
                            </div>

                            <!-- Fav Parameter -->
                            <div class="bg-gray-300/70 dark:bg-gray-900 rounded-lg p-4">
                                <h3 class="text-md font-medium text-gray-900 dark:text-white mb-3 flex items-center gap-2">
                                    <svg class="h-5 w-5 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 3.055A9.001 9.001 0 1020.945 13H11V3.055z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.488 9H15V3.512A9.025 9.025 0 0120.488 9z"></path>
                                    </svg>
                                    Параметр Fav
                                    @if($isOptimized)
                                    <span class="text-xs font-normal text-blue-600 dark:text-blue-400 ml-2">(оптимизировано)</span>
                                    @endif
                                </h3>

                                <div class="bg-white dark:bg-gray-800 rounded-lg p-4 border-l-4 border-orange-500">
                                    <p class="text-sm text-gray-700 dark:text-gray-400 mb-1">Значение Fav:</p>
                                    <p class="text-xl font-mono font-bold text-orange-600 dark:text-orange-400">
                                        {{ isset($inputData['fav']) ? rtrim(rtrim(number_format(floatval($inputData['fav']), 10, '.', ''), '0'), '.') : '-' }}
                                    </p>
                                    @if($isOptimized && isset($inputOriginal['fav']) && $inputOriginal['fav'] != $inputData['fav'])
                                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                                        Оригинал: {{ rtrim(rtrim(number_format(floatval($inputOriginal['fav']), 10, '.', ''), '0'), '.') }}
                                        <span class="text-green-700 dark:text-green-400 ml-1">→ оптимизировано</span>
                                    </p>
                                    @elseif($isOptimized)
                                    <p class="text-xs text-gray-700 dark:text-gray-400 mt-1">
                                        (без изменений)
                                    </p>
                                    @endif
                                </div>
                            </div>

                            @if($isOptimized)
                            <div class="bg-blue-200 dark:bg-blue-900/30 rounded-lg p-4 border border-blue-400 dark:border-blue-800">
                                <div class="flex items-center gap-2">
                                    <svg class="h-5 w-5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    <p class="text-sm text-blue-800 dark:text-blue-200">
                                        <span class="font-medium">Примечание:</span> В этом расчете используется <strong>оптимизированные параметры</strong>.
                                        Исходные значения показаны серым цветом там, где они отличаются.
                                    </p>
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>

                    <!-- Experimental Data Tab -->
                    @if($experimentalData && count($experimentalData) > 0)
                    <div id="experimental-tab" class="tab-pane hidden">
                        <div class="border-b border-gray-400 dark:border-gray-700 pb-4 mb-4">
                            <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Экспериментальные данные</h2>
                        </div>
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                                <thead class="bg-gray-300 dark:bg-gray-700">
                                    <tr>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-700 dark:text-gray-300 uppercase">Температура (K)</th>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-700 dark:text-gray-300 uppercase">Продолжительность жизни (µs)</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-300 dark:divide-gray-700">
                                    @foreach($experimentalData as $data)
                                    <tr class="hover:bg-gray-200 dark:hover:bg-gray-700/50">
                                        <td class="px-4 py-2 text-sm text-gray-900 dark:text-gray-100">{{ number_format(floatval($data['temperature']), 1) }}</td>
                                        <td class="px-4 py-2 text-sm text-gray-900 dark:text-gray-100">{{ number_format(floatval($data['value']), 6) }}</td>
                                    <tr>
                                        @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                    @endif

                    <!-- Results Tab -->
                    <div id="results-tab" class="tab-pane hidden">
                        <div class="border-b border-gray-400 dark:border-gray-700 pb-4 mb-4">
                            <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Результаты расчёта</h2>
                        </div>

                        @if(isset($results['temperatures']) && count($results['temperatures']) > 0)
                        <!-- Кнопка сохранения графика -->
                        <div class="flex justify-end mb-4 mt-6">
                            <button type="button" onclick="saveChartAsImage()"
                                class="inline-flex items-center px-3 py-1.5 bg-gray-400/40 hover:bg-gray-400/60 dark:bg-gray-500 dark:hover:bg-gray-600 text-black dark:text-white text-xs font-medium rounded-md transition-colors duration-150">
                                <svg class="h-4 w-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                                </svg>
                                Сохранить график
                            </button>
                        </div>

                        <!-- Комбинированный график -->
                        <div class="mb-6 p-4 bg-gray-200 dark:bg-gray-700/30 rounded-lg">
                            <canvas id="combinedChart" width="800" height="400" class="w-full h-auto"></canvas>
                        </div>

                        <!-- Кнопка экспорта таблицы -->
                        <div class="flex justify-end mb-4">
                            <button type="button" onclick="exportFullResultsTable()"
                                class="inline-flex items-center px-3 py-1.5 bg-green-600 hover:bg-green-700 text-white text-xs font-medium rounded-md transition-colors duration-150">
                                <svg class="h-4 w-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                                </svg>
                                Экспорт таблицы
                            </button>
                        </div>

                        <!-- Полная таблица результатов -->
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                                <thead class="bg-gray-300 dark:bg-gray-700">
                                    <tr>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-700 dark:text-gray-300 uppercase">T (K)</th>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-700 dark:text-gray-300 uppercase">Вычисленное τ (µs)</th>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-700 dark:text-gray-300 uppercase">Экспериментальное τ (µs)</th>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-700 dark:text-gray-300 uppercase">Разница (µs)</th>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-700 dark:text-gray-300 uppercase">Относительная ошибка (%)</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-300 dark:divide-gray-700">
                                    @foreach($results['temperatures'] as $index => $T)
                                    @php
                                    $calcValue = floatval($results['delta'][$index]);
                                    $expValue = null;
                                    foreach($experimentalData as $data) {
                                    if(abs(floatval($data['temperature']) - floatval($T)) < 0.1) {
                                        $expValue=floatval($data['value']);
                                        break;
                                        }
                                        }
                                        $diff=$expValue ? $calcValue - $expValue : null;
                                        $relError=$expValue ? ($diff / $expValue * 100) : null;
                                        @endphp
                                        <tr class="hover:bg-gray-200 dark:hover:bg-gray-700/50">
                                        <td class="px-4 py-2 text-sm text-gray-900 dark:text-gray-100">{{ number_format(floatval($T), 1) }}</td>
                                        <td class="px-4 py-2 text-sm text-gray-900 dark:text-gray-100">{{ number_format($calcValue, 6, '.', '') }}</td>
                                        <td class="px-4 py-2 text-sm text-gray-900 dark:text-gray-100">
                                            {{ $expValue ? number_format($expValue, 6, '.', '') : '-' }}
                                        </td>
                                        <td class="px-4 py-2 text-sm {{ $diff && $diff < 0 ? 'text-red-600 dark:text-red-400' : ($diff && $diff > 0 ? 'text-green-600 dark:text-green-400' : 'text-gray-900 dark:text-gray-100') }}">
                                            {{ $diff ? number_format($diff, 6, '.', '') : '-' }}
                                        </td>
                                        <td class="px-4 py-2 text-sm {{ $relError && abs($relError) > 10 ? 'text-red-600 dark:text-red-400' : ($relError && abs($relError) > 5 ? 'text-yellow-600 dark:text-yellow-400' : 'text-gray-900 dark:text-gray-100') }}">
                                            {{ $relError ? number_format($relError, 2, '.', '') . '%' : '-' }}
                                        </td>
                                        </tr>
                                        @endforeach
                                </tbody>
                            </table>
                        </div>

                        @if(empty($experimentalData) || count($experimentalData) == 0)
                        <div class="mt-4 p-3 bg-yellow-50 dark:bg-yellow-900/30 rounded-lg text-yellow-800 dark:text-yellow-200 text-sm">
                            <svg class="inline-block h-4 w-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            Нет экспериментальных данных для сравнения. Показаны только расчётные значения.
                        </div>
                        @endif
                        @else
                        <p class="text-gray-700 dark:text-gray-400 text-center py-8">Нет данных результатов</p>
                        @endif
                    </div>

                    <!-- Optimization Tab -->
                    <div id="optimization-tab" class="tab-pane hidden">
                        @if($optimizationResult && isset($optimizationResult['success']) && $optimizationResult['success'])
                        <div class="space-y-4">
                            <div class="bg-gradient-to-r from-green-200 to-emerald-100 dark:from-green-900/30 dark:to-emerald-900/30 rounded-lg p-6 border border-green-200 dark:border-green-800">
                                <h5 class="text-xl font-bold text-gray-900 dark:text-gray-100 mb-4 flex items-center">
                                    <svg class="h-6 w-6 text-green-600 dark:text-green-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    Сводка оптимизации
                                </h5>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div class="bg-white border border-gray-400/70 dark:border-gray-700 dark:bg-gray-800 rounded-lg p-4 shadow-sm">
                                        <p class="text-sm text-gray-700 dark:text-gray-400 mb-1">Сумма квадратов отклонений</p>
                                        <p class="text-2xl font-bold text-green-700 dark:text-green-300">{{ number_format(floatval($optimizationResult['ssd']), 6, '.', '') }}</p>
                                        <p class="text-xs text-gray-700 dark:text-gray-400 mt-1">Среднеквадратичная ошибка: {{ number_format(sqrt(floatval($optimizationResult['objective'])), 6, '.', '') }}</p>
                                    </div>
                                    <div class="bg-white border border-gray-400/70 dark:border-gray-700 dark:bg-gray-800 rounded-lg p-4 shadow-sm">
                                        <p class="text-sm text-gray-700 dark:text-gray-400 mb-1">Статус оптимизации</p>
                                        <p class="text-lg font-semibold text-green-600 dark:text-green-400">✓ Завершена успешно</p>
                                        <p class="text-xs text-gray-700 dark:text-gray-400 mt-1">Многозапусковая оптимизация с адаптивным спуском</p>
                                    </div>
                                </div>
                            </div>

                            <div class="bg-gray-200 dark:bg-gray-700 rounded-lg p-6">
                                <h5 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4 flex items-center">
                                    <svg class="h-5 w-5 text-blue-600 dark:text-blue-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    </svg>
                                    Оптимизированные параметры
                                </h5>

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                                    <div class="bg-white dark:bg-gray-800 rounded-lg p-4 border-l-4 border-blue-500">
                                        <p class="text-sm text-gray-700 dark:text-gray-400 mb-1">Коэффициент J9C</p>
                                        @php
                                        $j9cOpt = $optimizationResult['j9c'] ?? 0;
                                        $j9cOrig = $inputOriginal['j9c'] ?? $inputData['j9c'] ?? 0;
                                        @endphp
                                        <p class="text-xl font-mono font-bold text-blue-700 dark:text-blue-300">
                                            {{ rtrim(rtrim(sprintf('%.15F', (float)$j9cOpt), '0'), '.') }}
                                        </p>
                                        <p class="text-xs text-gray-700 dark:text-gray-400 mt-1">
                                            Оригинал: {{ rtrim(rtrim(sprintf('%.15F', (float)$j9cOrig), '0'), '.') }}
                                        </p>
                                    </div>
                                    <div class="bg-white dark:bg-gray-800 rounded-lg p-4 border-l-4 border-purple-500">
                                        <p class="text-sm text-gray-700 dark:text-gray-400 mb-1">Коэффициент J11C</p>
                                        @php
                                        $j11cOpt = $optimizationResult['j11c'] ?? 0;
                                        $j11cOrig = $inputOriginal['j11c'] ?? $inputData['j11c'] ?? 0;
                                        @endphp
                                        <p class="text-xl font-mono font-bold text-purple-700 dark:text-purple-300">
                                            {{ rtrim(rtrim(sprintf('%.15F', (float)$j11cOpt), '0'), '.') }}
                                        </p>
                                        <p class="text-xs text-gray-700 dark:text-gray-400 mt-1">
                                            Оригинал: {{ rtrim(rtrim(sprintf('%.15F', (float)$j11cOrig), '0'), '.') }}
                                        </p>
                                    </div>
                                </div>

                                <div class="mt-4">
                                    <p class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-3">Коэффициенты Kc:</p>
                                    <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-3">
                                        @foreach($optimizationResult['kc'] as $index => $kc)
                                        @php
                                        $kcOrig = $inputOriginal['kc'][$index] ?? $inputData['kc'][$index] ?? 0;
                                        @endphp
                                        <div class="bg-white border border-gray-400/70 dark:border-gray-700 dark:bg-gray-800 rounded-lg p-3 text-center shadow-sm hover:shadow-md transition-shadow duration-200">
                                            <p class="text-xs text-gray-700 dark:text-gray-300 mb-1">Kc[{{ $index }}]</p>
                                            <p class="text-sm font-mono font-semibold break-all {{ $index == 0 ? 'text-gray-800 dark:text-gray-300' : 'text-green-700 dark:text-green-300' }}">
                                                {{ rtrim(rtrim(sprintf('%.15F', (float)$kc), '0'), '.') }}
                                            </p>
                                            @if($index == 0)
                                            <p class="text-xs text-gray-700 dark:text-gray-300 mt-1">(фиксированный)</p>
                                            @else
                                            <p class="text-xs text-gray-700 dark:text-gray-400 mt-1 break-all">
                                                Оригинал: {{ rtrim(rtrim(sprintf('%.15F', (float)$kcOrig), '0'), '.') }}
                                            </p>
                                            @endif
                                        </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>

                            <div class="bg-gray-200 dark:bg-gray-700 rounded-lg p-6">
                                <h5 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-3 flex items-center">
                                    <svg class="h-5 w-5 text-yellow-600 dark:text-yellow-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"></path>
                                    </svg>
                                    Сравнение параметров
                                </h5>
                                <div class="flex justify-end mb-3 gap-2">
                                    <button type="button" onclick="exportOptimizationParamsTable()"
                                        class="inline-flex items-center px-3 py-1.5 bg-green-600 hover:bg-green-700 text-white text-xs font-medium rounded-md transition-colors duration-150">
                                        <svg class="h-4 w-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                                        </svg>
                                        Экспорт таблицы
                                    </button>
                                </div>
                                <div class="overflow-x-auto">
                                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-600">
                                        <thead class="bg-gray-500 dark:bg-gray-900/80">
                                            <tr>
                                                <th class="px-4 py-3 text-left text-xs font-medium text-white dark:text-gray-300 uppercase tracking-wider">Параметр</th>
                                                <th class="px-4 py-3 text-left text-xs font-medium text-white dark:text-gray-300 uppercase tracking-wider">Оригинальное значение</th>
                                                <th class="px-4 py-3 text-left text-xs font-medium text-white dark:text-gray-300 uppercase tracking-wider">Оптимизированное значение</th>
                                                <th class="px-4 py-3 text-left text-xs font-medium text-white dark:text-gray-300 uppercase tracking-wider">Отклонение (%)</th>
                                            </tr>
                                        </thead>
                                        <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                            @foreach($optimizationResult['kc'] as $index => $kc)
                                            @if($index > 0)
                                            @php
                                            $originalValue = floatval($inputOriginal['kc'][$index] ?? $inputData['kc'][$index] ?? 0);
                                            $optimizedValue = floatval($kc);
                                            $change = $originalValue != 0 ? ($optimizedValue - $originalValue) / abs($originalValue) * 100 : 0;
                                            $changeFormatted = ($change > 0 ? '+' : '') . number_format($change, 2, '.', '');
                                            @endphp
                                            <tr>
                                                <td class="px-4 py-3 text-sm font-medium text-gray-900 dark:text-gray-100">Kc[{{ $index }}]</td>
                                                <td class="px-4 py-3 text-sm font-mono text-gray-700 dark:text-gray-300 break-all">
                                                    {{ rtrim(rtrim(sprintf('%.15F', $originalValue), '0'), '.') }}
                                                </td>
                                                <td class="px-4 py-3 text-sm font-mono text-green-700 dark:text-green-300 break-all">
                                                    {{ rtrim(rtrim(sprintf('%.15F', $optimizedValue), '0'), '.') }}
                                                </td>
                                                <td class="px-4 py-3 text-sm {{ $change > 0 ? 'text-green-600' : ($change < 0 ? 'text-red-600' : 'text-gray-600') }}">
                                                    {{ $changeFormatted }}%
                                                </td>
                                            </tr>
                                            @endif
                                            @endforeach

                                            @php
                                            $originalJ9C = floatval($inputOriginal['j9c'] ?? $inputData['j9c'] ?? 0);
                                            $optimizedJ9C = floatval($optimizationResult['j9c'] ?? 0);
                                            $changeJ9C = $originalJ9C != 0 ? ($optimizedJ9C - $originalJ9C) / abs($originalJ9C) * 100 : 0;
                                            $changeJ9CFormatted = ($changeJ9C > 0 ? '+' : '') . number_format($changeJ9C, 2, '.', '');
                                            @endphp
                                            <tr>
                                                <td class="px-4 py-3 text-sm font-medium text-gray-900 dark:text-gray-100">J9C</td>
                                                <td class="px-4 py-3 text-sm font-mono text-gray-700 dark:text-gray-300 break-all">
                                                    {{ rtrim(rtrim(sprintf('%.15F', $originalJ9C), '0'), '.') }}
                                                </td>
                                                <td class="px-4 py-3 text-sm font-mono text-green-700 dark:text-green-300 break-all">
                                                    {{ rtrim(rtrim(sprintf('%.15F', $optimizedJ9C), '0'), '.') }}
                                                </td>
                                                <td class="px-4 py-3 text-sm {{ $changeJ9C > 0 ? 'text-green-600' : ($changeJ9C < 0 ? 'text-red-600' : 'text-gray-600') }}">
                                                    {{ $changeJ9CFormatted }}%
                                                </td>
                                            </tr>

                                            @php
                                            $originalJ11C = floatval($inputOriginal['j11c'] ?? $inputData['j11c'] ?? 0);
                                            $optimizedJ11C = floatval($optimizationResult['j11c'] ?? 0);
                                            $changeJ11C = $originalJ11C != 0 ? ($optimizedJ11C - $originalJ11C) / abs($originalJ11C) * 100 : 0;
                                            $changeJ11CFormatted = ($changeJ11C > 0 ? '+' : '') . number_format($changeJ11C, 2, '.', '');
                                            @endphp
                                            <tr>
                                                <td class="px-4 py-3 text-sm font-medium text-gray-900 dark:text-gray-100">J11C</td>
                                                <td class="px-4 py-3 text-sm font-mono text-gray-700 dark:text-gray-300 break-all">
                                                    {{ rtrim(rtrim(sprintf('%.15F', $originalJ11C), '0'), '.') }}
                                                </td>
                                                <td class="px-4 py-3 text-sm font-mono text-green-700 dark:text-green-300 break-all">
                                                    {{ rtrim(rtrim(sprintf('%.15F', $optimizedJ11C), '0'), '.') }}
                                                </td>
                                                <td class="px-4 py-3 text-sm {{ $changeJ11C > 0 ? 'text-green-600' : ($changeJ11C < 0 ? 'text-red-600' : 'text-gray-600') }}">
                                                    {{ $changeJ11CFormatted }}%
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        @else
                        <div class="bg-blue-50 dark:bg-blue-900 rounded-lg p-4">
                            <p class="text-sm text-blue-800 dark:text-blue-200">
                                Результаты оптимизации пока отсутствуют. Пожалуйста, добавьте экспериментальные данные и нажмите кнопку «Оптимизировать» на вкладке «Входные данные».
                            </p>
                        </div>
                        @endif
                    </div>

                    <!-- Footer -->
                    <div class="mt-8 pt-6 border-t border-gray-200 dark:border-gray-700 text-center text-xs text-gray-700 dark:text-gray-400">
                        <p>Nd³⁺ Lifetime Calculator — Открытый расчёт</p>
                        <p class="mt-1">Просмотров: {{ $shareLink->views }} | Создано: {{ $shareLink->created_at->translatedFormat('d F Y') }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        // Tab switching
        document.querySelectorAll('.tab-btn').forEach(btn => {
            btn.addEventListener('click', () => {
                document.querySelectorAll('.tab-btn').forEach(b => {
                    b.classList.remove('active', 'text-indigo-600', 'border-indigo-600', 'dark:text-indigo-400');
                    b.classList.add('text-gray-500', 'border-transparent', 'dark:text-gray-400');
                });
                btn.classList.add('active', 'text-indigo-600', 'border-indigo-600', 'dark:text-indigo-400');
                btn.classList.remove('text-gray-500', 'border-transparent', 'dark:text-gray-400');

                const tabId = btn.getAttribute('data-tab');
                document.querySelectorAll('.tab-pane').forEach(pane => {
                    pane.classList.add('hidden');
                });
                document.getElementById(`${tabId}-tab`).classList.remove('hidden');
            });
        });

        // Copy link function
        function copyLink() {
            navigator.clipboard.writeText(window.location.href);
            const toast = document.getElementById('copy-toast');
            toast.classList.remove('opacity-0');
            toast.classList.add('opacity-100');
            setTimeout(() => {
                toast.classList.remove('opacity-100');
                toast.classList.add('opacity-0');
            }, 2000);
        }

        // Copy to account function
        function copyToAccount() {
            fetch('{{ route("public.calculation.copy", ["token" => $shareLink->token]) }}', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Content-Type': 'application/json',
                        'Accept': 'application/json'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert('Заметка скопирована в ваш аккаунт!');
                        window.location.href = '/calculator/ND3+';
                    } else {
                        alert(data.error || 'Ошибка копирования');
                    }
                })
                .catch(error => {
                    console.error('Ошибка:', error);
                    alert('Ошибка сети. Попробуйте снова.');
                });
        }


        // Комбинированный график
        @if(isset($results['temperatures']) && count($results['temperatures']) > 0)
        let combinedChart = null;

        function initCombinedChart() {
            const combinedCtx = document.getElementById('combinedChart');
            if (!combinedCtx) return;


            const expData = @json($experimentalData);
            const calcTemps = @json($results['temperatures']);
            const calcValues = @json($results['delta']);
            
            // Проверка наличия данных
            if (!calcTemps || !calcValues || calcTemps.length === 0) {
                console.warn('No chart data available');
                return;
            }

            const calcDataPoints = calcTemps.map((temp, index) => ({
                x: temp,
                y: calcValues[index]
            }));
            
            const expDataPoints = (expData && expData.length > 0) 
                ? expData.map(d => ({ x: d.temperature, y: d.value }))
                : [];
                
            const isDark = document.documentElement.classList.contains('dark');
            const isMobile = window.innerWidth < 640;

            if (combinedChart) combinedChart.destroy();

            // Базовый набор данных (расчётные значения)
            const datasets = [{
                label: 'Calculated Lifetime (µs)',
                data: calcDataPoints,
                borderColor: 'rgb(75, 192, 192)',
                backgroundColor: 'rgba(75, 192, 192, 0.1)',
                type: 'line',
                tension: 0.1,
                fill: false,
                showLine: true,
                pointRadius: 0,
                pointHoverRadius: 5,
                borderWidth: 2
            }];

            // Добавляем экспериментальные данные только если они есть
            if (expDataPoints.length > 0) {
                datasets.push({
                    label: 'Experimental Data (µs)',
                    data: expDataPoints,
                    borderColor: 'rgb(255, 99, 132)',
                    backgroundColor: 'rgb(255, 99, 132)',
                    type: 'scatter',
                    pointRadius: 6,
                    pointHoverRadius: 8,
                    showLine: false,
                    pointBackgroundColor: 'rgb(255, 99, 132)',
                    pointBorderColor: '#fff',
                    pointBorderWidth: 2
                });
            }

            combinedChart = new Chart(combinedCtx, {
                type: 'scatter',
                data: {
                    datasets: datasets
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: true,
                    aspectRatio: isMobile ? 1.2 : 1.8, // Увеличенная высота на мобильных (было 1.6, стало 1.2 - чем меньше число, тем выше график)
                    plugins: {
                        legend: {
                            display: true,
                            position: 'top',
                            labels: {
                                usePointStyle: true,
                                color: isDark ? '#e5e7eb' : '#1f2937',
                                font: {
                                    size: 12
                                }
                            }
                        },
                        tooltip: {
                            backgroundColor: isDark ? 'rgba(0, 0, 0, 0.9)' : 'rgba(0, 0, 0, 0.8)',
                            titleColor: '#fff',
                            bodyColor: '#fff',
                            callbacks: {
                                label: (context) => {
                                    const dataset = context.dataset;
                                    const value = context.parsed;
                                    if (dataset.label === 'Calculated Lifetime (µs)') {
                                        return `Calculated: ${value.y.toFixed(3)} µs at ${value.x.toFixed(1)} K`;
                                    } else {
                                        return `Experimental: ${value.y.toFixed(3)} µs at ${value.x.toFixed(1)} K`;
                                    }
                                }
                            }
                        }
                    },
                    scales: {
                        x: {
                            title: {
                                display: true,
                                text: 'Temperature (K)',
                                color: isDark ? '#e5e7eb' : '#374151',
                                font: {
                                    weight: 'bold',
                                    size: 12
                                }
                            },
                            ticks: {
                                color: isDark ? '#e4e7ec' : '#2d343c',
                                callback: function(val) {
                                    return val + ' K';
                                }
                            },
                            grid: {
                                color: isDark ? 'rgba(255, 255, 255, 0.1)' : 'rgba(0, 0, 0, 0.1)',
                                drawBorder: true,
                                borderColor: isDark ? '#4b5563' : '#d1d5db'
                            }
                        },
                        y: {
                            title: {
                                display: true,
                                text: 'Lifetime (µs)',
                                color: isDark ? '#e5e7eb' : '#374151',
                                font: {
                                    weight: 'bold',
                                    size: 12
                                }
                            },
                            ticks: {
                                color: isDark ? '#e4e7ec' : '#2d343c',
                                callback: function(val) {
                                    return val.toFixed(1) + ' µs';
                                }
                            },
                            grid: {
                                color: isDark ? 'rgba(255, 255, 255, 0.1)' : 'rgba(0, 0, 0, 0.1)',
                                drawBorder: true,
                                borderColor: isDark ? '#4b5563' : '#3865a8'
                            },
                            beginAtZero: false
                        }
                    }
                }
            });
        }


        function saveChartAsImage() {
            if (!combinedChart) {
                console.error('Chart not found');
                return;
            }
            try {
                const originalCanvas = combinedChart.canvas;
                const isDarkMode = document.documentElement.classList.contains('dark');

                // Создаем временный canvas с теми же размерами
                const tempCanvas = document.createElement('canvas');
                tempCanvas.width = originalCanvas.width;
                tempCanvas.height = originalCanvas.height;
                const ctx = tempCanvas.getContext('2d');

                // Заливаем фон в зависимости от темы
                // Для темной темы используем темный фон, для светлой - белый
                ctx.fillStyle = isDarkMode ? '#1f2937' : '#e5e7eb';
                ctx.fillRect(0, 0, tempCanvas.width, tempCanvas.height);

                // Рисуем график поверх фона
                ctx.drawImage(originalCanvas, 0, 0);

                // Сохраняем изображение
                const link = document.createElement('a');
                link.download = isDarkMode ? 'combined_chart.png' : 'combined_chart.png';
                link.href = tempCanvas.toDataURL('image/png');
                link.click();

                showToast('График успешно сохранен!', 'success');
            } catch (error) {
                console.error('Ошибка сохранения графика:', error);
                showToast('Ошибка сохранения графика', 'error');
            }
        }

        function showToast(message, type = 'success') {
            const toast = document.createElement('div');
            toast.className = `fixed bottom-4 right-4 px-4 py-2 rounded-lg shadow-lg text-white z-50 ${type === 'success' ? 'bg-green-500' : 'bg-red-500'}`;
            toast.textContent = message;
            document.body.appendChild(toast);
            setTimeout(() => toast.remove(), 3000);
        }

        function exportFullResultsTable() {
            const rows = [];
            const headers = ['T (K)', 'Calculated τ (µs)', 'Experimental τ (µs)', 'Difference (µs)', 'Relative Error (%)'];

            document.querySelectorAll('#results-tab table tbody tr').forEach(row => {
                const cells = row.querySelectorAll('td');
                if (cells.length >= 5) {
                    rows.push([
                        cells[0].innerText.trim(),
                        cells[1].innerText.trim(),
                        cells[2].innerText.trim(),
                        cells[3].innerText.trim(),
                        cells[4].innerText.trim()
                    ]);
                }
            });

            if (rows.length === 0) {
                showToast('Нет результатов для экспорта', 'error');
                return;
            }

            exportToExcel(headers, rows, 'full_results.csv');
        }

        function exportToExcel(headers, data, filename) {
            try {
                const csvRows = [];
                csvRows.push(headers.join(';'));
                data.forEach(row => {
                    const processedRow = row.map(cell => {
                        let text = String(cell).trim();
                        text = text.replace(/"/g, '""');
                        return `="${text}"`;
                    });
                    csvRows.push(processedRow.join(';'));
                });

                const csvString = csvRows.join('\n');
                const blob = new Blob(['\uFEFF' + csvString], {
                    type: 'text/csv;charset=utf-8;'
                });
                const link = document.createElement('a');
                const url = URL.createObjectURL(blob);
                link.href = url;
                link.setAttribute('download', filename || 'export.csv');
                document.body.appendChild(link);
                link.click();
                document.body.removeChild(link);
                URL.revokeObjectURL(url);
                showToast('Данные успешно экспортированы', 'success');
            } catch (error) {
                console.error('Ошибка при экспорте данных:', error);
                showToast('Ошибка при экспорте данных', 'error');
            }
        }

        function exportOptimizationParamsTable() {
            const tableElement = document.querySelector('#optimization-tab .overflow-x-auto table');
            if (!tableElement) {
                showToast('Таблица параметров оптимизации не найдена', 'error');
                return;
            }

            try {
                const rows = tableElement.querySelectorAll('tr');
                const csvData = [];
                rows.forEach(row => {
                    const rowData = [];
                    const cells = row.querySelectorAll('th, td');
                    cells.forEach(cell => {
                        let text = cell.innerText.trim();
                        text = `="${text.replace(/"/g, '\\"')}"`;
                        rowData.push(text);
                    });
                    if (rowData.length > 0) csvData.push(rowData.join(';'));
                });

                let csvString = csvData.join('\n');
                const blob = new Blob(['\uFEFF' + csvString], {
                    type: 'text/csv;charset=utf-8;'
                });
                const link = document.createElement('a');
                const url = URL.createObjectURL(blob);
                link.href = url;
                link.setAttribute('download', 'optimization_parameters.csv');
                document.body.appendChild(link);
                link.click();
                document.body.removeChild(link);
                URL.revokeObjectURL(url);
                showToast('Данные успешно экспортированы', 'success');
            } catch (error) {
                console.error('Ошибка при экспорте данных:', error);
                showToast('Ошибка при экспорте данных', 'error');
            }
        }

        document.addEventListener('DOMContentLoaded', function() {
            initCombinedChart();
            const observer = new MutationObserver(function(mutations) {
                mutations.forEach(function(mutation) {
                    if (mutation.attributeName === 'class') initCombinedChart();
                });
            });
            observer.observe(document.documentElement, {
                attributes: true,
                attributeFilter: ['class']
            });

            let resizeTimer;
            window.addEventListener('resize', function() {
                clearTimeout(resizeTimer);
                resizeTimer = setTimeout(() => initCombinedChart(), 250);
            });
        });
        @endif
    </script>
</x-app-layout>
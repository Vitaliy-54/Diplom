<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Ho³⁺ Lifetime Calculator') }}
            </h2>
            <div class="text-sm text-gray-800 dark:text-gray-400 animate__animated animate__fadeIn">
                {{ now()->translatedFormat('d F Y') }}
            </div>
        </div>
    </x-slot>

    <x-slot name="title">
        {{ __('Ho³⁺ Lifetime Calculator') }}
    </x-slot>

<style>
    /* Глобальное решение для всех браузеров */
    html {
        overflow-y: scroll;
        scrollbar-gutter: stable;
    }

    /* Анимация для уведомлений */
#success-notification,
#error-notification,
#info-notification,
#validation-errors {
    transition: all 0.3s ease;
    animation: slideIn 0.3s ease;
}

@keyframes slideIn {
    from {
        opacity: 0;
        transform: translateX(-20px);
    }
    to {
        opacity: 1;
        transform: translateX(0);
    }
}

/* Адаптивные стили для графиков */
@media (max-width: 640px) {
    canvas {
        max-height: 300px;
        width: 100%;
        height: auto;
    }
    
    /* Улучшаем читаемость графика на маленьких экранах */
    #combinedChart,
    #resultsChart {
        touch-action: pan-x pan-y;
        -webkit-tap-highlight-color: transparent;
    }
}

/* Для планшетов */
@media (min-width: 640px) and (max-width: 1024px) {
    canvas {
        max-height: 400px;
    }
}

/* Для десктопов */
@media (min-width: 1024px) {
    canvas {
        max-height: 500px;
    }
}

/* Плавная анимация при изменении размера */
canvas {
    transition: all 0.3s ease;
}

/* Стили для мобильной навигации */
@media (max-width: 640px) {
    .overflow-x-auto {
        -webkit-overflow-scrolling: touch;
        scrollbar-width: thin;
    }
    
    /* Убираем скроллбар на мобильных, но оставляем возможность скролла */
    .overflow-x-auto::-webkit-scrollbar {
        height: 2px;
    }
    
    /* Увеличиваем отступы для удобства касания */
    .tab-button {
        padding-left: 0.75rem;
        padding-right: 0.75rem;
        padding-top: 0.5rem;
        padding-bottom: 0.5rem;
        font-size: 0.875rem;
    }
}

/* Индикатор прокрутки для мобильных устройств */
.scroll-indicator {
    position: relative;
}

.scroll-indicator::after {
    content: '';
    position: absolute;
    right: 0;
    top: 0;
    bottom: 0;
    width: 30px;
    background: linear-gradient(to right, transparent, white);
    pointer-events: none;
}

.dark .scroll-indicator::after {
    background: linear-gradient(to right, transparent, #1f2937);
}

/* Стили для полей с ошибками - только красная рамка */
/* Более тонкая красная рамка */
input.error-border {
    border-color: #ef4444 !important;
    border-width: 1px !important;
    box-shadow: 0 0 0 1px rgba(239, 68, 68, 0.1) !important;
}

.dark input.error-border {
    border-color: #ef4444 !important;
    border-width: 1px !important;
    box-shadow: 0 0 0 1px rgba(239, 68, 68, 0.2) !important;
}

/* Анимация для подсветки ошибок */
@keyframes errorShake {
    0%, 100% { transform: translateX(0); }
    25% { transform: translateX(-3px); }
    75% { transform: translateX(3px); }
}

input.error-border {
    animation: errorShake 0.3s ease-in-out;
}

/* Убираем анимацию после её завершения */
input.error-border.animate-complete {
    animation: none;
}

/* Стили для кнопки закрытия */
.notification-close {
    cursor: pointer;
    transition: all 0.2s ease;
}

.notification-close:hover {
    transform: scale(1.1);
}
    
    /* Для контейнера истории */
    #history-tab .overflow-y-auto,
    #results-tab .overflow-y-auto,
    #optimization-tab .overflow-y-auto,
    #input-tab .overflow-y-auto {
        scrollbar-gutter: stable;
        scrollbar-width: thin;
    }
    
    /* Стилизация скролла для WebKit браузеров */
    .overflow-y-auto::-webkit-scrollbar {
        width: 8px;
    }
    
    .overflow-y-auto::-webkit-scrollbar-track {
        background: #f1f1f1;
        border-radius: 4px;
    }
    
    .overflow-y-auto::-webkit-scrollbar-thumb {
        background: #cbd5e0;
        border-radius: 4px;
    }
    
    .overflow-y-auto::-webkit-scrollbar-thumb:hover {
        background: #94a3b8;
    }
    
    /* Темная тема */
    .dark .overflow-y-auto::-webkit-scrollbar-track {
        background: #2d3748;
    }
    
    .dark .overflow-y-auto::-webkit-scrollbar-thumb {
        background: #4a5568;
    }
    
    .dark .overflow-y-auto::-webkit-scrollbar-thumb:hover {
        background: #718096;
    }
</style>

<div class="py-6">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900 dark:text-gray-100">
                <!-- CSRF Token -->
                <meta name="csrf-token" content="{{ csrf_token() }}">

            <!-- Навигация -->
<div class="mb-6 border-b border-gray-200 dark:border-gray-700">
    <!-- Десктопная версия (скрывается на мобильных) -->
    <nav class="hidden sm:flex space-x-8" aria-label="Tabs">
        <button class="tab-button active border-b-2 border-indigo-500 text-indigo-600 dark:text-indigo-400 px-1 py-2 text-sm font-medium" 
                data-tab="input">
            Input Data
        </button>
        <button class="tab-button border-b-2 border-transparent text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-300 px-1 py-2 text-sm font-medium" 
                data-tab="experimental">
            Experimental Data
        </button>
        <button class="tab-button border-b-2 border-transparent text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-300 px-1 py-2 text-sm font-medium" 
                data-tab="results">
            Results
        </button>
        <button class="tab-button border-b-2 border-transparent text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-300 px-1 py-2 text-sm font-medium" 
                data-tab="optimization">
            Optimization Results
        </button>
        <button class="tab-button border-b-2 border-transparent text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-300 px-1 py-2 text-sm font-medium" 
                data-tab="history">
            History
        </button>
    </nav>
    
    <!-- Мобильная версия (прокручиваемая) -->
    <nav class="sm:hidden flex space-x-4 overflow-x-auto pb-2" aria-label="Tabs" style="scrollbar-width: thin; -webkit-overflow-scrolling: touch;">
        <button class="tab-button active border-b-2 border-indigo-500 text-indigo-600 dark:text-indigo-400 px-3 py-2 text-sm font-medium whitespace-nowrap" 
                data-tab="input">
            Input Data
        </button>
        <button class="tab-button border-b-2 border-transparent text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-300 px-3 py-2 text-sm font-medium whitespace-nowrap" 
                data-tab="experimental">
            Experimental Data
        </button>
        <button class="tab-button border-b-2 border-transparent text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-300 px-3 py-2 text-sm font-medium whitespace-nowrap" 
                data-tab="results">
            Results
        </button>
        <button class="tab-button border-b-2 border-transparent text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-300 px-3 py-2 text-sm font-medium whitespace-nowrap" 
                data-tab="optimization">
            Optimization Results
        </button>
        <button class="tab-button border-b-2 border-transparent text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-300 px-3 py-2 text-sm font-medium whitespace-nowrap" 
                data-tab="history">
            History
        </button>
    </nav>
</div>

<!-- Информационное сообщение (для загрузки из истории) -->
@if(session('info'))
    <div class="mb-4 rounded-md bg-blue-200 dark:bg-blue-900 p-4 relative" id="info-notification" data-auto-close="false">
        <div class="flex">
            <div class="flex-shrink-0">
                <svg class="h-5 w-5 text-blue-400" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
                </svg>
            </div>
            <div class="ml-3">
                <p class="text-sm font-medium text-blue-800 dark:text-blue-200">
                    {!! session('info') !!}
                </p>
            </div>
            <button onclick="closeNotification('info-notification')" class="absolute top-2 right-2 text-blue-600 dark:text-blue-400 hover:text-blue-800 dark:hover:text-blue-200 transition-colors duration-150">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>
    </div>
@endif

<!-- Краткое сообщение об ошибках валидации -->
@if($errors->any())
    <div id="validation-errors-data" style="display: none;">
        <div class="errors-list">
            @foreach($errors->all() as $error)
                <div class="error-item">{{ $error }}</div>
            @endforeach
        </div>
    </div>
@endif

                <!-- Контент вкладок -->
                <div class="tab-content">
                    <!-- ОДНА ГЛАВНАЯ ФОРМА -->
                    <form id="calculatorForm" method="POST" action="{{ route('calculator.Ho3+.calculate') }}">
                        @csrf
                        
                        <!-- Вкладка ввода данных -->
                        <div class="tab-pane active" id="input-tab">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <!-- Левая колонка - ввод данных -->
                                <div>
                                    <!-- Поле для названия вычисления -->
                                    <div class="bg-gray-200 dark:bg-gray-700 rounded-lg p-4 mb-4">
                                        <div class="flex items-center space-x-4">
                                            <label class="text-sm font-medium text-gray-700 dark:text-gray-300">Save with name (optional):</label>
                                            <input type="text" name="calculation_name" class="block w-64 rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 bg-gray-100 dark:bg-gray-800 dark:border-gray-600 dark:text-white" 
                                                   value="{{ old('calculation_name', $input['calculation_name'] ?? '') }}" 
                                                   placeholder="e.g., My calculation"
                                                   {{ $viewingHistory ? 'disabled' : '' }}>
                                        </div>
                                    </div>
                                    
                                    <!-- I7 Multiplet (верхний уровень) -->
                                    <div class="bg-gray-200 dark:bg-gray-700 rounded-lg p-4 mb-4">
                                        <h5 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-3">5I7 Multiplet</h5>
                                        <div class="mb-3">
                                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Number of components:</label>
                                            <select name="i7n" class="block w-48 rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-800 dark:border-gray-600 dark:text-white" id="i7nSelect" onchange="updateI7NGrid()" {{ $viewingHistory ? 'disabled' : '' }}>
                                                @for($i = 1; $i <= 12; $i++)
                                                    @php
                                                        $selected = false;
                                                        if (old('i7n') !== null) {
                                                            $selected = old('i7n') == $i;
                                                        } elseif (isset($input['i7n'])) {
                                                            $selected = $input['i7n'] == $i;
                                                        } else {
                                                            $selected = ($i == 6);
                                                        }
                                                    @endphp
                                                    <option value="{{ $i }}" {{ $selected ? 'selected' : '' }}>{{ $i }}</option>
                                                @endfor
                                            </select>
                                        </div>
                                        
                                        <div class="mb-3">
                                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Energies (cm⁻¹):</label>
                                            <div class="grid grid-cols-3 md:grid-cols-4 gap-2" id="i7eGrid">
                                                @php
                                                    $i7n = old('i7n', $input['i7n'] ?? 6);
                                                    $i7e = $input['i7e'] ?? [];
                                                @endphp
                                                @for($i = 0; $i < $i7n; $i++)
                                                    <div>
                                                        <input type="text" name="i7e[]" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-800 dark:border-gray-600 dark:text-white
                                                        {{ $errors->has('i7e.' . $i) ? 'border-red-500 dark:border-red-500' : 'border-gray-300' }}"
                                                        value="{{ old('i7e.' . $i, $i7e[$i] ?? '') }}"
                                                        placeholder="E{{ $i+1 }}"
                                                        {{ $viewingHistory ? 'disabled' : '' }}>
                                                        @if($errors->has('i7e.' . $i))
                                                            <p class="text-xs text-red-600 dark:text-red-400 mt-1 flex items-start">
                                                                <svg class="h-3 w-3 mr-1 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                                </svg>
                                                                <span class="break-words">Energy value required</span>
                                                            </p>
                                                        @endif
                                                    </div>
                                                @endfor
                                            </div>
                                        </div>
                                        
                                        <!-- Для Kc значений -->
                                        <div class="mb-3">
                                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Coefficients Kc:</label>
                                            <div class="grid grid-cols-3 md:grid-cols-4 gap-2" id="kcGrid">
                                                @php
                                                    $kc = $input['kc'] ?? [];
                                                    $kc_original = $inputOriginal['kc'] ?? [];
                                                @endphp
                                                @for($i = 0; $i < $i7n; $i++)
                                                    <div>
                                                        <input type="text" name="kc[]" 
                                                            class="block w-full rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-800 dark:border-gray-600 dark:text-white
                                                            {{ $errors->has('kc.' . $i) ? 'border-red-500 dark:border-red-500 ring-1 ring-red-500' : 'border-gray-300' }}
                                                            {{ $i == 0 ? 'bg-gray-100 dark:bg-gray-600 text-gray-700 dark:text-gray-200 cursor-not-allowed' : '' }}"
                                                            value="{{ old('kc.' . $i, $kc[$i] ?? ($i == 0 ? '1' : '')) }}"
                                                            placeholder="Kc{{ $i+1 }}"
                                                            {{ $i == 0 ? 'readonly' : '' }}
                                                            {{ $viewingHistory ? 'disabled' : '' }}>
                                                        @if($errors->has('kc.' . $i))
                                                            <p class="text-xs text-red-600 dark:text-red-400 mt-1 flex items-start">
                                                                <svg class="h-3 w-3 mr-1 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                                </svg>
                                                                <span class="break-words">Kc coefficient required</span>
                                                            </p>
                                                        @endif
                                                        @if($i == 0)
                                                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Fixed to 1</p>
                                                        @endif
                                                    </div>
                                                @endfor
                                            </div>
                                        </div>
                                    </div>

                                    <!-- I8 Multiplet (нижний уровень) -->
                                    <div class="bg-gray-200 dark:bg-gray-700 rounded-lg p-4 mb-4">
                                        <h5 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-3">5I8 Multiplet</h5>
                                        <div class="mb-3">
                                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Number of components:</label>
                                            <select name="i8n" class="block w-48 rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-800 dark:border-gray-600 dark:text-white" 
                                                    id="i8nSelect" 
                                                    onchange="updateI8NGrid()" 
                                                    {{ $viewingHistory ? 'disabled' : '' }}>
                                                @for($i = 1; $i <= 12; $i++)
                                                    @php
                                                        $selected = false;
                                                        if (old('i8n') !== null) {
                                                            $selected = old('i8n') == $i;
                                                        } elseif (isset($input['i8n'])) {
                                                            $selected = $input['i8n'] == $i;
                                                        } else {
                                                            $selected = ($i == 7);
                                                        }
                                                    @endphp
                                                    <option value="{{ $i }}" {{ $selected ? 'selected' : '' }}>{{ $i }}</option>
                                                @endfor
                                            </select>
                                        </div>
                                        
                                        <div class="mb-3">
                                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Energies (cm⁻¹):</label>
                                            <div class="grid grid-cols-3 md:grid-cols-4 gap-2" id="i8eGrid">
                                                @php
                                                    $i8n = old('i8n', $input['i8n'] ?? 7);
                                                    $i8e = $input['i8e'] ?? [];
                                                @endphp
                                                @for($i = 0; $i < $i8n; $i++)
                                                    <div>
                                                        <input type="text" name="i8e[]" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-800 dark:border-gray-600 dark:text-white
                                                        {{ $errors->has('i8e.' . $i) ? 'border-red-500 dark:border-red-500' : 'border-gray-300' }}" 
                                                        value="{{ old('i8e.' . $i, $i8e[$i] ?? '') }}" 
                                                        placeholder="E{{ $i+1 }}"
                                                        {{ $viewingHistory ? 'disabled' : '' }}>
                                                        @if($errors->has('i8e.' . $i))
                                                            <p class="text-xs text-red-600 dark:text-red-400 mt-1 flex items-start">
                                                                <svg class="h-3 w-3 mr-1 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                                </svg>
                                                                <span class="break-words">Energy value required</span>
                                                            </p>
                                                        @endif
                                                    </div>
                                                @endfor
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Fav parameter -->
                                    <div class="bg-gray-200 dark:bg-gray-700 rounded-lg p-4 mb-4">
                                        <div class="flex items-center space-x-4">
                                            <label class="text-sm font-medium text-gray-700 dark:text-gray-300">Fav =</label>
                                            <input type="text" name="fav" 
                                                class="block w-48 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-800 dark:border-gray-600 dark:text-white
                                                {{ $errors->has('fav') ? 'border-red-500 dark:border-red-500 ring-1 ring-red-500' : 'border-gray-300' }}" 
                                                value="{{ old('fav', $input['fav'] ?? '') }}"
                                                placeholder="Enter Fav value"
                                                {{ $viewingHistory ? 'disabled' : '' }}>
                                        </div>
                                        @if($errors->has('fav'))
                                            <p class="text-xs text-red-600 dark:text-red-400 mt-1 flex items-center">
                                                <svg class="h-3 w-3 mr-1 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                </svg>
                                                @if(old('fav') === null || old('fav') === '')
                                                    Fav parameter is required
                                                @else
                                                    Fav must be a number
                                                @endif
                                            </p>
                                        @endif
                                    </div>

                                    <!-- Кнопки управления -->
                                    <div class="flex flex-wrap gap-2">
                                        @if(!$viewingHistory)
                                            <button type="submit" class="inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-500 active:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
                                                Calculate
                                            </button>
                                            <button type="button" onclick="submitOptimization()" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-500 active:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
                                                Optimize
                                            </button>
                                        @endif
                                        
                                        @if($viewingHistory)
                                            <button type="button" onclick="exitHistoryView()" class="inline-flex items-center px-4 py-2 bg-purple-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-purple-500 active:bg-purple-700 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
                                                Edit Mode
                                            </button>
                                        @else
                                            <button type="button" onclick="clearForm()" class="inline-flex items-center px-4 py-2 bg-yellow-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-yellow-500 active:bg-yellow-700 focus:outline-none focus:ring-2 focus:ring-yellow-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
                                                Clear Input Data
                                            </button>
                                        @endif
                                    </div>
                                </div>

                            <!-- Правая колонка - результаты расчетов -->
                            <div>
                                @if($results)
                                <div class="bg-gray-200 dark:bg-gray-700 rounded-lg p-4">
                                    <div class="flex justify-between items-center">
                                        <h5 class="text-lg font-medium text-gray-900 dark:text-gray-100">Calculation Results</h5>
                                    </div>

                            <div class="flex justify-end gap-2">
                                <button type="button" onclick="saveChartAsImage(resultsChart, 'lifetime_chart.png')" 
                                        class="inline-flex items-center px-3 py-1.5 bg-gray-400/40 hover:bg-gray-400/60 dark:bg-gray-500 dark:hover:bg-gray-600 text-black dark:text-white text-xs font-medium rounded-md transition-colors duration-150">
                                    <svg class="h-4 w-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                                    </svg>
                                    Save Chart
                                </button>
                            </div>
                            
                            <!-- График -->
                            <div class="relative">
                                <canvas id="resultsChart" width="400" height="300" class="w-full h-auto"></canvas>
                            </div>

                            <div class="flex gap-2 mt-6 mb-3">
                                <div class="ml-auto">
                                    <button type="button" onclick="exportTableFromResultsTab()" 
                                            class="inline-flex items-center px-3 py-1.5 bg-green-600 hover:bg-green-700 text-white text-xs font-medium rounded-md transition-colors duration-150">
                                        <svg class="h-4 w-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                                        </svg>
                                        Export Table to CSV
                                    </button>
                                </div>
                            </div>
                                        
                                        <!-- Таблица результатов -->
                                        <div class="mt-3 max-h-96 overflow-y-auto">
                                            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-600">
                                                <thead class="bg-gray-100 dark:bg-gray-800 sticky top-0">
                                                    <tr>
                                                        <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">T, K</th>
                                                        <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">W, s⁻¹</th>
                                                        <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Lifetime, µs</th>
                                                    </tr>
                                                </thead>
                                                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                                    @foreach($results['temperatures'] as $index => $T)
                                                    <tr>
                                                        <td class="px-3 py-2 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">{{ number_format(floatval($T), 1, '.', '') }}</td>
                                                        <td class="px-3 py-2 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">{{ number_format(floatval($results['w'][$index]), 6, '.', '') }}</td>
                                                        <td class="px-3 py-2 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">{{ number_format(floatval($results['delta'][$index]), 6, '.', '') }}</td>
                                                    </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <!-- Вкладка экспериментальных данных -->
                        <div class="tab-pane hidden" id="experimental-tab">
                            <div class="bg-gray-200 dark:bg-gray-700 rounded-lg p-4">
                                <div class="flex justify-between items-center mb-3">
                                    <h5 class="text-lg font-medium text-gray-900 dark:text-gray-100">Experimental Data (T, K → τ, µs)</h5>
                                </div>
                                
                                <p class="text-sm text-gray-600 dark:text-gray-400 mb-2">Experimental data will be used for optimization and graphing.</p>
                                
                                <div class="flex flex-wrap gap-2 mb-4 justify-between items-center">
                            <div class="flex gap-2">
                                @if(!$viewingHistory)
                                    <button type="button" onclick="addExperimentalRow()" class="inline-flex items-center px-4 py-2 bg-cyan-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-cyan-500 active:bg-cyan-700 focus:outline-none focus:ring-2 focus:ring-cyan-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
                                        Add Row
                                    </button>
                                @endif
                            </div>
                            <div>
                                @if($results)
                                <button type="button" onclick="exportExperimentalData()" 
                                        class="inline-flex items-center px-3 py-1.5 bg-green-600 hover:bg-green-700 text-white text-xs font-medium rounded-md transition-colors duration-150">
                                    <svg class="h-4 w-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                                    </svg>
                                    Export Table to CSV
                                </button>
                                @endif
                            </div>
                        </div>

                                <div class="overflow-x-auto">
                                    <table class="min-w-full divide-y divide-gray-400 dark:divide-gray-600">
                                        <thead class="bg-gray-400 dark:bg-gray-800">
                                            <tr>
                                                <th class="px-3 py-2 text-left text-xs font-medium text-white dark:text-gray-400 uppercase tracking-wider">Temperature (K)</th>
                                                <th class="px-3 py-2 text-left text-xs font-medium text-white dark:text-gray-400 uppercase tracking-wider">Lifetime (µs)</th>
                                                @if(!$viewingHistory)
                                                    <th class="px-3 py-2 text-left text-xs font-medium text-white dark:text-gray-400 uppercase tracking-wider">Action</th>
                                                @endif
                                            </tr>
                                        </thead>
                                        <tbody id="experimentalBody" class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                            @php
                                                $expTemps = old('exp_temperatures', $experimentalInput['exp_temperatures'] ?? []);
                                                $expVals = old('exp_values', $experimentalInput['exp_values'] ?? []);
                                                $rowCount = !empty($expTemps) ? count($expTemps) : 1;
                                            @endphp
                                            
                                            @for($i = 0; $i < $rowCount; $i++)
                                            <tr class="experimental-row">
                                                <td class="px-3 py-2">
                                                    <input type="text" name="exp_temperatures[]" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-800 dark:border-gray-600 dark:text-white" 
                                                        value="{{ $expTemps[$i] ?? '' }}" 
                                                        placeholder="Input Temperature"
                                                        {{ $viewingHistory ? 'disabled' : '' }}>
                                                  </td>
                                                <td class="px-3 py-2">
                                                    <input type="text" name="exp_values[]" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-800 dark:border-gray-600 dark:text-white" 
                                                        value="{{ $expVals[$i] ?? '' }}" 
                                                        placeholder="Input Lifetime"
                                                        {{ $viewingHistory ? 'disabled' : '' }}>
                                                  </td>
                                                @if(!$viewingHistory)
                                                    <td class="px-3 py-2">
                                                        <button type="button" onclick="removeExperimentalRow(this)" class="inline-flex items-center px-3 py-1 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-500 active:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
                                                            Remove
                                                        </button>
                                                      </td>
                                                @endif
                                            </tr>
                                            @endfor
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </form>

                    <!-- Вкладка результатов -->
                   <div class="tab-pane hidden" id="results-tab">
                        @if($results)
                        <div class="bg-gray-200 dark:bg-gray-700 rounded-lg p-4">
                            <h5 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-3">Detailed Results</h5>
                            
                            <div class="flex justify-end gap-2">
                                <button type="button" onclick="saveChartAsImage(combinedChart, 'detailed_results_chart.png', 2)" 
                                        class="inline-flex items-center px-3 py-1.5 bg-gray-400/40 hover:bg-gray-400/60 dark:bg-gray-500 dark:hover:bg-gray-600 text-black dark:text-white text-xs font-medium rounded-md transition-colors duration-150">
                                    <svg class="h-4 w-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                                    </svg>
                                    Save Chart
                                </button>
                </div>


                            <canvas id="combinedChart" width="800" height="400" class="w-full h-auto"></canvas>
                            
                                        <div class="flex justify-end mt-6 gap-2">
                <button type="button" onclick="exportFullResultsTable()" 
                        class="inline-flex items-center px-3 py-1.5 bg-green-600 hover:bg-green-700 text-white text-xs font-medium rounded-md transition-colors duration-150">
                    <svg class="h-4 w-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                    </svg>
                    Export Table to CSV
                </button>
            </div>
                            
                            <div class="mt-3 max-h-96 overflow-y-auto">
                                <table class="min-w-full divide-y divide-gray-400 dark:divide-gray-600">
                                    <thead class="bg-gray-400 dark:bg-gray-800 sticky top-0">
                                        <tr>
                                            <th class="px-3 py-2 text-left text-xs font-medium text-white dark:text-gray-400 uppercase tracking-wider">T, K</th>
                                            <th class="px-3 py-2 text-left text-xs font-medium text-white dark:text-gray-400 uppercase tracking-wider">Calculated τ, µs</th>
                                            <th class="px-3 py-2 text-left text-xs font-medium text-white dark:text-gray-400 uppercase tracking-wider">Experimental τ, µs</th>
                                            <th class="px-3 py-2 text-left text-xs font-medium text-white dark:text-gray-400 uppercase tracking-wider">Difference, µs</th>
                                            <th class="px-3 py-2 text-left text-xs font-medium text-white dark:text-gray-400 uppercase tracking-wider">Relative Error, %</th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                        @foreach($results['temperatures'] as $index => $T)
                                        @php
                                            $calcValue = floatval($results['delta'][$index]);
                                            $expValue = null;
                                            foreach($experimentalData as $data) {
                                                if(abs(floatval($data['temperature']) - floatval($T)) < 0.1) {
                                                    $expValue = floatval($data['value']);
                                                    break;
                                                }
                                            }
                                            $diff = $expValue ? $calcValue - $expValue : null;
                                            $relError = $expValue ? ($diff / $expValue * 100) : null;
                                        @endphp
                                        <tr>
                                            <td class="px-3 py-2 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">{{ number_format(floatval($T), 1, '.', '') }}</td>
                                            <td class="px-3 py-2 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">{{ number_format($calcValue, 6, '.', '') }}</td>
                                            <td class="px-3 py-2 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">{{ $expValue ? number_format($expValue, 6, '.', '') : '-' }}</td>
                                            <td class="px-3 py-2 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">{{ $diff ? number_format($diff, 6, '.', '') : '-' }}</td>
                                            <td class="px-3 py-2 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">{{ $relError ? number_format($relError, 2, '.', '') . '%' : '-' }}</td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        @else
                        <div class="bg-blue-50 dark:bg-blue-900 rounded-lg p-4">
                            <p class="text-sm text-blue-800 dark:text-blue-200">
                                No calculation results yet. Please go to the Input Data tab and run a calculation.
                            </p>
                        </div>
                        @endif
                    </div>

                    <!-- Вкладка оптимизации -->
                    <div class="tab-pane hidden" id="optimization-tab">
                        @if($optimizationResult && isset($optimizationResult['success']) && $optimizationResult['success'])
                        <div class="space-y-4">
                            <div class="bg-gradient-to-r from-green-200 to-emerald-50 dark:from-green-900/30 dark:to-emerald-900/30 rounded-lg p-6 border border-green-200 dark:border-green-800">
                                <h5 class="text-xl font-bold text-gray-900 dark:text-gray-100 mb-4 flex items-center">
                                    <svg class="h-6 w-6 text-green-600 dark:text-green-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    Optimization Summary
                                </h5>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div class="bg-white dark:bg-gray-800 rounded-lg p-4 shadow-sm">
                                        <p class="text-sm text-gray-600 dark:text-gray-400 mb-1">Sum of Squared Deviations</p>
                                        <p class="text-2xl font-bold text-green-700 dark:text-green-300">{{ number_format(floatval($optimizationResult['ssd']), 6, '.', '') }}</p>
                                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Root Mean Square Error: {{ number_format(sqrt(floatval($optimizationResult['objective'])), 6, '.', '') }}</p>
                                    </div>
                                    <div class="bg-white dark:bg-gray-800 rounded-lg p-4 shadow-sm">
                                        <p class="text-sm text-gray-600 dark:text-gray-400 mb-1">Optimization Status</p>
                                        <p class="text-lg font-semibold text-green-600 dark:text-green-400">✓ Completed Successfully</p>
                                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Multi-start optimization with adaptive coordinate descent</p>
                                    </div>
                                </div>
                            </div>

                            <div class="bg-gray-200 dark:bg-gray-700 rounded-lg p-6">
                                <h5 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4 flex items-center">
                                    <svg class="h-5 w-5 text-blue-600 dark:text-blue-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    </svg>
                                    Optimized Parameters
                                </h5>
                                
                                <div class="bg-white dark:bg-gray-800 rounded-lg p-4 mb-6 border-l-4 border-orange-500">
                                    <p class="text-sm text-gray-600 dark:text-gray-400 mb-1">Fav Parameter</p>
                                    @php
                                        $favOpt = $optimizationResult['fav'] ?? 0;
                                        $favOrig = $inputOriginal['fav'] ?? $input['fav'] ?? 0;
                                    @endphp
                                    <p class="text-xl font-mono font-bold text-orange-700 dark:text-orange-300">
                                        {{ rtrim(rtrim(sprintf('%.15F', (float)$favOpt), '0'), '.') }}
                                    </p>
                                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                                        Original: {{ rtrim(rtrim(sprintf('%.15F', (float)$favOrig), '0'), '.') }}
                                    </p>
                                </div>

                                <div class="mt-4">
                                    <p class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-3">Kc Coefficients (optimized):</p>
                                    <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-3">
                                        @foreach($optimizationResult['kc'] as $index => $kc)
                                            <div class="bg-white dark:bg-gray-800 rounded-lg p-3 text-center shadow-sm hover:shadow-md transition-shadow duration-200">
                                                <p class="text-xs text-gray-500 dark:text-gray-300 mb-1">Kc[{{ $index }}]</p>
                                                <p class="text-sm font-mono font-semibold break-all {{ $index == 0 ? 'text-gray-700 dark:text-gray-300' : 'text-green-700 dark:text-green-300' }}">
                                                    {{ rtrim(rtrim(number_format(floatval($kc), 15, '.', ''), '0'), '.') }}
                                                </p>
                                                @if($index == 0)
                                                    <p class="text-xs text-gray-500 dark:text-gray-300 mt-1">(fixed)</p>
                                                @else
                                                    @php
                                                        $origKc = $inputOriginal['kc'][$index] ?? $input['kc'][$index] ?? 0;
                                                    @endphp
                                                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-1 break-all">
                                                        Original: {{ rtrim(rtrim(number_format(floatval($origKc), 15, '.', ''), '0'), '.') }}
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
                                        Parameter Comparison
                                    </h5>
                                     <div class="flex justify-end mb-3 gap-2">
                                           <button type="button" onclick="exportOptimizationParamsTable()" 
                class="inline-flex items-center px-3 py-1.5 bg-green-600 hover:bg-green-700 text-white text-xs font-medium rounded-md transition-colors duration-150">
            <svg class="h-4 w-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
            </svg>
            Export Table to CSV
        </button>
        </div>
                                <div class="overflow-x-auto">
                                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-600">
                                        <thead class="bg-gray-400 dark:bg-gray-800">
                                            <tr>
                                                <th class="px-4 py-3 text-left text-xs font-medium text-white dark:text-gray-400 uppercase tracking-wider">Parameter</th>
                                                <th class="px-4 py-3 text-left text-xs font-medium text-white dark:text-gray-400 uppercase tracking-wider">Original Value</th>
                                                <th class="px-4 py-3 text-left text-xs font-medium text-white dark:text-gray-400 uppercase tracking-wider">Optimized Value</th>
                                                <th class="px-4 py-3 text-left text-xs font-medium text-white dark:text-gray-400 uppercase tracking-wider">Change (%)</th>
                                            </tr>
                                        </thead>
                                        <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                            @foreach($optimizationResult['kc'] as $index => $kc)
                                                @if($index > 0)
                                                    @php
                                                        $originalValue = floatval($inputOriginal['kc'][$index] ?? $input['kc'][$index] ?? 0);
                                                        $optimizedValue = floatval($kc);
                                                        $change = $originalValue != 0 ? ($optimizedValue - $originalValue) / abs($originalValue) * 100 : 0;
                                                        $changeFormatted = ($change > 0 ? '+' : '') . number_format($change, 2, '.', '');
                                                    @endphp
                                                    <tr>
                                                        <td class="px-4 py-3 text-sm font-medium text-gray-900 dark:text-gray-100">Kc[{{ $index }}]</td>
                                                        <td class="px-4 py-3 text-sm font-mono text-gray-700 dark:text-gray-300 break-all">
                                                            {{ rtrim(rtrim(number_format($originalValue, 15, '.', ''), '0'), '.') }}
                                                        </td>
                                                        <td class="px-4 py-3 text-sm font-mono text-green-700 dark:text-green-300 break-all">
                                                            {{ rtrim(rtrim(number_format($optimizedValue, 15, '.', ''), '0'), '.') }}
                                                        </td>
                                                        <td class="px-4 py-3 text-sm {{ $change > 0 ? 'text-green-600' : ($change < 0 ? 'text-red-600' : 'text-gray-600') }}">
                                                            {{ $changeFormatted }}%
                                                         </td>
                                                    </tr>
                                                @endif
                                            @endforeach

                                            @php
                                                $originalFav = floatval($inputOriginal['fav'] ?? $input['fav'] ?? 0);
                                                $optimizedFav = floatval($optimizationResult['fav'] ?? 0);
                                                $changeFav = $originalFav != 0 ? ($optimizedFav - $originalFav) / abs($originalFav) * 100 : 0;
                                                $changeFavFormatted = ($changeFav > 0 ? '+' : '') . number_format($changeFav, 2, '.', '');
                                            @endphp
                                            <tr>
                                                <td class="px-4 py-3 text-sm font-medium text-gray-900 dark:text-gray-100">Fav</td>
                                                <td class="px-4 py-3 text-sm font-mono text-gray-700 dark:text-gray-300 break-all">
                                                    {{ rtrim(rtrim(number_format($originalFav, 15, '.', ''), '0'), '.') }}
                                                 </td>
                                                <td class="px-4 py-3 text-sm font-mono text-green-700 dark:text-green-300 break-all">
                                                    {{ rtrim(rtrim(number_format($optimizedFav, 15, '.', ''), '0'), '.') }}
                                                 </td>
                                                <td class="px-4 py-3 text-sm {{ $changeFav > 0 ? 'text-green-600' : ($changeFav < 0 ? 'text-red-600' : 'text-gray-600') }}">
                                                    {{ $changeFavFormatted }}%
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
                                No optimization results yet. Please add experimental data and click the "Optimize" button in the Input Data tab.
                            </p>
                        </div>
                        @endif
                    </div>

                    <!-- Вкладка истории -->
                    <div class="tab-pane hidden" id="history-tab">
                        <div class="bg-gray-200 dark:bg-gray-700 rounded-lg p-4">
                            <h5 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-3">Calculation History</h5>
                            
                            @if($history->isEmpty())
                                <div class="bg-blue-50 dark:bg-blue-900 rounded-lg p-4">
                                    <p class="text-sm text-blue-800 dark:text-blue-200">
                                        No calculations in history yet. Run a calculation to see it here.
                                    </p>
                                </div>
                            @else
                                <div class="space-y-3 max-h-96 overflow-y-auto pr-2">
                                    @foreach($history as $item)
                                        <div class="border border-gray-400 dark:border-gray-500 rounded-lg p-3 sm:p-4 hover:bg-gray-100 dark:hover:bg-gray-800 transition-colors duration-150 
                                            {{ $item->is_favorite ? 'border-yellow-400 dark:border-yellow-600 bg-yellow-100 dark:bg-yellow-900/20' : '' }}">
                                            
                                            <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-3">
                                                <div class="flex-1 min-w-0">
                                                    <div class="flex items-start gap-2">
                                                        @if($item->is_favorite)
                                                            <svg class="h-5 w-5 text-yellow-400 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                                            </svg>
                                                        @endif
                                                        
                                                        <div class="flex-1 min-w-0">
                                                            <div class="flex flex-wrap items-center gap-2">
                                                                <h6 class="font-medium text-gray-900 dark:text-gray-100 break-words" id="name-text-{{ $item->id }}">
                                                                    {{ $item->name }}
                                                                </h6>
                                                                <button onclick="editName({{ $item->id }})" class="text-gray-500 hover:text-indigo-600 dark:text-gray-400 dark:hover:text-indigo-400 transition-colors duration-150 flex-shrink-0">
                                                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                                                                    </svg>
                                                                </button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    
                                                    <div id="edit-name-{{ $item->id }}" class="hidden mt-2 w-full">
                                                        <div class="flex flex-col sm:flex-row items-start sm:items-center gap-2">
                                                            <input type="text" id="new-name-{{ $item->id }}" value="{{ $item->name }}" 
                                                                   class="w-full sm:flex-1 rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 bg-white dark:bg-gray-600 dark:border-gray-600 dark:text-white text-sm">
                                                            <div class="flex gap-2 w-full sm:w-auto">
                                                                <button onclick="saveName({{ $item->id }})" class="flex-1 sm:flex-none inline-flex items-center justify-center px-3 py-1 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-500 focus:outline-none focus:ring-2 focus:ring-green-500 transition ease-in-out duration-150">
                                                                    Save
                                                                </button>
                                                                <button onclick="cancelEdit({{ $item->id }})" class="flex-1 sm:flex-none inline-flex items-center justify-center px-3 py-1 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-500 focus:outline-none focus:ring-2 focus:ring-gray-500 transition ease-in-out duration-150">
                                                                    Cancel
                                                                </button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    
                                                    <p class="text-sm text-gray-600 dark:text-gray-400 mt-2">
                                                        Created: {{ $item->created_at->format('d.m.Y H:i') }}
                                                    </p>
                                                    
                                                    <!-- Блок с публичными ссылками -->
@if(isset($item->active_share_links) && $item->active_share_links->count() > 0)
<div class="mt-3">
    <p class="text-xs font-medium text-gray-500 dark:text-gray-300 mb-2 flex items-center gap-1">
        <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.368 2.684 3 3 0 00-5.368-2.684z"></path>
        </svg>
        Публичные ссылки:
    </p>
    <div class="flex flex-col gap-2">
        @foreach($item->active_share_links as $link)
        <div class="bg-gray-100 dark:bg-gray-600 border border-gray-400 dark:border-gray-500 rounded-lg p-2.5 hover:shadow-md transition-all duration-200">
            <!-- Первая строка: ссылка -->
            <div class="flex items-center gap-2">
                
                <a href="{{ $link->url }}" target="_blank" 
                   class="text-blue-600 dark:text-blue-400 hover:underline font-mono text-sm truncate flex-1"
                   title="{{ $link->url }}">
                    {{ $link->url }}
        @if($link->password_hash)
        <span class="inline-flex items-center gap-1 text-gray-500 dark:text-gray-200 text-xs" title="Защищена паролем">
            <svg class="h-3 w-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
            </svg>
        </span>
        @endif
                </a>

            </div>
            
            <!-- Вторая строка: счётчик просмотров и кнопки -->
            <div class="flex items-center justify-between mt-2 pt-1 border-t border-gray-300 dark:border-gray-500">
                <div class="flex items-center gap-3">
                    
                    @if($link->expires_at)
                    <span class="text-xs text-gray-500 dark:text-gray-200" title="Истекает: {{ $link->expires_at->format('d.m.Y') }}">
                        До {{ $link->expires_at->format('d.m.Y') }}
                    </span>
                    @else
                    <span class="text-xs text-gray-500 dark:text-gray-200" title="Бессрочная ссылка">
                        Бессрочно
                    </span>
                    @endif
                </div>
                
                <!-- Кнопки действий -->
                <div class="flex items-center gap-1">
                    <!-- Статусы -->

                   <!-- Счётчик просмотров -->
<span class="inline-flex items-center gap-1 text-gray-500 dark:text-gray-200 text-xs mr-2" title="Просмотров: {{ $link->views }}">
    <svg class="h-3 w-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
    </svg>
    {{ $link->views }}
</span>

<!-- Кнопка просмотра статистики -->
@if(isset($item->active_share_links) && $item->active_share_links->count() > 0)
    @foreach($item->active_share_links as $link)
    <a href="{{ route('public.calculation.stats', ['token' => $link->token]) }}" 
       target="_blank"
       class="inline-flex items-center gap-1 text-gray-500 dark:text-gray-200 text-xs hover:text-blue-600 dark:hover:text-blue-400 transition-colors" 
       title="Статистика">
        <svg class="h-3 w-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
        </svg>
    </a>
    @break
    @endforeach
@endif

                    <button onclick="copyToClipboard('{{ $link->url }}')" 
                            class="p-1 text-gray-500 hover:text-indigo-600 dark:text-gray-200 dark:hover:text-indigo-400 transition-colors rounded-md hover:bg-gray-200 dark:hover:bg-gray-600"
                            title="Копировать ссылку">
                        <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
                        </svg>
                    </button>
                    
                    <button onclick="showQRCode('{{ $link->token }}', '{{ $link->url }}')" 
                            class="p-1 text-gray-500 hover:text-purple-600 dark:text-gray-200 dark:hover:text-purple-400 transition-colors rounded-md hover:bg-gray-200 dark:hover:bg-gray-600"
                            title="Показать QR-код">
                        <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"></path>
                        </svg>
                    </button>
                    
                    <button onclick="editShareLink(
                        {{ $link->id }}, 
                        {{ json_encode($link->title) }}, 
                        {{ json_encode($link->description) }}, 
                        {{ json_encode($link->expires_at) }}, 
                        {{ $link->allow_copy_to_account ? 'true' : 'false' }},
                        {{ $link->password_hash ? 'true' : 'false' }}
                    )" 
                        class="p-1 text-gray-500 hover:text-yellow-600 dark:text-gray-200 dark:hover:text-yellow-400 transition-colors rounded-md hover:bg-gray-200 dark:hover:bg-gray-600"
                        title="Редактировать">
                        <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path>
                        </svg>
                    </button>
                    
                    <button onclick="revokeShareLink({{ $link->id }})" 
                            class="p-1 text-gray-500 hover:text-red-600 dark:text-gray-200 dark:hover:text-red-400 transition-colors rounded-md hover:bg-gray-200 dark:hover:bg-gray-600"
                            title="Удалить ссылку">
                        <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                        </svg>
                    </button>
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>
@endif
                                                </div>
                                                
                                                <div class="text-sm text-gray-600 dark:text-gray-400 flex flex-row sm:flex-col gap-2 sm:gap-1 flex-wrap sm:flex-nowrap">
                                                    @if($item->results)
                                                        <span class="whitespace-nowrap">Results: {{ count($item->results['temperatures'] ?? []) }} points</span>
                                                    @endif
                                                    @if($item->optimization_results)
                                                        <span class="whitespace-nowrap text-lime-700 dark:text-green-400">Optimized</span>
                                                    @endif
                                                </div>
                                            </div>
                                            
                                            <div class="mt-3 flex flex-wrap gap-2">
                                                <a href="{{ route('calculator.Ho3+.history.load', $item) }}" 
                                                   class="inline-flex items-center px-3 py-1.5 sm:px-3 sm:py-1 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-500 active:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
                                                    Load
                                                </a>
                                                
                                                <!-- Кнопка создания публичной ссылки -->
                                                <button onclick="createShareLink({{ $item->id }})" 
                                                        class="inline-flex items-center px-3 py-1.5 sm:px-3 sm:py-1 bg-purple-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-purple-500 active:bg-purple-700 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
                                                    <svg class="h-3 w-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.368 2.684 3 3 0 00-5.368-2.684z"></path>
                                                    </svg>
                                                    Share
                                                </button>
                                                
                                                <button onclick="toggleFavorite({{ $item->id }})" 
                                                        class="inline-flex items-center px-3 py-1.5 sm:px-3 sm:py-1 {{ $item->is_favorite ? 'bg-yellow-600' : 'bg-gray-600' }} border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-yellow-500 active:bg-yellow-700 focus:outline-none focus:ring-2 focus:ring-yellow-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
                                                    {{ $item->is_favorite ? 'Remove from favorites' : 'Add to favorites' }}
                                                </button>
                                                <form action="{{ route('calculator.Ho3+.history.delete', $item) }}" method="POST" class="inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" onclick="return confirm('Delete this calculation from history?');"
                                                            class="inline-flex items-center px-3 py-1.5 sm:px-3 sm:py-1 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-500 active:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
                                                        Delete
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div id="share-link-modal" class="fixed inset-0 z-50 hidden">
    <div class="absolute inset-0 bg-gray-900/70 dark:bg-gray-900/80 backdrop-blur-sm"></div>
    <div class="relative flex items-center justify-center h-full">
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-2xl p-6 max-w-md w-full mx-4">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-xl font-bold text-gray-900 dark:text-gray-100">Создать ссылку</h3>
                <button onclick="closeShareModal()" class="text-gray-500 hover:text-gray-700 dark:text-gray-400">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
            
            <form id="share-link-form" autocomplete="off">
                <input type="hidden" id="share-history-id" name="history_id">
                
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Заголовок (необязательно)</label>
                    <input type="text" id="share-title" name="title" 
                           autocomplete="off"
                           data-lpignore="true"
                           data-1p-ignore="true"
                           class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                           placeholder="Введите заголовок">
                </div>
                
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Описание (необязательно)</label>
                    <textarea id="share-description" name="description" rows="2"
                              autocomplete="off"
                              data-lpignore="true"
                              data-1p-ignore="true"
                              class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                              placeholder="Введите описание"></textarea>
                </div>
                
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Срок действия</label>
                    <select id="share-expires" name="expires_in_days" 
                            class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                        <option value="7">7 дней</option>
                        <option value="30" selected>30 дней</option>
                        <option value="90">90 дней</option>
                        <option value="365">1 дней</option>
                        <option value="">Бессрочно</option>
                    </select>
                </div>
                
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Пароль (необязательно)</label>
                    <input type="password" id="share-password" name="password" 
                           autocomplete="new-password"
                           data-lpignore="true"
                           data-1p-ignore="true"
                           class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                           placeholder="Оставьте пустым для общего доступа">
                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Установите пароль для ограничения доступа.</p>
                </div>
                
                <div class="mb-4">
                    <label class="flex items-center gap-2">
                        <input type="checkbox" id="share-allow-copy" name="allow_copy_to_account" checked
                               class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                        <span class="text-sm text-gray-700 dark:text-gray-300">Разрешить копирование в аккаунт</span>
                    </label>
                </div>
                
                <div class="flex gap-3">
                    <button type="submit" class="flex-1 inline-flex justify-center items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-sm text-white uppercase tracking-widest hover:bg-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
                        Создать ссылку
                    </button>
                    <button type="button" onclick="closeShareModal()" class="flex-1 inline-flex justify-center items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-sm text-white uppercase tracking-widest hover:bg-gray-500 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
                        Отмена
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Оверлей оптимизации -->
<div id="optimization-overlay" class="fixed inset-0 z-50 hidden">
    <div class="absolute inset-0 bg-gray-900/70 dark:bg-gray-900/80 backdrop-blur-sm"></div>
    <div class="relative flex items-center justify-center h-full">
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-2xl p-8 max-w-md w-full mx-4 text-center">
            
            <!-- Анимированная иконка -->
            <div class="flex justify-center mb-6">
                <div class="relative">
                    <div class="w-20 h-20 rounded-full border-4 border-indigo-100 dark:border-indigo-900 flex items-center justify-center">
                        <svg class="w-10 h-10 text-indigo-600 dark:text-indigo-400 animate-pulse" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        </svg>
                    </div>
                    <!-- Вращающееся кольцо -->
                    <div class="absolute inset-0 rounded-full border-4 border-transparent border-t-indigo-500 dark:border-t-indigo-400 animate-spin"></div>
                </div>
            </div>
            
            <h3 class="text-xl font-bold text-gray-900 dark:text-gray-100 mb-2">Optimization in Progress</h3>
            <p class="text-gray-500 dark:text-gray-400 text-sm mb-6" id="optimization-status-text">
                Initializing multi-start optimization...
            </p>
            
            <!-- Прогресс-бар -->
            <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-2 mb-4 overflow-hidden">
                <div id="opt-progress-bar" class="h-2 rounded-full bg-gradient-to-r from-indigo-500 to-blue-500 transition-all duration-500" style="width: 0%"></div>
            </div>
            
            <!-- Счётчик времени -->
            <p class="text-xs text-gray-400 dark:text-gray-500 mb-6">
                Elapsed time: <span id="opt-elapsed-time" class="font-mono font-medium text-gray-600 dark:text-gray-400">0s</span>
                <span class="mx-2">·</span>
                This may take up to several minutes
            </p>
            
            <!-- Анимированные точки с описанием шагов -->
            <!-- Шаги оптимизации -->
<div class="space-y-2 text-left">
    @for($i = 1; $i <= 5; $i++)
        <div class="opt-step flex items-center gap-3 p-2 rounded-lg opacity-30 transition-all duration-300" id="step-{{ $i }}">
            <div class="w-5 h-5 rounded-full bg-indigo-100 dark:bg-indigo-900 flex items-center justify-center flex-shrink-0">
                <span class="text-xs font-bold text-indigo-600 dark:text-indigo-400">{{ $i }}</span>
            </div>

            <span class="text-sm text-gray-600 dark:text-gray-400">
                Restart {{ $i }} of 5
            </span>

            <div class="ml-auto step-check hidden">
                <svg class="w-4 h-4 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd"
                          d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                          clip-rule="evenodd"/>
                </svg>
            </div>
        </div>
    @endfor
</div>
        </div>
    </div>
</div>

<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
    // Переменная для хранения текущего ID истории для создания ссылки
    let currentHistoryId = null;
    
    // ==========================================
    // ФУНКЦИИ ДЛЯ РАБОТЫ С ПУБЛИЧНЫМИ ССЫЛКАМИ
    // ==========================================
    
    function createShareLink(historyId) {
        currentHistoryId = historyId;
        document.getElementById('share-history-id').value = historyId;
        document.getElementById('share-title').value = '';
        document.getElementById('share-description').value = '';
        document.getElementById('share-password').value = '';
        document.getElementById('share-expires').value = '30';
        document.getElementById('share-allow-copy').checked = true;
        document.getElementById('share-link-modal').classList.remove('hidden');
    }
    
    function closeShareModal() {
        document.getElementById('share-link-modal').classList.add('hidden');
        currentHistoryId = null;
    }
    
    // Обработчик формы создания ссылки
    document.getElementById('share-link-form').addEventListener('submit', async (e) => {
        e.preventDefault();
        
        const historyId = document.getElementById('share-history-id').value;
        const title = document.getElementById('share-title').value;
        const description = document.getElementById('share-description').value;
        const expiresInDays = document.getElementById('share-expires').value;
        const password = document.getElementById('share-password').value;
        const allowCopy = document.getElementById('share-allow-copy').checked;
        
        const formData = {
            title: title || null,
            description: description || null,
            expires_in_days: expiresInDays ? parseInt(expiresInDays) : null,
            password: password || null,
            allow_copy_to_account: allowCopy
        };
        
        try {
            const response = await fetch(`/calculator/HO3+/history/${historyId}/share`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json'
                },
                body: JSON.stringify(formData)
            });
            
            const data = await response.json();
            
            if (data.success) {
                // Копируем ссылку в буфер обмена
                await navigator.clipboard.writeText(data.share_url);
                showTemporaryMessage('Share link created and copied to clipboard!', 'success');
                closeShareModal();
                setTimeout(() => location.reload(), 1000);
            } else {
                showTemporaryMessage(data.error || 'Failed to create share link', 'error');
            }
        } catch (error) {
            console.error('Error creating share link:', error);
            showTemporaryMessage('Network error. Please try again.', 'error');
        }
    });
    
// Функция для полного удаления ссылки из базы данных
async function revokeShareLink(linkId) {
    if (!confirm('Вы уверены, что хотите окончательно удалить эту ссылку? Это действие нельзя отменить.')) {
        return;
    }
    
    try {
        const response = await fetch(`/share-links/${linkId}`, {
            method: 'DELETE',  // Используем DELETE метод для полного удаления
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Accept': 'application/json'
            }
        });
        
        const data = await response.json();
        
        if (data.success) {
            showTemporaryMessage('Ссылка удалена', 'success');
            // Удаляем элемент из DOM без перезагрузки страницы
            const linkElement = event?.target?.closest('.group');
            if (linkElement) {
                linkElement.remove();
            }
            // Перезагружаем страницу для обновления списка
            setTimeout(() => location.reload(), 1000);
        } else {
            showTemporaryMessage(data.error || 'Ошибка удаления ссылки', 'error');
        }
    } catch (error) {
        console.error('Error:', error);
        showTemporaryMessage('Ошибка сети при удалении ссылки', 'error');
    }
}

// Функция для показа QR-кода
function showQRCode(token, url) {
    // Удаляем существующее модальное окно, если есть
    const existingModal = document.getElementById('qr-modal');
    if (existingModal) {
        existingModal.remove();
    }
    
    // Создаём модальное окно
    const modal = document.createElement('div');
    modal.id = 'qr-modal';
    modal.className = 'fixed inset-0 z-50 hidden';
    modal.innerHTML = `
        <div class="absolute inset-0 bg-gray-900/70 dark:bg-gray-900/80 backdrop-blur-sm"></div>
        <div class="relative flex items-center justify-center h-full">
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-2xl p-6 max-w-sm w-full mx-4">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-xl font-bold text-gray-900 dark:text-white">QR-код</h3>
                    <button onclick="closeQRModal()" class="text-gray-500 hover:text-gray-700 dark:text-gray-400">
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
                <div class="flex justify-center mb-4">
                    <div id="qr-code-container" class="bg-white p-4 rounded-lg">
                        <div class="text-center text-gray-500">Загрузка QR-кода...</div>
                    </div>
                </div>
                <p class="text-xs text-gray-500 dark:text-gray-400 text-center break-all">${url}</p>
                <div class="flex gap-3 mt-4">
                    <button onclick="downloadQRCode()" class="flex-1 inline-flex justify-center items-center px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium rounded-lg transition-colors duration-150">
                        <svg class="h-4 w-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                        </svg>
                        Скачать
                    </button>
                    <button onclick="closeQRModal()" class="flex-1 inline-flex justify-center items-center px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white text-sm font-medium rounded-lg transition-colors duration-150">
                        Закрыть
                    </button>
                </div>
            </div>
        </div>
    `;
    
    document.body.appendChild(modal);
    modal.classList.remove('hidden');
    
    // Генерируем QR-код
    const qrImage = document.createElement('img');
    qrImage.src = `https://quickchart.io/qr?text=${encodeURIComponent(url)}&size=200`;
    qrImage.alt = 'QR Code';
    qrImage.className = 'w-48 h-48';
    qrImage.onload = () => {
        const container = document.getElementById('qr-code-container');
        if (container) {
            container.innerHTML = '';
            container.appendChild(qrImage);
        }
    };
    qrImage.onerror = () => {
        const container = document.getElementById('qr-code-container');
        if (container) {
            container.innerHTML = '<div class="text-red-500 text-center">Ошибка загрузки QR-кода</div>';
        }
    };
}

function closeQRModal() {
    const modal = document.getElementById('qr-modal');
    if (modal) {
        modal.classList.add('hidden');
        setTimeout(() => modal.remove(), 300);
    }
}

function downloadQRCode() {
    const qrImage = document.querySelector('#qr-code-container img');
    if (qrImage && qrImage.src) {
        const link = document.createElement('a');
        link.download = 'qrcode.png';
        link.href = qrImage.src;
        link.click();
        showTemporaryMessage('QR-код сохранён!', 'success');
    } else {
        showTemporaryMessage('Не удалось сохранить QR-код', 'error');
    }
}

// Функция редактирования ссылки (с отображением статуса пароля)
function editShareLink(linkId, currentTitle, currentDescription, currentExpiresAt, currentAllowCopy, hasPassword) {
    // Удаляем существующее модальное окно, если есть
    const existingModal = document.getElementById('edit-link-modal');
    if (existingModal) {
        existingModal.remove();
    }
    
    // Форматируем дату истечения для select
    let expiresValue = '';
    if (currentExpiresAt && currentExpiresAt !== 'null') {
        try {
            const expiresDate = new Date(currentExpiresAt);
            if (!isNaN(expiresDate.getTime())) {
                const now = new Date();
                const diffDays = Math.ceil((expiresDate - now) / (1000 * 60 * 60 * 24));
                if (diffDays === 7) expiresValue = '7';
                else if (diffDays === 30) expiresValue = '30';
                else if (diffDays === 90) expiresValue = '90';
                else if (diffDays === 365) expiresValue = '365';
                else expiresValue = '';
            }
        } catch(e) {
            expiresValue = '';
        }
    }
    
    // Создаём модальное окно для редактирования
    const modal = document.createElement('div');
    modal.id = 'edit-link-modal';
    modal.className = 'fixed inset-0 z-50 hidden';
    modal.innerHTML = `
        <div class="absolute inset-0 bg-gray-900/70 dark:bg-gray-900/80 backdrop-blur-sm"></div>
        <div class="relative flex items-center justify-center h-full p-4">
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-2xl p-6 max-w-md w-full mx-auto max-h-[90vh] overflow-y-auto">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-xl font-bold text-gray-900 dark:text-white">Редактировать ссылку</h3>
                    <button onclick="closeEditModal()" class="text-gray-500 hover:text-gray-700 dark:text-gray-400">
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
                
                <form id="edit-link-form" autocomplete="off">
                    <input type="hidden" id="edit-link-id" value="${linkId}">
                    
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Заголовок (необязательно)</label>
                        <input type="text" id="edit-link-title" value="${escapeHtml(currentTitle || '')}" 
                               autocomplete="off"
                               data-lpignore="true"
                               data-1p-ignore="true"
                               class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 bg-gray-200 dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                               placeholder="Введите заголовок">
                    </div>
                    
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Описание (необязательно)</label>
                        <textarea id="edit-link-description" rows="3"
                                  autocomplete="off"
                                  data-lpignore="true"
                                  data-1p-ignore="true"
                                  class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 bg-gray-200 dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                                  placeholder="Введите описание">${escapeHtml(currentDescription || '')}</textarea>
                    </div>
                    
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Срок действия</label>
                        <select id="edit-link-expires" 
                                class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 bg-gray-200 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                            <option value="7" ${expiresValue === '7' ? 'selected' : ''}>7 дней</option>
                            <option value="30" ${expiresValue === '30' ? 'selected' : ''}>30 дней</option>
                            <option value="90" ${expiresValue === '90' ? 'selected' : ''}>90 дней</option>
                            <option value="365" ${expiresValue === '365' ? 'selected' : ''}>1 год</option>
                            <option value="" ${expiresValue === '' ? 'selected' : ''}>Бессрочно</option>
                        </select>
                    </div>
                    
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Пароль (необязательно)</label>
                        <div class="relative">
                            <input type="password" id="edit-link-password" 
                                   autocomplete="new-password"
                                   data-lpignore="true"
                                   data-1p-ignore="true"
                                   class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 bg-gray-200 dark:bg-gray-700 dark:border-gray-600 dark:text-white pr-10"
                                   placeholder="Оставьте пустым для общего доступа">
                            <button type="button" 
                                    onclick="togglePasswordVisibility('edit-link-password')"
                                    class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-gray-600">
                                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                </svg>
                            </button>
                        </div>
                        <div class="mt-2 p-2 rounded-md ${hasPassword ? 'bg-green-100 dark:bg-green-900/30 border border-green-300 dark:border-green-700' : 'bg-gray-100 dark:bg-gray-700 border border-gray-300 dark:border-gray-600'}">
                            <div class="flex items-center gap-2">
                                <svg class="h-4 w-4 ${hasPassword ? 'text-green-600 dark:text-green-400' : 'text-gray-500 dark:text-gray-400'}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    ${hasPassword ? 
                                        '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>' :
                                        '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 11V7a4 4 0 118 0m-4 8v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2z"></path>'
                                    }
                                </svg>
                                <span class="text-sm ${hasPassword ? 'text-green-700 dark:text-green-300' : 'text-gray-600 dark:text-gray-400'}">
                                    ${hasPassword ? 'Ссылка защищена паролем' : 'Парольная защита не установлена'}
                                </span>
                            </div>
                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1 ml-6">
                                ${hasPassword ? 
                                    'Оставьте поле пустым, чтобы сохранить текущий пароль. Введите новый пароль для его изменения.' : 
                                    'Введите пароль для защиты ссылки, или оставьте поле пустым.'}
                            </p>
                            ${hasPassword ? `
                            <div class="mt-2 ml-6">
                                <button type="button" 
                                        onclick="removePassword()"
                                        class="text-xs text-red-600 hover:text-red-700 dark:text-red-400 dark:hover:text-red-300 underline">
                                    ✕ Удалить парольную защиту
                                </button>
                            </div>
                            ` : ''}
                        </div>
                    </div>
                    
                    <div class="mb-4">
                        <label class="flex items-center gap-2">
                            <input type="checkbox" id="edit-link-allow-copy" ${currentAllowCopy ? 'checked' : ''}
                                   class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                            <span class="text-sm text-gray-700 dark:text-gray-300">Разрешить копирование в аккаунт</span>
                        </label>
                    </div>
                    
                    <div class="flex gap-3">
                        <button type="submit" class="flex-1 inline-flex justify-center items-center px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium rounded-lg transition-colors duration-150">
                            Сохранить изменения
                        </button>
                        <button type="button" onclick="closeEditModal()" class="flex-1 inline-flex justify-center items-center px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white text-sm font-medium rounded-lg transition-colors duration-150">
                            Отмена
                        </button>
                    </div>
                </form>
            </div>
        </div>
    `;
    
    document.body.appendChild(modal);
    modal.classList.remove('hidden');
    
    // Глобальная переменная для отслеживания удаления пароля
    window.passwordToRemove = false;
    
    window.removePassword = function() {
        if (confirm('Вы уверены, что хотите удалить парольную защиту? Ссылка станет публично доступной.')) {
            const passwordInput = document.getElementById('edit-link-password');
            passwordInput.value = 'DELETE_PASSWORD'; // Специальный маркер для удаления
            passwordInput.placeholder = 'Пароль будет удален';
            passwordInput.classList.add('bg-red-50', 'dark:bg-red-900/20', 'border-red-300', 'dark:border-red-700');
            
            // Обновляем сообщение
            const infoDiv = passwordInput.closest('.mb-4').querySelector('.bg-green-100, .bg-gray-100');
            if (infoDiv) {
                infoDiv.innerHTML = `
                    <div class="flex items-center gap-2">
                        <svg class="h-4 w-4 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 11V7a4 4 0 118 0m-4 8v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2z"></path>
                        </svg>
                        <span class="text-sm text-red-700 dark:text-red-300">⚠️Пароль будет удален при сохранении</span>
                    </div>
                `;
            }
        }
    };
    
    // Обработчик отправки формы
    document.getElementById('edit-link-form').addEventListener('submit', async (e) => {
        e.preventDefault();
        
        const linkId = document.getElementById('edit-link-id').value;
        const title = document.getElementById('edit-link-title').value;
        const description = document.getElementById('edit-link-description').value;
        const expiresInDays = document.getElementById('edit-link-expires').value;
        let password = document.getElementById('edit-link-password').value;
        const allowCopy = document.getElementById('edit-link-allow-copy').checked;
        
        // Обработка удаления пароля
        if (password === 'DELETE_PASSWORD') {
            password = '';
        }
        
        const formData = {
            title: title || null,
            description: description || null,
            expires_in_days: expiresInDays ? parseInt(expiresInDays) : null,
            password: password || null,
            allow_copy_to_account: allowCopy
        };
        
        // Показываем индикатор загрузки
        const submitBtn = e.target.querySelector('button[type="submit"]');
        const originalText = submitBtn.innerHTML;
        submitBtn.innerHTML = '<svg class="animate-spin h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg> Сохранение...';
        submitBtn.disabled = true;
        
        try {
            const response = await fetch(`/share-links/${linkId}`, {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json'
                },
                body: JSON.stringify(formData)
            });
            
            const data = await response.json();
            
            if (data.success) {
                showTemporaryMessage('Ссылка успешно обновлена!', 'success');
                closeEditModal();
                setTimeout(() => location.reload(), 1000);
            } else {
                showTemporaryMessage(data.error || 'Ошибка обновления ссылки', 'error');
            }
        } catch (error) {
            console.error('Error:', error);
            showTemporaryMessage('Ошибка сети при обновлении ссылки', 'error');
        } finally {
            submitBtn.innerHTML = originalText;
            submitBtn.disabled = false;
        }
    });
}

// Вспомогательная функция для экранирования HTML
function escapeHtml(text) {
    if (!text) return '';
    const div = document.createElement('div');
    div.textContent = text;
    return div.innerHTML;
}

// Функция для показа/скрытия пароля
function togglePasswordVisibility(inputId) {
    const input = document.getElementById(inputId);
    if (input) {
        input.type = input.type === 'password' ? 'text' : 'password';
    }
}

function closeEditModal() {
    const modal = document.getElementById('edit-link-modal');
    if (modal) {
        modal.classList.add('hidden');
        setTimeout(() => modal.remove(), 300);
    }
}
    
    function copyToClipboard(text) {
        navigator.clipboard.writeText(text);
        showTemporaryMessage('Ссылка скопирована!', 'success');
    }
    
    // Управление вкладками
    document.querySelectorAll('.tab-button').forEach(button => {
        button.addEventListener('click', () => {
            document.querySelectorAll('.tab-button').forEach(btn => {
                btn.classList.remove('active', 'border-indigo-500', 'text-indigo-600', 'dark:text-indigo-400');
                btn.classList.add('border-transparent', 'text-gray-500', 'hover:text-gray-700', 'dark:text-gray-400', 'dark:hover:text-gray-300');
            });
            
            button.classList.add('active', 'border-indigo-500', 'text-indigo-600', 'dark:text-indigo-400');
            button.classList.remove('border-transparent', 'text-gray-500', 'hover:text-gray-700', 'dark:text-gray-400', 'dark:hover:text-gray-300');
            
            document.querySelectorAll('.tab-pane').forEach(pane => {
                pane.classList.add('hidden');
            });
            
            const tabId = button.getAttribute('data-tab');
            document.getElementById(tabId + '-tab').classList.remove('hidden');
        });
    });

    // Функция для экспорта таблицы из правой колонки (вкладка Input)
function exportTableFromResultsTab() {
    // Собираем данные вручную для правильного форматирования
    const temperatures = [];
    const wValues = [];
    const lifetimes = [];
    
    // Собираем данные из таблицы
    document.querySelectorAll('#input-tab .bg-gray-200.dark\\:bg-gray-700 table tbody tr').forEach(row => {
        const cells = row.querySelectorAll('td');
        if (cells.length >= 3) {
            temperatures.push(cells[0].innerText.trim());
            wValues.push(cells[1].innerText.trim());
            lifetimes.push(cells[2].innerText.trim());
        }
    });
    
    if (temperatures.length === 0) {
        showTemporaryMessage('No results to export', 'error');
        return;
    }
    
    // Заголовки для Excel
    const headers = ['T (K)', 'W (s⁻¹)', 'Lifetime (µs)'];
    
    // Подготавливаем данные
    const data = temperatures.map((temp, index) => [
        temp,
        wValues[index],
        lifetimes[index]
    ]);
    
    // Экспортируем
    exportToExcel(headers, data, 'calculation_results.csv');
}

    // Функция сохранения экспериментальных данных в сессию
    function saveExperimentalDataToSession() {
        const temps = [];
        const values = [];
        
        document.querySelectorAll('input[name="exp_temperatures[]"]').forEach(input => {
            if (input.value && input.value.trim() !== '') {
                temps.push(input.value.trim());
            }
        });
        
        document.querySelectorAll('input[name="exp_values[]"]').forEach(input => {
            if (input.value && input.value.trim() !== '') {
                values.push(input.value.trim());
            }
        });
        
        if (temps.length > 0 || values.length > 0) {
            fetch('{{ route("calculator.Ho3+.save-experimental") }}', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    exp_temperatures: temps,
                    exp_values: values
                })
            })
            .then(response => response.json())
            .then(data => {
                if (!data.success) {
                    console.error('Failed to save experimental data');
                } else {
                    console.log('Experimental data saved:', temps.length, 'points');
                }
            })
            .catch(error => console.error('Error saving experimental data:', error));
        }
    }

    // Функция проверки экспериментальных данных
    function checkExperimentalData() {
        const temps = [];
        const values = [];
        
        document.querySelectorAll('input[name="exp_temperatures[]"]').forEach(input => {
            if (input.value && input.value.trim() !== '') {
                temps.push(input.value.trim());
            }
        });
        
        document.querySelectorAll('input[name="exp_values[]"]').forEach(input => {
            if (input.value && input.value.trim() !== '') {
                values.push(input.value.trim());
            }
        });
        
        if (temps.length > 0 && temps.length === values.length) {
            alert('✓ Found ' + temps.length + ' experimental data point(s). Ready for optimization!');
        } else {
            alert('⚠️ No valid experimental data found. Please add temperature and lifetime pairs.\n\nTemperatures: ' + temps.length + '\nValues: ' + values.length);
        }
    }

    // Обновленная функция submitOptimization
function submitOptimization() {
    @if($viewingHistory)
        showTemporaryMessage('You cannot run optimization in view mode. Click "Edit Mode" to modify data.', 'warning');
        return;
    @endif
    
    const temps = [];
    const values = [];
    
    document.querySelectorAll('input[name="exp_temperatures[]"]').forEach(input => {
        if (input.value && input.value.trim() !== '') temps.push(input.value.trim());
    });
    document.querySelectorAll('input[name="exp_values[]"]').forEach(input => {
        if (input.value && input.value.trim() !== '') values.push(input.value.trim());
    });
    
    if (temps.length === 0 || values.length === 0) {
        showTemporaryMessage('Please add experimental data in the "Experimental Data" tab before optimizing.', 'warning');
        document.querySelectorAll('.tab-button').forEach(btn => {
            if (btn.getAttribute('data-tab') === 'experimental') btn.click();
        });
        return;
    }
    
    if (temps.length !== values.length) {
        showTemporaryMessage('The number of temperatures and lifetime values must match.', 'error');
        return;
    }
    
    showOptimizationOverlay();
    runOptimizationStep(0, 5, null, Infinity);
}

function runOptimizationStep(step, totalSteps, bestParams, bestValue) {
    const form = document.getElementById('calculatorForm');
    const formData = new FormData(form);
    
    formData.append('step', step);
    formData.append('total_steps', totalSteps);
    formData.append('best_value', bestValue === Infinity ? '' : bestValue);
    
    if (bestParams !== null) {
        bestParams.forEach((p, i) => formData.append('best_params[' + i + ']', p));
    }
    
    updateOverlayStep(step, totalSteps);
    
    fetch('{{ route("calculator.Ho3+.optimize-step") }}', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Accept': 'application/json'
        },
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (!data.success) {
            hideOptimizationOverlay();
            showTemporaryMessage(data.error || 'Optimization failed', 'error');
            return;
        }
        
        if (data.finished) {
            completeAllSteps();
            setTimeout(() => {
                window.location.href = data.redirect;
            }, 600);
        } else {
            // Запускаем следующий шаг
            runOptimizationStep(data.step, data.total_steps, data.best_params, data.best_value);
        }
    })
    .catch(error => {
        hideOptimizationOverlay();
        console.error('Error:', error);
        showTemporaryMessage('Network error during optimization. Please try again.', 'error');
    });
}

// --- Управление оверлеем ---
let optTimer = null;
let optStartTime = null;

function showOptimizationOverlay() {
    const overlay = document.getElementById('optimization-overlay');
    overlay.classList.remove('hidden');
    
    optStartTime = Date.now();
    optTimer = setInterval(() => {
        const elapsed = Math.floor((Date.now() - optStartTime) / 1000);
        const el = document.getElementById('opt-elapsed-time');
        if (el) el.textContent = elapsed + 's';
    }, 1000);
    
    // Сбрасываем все шаги
    [1,2,3,4,5].forEach(i => {
        const step = document.getElementById('step-' + i);
        if (step) {
            step.classList.add('opacity-30');
            step.classList.remove('bg-indigo-50', 'dark:bg-indigo-900/20', 'bg-green-50', 'dark:bg-green-900/20');
            const check = step.querySelector('.step-check');
            if (check) check.classList.add('hidden');
        }
    });
    
    const bar = document.getElementById('opt-progress-bar');
    if (bar) bar.style.width = '0%';
}

function hideOptimizationOverlay() {
    clearInterval(optTimer);
    const overlay = document.getElementById('optimization-overlay');
    if (overlay) overlay.classList.add('hidden');
}

function updateOverlayStep(step, totalSteps) {
    const statusText = document.getElementById('optimization-status-text');
    const bar = document.getElementById('opt-progress-bar');
    
    // Отмечаем предыдущий шаг выполненным
    if (step > 0) {
        const prevEl = document.getElementById('step-' + step);
        if (prevEl) {
            prevEl.classList.remove('opacity-30', 'bg-indigo-50', 'dark:bg-indigo-900/20');
            prevEl.classList.add('bg-green-50', 'dark:bg-green-900/20');
            const check = prevEl.querySelector('.step-check');
            if (check) check.classList.remove('hidden');
        }
    }
    
    // Активируем текущий шаг
    const currentEl = document.getElementById('step-' + (step + 1));
    if (currentEl) {
        currentEl.classList.remove('opacity-30');
        currentEl.classList.add('bg-indigo-50', 'dark:bg-indigo-900/20');
    }
    
    const progress = Math.round((step / totalSteps) * 90);
    if (bar) bar.style.width = progress + '%';
    
    if (statusText) {
        statusText.textContent = step === 0
            ? 'Initializing optimization...'
            : `Running restart ${step} of ${totalSteps}...`;
    }
}

function completeAllSteps() {
    [1,2,3,4,5].forEach(i => {
        const step = document.getElementById('step-' + i);
        if (step) {
            step.classList.remove('opacity-30', 'bg-indigo-50', 'dark:bg-indigo-900/20');
            step.classList.add('bg-green-50', 'dark:bg-green-900/20');
            const check = step.querySelector('.step-check');
            if (check) check.classList.remove('hidden');
        }
    });
    const bar = document.getElementById('opt-progress-bar');
    if (bar) bar.style.width = '100%';
    
    const statusText = document.getElementById('optimization-status-text');
    if (statusText) statusText.textContent = 'Optimization complete!';
}

    function exitHistoryView() {
        fetch('{{ route('calculator.Ho3+.exit-history-view') }}', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Content-Type': 'application/json'
            }
        })
        .then(response => {
            if (response.ok) {
                window.location.reload();
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showTemporaryMessage('Error exiting history view', 'error');
        });
    }

    // Функция для закрытия уведомлений
    function closeNotification(notificationId) {
        const notification = document.getElementById(notificationId);
        if (notification) {
            notification.style.transition = 'opacity 0.3s ease, transform 0.3s ease';
            notification.style.opacity = '0';
            notification.style.transform = 'translateX(-20px)';
            setTimeout(() => {
                if (notification && notification.parentElement) {
                    notification.remove();
                }
            }, 300);
        }
    }

    // Автоматическое закрытие уведомлений (только для тех, у которых data-auto-close="true")
    document.addEventListener('DOMContentLoaded', function() {
        // Находим все уведомления
        const notifications = document.querySelectorAll('[id$="-notification"], #validation-errors');
        
        notifications.forEach(notification => {
            const autoClose = notification.getAttribute('data-auto-close');
            // Закрываем автоматически только если auto-close = "true" (по умолчанию true)
            if (autoClose === 'true' || autoClose === null) {
                setTimeout(() => {
                    if (notification && notification.parentElement) {
                        closeNotification(notification.id);
                    }
                }, 3000);
            }
        });
    });

    function editName(id) {
        document.getElementById(`name-text-${id}`).classList.add('hidden');
        document.getElementById(`edit-name-${id}`).classList.remove('hidden');
    }

    function cancelEdit(id) {
        document.getElementById(`name-text-${id}`).classList.remove('hidden');
        document.getElementById(`edit-name-${id}`).classList.add('hidden');
    }

    function saveName(id) {
        const newName = document.getElementById(`new-name-${id}`).value;
        
        if (!newName.trim()) {
            showTemporaryMessage('Name cannot be empty', 'warning');
            return;
        }
        
        fetch(`/calculator/HO3+/history/${id}/update-name`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            },
            body: JSON.stringify({ name: newName })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                document.getElementById(`name-text-${id}`).textContent = data.name;
                cancelEdit(id);
                showTemporaryMessage('Name updated successfully', 'success');
            } else {
                showTemporaryMessage('Error updating name', 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showTemporaryMessage('Error updating name', 'error');
        });
    }

    function toggleFavorite(historyId) {
        fetch(`/calculator/HO3+/history/${historyId}/toggle-favorite`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Accept': 'application/json',
                'Content-Type': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                window.location.reload();
            } else {
                showTemporaryMessage('Error toggling favorite status', 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showTemporaryMessage('Error toggling favorite status', 'error');
        });
    }

    function clearForm() {
        if (confirm('Clear all input fields?')) {
            // Сохраняем экспериментальные данные перед очисткой
            const experimentalTemps = [];
            const experimentalValues = [];
            
            document.querySelectorAll('input[name="exp_temperatures[]"]').forEach(input => {
                if (input.value && input.value.trim() !== '') {
                    experimentalTemps.push(input.value);
                } else {
                    experimentalTemps.push('');
                }
            });
            
            document.querySelectorAll('input[name="exp_values[]"]').forEach(input => {
                if (input.value && input.value.trim() !== '') {
                    experimentalValues.push(input.value);
                } else {
                    experimentalValues.push('');
                }
            });
            
            // Очищаем все текстовые поля формы (КРОМЕ экспериментальных)
            let inputs = document.querySelectorAll('#calculatorForm input[type=text]');
            inputs.forEach(input => {
                // Пропускаем поля экспериментальных данных
                if (input.name === 'exp_temperatures[]' || input.name === 'exp_values[]') {
                    return; // Пропускаем
                }
                if (!input.hasAttribute('readonly')) {
                    input.value = '';
                }
            });
            
            // Очищаем select поля (возвращаем к значениям по умолчанию)
            const i7nSelect = document.getElementById('i7nSelect');
            if (i7nSelect) {
                i7nSelect.value = '6';
                updateI7NGrid();
            }
            
            const i8nSelect = document.getElementById('i8nSelect');
            if (i8nSelect) {
                i8nSelect.value = '7';
                updateI8NGrid();
            }
            
            // Восстанавливаем экспериментальные данные после обновления сеток
            setTimeout(() => {
                const tempInputs = document.querySelectorAll('input[name="exp_temperatures[]"]');
                const valueInputs = document.querySelectorAll('input[name="exp_values[]"]');
                
                tempInputs.forEach((input, index) => {
                    if (experimentalTemps[index] !== undefined && experimentalTemps[index] !== '') {
                        input.value = experimentalTemps[index];
                    }
                });
                
                valueInputs.forEach((input, index) => {
                    if (experimentalValues[index] !== undefined && experimentalValues[index] !== '') {
                        input.value = experimentalValues[index];
                    }
                });
            }, 100);
            
            // Сбрасываем и обновляем Kc[0] 
            setTimeout(() => {
                const firstKcInput = document.querySelector('input[name="kc[]"]');
                if (firstKcInput) {
                    firstKcInput.value = '1';
                    firstKcInput.setAttribute('readonly', 'readonly');
                    firstKcInput.classList.remove('bg-gray-50', 'dark:bg-gray-700', 'text-gray-900', 'dark:text-gray-100');
                    firstKcInput.classList.add('bg-gray-100', 'dark:bg-gray-600', 'text-gray-700', 'dark:text-gray-200', 'cursor-not-allowed');
                }
            }, 50);
            
            // Удаляем все сообщения об ошибках и подсветку полей
            function removeErrorsFromContainer(container) {
                if (!container) return;
                
                const errorFields = container.querySelectorAll('.error-border, [class*="border-red-500"], [class*="ring-red-500"]');
                errorFields.forEach(field => {
                    field.classList.remove('error-border', 'border-red-500', 'dark:border-red-500', 'ring-1', 'ring-red-500');
                });
                
                const errorMessages = container.querySelectorAll('.text-red-600, .text-red-400');
                errorMessages.forEach(msg => {
                    if (msg.innerText.includes('required') || 
                        msg.innerText.includes('must be') || 
                        msg.innerText.includes('Energy') ||
                        msg.innerText.includes('Kc') ||
                        msg.innerText.includes('Fav')) {
                        msg.remove();
                    }
                });
            }
            
            removeErrorsFromContainer(document.getElementById('input-tab'));
            removeErrorsFromContainer(document.getElementById('experimental-tab'));
            
            const validationErrors = document.getElementById('validation-errors');
            if (validationErrors && validationErrors.parentElement) {
                closeNotification('validation-errors');
            }
            
            const notifications = document.querySelectorAll('[id$="-notification"]');
            notifications.forEach(notification => {
                if (notification.id !== 'validation-errors') {
                    closeNotification(notification.id);
                }
            });
            
            setTimeout(saveExperimentalDataToSession, 200);
            
            setTimeout(() => {
                showTemporaryMessage('Form cleared successfully', 'success');
            }, 150);
        }
    }

    // Вспомогательная функция для показа временного сообщения
    function showTemporaryMessage(message, type = 'info') {
        // Проверяем, есть ли уже активное сообщение
        const existingMsg = document.querySelector('.temporary-message');
        if (existingMsg) {
            existingMsg.remove();
        }
        
        // Определяем стиль в зависимости от типа
        let bgColor, borderColor, textColor, iconColor, iconPath;
        
        switch(type) {
            case 'success':
                bgColor = 'bg-green-200 dark:bg-green-900';
                borderColor = 'border-green-500';
                textColor = 'text-green-800 dark:text-green-200';
                iconColor = 'text-green-400';
                iconPath = '<path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>';
                break;
            case 'error':
                bgColor = 'bg-red-200 dark:bg-red-900';
                borderColor = 'border-red-500';
                textColor = 'text-red-800 dark:text-red-200';
                iconColor = 'text-red-400';
                iconPath = '<path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>';
                break;
            case 'warning':
                bgColor = 'bg-yellow-50 dark:bg-yellow-900';
                borderColor = 'border-yellow-500';
                textColor = 'text-yellow-800 dark:text-yellow-200';
                iconColor = 'text-yellow-400';
                iconPath = '<path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>';
                break;
            default: // info
                bgColor = 'bg-blue-50 dark:bg-blue-900';
                borderColor = 'border-blue-500';
                textColor = 'text-blue-800 dark:text-blue-200';
                iconColor = 'text-blue-400';
                iconPath = '<path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>';
                break;
        }
        
        // Создаем временное сообщение
        const msgDiv = document.createElement('div');
        msgDiv.className = `fixed bottom-4 right-4 z-50 rounded-lg shadow-lg ${bgColor} border-l-4 ${borderColor} p-4 temporary-message`;
        msgDiv.style.maxWidth = '400px';
        msgDiv.style.minWidth = '280px';
        msgDiv.innerHTML = `
            <div class="flex items-center justify-between">
                <div class="flex items-center flex-1">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 ${iconColor}" fill="currentColor" viewBox="0 0 20 20">
                            ${iconPath}
                        </svg>
                    </div>
                    <div class="ml-3 flex-1">
                        <p class="text-sm font-medium ${textColor}">
                            ${message}
                        </p>
                    </div>
                </div>
                <button onclick="this.closest('.temporary-message').remove()" class="ml-4 flex-shrink-0 ${iconColor} hover:opacity-75 transition-colors duration-150">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
        `;
        
        document.body.appendChild(msgDiv);
        
        // Анимация появления
        msgDiv.style.animation = 'slideInUp 0.3s ease-out';
        
        // Добавляем стили анимации, если их нет
        if (!document.querySelector('#toast-animation-style')) {
            const style = document.createElement('style');
            style.id = 'toast-animation-style';
            style.textContent = `
                @keyframes slideInUp {
                    from {
                        opacity: 0;
                        transform: translateY(20px);
                    }
                    to {
                        opacity: 1;
                        transform: translateY(0);
                    }
                }
                @keyframes slideOutDown {
                    from {
                        opacity: 1;
                        transform: translateY(0);
                    }
                    to {
                        opacity: 0;
                        transform: translateY(20px);
                    }
                }
            `;
            document.head.appendChild(style);
        }
        
        // Автоматически удаляем через 5 секунд (для ошибок - через 8 секунд)
        const timeout = type === 'error' ? 8000 : 5000;
        setTimeout(() => {
            if (msgDiv && msgDiv.parentElement) {
                msgDiv.style.animation = 'slideOutDown 0.3s ease-in forwards';
                setTimeout(() => {
                    if (msgDiv && msgDiv.parentElement) {
                        msgDiv.remove();
                    }
                }, 300);
            }
        }, timeout);
    }

    // Подсветка полей с ошибками и удаление подсветки при вводе
    document.addEventListener('DOMContentLoaded', function() {
        // Функция для удаления ошибки с поля
        function removeErrorFromField(field) {
            field.classList.remove('error-border', 'border-red-500', 'dark:border-red-500', 'ring-1', 'ring-red-500');
            
            let parent = field.parentElement;
            
            const errorMessages = parent.querySelectorAll('.text-red-600, .text-red-400');
            errorMessages.forEach(errorMsg => {
                if (errorMsg.previousElementSibling === field || 
                    errorMsg.closest('.flex') || 
                    (errorMsg.innerText && (
                        errorMsg.innerText.includes('required') ||
                        errorMsg.innerText.includes('must be') ||
                        errorMsg.innerText.includes('Energy') ||
                        errorMsg.innerText.includes('Kc') ||
                        errorMsg.innerText.includes('Fav')
                    ))) {
                    errorMsg.remove();
                }
            });
            
            let nextElement = parent.nextElementSibling;
            if (nextElement && (nextElement.classList.contains('text-red-600') || nextElement.classList.contains('text-red-400'))) {
                nextElement.remove();
            }
            
            const allErrorMsgs = parent.querySelectorAll('p[class*="text-red"]');
            allErrorMsgs.forEach(msg => {
                if (msg.innerText && (msg.innerText.includes('required') || 
                    msg.innerText.includes('must be') || 
                    msg.innerText.includes('value'))) {
                    msg.remove();
                }
            });
        }
        
        // Находим все поля с ошибками
        const errorFields = document.querySelectorAll('[class*="border-red-500"]');
        
        errorFields.forEach(field => {
            field.classList.add('error-border');
            
            const removeErrorHandler = function() {
                removeErrorFromField(this);
            };
            
            field.addEventListener('input', removeErrorHandler);
            field.addEventListener('focus', removeErrorHandler);
            field.addEventListener('change', removeErrorHandler);
            
            field.hasListener = true;
        });
        
        // Также обрабатываем динамически добавленные поля
        const observer = new MutationObserver(function(mutations) {
            mutations.forEach(function(mutation) {
                if (mutation.type === 'childList') {
                    document.querySelectorAll('[class*="border-red-500"]').forEach(field => {
                        if (!field.hasListener) {
                            field.classList.add('error-border');
                            
                            const removeErrorHandler = function() {
                                removeErrorFromField(this);
                            };
                            
                            field.addEventListener('input', removeErrorHandler);
                            field.addEventListener('focus', removeErrorHandler);
                            field.addEventListener('change', removeErrorHandler);
                            
                            field.hasListener = true;
                        }
                    });
                }
            });
        });
        
        observer.observe(document.body, {
            childList: true,
            subtree: true
        });
        
        // Также добавляем обработчик для удаления ошибок при отправке формы
        const calculatorForm = document.getElementById('calculatorForm');
        if (calculatorForm) {
            calculatorForm.addEventListener('submit', function() {
                document.querySelectorAll('.error-border, [class*="border-red-500"]').forEach(field => {
                    field.classList.remove('error-border', 'border-red-500', 'dark:border-red-500', 'ring-1', 'ring-red-500');
                    
                    let parent = field.parentElement;
                    const errorMessages = parent.querySelectorAll('.text-red-600, .text-red-400');
                    errorMessages.forEach(msg => msg.remove());
                });
            });
        }
    });

    // Показываем сообщения при загрузке страницы
    document.addEventListener('DOMContentLoaded', function() {
        // Проверяем наличие ошибок валидации
        const validationErrorsDiv = document.getElementById('validation-errors-data');
        if (validationErrorsDiv) {
            const errorItems = validationErrorsDiv.querySelectorAll('.error-item');
            if (errorItems.length > 0) {
                setTimeout(() => {
                    showTemporaryMessage('Please check your input fields', 'error');
                }, 500);
            }
            
            const oldNotification = document.getElementById('validation-errors');
            if (oldNotification) {
                oldNotification.remove();
            }
        }
        
        @if(session('success'))
            setTimeout(() => {
                showTemporaryMessage('{{ session('success') }}', 'success');
            }, 500);
        @endif
        
        @if(session('error'))
            setTimeout(() => {
                showTemporaryMessage('{{ session('error') }}', 'error');
            }, 500);
        @endif
    });

// Обновление сетки I7 Multiplet
function updateI7NGrid() {
    let i7n = document.getElementById('i7nSelect').value;
    let i7eGrid = document.getElementById('i7eGrid');
    let kcGrid = document.getElementById('kcGrid');
    
    // Сохраняем текущие значения перед обновлением
    let currentI7e = [];
    let currentKc = [];
    
    document.querySelectorAll('#i7eGrid input').forEach(input => {
        if (input.value) currentI7e.push(input.value);
    });
    
    document.querySelectorAll('#kcGrid input').forEach(input => {
        if (input.value) currentKc.push(input.value);
    });
    
    i7eGrid.innerHTML = '';
    kcGrid.innerHTML = '';
    
    // Получаем ошибки из PHP
    let errors = @json($errors->messages());
    
    for (let i = 0; i < i7n; i++) {
        // Обработка поля i7e
        let hasError = errors && errors[`i7e.${i}`] !== undefined;
        let errorClass = hasError ? 'border-red-500 dark:border-red-500' : 'border-gray-300';
        let errorMessage = hasError ? errors[`i7e.${i}`][0] : '';
        
        i7eGrid.innerHTML += `<div>
            <input type="text" name="i7e[]" 
                class="block w-full rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-800 dark:border-gray-600 dark:text-white ${errorClass}" 
                value="${currentI7e[i] || ''}" 
                placeholder="E${i+1}">
            ${hasError ? `<p class="text-xs text-red-600 dark:text-red-400 mt-1 flex items-start">
                <svg class="h-3 w-3 mr-1 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <span class="break-words">${errorMessage}</span>
            </p>` : ''}
        </div>`;
        
        // Обработка поля Kc
        const isFirst = (i === 0);
        const readonlyAttr = isFirst ? 'readonly' : '';
        const readonlyClass = isFirst ? 'bg-gray-100 dark:bg-gray-600 text-gray-700 dark:text-gray-200 cursor-not-allowed' : '';
        
        let hasKcError = !isFirst && errors && errors[`kc.${i}`] !== undefined;
        let kcErrorClass = hasKcError ? 'border-red-500 dark:border-red-500' : 'border-gray-300';
        let kcErrorMessage = hasKcError ? errors[`kc.${i}`][0] : '';
        
        kcGrid.innerHTML += `<div>
            <input type="text" name="kc[]" 
                class="block w-full rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-800 dark:border-gray-600 dark:text-white ${readonlyClass} ${kcErrorClass}" 
                value="${isFirst ? '1' : (currentKc[i] || '')}" 
                placeholder="Kc${i+1}"
                ${readonlyAttr}>
            ${isFirst ? '<p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Fixed to 1</p>' : ''}
            ${hasKcError ? `<p class="text-xs text-red-600 dark:text-red-400 mt-1 flex items-start">
                <svg class="h-3 w-3 mr-1 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <span class="break-words">${kcErrorMessage}</span>
            </p>` : ''}
        </div>`;
    }
}

// Обновление сетки I8 Multiplet
function updateI8NGrid() {
    let i8n = document.getElementById('i8nSelect').value;
    let i8eGrid = document.getElementById('i8eGrid');
    
    // Сохраняем текущие значения перед обновлением
    let currentI8e = [];
    document.querySelectorAll('#i8eGrid input').forEach(input => {
        if (input.value) currentI8e.push(input.value);
    });
    
    i8eGrid.innerHTML = '';
    
    // Получаем ошибки из PHP (передаем в JavaScript)
    let errors = @json($errors->messages());
    
    for (let i = 0; i < i8n; i++) {
        // Проверяем наличие ошибки для этого поля
        let hasError = errors && errors[`i8e.${i}`] !== undefined;
        let errorClass = hasError ? 'border-red-500 dark:border-red-500' : 'border-gray-300';
        let errorMessage = hasError ? errors[`i8e.${i}`][0] : '';
        
        i8eGrid.innerHTML += `<div>
            <input type="text" name="i8e[]" 
                class="block w-full rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-800 dark:border-gray-600 dark:text-white ${errorClass}" 
                value="${currentI8e[i] || ''}" 
                placeholder="E${i+1}">
            ${hasError ? `<p class="text-xs text-red-600 dark:text-red-400 mt-1 flex items-start">
                <svg class="h-3 w-3 mr-1 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <span class="break-words">${errorMessage}</span>
            </p>` : ''}
        </div>`;
    }
}

    // Функции для экспериментальных данных
    function addExperimentalRow() {
        let tbody = document.getElementById('experimentalBody');
        let rows = tbody.children;
        let lastRow = rows[rows.length - 1];
        
        if (lastRow) {
            let tempInput = lastRow.querySelector('input[name="exp_temperatures[]"]');
            let valInput = lastRow.querySelector('input[name="exp_values[]"]');
            
            if ((!tempInput.value || tempInput.value.trim() === '') && 
                (!valInput.value || valInput.value.trim() === '')) {
                showTemporaryMessage('Please fill the last row first', 'warning');
                return;
            }
        }
        
        let newRow = document.createElement('tr');
        newRow.className = 'experimental-row';
        newRow.innerHTML = `
            <td class="px-3 py-2">
                <input type="text" name="exp_temperatures[]" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-800 dark:border-gray-600 dark:text-white" 
                    placeholder="Input Temperature">
            </td>
            <td class="px-3 py-2">
                <input type="text" name="exp_values[]" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-800 dark:border-gray-600 dark:text-white" 
                    placeholder="Input Lifetime">
            </td>
            <td class="px-3 py-2">
                <button type="button" onclick="removeExperimentalRow(this)" class="inline-flex items-center px-3 py-1 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-500 active:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
                    Remove
                </button>
            </td>
        `;
        tbody.appendChild(newRow);
        
        const newTempInput = newRow.querySelector('input[name="exp_temperatures[]"]');
        const newValInput = newRow.querySelector('input[name="exp_values[]"]');
        newTempInput.addEventListener('change', saveExperimentalDataToSession);
        newTempInput.addEventListener('blur', saveExperimentalDataToSession);
        newValInput.addEventListener('change', saveExperimentalDataToSession);
        newValInput.addEventListener('blur', saveExperimentalDataToSession);
        
        setTimeout(saveExperimentalDataToSession, 100);
    }

    function removeExperimentalRow(button) {
        let row = button.closest('tr');
        let tbody = document.getElementById('experimentalBody');
        
        let rowsWithData = 0;
        Array.from(tbody.children).forEach(r => {
            let tempInput = r.querySelector('input[name="exp_temperatures[]"]');
            let valInput = r.querySelector('input[name="exp_values[]"]');
            if ((tempInput.value && tempInput.value.trim() !== '') || 
                (valInput.value && valInput.value.trim() !== '')) {
                rowsWithData++;
            }
        });
        
        if (rowsWithData === 1) {
            let tempInput = row.querySelector('input[name="exp_temperatures[]"]');
            let valInput = row.querySelector('input[name="exp_values[]"]');
            if (tempInput) tempInput.value = '';
            if (valInput) valInput.value = '';
            showTemporaryMessage('Last row cannot be removed, you can clear it instead', 'info');
            return;
        }
        
        row.remove();
        setTimeout(saveExperimentalDataToSession, 100);
        showTemporaryMessage('Row removed successfully', 'success');
    }

    // Сохранение при изменении полей
    document.addEventListener('DOMContentLoaded', function() {
        const expInputs = document.querySelectorAll('input[name="exp_temperatures[]"], input[name="exp_values[]"]');
        expInputs.forEach(input => {
            input.addEventListener('change', saveExperimentalDataToSession);
            input.addEventListener('blur', saveExperimentalDataToSession);
        });
        
        const firstKcInput = document.querySelector('input[name="kc[]"]');
        if (firstKcInput && !firstKcInput.value) {
            firstKcInput.value = '1';
        }
        
        @if(!$viewingHistory)
        updateI7NGrid();
        updateI8NGrid();
        @endif
    });

// График результатов
@if($results)
let resultsChart = null;
let combinedChart = null;

// Функция для сохранения графика в PNG
async function saveChartAsImage(chart, filename, scale = 2) {
    if (!chart) {
        console.error('Chart not found');
        return;
    }
    
    try {
        const originalCanvas = chart.canvas;
        const isDarkMode = document.documentElement.classList.contains('dark');
        
        // Получаем оригинальные размеры
        const originalWidth = originalCanvas.width;
        const originalHeight = originalCanvas.height;
        
        // Создаем временный canvas с увеличенным разрешением
        const tempCanvas = document.createElement('canvas');
        tempCanvas.width = originalWidth * scale;
        tempCanvas.height = originalHeight * scale;
        const ctx = tempCanvas.getContext('2d');
        
        // Включаем сглаживание для лучшего качества
        ctx.imageSmoothingEnabled = true;
        ctx.imageSmoothingQuality = 'high';
        
        // Заливаем фон
        ctx.fillStyle = isDarkMode ? '#1f2937' : '#ffffff';
        ctx.fillRect(0, 0, tempCanvas.width, tempCanvas.height);
        
        // Масштабируем и рисуем оригинальный график
        ctx.drawImage(originalCanvas, 0, 0, tempCanvas.width, tempCanvas.height);
        
        // Сохраняем
        const link = document.createElement('a');
        link.download = filename || 'chart.png';
        link.href = tempCanvas.toDataURL('image/png');
        link.click();
        
        showTemporaryMessage(`Chart saved as PNG`, 'success');
    } catch (error) {
        console.error('Error saving chart:', error);
        showTemporaryMessage('Error saving chart', 'error');
    }
}

// Функция для экспорта таблицы в CSV (текстовый формат, без автоформатирования дат)
function exportTableToCSV(tableElement, filename) {
    if (!tableElement) {
        console.error('Table element not found');
        return;
    }
    
    try {
        // Получаем все строки таблицы
        const rows = tableElement.querySelectorAll('tr');
        const csvData = [];
        
        // Проходим по каждой строке
        rows.forEach(row => {
            const rowData = [];
            const cells = row.querySelectorAll('th, td');
            
            cells.forEach(cell => {
                // Получаем текст ячейки, убираем лишние пробелы и символы
                let text = cell.innerText.trim();
                
                // Принудительно форматируем как текст в Excel
                // Формула = "значение" заставляет Excel интерпретировать как текст
                text = `="${text.replace(/"/g, '\\"')}"`;
                
                rowData.push(text);
            });
            
            if (rowData.length > 0) {
                // Используем точку с запятой как разделитель для Excel
                csvData.push(rowData.join(';'));
            }
        });
        
        // Создаем CSV строку с разделителями-точками с запятой
        let csvString = csvData.join('\n');
        
        // Добавляем BOM для поддержки UTF-8 и правильного отображения кириллицы
        const blob = new Blob(['\uFEFF' + csvString], { type: 'text/csv;charset=utf-8;' });
        
        // Создаем ссылку для скачивания
        const link = document.createElement('a');
        const url = URL.createObjectURL(blob);
        link.href = url;
        link.setAttribute('download', filename || 'export.csv');
        document.body.appendChild(link);
        link.click();
        document.body.removeChild(link);
        URL.revokeObjectURL(url);
        
        // Показываем уведомление об успехе
        showTemporaryMessage('Data exported successfully', 'success');
    } catch (error) {
        console.error('Error exporting data:', error);
        showTemporaryMessage('Error exporting data', 'error');
    }
}

// Функция для экспорта данных с кастомными заголовками (текстовый формат)
function exportToExcel(headers, data, filename) {
    try {
        const csvRows = [];
        
        // Добавляем заголовки (заголовки можно не оборачивать в формулу)
        csvRows.push(headers.join(';'));
        
        // Добавляем данные
        data.forEach(row => {
            const processedRow = row.map(cell => {
                let text = String(cell).trim();
                // Очищаем от лишних символов
                text = text.replace(/"/g, '""');
                // Оборачиваем в формулу Excel для текстового формата
                return `="${text}"`;
            });
            csvRows.push(processedRow.join(';'));
        });
        
        const csvString = csvRows.join('\n');
        const blob = new Blob(['\uFEFF' + csvString], { type: 'text/csv;charset=utf-8;' });
        const link = document.createElement('a');
        const url = URL.createObjectURL(blob);
        link.href = url;
        link.setAttribute('download', filename || 'export.csv');
        document.body.appendChild(link);
        link.click();
        document.body.removeChild(link);
        URL.revokeObjectURL(url);
        
        showTemporaryMessage('Data exported successfully', 'success');
    } catch (error) {
        console.error('Error exporting data:', error);
        showTemporaryMessage('Error exporting data', 'error');
    }
}

// Функция для экспорта экспериментальных данных (текстовый формат)
function exportExperimentalData() {
    const temps = [];
    const values = [];
    
    document.querySelectorAll('input[name="exp_temperatures[]"]').forEach(input => {
        if (input.value && input.value.trim() !== '') {
            temps.push(input.value.trim());
        }
    });
    
    document.querySelectorAll('input[name="exp_values[]"]').forEach(input => {
        if (input.value && input.value.trim() !== '') {
            values.push(input.value.trim());
        }
    });
    
    if (temps.length === 0) {
        showTemporaryMessage('No experimental data to export', 'error');
        return;
    }
    
    const headers = ['Temperature (K)', 'Lifetime (µs)'];
    const data = temps.map((temp, index) => [temp, values[index] || '']);
    
    exportToExcel(headers, data, 'experimental_data.csv');
}

// Функция для экспорта полной таблицы результатов (текстовый формат)
function exportFullResultsTable() {
    const rows = [];
    const headers = ['T (K)', 'Calculated τ (µs)', 'Experimental τ (µs)', 'Difference (µs)', 'Relative Error (%)'];
    
    // Собираем данные из таблицы на вкладке Results
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
        showTemporaryMessage('No results to export', 'error');
        return;
    }
    
    exportToExcel(headers, rows, 'full_results.csv');
}

// Функция для экспорта таблицы параметров оптимизации (текстовый формат)
function exportOptimizationParamsTable() {
    const tableElement = document.querySelector('#optimization-tab .overflow-x-auto table');
    if (!tableElement) {
        showTemporaryMessage('No optimization parameters table found', 'error');
        return;
    }
    
    exportTableToCSV(tableElement, 'optimization_parameters.csv');
}

function initCharts() {
    // Функция для определения текущей темы
    function isDarkMode() {
        return document.documentElement.classList.contains('dark');
    }
    
    // Функция для получения цветов в зависимости от темы
    function getChartColors() {
        const dark = isDarkMode();
        return {
            axisTitle: dark ? '#e5e7eb' : '#374151',
            axisTicks: dark ? '#e4e7ec' : '#2d343c',
            gridLines: dark ? 'rgba(255, 255, 255, 0.1)' : 'rgba(0, 0, 0, 0.1)',
            axisBorder: dark ? '#4b5563' : '#9ca3af',
            legendText: dark ? '#e5e7eb' : '#1f2937',
            tooltipBackground: dark ? 'rgba(0, 0, 0, 0.9)' : 'rgba(0, 0, 0, 0.8)',
            calculatedLine: dark ? 'rgb(75, 192, 192)' : 'rgb(75, 192, 192)',
            calculatedPoint: dark ? 'rgb(75, 192, 192)' : 'rgb(75, 192, 192)',
            experimentalPoint: dark ? 'rgb(255, 99, 132)' : 'rgb(255, 99, 132)'
        };
    }
    
    // Функция для обновления цветов графика на вкладке Input
    function updateResultsChartColors() {
        if (!resultsChart) return;
        const colors = getChartColors();
        
        // Обновляем цвета осей
        if (resultsChart.options.scales) {
            if (resultsChart.options.scales.x) {
                if (resultsChart.options.scales.x.title) resultsChart.options.scales.x.title.color = colors.axisTitle;
                if (resultsChart.options.scales.x.ticks) resultsChart.options.scales.x.ticks.color = colors.axisTicks;
                if (resultsChart.options.scales.x.grid) {
                    resultsChart.options.scales.x.grid.color = colors.gridLines;
                    resultsChart.options.scales.x.grid.borderColor = colors.axisBorder;
                }
            }
            if (resultsChart.options.scales.y) {
                if (resultsChart.options.scales.y.title) resultsChart.options.scales.y.title.color = colors.axisTitle;
                if (resultsChart.options.scales.y.ticks) resultsChart.options.scales.y.ticks.color = colors.axisTicks;
                if (resultsChart.options.scales.y.grid) {
                    resultsChart.options.scales.y.grid.color = colors.gridLines;
                    resultsChart.options.scales.y.grid.borderColor = colors.axisBorder;
                }
            }
        }
        
        // Обновляем цвета легенды
        if (resultsChart.options.plugins && resultsChart.options.plugins.legend && resultsChart.options.plugins.legend.labels) {
            resultsChart.options.plugins.legend.labels.color = colors.legendText;
        }
        
        // Обновляем цвета тултипа
        if (resultsChart.options.plugins && resultsChart.options.plugins.tooltip) {
            resultsChart.options.plugins.tooltip.backgroundColor = colors.tooltipBackground;
        }
        
        resultsChart.update('none');
    }
    
    // Функция для обновления цветов комбинированного графика
    function updateCombinedChartColors() {
        if (!combinedChart) return;
        const colors = getChartColors();
        
        // Обновляем цвета осей
        if (combinedChart.options.scales) {
            if (combinedChart.options.scales.x) {
                if (combinedChart.options.scales.x.title) combinedChart.options.scales.x.title.color = colors.axisTitle;
                if (combinedChart.options.scales.x.ticks) combinedChart.options.scales.x.ticks.color = colors.axisTicks;
                if (combinedChart.options.scales.x.grid) {
                    combinedChart.options.scales.x.grid.color = colors.gridLines;
                    combinedChart.options.scales.x.grid.borderColor = colors.axisBorder;
                }
            }
            if (combinedChart.options.scales.y) {
                if (combinedChart.options.scales.y.title) combinedChart.options.scales.y.title.color = colors.axisTitle;
                if (combinedChart.options.scales.y.ticks) combinedChart.options.scales.y.ticks.color = colors.axisTicks;
                if (combinedChart.options.scales.y.grid) {
                    combinedChart.options.scales.y.grid.color = colors.gridLines;
                    combinedChart.options.scales.y.grid.borderColor = colors.axisBorder;
                }
            }
        }
        
        // Обновляем цвета легенды
        if (combinedChart.options.plugins && combinedChart.options.plugins.legend && combinedChart.options.plugins.legend.labels) {
            combinedChart.options.plugins.legend.labels.color = colors.legendText;
        }
        
        // Обновляем цвета датасетов
        if (combinedChart.data && combinedChart.data.datasets) {
            combinedChart.data.datasets.forEach(dataset => {
                if (dataset.label === 'Calculated Lifetime (µs)') {
                    dataset.borderColor = colors.calculatedLine;
                    dataset.backgroundColor = colors.calculatedLine + '20';
                }
                if (dataset.label === 'Experimental Data (µs)') {
                    dataset.borderColor = colors.experimentalPoint;
                    dataset.backgroundColor = colors.experimentalPoint;
                    dataset.pointBackgroundColor = colors.experimentalPoint;
                    dataset.pointBorderColor = colors.experimentalPoint;
                }
            });
        }
        
        combinedChart.update('none');
    }
    
    // Проверяем, виден ли canvas
    function isCanvasVisible(canvasId) {
        const canvas = document.getElementById(canvasId);
        if (!canvas) return false;
        const rect = canvas.getBoundingClientRect();
        return rect.width > 0 && rect.height > 0;
    }
    
    // Сохраняем флаг, нужно ли пересоздавать график на Input вкладке
    const isInputTabVisible = document.getElementById('input-tab') && 
                              !document.getElementById('input-tab').classList.contains('hidden');
    
    // Уничтожаем старые графики только если они существуют и нужно пересоздать
    if (resultsChart) {
        // Если график на Input вкладке видим, пересоздаем, иначе просто обновим цвета позже
        if (isInputTabVisible) {
            resultsChart.destroy();
            resultsChart = null;
        }
    }
    
    if (combinedChart) {
        combinedChart.destroy();
        combinedChart = null;
    }
    
    const colors = getChartColors();
    
    // График на вкладке Input - создаем только если видим или принудительно
    const ctx = document.getElementById('resultsChart');
    if (ctx && !resultsChart) {
        resultsChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: {!! json_encode($results['temperatures']) !!},
                datasets: [{
                    label: 'Calculated Lifetime (µs)',
                    data: {!! json_encode($results['delta']) !!},
                    borderColor: colors.calculatedLine,
                    backgroundColor: colors.calculatedLine + '20',
                    tension: 0.1,
                    fill: false,
                    pointRadius: 0,
                    pointHoverRadius: 0,
                    pointBackgroundColor: 'transparent',
                    pointBorderColor: 'transparent'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: true,
                plugins: {
                    legend: {
                        display: true,
                        position: 'top',
                        labels: {
                            usePointStyle: true,
                            color: colors.legendText,
                            font: { size: 12 }
                        }
                    },
                    tooltip: {
                        backgroundColor: colors.tooltipBackground,
                        titleColor: '#fff',
                        bodyColor: '#fff',
                        borderColor: 'rgba(255, 255, 255, 0.2)',
                        borderWidth: 1,
                        callbacks: {
                            title: function(context) {
                                return `Temperature: ${context[0].label} K`;
                            },
                            label: function(context) {
                                return `Lifetime: ${context.parsed.y.toFixed(3)} µs`;
                            }
                        }
                    }
                },
                scales: {
                    x: {
                        title: {
                            display: true,
                            text: 'Temperature (K)',
                            font: { weight: 'bold', size: 12 },
                            color: colors.axisTitle
                        },
                        ticks: {
                            color: colors.axisTicks,
                            font: { size: 11 }
                        },
                        grid: {
                            color: colors.gridLines,
                            borderColor: colors.axisBorder
                        }
                    },
                    y: {
                        title: {
                            display: true,
                            text: 'Lifetime (µs)',
                            font: { weight: 'bold', size: 12 },
                            color: colors.axisTitle
                        },
                        ticks: {
                            color: colors.axisTicks,
                            font: { size: 11 },
                            callback: function(value) {
                                return value.toFixed(1) + ' µs';
                            }
                        },
                        grid: {
                            color: colors.gridLines,
                            borderColor: colors.axisBorder
                        },
                        beginAtZero: false
                    }
                }
            }
        });
    }
    
    // Комбинированный график на вкладке Results
    const combinedCtx = document.getElementById('combinedChart');
    if (combinedCtx && !combinedChart) {
        const expData = {!! json_encode($experimentalData) !!};
        const calcTemps = {!! json_encode($results['temperatures']) !!};
        const calcValues = {!! json_encode($results['delta']) !!};
        
        expData.sort((a, b) => a.temperature - b.temperature);
        
        const calcDataPoints = calcTemps.map((temp, index) => ({
            x: temp,
            y: calcValues[index]
        }));
        
        const expDataPoints = expData.map(d => ({
            x: d.temperature,
            y: d.value
        }));
        
        const isMobile = window.innerWidth < 640;
        const isTablet = window.innerWidth >= 640 && window.innerWidth < 1024;
        
        const pointSizes = {
            calculated: 0,
            experimental: isMobile ? 5 : (isTablet ? 6 : 7)
        };
        
        const fontSizes = {
            title: isMobile ? 10 : (isTablet ? 11 : 12),
            ticks: isMobile ? 9 : (isTablet ? 10 : 11),
            legend: isMobile ? 10 : (isTablet ? 11 : 12)
        };
        
        const lineWidth = isMobile ? 1.5 : 2;
        
        combinedChart = new Chart(combinedCtx, {
            type: 'scatter',
            data: {
                datasets: [
                    {
                        label: 'Calculated Lifetime (µs)',
                        data: calcDataPoints,
                        borderColor: colors.calculatedLine,
                        backgroundColor: colors.calculatedLine + '20',
                        type: 'line',
                        tension: 0.1,
                        fill: false,
                        showLine: true,
                        pointRadius: 0,
                        pointHoverRadius: 0,
                        pointBackgroundColor: 'transparent',
                        pointBorderColor: 'transparent',
                        borderWidth: lineWidth,
                        pointBorderWidth: 0
                    },
                    {
                        label: 'Experimental Data (µs)',
                        data: expDataPoints,
                        borderColor: colors.experimentalPoint,
                        backgroundColor: colors.experimentalPoint,
                        type: 'scatter',
                        pointRadius: pointSizes.experimental,
                        pointHoverRadius: pointSizes.experimental + 2,
                        showLine: false,
                        pointBackgroundColor: colors.experimentalPoint,
                        pointBorderColor: colors.experimentalPoint,
                        pointBorderWidth: isMobile ? 1 : 2
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: true,
                aspectRatio: isMobile ? 1 : (isTablet ? 1.2 : 1.5),
                plugins: {
                    legend: {
                        display: true,
                        position: isMobile ? 'bottom' : 'top',
                        labels: {
                            usePointStyle: true,
                            pointStyle: 'circle',
                            color: colors.legendText,
                            font: { size: fontSizes.legend, weight: isMobile ? 'normal' : 'bold' },
                            boxWidth: isMobile ? 10 : 12,
                            padding: isMobile ? 8 : 10
                        }
                    },
                    tooltip: {
                        backgroundColor: colors.tooltipBackground,
                        titleColor: '#fff',
                        bodyColor: '#fff',
                        borderColor: 'rgba(255, 255, 255, 0.2)',
                        borderWidth: 1,
                        padding: isMobile ? 6 : 10,
                        cornerRadius: 4,
                        displayColors: true,
                        boxPadding: 5,
                        titleFont: { size: isMobile ? 11 : 12 },
                        bodyFont: { size: isMobile ? 10 : 11 },
                        callbacks: {
                            title: () => '',
                            label: (context) => {
                                const xValue = context.parsed.x;
                                const yValue = context.parsed.y;
                                if (isMobile) {
                                    return [`${xValue.toFixed(0)} K`, `${yValue.toFixed(2)} µs`];
                                }
                                return [`Temperature: ${xValue.toFixed(1)} K`, `Lifetime: ${yValue.toFixed(3)} µs`];
                            }
                        }
                    }
                },
                scales: {
                    x: {
                        type: 'linear',
                        position: 'bottom',
                        title: {
                            display: true,
                            text: isMobile ? 'T (K)' : 'Temperature (K)',
                            font: { weight: isMobile ? 'normal' : 'bold', size: fontSizes.title },
                            color: colors.axisTitle,
                            padding: { top: isMobile ? 5 : 10 }
                        },
                        ticks: {
                            color: colors.axisTicks,
                            font: { size: fontSizes.ticks },
                            callback: (value) => isMobile ? value.toFixed(0) : value.toFixed(0) + ' K',
                            maxTicksLimit: isMobile ? 5 : 8,
                            autoSkip: true,
                            stepSize: isMobile ? undefined : 50
                        },
                        grid: {
                            color: colors.gridLines,
                            borderColor: colors.axisBorder,
                            drawBorder: true,
                            drawOnChartArea: true,
                            lineWidth: isMobile ? 0.5 : 1
                        }
                    },
                    y: {
                        title: {
                            display: true,
                            text: isMobile ? 'τ (µs)' : 'Lifetime (µs)',
                            font: { weight: isMobile ? 'normal' : 'bold', size: fontSizes.title },
                            color: colors.axisTitle,
                            padding: { bottom: isMobile ? 5 : 10 }
                        },
                        ticks: {
                            color: colors.axisTicks,
                            font: { size: fontSizes.ticks },
                            callback: (value) => isMobile ? value.toFixed(0) : value.toFixed(1) + ' µs',
                            maxTicksLimit: isMobile ? 5 : 8,
                            autoSkip: true
                        },
                        grid: {
                            color: colors.gridLines,
                            borderColor: colors.axisBorder,
                            drawBorder: true,
                            drawOnChartArea: true,
                            lineWidth: isMobile ? 0.5 : 1
                        },
                        beginAtZero: false
                    }
                },
                elements: {
                    point: {
                        hoverRadius: pointSizes.experimental + 2,
                        hoverBorderWidth: isMobile ? 1 : 2,
                        hoverBorderColor: '#fff'
                    },
                    line: {
                        tension: 0.1,
                        borderWidth: lineWidth
                    }
                },
                layout: {
                    padding: {
                        top: isMobile ? 5 : 10,
                        bottom: isMobile ? 5 : 10,
                        left: isMobile ? 5 : 10,
                        right: isMobile ? 5 : 10
                    }
                },
                parsing: false,
                normalized: true
            }
        });
    }
    
    // Если график на Input вкладке не был пересоздан, просто обновляем его цвета
    if (resultsChart && !isInputTabVisible) {
        updateResultsChartColors();
    }
}

// Обновленный слушатель изменения темы
const themeObserver = new MutationObserver(function(mutations) {
    mutations.forEach(function(mutation) {
        if (mutation.attributeName === 'class') {
            // Обновляем цвета обоих графиков без пересоздания
            if (resultsChart) {
                updateResultsChartColors();
            }
            if (combinedChart) {
                updateCombinedChartColors();
            }
        }
    });
});

// Функции обновления цветов (глобальные)
function updateResultsChartColors() {
    if (!resultsChart) return;
    const dark = document.documentElement.classList.contains('dark');
    const colors = {
        axisTitle: dark ? '#e5e7eb' : '#374151',
        axisTicks: dark ? '#e4e7ec' : '#2d343c',
        gridLines: dark ? 'rgba(255, 255, 255, 0.1)' : 'rgba(0, 0, 0, 0.1)',
        axisBorder: dark ? '#4b5563' : '#9ca3af',
        legendText: dark ? '#e5e7eb' : '#1f2937',
        tooltipBackground: dark ? 'rgba(0, 0, 0, 0.9)' : 'rgba(0, 0, 0, 0.8)'
    };
    
    if (resultsChart.options.scales) {
        if (resultsChart.options.scales.x) {
            if (resultsChart.options.scales.x.title) resultsChart.options.scales.x.title.color = colors.axisTitle;
            if (resultsChart.options.scales.x.ticks) resultsChart.options.scales.x.ticks.color = colors.axisTicks;
            if (resultsChart.options.scales.x.grid) {
                resultsChart.options.scales.x.grid.color = colors.gridLines;
                resultsChart.options.scales.x.grid.borderColor = colors.axisBorder;
            }
        }
        if (resultsChart.options.scales.y) {
            if (resultsChart.options.scales.y.title) resultsChart.options.scales.y.title.color = colors.axisTitle;
            if (resultsChart.options.scales.y.ticks) resultsChart.options.scales.y.ticks.color = colors.axisTicks;
            if (resultsChart.options.scales.y.grid) {
                resultsChart.options.scales.y.grid.color = colors.gridLines;
                resultsChart.options.scales.y.grid.borderColor = colors.axisBorder;
            }
        }
    }
    
    if (resultsChart.options.plugins && resultsChart.options.plugins.legend && resultsChart.options.plugins.legend.labels) {
        resultsChart.options.plugins.legend.labels.color = colors.legendText;
    }
    
    if (resultsChart.options.plugins && resultsChart.options.plugins.tooltip) {
        resultsChart.options.plugins.tooltip.backgroundColor = colors.tooltipBackground;
    }
    
    resultsChart.update('none');
}

function updateCombinedChartColors() {
    if (!combinedChart) return;
    const dark = document.documentElement.classList.contains('dark');
    const colors = {
        axisTitle: dark ? '#e5e7eb' : '#374151',
        axisTicks: dark ? '#e4e7ec' : '#2d343c',
        gridLines: dark ? 'rgba(255, 255, 255, 0.1)' : 'rgba(0, 0, 0, 0.1)',
        axisBorder: dark ? '#4b5563' : '#9ca3af',
        legendText: dark ? '#e5e7eb' : '#1f2937',
        calculatedLine: dark ? 'rgb(75, 192, 192)' : 'rgb(75, 192, 192)',
        experimentalPoint: dark ? 'rgb(255, 99, 132)' : 'rgb(255, 99, 132)'
    };
    
    if (combinedChart.options.scales) {
        if (combinedChart.options.scales.x) {
            if (combinedChart.options.scales.x.title) combinedChart.options.scales.x.title.color = colors.axisTitle;
            if (combinedChart.options.scales.x.ticks) combinedChart.options.scales.x.ticks.color = colors.axisTicks;
            if (combinedChart.options.scales.x.grid) {
                combinedChart.options.scales.x.grid.color = colors.gridLines;
                combinedChart.options.scales.x.grid.borderColor = colors.axisBorder;
            }
        }
        if (combinedChart.options.scales.y) {
            if (combinedChart.options.scales.y.title) combinedChart.options.scales.y.title.color = colors.axisTitle;
            if (combinedChart.options.scales.y.ticks) combinedChart.options.scales.y.ticks.color = colors.axisTicks;
            if (combinedChart.options.scales.y.grid) {
                combinedChart.options.scales.y.grid.color = colors.gridLines;
                combinedChart.options.scales.y.grid.borderColor = colors.axisBorder;
            }
        }
    }
    
    if (combinedChart.options.plugins && combinedChart.options.plugins.legend && combinedChart.options.plugins.legend.labels) {
        combinedChart.options.plugins.legend.labels.color = colors.legendText;
    }
    
    if (combinedChart.data && combinedChart.data.datasets) {
        combinedChart.data.datasets.forEach(dataset => {
            if (dataset.label === 'Calculated Lifetime (µs)') {
                dataset.borderColor = colors.calculatedLine;
                dataset.backgroundColor = colors.calculatedLine + '20';
            }
            if (dataset.label === 'Experimental Data (µs)') {
                dataset.borderColor = colors.experimentalPoint;
                dataset.backgroundColor = colors.experimentalPoint;
                dataset.pointBackgroundColor = colors.experimentalPoint;
                dataset.pointBorderColor = colors.experimentalPoint;
            }
        });
    }
    
    combinedChart.update('none');
}

// Инициализация
document.addEventListener('DOMContentLoaded', function() {
    initCharts();
    
    // Обработчик переключения вкладок для корректировки размера графика
    const tabButtons = document.querySelectorAll('.tab-button');
    tabButtons.forEach(button => {
        button.addEventListener('click', function() {
            const tabId = this.getAttribute('data-tab');
            setTimeout(() => {
                if (tabId === 'input' && resultsChart) {
                    resultsChart.resize();
                    resultsChart.update();
                }
                if (tabId === 'results' && combinedChart) {
                    combinedChart.resize();
                    combinedChart.update();
                }
            }, 150);
        });
    });
});

themeObserver.observe(document.documentElement, {
    attributes: true,
    attributeFilter: ['class']
});
@endif
</script>
</x-app-layout>
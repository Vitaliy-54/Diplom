<?php
// Этот файл должен быть размещен в: resources/views/calculator/nd3.blade.php
// Или в зависимости от вашей структуры маршрутов
?>

<?php if (isset($component)) { $__componentOriginal9ac128a9029c0e4701924bd2d73d7f54 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54 = $attributes; } ?>
<?php $component = App\View\Components\AppLayout::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('app-layout'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\App\View\Components\AppLayout::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
     <?php $__env->slot('header', null, []); ?> 
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                <?php echo e(__('Nd³⁺ Lifetime Calculator')); ?>

            </h2>
            <div class="text-sm text-gray-800 dark:text-gray-400 animate__animated animate__fadeIn">
                <?php echo e(now()->translatedFormat('d F Y')); ?>

            </div>
        </div>
     <?php $__env->endSlot(); ?>

     <?php $__env->slot('title', null, []); ?> 
        <?php echo e(__('Nd³⁺ Lifetime Calculator')); ?>

     <?php $__env->endSlot(); ?>

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
                <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">

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
<?php if(session('info')): ?>
    <div class="mb-4 rounded-md bg-blue-200 dark:bg-blue-900 p-4 relative" id="info-notification" data-auto-close="false">
        <div class="flex">
            <div class="flex-shrink-0">
                <svg class="h-5 w-5 text-blue-400" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
                </svg>
            </div>
            <div class="ml-3">
                <p class="text-sm font-medium text-blue-800 dark:text-blue-200">
                    <?php echo session('info'); ?>

                </p>
            </div>
            <button onclick="closeNotification('info-notification')" class="absolute top-2 right-2 text-blue-600 dark:text-blue-400 hover:text-blue-800 dark:hover:text-blue-200 transition-colors duration-150">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>
    </div>
<?php endif; ?>

<!-- Краткое сообщение об ошибках валидации -->
<?php if($errors->any()): ?>
    <div id="validation-errors-data" style="display: none;">
        <div class="errors-list">
            <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="error-item"><?php echo e($error); ?></div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
    </div>
<?php endif; ?>

                <!-- Контент вкладок -->
                <div class="tab-content">
                    <!-- ОДНА ГЛАВНАЯ ФОРМА -->
                    <form id="calculatorForm" method="POST" action="<?php echo e(route('calculator.calculate')); ?>">
                        <?php echo csrf_field(); ?>
                        
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
                                                   value="<?php echo e(old('calculation_name', $input['calculation_name'] ?? '')); ?>" 
                                                   placeholder="e.g., My calculation"
                                                   <?php echo e($viewingHistory ? 'disabled' : ''); ?>>
                                        </div>
                                    </div>
                                    
                                    <!-- 4F3.2 Multiplet -->
                                    <div class="bg-gray-200 dark:bg-gray-700 rounded-lg p-4 mb-4">
                                        <h5 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-3">4F3.2 Multiplet</h5>
                                        <div class="mb-3">
                                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Number of components:</label>
                                            <select name="fn" class="block w-48 rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-800 dark:border-gray-600 dark:text-white" id="fnSelect" onchange="updateFNGrid()" <?php echo e($viewingHistory ? 'disabled' : ''); ?>>
                                                <?php for($i = 1; $i <= 12; $i++): ?>
                                                    <?php
                                                        $selected = false;
                                                        if (old('fn') !== null) {
                                                            $selected = old('fn') == $i;
                                                        } elseif (isset($input['fn'])) {
                                                            $selected = $input['fn'] == $i;
                                                        } else {
                                                            $selected = ($i == 2);
                                                        }
                                                    ?>
                                                    <option value="<?php echo e($i); ?>" <?php echo e($selected ? 'selected' : ''); ?>><?php echo e($i); ?></option>
                                                <?php endfor; ?>
                                            </select>
                                        </div>
                                        
                                        <div class="mb-3">
                                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Energies (cm⁻¹):</label>
                                            <div class="grid grid-cols-3 md:grid-cols-4 gap-2" id="feGrid">
                                                <?php
                                                    $fn = old('fn', $input['fn'] ?? 2);
                                                    $fe = $input['fe'] ?? [];
                                                ?>
                                                <?php for($i = 0; $i < $fn; $i++): ?>
                                                    <div>
                                                        <input type="text" name="fe[]" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-800 dark:border-gray-600 dark:text-white
                                                        <?php echo e($errors->has('fe.' . $i) ? 'border-red-500 dark:border-red-500' : 'border-gray-300'); ?>"
                                                        value="<?php echo e(old('fe.' . $i, $fe[$i] ?? '')); ?>"
                                                        placeholder="E<?php echo e($i+1); ?>"
                                                        <?php echo e($viewingHistory ? 'disabled' : ''); ?>>
                                                        <?php if($errors->has('fe.' . $i)): ?>
                                                            <p class="text-xs text-red-600 dark:text-red-400 mt-1 flex items-start">
                                                                <svg class="h-3 w-3 mr-1 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                                </svg>
                                                                <span class="break-words">Energy value required</span>
                                                            </p>
                                                        <?php endif; ?>
                                                    </div>
                                                <?php endfor; ?>
                                            </div>
                                        </div>
                                        
                                        <!-- Для Kc значений -->
                                        <div class="mb-3">
                                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Coefficients Kc:</label>
                                            <div class="grid grid-cols-3 md:grid-cols-4 gap-2" id="kcGrid">
                                                <?php
                                                    $kc = $input['kc'] ?? [];
                                                    $kc_original = $inputOriginal['kc'] ?? [];
                                                ?>
                                                <?php for($i = 0; $i < $fn; $i++): ?>
                                                    <div>
                                                        <input type="text" name="kc[]" 
                                                            class="block w-full rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-800 dark:border-gray-600 dark:text-white
                                                            <?php echo e($errors->has('kc.' . $i) ? 'border-red-500 dark:border-red-500 ring-1 ring-red-500' : 'border-gray-300'); ?>

                                                            <?php echo e($i == 0 ? 'bg-gray-100 dark:bg-gray-600 text-gray-700 dark:text-gray-200 cursor-not-allowed' : ''); ?>"
                                                            value="<?php echo e(old('kc.' . $i, $kc[$i] ?? ($i == 0 ? '1' : ''))); ?>"
                                                            placeholder="Kc<?php echo e($i+1); ?>"
                                                            <?php echo e($i == 0 ? 'readonly' : ''); ?>

                                                            <?php echo e($viewingHistory ? 'disabled' : ''); ?>>
                                                        <?php if($errors->has('kc.' . $i)): ?>
                                                            <p class="text-xs text-red-600 dark:text-red-400 mt-1 flex items-start">
                                                                <svg class="h-3 w-3 mr-1 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                                </svg>
                                                                <span class="break-words">Kc coefficient required</span>
                                                            </p>
                                                        <?php endif; ?>
                                                        <?php if($i == 0): ?>
                                                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Fixed to 1</p>
                                                        <?php endif; ?>
                                                    </div>
                                                <?php endfor; ?>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- 4J9/2 Multiplet -->
                                    <div class="bg-gray-200 dark:bg-gray-700 rounded-lg p-4 mb-4">
                                        <h5 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-3">4J9.2 Multiplet</h5>
                                        <div class="mb-3">
                                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Number of components:</label>
                                            <select name="j9n" class="block w-48 rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-800 dark:border-gray-600 dark:text-white" id="j9nSelect" onchange="updateJ9NGrid()" <?php echo e($viewingHistory ? 'disabled' : ''); ?>>
                                                <?php for($i = 1; $i <= 13; $i++): ?>
                                                    <?php
                                                        $selected = false;
                                                        if (old('j9n') !== null) {
                                                            $selected = old('j9n') == $i;
                                                        } elseif (isset($input['j9n'])) {
                                                            $selected = $input['j9n'] == $i;
                                                        } else {
                                                            $selected = ($i == 5);
                                                        }
                                                    ?>
                                                    <option value="<?php echo e($i); ?>" <?php echo e($selected ? 'selected' : ''); ?>><?php echo e($i); ?></option>
                                                <?php endfor; ?>
                                            </select>
                                        </div>
                                        
                                        <div class="mb-3">
                                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Energies (cm⁻¹):</label>
                                            <div class="grid grid-cols-3 md:grid-cols-4 gap-2" id="j9eGrid">
                                                <?php
                                                    $j9n = old('j9n', $input['j9n'] ?? 5);
                                                    $j9e = $input['j9e'] ?? [];
                                                ?>
                                                <?php for($i = 0; $i < $j9n; $i++): ?>
                                                    <div>
                                                        <input type="text" name="j9e[]" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-800 dark:border-gray-600 dark:text-white
                                                        <?php echo e($errors->has('j9e.' . $i) ? 'border-red-500 dark:border-red-500' : 'border-gray-300'); ?>" 
                                                        value="<?php echo e(old('j9e.' . $i, $j9e[$i] ?? '')); ?>" 
                                                        placeholder="E<?php echo e($i+1); ?>"
                                                        <?php echo e($viewingHistory ? 'disabled' : ''); ?>>
                                                        <?php if($errors->has('j9e.' . $i)): ?>
                                                            <p class="text-xs text-red-600 dark:text-red-400 mt-1 flex items-start">
                                                                <svg class="h-3 w-3 mr-1 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                                </svg>
                                                                <span class="break-words">Energy value required</span>
                                                            </p>
                                                        <?php endif; ?>
                                                    </div>
                                                <?php endfor; ?>
                                            </div>
                                        </div>
                                        
                                        <div class="mb-3">
                                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Coefficient J9C:</label>
                                            <input type="text" name="j9c" 
                                                class="block w-48 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-800 dark:border-gray-600 dark:text-white
                                                <?php echo e($errors->has('j9c') ? 'border-red-500 dark:border-red-500 ring-1 ring-red-500' : 'border-gray-300'); ?>" 
                                                value="<?php echo e(old('j9c', $input['j9c'] ?? '')); ?>"
                                                placeholder="Enter coefficient"
                                                <?php echo e($viewingHistory ? 'disabled' : ''); ?>>
                                            <?php if($errors->has('j9c')): ?>
                                                <p class="text-xs text-red-600 dark:text-red-400 mt-1 flex items-center">
                                                    <svg class="h-3 w-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                    </svg>
                                                    J9C coefficient is required
                                                </p>
                                            <?php endif; ?>
                                        </div>
                                    </div>

                                    <!-- 4J11/2 Multiplet -->
                                    <div class="bg-gray-200 dark:bg-gray-700 rounded-lg p-4 mb-4">
                                        <h5 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-3">4J11.2 Multiplet</h5>
                                        <div class="mb-3">
                                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Number of components:</label>
                                            <select name="j11n" class="block w-48 rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-800 dark:border-gray-600 dark:text-white" id="j11nSelect" onchange="updateJ11NGrid()" <?php echo e($viewingHistory ? 'disabled' : ''); ?>>
                                                <?php for($i = 1; $i <= 13; $i++): ?>
                                                    <?php
                                                        $selected = false;
                                                        if (old('j11n') !== null) {
                                                            $selected = old('j11n') == $i;
                                                        } elseif (isset($input['j11n'])) {
                                                            $selected = $input['j11n'] == $i;
                                                        } else {
                                                            $selected = ($i == 6);
                                                        }
                                                    ?>
                                                    <option value="<?php echo e($i); ?>" <?php echo e($selected ? 'selected' : ''); ?>><?php echo e($i); ?></option>
                                                <?php endfor; ?>
                                            </select>
                                        </div>
                                        
                                        <div class="mb-3">
                                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Energies (cm⁻¹):</label>
                                            <div class="grid grid-cols-3 md:grid-cols-4 gap-2" id="j11eGrid">
                                                <?php
                                                    $j11n = old('j11n', $input['j11n'] ?? 6);
                                                    $j11e = $input['j11e'] ?? [];
                                                ?>
                                                <?php for($i = 0; $i < $j11n; $i++): ?>
                                                    <div>
                                                        <input type="text" name="j11e[]" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-800 dark:border-gray-600 dark:text-white
                                                        <?php echo e($errors->has('j11e.' . $i) ? 'border-red-500 dark:border-red-500' : 'border-gray-300'); ?>" 
                                                        value="<?php echo e(old('j11e.' . $i, $j11e[$i] ?? '')); ?>" 
                                                        placeholder="E<?php echo e($i+1); ?>"
                                                        <?php echo e($viewingHistory ? 'disabled' : ''); ?>>
                                                        <?php if($errors->has('j11e.' . $i)): ?>
                                                            <p class="text-xs text-red-600 dark:text-red-400 mt-1 flex items-start">
                                                                <svg class="h-3 w-3 mr-1 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                                </svg>
                                                                <span class="break-words">Energy value required</span>
                                                            </p>
                                                        <?php endif; ?>
                                                    </div>
                                                <?php endfor; ?>
                                            </div>
                                        </div>
                                        
                                        <div class="mb-3">
                                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Coefficient J11C:</label>
                                            <input type="text" name="j11c" 
                                                class="block w-48 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-800 dark:border-gray-600 dark:text-white
                                                <?php echo e($errors->has('j11c') ? 'border-red-500 dark:border-red-500 ring-1 ring-red-500' : 'border-gray-300'); ?>" 
                                                value="<?php echo e(old('j11c', $input['j11c'] ?? '')); ?>"
                                                placeholder="Enter coefficient"
                                                <?php echo e($viewingHistory ? 'disabled' : ''); ?>>
                                            <?php if($errors->has('j11c')): ?>
                                                <p class="text-xs text-red-600 dark:text-red-400 mt-1 flex items-center">
                                                    <svg class="h-3 w-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                    </svg>
                                                    J11C coefficient is required
                                                </p>
                                            <?php endif; ?>
                                        </div>
                                    </div>

                                    <!-- Fav parameter -->
                                    <div class="bg-gray-200 dark:bg-gray-700 rounded-lg p-4 mb-4">
                                        <div class="flex items-center space-x-4">
                                            <label class="text-sm font-medium text-gray-700 dark:text-gray-300">Fav =</label>
                                            <input type="text" name="fav" 
                                                class="block w-48 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-800 dark:border-gray-600 dark:text-white
                                                <?php echo e($errors->has('fav') ? 'border-red-500 dark:border-red-500 ring-1 ring-red-500' : 'border-gray-300'); ?>" 
                                                value="<?php echo e(old('fav', $input['fav'] ?? '')); ?>"
                                                placeholder="Enter Fav value"
                                                <?php echo e($viewingHistory ? 'disabled' : ''); ?>>
                                        </div>
                                        <?php if($errors->has('fav')): ?>
                                            <p class="text-xs text-red-600 dark:text-red-400 mt-1 flex items-center">
                                                <svg class="h-3 w-3 mr-1 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                </svg>
                                                <?php if(old('fav') === null || old('fav') === ''): ?>
                                                    Fav parameter is required
                                                <?php else: ?>
                                                    Fav must be a number
                                                <?php endif; ?>
                                            </p>
                                        <?php endif; ?>
                                    </div>

                                    <!-- Кнопки управления -->
                                    <div class="flex flex-wrap gap-2">
                                        <?php if(!$viewingHistory): ?>
                                            <button type="submit" class="inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-500 active:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
                                                Calculate
                                            </button>
                                            <button type="button" onclick="submitOptimization()" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-500 active:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
                                                Optimize
                                            </button>
                                        <?php endif; ?>
                                        
                                        <?php if($viewingHistory): ?>
                                            <button type="button" onclick="exitHistoryView()" class="inline-flex items-center px-4 py-2 bg-purple-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-purple-500 active:bg-purple-700 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
                                                Edit Mode
                                            </button>
                                        <?php else: ?>
                                            <button type="button" onclick="clearForm()" class="inline-flex items-center px-4 py-2 bg-yellow-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-yellow-500 active:bg-yellow-700 focus:outline-none focus:ring-2 focus:ring-yellow-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
                                                Clear Input Data
                                            </button>
                                        <?php endif; ?>
                                    </div>
                                </div>

                                <!-- Правая колонка - результаты расчетов -->
                                <div>
                                    <?php if($results): ?>
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
                                            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-600" id="resultsTable">
                                                <thead class="bg-gray-100 dark:bg-gray-800 sticky top-0">
                                                    <tr>
                                                        <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">T, K</th>
                                                        <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">W×10⁻³, s⁻¹</th>
                                                        <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Lifetime, µs</th>
                                                    </tr>
                                                </thead>
                                                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                                    <?php $__currentLoopData = $results['temperatures']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $T): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                    <tr>
                                                        <td class="px-3 py-2 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100"><?php echo e(number_format(floatval($T), 1, '.', '')); ?></td>
                                                        <td class="px-3 py-2 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100"><?php echo e(number_format(floatval($results['w'][$index]), 6, '.', '')); ?></td>
                                                        <td class="px-3 py-2 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100"><?php echo e(number_format(floatval($results['delta'][$index]), 6, '.', '')); ?></td>
                                                    </tr>
                                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                    <?php endif; ?>
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
                                        <?php if(!$viewingHistory): ?>
                                            <button type="button" onclick="addExperimentalRow()" class="inline-flex items-center px-4 py-2 bg-cyan-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-cyan-500 active:bg-cyan-700 focus:outline-none focus:ring-2 focus:ring-cyan-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
                                                Add Row
                                            </button>
                                        <?php endif; ?>
                                    </div>
                                    <div>
                                        <?php if($results): ?>
                                        <button type="button" onclick="exportExperimentalData()" 
                                                class="inline-flex items-center px-3 py-1.5 bg-green-600 hover:bg-green-700 text-white text-xs font-medium rounded-md transition-colors duration-150">
                                            <svg class="h-4 w-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                                            </svg>
                                            Export Table to CSV
                                        </button>
                                        <?php endif; ?>
                                    </div>
                                </div>

                                <div class="overflow-x-auto">
                                    <table class="min-w-full divide-y divide-gray-400 dark:divide-gray-600" id="experimentalTable">
                                        <thead class="bg-gray-400 dark:bg-gray-800">
                                            <tr>
                                                <th class="px-3 py-2 text-left text-xs font-medium text-white dark:text-gray-400 uppercase tracking-wider">Temperature (K)</th>
                                                <th class="px-3 py-2 text-left text-xs font-medium text-white dark:text-gray-400 uppercase tracking-wider">Lifetime (µs)</th>
                                                <?php if(!$viewingHistory): ?>
                                                    <th class="px-3 py-2 text-left text-xs font-medium text-white dark:text-gray-400 uppercase tracking-wider">Action</th>
                                                <?php endif; ?>
                                            </tr>
                                        </thead>
                                        <tbody id="experimentalBody" class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                            <?php
                                                $expTemps = old('exp_temperatures', $experimentalInput['exp_temperatures'] ?? []);
                                                $expVals = old('exp_values', $experimentalInput['exp_values'] ?? []);
                                                $rowCount = !empty($expTemps) ? count($expTemps) : 1;
                                            ?>
                                            
                                            <?php for($i = 0; $i < $rowCount; $i++): ?>
                                            <tr class="experimental-row">
                                                <td class="px-3 py-2">
                                                    <input type="text" name="exp_temperatures[]" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-800 dark:border-gray-600 dark:text-white" 
                                                        value="<?php echo e($expTemps[$i] ?? ''); ?>" 
                                                        placeholder="Input Temperature"
                                                        <?php echo e($viewingHistory ? 'disabled' : ''); ?>>
                                                  </td>
                                                <td class="px-3 py-2">
                                                    <input type="text" name="exp_values[]" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-800 dark:border-gray-600 dark:text-white" 
                                                        value="<?php echo e($expVals[$i] ?? ''); ?>" 
                                                        placeholder="Input Lifetime"
                                                        <?php echo e($viewingHistory ? 'disabled' : ''); ?>>
                                                  </td>
                                                <?php if(!$viewingHistory): ?>
                                                    <td class="px-3 py-2">
                                                        <button type="button" onclick="removeExperimentalRow(this)" class="inline-flex items-center px-3 py-1 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-500 active:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
                                                            Remove
                                                        </button>
                                                      </td>
                                                <?php endif; ?>
                                            </tr>
                                            <?php endfor; ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </form>

                    <!-- Вкладка результатов -->
                    <div class="tab-pane hidden" id="results-tab">
                        <?php if($results): ?>
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
                                        <?php $__currentLoopData = $results['temperatures']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $T): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <?php
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
                                        ?>
                                        <tr>
                                            <td class="px-3 py-2 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100"><?php echo e(number_format(floatval($T), 1, '.', '')); ?></td>
                                            <td class="px-3 py-2 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100"><?php echo e(number_format($calcValue, 6, '.', '')); ?></td>
                                            <td class="px-3 py-2 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100"><?php echo e($expValue ? number_format($expValue, 6, '.', '') : '-'); ?></td>
                                            <td class="px-3 py-2 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100"><?php echo e($diff ? number_format($diff, 6, '.', '') : '-'); ?></td>
                                            <td class="px-3 py-2 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100"><?php echo e($relError ? number_format($relError, 2, '.', '') . '%' : '-'); ?></td>
                                        </tr>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <?php else: ?>
                        <div class="bg-blue-50 dark:bg-blue-900 rounded-lg p-4">
                            <p class="text-sm text-blue-800 dark:text-blue-200">
                                No calculation results yet. Please go to the Input Data tab and run a calculation.
                            </p>
                        </div>
                        <?php endif; ?>
                    </div>

                    <!-- Вкладка оптимизации -->
                    <div class="tab-pane hidden" id="optimization-tab">
                        <?php if($optimizationResult && isset($optimizationResult['success']) && $optimizationResult['success']): ?>
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
                                        <p class="text-2xl font-bold text-green-700 dark:text-green-300"><?php echo e(number_format(floatval($optimizationResult['ssd']), 6, '.', '')); ?></p>
                                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Root Mean Square Error: <?php echo e(number_format(sqrt(floatval($optimizationResult['objective'])), 6, '.', '')); ?></p>
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
                                
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                                    <div class="bg-white dark:bg-gray-800 rounded-lg p-4 border-l-4 border-blue-500">
                                        <p class="text-sm text-gray-600 dark:text-gray-400 mb-1">J9C Coefficient</p>
                                        <?php
                                            $j9cOpt = $optimizationResult['j9c'] ?? 0;
                                            $j9cOrig = $inputOriginal['j9c'] ?? $input['j9c'] ?? 0;
                                        ?>
                                        <p class="text-xl font-mono font-bold text-blue-700 dark:text-blue-300">
                                            <?php echo e(rtrim(rtrim(sprintf('%.15F', (float)$j9cOpt), '0'), '.')); ?>

                                        </p>
                                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                                            Original: <?php echo e(rtrim(rtrim(sprintf('%.15F', (float)$j9cOrig), '0'), '.')); ?>

                                        </p>
                                    </div>
                                    <div class="bg-white dark:bg-gray-800 rounded-lg p-4 border-l-4 border-purple-500">
                                        <p class="text-sm text-gray-600 dark:text-gray-400 mb-1">J11C Coefficient</p>
                                        <?php
                                            $j11cOpt = $optimizationResult['j11c'] ?? 0;
                                            $j11cOrig = $inputOriginal['j11c'] ?? $input['j11c'] ?? 0;
                                        ?>
                                        <p class="text-xl font-mono font-bold text-purple-700 dark:text-purple-300">
                                            <?php echo e(rtrim(rtrim(sprintf('%.15F', (float)$j11cOpt), '0'), '.')); ?>

                                        </p>
                                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                                            Original: <?php echo e(rtrim(rtrim(sprintf('%.15F', (float)$j11cOrig), '0'), '.')); ?>

                                        </p>
                                    </div>
                                </div>

                                <div class="mt-4">
                                    <p class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-3">Kc Coefficients (optimized):</p>
                                    <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-3">
                                        <?php $__currentLoopData = $optimizationResult['kc']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $kc): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <?php
                                                $kcOrig = $inputOriginal['kc'][$index] ?? $input['kc'][$index] ?? 0;
                                            ?>
                                            <div class="bg-white dark:bg-gray-800 rounded-lg p-3 text-center shadow-sm hover:shadow-md transition-shadow duration-200">
                                                <p class="text-xs text-gray-500 dark:text-gray-300 mb-1">Kc[<?php echo e($index); ?>]</p>
                                                <p class="text-sm font-mono font-semibold break-all <?php echo e($index == 0 ? 'text-gray-700 dark:text-gray-300' : 'text-green-700 dark:text-green-300'); ?>">
                                                    <?php echo e(rtrim(rtrim(sprintf('%.15F', (float)$kc), '0'), '.')); ?>

                                                </p>
                                                <?php if($index == 0): ?>
                                                    <p class="text-xs text-gray-500 dark:text-gray-300 mt-1">(fixed)</p>
                                                <?php else: ?>
                                                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-1 break-all">
                                                        Original: <?php echo e(rtrim(rtrim(sprintf('%.15F', (float)$kcOrig), '0'), '.')); ?>

                                                    </p>
                                                <?php endif; ?>
                                            </div>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
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
                                            <?php $__currentLoopData = $optimizationResult['kc']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $kc): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <?php if($index > 0): ?>
                                                    <?php
                                                        $originalValue = floatval($inputOriginal['kc'][$index] ?? $input['kc'][$index] ?? 0);
                                                        $optimizedValue = floatval($kc);
                                                        $change = $originalValue != 0 ? ($optimizedValue - $originalValue) / abs($originalValue) * 100 : 0;
                                                        $changeFormatted = ($change > 0 ? '+' : '') . number_format($change, 2, '.', '');
                                                    ?>
                                                    <tr>
                                                        <td class="px-4 py-3 text-sm font-medium text-gray-900 dark:text-gray-100">Kc[<?php echo e($index); ?>]</td>
                                                        <td class="px-4 py-3 text-sm font-mono text-gray-700 dark:text-gray-300 break-all">
                                                            <?php echo e(rtrim(rtrim(sprintf('%.15F', $originalValue), '0'), '.')); ?>

                                                        </td>
                                                        <td class="px-4 py-3 text-sm font-mono text-green-700 dark:text-green-300 break-all">
                                                            <?php echo e(rtrim(rtrim(sprintf('%.15F', $optimizedValue), '0'), '.')); ?>

                                                        </td>
                                                        <td class="px-4 py-3 text-sm <?php echo e($change > 0 ? 'text-green-600' : ($change < 0 ? 'text-red-600' : 'text-gray-600')); ?>">
                                                            <?php echo e($changeFormatted); ?>%
                                                        </td>
                                                    </tr>
                                                <?php endif; ?>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                                            <?php
                                                $originalJ9C = floatval($inputOriginal['j9c'] ?? $input['j9c'] ?? 0);
                                                $optimizedJ9C = floatval($optimizationResult['j9c'] ?? 0);
                                                $changeJ9C = $originalJ9C != 0 ? ($optimizedJ9C - $originalJ9C) / abs($originalJ9C) * 100 : 0;
                                                $changeJ9CFormatted = ($changeJ9C > 0 ? '+' : '') . number_format($changeJ9C, 2, '.', '');
                                            ?>
                                            <tr>
                                                <td class="px-4 py-3 text-sm font-medium text-gray-900 dark:text-gray-100">J9C</td>
                                                <td class="px-4 py-3 text-sm font-mono text-gray-700 dark:text-gray-300 break-all">
                                                    <?php echo e(rtrim(rtrim(sprintf('%.15F', $originalJ9C), '0'), '.')); ?>

                                                </td>
                                                <td class="px-4 py-3 text-sm font-mono text-green-700 dark:text-green-300 break-all">
                                                    <?php echo e(rtrim(rtrim(sprintf('%.15F', $optimizedJ9C), '0'), '.')); ?>

                                                </td>
                                                <td class="px-4 py-3 text-sm <?php echo e($changeJ9C > 0 ? 'text-green-600' : ($changeJ9C < 0 ? 'text-red-600' : 'text-gray-600')); ?>">
                                                    <?php echo e($changeJ9CFormatted); ?>%
                                                </td>
                                            </tr>

                                            <?php
                                                $originalJ11C = floatval($inputOriginal['j11c'] ?? $input['j11c'] ?? 0);
                                                $optimizedJ11C = floatval($optimizationResult['j11c'] ?? 0);
                                                $changeJ11C = $originalJ11C != 0 ? ($optimizedJ11C - $originalJ11C) / abs($originalJ11C) * 100 : 0;
                                                $changeJ11CFormatted = ($changeJ11C > 0 ? '+' : '') . number_format($changeJ11C, 2, '.', '');
                                            ?>
                                            <tr>
                                                <td class="px-4 py-3 text-sm font-medium text-gray-900 dark:text-gray-100">J11C</td>
                                                <td class="px-4 py-3 text-sm font-mono text-gray-700 dark:text-gray-300 break-all">
                                                    <?php echo e(rtrim(rtrim(sprintf('%.15F', $originalJ11C), '0'), '.')); ?>

                                                </td>
                                                <td class="px-4 py-3 text-sm font-mono text-green-700 dark:text-green-300 break-all">
                                                    <?php echo e(rtrim(rtrim(sprintf('%.15F', $optimizedJ11C), '0'), '.')); ?>

                                                </td>
                                                <td class="px-4 py-3 text-sm <?php echo e($changeJ11C > 0 ? 'text-green-600' : ($changeJ11C < 0 ? 'text-red-600' : 'text-gray-600')); ?>">
                                                    <?php echo e($changeJ11CFormatted); ?>%
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <?php else: ?>
                        <div class="bg-blue-50 dark:bg-blue-900 rounded-lg p-4">
                            <p class="text-sm text-blue-800 dark:text-blue-200">
                                No optimization results yet. Please add experimental data and click the "Optimize" button in the Input Data tab.
                            </p>
                        </div>
                        <?php endif; ?>
                    </div>

                    <!-- Вкладка истории -->
                    <div class="tab-pane hidden" id="history-tab">
                        <div class="bg-gray-200 dark:bg-gray-700 rounded-lg p-4">
                            <h5 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-3">Calculation History</h5>
                            
                            <?php if($history->isEmpty()): ?>
                                <div class="bg-blue-50 dark:bg-blue-900 rounded-lg p-4">
                                    <p class="text-sm text-blue-800 dark:text-blue-200">
                                        No calculations in history yet. Run a calculation to see it here.
                                    </p>
                                </div>
                            <?php else: ?>
                                <div class="space-y-3 max-h-96 overflow-y-auto pr-2">
                                    <?php $__currentLoopData = $history; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <div class="border border-gray-400 dark:border-gray-500 rounded-lg p-3 sm:p-4 hover:bg-gray-100 dark:hover:bg-gray-800 transition-colors duration-150 
                                            <?php echo e($item->is_favorite ? 'border-yellow-400 dark:border-yellow-600 bg-yellow-100 dark:bg-yellow-900/20' : ''); ?>">
                                            
                                            <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-3">
                                                <div class="flex-1 min-w-0">
                                                    <div class="flex items-start gap-2">
                                                        <?php if($item->is_favorite): ?>
                                                            <svg class="h-5 w-5 text-yellow-400 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                                            </svg>
                                                        <?php endif; ?>
                                                        
                                                        <div class="flex-1 min-w-0">
                                                            <div class="flex flex-wrap items-center gap-2">
                                                                <h6 class="font-medium text-gray-900 dark:text-gray-100 break-words" id="name-text-<?php echo e($item->id); ?>">
                                                                    <?php echo e($item->name); ?>

                                                                </h6>
                                                                <button onclick="editName(<?php echo e($item->id); ?>)" class="text-gray-500 hover:text-indigo-600 dark:text-gray-400 dark:hover:text-indigo-400 transition-colors duration-150 flex-shrink-0">
                                                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                                                                    </svg>
                                                                </button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    
                                                    <div id="edit-name-<?php echo e($item->id); ?>" class="hidden mt-2 w-full">
                                                        <div class="flex flex-col sm:flex-row items-start sm:items-center gap-2">
                                                            <input type="text" id="new-name-<?php echo e($item->id); ?>" value="<?php echo e($item->name); ?>" 
                                                                   class="w-full sm:flex-1 rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 bg-white dark:bg-gray-600 dark:border-gray-600 dark:text-white text-sm">
                                                            <div class="flex gap-2 w-full sm:w-auto">
                                                                <button onclick="saveName(<?php echo e($item->id); ?>)" class="flex-1 sm:flex-none inline-flex items-center justify-center px-3 py-1 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-500 focus:outline-none focus:ring-2 focus:ring-green-500 transition ease-in-out duration-150">
                                                                    Save
                                                                </button>
                                                                <button onclick="cancelEdit(<?php echo e($item->id); ?>)" class="flex-1 sm:flex-none inline-flex items-center justify-center px-3 py-1 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-500 focus:outline-none focus:ring-2 focus:ring-gray-500 transition ease-in-out duration-150">
                                                                    Cancel
                                                                </button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    
                                                    <p class="text-sm text-gray-600 dark:text-gray-400 mt-2">
                                                        Created: <?php echo e($item->created_at->format('d.m.Y H:i')); ?>

                                                    </p>
                                                    
                                                    <!-- Блок с публичными ссылками -->
                                                    <?php if(isset($item->active_share_links) && $item->active_share_links->count() > 0): ?>
                                                    <div class="mt-3">
                                                        <p class="text-xs font-medium text-gray-500 dark:text-gray-300 mb-2 flex items-center gap-1">
                                                            <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.368 2.684 3 3 0 00-5.368-2.684z"></path>
                                                            </svg>
                                                            Публичные ссылки:
                                                        </p>
                                                        <div class="flex flex-col gap-2">
                                                            <?php $__currentLoopData = $item->active_share_links; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $link): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                            <div class="bg-gray-100 dark:bg-gray-600 border border-gray-400 dark:border-gray-500 rounded-lg p-2.5 hover:shadow-md transition-all duration-200">
                                                                <div class="flex items-center gap-2">
                                                                    <a href="<?php echo e($link->url); ?>" target="_blank" 
                                                                       class="text-blue-600 dark:text-blue-400 hover:underline font-mono text-sm truncate flex-1"
                                                                       title="<?php echo e($link->url); ?>">
                                                                        <?php echo e($link->url); ?>

                                                                        <?php if($link->password_hash): ?>
                                                                        <span class="inline-flex items-center gap-1 text-gray-500 dark:text-gray-400 text-xs ml-1" title="Защищена паролем">
                                                                            <svg class="h-3 w-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                                                                            </svg>
                                                                        </span>
                                                                        <?php endif; ?>
                                                                    </a>
                                                                </div>
                                                                
                                                                <div class="flex items-center justify-between mt-2 pt-1 border-t border-gray-300 dark:border-gray-500">
                                                                    <div class="flex items-center gap-3">
                                                                        <?php if($link->expires_at): ?>
                                                                        <span class="text-xs text-gray-500 dark:text-gray-200" title="Истекает: <?php echo e($link->expires_at->format('d.m.Y')); ?>">
                                                                            До <?php echo e($link->expires_at->format('d.m.Y')); ?>

                                                                        </span>
                                                                        <?php else: ?>
                                                                        <span class="text-xs text-gray-500 dark:text-gray-200" title="Бессрочная ссылка">
                                                                            Бессрочно
                                                                        </span>
                                                                        <?php endif; ?>
                                                                    </div>
                                                                    
                                                                    <div class="flex items-center gap-1">
                                                                        <span class="inline-flex items-center gap-1 text-gray-500 dark:text-gray-200 text-xs mr-2" title="Просмотров: <?php echo e($link->views); ?>">
                                                                            <svg class="h-3 w-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                                                            </svg>
                                                                            <?php echo e($link->views); ?>

                                                                        </span>

                                                                        <!-- Кнопка просмотра статистики -->
<?php if(isset($item->active_share_links) && $item->active_share_links->count() > 0): ?>
    <?php $__currentLoopData = $item->active_share_links; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $link): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
    <a href="<?php echo e(route('public.calculation.stats', ['token' => $link->token])); ?>" 
       target="_blank"
       class="inline-flex items-center gap-1 text-gray-500 dark:text-gray-200 text-xs hover:text-blue-600 dark:hover:text-blue-400 transition-colors" 
       title="Статистика">
        <svg class="h-3 w-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
        </svg>
    </a>
    <?php break; ?>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
<?php endif; ?>
                                                                        
                                                                        <button onclick="copyToClipboard('<?php echo e($link->url); ?>')" 
                                                                                class="p-1 text-gray-500 hover:text-indigo-600 dark:text-gray-200 dark:hover:text-indigo-400 transition-colors rounded-md hover:bg-gray-200 dark:hover:bg-gray-600"
                                                                                title="Копировать ссылку">
                                                                            <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
                                                                            </svg>
                                                                        </button>
                                                                        
                                                                        <button onclick="showQRCode('<?php echo e($link->token); ?>', '<?php echo e($link->url); ?>')" 
                                                                                class="p-1 text-gray-500 hover:text-purple-600 dark:text-gray-200 dark:hover:text-purple-400 transition-colors rounded-md hover:bg-gray-200 dark:hover:bg-gray-600"
                                                                                title="Показать QR-код">
                                                                            <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"></path>
                                                                            </svg>
                                                                        </button>
                                                                        
                                                                        <button onclick="editShareLink(<?php echo e($link->id); ?>, 
                                                                                '<?php echo e(addslashes($link->title)); ?>', 
                                                                                '<?php echo e(addslashes($link->description)); ?>', 
                                                                                '<?php echo e($link->expires_at); ?>', 
                                                                                <?php echo e($link->allow_copy_to_account ? 'true' : 'false'); ?>,
                                                                                <?php echo e($link->password_hash ? 'true' : 'false'); ?>)" 
                                                                                class="p-1 text-gray-500 hover:text-yellow-600 dark:text-gray-200 dark:hover:text-yellow-400 transition-colors rounded-md hover:bg-gray-200 dark:hover:bg-gray-600"
                                                                                title="Редактировать">
                                                                            <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path>
                                                                            </svg>
                                                                        </button>
                                                                        
                                                                        <button onclick="revokeShareLink(<?php echo e($link->id); ?>)" 
                                                                                class="p-1 text-gray-500 hover:text-red-600 dark:text-gray-200 dark:hover:text-red-400 transition-colors rounded-md hover:bg-gray-200 dark:hover:bg-gray-600"
                                                                                title="Удалить ссылку">
                                                                            <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                                                            </svg>
                                                                        </button>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                        </div>
                                                    </div>
                                                    <?php endif; ?>
                                                </div>
                                                
                                                <div class="text-sm text-gray-600 dark:text-gray-400 flex flex-row sm:flex-col gap-2 sm:gap-1 flex-wrap sm:flex-nowrap">
                                                    <?php if($item->results): ?>
                                                        <span class="whitespace-nowrap">Results: <?php echo e(count($item->results['temperatures'] ?? [])); ?> points</span>
                                                    <?php endif; ?>
                                                    <?php if($item->optimization_results): ?>
                                                        <span class="whitespace-nowrap text-lime-700 dark:text-green-400">Optimized</span>
                                                    <?php endif; ?>
                                                </div>
                                            </div>
                                            
                                            <div class="mt-3 flex flex-wrap gap-2">
                                                <a href="<?php echo e(route('calculator.history.load', $item)); ?>" 
                                                   class="inline-flex items-center px-3 py-1.5 sm:px-3 sm:py-1 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-500 active:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
                                                    Load
                                                </a>
                                                
                                                <!-- Кнопка создания публичной ссылки -->
                                                <button onclick="createShareLink(<?php echo e($item->id); ?>)" 
                                                        class="inline-flex items-center px-3 py-1.5 sm:px-3 sm:py-1 bg-purple-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-purple-500 active:bg-purple-700 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
                                                    <svg class="h-3 w-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.368 2.684 3 3 0 00-5.368-2.684z"></path>
                                                    </svg>
                                                    Share
                                                </button>
                                                
                                                <button onclick="toggleFavorite(<?php echo e($item->id); ?>)" 
                                                        class="inline-flex items-center px-3 py-1.5 sm:px-3 sm:py-1 <?php echo e($item->is_favorite ? 'bg-yellow-600' : 'bg-gray-600'); ?> border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-yellow-500 active:bg-yellow-700 focus:outline-none focus:ring-2 focus:ring-yellow-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
                                                    <?php echo e($item->is_favorite ? 'Remove from favorites' : 'Add to favorites'); ?>

                                                </button>
                                                <form action="<?php echo e(route('calculator.history.delete', $item)); ?>" method="POST" class="inline">
                                                    <?php echo csrf_field(); ?>
                                                    <?php echo method_field('DELETE'); ?>
                                                    <button type="submit" onclick="return confirm('Delete this calculation from history?');"
                                                            class="inline-flex items-center px-3 py-1.5 sm:px-3 sm:py-1 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-500 active:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
                                                        Delete
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Модальное окно создания публичной ссылки -->
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
            <div class="space-y-2 text-left">
                <?php for($i = 1; $i <= 5; $i++): ?>
                <div class="opt-step flex items-center gap-3 p-2 rounded-lg opacity-30 transition-all duration-300" id="step-<?php echo e($i); ?>">
                    <div class="w-5 h-5 rounded-full bg-indigo-100 dark:bg-indigo-900 flex items-center justify-center flex-shrink-0">
                        <span class="text-xs font-bold text-indigo-600 dark:text-indigo-400"><?php echo e($i); ?></span>
                    </div>
                    <span class="text-sm text-gray-600 dark:text-gray-400">
                        Restart <?php echo e($i); ?> of 5
                    </span>
                    <div class="ml-auto step-check hidden">
                        <svg class="w-4 h-4 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                </div>
                <?php endfor; ?>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>

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
            const response = await fetch(`/calculator/ND3+/history/${historyId}/share`, {
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
    
    // Функция для полного удаления ссылки
    async function revokeShareLink(linkId) {
        if (!confirm('Are you sure you want to permanently delete this link? This action cannot be undone.')) {
            return;
        }
        
        try {
            const response = await fetch(`/share-links/${linkId}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json'
                }
            });
            
            const data = await response.json();
            
            if (data.success) {
                showTemporaryMessage('Link deleted successfully', 'success');
                setTimeout(() => location.reload(), 1000);
            } else {
                showTemporaryMessage(data.error || 'Error deleting link', 'error');
            }
        } catch (error) {
            console.error('Error:', error);
            showTemporaryMessage('Network error while deleting link', 'error');
        }
    }
    
    // Функция для показа QR-кода
    function showQRCode(token, url) {
        const existingModal = document.getElementById('qr-modal');
        if (existingModal) {
            existingModal.remove();
        }
        
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
                container.innerHTML = '<div class="text-red-500 text-center">Error loading QR code</div>';
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
            showTemporaryMessage('QR code saved!', 'success');
        } else {
            showTemporaryMessage('Failed to save QR code', 'error');
        }
    }
    
    // Функция редактирования ссылки
    function editShareLink(linkId, currentTitle, currentDescription, currentExpiresAt, currentAllowCopy, hasPassword) {
        const existingModal = document.getElementById('edit-link-modal');
        if (existingModal) {
            existingModal.remove();
        }
        
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
        
        window.passwordToRemove = false;
        window.removePassword = function() {
            if (confirm('Вы уверены, что хотите снять защиту паролем? Ссылка станет общедоступной.')) {
                const passwordInput = document.getElementById('edit-link-password');
                passwordInput.value = 'DELETE_PASSWORD';
                passwordInput.placeholder = 'Пароль будет удален';
                passwordInput.classList.add('bg-red-50', 'dark:bg-red-900/20', 'border-red-300', 'dark:border-red-700');
                
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
        
        document.getElementById('edit-link-form').addEventListener('submit', async (e) => {
            e.preventDefault();
            
            const linkId = document.getElementById('edit-link-id').value;
            const title = document.getElementById('edit-link-title').value;
            const description = document.getElementById('edit-link-description').value;
            const expiresInDays = document.getElementById('edit-link-expires').value;
            let password = document.getElementById('edit-link-password').value;
            const allowCopy = document.getElementById('edit-link-allow-copy').checked;
            
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
                    showTemporaryMessage('Link updated successfully!', 'success');
                    closeEditModal();
                    setTimeout(() => location.reload(), 1000);
                } else {
                    showTemporaryMessage(data.error || 'Error updating link', 'error');
                }
            } catch (error) {
                console.error('Error:', error);
                showTemporaryMessage('Network error while updating link', 'error');
            } finally {
                submitBtn.innerHTML = originalText;
                submitBtn.disabled = false;
            }
        });
    }
    
    function escapeHtml(text) {
        if (!text) return '';
        const div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    }
    
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

    // Функция для экспорта таблицы
    function exportTableFromResultsTab() {
        const temperatures = [];
        const wValues = [];
        const lifetimes = [];
        
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
        
        const headers = ['T (K)', 'W×10⁻³ (s⁻¹)', 'Lifetime (µs)'];
        const data = temperatures.map((temp, index) => [temp, wValues[index], lifetimes[index]]);
        exportToExcel(headers, data, 'calculation_results.csv');
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
            const blob = new Blob(['\uFEFF' + csvString], { type: 'text/csv;charset=utf-8;' });
            const link = document.createElement('a');
            const url = URL.createObjectURL(blob);
            link.href = url;
            link.setAttribute('download', filename);
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
            showTemporaryMessage('No results to export', 'error');
            return;
        }
        
        exportToExcel(headers, rows, 'full_results.csv');
    }
    
    function exportOptimizationParamsTable() {
        const tableElement = document.querySelector('#optimization-tab .overflow-x-auto table');
        if (!tableElement) {
            showTemporaryMessage('No optimization parameters table found', 'error');
            return;
        }
        
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
            if (rowData.length > 0) {
                csvData.push(rowData.join(';'));
            }
        });
        
        const csvString = csvData.join('\n');
        const blob = new Blob(['\uFEFF' + csvString], { type: 'text/csv;charset=utf-8;' });
        const link = document.createElement('a');
        const url = URL.createObjectURL(blob);
        link.href = url;
        link.setAttribute('download', 'optimization_parameters.csv');
        document.body.appendChild(link);
        link.click();
        document.body.removeChild(link);
        URL.revokeObjectURL(url);
        showTemporaryMessage('Data exported successfully', 'success');
    }
    
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
            fetch('<?php echo e(route("calculator.save-experimental")); ?>', {
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
            .catch(error => console.error('Error saving experimental data:', error));
        }
    }
    
    function submitOptimization() {
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
        
        saveExperimentalDataToSession();
        setTimeout(() => {
            const form = document.getElementById('calculatorForm');
            form.action = "<?php echo e(route('calculator.optimize')); ?>";
            form.method = "POST";
            form.submit();
        }, 200);
    }
    
    function exitHistoryView() {
        fetch('<?php echo e(route('calculator.exit-history-view')); ?>', {
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
        .catch(error => console.error('Error:', error));
    }
    
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
        
        fetch(`/calculator/ND3+/history/${id}/update-name`, {
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
        fetch(`/calculator/ND3+/history/${historyId}/toggle-favorite`, {
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
            
            let inputs = document.querySelectorAll('#calculatorForm input[type=text]');
            inputs.forEach(input => {
                if (input.name === 'exp_temperatures[]' || input.name === 'exp_values[]') {
                    return;
                }
                if (!input.hasAttribute('readonly')) {
                    input.value = '';
                }
            });
            
            const fnSelect = document.getElementById('fnSelect');
            if (fnSelect) {
                fnSelect.value = '2';
                updateFNGrid();
            }
            
            const j9nSelect = document.getElementById('j9nSelect');
            if (j9nSelect) {
                j9nSelect.value = '5';
                updateJ9NGrid();
            }
            
            const j11nSelect = document.getElementById('j11nSelect');
            if (j11nSelect) {
                j11nSelect.value = '6';
                updateJ11NGrid();
            }
            
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
            
            setTimeout(() => {
                const firstKcInput = document.querySelector('input[name="kc[]"]');
                if (firstKcInput) {
                    firstKcInput.value = '1';
                    firstKcInput.setAttribute('readonly', 'readonly');
                    firstKcInput.classList.remove('bg-gray-50', 'dark:bg-gray-700', 'text-gray-900', 'dark:text-gray-100');
                    firstKcInput.classList.add('bg-gray-100', 'dark:bg-gray-600', 'text-gray-700', 'dark:text-gray-200', 'cursor-not-allowed');
                }
            }, 50);
            
            setTimeout(saveExperimentalDataToSession, 200);
            setTimeout(() => showTemporaryMessage('Form cleared successfully', 'success'), 150);
        }
    }
    
    function showTemporaryMessage(message, type = 'info') {
        const existingMsg = document.querySelector('.temporary-message');
        if (existingMsg) existingMsg.remove();
        
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
            default:
                bgColor = 'bg-blue-50 dark:bg-blue-900';
                borderColor = 'border-blue-500';
                textColor = 'text-blue-800 dark:text-blue-200';
                iconColor = 'text-blue-400';
                iconPath = '<path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>';
                break;
        }
        
        const msgDiv = document.createElement('div');
        msgDiv.className = `fixed bottom-4 right-4 z-50 rounded-lg shadow-lg ${bgColor} border-l-4 ${borderColor} p-4 temporary-message`;
        msgDiv.style.maxWidth = '400px';
        msgDiv.style.minWidth = '280px';
        msgDiv.innerHTML = `
            <div class="flex items-center justify-between">
                <div class="flex items-center flex-1">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 ${iconColor}" fill="currentColor" viewBox="0 0 20 20">${iconPath}</svg>
                    </div>
                    <div class="ml-3 flex-1">
                        <p class="text-sm font-medium ${textColor}">${message}</p>
                    </div>
                </div>
                <button onclick="this.closest('.temporary-message').remove()" class="ml-4 flex-shrink-0 ${iconColor} hover:opacity-75">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
        `;
        document.body.appendChild(msgDiv);
        msgDiv.style.animation = 'slideInUp 0.3s ease-out';
        
        setTimeout(() => {
            if (msgDiv && msgDiv.parentElement) {
                msgDiv.style.animation = 'slideOutDown 0.3s ease-in forwards';
                setTimeout(() => msgDiv.remove(), 300);
            }
        }, type === 'error' ? 8000 : 5000);
    }
    
    function updateFNGrid() {
        let fn = document.getElementById('fnSelect').value;
        let feGrid = document.getElementById('feGrid');
        let kcGrid = document.getElementById('kcGrid');
        
        let currentFe = [];
        let currentKc = [];
        
        document.querySelectorAll('#feGrid input').forEach(input => {
            if (input.value) currentFe.push(input.value);
        });
        
        document.querySelectorAll('#kcGrid input').forEach(input => {
            if (input.value) currentKc.push(input.value);
        });
        
        feGrid.innerHTML = '';
        kcGrid.innerHTML = '';
        
        for (let i = 0; i < fn; i++) {
            feGrid.innerHTML += `<div>
                <input type="text" name="fe[]" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-800 dark:border-gray-600 dark:text-white" 
                       value="${currentFe[i] || ''}" placeholder="E${i+1}">
            </div>`;
            
            const isFirst = (i === 0);
            const readonlyAttr = isFirst ? 'readonly' : '';
            const readonlyClass = isFirst ? 'bg-gray-100 dark:bg-gray-600 text-gray-700 dark:text-gray-200 cursor-not-allowed' : '';
            
            kcGrid.innerHTML += `<div>
                <input type="text" name="kc[]" 
                    class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-800 dark:border-gray-600 dark:text-white ${readonlyClass}" 
                    value="${isFirst ? '1' : (currentKc[i] || '')}" placeholder="Kc${i+1}" ${readonlyAttr}>
                ${isFirst ? '<p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Fixed to 1</p>' : ''}
            </div>`;
        }
    }
    
    function updateJ9NGrid() {
        let j9n = document.getElementById('j9nSelect').value;
        let j9eGrid = document.getElementById('j9eGrid');
        
        let currentJ9e = [];
        document.querySelectorAll('#j9eGrid input').forEach(input => {
            if (input.value) currentJ9e.push(input.value);
        });
        
        j9eGrid.innerHTML = '';
        for (let i = 0; i < j9n; i++) {
            j9eGrid.innerHTML += `<div>
                <input type="text" name="j9e[]" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-800 dark:border-gray-600 dark:text-white" 
                       value="${currentJ9e[i] || ''}" placeholder="E${i+1}">
            </div>`;
        }
    }
    
    function updateJ11NGrid() {
        let j11n = document.getElementById('j11nSelect').value;
        let j11eGrid = document.getElementById('j11eGrid');
        
        let currentJ11e = [];
        document.querySelectorAll('#j11eGrid input').forEach(input => {
            if (input.value) currentJ11e.push(input.value);
        });
        
        j11eGrid.innerHTML = '';
        for (let i = 0; i < j11n; i++) {
            j11eGrid.innerHTML += `<div>
                <input type="text" name="j11e[]" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-800 dark:border-gray-600 dark:text-white" 
                       value="${currentJ11e[i] || ''}" placeholder="E${i+1}">
            </div>`;
        }
    }
    
    function addExperimentalRow() {
        let tbody = document.getElementById('experimentalBody');
        let rows = tbody.children;
        let lastRow = rows[rows.length - 1];
        
        if (lastRow) {
            let tempInput = lastRow.querySelector('input[name="exp_temperatures[]"]');
            let valInput = lastRow.querySelector('input[name="exp_values[]"]');
            if ((!tempInput.value || tempInput.value.trim() === '') && (!valInput.value || valInput.value.trim() === '')) {
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
            if ((tempInput.value && tempInput.value.trim() !== '') || (valInput.value && valInput.value.trim() !== '')) {
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
        
        <?php if(!$viewingHistory): ?>
        updateFNGrid();
        updateJ9NGrid();
        updateJ11NGrid();
        <?php endif; ?>
    });
    
    <?php if($results): ?>
    let resultsChart = null;
    let combinedChart = null;
    
    async function saveChartAsImage(chart, filename, scale = 2) {
        if (!chart) {
            console.error('Chart not found');
            return;
        }
        try {
            const originalCanvas = chart.canvas;
            const isDarkMode = document.documentElement.classList.contains('dark');
            const originalWidth = originalCanvas.width;
            const originalHeight = originalCanvas.height;
            const tempCanvas = document.createElement('canvas');
            tempCanvas.width = originalWidth * scale;
            tempCanvas.height = originalHeight * scale;
            const ctx = tempCanvas.getContext('2d');
            ctx.imageSmoothingEnabled = true;
            ctx.imageSmoothingQuality = 'high';
            ctx.fillStyle = isDarkMode ? '#1f2937' : '#ffffff';
            ctx.fillRect(0, 0, tempCanvas.width, tempCanvas.height);
            ctx.drawImage(originalCanvas, 0, 0, tempCanvas.width, tempCanvas.height);
            const link = document.createElement('a');
            link.download = filename || 'chart.png';
            link.href = tempCanvas.toDataURL('image/png');
            link.click();
            showTemporaryMessage('Chart saved as PNG', 'success');
        } catch (error) {
            console.error('Error saving chart:', error);
            showTemporaryMessage('Error saving chart', 'error');
        }
    }
    
    function initCharts() {
        function isDarkMode() {
            return document.documentElement.classList.contains('dark');
        }
        
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
                experimentalPoint: dark ? 'rgb(255, 99, 132)' : 'rgb(255, 99, 132)'
            };
        }
        
        function updateResultsChartColors() {
            if (!resultsChart) return;
            const colors = getChartColors();
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
            const colors = getChartColors();
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
        
        const isInputTabVisible = document.getElementById('input-tab') && !document.getElementById('input-tab').classList.contains('hidden');
        
        if (resultsChart) {
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
        const ctx = document.getElementById('resultsChart');
        if (ctx && !resultsChart) {
            resultsChart = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: <?php echo json_encode($results['temperatures']); ?>,
                    datasets: [{
                        label: 'Calculated Lifetime (µs)',
                        data: <?php echo json_encode($results['delta']); ?>,
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
                            labels: { usePointStyle: true, color: colors.legendText, font: { size: 12 } }
                        },
                        tooltip: {
                            backgroundColor: colors.tooltipBackground,
                            titleColor: '#fff',
                            bodyColor: '#fff',
                            borderColor: 'rgba(255, 255, 255, 0.2)',
                            borderWidth: 1,
                            callbacks: {
                                title: (context) => `Temperature: ${context[0].label} K`,
                                label: (context) => `Lifetime: ${context.parsed.y.toFixed(3)} µs`
                            }
                        }
                    },
                    scales: {
                        x: {
                            title: { display: true, text: 'Temperature (K)', font: { weight: 'bold', size: 12 }, color: colors.axisTitle },
                            ticks: { color: colors.axisTicks, font: { size: 11 } },
                            grid: { color: colors.gridLines, borderColor: colors.axisBorder }
                        },
                        y: {
                            title: { display: true, text: 'Lifetime (µs)', font: { weight: 'bold', size: 12 }, color: colors.axisTitle },
                            ticks: { color: colors.axisTicks, font: { size: 11 }, callback: (value) => value.toFixed(1) + ' µs' },
                            grid: { color: colors.gridLines, borderColor: colors.axisBorder },
                            beginAtZero: false
                        }
                    }
                }
            });
        }
        
        const combinedCtx = document.getElementById('combinedChart');
        if (combinedCtx && !combinedChart) {
            const expData = <?php echo json_encode($experimentalData); ?>;
            const calcTemps = <?php echo json_encode($results['temperatures']); ?>;
            const calcValues = <?php echo json_encode($results['delta']); ?>;
            expData.sort((a, b) => a.temperature - b.temperature);
            const calcDataPoints = calcTemps.map((temp, index) => ({ x: temp, y: calcValues[index] }));
            const expDataPoints = expData.map(d => ({ x: d.temperature, y: d.value }));
            const isMobile = window.innerWidth < 640;
            const isTablet = window.innerWidth >= 640 && window.innerWidth < 1024;
            const pointSizes = { experimental: isMobile ? 5 : (isTablet ? 6 : 7) };
            const fontSizes = { title: isMobile ? 10 : (isTablet ? 11 : 12), ticks: isMobile ? 9 : (isTablet ? 10 : 11), legend: isMobile ? 10 : (isTablet ? 11 : 12) };
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
                            borderWidth: lineWidth
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
                            titleFont: { size: isMobile ? 11 : 12 },
                            bodyFont: { size: isMobile ? 10 : 11 },
                            callbacks: {
                                title: () => '',
                                label: (context) => {
                                    const xValue = context.parsed.x;
                                    const yValue = context.parsed.y;
                                    if (isMobile) return [`${xValue.toFixed(0)} K`, `${yValue.toFixed(2)} µs`];
                                    return [`Temperature: ${xValue.toFixed(1)} K`, `Lifetime: ${yValue.toFixed(3)} µs`];
                                }
                            }
                        }
                    },
                    scales: {
                        x: {
                            type: 'linear',
                            position: 'bottom',
                            title: { display: true, text: isMobile ? 'T (K)' : 'Temperature (K)', font: { weight: isMobile ? 'normal' : 'bold', size: fontSizes.title }, color: colors.axisTitle },
                            ticks: { color: colors.axisTicks, font: { size: fontSizes.ticks }, callback: (value) => isMobile ? value.toFixed(0) : value.toFixed(0) + ' K', maxTicksLimit: isMobile ? 5 : 8, autoSkip: true },
                            grid: { color: colors.gridLines, borderColor: colors.axisBorder, lineWidth: isMobile ? 0.5 : 1 }
                        },
                        y: {
                            title: { display: true, text: isMobile ? 'τ (µs)' : 'Lifetime (µs)', font: { weight: isMobile ? 'normal' : 'bold', size: fontSizes.title }, color: colors.axisTitle },
                            ticks: { color: colors.axisTicks, font: { size: fontSizes.ticks }, callback: (value) => isMobile ? value.toFixed(0) : value.toFixed(1) + ' µs', maxTicksLimit: isMobile ? 5 : 8, autoSkip: true },
                            grid: { color: colors.gridLines, borderColor: colors.axisBorder, lineWidth: isMobile ? 0.5 : 1 },
                            beginAtZero: false
                        }
                    }
                }
            });
        }
        
        if (resultsChart && !isInputTabVisible) updateResultsChartColors();
    }
    
    const themeObserver = new MutationObserver((mutations) => {
        mutations.forEach((mutation) => {
            if (mutation.attributeName === 'class') {
                if (resultsChart) updateResultsChartColors();
                if (combinedChart) updateCombinedChartColors();
            }
        });
    });
    
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
    
    document.addEventListener('DOMContentLoaded', function() {
        initCharts();
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
    
    themeObserver.observe(document.documentElement, { attributes: true, attributeFilter: ['class'] });
    <?php endif; ?>
</script>
 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54)): ?>
<?php $attributes = $__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54; ?>
<?php unset($__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal9ac128a9029c0e4701924bd2d73d7f54)): ?>
<?php $component = $__componentOriginal9ac128a9029c0e4701924bd2d73d7f54; ?>
<?php unset($__componentOriginal9ac128a9029c0e4701924bd2d73d7f54); ?>
<?php endif; ?><?php /**PATH C:\Users\Desktop_54\Desktop\neocalc.site_share_link_30.05\resources\views/calculatorND3+/index.blade.php ENDPATH**/ ?>
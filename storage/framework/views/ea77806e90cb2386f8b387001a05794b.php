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
                <?php echo e(__('Управление сайтом')); ?>

            </h2>
            <div class="text-sm text-gray-800 dark:text-gray-400 animate__animated animate__fadeIn">
                <?php echo e(now()->translatedFormat('d F Y')); ?>

            </div>
        </div>
     <?php $__env->endSlot(); ?>

     <?php $__env->slot('title', null, []); ?> 
        <?php echo e(__('Панель управления')); ?>

     <?php $__env->endSlot(); ?>

        <?php
        $__assetKey = '645518215-0';

        ob_start();
    ?>
    <style>
        /* Анимации и дополнительные стили */
        .dashboard-card {
            transition: all 0.6s ease;
            transform: translateY(0);
            border-left: 4px solid transparent;
            background-color: #d1d5db;
        }

        .dashboard-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
            border-left-color: #3b82f6;
        }

        .dashboard-card.dark:hover {
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.3);
            border-left-color: #60a5fa;
        }

        .action-btn {
            transition: all 0.2s ease;
            position: relative;
            overflow: hidden;
        }

        .action-btn::after {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
            transition: all 1.0s ease;
        }

        .action-btn:hover::after {
            left: 100%;
        }

        .gradient-btn {
            background: linear-gradient(135deg, #3b82f6, #6366f1);
            transition: all 0.3s ease;
        }

        .gradient-btn:hover {
            background: linear-gradient(135deg, #2563eb, #4f46e5);
            transform: translateY(-2px);
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Медиазапросы для мобильных устройств */
        @media (max-width: 768px) {
            .flex.items-center.justify-between {
                align-items: flex-start;
                gap: 0.5rem;
            }

            .dashboard-card {
                margin-bottom: 1rem;
                border-left: none;
                border-top: 4px solid transparent;
            }

            .p-6 {
                padding: 1.25rem;
            }

            /*.flex.items-center.mb-4 {
                flex-direction: column;
                align-items: flex-start;
                gap: 0.75rem; 
            }*/

            .p-3.rounded-full {
                margin-right: 0;
                margin-bottom: 0.5rem;
            }

            .grid.grid-cols-1.md\:grid-cols-2.gap-3 {
                grid-template-columns: 1fr;
                gap: 0.75rem;
            }

            .action-btn {
                width: 100%;
                text-align: center;
                padding: 0.75rem 1rem;
                height: 85%;
            }

            .w-full.md\:w-1\/2.p-4 {
                padding: 0.75rem;
                width: 100%;
            }

            .max-w-7xl.mx-auto.sm\:px-6.lg\:px-8.space-y-6 {
                padding-left: 0.75rem;
                padding-right: 0.75rem;
            }

            .flex.flex-wrap {
                margin-left: -0.75rem;
                margin-right: -0.75rem;
            }

            .text-2xl.font-bold {
                font-size: 1.5rem;
                line-height: 2rem;
                margin-left: 0.75rem;
            }

            .mb-6 {
                margin-bottom: 1rem;
            }

            .mb-4 {
                margin-bottom: 0.3rem;
            }
        }

        /* Дополнительные медиазапросы для очень маленьких экранов */
        @media (max-width: 480px) {
            .p-6 {
                padding: 1rem;
            }

            .text-2xl.font-bold {
                font-size: 1.25rem;
            }

            .dashboard-card {
                border-radius: 0.5rem;
            }
        }
    </style>
        <?php
        $__output = ob_get_clean();

        // If the asset has already been loaded anywhere during this request, skip it...
        if (in_array($__assetKey, \Livewire\Features\SupportScriptsAndAssets\SupportScriptsAndAssets::$alreadyRunAssetKeys)) {
            // Skip it...
        } else {
            \Livewire\Features\SupportScriptsAndAssets\SupportScriptsAndAssets::$alreadyRunAssetKeys[] = $__assetKey;

            // Check if we're in a Livewire component or not and store the asset accordingly...
            if (isset($this)) {
                \Livewire\store($this)->push('assets', $__output, $__assetKey);
            } else {
                \Livewire\Features\SupportScriptsAndAssets\SupportScriptsAndAssets::$nonLivewireAssets[$__assetKey] = $__output;
            }
        }
    ?>

    <div class="py-2">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="flex flex-wrap">
                <!-- Блок "Пользователи" -->
                <div class="w-full md:w-1/2 p-4">
                    <div class="dashboard-card bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg h-full">
                        <div class="p-6 text-gray-900 dark:text-gray-100">
                            <div class="flex items-center mb-4">
                                <div class="p-3 rounded-full bg-blue-100 dark:bg-blue-900 mr-4">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-blue-600 dark:text-blue-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                                    </svg>
                                </div>
                                <h1 class="text-2xl font-bold"><?php echo e(__('Пользователи')); ?></h1>
                            </div>
                            <p class="mb-6 text-gray-600 dark:text-gray-300"><?php echo e(__('Управление учетными записями пользователей.')); ?></p>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                                <a href="<?php echo e(route('admin.users.index')); ?>" class="action-btn inline-flex items-center justify-center px-4 py-3 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-900 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                    <?php echo e(__('Просмотр пользователей')); ?>

                                </a>
                                <a href="<?php echo e(route('admin.users.create')); ?>" class="action-btn inline-flex items-center justify-center px-4 py-3 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700 focus:bg-green-700 active:bg-green-900 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                    <?php echo e(__('Добавить пользователя')); ?>

                                </a>
                                <a href="<?php echo e(route('admin.users.index')); ?>" class="action-btn inline-flex items-center justify-center px-4 py-3 bg-yellow-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-yellow-700 focus:bg-yellow-700 active:bg-yellow-900 focus:outline-none focus:ring-2 focus:ring-yellow-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                    <?php echo e(__('Сменить пароль')); ?>

                                </a>
                                <a href="<?php echo e(route('admin.users.index')); ?>" class="action-btn inline-flex items-center justify-center px-4 py-3 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-700 focus:bg-red-700 active:bg-red-900 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                    <?php echo e(__('Удалить пользователя')); ?>

                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Блок "Справочные материалы" -->
                <div class="w-full md:w-1/2 p-4">
                    <div class="dashboard-card bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg h-full">
                        <div class="p-6 text-gray-900 dark:text-gray-100">
                            <div class="flex items-center mb-4">
                                <div class="p-3 rounded-full bg-indigo-100 dark:bg-indigo-900 mr-4">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-indigo-600 dark:text-indigo-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path d="M12 14l9-5-9-5-9 5 9 5z" />
                                        <path d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5zm0 0l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14zm-4 6v-7.5l4-2.222" />
                                    </svg>
                                </div>
                                <h1 class="text-2xl font-bold"><?php echo e(__('Справочные материалы')); ?></h1>
                            </div>
                            <p class="mb-6 text-gray-600 dark:text-gray-300"><?php echo e(__('Управление справочными маатериалами.')); ?></p>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                                <a href="<?php echo e(route('literature.index')); ?>" class="action-btn inline-flex items-center justify-center px-4 py-3 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-900 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                    <?php echo e(__('Список материалов')); ?>

                                </a>
                                <a href="<?php echo e(route('literature.create')); ?>" class="action-btn inline-flex items-center justify-center px-4 py-3 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700 focus:bg-green-700 active:bg-green-900 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                    <?php echo e(__('Добавить материал')); ?>

                                </a>
                                <a href="<?php echo e(route('literature.index')); ?>" class="action-btn inline-flex items-center justify-center px-4 py-3 bg-yellow-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-yellow-700 focus:bg-yellow-700 active:bg-yellow-900 focus:outline-none focus:ring-2 focus:ring-yellow-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                    <?php echo e(__('Редактировать материал')); ?>

                                </a>
                                <a href="<?php echo e(route('literature.index')); ?>" class="action-btn inline-flex items-center justify-center px-4 py-3 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-700 focus:bg-red-700 active:bg-red-900 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                    <?php echo e(__('Удалить материал')); ?>

                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Блок "Блоки на главной странице" -->
                <div class="w-full md:w-1/2 p-4">
                    <div class="dashboard-card bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg h-full">
                        <div class="p-6 text-gray-900 dark:text-gray-100">
                            <div class="flex items-center mb-4">
                                <div class="p-3 rounded-full bg-purple-100 dark:bg-purple-900 mr-4">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-purple-600 dark:text-purple-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 5a1 1 0 011-1h14a1 1 0 011 1v2a1 1 0 01-1 1H5a1 1 0 01-1-1V5zM4 13a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H5a1 1 0 01-1-1v-6zM16 13a1 1 0 011-1h2a1 1 0 011 1v6a1 1 0 01-1 1h-2a1 1 0 01-1-1v-6z" />
                                    </svg>
                                </div>
                                <h1 class="text-2xl font-bold"><?php echo e(__('Блоки')); ?></h1>
                            </div>
                            <p class="mb-6 text-gray-600 dark:text-gray-300"><?php echo e(__('Управление блоками на главной странице.')); ?></p>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                                <a href="<?php echo e(route('blocks.index')); ?>" class="action-btn inline-flex items-center justify-center px-4 py-3 bg-blue-500 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-900 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                    <?php echo e(__('Список блоков')); ?>

                                </a>
                                <a href="<?php echo e(route('blocks.create')); ?>" class="action-btn inline-flex items-center justify-center px-4 py-3 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700 focus:bg-green-700 active:bg-green-900 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                    <?php echo e(__('Добавить блок')); ?>

                                </a>
                                <a href="<?php echo e(route('blocks.index')); ?>" class="action-btn inline-flex items-center justify-center px-4 py-3 bg-yellow-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-yellow-700 focus:bg-yellow-700 active:bg-yellow-900 focus:outline-none focus:ring-2 focus:ring-yellow-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                    <?php echo e(__('Редактировать блок')); ?>

                                </a>
                                <a href="<?php echo e(route('blocks.index')); ?>" class="action-btn inline-flex items-center justify-center px-4 py-3 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-700 focus:bg-red-700 active:bg-red-900 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                    <?php echo e(__('Удалить блок')); ?>

                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Блок "Уведомления" -->
                <div class="w-full md:w-1/2 p-4">
                    <div class="dashboard-card bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg h-full">
                        <div class="p-6 text-gray-900 dark:text-gray-100">
                            <div class="flex items-center mb-4">
                                <div class="p-3 rounded-full bg-amber-100 dark:bg-amber-900 mr-4">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-amber-600 dark:text-amber-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                                    </svg>
                                </div>
                                <h1 class="text-2xl font-bold"><?php echo e(__('Уведомления')); ?></h1>
                            </div>
                            <p class="mb-6 text-gray-600 dark:text-gray-300"><?php echo e(__('Управление уведомлениями для пользователей.')); ?></p>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                                <a href="<?php echo e(route('admin.users.sent-notifications')); ?>" class="action-btn inline-flex items-center justify-center px-4 py-3 bg-blue-500 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-900 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                    <?php echo e(__('Отправленные уведомления')); ?>

                                </a>
                                <a href="<?php echo e(route('admin.users.show-send-notification')); ?>" class="action-btn inline-flex items-center justify-center px-4 py-3 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700 focus:bg-green-700 active:bg-green-900 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                    <?php echo e(__('Отправить уведомление')); ?>

                                </a>
                                <a href="<?php echo e(route('admin.users.sent-notifications')); ?>" class="action-btn inline-flex items-center justify-center px-4 py-3 bg-yellow-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-yellow-700 focus:bg-yellow-700 active:bg-yellow-900 focus:outline-none focus:ring-2 focus:ring-yellow-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                    <?php echo e(__('Редактировать уведомление')); ?>

                                </a>
                                <a href="<?php echo e(route('admin.users.sent-notifications')); ?>" class="action-btn inline-flex items-center justify-center px-4 py-3 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-700 focus:bg-red-700 active:bg-red-900 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                    <?php echo e(__('Удалить уведомление')); ?>

                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Блок "Регистрация" -->
                <div class="w-full md:w-1/2 p-4">
                    <div class="dashboard-card bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg h-full">
                        <div class="p-6 text-gray-900 dark:text-gray-100">
                            <div class="flex items-center mb-4">
                                <div class="p-3 rounded-full bg-emerald-100 dark:bg-emerald-900 mr-4">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-emerald-600 dark:text-emerald-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                                    </svg>
                                </div>
                                <h1 class="text-2xl font-bold"><?php echo e(__('Регистрация')); ?></h1>
                            </div>
                            <p class="mb-6 text-gray-600 dark:text-gray-300"><?php echo e(__('Управление регистрацией пользователей.')); ?></p>
                            <div class="flex flex-col space-y-4">
                                <a href="<?php echo e(route('admin.registration-control')); ?>" class="bg-gradient-to-r from-green-600 to-red-600 hover:brightness-90 action-btn inline-flex items-center justify-center px-4 py-3 rounded-md font-semibold text-xs text-white uppercase tracking-widest focus:outline-none transition ease-in-out duration-150">
                                    <?php echo e(__('Включение/Отключение регистрации')); ?>

                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Блок "База знаний" -->
                <div class="w-full md:w-1/2 p-4">
                    <div class="dashboard-card bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg h-full">
                        <div class="p-6 text-gray-900 dark:text-gray-100">
                            <div class="flex items-center mb-4">
                                <div class="p-3 rounded-full bg-sky-100 dark:bg-sky-900 mr-4">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-sky-600 dark:text-sky-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                    </svg>
                                </div>
                                <h1 class="text-2xl font-bold"><?php echo e(__('База знаний')); ?></h1>
                            </div>
                            <p class="mb-6 text-gray-600 dark:text-gray-300"><?php echo e(__('Управление заметками пользователей в базе знаний.')); ?></p>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                                <a href="<?php echo e(route('notes.index')); ?>" class="action-btn inline-flex items-center justify-center px-4 py-3 bg-blue-500 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-900 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                    <?php echo e(__('Список заметок')); ?>

                                </a>
                                <a href="<?php echo e(route('notes.create')); ?>" class="action-btn inline-flex items-center justify-center px-4 py-3 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700 focus:bg-green-700 active:bg-green-900 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                    <?php echo e(__('Создать заметку')); ?>

                                </a>
                                <a href="<?php echo e(route('notes.index')); ?>" class="action-btn inline-flex items-center justify-center px-4 py-3 bg-yellow-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-yellow-700 focus:bg-yellow-700 active:bg-yellow-900 focus:outline-none focus:ring-2 focus:ring-yellow-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                    <?php echo e(__('Редактировать заметку')); ?>

                                </a>
                                <a href="<?php echo e(route('notes.index')); ?>" class="action-btn inline-flex items-center justify-center px-4 py-3 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-700 focus:bg-red-700 active:bg-red-900 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                    <?php echo e(__('Удалить заметку')); ?>

                                </a>
                            </div>
                        </div>
                    </div>
                </div>

            

                <!-- Блок "Файлы" -->
                <div class="w-full md:w-1/2 p-4">
                    <div class="dashboard-card bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg h-full">
                        <div class="p-6 text-gray-900 dark:text-gray-100">
                            <div class="flex items-center mb-4">
                                <div class="p-3 rounded-full bg-violet-100 dark:bg-violet-900 mr-4">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-violet-600 dark:text-violet-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8l-6-6z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 2v6h6" />
                                    </svg>

                                </div>
                                <h1 class="text-2xl font-bold"><?php echo e(__('Файлы')); ?></h1>
                            </div>
                            <p class="mb-6 text-gray-600 dark:text-gray-300"><?php echo e(__('Управление файлами пользователей.')); ?></p>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                                <a href="<?php echo e(url('/my-files')); ?>" class="action-btn inline-flex items-center justify-center px-4 py-3 bg-blue-500 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-900 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                    <?php echo e(__('Список моих файлов')); ?>

                                </a>
                                <a href="<?php echo e(route('admin.files')); ?>" class="action-btn inline-flex items-center justify-center px-4 py-3 bg-yellow-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-yellow-700 focus:bg-yellow-700 active:bg-yellow-900 focus:outline-none focus:ring-2 focus:ring-yellow-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                    <?php echo e(__('Администрирование')); ?>

                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Блок "Статистика" -->
                <div class="w-full md:w-1/2 p-4">
                    <div class="dashboard-card bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg h-full">
                        <div class="p-6 text-gray-900 dark:text-gray-100">
                            <div class="flex items-center mb-4">
                                <div class="p-3 rounded-full bg-blue-100 dark:bg-blue-900 mr-4">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-blue-600 dark:text-blue-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 17l6-6 4 4 8-8" />
                                    </svg>
                                </div>
                                <h1 class="text-2xl font-bold"><?php echo e(__('Статистика')); ?></h1>
                            </div>
                            <p class="mb-6 text-gray-600 dark:text-gray-300"><?php echo e(__('Просмотр истории посещения сайта.')); ?></p>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                                <a href="<?php echo e(route('admin.statistics.visits')); ?>" class="action-btn inline-flex items-center justify-center px-4 py-3 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-900 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                    <?php echo e(__('Просмотр истории')); ?>

                                </a>                         
                                <a href="<?php echo e(route('visits.deletePage')); ?>" class="action-btn inline-flex items-center justify-center px-4 py-3 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-700 focus:bg-red-700 active:bg-red-900 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                    <?php echo e(__('Удаление истории')); ?>

                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54)): ?>
<?php $attributes = $__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54; ?>
<?php unset($__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal9ac128a9029c0e4701924bd2d73d7f54)): ?>
<?php $component = $__componentOriginal9ac128a9029c0e4701924bd2d73d7f54; ?>
<?php unset($__componentOriginal9ac128a9029c0e4701924bd2d73d7f54); ?>
<?php endif; ?><?php /**PATH C:\Users\Desktop_54\Desktop\neocalc.site_share_link_30.05\resources\views/admin/index.blade.php ENDPATH**/ ?>
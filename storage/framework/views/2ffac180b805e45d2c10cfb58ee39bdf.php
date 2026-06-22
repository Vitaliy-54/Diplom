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
                <?php echo e(__('Справочные материалы')); ?>

            </h2>
            <div class="text-sm text-gray-800 dark:text-gray-400 animate__animated animate__fadeIn">
                <?php echo e(now()->translatedFormat('d F Y')); ?>

            </div>
        </div>
     <?php $__env->endSlot(); ?>

     <?php $__env->slot('title', null, []); ?> 
        <?php echo e(__('Справочные материалы')); ?>

     <?php $__env->endSlot(); ?>

    <div class="py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Карточка с общей статистикой -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                <div class="bg-gray-300 dark:bg-gray-700 rounded-xl shadow p-4 border-l-4 border-blue-500">
                    <div class="text-gray-500 dark:text-gray-300 text-sm font-medium"><?php echo e(__('Всего материалов')); ?></div>
                    <div class="text-2xl font-bold text-gray-800 dark:text-white mt-1"><?php echo e(count($literatures)); ?></div>
                </div>
                <div class="bg-gray-300 dark:bg-gray-700 rounded-xl shadow p-4 border-l-4 border-green-500">
                    <div class="text-gray-500 dark:text-gray-300 text-sm font-medium"><?php echo e(__('Категорий')); ?></div>
                    <div class="text-2xl font-bold text-gray-800 dark:text-white mt-1"><?php echo e($categories->count()); ?></div>
                </div>
            </div>

            <!-- Фильтр по категориям -->
            <?php if($categories->count()): ?>
            <div class="mb-6 flex flex-wrap gap-2">
                <a href="<?php echo e(route('literature.index')); ?>"
                    class="px-3 py-2 rounded-lg text-base font-medium
              <?php echo e(!$category ? 'bg-blue-600 text-white' : 'bg-gray-300 dark:bg-gray-600 text-gray-800 dark:text-gray-200'); ?>">
                    Все материалы
                </a>

                <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cat): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <a href="<?php echo e(route('literature.index', ['category' => $cat])); ?>"
                    class="px-3 py-2 rounded-lg text-base font-medium
                  <?php echo e($category === $cat ? 'bg-blue-600 text-white' : 'bg-gray-300 dark:bg-gray-600 text-gray-800 dark:text-gray-200'); ?>">
                    <?php echo e($cat); ?>

                </a>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
            <?php endif; ?>

            <!-- Основная карточка -->
            <div class="bg-gray-300 dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-xl">
                <div class="p-6">
                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-6 gap-4">
                        <div>
                            <h1 class="text-2xl font-bold text-gray-900 dark:text-white flex items-baseline gap-2">
                                <span><?php echo e(__('Справочные материалы ')); ?></span>
                                <?php if($category): ?>
                                <span class="text-xl font-semibold text-blue-700 dark:text-blue-400">(Категория: <?php echo e($category); ?>)</span>
                                <?php endif; ?>
                            </h1>
                        </div>

                        <?php if(auth()->guard()->check()): ?>
                        <?php if(auth()->user()->role === 'admin'): ?>
                        <a href="<?php echo e(route('literature.create')); ?>" class="inline-flex items-center px-4 py-2 bg-lime-600 hover:bg-lime-700 text-white font-medium rounded-lg transition duration-200 shadow-sm">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd" />
                            </svg>
                            <?php echo e(__('Добавить материал')); ?>

                        </a>
                        <?php endif; ?>
                        <?php endif; ?>
                    </div>

                    <?php if(session('success')): ?>
                    <div class="mb-6 px-4 py-3 bg-green-100 dark:bg-green-900 text-green-700 dark:text-green-200 rounded-lg">
                        <div class="flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                            </svg>
                            <?php echo e(session('success')); ?>

                        </div>
                    </div>
                    <?php endif; ?>

                    <div class="overflow-x-auto rounded-lg border border-gray-400 dark:border-gray-700">
                        <table class="min-w-full divide-y divide-gray-400 dark:divide-gray-700">
                            <thead class="bg-gray-300 dark:bg-gray-600 text-gray-700 dark:text-gray-200">
                                <tr>
                                    <th class="px-4 py-3 text-left text-sm font-semibold">Название</th>
                                    <th class="px-4 py-3 text-left text-sm font-semibold">Описание</th>
                                    <th class="px-4 py-3 text-left text-sm font-semibold">Категория</th>
                                    <th class="px-4 py-3 text-left text-sm font-semibold">Размер</th>
                                    <th class="px-4 py-3 text-left text-sm font-semibold">Добавил</th>
                                    <th class="px-4 py-3 text-center text-sm font-semibold">Действия</th>
                                </tr>
                            </thead>

                            <tbody class="bg-gray-200 dark:bg-gray-800 divide-y divide-gray-400 dark:divide-gray-700">
                                <?php $__empty_1 = true; $__currentLoopData = $literatures; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $literature): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                <tr class="hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors">
                                    <!-- Название -->
                                    <td class="px-4 py-3 font-medium text-gray-900 dark:text-white align-middle">
                                        <div class="max-w-[300px] break-words">
                                            <?php echo e($literature->title ?: '—'); ?>

                                        </div>
                                    </td>

                                    <!-- Описание -->
                                    <td class="px-4 py-3 text-sm text-gray-600 dark:text-gray-300 align-middle">
                                        <div class="max-w-[400px] break-words">
                                            <?php if($literature->description): ?>
                                                <?php echo e($literature->description); ?>

                                            <?php else: ?>
                                                <span class="text-gray-400 dark:text-gray-500">—</span>
                                            <?php endif; ?>
                                        </div>
                                    </td>

                                    <!-- Категория -->
                                    <td class="px-4 py-3 align-middle">
                                        <?php if($literature->category): ?>
                                        <span class="inline-flex px-2 py-1 text-xs bg-blue-100 dark:bg-blue-900 text-blue-800 dark:text-blue-200 rounded-full whitespace-nowrap">
                                            <?php echo e($literature->category); ?>

                                        </span>
                                        <?php else: ?>
                                            <span class="text-gray-400 dark:text-gray-500">—</span>
                                        <?php endif; ?>
                                    </td>

                                    <!-- Размер -->
                                    <td class="px-4 py-3 text-sm text-gray-600 dark:text-gray-300 align-middle whitespace-nowrap">
                                        <?php echo e($literature->file_size_formatted ?: '—'); ?>

                                    </td>

                                    <!-- Добавил -->
                                    <td class="px-4 py-3 text-sm text-gray-600 dark:text-gray-300 align-middle whitespace-nowrap">
                                        <?php echo e($literature->user->name ?? '—'); ?>

                                    </td>

                                    <!-- Действия -->
                                    <td class="px-4 py-3 text-center align-middle">
                                        <div class="flex items-center justify-center gap-3 min-h-[40px]">
                                            <!-- Скачать (всегда показываем) -->
                                            <a href="<?php echo e(route('literature.download', $literature)); ?>"
                                            class="inline-flex items-center justify-center w-8 h-8 text-green-600 hover:text-green-700 dark:text-green-500 dark:hover:text-green-400 transition transform hover:scale-110"
                                            title="Скачать">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                                                    viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                                                </svg>
                                            </a>

                                            <?php if(auth()->guard()->check()): ?>
                                            <?php if(auth()->user()->role === 'admin' || auth()->id() === $literature->user_id): ?>
                                                <!-- Редактировать -->
                                                <a href="<?php echo e(route('literature.edit', $literature)); ?>"
                                                class="inline-flex items-center justify-center w-8 h-8 text-yellow-600 hover:text-yellow-700 dark:text-yellow-500 dark:hover:text-yellow-400 transition transform hover:scale-110"
                                                title="Редактировать">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                                                        viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                            d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414
                                                                a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                                    </svg>
                                                </a>

                                                <!-- Удалить -->
                                                <form method="POST" action="<?php echo e(route('literature.destroy', $literature)); ?>" class="inline-flex">
                                                    <?php echo csrf_field(); ?>
                                                    <?php echo method_field('DELETE'); ?>
                                                    <button type="submit"
                                                        onclick="return confirm('Вы уверены, что хотите удалить этот материал?')"
                                                        class="inline-flex items-center justify-center w-8 h-8 text-red-600 hover:text-red-700 dark:text-red-500 dark:hover:text-red-400 transition transform hover:scale-110"
                                                        title="Удалить">
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                                                            viewBox="0 0 24 24" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862
                                                                    a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6
                                                                    m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                                        </svg>
                                                    </button>
                                                </form>
                                            <?php endif; ?>
                                            <?php endif; ?>
                                        </div>
                                    </td>
                                </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                <tr>
                                    <td colspan="6" class="text-center py-10 text-gray-500 dark:text-gray-400">
                                        <div class="flex flex-col items-center justify-center">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 mt-6 mb-3 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                                            </svg>
                                            <p class="text-lg mb-6">Материалы не найдены</p>
                                        </div>
                                    </td>
                                </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php $__env->startPush('styles'); ?>
    <style>
        /* Стили для таблицы */
        table {
            border-collapse: separate;
            border-spacing: 0;
            width: 100%;
            table-layout: auto;
        }
        
        th {
            position: sticky;
            top: 0;
            z-index: 10;
            background-color: inherit;
        }
        
        td, th {
            vertical-align: middle !important; /* Принудительное выравнивание по центру */
            padding: 12px 16px;
        }
        
        /* Анимация для кнопок */
        .transition {
            transition: all 0.2s ease-in-out;
        }
        
        /* Ховер эффект для строк */
        tbody tr:hover {
            background-color: rgba(0, 0, 0, 0.02);
        }
        
        .dark tbody tr:hover {
            background-color: rgba(255, 255, 255, 0.02);
        }

        /* Перенос слов для длинных названий и описаний */
        .break-words {
            word-break: break-word;
            overflow-wrap: break-word;
        }
        
        /* Фиксированная высота для контейнера с иконками */
        .min-h-\[40px\] {
            min-height: 40px;
        }
        
        /* Единый размер для кнопок-иконок */
        .w-8 {
            width: 2rem;
        }
        
        .h-8 {
            height: 2rem;
        }
        
        /* Адаптивность для мобильных устройств */
        @media (max-width: 768px) {
            td, th {
                padding: 8px 12px;
            }
            
            .max-w-\[300px\] {
                max-width: 200px;
            }
            
            .max-w-\[400px\] {
                max-width: 250px;
            }
        }

        /* Дополнительные стили для выравнивания */
        .align-middle {
            vertical-align: middle !important;
        }

        /* Фиксированный размер для контейнера иконок */
        .inline-flex.items-center.justify-center {
            flex-shrink: 0;
        }

        /* Убираем возможные отступы у форм */
        form.inline-flex {
            display: inline-flex;
        }
    </style>
    <?php $__env->stopPush(); ?>
 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54)): ?>
<?php $attributes = $__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54; ?>
<?php unset($__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal9ac128a9029c0e4701924bd2d73d7f54)): ?>
<?php $component = $__componentOriginal9ac128a9029c0e4701924bd2d73d7f54; ?>
<?php unset($__componentOriginal9ac128a9029c0e4701924bd2d73d7f54); ?>
<?php endif; ?><?php /**PATH C:\Users\Desktop_54\Desktop\neocalc.site_share_link_30.05\resources\views/literature/index.blade.php ENDPATH**/ ?>
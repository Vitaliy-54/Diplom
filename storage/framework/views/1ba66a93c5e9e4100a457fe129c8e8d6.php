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
                <?php echo e(__('Пользователи')); ?>

            </h2>
            <div class="text-sm text-gray-800 dark:text-gray-400 animate__animated animate__fadeIn">
                <?php echo e(now()->translatedFormat('d F Y')); ?>

            </div>
        </div>
     <?php $__env->endSlot(); ?>

     <?php $__env->slot('title', null, []); ?> 
        <?php echo e(__('Пользователи')); ?>

     <?php $__env->endSlot(); ?>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6 px-4 sm:px-0">
            <!-- Карточка с общей статистикой - адаптированная для мобильных -->
            <div class="grid grid-cols-2 gap-3 sm:grid-cols-4 sm:gap-4">
                <!-- Всего пользователей -->
                <div class="bg-gray-300 dark:bg-gray-700 rounded-lg shadow p-3 sm:p-4">
                    <div class="text-gray-500 dark:text-gray-300 text-xs sm:text-sm font-medium"><?php echo e(__('Всего пользователей')); ?></div>
                    <div class="text-xl sm:text-2xl font-bold text-gray-800 dark:text-white mt-1"><?php echo e($users->count()); ?></div>
                </div>

                <!-- Онлайн -->
                <div class="bg-gray-300 dark:bg-gray-700 rounded-lg shadow p-3 sm:p-4">
                    <div class="text-gray-500 dark:text-gray-300 text-xs sm:text-sm font-medium"><?php echo e(__('Онлайн')); ?></div>
                    <div class="text-xl sm:text-2xl font-bold text-green-600 dark:text-green-400 mt-1" id="online-count">
                        <?php echo e($users->filter(fn($user) => $user->id === auth()->id() || ($user->lastSession && $user->lastSession->last_activity > now()->subMinutes(5)->timestamp))->count()); ?>

                    </div>
                </div>

                <!-- Подтверждённые -->
                <div class="bg-gray-300 dark:bg-gray-700 rounded-lg shadow p-3 sm:p-4">
                    <div class="text-gray-500 dark:text-gray-300 text-xs sm:text-sm font-medium"><?php echo e(__('Подтверждённые')); ?></div>
                    <div class="text-xl sm:text-2xl font-bold text-blue-600 dark:text-blue-400 mt-1">
                        <?php echo e($users->whereNotNull('email_verified_at')->count()); ?>

                    </div>
                </div>

                <!-- Администраторы -->
                <div class="bg-gray-300 dark:bg-gray-700 rounded-lg shadow p-3 sm:p-4">
                    <div class="text-gray-500 dark:text-gray-300 text-xs sm:text-sm font-medium"><?php echo e(__('Администраторы')); ?></div>
                    <div class="text-xl sm:text-2xl font-bold text-purple-600 dark:text-purple-400 mt-1">
                        <?php echo e($users->where('role', 'admin')->count()); ?>

                    </div>
                </div>
            </div>

            <!-- Основная карточка с таблицей -->
            <div class="bg-gray-300 dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-6 gap-4">
                        <h1 class="text-2xl font-bold"><?php echo e(__('Список пользователей')); ?></h1>

                        <div class="flex items-center space-x-2">
                            <div class="relative">
                                <input type="text" id="user-search" placeholder="<?php echo e(__('Поиск пользователей...')); ?>"
                                    class="bg-gray-200 dark:bg-gray-700 border border-gray-400 dark:border-gray-600 rounded-lg px-4 py-2 pl-10 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 w-full md:w-64">
                                <div class="absolute left-3 top-2.5 text-gray-400">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                    </svg>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Таблица пользователей -->
                    <div class="overflow-x-auto rounded-lg border border-gray-400 dark:border-gray-700">
                        <table class="min-w-full divide-y divide-gray-400 dark:divide-gray-700">
                            <thead class="bg-gray-300 dark:bg-gray-700">
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                        <div class="flex items-center">
                                            <?php echo e(__('Пользователь')); ?>

                                            <button class="ml-1 focus:outline-none">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4" />
                                                </svg>
                                            </button>
                                        </div>
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                        <?php echo e(__('Роль')); ?>

                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                        <?php echo e(__('Статус')); ?>

                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                        <?php echo e(__('Дата регистрации')); ?>

                                    </th>
                                    <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                        <?php echo e(__('Действия')); ?>

                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-gray-200 dark:bg-gray-800 divide-y divide-gray-400 dark:divide-gray-700" id="users-table-body">
                                <?php $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr class="hover:bg-gray-300 dark:hover:bg-gray-700 transition-colors" data-user-id="<?php echo e($user->id); ?>">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div class="flex-shrink-0 h-10 w-10">
                                                <?php
                                                $avatarDir = "avatars/{$user->id}";
                                                $avatarFile = collect(Storage::files($avatarDir))
                                                ->first(fn($f) => preg_match('/^avatars\/' . $user->id . '\/avatar\.(jpg|jpeg|png|svg|gif)$/i', $f));
                                                ?>

                                                <div class="h-10 w-10 rounded-full bg-gray-200 dark:bg-gray-600 overflow-hidden flex items-center justify-center">
                                                    <?php if($avatarFile): ?>
                                                    <img src="<?php echo e(route('avatar.serve', [
                        'user' => $user->id,
                        'filename' => basename($avatarFile)
                    ])); ?>"
                                                        alt="Avatar"
                                                        class="h-full w-full object-cover">
                                                    <?php else: ?>
                                                    
                                                    <?php if($user->role === 'admin'): ?>
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-purple-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                            d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                                    </svg>
                                                    <?php else: ?>
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                            d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                                    </svg>
                                                    <?php endif; ?>
                                                    <?php endif; ?>
                                                </div>
                                            </div>

                                            <div class="ml-4">
                                                
                                                <a href="<?php echo e(route('user.info', ['user' => $user->id])); ?>"
                                                    class="text-sm font-medium text-gray-900 dark:text-white hover:text-blue-700 dark:hover:text-blue-400 transition-colors">
                                                    <?php echo e($user->name); ?>

                                                    <?php if($user->id === auth()->id()): ?>
                                                    <span class="ml-2 px-2 py-0.5 text-xs rounded bg-blue-200 dark:bg-blue-900 text-blue-800 dark:text-blue-200"><?php echo e(__('Вы')); ?></span>
                                                    <?php endif; ?>
                                                </a>

                                                
                                                <div class="text-sm text-gray-500 dark:text-gray-400">
                                                    <?php echo e($user->email); ?>

                                                    <?php if($user->email_verified_at): ?>
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 inline text-green-500 ml-1" viewBox="0 0 20 20" fill="currentColor">
                                                        <path fill-rule="evenodd" d="M6.267 3.455a3.066 3.066 0 001.745-.723 3.066 3.066 0 013.976 0 3.066 3.066 0 001.745.723 3.066 3.066 0 012.812 2.812c.051.643.304 1.254.723 1.745a3.066 3.066 0 010 3.976 3.066 3.066 0 00-.723 1.745 3.066 3.066 0 01-2.812 2.812 3.066 3.066 0 00-1.745.723 3.066 3.066 0 01-3.976 0 3.066 3.066 0 00-1.745-.723 3.066 3.066 0 01-2.812-2.812 3.066 3.066 0 00-.723-1.745 3.066 3.066 0 010-3.976 3.066 3.066 0 00.723-1.745 3.066 3.066 0 012.812-2.812zm7.44 5.252a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                                    </svg>
                                                    <?php endif; ?>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                            <?php echo e($user->role === 'admin' ? 'bg-purple-200 dark:bg-purple-900 text-purple-900 dark:text-purple-200' : 'bg-green-200 dark:bg-green-900 text-green-900 dark:text-green-200'); ?>">
                                            <?php echo e($user->role === 'admin' ? __('Администратор') : __('Пользователь')); ?>

                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <span class="relative flex h-3 w-3 mr-2">
                                                <span id="status-indicator-<?php echo e($user->id); ?>" class="animate-ping absolute inline-flex h-full w-full rounded-full 
                                                    <?php echo e($user->id === auth()->id() || ($user->lastSession && $user->lastSession->last_activity > now()->subMinutes(5)->timestamp) ? 'bg-green-400 opacity-75' : 'bg-red-400 opacity-75'); ?>"></span>
                                                <span class="relative inline-flex rounded-full h-3 w-3 
                                                    <?php echo e($user->id === auth()->id() || ($user->lastSession && $user->lastSession->last_activity > now()->subMinutes(5)->timestamp) ? 'bg-green-500' : 'bg-red-500'); ?>"></span>
                                            </span>
                                            <div>
                                                <!-- Статус пользователя -->
                                                <span id="user-status-text-<?php echo e($user->id); ?>">
                                                    <?php if($user->id === auth()->id()): ?>
                                                    <span class="text-green-500 font-semibold"><?php echo e(__('Онлайн')); ?></span>
                                                    <?php elseif($user->lastSession && $user->lastSession->last_activity > now()->subMinutes(5)->timestamp): ?>
                                                    <span class="text-green-500 font-semibold"><?php echo e(__('Онлайн')); ?></span>
                                                    <?php else: ?>
                                                    <span class="text-red-500 font-semibold"><?php echo e(__('Оффлайн')); ?></span>
                                                    <?php endif; ?>
                                                </span>
                                                <!-- Время последней активности -->
                                                <div class="text-xs text-gray-500 dark:text-gray-400" id="user-last-activity-<?php echo e($user->id); ?>">
                                                    <?php if($user->logs->first() && $user->logs->first()->last_activity_at): ?>
                                                    <?php echo e(__('Последняя активность:')); ?> <?php echo e(Carbon\Carbon::parse($user->logs->first()->last_activity_at)->format('d.m.Y H:i')); ?>

                                                    <?php else: ?>
                                                    <?php echo e(__('Нет данных о последней активности')); ?>

                                                    <?php endif; ?>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900 dark:text-white"><?php echo e($user->created_at->format('d.m.Y')); ?></div>
                                        <div class="text-xs text-gray-500 dark:text-gray-400"><?php echo e($user->created_at->format('H:i')); ?></div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                        <?php if(auth()->user()->id !== $user->id): ?>
                                        <div class="flex items-center justify-end space-x-2">
                                            <div class="flex items-center justify-end space-x-2">
                                                <!-- Кнопка смены роли -->
                                                <form method="POST" action="<?php echo e(route('admin.users.change-role', $user->id)); ?>" class="inline">
                                                    <?php echo csrf_field(); ?>
                                                    <?php echo method_field('PATCH'); ?>
                                                    <button type="submit"
                                                        class="text-yellow-600 dark:text-yellow-400 hover:text-yellow-900 dark:hover:text-yellow-300 transition-colors"
                                                        title="<?php echo e($user->role === 'admin' ? __('Понизить до пользователя') : __('Повысить до администратора')); ?>">
                                                        <?php if($user->role === 'admin'): ?>
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3" />
                                                        </svg>
                                                        <?php else: ?>
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4" />
                                                        </svg>
                                                        <?php endif; ?>
                                                    </button>
                                                </form>
                                                <!-- Кнопка изменения email -->
                                                <?php if(auth()->user()->role === 'admin'): ?>
                                                <a href="<?php echo e(route('admin.users.edit-email', $user->id)); ?>"
                                                    class="text-indigo-600 dark:text-indigo-400 hover:text-indigo-900 dark:hover:text-indigo-300 transition-colors"
                                                    title="<?php echo e(__('Изменить email')); ?>">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                            d="M4 4h16v16H4V4zm0 4l8 6 8-6" />
                                                    </svg>
                                                </a>
                                                <?php endif; ?>
                                                <a href="<?php echo e(route('admin.users.edit-password', $user->id)); ?>"
                                                    class="text-blue-600 dark:text-blue-400 hover:text-blue-900 dark:hover:text-blue-300 transition-colors"
                                                    title="<?php echo e(__('Изменить пароль')); ?>">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z" />
                                                    </svg>
                                                </a>
                                                <form method="POST" action="<?php echo e(route('admin.users.destroy', $user->id)); ?>" class="inline">
                                                    <?php echo csrf_field(); ?>
                                                    <?php echo method_field('DELETE'); ?>
                                                    <button type="submit"
                                                        class="text-red-600 dark:text-red-400 hover:text-red-900 dark:hover:text-red-300 transition-colors"
                                                        onclick="return confirm('<?php echo e(__('Вы уверены, что хотите удалить этого пользователя?')); ?>')"
                                                        title="<?php echo e(__('Удалить пользователя')); ?>">
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                        </svg>
                                                    </button>
                                                </form>
                                            </div>
                                            <?php else: ?>
                                            <span class="text-gray-500 dark:text-gray-400 text-sm"><?php echo e(__('Текущий пользователь')); ?></span>
                                            <?php endif; ?>
                                    </td>
                                </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Информационные карточки -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Карточка с инструкцией -->
                <div class="bg-gray-300 dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-3 flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-blue-500" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2h-1V9z" clip-rule="evenodd" />
                            </svg>
                            <?php echo e(__('Как использовать эту панель')); ?>

                        </h3>
                        <ul class="space-y-2 text-gray-600 dark:text-gray-300">
                            <li class="flex items-start">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-green-500 mt-0.5 flex-shrink-0" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                </svg>
                                <span><?php echo e(__('Статус пользователей обновляется автоматически каждую минуту')); ?></span>
                            </li>
                            <li class="flex items-start">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-green-500 mt-0.5 flex-shrink-0" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                </svg>
                                <span><?php echo e(__('Используйте поиск для быстрого нахождения пользователей')); ?></span>
                            </li>
                            <li class="flex items-start">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-green-500 mt-0.5 flex-shrink-0" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                </svg>
                                <span><?php echo e(__('Вы не можете удалить или изменить пароль своей учетной записи на этой странице')); ?></span>
                            </li>
                        </ul>
                    </div>
                </div>

                <!-- Карточка со статусами -->
                <div class="bg-gray-300 dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-3 flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-green-500" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M11.3 1.046A1 1 0 0112 2v5h4a1 1 0 01.82 1.573l-7 10A1 1 0 018 18v-5H4a1 1 0 01-.82-1.573l7-10a1 1 0 011.12-.38z" clip-rule="evenodd" />
                            </svg>
                            <?php echo e(__('Статусы пользователей')); ?>

                        </h3>
                        <div class="space-y-3">
                            <div class="flex items-center">
                                <span class="relative flex h-3 w-3 mr-3">
                                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-green-400 opacity-75"></span>
                                    <span class="relative inline-flex rounded-full h-3 w-3 bg-green-500"></span>
                                </span>
                                <span class="text-sm text-gray-600 dark:text-gray-300"><?php echo e(__('Онлайн - активность в последние 5 минут')); ?></span>
                            </div>
                            <div class="flex items-center">
                                <span class="relative flex h-3 w-3 mr-3">
                                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-red-400 opacity-75"></span>
                                    <span class="relative inline-flex rounded-full h-3 w-3 bg-red-500"></span>
                                </span>
                                <span class="text-sm text-gray-600 dark:text-gray-300"><?php echo e(__('Оффлайн - нет активности более 5 минут')); ?></span>
                            </div>
                            <div class="flex items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-3 text-green-500" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M6.267 3.455a3.066 3.066 0 001.745-.723 3.066 3.066 0 013.976 0 3.066 3.066 0 001.745.723 3.066 3.066 0 012.812 2.812c.051.643.304 1.254.723 1.745a3.066 3.066 0 010 3.976 3.066 3.066 0 00-.723 1.745 3.066 3.066 0 01-2.812 2.812 3.066 3.066 0 00-1.745.723 3.066 3.066 0 01-3.976 0 3.066 3.066 0 00-1.745-.723 3.066 3.066 0 01-2.812-2.812 3.066 3.066 0 00-.723-1.745 3.066 3.066 0 010-3.976 3.066 3.066 0 00.723-1.745 3.066 3.066 0 012.812-2.812zm7.44 5.252a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                </svg>
                                <span class="text-sm text-gray-600 dark:text-gray-300"><?php echo e(__('Подтверждённый email')); ?></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        // Функция для обновления статуса пользователей
        function updateUserStatuses() {
            $.ajax({
                url: "<?php echo e(route('admin.users.status')); ?>",
                method: "GET",
                success: function(response) {
                    // Обновляем счетчик онлайн пользователей
                    $('#online-count').text(response.online_count);

                    // Обновляем статус для каждого пользователя
                    response.users.forEach(function(user) {
                        let statusIndicator = $(`#status-indicator-${user.id}`);
                        let statusDot = statusIndicator.next();
                        let statusText = $(`#user-status-text-${user.id}`);
                        let lastActivityCell = $(`#user-last-activity-${user.id}`);

                        if (user.is_online) {
                            // Обновляем индикатор статуса
                            statusIndicator.removeClass('bg-red-400').addClass('bg-green-400');
                            statusDot.removeClass('bg-red-500').addClass('bg-green-500');
                            statusText.html('<span class="text-green-500 font-semibold"><?php echo e(__("Онлайн")); ?></span>');

                            if (user.last_activity_at) {
                                lastActivityCell.html(`<div class="text-xs text-gray-500 dark:text-gray-400"><?php echo e(__("Последняя активность:")); ?> ${user.last_activity_at}</div>`);
                            } else {
                                lastActivityCell.html('');
                            }
                        } else {
                            // Обновляем индикатор статуса
                            statusIndicator.removeClass('bg-green-400').addClass('bg-red-400');
                            statusDot.removeClass('bg-green-500').addClass('bg-red-500');
                            statusText.html('<span class="text-red-500 font-semibold"><?php echo e(__("Оффлайн")); ?></span>');

                            if (user.last_activity_at) {
                                lastActivityCell.html(`<div class="text-xs text-gray-500 dark:text-gray-400"><?php echo e(__("Последняя активность:")); ?> ${user.last_activity_at}</div>`);
                            } else {
                                lastActivityCell.html('<div class="text-xs text-gray-500 dark:text-gray-400"><?php echo e(__("Нет данных о последней активности")); ?></div>');
                            }
                        }
                    });
                },
                error: function(xhr) {
                    console.error("Ошибка при обновлении статуса пользователей");
                }
            });
        }

        // Обновляем статус каждые 60 секунд
        setInterval(updateUserStatuses, 60000);

        // Первоначальное обновление статуса при загрузке страницы
        $(document).ready(function() {
            updateUserStatuses();

            // Поиск пользователей
            $('#user-search').on('input', function() {
                let searchText = $(this).val().toLowerCase();
                $('#users-table-body tr').each(function() {
                    let userText = $(this).text().toLowerCase();
                    if (userText.includes(searchText)) {
                        $(this).show();
                    } else {
                        $(this).hide();
                    }
                });
            });
        });
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
<?php endif; ?><?php /**PATH C:\Users\Desktop_54\Desktop\neocalc.site_share_link_30.05\resources\views/admin/users/index.blade.php ENDPATH**/ ?>
<?php if($notes->isEmpty()): ?>
<div class="mb-5 overflow-hidden rounded-2xl border border-red-200 bg-gradient-to-r from-red-100 to-red-50 p-5 shadow-sm dark:border-red-900/50 dark:from-red-950 dark:to-red-900/40">
    <div class="flex items-center gap-3">
        <div class="flex h-11 w-11 items-center justify-center rounded-full bg-red-200 dark:bg-red-800/60">
            <i class="fas fa-search text-base text-red-700 dark:text-red-300"></i>
        </div>

        <div>
            <h3 class="text-base font-semibold text-red-800 dark:text-red-200">
                <?php echo e(__('Ничего не найдено')); ?>

            </h3>

            <p class="text-sm text-red-700 dark:text-red-300">
                <?php echo e(__('Ничего не найдено по вашему запросу.')); ?>

            </p>
        </div>
    </div>
</div>
<?php else: ?>

<div class="grid grid-cols-1 gap-5">
<?php $__currentLoopData = $notes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $note): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>

<div class="group relative overflow-hidden rounded-2xl border border-gray-400/70 bg-white/95 shadow-md transition-all duration-300 hover:shadow-xl dark:border-gray-500/50 dark:bg-gray-900/95"
    data-user-id="<?php echo e($note->user_id); ?>"
    data-is-public="<?php echo e($note->is_public); ?>"
    data-note-id="<?php echo e($note->id); ?>">

    <!-- Header -->
    <div class="relative flex items-center justify-between border-b border-gray-200/70 bg-gradient-to-r from-gray-400/60 to-gray-400/40 px-5 py-4 dark:border-gray-700/60 dark:from-gray-700/60 dark:to-gray-800">

        <a href="<?php echo e(route('user.info', ['user' => $note->user->id])); ?>"
           class="group/user flex items-center gap-3">

            <div>
                <?php if (isset($component)) { $__componentOriginal8ca5b43b8fff8bb34ab2ba4eb4bdd67b = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal8ca5b43b8fff8bb34ab2ba4eb4bdd67b = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.avatar','data' => ['user' => $note->user]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('avatar'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['user' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($note->user)]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal8ca5b43b8fff8bb34ab2ba4eb4bdd67b)): ?>
<?php $attributes = $__attributesOriginal8ca5b43b8fff8bb34ab2ba4eb4bdd67b; ?>
<?php unset($__attributesOriginal8ca5b43b8fff8bb34ab2ba4eb4bdd67b); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal8ca5b43b8fff8bb34ab2ba4eb4bdd67b)): ?>
<?php $component = $__componentOriginal8ca5b43b8fff8bb34ab2ba4eb4bdd67b; ?>
<?php unset($__componentOriginal8ca5b43b8fff8bb34ab2ba4eb4bdd67b); ?>
<?php endif; ?>
            </div>

            <div>
                <p class="text-base font-semibold text-gray-900 transition-colors duration-300 group-hover/user:text-indigo-600 dark:text-white dark:group-hover/user:text-indigo-400">
                    <?php echo e($note->user->name); ?>

                </p>

                <p class="text-xs text-gray-500 dark:text-gray-400">
                    <?php echo e(__('Автор заметки')); ?>

                </p>
            </div>
        </a>

        <?php if($note->is_public): ?>
        <span class="inline-flex items-center gap-1 rounded-full border border-green-500/10 bg-green-500/20 px-3 py-1.5 text-xs font-medium text-green-700 dark:text-green-300">
            <span class="h-2 w-2 rounded-full bg-green-500"></span>
            <?php echo e(__('Публичная')); ?>

        </span>
        <?php else: ?>
        <span class="inline-flex items-center gap-1 rounded-full border border-red-500/20 bg-red-500/10 px-3 py-1.5 text-xs font-medium text-red-700 dark:text-red-300">
            <span class="h-2 w-2 rounded-full bg-red-500"></span>
            <?php echo e(__('Приватная')); ?>

        </span>
        <?php endif; ?>
    </div>

    <!-- Content -->
    <div class="relative px-5 py-4">

        <!-- Title -->
        <h2 class="mb-3 text-xl font-bold leading-tight text-gray-900 dark:text-white">
            <a href="<?php echo e(route('notes.show', $note)); ?>"
               class="transition-colors duration-300 hover:text-indigo-600 dark:hover:text-indigo-400">
                <?php echo e($note->title); ?>

            </a>
        </h2>

        <!-- Description -->
        <div class="relative">
            <div class="ck-content note-content text-base leading-relaxed text-gray-700 dark:text-gray-300"
                 id="description-<?php echo e($note->id); ?>">
                <?php echo $note->description; ?>

            </div>
        </div>

        
        <!-- Meta -->
        <div class="mt-2 flex flex-wrap items-center gap-2.5">

            <?php if($note->tags->count() > 0): ?>
                <?php $__currentLoopData = $note->tags; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $tag): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <span class="inline-flex items-center rounded-full border border-blue-500/20 bg-blue-500/10 px-3 py-1.5 text-xs font-medium text-blue-700 dark:text-blue-300">
                    <i class="fas fa-hashtag mr-1 text-[10px]"></i>
                    <?php echo e($tag->name); ?>

                </span>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            <?php endif; ?>

            <?php if($note->files->count() > 0): ?>
            <span class="inline-flex items-center rounded-full border border-orange-500/20 bg-orange-500/10 px-3 py-1.5 text-xs font-medium text-orange-700 dark:text-orange-300">
                <i class="fas fa-paperclip mr-2"></i>

                <?php echo e($note->files->count()); ?>

                <?php echo e(trans_choice('файл|файла|файлов', $note->files->count())); ?>

            </span>
            <?php endif; ?>

            <?php if($note->comments->count() > 0): ?>
            <span class="inline-flex items-center rounded-full border border-cyan-500/20 bg-cyan-500/10 px-3 py-1.5 text-xs font-medium text-cyan-700 dark:text-cyan-300">
                <i class="fas fa-comments mr-2"></i>

                <?php echo e($note->comments->count()); ?>

                <?php echo e(trans_choice('комментарий|комментария|комментариев', $note->comments->count())); ?>

            </span>
            <?php endif; ?>
        </div>
    </div>

    <!-- Footer -->
    <div class="relative border-t border-gray-200/70 bg-gray-50/80 px-5 py-4 dark:border-gray-700/60 dark:bg-gray-900/60">

        <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">

            <!-- Dates -->
            <div class="flex flex-col gap-1.5 text-sm text-gray-600 dark:text-gray-400">
                <p class="flex items-center gap-2">
                    <i class="far fa-calendar-plus text-green-500"></i>
                    <?php echo e(__('Создана:')); ?>

                    <span class="font-medium">
                        <?php echo e($note->created_at->format('d.m.Y H:i')); ?>

                    </span>
                </p>

                <p class="flex items-center gap-2">
                    <i class="far fa-clock text-blue-500"></i>
                    <?php echo e(__('Изменена:')); ?>

                    <span class="font-medium">
                        <?php echo e($note->updated_at->format('d.m.Y H:i')); ?>

                    </span>
                </p>
            </div>

            <!-- Actions -->
            <div class="flex items-center justify-between gap-2.5 lg:justify-end">
                <button onclick="toggleDescription(<?php echo e($note->id); ?>)"
                    class="toggle-button inline-flex h-10 w-10 items-center justify-center rounded-xl bg-gray-500 text-base text-white shadow-sm transition-all duration-300 hover:bg-gray-800 dark:bg-gray-600 dark:hover:bg-gray-800"
                    id="toggle-button-<?php echo e($note->id); ?>"
                    title="<?php echo e(__('Развернуть')); ?>">
                    <i class="fas fa-angle-double-down"></i>
                </button>

                <div class="flex items-center gap-2.5">
                    <?php if($note->user_id === auth()->id() || auth()->user()->isAdmin()): ?>
                    <a href="<?php echo e(route('notes.edit', $note)); ?>"
                    class="inline-flex h-10 w-10 items-center justify-center rounded-xl bg-yellow-500 text-base text-white shadow-sm transition-all duration-300 hover:bg-yellow-600 dark:bg-yellow-600 dark:hover:bg-yellow-700"
                    title="<?php echo e(__('Изменить')); ?>">
                        <i class="fas fa-pen"></i>
                    </a>

                    <form action="<?php echo e(route('notes.destroy', $note)); ?>"
                        method="POST"
                        onsubmit="return confirm('Вы уверены, что хотите удалить эту заметку?');">
                        <?php echo csrf_field(); ?>
                        <?php echo method_field('DELETE'); ?>
                        <button type="submit"
                            class="inline-flex h-10 w-10 items-center justify-center rounded-xl bg-red-500 text-base text-white shadow-sm transition-all duration-300 hover:bg-red-600 dark:bg-red-600 dark:hover:bg-red-700"
                            title="<?php echo e(__('Удалить')); ?>">
                            <i class="fas fa-trash"></i>
                        </button>
                    </form>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
</div>

<?php if($notes->hasMorePages()): ?>
<div class="pagination hidden">
    <a href="<?php echo e($notes->nextPageUrl()); ?>" rel="next"></a>
</div>
<?php endif; ?>

<?php endif; ?><?php /**PATH C:\Users\Desktop_54\Desktop\neocalc.site_share_link_30.05\resources\views/notes/partials/notes_list.blade.php ENDPATH**/ ?>
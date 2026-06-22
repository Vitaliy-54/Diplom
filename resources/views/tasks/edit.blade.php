<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Редактирование задачи') }}
            </h2>
            <div class="text-sm text-gray-800 dark:text-gray-400 animate__animated animate__fadeIn">
                {{ now()->translatedFormat('d F Y') }}
            </div>
        </div>
    </x-slot>

    <x-slot name="title">
        {{ __('Редактирование задачи') }}
    </x-slot>


    <div class="py-6">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Основная карточка формы -->
            <div class="bg-gray-300 dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-6 sm:p-8">
                    <div class="flex items-center justify-between mb-6">
                        <h1 class="text-2xl font-bold text-gray-900 dark:text-white">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 inline-block mr-2 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                            </svg>
                            {{ __('Редактирование задачи') }} - {{ $task->title }}
                        </h1>
                    </div>

                    <!-- Форма редактирования задачи -->
                    <form action="{{ route('tasks.update', $task->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <!-- Название задачи -->
                        <div class="mb-4">
                            <label for="title" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Название задачи</label>
                            <input type="text" name="title" id="title" value="{{ $task->title }}" placeholder="Название задачи"
                                class="mt-1 block w-full p-2 border border-gray-300 dark:border-gray-700 rounded-lg dark:bg-gray-700 dark:text-white focus:outline-none focus:ring-2 focus:ring-blue-500"
                                required>
                        </div>

                        <!-- Категория -->
                        <div class="mb-4">
                            <label for="category" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Категория</label>
                            <div class="relative">
                                <select name="category" id="category"
                                    class="mt-1 block w-full p-2 border border-gray-300 dark:border-gray-700 rounded-lg dark:bg-gray-700 dark:text-white focus:outline-none focus:ring-2 focus:ring-blue-500 appearance-none"
                                    required>
                                    <option value="">Выберите категорию</option>
                                    @foreach ($categories as $category)
                                    <option value="{{ $category }}" {{ $task->category === $category ? 'selected' : '' }}>{{ $category }}</option>
                                    @endforeach
                                    <option value="new_category_task">Новая категория...</option>
                                </select>
                                <!-- Иконка для выпадающего списка -->
                                <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-700 dark:text-gray-300">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                    </svg>
                                </div>
                            </div>
                            <!-- Поле для новой категории (скрыто по умолчанию) -->
                            <input type="text" name="new_category" id="new_category" placeholder="Введите новую категорию"
                                class="mt-2 hidden w-full p-2 border border-gray-300 dark:border-gray-700 rounded-lg dark:bg-gray-700 dark:text-white focus:outline-none focus:ring-2 focus:ring-blue-500">
                        </div>

                        <!-- Описание задачи -->
                        <div class="mb-4">
                            <label for="description" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Описание задачи</label>
                            <textarea name="description" id="description" placeholder="Описание задачи (необязательно)"
                                class="mt-1 block w-full p-2 border border-gray-300 dark:border-gray-700 rounded-lg dark:bg-gray-700 dark:text-white focus:outline-none focus:ring-2 focus:ring-blue-500">{{ $task->description }}</textarea>
                        </div>

                         <!-- Кнопки действий -->
                         <div class="flex flex-col sm:flex-row justify-end space-y-3 sm:space-y-0 sm:space-x-3">
                            <a href="{{ route('tasks.index') }}" class="inline-flex items-center justify-center px-6 py-3 border border-transparent text-sm font-medium rounded-lg shadow-sm text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition duration-200">
                                {{ __('Отмена') }}
                            </a>
                            <button type="submit" class="inline-flex items-center justify-center px-6 py-3 border border-transparent text-sm font-medium rounded-lg shadow-sm text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition duration-200">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                </svg>
                                {{ __('Сохранить изменения') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- JavaScript для управления полем новой категории -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const categorySelect = document.getElementById('category');
            const newCategoryInput = document.getElementById('new_category');

            // Обработчик изменения выпадающего списка
            categorySelect.addEventListener('change', function() {
                // Если выбрана опция "Новая категория..."
                if (this.value === 'new_category_task') {
                    newCategoryInput.classList.remove('hidden'); // Показываем поле для новой категории
                    newCategoryInput.setAttribute('required', true); // Делаем поле обязательным
                } else {
                    newCategoryInput.classList.add('hidden'); // Скрываем поле для новой категории
                    newCategoryInput.removeAttribute('required'); // Убираем обязательность
                }
            });

            // Инициализация при загрузке страницы
            if (categorySelect.value === 'new_category_task') {
                newCategoryInput.classList.remove('hidden');
                newCategoryInput.setAttribute('required', true);
            } else {
                newCategoryInput.classList.add('hidden');
                newCategoryInput.removeAttribute('required');
            }
        });
    </script>
</x-app-layout>
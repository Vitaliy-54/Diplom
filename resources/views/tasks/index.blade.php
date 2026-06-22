<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between pl-6">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight" id="current-section">
                {{ __('Все задачи') }}
            </h2>
            <div class="text-sm text-gray-800 dark:text-gray-400 animate__animated animate__fadeIn">
                {{ now()->translatedFormat('d F Y') }}
            </div>
        </div>
    </x-slot>

    <x-slot name="title">
        {{ __('Задачи') }}
    </x-slot>

    @assets
    <style>
        /* Убедитесь, что полоса прокрутки всегда занимает место */
        html {
            scrollbar-gutter: stable;
        }

        /* Альтернативное решение для старых браузеров */
        body {
            overflow-y: scroll;
            /* Всегда показывать вертикальную полосу прокрутки */
        }
    </style>
    @endassets

    <div class="py-6 px-4 dark:bg-gray-900">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Кнопки фильтрации -->
            <div class="mb-6 flex items-center gap-4 flex-wrap">
                <button onclick="loadTasks('all')"
                    class="filter-tab px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition duration-300 text-sm sm:text-base sm:px-6 sm:py-2">
                    {{ __('Все задачи') }}
                </button>
                <button onclick="loadTasks('active')"
                    class="filter-tab px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition duration-300 text-sm sm:text-base sm:px-6 sm:py-2">
                    {{ __('Активные задачи') }}
                </button>
                <button onclick="loadTasks('completed')"
                    class="filter-tab px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition duration-300 text-sm sm:text-base sm:px-6 sm:py-2">
                    {{ __('Выполненные задачи') }}
                </button>
            </div>

            <!-- Блок категорий -->
            <div class="mb-6 p-4 bg-gray-300 dark:bg-gray-800 rounded-lg"> <!-- Изменен фон блока категорий -->
                <h3 class="text-gray-800 dark:text-white text-lg font-semibold mb-3 border-b border-gray-200 dark:border-gray-600 pb-2">{{ __('Категории') }}</h3>
                <div class="flex gap-4 flex-wrap">
                    @foreach ($categories as $category)
                    <button onclick="loadTasksByCategory('{{ $category }}')"
                        class="category-tab px-4 py-2 bg-gray-200 dark:bg-gray-700 text-gray-800 dark:text-white rounded-lg hover:bg-gray-400 dark:hover:bg-gray-600 transition duration-300 text-sm sm:text-base sm:px-3 sm:py-2">
                        {{ $category }}
                    </button>
                    @endforeach
                </div>
            </div>

            <!-- Форма добавления новой задачи -->
            <form action="{{ route('tasks.store') }}" method="POST" class="mb-6 bg-gray-300 dark:bg-gray-800 p-4 rounded-lg">
                @csrf
                <!-- Заголовок формы -->
                <h3 class="text-gray-800 dark:text-white text-lg font-semibold mb-3 border-b border-gray-200 dark:border-gray-600 pb-2">
                    Создание задачи
                </h3>
                <div class="flex flex-col sm:flex-row gap-2">
                    <!-- Название задачи -->
                    <input type="text" name="title" id="title" placeholder="Название задачи"
                        class="flex-1 p-2 border border-gray-300 dark:border-gray-700 rounded-lg bg-white dark:bg-gray-700 dark:text-white focus:outline-none focus:ring-2 focus:ring-blue-500"
                        required>

                    <!-- Категория -->
                    <div class="relative flex-1">
                        <select name="category" id="category"
                            class="w-full p-2 border border-gray-300 dark:border-gray-700 rounded-lg bg-white dark:bg-gray-700 dark:text-white focus:outline-none focus:ring-2 focus:ring-blue-500 appearance-none"
                            required>
                            <option value="">Выберите категорию</option>
                            @foreach ($categories as $category)
                            <option value="{{ $category }}">{{ $category }}</option>
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

                    <!-- Поле для новой категории -->
                    <input type="text" name="new_category" id="new_category" placeholder="Введите новую категорию"
                        class="hidden p-2 border border-gray-300 dark:border-gray-700 rounded-lg bg-white dark:bg-gray-700 dark:text-white focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>

                <!-- Описание задачи -->
                <textarea name="description" id="description" placeholder="Описание задачи (необязательно)"
                    class="mt-2 w-full p-2 border border-gray-300 dark:border-gray-700 rounded-lg bg-white dark:bg-gray-700 dark:text-white focus:outline-none focus:ring-2 focus:ring-blue-500"></textarea>

                <!-- Кнопка добавления задачи -->
                <div class="mt-2">
                    <button type="submit"
                        class="bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 rounded-lg focus:outline-none focus:ring-2 focus:ring-lime-500 focus:ring-offset-2 transition-shadow shadow-md">
                        Добавить задачу
                    </button>
                </div>
            </form>

            <!-- Контейнер для списка задач -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6" id="tasks-container">
                @if($tasks->isEmpty())
                <div class="col-span-full text-center text-gray-500 dark:text-gray-400">
                    {{ __('Нет задач для отображения.') }}
                </div>
                @else
                @include('tasks.partials.task-list', ['tasks' => $tasks])
                @endif
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <script>
        // Функция для изменения статуса задачи
        function toggleTaskCompletion(taskId, button) {
            const currentDate = new Date().toISOString().split('T')[0];

            axios.post(`/tasks/${taskId}/toggle-completion`, {
                    due_date: currentDate,
                })
                .then(response => {
                    // Обновляем цвет полоски
                    if (response.data.completed) {
                        button.classList.remove('bg-red-600');
                        button.classList.add('bg-green-600');
                    } else {
                        button.classList.remove('bg-green-600');
                        button.classList.add('bg-red-600');
                    }

                    // Обновляем дату выполнения в интерфейсе
                    const taskElement = button.closest('.task-item');
                    if (taskElement) {
                        const dueDateElement = taskElement.querySelector('.due-date');
                        if (dueDateElement) {
                            if (response.data.due_date) {
                                // Преобразуем дату в объект Date
                                const date = new Date(response.data.due_date);

                                // Проверяем, что дата корректна
                                if (!isNaN(date.getTime())) {
                                    // Форматируем дату в ДД.ММ.ГГГГ
                                    const formattedDate = `${String(date.getDate()).padStart(2, '0')}.${String(date.getMonth() + 1).padStart(2, '0')}.${date.getFullYear()}`;
                                    dueDateElement.textContent = `Дата выполнения: ${formattedDate}`;
                                } else {
                                    dueDateElement.textContent = 'Некорректная дата';
                                }
                            } else {
                                dueDateElement.textContent = 'Задача не выполнена';
                            }
                        }
                    }

                    // Получаем текущий раздел (вкладку)
                    const currentSection = document.getElementById('current-section').textContent;

                    // Удаляем задачу из DOM, если она больше не соответствует текущему фильтру
                    if (taskElement) {
                        if (currentSection.includes('Выполненные') && !response.data.completed) {
                            taskElement.remove();
                        } else if (currentSection.includes('Активные') && response.data.completed) {
                            taskElement.remove();
                        }
                    }

                    // Проверяем, остались ли задачи в контейнере
                    const tasksContainer = document.getElementById('tasks-container');
                    if (tasksContainer.children.length === 0) {
                        tasksContainer.innerHTML = `
                    <div class="col-span-full text-center text-gray-500 dark:text-gray-400">
                        {{ __('Нет задач для отображения.') }}
                    </div>
                `;
                    }
                })
                .catch(error => {
                    console.error('Ошибка при изменении статуса задачи:', error);
                    alert('Не удалось изменить статус задачи.');
                });
        }

        // Функция для загрузки задач по фильтру
        function loadTasks(filter) {
            // Очищаем контейнер перед загрузкой новых задач
            const tasksContainer = document.getElementById('tasks-container');
            tasksContainer.innerHTML = '';

            // Показываем кружок загрузки
            tasksContainer.innerHTML = `
            <div class="col-span-full flex justify-center items-center">
                <div class="animate-spin rounded-full h-12 w-12 border-t-2 border-b-2 border-blue-500"></div>
            </div>
        `;

            // Обновляем заголовок текущего раздела
            updateSectionTitle(filter);

            // Отправляем AJAX-запрос
            axios.get('{{ route("tasks.index") }}', {
                    params: {
                        filter: filter
                    },
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest' // Это важно для $request->ajax()
                    }
                })
                .then(response => {
                    // Обновляем содержимое контейнера с задачами
                    tasksContainer.innerHTML = response.data;

                    // Если задач нет, показываем сообщение
                    if (tasksContainer.children.length === 0) {
                        tasksContainer.innerHTML = `
                    <div class="col-span-full text-center text-gray-500 dark:text-gray-400">
                        {{ __('Нет задач для отображения.') }}
                    </div>
                `;
                    }
                })
                .catch(error => {
                    console.error('Ошибка при загрузке задач:', error);
                    tasksContainer.innerHTML = `
                <div class="col-span-full text-center text-red-500">
                    Ошибка при загрузке задач.
                </div>
            `;
                });
        }

        // Функция для загрузки задач по категории
        function loadTasksByCategory(category) {
            // Очищаем контейнер перед загрузкой новых задач
            const tasksContainer = document.getElementById('tasks-container');
            tasksContainer.innerHTML = '';

            // Показываем кружок загрузки
            tasksContainer.innerHTML = `
            <div class="col-span-full flex justify-center items-center">
                <div class="animate-spin rounded-full h-12 w-12 border-t-2 border-b-2 border-blue-500"></div>
            </div>
        `;

            // Обновляем заголовок текущего раздела
            document.getElementById('current-section').textContent = `Задачи категории: ${category}`;

            // Отправляем AJAX-запрос
            axios.get('{{ route("tasks.index") }}', {
                    params: {
                        category: category
                    },
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest' // Это важно для $request->ajax()
                    }
                })
                .then(response => {
                    // Обновляем содержимое контейнера с задачами
                    tasksContainer.innerHTML = response.data;

                    // Если задач нет, показываем сообщение
                    if (tasksContainer.children.length === 0) {
                        tasksContainer.innerHTML = `
                    <div class="col-span-full text-center text-gray-500 dark:text-gray-400">
                        {{ __('Нет задач для отображения в категории') }} "${category}".
                    </div>
                `;
                    }
                })
                .catch(error => {
                    console.error('Ошибка при загрузке задач:', error);
                    tasksContainer.innerHTML = `
                <div class="col-span-full text-center text-red-500">
                    Ошибка при загрузке задач.
                </div>
            `;
                });
        }

        // Функция для обновления заголовка текущего раздела
        function updateSectionTitle(filter) {
            const sectionTitles = {
                'all': 'Все задачи',
                'active': 'Активные задачи',
                'completed': 'Выполненные задачи',
            };
            document.getElementById('current-section').textContent = sectionTitles[filter];
        }

        // Функция для удаления задачи
        function deleteTask(taskId) {
            axios.delete(`/tasks/${taskId}`)
                .then(response => {
                    // Удаляем задачу из DOM
                    const taskElement = document.getElementById(`task-${taskId}`);
                    if (taskElement) {
                        taskElement.remove();
                    }

                    // Проверяем, остались ли задачи в контейнере
                    const tasksContainer = document.getElementById('tasks-container');
                    if (tasksContainer.children.length === 0) {
                        tasksContainer.innerHTML = `
                        <div class="col-span-full text-center text-gray-500 dark:text-gray-400">
                            {{ __('Нет задач для отображения.') }}
                        </div>
                    `;
                    }
                })
                .catch(error => {
                    console.error('Ошибка при удалении задачи:', error);
                    alert('Не удалось удалить задачу.');
                });
        }

        // Управление полем для новой категории
        document.addEventListener('DOMContentLoaded', function() {
            const categorySelect = document.getElementById('category');
            const newCategoryInput = document.getElementById('new_category');

            if (categorySelect && newCategoryInput) {
                categorySelect.addEventListener('change', function() {
                    if (this.value === 'new_category_task') {
                        newCategoryInput.classList.remove('hidden');
                        newCategoryInput.setAttribute('required', true);
                    } else {
                        newCategoryInput.classList.add('hidden');
                        newCategoryInput.removeAttribute('required');
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
            }
        });
    </script>
</x-app-layout>
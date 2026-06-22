<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Добавление материала') }}
            </h2>
            <div class="text-sm text-gray-800 dark:text-gray-400 animate__animated animate__fadeIn">
                {{ now()->translatedFormat('d F Y') }}
            </div>
        </div>
    </x-slot>

    <x-slot name="title">
        {{ __('Добавление материала') }}
    </x-slot>

    <div class="py-6">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Основная карточка формы -->
            <div class="bg-gray-300 dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-6 sm:p-8">
                    <div class="flex items-center justify-between mb-6">
                        <h1 class="text-2xl font-bold text-gray-900 dark:text-white">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 inline-block mr-2 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                            </svg>
                            {{ __('Добавление нового материала') }}
                        </h1>
                    </div>

                    <form method="POST" action="{{ route('literature.store') }}" enctype="multipart/form-data" class="space-y-6">
                        @csrf

                        <!-- Название с счетчиком -->
                        <div>
                            <div class="flex justify-between items-center mb-1">
                                <label for="title" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                    {{ __('Название материала') }} <span class="text-red-500">*</span>
                                </label>
                                <span class="text-xs text-gray-500 dark:text-gray-400" id="title-counter">0/100</span>
                            </div>
                            <div class="relative">
                                <input
                                    type="text"
                                    id="title"
                                    name="title"
                                    value="{{ old('title') }}"
                                    required
                                    maxlength="100"
                                    autofocus
                                    class="block w-full px-4 py-3 rounded-lg border border-gray-300 dark:border-gray-600 bg-gray-50 dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-400 dark:placeholder-gray-400 focus:outline-none focus:border-blue-400 dark:focus:border-blue-500"
                                    placeholder="Введите название материала"
                                    oninput="document.getElementById('title-counter').textContent = this.value.length + '/100'">
                                @error('title')
                                <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-red-500" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                                    </svg>
                                </div>
                                @enderror
                            </div>
                            @error('title')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Описание с счетчиком -->
                        <div>
                            <div class="flex justify-between items-center mb-1">
                                <label for="description" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                    {{ __('Описание (необязательно)') }}
                                </label>
                                <span class="text-xs text-gray-500 dark:text-gray-400" id="description-counter">0/255</span>
                            </div>
                            <div class="relative">
                                <textarea
                                    id="description"
                                    name="description"
                                    rows="3"
                                    maxlength="255"
                                    class="block w-full px-4 py-3 rounded-lg border border-gray-300 dark:border-gray-600 bg-gray-50 dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-400 dark:placeholder-gray-400 focus:outline-none focus:border-blue-400 dark:focus:border-blue-500"
                                    placeholder="Введите краткое описание материала"
                                    oninput="document.getElementById('description-counter').textContent = this.value.length + '/255'">{{ old('description') }}</textarea>
                                @error('description')
                                <div class="absolute top-3 right-0 flex items-center pr-3 pointer-events-none">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-red-500" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                                    </svg>
                                </div>
                                @enderror
                            </div>
                            @error('description')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Категория с счетчиком -->
                        <div>
                            <div class="flex justify-between items-center mb-1">
                                <label for="category" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                    {{ __('Категория (необязательно)') }}
                                </label>
                                <span class="text-xs text-gray-500 dark:text-gray-400" id="category-counter">0/30</span>
                            </div>
                            <input
                                type="text"
                                id="category"
                                name="category"
                                value="{{ old('category') }}"
                                maxlength="30"
                                class="block w-full px-4 py-3 rounded-lg border border-gray-300 dark:border-gray-600 bg-gray-50 dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-400 dark:placeholder-gray-400 focus:outline-none focus:border-blue-400 dark:focus:border-blue-500"
                                placeholder="Например: Журнал, статья или рецензия"
                                oninput="document.getElementById('category-counter').textContent = this.value.length + '/30'">
                            @error('category')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Файл -->
                        <div>
                            <label for="file" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                {{ __('Файл') }} <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <input
                                    type="file"
                                    id="file"
                                    name="file"
                                    required
                                    accept=".pdf,.doc,.docx,.txt,.rtf,.epub,.mobi"
                                    class="block w-full px-4 py-3 rounded-lg border border-gray-300 dark:border-gray-600 bg-gray-200 dark:bg-gray-700 text-gray-900 dark:text-white file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-medium file:bg-blue-600 file:text-white hover:file:bg-blue-700 transition duration-200">
                                @error('file')
                                <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-red-500" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                                    </svg>
                                </div>
                                @enderror
                            </div>
                            <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                                Разрешенные форматы: PDF, DOC, DOCX, TXT, RTF, EPUB, MOBI (макс. 10MB)
                            </p>
                            @error('file')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Кнопки действий -->
                        <div class="flex flex-col sm:flex-row justify-end space-y-3 sm:space-y-0 sm:space-x-3">
                            <a href="{{ route('literature.index') }}" class="inline-flex items-center justify-center px-6 py-3 border border-transparent text-sm font-medium rounded-lg shadow-sm text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition duration-200">
                                {{ __('Отмена') }}
                            </a>
                            <button type="submit" class="inline-flex items-center justify-center px-6 py-3 border border-transparent text-sm font-medium rounded-lg shadow-sm text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition duration-200">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                                </svg>
                                {{ __('Добавить материал') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        // Инициализация счетчиков при загрузке страницы
        document.addEventListener('DOMContentLoaded', function() {
            const titleInput = document.getElementById('title');
            const descriptionInput = document.getElementById('description');
            const categoryInput = document.getElementById('category');
            
            if (titleInput) {
                document.getElementById('title-counter').textContent = titleInput.value.length + '/100';
            }
            if (descriptionInput) {
                document.getElementById('description-counter').textContent = descriptionInput.value.length + '/255';
            }
            if (categoryInput) {
                document.getElementById('category-counter').textContent = categoryInput.value.length + '/30';
            }
        });
    </script>
    @endpush
</x-app-layout>
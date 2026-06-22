<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Мои файлы') }}
            </h2>
            <div class="text-sm text-gray-800 dark:text-gray-400 animate__animated animate__fadeIn">
                {{ now()->translatedFormat('d F Y') }}
            </div>
        </div>
    </x-slot>

    <x-slot name="title">
        {{ __('Мои файлы') }}
    </x-slot>

    @assets
    <style>
        [x-cloak] {
            display: none !important;
        }

        html {
            scrollbar-gutter: stable;
            overflow-y: scroll;
        }

        .transition-all {
            transition-property: all;
            transition-timing-function: cubic-bezier(0.4, 0, 0.2, 1);
            transition-duration: 150ms;
        }

        .accordion-content {
            transition: opacity 0.2s ease, max-height 0.3s ease;
            overflow: hidden;
        }

        @keyframes spin {
            to {
                transform: rotate(360deg);
            }
        }
    </style>
    @endassets

    <div class="py-6 sm:py-6" x-data="{
        isLoaded: false,
        searchQuery: '',
        filteredNotes: @js($files->groupBy('note_id')->toArray()),
        
        init() {
            this.isLoaded = true;
            
            // Обработчик для поиска
            this.$watch('searchQuery', (value) => {
                if (!value) {
                    this.filteredNotes = @js($files->groupBy('note_id')->toArray());
                    return;
                }
                
                const query = value.toLowerCase();
                this.filteredNotes = Object.fromEntries(
                    Object.entries(@js($files->groupBy('note_id')->toArray())).filter(([noteId, files]) => {
                        const note = files[0].note;
                        return note.title.toLowerCase().includes(query) || 
                               files.some(file => file.name.toLowerCase().includes(query));
                    })
                );
            });
        }
    }">

        <!-- Основное содержимое -->
        <div x-show="isLoaded" x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
            class="max-w-7xl mx-auto px-3 sm:px-6 lg:px-8">

            <!-- Карточка использования и кнопка обновления -->
            <div class="flex flex-wrap items-center justify-between gap-3 mb-4 sm:mb-6">
                <div class="flex items-center bg-gray-300 border border-gray-400 dark:border-gray-600 dark:bg-gray-700 px-4 py-3 sm:px-5 sm:py-4 rounded-lg flex-shrink-0">
                    <div class="w-10 h-10 sm:w-12 sm:h-12 rounded-full bg-blue-100 dark:bg-blue-900 flex items-center justify-center mr-3">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 sm:h-7 sm:w-7 text-blue-500 dark:text-blue-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4" />
                        </svg>
                    </div>
                    <div>
                        <div class="text-sm sm:text-base text-gray-700 dark:text-gray-300">Использовано</div>
                        <div class="text-base sm:text-lg font-medium text-gray-800 dark:text-gray-200">
                            {{ $formattedTotalSize }}<span class="text-xs sm:text-sm text-gray-700 dark:text-gray-400"> / 150MB</span>
                            <span class="text-xs sm:text-sm text-gray-700 dark:text-gray-400">({{ round($storagePercentage, 2) }}%)</span>
                        </div>
                    </div>
                </div>
                <button wire:navigate href="{{ route('my-files') }}"
                    class="p-3 rounded-lg hover:bg-gray-300 dark:hover:bg-gray-700 text-gray-500 dark:text-gray-400 flex-shrink-0"
                    title="Обновить данные">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="#4299e1">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                    </svg>
                </button>
            </div>

            <div class="text-sm sm:text-base mb-2 ml-3 text-gray-700 dark:text-gray-400">
                Актуальные данные на: {{ now()->format('d.m.Y H:i') }}
            </div>

            <!-- Контейнер с файлами -->
            <div class="bg-gray-300 dark:bg-gray-800 rounded-xl shadow-sm border border-gray-400 dark:border-gray-600 overflow-hidden">
                <!-- Заголовок с фильтрами -->
                <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                    <div class="flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-blue-500 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        <h2 class="text-xl font-bold text-gray-800 dark:text-white">Мои файлы</h2>
                        <span class="ml-3 px-3 py-2 text-xs font-semibold bg-blue-300 dark:bg-blue-900/30 text-blue-800 dark:text-blue-400 border border-gray-400 dark:border-gray-600 rounded-full">
                            {{ $files->total() }} {{ trans_choice('файл|файла|файлов', $files->total()) }}
                        </span>
                    </div>

                    <div class="flex items-center gap-2">
                        <div class="relative">
                            <input x-model="searchQuery" type="text" placeholder="Поиск файлов..."
                                class="pl-10 pr-4 py-2 border border-gray-400 dark:border-gray-600 text-gray-900 dark:text-gray-200 rounded-lg bg-white dark:bg-gray-800 focus:ring-blue-500 focus:border-blue-500 w-full sm:w-64">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-600 dark:text-gray-400 absolute left-3 top-2.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                        </div>
                    </div>
                </div>

                @if($files->isEmpty())
                <!-- Состояние "Нет файлов" -->
                <div class="text-center py-6" x-show="isLoaded" x-transition>
                    <div class="mx-auto h-12 w-12 text-gray-500">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 13h6m-3-3v6m-9 1V7a2 2 0 012-2h6l2 2h6a2 2 0 012 2v8a2 2 0 01-2 2H5a2 2 0 01-2-2z" />
                        </svg>
                    </div>
                    <h3 class="mt-2 text-lg font-medium text-gray-900 dark:text-gray-200">Файлы не найдены</h3>
                    <p class="mt-2 text-sm text-gray-500 dark:text-gray-400 max-w-md mx-auto">
                        У вас пока нет загруженных файлов.
                    </p>
                    <p class="text-sm text-gray-500 dark:text-gray-400 max-w-md mx-auto">
                        Начните с создания новой заметки с файлами.
                    </p>
                    <div class="mt-6">
                        <a href="{{ route('notes.create') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 active:bg-blue-800 focus:outline-none focus:border-blue-900 focus:ring ring-blue-300 disabled:opacity-25 transition ease-in-out duration-150">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                            </svg>
                            Создать заметку
                        </a>
                    </div>
                </div>
                @else
                <!-- Список файлов -->
                <div class="divide-y divide-gray-200 dark:divide-gray-700" x-cloak>
                    <template x-for="[noteId, noteFiles] in Object.entries(filteredNotes)" :key="noteId">
                        <!-- Аккордеон для заметки -->
                        <div x-data="{ open: false }"
                            class="transition-colors bg-gray-400 dark:bg-gray-700 border-b border-gray-400 dark:border-gray-500 mb-px dark:mb-px last:mb-0">
                            <button @click="open = !open" class="w-full px-4 sm:px-6 py-3 sm:py-4 flex items-center justify-between transition-colors hover:bg-gray-500 dark:hover:bg-gray-600">
                                <div class="flex items-center min-w-0">
                                    <!-- Иконка с количеством файлов -->
                                    <div class="mr-4 sm:mr-4 flex items-center flex-shrink-0">
                                        <div class="relative">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-blue-600 dark:text-blue-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                            </svg>
                                            <span x-text="noteFiles.length" class="absolute -top-2 -right-2 bg-blue-600 dark:bg-blue-600 text-white text-xs font-bold rounded-full h-5 w-5 flex items-center justify-center"></span>
                                        </div>
                                    </div>

                                    <!-- Заголовок заметки (максимум 3 строки) -->
                                    <a :href="`/notes/${noteFiles[0].note.id}`" class="min-w-0">
                                        <span x-text="noteFiles[0].note.title" class="block font-medium text-gray-800 dark:text-gray-200 hover:text-blue-400 dark:hover:text-blue-400 transition-colors line-clamp-3 text-left"></span>
                                    </a>
                                </div>

                                <!-- Иконка стрелки -->
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-700 dark:text-gray-300 transition-transform duration-200 ml-2"
                                    :class="{ 'transform rotate-180': open }"
                                    fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                </svg>
                            </button>

                            <!-- Содержимое аккордеона -->
                            <div x-show="open"
                                x-collapse
                                class="accordion-content"
                                x-transition:enter="transition ease-out duration-300"
                                x-transition:enter-start="opacity-0 max-h-0"
                                x-transition:enter-end="opacity-100 max-h-screen"
                                x-transition:leave="transition ease-in duration-200"
                                x-transition:leave-start="opacity-100 max-h-screen"
                                x-transition:leave-end="opacity-0 max-h-0">
                                <div class="overflow-x-auto">
                                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                                        <thead class="bg-gray-400 dark:bg-gray-700 border-t border-b border-gray-400 dark:border-gray-500">
                                            <tr class="border-t border-b border-gray-400 dark:border-gray-500">
                                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-700 dark:text-gray-300 uppercase tracking-wider">Файл</th>
                                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-700 dark:text-gray-300 uppercase tracking-wider">Размер</th>
                                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-700 dark:text-gray-300 uppercase tracking-wider">Дата</th>
                                                <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-700 dark:text-gray-300 uppercase tracking-wider">Действия</th>
                                            </tr>
                                        </thead>
                                        <tbody class="bg-gray-300 dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                            <template x-for="file in noteFiles.sort((a, b) => new Date(b.created_at) - new Date(a.created_at))" :key="file.id">
                                                <tr class="hover:bg-gray-200 dark:hover:bg-gray-700/20 transition-colors">
                                                    <!-- Имя файла -->
                                                    <td class="px-6 py-4">
                                                        <div class="flex items-center">
                                                            <div>
                                                                <div class="text-sm font-medium text-gray-800 dark:text-gray-200 break-words max-w-xs" x-text="file.name"></div>
                                                            </div>
                                                        </div>
                                                    </td>

                                                    <!-- Размер -->
                                                    <td class="px-6 py-4 whitespace-nowrap">
                                                        <span class="text-sm text-gray-800 dark:text-gray-200" x-text="formatFileSize(file.size)"></span>
                                                    </td>

                                                    <!-- Дата -->
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600 dark:text-gray-400">
                                                        <div class="flex flex-col items-start">
                                                            <span class="text-gray-600 dark:text-gray-300 font-medium" x-text="formatDate(file.created_at)"></span>
                                                            <span class="text-gray-500 dark:text-gray-400 mt-1" x-text="formatTime(file.created_at)"></span>
                                                        </div>
                                                    </td>

                                                    <!-- Действия -->
                                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                                        <div class="flex justify-end space-x-2">
                                                            <a :href="`/notes/${noteFiles[0].note.id}/files/${file.id}/download`"
                                                                class="p-2 text-gray-500 hover:text-green-600 dark:hover:text-green-400 rounded-lg hover:bg-gray-300 dark:hover:bg-gray-700 transition-colors"
                                                                title="Скачать">
                                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                                                                </svg>
                                                            </a>

                                                            <form :action="`/notes/${noteFiles[0].note.id}/files/${file.id}`" method="POST">
                                                                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                                                <input type="hidden" name="_method" value="DELETE">
                                                                <button type="submit"
                                                                    class="p-2 text-gray-500 hover:text-red-600 dark:hover:text-red-400 rounded-lg hover:bg-gray-300 dark:hover:bg-gray-700 transition-colors"
                                                                    title="Удалить"
                                                                    @click.prevent="if(confirm(`Вы уверены, что хотите удалить файл «${file.name}»?`)) $event.target.closest('form').submit()">
                                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                                    </svg>
                                                                </button>
                                                            </form>
                                                        </div>
                                                    </td>
                                                </tr>
                                            </template>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </template>

                    <!-- Сообщение, если ничего не найдено -->
                    <div x-show="Object.keys(filteredNotes).length === 0 && searchQuery" class="text-center py-6">
                        <div class="mx-auto h-12 w-12 text-red-600 dark:text-red-500">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <h3 class="mt-2 text-lg font-medium text-red-600 dark:text-red-500">Ничего не найдено</h3>
                        <p class="mt-2 text-base text-gray-600 dark:text-gray-400 max-w-md mx-auto">
                            По запросу "<span x-text="searchQuery"></span>" файлы не найдены.
                        </p>
                    </div>
                </div>
                @endif
            </div>
            <!-- Информационные карточки -->
            <div class="mt-8 grid grid-cols-1 md:grid-cols-3 gap-6">
                <!-- Карточка 1 -->
                <div class="bg-gray-300 dark:bg-gray-700 rounded-lg p-6 border-l-4 border-blue-500">
                    <div class="flex items-start">
                        <div class="flex-shrink-0 h-10 w-10 rounded-full bg-blue-100 dark:bg-blue-900/30 flex items-center justify-center text-blue-600 dark:text-blue-400 mr-4">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">Управление файлами</h3>
                            <p class="text-gray-600 dark:text-gray-400">
                                Используйте интерфейс для скачивания, удаления и управления своими файлами.
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Карточка 2 -->
                <div class="bg-gray-300 dark:bg-gray-700 rounded-lg p-6 border-l-4 border-purple-500">
                    <div class="flex items-start">
                        <div class="flex-shrink-0 h-10 w-10 rounded-full bg-purple-100 dark:bg-purple-900/30 flex items-center justify-center text-purple-600 dark:text-purple-400 mr-4">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">Ограничение размера</h3>
                            <p class="text-gray-600 dark:text-gray-400">
                                Ваш лимит составляет 150MB. При достижении лимита вы не сможете загружать новые файлы.
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Карточка 3 -->
                <div class="bg-gray-300 dark:bg-gray-700 rounded-lg p-6 border-l-4 border-green-500">
                    <div class="flex items-start">
                        <div class="flex-shrink-0 h-10 w-10 rounded-full bg-green-100 dark:bg-green-900/30 flex items-center justify-center text-green-600 dark:text-green-400 mr-4">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">Поиск файлов</h3>
                            <p class="text-gray-600 dark:text-gray-400">
                                Используйте поиск для нахождения файлов по имени или содержимому. Поиск выполняется в реальном времени по мере ввода текста.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function formatFileSize(bytes) {
            if (bytes === 0) return '0 Bytes';
            const k = 1024;
            const sizes = ['Bytes', 'KB', 'MB', 'GB'];
            const i = Math.floor(Math.log(bytes) / Math.log(k));
            return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
        }

        function formatDate(dateString) {
            const date = new Date(dateString);
            return date.toLocaleDateString('ru-RU', {
                day: '2-digit',
                month: '2-digit',
                year: 'numeric'
            });
        }

        function formatTime(dateString) {
            const date = new Date(dateString);
            return date.toLocaleTimeString('ru-RU', {
                hour: '2-digit',
                minute: '2-digit'
            });
        }
    </script>
</x-app-layout>
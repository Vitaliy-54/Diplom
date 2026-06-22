<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Администрирование файлов') }}
            </h2>
            <div class="text-sm text-gray-800 dark:text-gray-400">
                {{ now()->translatedFormat('d F Y') }}
            </div>
        </div>
    </x-slot>

    <x-slot name="title">
        {{ __('Администрирование файлов') }}
    </x-slot>

       <!-- Предварительная загрузка стилей -->
    <style>
        /* Скрываем контент до полной загрузки */
        body:not(.loaded) #content-wrapper {
            opacity: 0;
            visibility: hidden;
        }
        
        /* Плавное появление после загрузки */
        body.loaded #content-wrapper {
            opacity: 1;
            visibility: visible;
            transition: opacity 0.3s ease-in-out;
        }
        
        /* Анимация для аватара */
        .avatar-image {
            opacity: 0;
            transition: opacity 0.5s ease-in-out;
        }
        
        .avatar-image.loaded {
            opacity: 1;
        }
    </style>

    @php
    // Хелпер для форматирования размера файла
    function formatFileSize($bytes) {
    if ($bytes === 0) return '0 Bytes';
    $k = 1024;
    $sizes = ['Bytes', 'KB', 'MB', 'GB'];
    $i = floor(log($bytes) / log($k));
    return number_format($bytes / pow($k, $i), 2) . ' ' . $sizes[$i];
    }
    @endphp

    <div class="py-6 sm:py-6">
        <div class="max-w-7xl mx-auto px-3 sm:px-6 lg:px-8">
            <!-- Статистика -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                <div class="bg-gray-300 dark:bg-gray-700 rounded-lg p-4 border-l-4 border-blue-500">
                    <div class="flex items-center">
                        <div class="p-3 rounded-full bg-blue-100 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 mr-4">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4" />
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600 dark:text-gray-400">Всего файлов</p>
                            <p class="text-xl font-bold text-gray-800 dark:text-white">{{ $files->total() }}</p>
                        </div>
                    </div>
                </div>

                <div class="bg-gray-300 dark:bg-gray-700 rounded-lg p-4 border-l-4 border-green-500">
                    <div class="flex items-center">
                        <div class="p-3 rounded-full bg-green-100 dark:bg-green-900/30 text-green-600 dark:text-green-400 mr-4">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600 dark:text-gray-400">Пользователей с файлами</p>
                            <p class="text-xl font-bold text-gray-800 dark:text-white">{{ $usersWithFiles }}</p>
                        </div>
                    </div>
                </div>

                <div class="bg-gray-300 dark:bg-gray-700 rounded-lg p-4 border-l-4 border-purple-500">
                    <div class="flex items-center">
                        <div class="p-3 rounded-full bg-purple-100 dark:bg-purple-900/30 text-purple-600 dark:text-purple-400 mr-4">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 7v10c0 2.21 3.582 4 8 4s8-1.79 8-4V7M4 7c0 2.21 3.582 4 8 4s8-1.79 8-4M4 7c0-2.21 3.582-4 8-4s8 1.79 8 4" />
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600 dark:text-gray-400">Общий объём</p>
                            <p class="text-xl font-bold text-gray-800 dark:text-white">{{ $formattedTotalSize }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Фильтры -->
            <div class="bg-gray-300 dark:bg-gray-800 rounded-lg shadow-sm border border-gray-400 dark:border-gray-600 mb-6 p-4">
                <form method="GET" action="{{ route('admin.files') }}" class="space-y-4 md:space-y-0 md:grid md:grid-cols-4 md:gap-4">
                    <!-- Поиск -->
                    <div>
                        <label for="search" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Поиск</label>
                        <input type="text" name="search" id="search" value="{{ $search }}" placeholder="Введите текст..."
                            class="w-full rounded-md border border-gray-400 dark:border-gray-600 bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-200 shadow-sm focus:ring-blue-500 focus:border-blue-500 px-3">
                    </div>

                    <!-- Пользователь -->
                    <div>
                        <label for="user_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Пользователь</label>
                        <select name="user_id" id="user_id" class="w-full rounded-md border border-gray-400 dark:border-gray-600 bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-200 shadow-sm focus:ring-blue-500 focus:border-blue-500">
                            <option value="">Все</option>
                            @foreach($users as $user)
                            <option value="{{ $user->id }}" {{ $userFilter == $user->id ? 'selected' : '' }}>
                                {{ $user->name }} ({{ $user->email }})
                            </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Размер -->
                    <div>
                        <label for="size" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Размер</label>
                        <select name="size" id="size" class="w-full rounded-md border border-gray-400 dark:border-gray-600 bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-200 shadow-sm focus:ring-blue-500 focus:border-blue-500">
                            <option value="">Все</option>
                            <option value="large" {{ $sizeFilter === 'large' ? 'selected' : '' }}>Большие (>10MB)</option>
                            <option value="small" {{ $sizeFilter === 'small' ? 'selected' : '' }}>
                                Маленькие (&lt;1MB)
                            </option>
                        </select>
                    </div>

                    <!-- Сортировка -->
                    <div>
                        <label for="sort" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Сортировка</label>
                        <select name="sort" id="sort" class="w-full rounded-md border border-gray-400 dark:border-gray-600 bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-200 shadow-sm focus:ring-blue-500 focus:border-blue-500">
                            <option value="newest" {{ $sort === 'newest' ? 'selected' : '' }}>Новые сначала</option>
                            <option value="oldest" {{ $sort === 'oldest' ? 'selected' : '' }}>Старые сначала</option>
                            <option value="largest" {{ $sort === 'largest' ? 'selected' : '' }}>По убыванию размера</option>
                            <option value="smallest" {{ $sort === 'smallest' ? 'selected' : '' }}>По возрастанию размера</option>
                        </select>
                    </div>

                    <!-- Кнопки -->
                    <div class="md:col-span-4 flex justify-end space-x-2">
                        <button type="submit" class="px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 active:bg-blue-800 focus:outline-none focus:border-blue-900 focus:ring ring-blue-300 disabled:opacity-25 transition ease-in-out duration-150">
                            Применить
                        </button>
                        <a href="{{ route('admin.files') }}" class="px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-800 focus:outline-none focus:border-gray-900 focus:ring ring-gray-300 disabled:opacity-25 transition ease-in-out duration-150">
                            Сбросить
                        </a>
                    </div>
                </form>
            </div>

            <!-- Таблица файлов -->
            <div class="bg-gray-300 dark:bg-gray-800 rounded-lg shadow-sm border border-gray-400 dark:border-gray-600 overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <thead class="bg-gray-400 dark:bg-gray-700">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-700 dark:text-gray-300 uppercase tracking-wider">Файл</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-700 dark:text-gray-300 uppercase tracking-wider">Заметка</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-700 dark:text-gray-300 uppercase tracking-wider">Пользователь</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-700 dark:text-gray-300 uppercase tracking-wider">Размер</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-700 dark:text-gray-300 uppercase tracking-wider">Дата</th>
                                <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-700 dark:text-gray-300 uppercase tracking-wider">Действия</th>
                            </tr>
                        </thead>
                        <tbody class="bg-gray-300 dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                            @forelse($files as $file)
                            <tr class="hover:bg-gray-200 dark:hover:bg-gray-700/20 transition-colors">
                                <!-- Имя файла -->
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0 h-10 w-10 text-gray-500 dark:text-gray-400">
                                            @php
                                            $iconComponent = match(true) {
                                            empty($file->mime_type) => 'icons.file-document',
                                            str_contains($file->mime_type, 'image/') => 'icons.file-image',
                                            str_contains($file->mime_type, 'video/') => 'icons.file-video',
                                            str_contains($file->mime_type, 'audio/') => 'icons.file-music',
                                            str_contains($file->mime_type, 'pdf') => 'icons.file-pdf',
                                            str_contains($file->mime_type, 'zip') || str_contains($file->mime_type, 'rar') => 'icons.file-zip',
                                            str_contains($file->mime_type, 'word') => 'icons.file-word',
                                            str_contains($file->mime_type, 'excel') => 'icons.file-excel',
                                            default => 'icons.file-document'
                                            };
                                            @endphp
                                            <x-dynamic-component :component="$iconComponent" class="h-10 w-10" />
                                        </div>
                                        <div class="ml-4">
                                            <div class="text-sm font-medium text-gray-900 dark:text-white">
                                                {{ $file->name }}
                                            </div>
                                            <div class="text-sm text-gray-500 dark:text-gray-400">
                                                {{ $file->mime_type }}
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <!-- Заметка -->
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($file->note)
                                    <a href="{{ route('notes.show', $file->note) }}" class="text-blue-600 dark:text-blue-400 hover:underline">
                                        {{ Str::limit($file->note->title, 30) }}
                                    </a>
                                    @else
                                    <span class="text-gray-500 dark:text-gray-400">Заметка удалена</span>
                                    @endif
                                </td>

                                <!-- Пользователь -->
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($file->note && $file->note->user)
                                    @php
                                    $user = $file->note->user;
                                    $avatarDir = "avatars/{$user->id}";
                                    $avatarFile = collect(Storage::files($avatarDir))
                                    ->first(fn($f) => preg_match('/^avatars\/' . $user->id . '\/avatar\.(jpg|jpeg|png|svg|gif)$/i', $f));
                                    @endphp

                                    <div class="flex items-center">
                                        <div class="w-10 h-10 rounded-full overflow-hidden bg-gray-200 dark:bg-gray-700 flex items-center justify-center flex-shrink-0">
                                            @if($avatarFile)
                                            <img src="{{ route('avatar.serve', [
                            'user' => $user->id,
                            'filename' => basename($avatarFile)
                        ]) }}"
                                                alt="{{ $user->name }}"
                                                class="h-full w-full object-cover avatar-image"
                                                onload="this.classList.add('loaded')">
                                            @else
                                            @if($user->role === 'admin')
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-purple-500 avatar-image loaded" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                            </svg>
                                            @else
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-gray-500 avatar-image loaded" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                            </svg>
                                            @endif
                                            @endif
                                        </div>
                                        <div class="ml-4">
                                            <div class="text-sm font-medium text-gray-900 dark:text-white">
                                                {{ $user->name }}
                                            </div>
                                            <div class="text-sm text-gray-500 dark:text-gray-400">
                                                {{ $user->email }}
                                            </div>
                                        </div>
                                    </div>
                                    @else
                                    <span class="text-gray-500 dark:text-gray-400">Пользователь удалён</span>
                                    @endif
                                </td>

                                <!-- Размер -->
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                                    {{ formatFileSize($file->size) }}
                                </td>

                                <!-- Дата -->
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                    {{ $file->created_at->format('d.m.Y H:i') }}
                                </td>

                                <!-- Действия -->
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <div class="flex justify-end space-x-2">
                                        @if($file->note)
                                        <a href="{{ route('notes.files.download', ['note' => $file->note, 'file' => $file]) }}"
                                            class="p-2 text-gray-500 hover:text-green-600 dark:hover:text-green-400 rounded-lg hover:bg-gray-300 dark:hover:bg-gray-700 transition-colors"
                                            title="Скачать">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                                            </svg>
                                        </a>
                                        @endif

                                        <form action="{{ $file->note ? route('notes.files.destroy', ['note' => $file->note, 'file' => $file]) : '#' }}" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                class="p-2 text-gray-500 hover:text-red-600 dark:hover:text-red-400 rounded-lg hover:bg-gray-300 dark:hover:bg-gray-700 transition-colors"
                                                title="Удалить"
                                                @if(!$file->note) disabled @endif
                                                onclick="return confirm('Вы уверены, что хотите удалить этот файл?')">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                </svg>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="px-6 py-4 text-center text-red-500 dark:text-red-400">
                                    Файлы не найдены
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
   <!-- Скрипт для плавного отображения после загрузки -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Добавляем класс loaded к body после полной загрузки страницы
            window.addEventListener('load', function() {
                document.body.classList.add('loaded');
            });
            
            // На случай, если событие load не сработает
            setTimeout(function() {
                document.body.classList.add('loaded');
            }, 500);
        });

          function formatFileSize(bytes) {
            if (bytes === 0) return '0 Bytes';
            const k = 1024;
            const sizes = ['Bytes', 'KB', 'MB', 'GB'];
            const i = Math.floor(Math.log(bytes) / Math.log(k));
            return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
        }
    </script>
    @endpush
</x-app-layout>
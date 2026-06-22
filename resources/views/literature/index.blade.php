<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Справочные материалы') }}
            </h2>
            <div class="text-sm text-gray-800 dark:text-gray-400 animate__animated animate__fadeIn">
                {{ now()->translatedFormat('d F Y') }}
            </div>
        </div>
    </x-slot>

    <x-slot name="title">
        {{ __('Справочные материалы') }}
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Карточка с общей статистикой -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                <div class="bg-gray-300 dark:bg-gray-700 rounded-xl shadow p-4 border-l-4 border-blue-500">
                    <div class="text-gray-500 dark:text-gray-300 text-sm font-medium">{{ __('Всего материалов') }}</div>
                    <div class="text-2xl font-bold text-gray-800 dark:text-white mt-1">{{ count($literatures) }}</div>
                </div>
                <div class="bg-gray-300 dark:bg-gray-700 rounded-xl shadow p-4 border-l-4 border-green-500">
                    <div class="text-gray-500 dark:text-gray-300 text-sm font-medium">{{ __('Категорий') }}</div>
                    <div class="text-2xl font-bold text-gray-800 dark:text-white mt-1">{{ $categories->count() }}</div>
                </div>
            </div>

            <!-- Фильтр по категориям -->
            @if($categories->count())
            <div class="mb-6 flex flex-wrap gap-2">
                <a href="{{ route('literature.index') }}"
                    class="px-3 py-2 rounded-lg text-base font-medium
              {{ !$category ? 'bg-blue-600 text-white' : 'bg-gray-300 dark:bg-gray-600 text-gray-800 dark:text-gray-200' }}">
                    Все материалы
                </a>

                @foreach($categories as $cat)
                <a href="{{ route('literature.index', ['category' => $cat]) }}"
                    class="px-3 py-2 rounded-lg text-base font-medium
                  {{ $category === $cat ? 'bg-blue-600 text-white' : 'bg-gray-300 dark:bg-gray-600 text-gray-800 dark:text-gray-200' }}">
                    {{ $cat }}
                </a>
                @endforeach
            </div>
            @endif

            <!-- Основная карточка -->
            <div class="bg-gray-300 dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-xl">
                <div class="p-6">
                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-6 gap-4">
                        <div>
                            <h1 class="text-2xl font-bold text-gray-900 dark:text-white flex items-baseline gap-2">
                                <span>{{ __('Справочные материалы ') }}</span>
                                @if($category)
                                <span class="text-xl font-semibold text-blue-700 dark:text-blue-400">(Категория: {{$category }})</span>
                                @endif
                            </h1>
                        </div>

                        @auth
                        @if(auth()->user()->role === 'admin')
                        <a href="{{ route('literature.create') }}" class="inline-flex items-center px-4 py-2 bg-lime-600 hover:bg-lime-700 text-white font-medium rounded-lg transition duration-200 shadow-sm">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd" />
                            </svg>
                            {{ __('Добавить материал') }}
                        </a>
                        @endif
                        @endauth
                    </div>

                    @if (session('success'))
                    <div class="mb-6 px-4 py-3 bg-green-100 dark:bg-green-900 text-green-700 dark:text-green-200 rounded-lg">
                        <div class="flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                            </svg>
                            {{ session('success') }}
                        </div>
                    </div>
                    @endif

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
                                @forelse ($literatures as $literature)
                                <tr class="hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors">
                                    <!-- Название -->
                                    <td class="px-4 py-3 font-medium text-gray-900 dark:text-white align-middle">
                                        <div class="max-w-[300px] break-words">
                                            {{ $literature->title ?: '—' }}
                                        </div>
                                    </td>

                                    <!-- Описание -->
                                    <td class="px-4 py-3 text-sm text-gray-600 dark:text-gray-300 align-middle">
                                        <div class="max-w-[400px] break-words">
                                            @if($literature->description)
                                                {{ $literature->description }}
                                            @else
                                                <span class="text-gray-400 dark:text-gray-500">—</span>
                                            @endif
                                        </div>
                                    </td>

                                    <!-- Категория -->
                                    <td class="px-4 py-3 align-middle">
                                        @if($literature->category)
                                        <span class="inline-flex px-2 py-1 text-xs bg-blue-100 dark:bg-blue-900 text-blue-800 dark:text-blue-200 rounded-full whitespace-nowrap">
                                            {{ $literature->category }}
                                        </span>
                                        @else
                                            <span class="text-gray-400 dark:text-gray-500">—</span>
                                        @endif
                                    </td>

                                    <!-- Размер -->
                                    <td class="px-4 py-3 text-sm text-gray-600 dark:text-gray-300 align-middle whitespace-nowrap">
                                        {{ $literature->file_size_formatted ?: '—' }}
                                    </td>

                                    <!-- Добавил -->
                                    <td class="px-4 py-3 text-sm text-gray-600 dark:text-gray-300 align-middle whitespace-nowrap">
                                        {{ $literature->user->name ?? '—' }}
                                    </td>

                                    <!-- Действия -->
                                    <td class="px-4 py-3 text-center align-middle">
                                        <div class="flex items-center justify-center gap-3 min-h-[40px]">
                                            <!-- Скачать (всегда показываем) -->
                                            <a href="{{ route('literature.download', $literature) }}"
                                            class="inline-flex items-center justify-center w-8 h-8 text-green-600 hover:text-green-700 dark:text-green-500 dark:hover:text-green-400 transition transform hover:scale-110"
                                            title="Скачать">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                                                    viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                                                </svg>
                                            </a>

                                            @auth
                                            @if(auth()->user()->role === 'admin' || auth()->id() === $literature->user_id)
                                                <!-- Редактировать -->
                                                <a href="{{ route('literature.edit', $literature) }}"
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
                                                <form method="POST" action="{{ route('literature.destroy', $literature) }}" class="inline-flex">
                                                    @csrf
                                                    @method('DELETE')
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
                                            @endif
                                            @endauth
                                        </div>
                                    </td>
                                </tr>
                                @empty
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
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('styles')
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
    @endpush
</x-app-layout>
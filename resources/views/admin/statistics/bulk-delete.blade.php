<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Удаление истории по пользователям') }}
            </h2>
            <div class="text-sm text-gray-800 dark:text-gray-400 animate__animated animate__fadeIn">
                {{ now()->translatedFormat('d F Y') }}
            </div>
        </div>
    </x-slot>

    <x-slot name="title">
        {{ __('Удаление истории посещений') }}
    </x-slot>

    <div class="py-6" x-data="{ showModal: false, selectedUserId: null, selectedUserName: '', showBulkDeleteModal: false }">
        <div class="max-w-6xl mx-auto px-6 lg:px-8">
            <div class="text-sm mb-4 sm:text-base flex justify-between items-center">
                <span class="text-gray-900 dark:text-gray-400">
                    Актуальные данные на: {{ now()->format('d.m.Y H:i') }}
                </span>
                <button wire:navigate href="{{ route('visits.deletePage')}}"
                    class="p-2 rounded-lg hover:bg-gray-300 dark:hover:bg-gray-700 text-gray-500 dark:text-gray-400"
                    title="Обновить данные">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="#4299e1">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                    </svg>
                </button>
            </div>

            <!-- Блоки статистики -->
            <div class="bg-gray-300 dark:bg-gray-800 rounded-2xl shadow-xl p-6 sm:p-8 space-y-6 mb-6">
                <h3 class="text-xl font-semibold text-gray-900 dark:text-white">
                    Статистика посещений
                </h3>

                <div class="grid grid-cols-1 sm:grid-cols-4 gap-6">
                    <div class="bg-gray-200 border border-gray-400 dark:border-gray-600 dark:bg-gray-700 p-4 rounded-lg shadow">
                        <p class="text-sm text-blue-800 dark:text-blue-400">Всего посещений</p>
                        <p class="text-xl font-bold text-gray-900 dark:text-white">{{ $totalVisits }}</p>
                    </div>

                    <div class="bg-gray-200 border border-gray-400 dark:border-gray-600 dark:bg-gray-700 p-4 rounded-lg shadow">
                        <p class="text-sm text-blue-800 dark:text-blue-400">Всего пользователей</p>
                        <p class="text-xl font-bold text-gray-900 dark:text-white">{{ $totalUsers }}</p>
                    </div>

                    <div class="bg-gray-200 border border-gray-400 dark:border-gray-600 dark:bg-gray-700 p-4 rounded-lg shadow">
                        <p class="text-sm text-lime-800 dark:text-lime-600">Пользователи с максимальным числом посещений</p>
                        <ul class="list-disc list-inside text-gray-900 dark:text-gray-100 mt-1">
                            @foreach($mostActiveUsers as $user)
                            <li>{{ $user->name }} — {{ $user->statistics_count }}</li>
                            @endforeach
                        </ul>
                    </div>

                    <div class="bg-gray-200 border border-gray-400 dark:border-gray-600 dark:bg-gray-700 p-4 rounded-lg shadow">
                        <p class="text-sm text-red-700 dark:text-red-400">Пользователи с минимальным числом посещений</p>
                        <ul class="list-disc list-inside text-gray-900 dark:text-gray-100 mt-1">
                            @foreach($leastActiveUsers as $user)
                            <li>{{ $user->name }} — {{ $user->statistics_count }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>

            <div class="bg-gray-300 dark:bg-gray-800 rounded-2xl shadow-xl p-6 sm:p-8 space-y-6">
                <h3 class="text-xl font-semibold text-gray-900 dark:text-white">
                    {{ __('Посещения пользователей') }}
                </h3>

                <div class="overflow-x-auto">
                    <table class="w-full text-sm text-left border-separate border-spacing-y-2">
                        <thead class="text-gray-700 dark:text-gray-300">
                            <tr>
                                <th class="px-4 py-2">Пользователь</th>
                                <th class="px-4 py-2">Email</th>
                                <th class="px-4 py-2 text-center">Посещений</th>
                                <th class="px-4 py-2 text-right">Действие</th>
                            </tr>
                        </thead>
                        <tbody class="text-gray-800 dark:text-gray-200">
                            @foreach($users as $user)
                            <tr class="bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 rounded-xl shadow-sm">
                                <td class="px-4 py-3 rounded-l-xl">
                                    <a
                                        href="{{ url('statistics/user/' . $user->id) }}"
                                        class="text-blue-800 hover:underline hover:text-blue-600 dark:text-blue-400 dark:hover:text-blue-500">
                                        {{ $user->name }}
                                    </a>
                                </td>
                                <td class="px-4 py-3">{{ $user->email }}</td>
                                <td class="px-4 py-3 text-center">{{ $user->statistics_count }}</td>
                                <td class="px-4 py-3 text-right rounded-r-xl">
                                    <button
                                        @click="
                                            showModal = true;
                                            selectedUserId = {{ $user->id }};
                                            selectedUserName = '{{ addslashes($user->name) }}';
                                        "
                                        class="inline-flex items-center px-4 py-2 bg-red-600 hover:bg-red-700 text-white text-sm font-medium rounded-lg transition-shadow shadow-md">
                                        Удалить
                                    </button>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <!-- Кнопка массового удаления по дате -->
                <div class="flex">
                    <button
                        @click="showBulkDeleteModal = true"
                        class="inline-flex items-center px-4 py-2 bg-red-600 hover:bg-red-700 text-white text-sm font-medium rounded-lg shadow">
                        Удалить всё по дате
                    </button>
                </div>
            </div>
        </div>

        <!-- Модалка удаления конкретного пользователя -->
        <div
            x-show="showModal"
            x-data="{ deleteAllTime: true }"
            x-transition:enter="transition ease-out duration-200"
            x-transition:enter-start="opacity-0 scale-95"
            x-transition:enter-end="opacity-100 scale-100"
            x-transition:leave="transition ease-in duration-150"
            x-transition:leave-start="opacity-100 scale-100"
            x-transition:leave-end="opacity-0 scale-95"
            x-cloak
            class="fixed inset-0 flex items-center justify-center bg-black/60 z-50 px-4 sm:px-6">
            <div
                @click.away="showModal = false"
                class="bg-white dark:bg-gray-800 rounded-2xl shadow-2xl p-6 sm:p-8 w-full max-w-md mx-2 sm:mx-0">
                <h2 class="text-lg font-bold text-gray-900 dark:text-white mb-4">
                    Подтвердите удаление
                </h2>
                <p class="text-base text-gray-700 dark:text-gray-300 mb-3">
                    Вы уверены, что хотите <span class="text-red-600 dark:text-red-500 font-semibold">удалить историю посещений</span> пользователя
                    <span class="font-bold text-white underline" x-text="selectedUserName"></span>?
                </p>

                <form method="POST" :action="`{{ url('statistics/user') }}/${selectedUserId}/clear`">
                    @csrf
                    @method('DELETE')

                    <!-- Чекбокс "Удалить за всё время" -->
                    <div class="flex items-center mb-4 space-x-3">
                        <input
                            type="checkbox"
                            id="deleteAllTime"
                            name="delete_all_time"
                            x-model="deleteAllTime"
                            class="h-4 w-4 text-red-600 focus:ring-red-500 border-gray-300 rounded">
                        <label for="deleteAllTime" class="text-sm font-medium text-gray-700 dark:text-gray-300 cursor-pointer">
                            Удалить за всё время
                        </label>
                    </div>

                    <!-- Поле "От" -->
                    <div class="mb-4">
                        <label for="from" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">От:</label>
                        <input
                            type="datetime-local"
                            name="from"
                            id="from"
                            x-ref="from"
                            :disabled="deleteAllTime"
                            :class="deleteAllTime ? 'opacity-50 cursor-not-allowed' : ''"
                            class="block w-full rounded-lg border border-gray-300 bg-white dark:bg-gray-700 dark:text-white dark:border-gray-600 px-4 py-2 focus:border-blue-500 focus:ring focus:ring-blue-200 focus:outline-none transition"
                            :required="!deleteAllTime">
                    </div>

                    <!-- Поле "До" -->
                    <div class="mb-6">
                        <label for="to" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">До:</label>
                        <input
                            type="datetime-local"
                            name="to"
                            id="to"
                            x-ref="to"
                            :disabled="deleteAllTime"
                            :class="deleteAllTime ? 'opacity-50 cursor-not-allowed' : ''"
                            class="block w-full rounded-lg border border-gray-300 bg-white dark:bg-gray-700 dark:text-white dark:border-gray-600 px-4 py-2 focus:border-blue-500 focus:ring focus:ring-blue-200 focus:outline-none transition"
                            :required="!deleteAllTime">
                    </div>

                    <!-- Кнопки -->
                    <div class="flex justify-end gap-3">
                        <button
                            type="button"
                            @click="showModal = false"
                            class="px-4 py-2 bg-gray-300 dark:bg-gray-700 text-gray-900 dark:text-white rounded-lg hover:bg-gray-400">
                            Отмена
                        </button>
                        <button
                            type="submit"
                            class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700">
                            Удалить
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Модалка массового удаления по дате -->
        <div
            x-show="showBulkDeleteModal"
            x-transition
            x-cloak
            class="fixed inset-0 flex items-center justify-center bg-black/60 z-50 px-4 sm:px-6">
            <div
                @click.away="showBulkDeleteModal = false"
                x-data="{ deleteAllTime: false }"
                class="bg-white dark:bg-gray-800 rounded-2xl shadow-2xl p-6 sm:p-8 w-full max-w-md mx-2 sm:mx-0">
                <h2 class="text-lg font-bold text-gray-900 dark:text-white mb-6 text-center">
                    <span class="block">Удалить посещения</span>
                    <span class="block text-red-700 dark:text-red-400 underline">всех пользователей по дате</span>
                </h2>

                <form method="POST" action="{{ route('visits.bulkDeleteByDate') }}">
                    @csrf
                    @method('DELETE')

                    <div class="flex items-center mb-6 space-x-3">
                        <input
                            type="checkbox"
                            id="deleteAllTime"
                            name="delete_all_time"
                            x-model="deleteAllTime"
                            class="h-4 w-4 text-red-600 focus:ring-red-500 border-gray-300 rounded cursor-pointer">
                        <span class="text-sm font-medium text-gray-700 dark:text-gray-300">
                            Удалить за всё время
                        </span>
                    </div>

                    <div class="mb-5">
                        <label for="from" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">От:</label>
                        <div class="relative">
                            <input
                                type="datetime-local"
                                name="from"
                                id="from"
                                x-ref="from"
                                :disabled="deleteAllTime"
                                :class="deleteAllTime ? 'opacity-50 cursor-not-allowed' : ''"
                                class="peer block w-full appearance-none rounded-lg border border-gray-300 bg-white dark:bg-gray-700 dark:text-white dark:border-gray-600 px-4 py-2 focus:border-blue-500 focus:ring focus:ring-blue-200 focus:outline-none transition"
                                :required="!deleteAllTime">
                        </div>
                    </div>

                    <div class="mb-6">
                        <label for="to" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">До:</label>
                        <div class="relative">
                            <input
                                type="datetime-local"
                                name="to"
                                id="to"
                                x-ref="to"
                                :disabled="deleteAllTime"
                                :class="deleteAllTime ? 'opacity-50 cursor-not-allowed' : ''"
                                class="peer block w-full appearance-none rounded-lg border border-gray-300 bg-white dark:bg-gray-700 dark:text-white dark:border-gray-600 px-4 py-2 focus:border-blue-500 focus:ring focus:ring-blue-200 focus:outline-none transition"
                                :required="!deleteAllTime">
                        </div>
                    </div>

                    <div class="flex justify-end gap-3">
                        <button type="button"
                            @click="showBulkDeleteModal = false"
                            class="px-4 py-2 bg-gray-300 dark:bg-gray-700 text-gray-900 dark:text-white rounded-lg hover:bg-gray-400">
                            Отмена
                        </button>
                        <button type="submit"
                            class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700">
                            Удалить
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('bulkDeleteModal', () => ({
                deleteAllTime: false,
                showBulkDeleteModal: false,
            }))
        })
    </script>
</x-app-layout>
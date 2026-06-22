@foreach ($tasks as $task)
    <div class="task-item bg-gray-300 dark:bg-gray-800 shadow-lg rounded-lg overflow-hidden hover:shadow-xl transition-shadow duration-300 relative">
        <!-- Полоска для пометки задачи выполненной -->
        <button onclick="toggleTaskCompletion({{ $task->id }}, this)"
                class="absolute left-0 top-0 bottom-0 w-2 hover:w-3 transition-all duration-300 cursor-pointer {{ $task->completed ? 'bg-green-600' : 'bg-red-600' }}">
        </button>

        <!-- Edit Icon -->
        @if ($task->user_id === auth()->id() || auth()->user()->isAdmin())
            <a href="{{ route('tasks.edit', $task) }}"
               class="absolute top-2 right-2 text-yellow-500 hover:text-yellow-700 transition duration-300">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                     xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path>
                </svg>
            </a>
        @endif

        <!-- Delete Icon -->
        @if ($task->user_id === auth()->id() || auth()->user()->isAdmin())
            <form action="{{ route('tasks.destroy', $task) }}" method="POST" class="absolute bottom-2 right-2">
                @csrf
                @method('DELETE')
                <button type="submit" class="text-red-500 hover:text-red-700 transition duration-300">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                         xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                    </svg>
                </button>
            </form>
        @endif

        <!-- Остальная часть задачи -->
        <div class="p-4 pl-6 pr-16">
            <h5 class="text-xl font-bold text-gray-900 dark:text-white break-words whitespace-normal">
                {{ $task->title }}
            </h5>
            <div class="mt-2 text-sm text-gray-600 dark:text-gray-400">
                {{ $task->description }}
            </div>
            <div class="mt-4 text-sm text-gray-500 dark:text-gray-400">
                <p class="due-date">
                    @if($task->due_date)
                        {{ __('Дата выполнения:') }} {{ \Carbon\Carbon::parse($task->due_date)->format('d.m.Y') }}
                    @else
                        {{ __('Задача не выполнена') }}
                    @endif
                </p>
                <p>{{ __('Категория:') }} {{ $task->category ?? 'Без категории' }}</p>
            </div>
        </div>
    </div>
@endforeach
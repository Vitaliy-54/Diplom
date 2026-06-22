@if($notes->isEmpty())
    <div class="p-4 bg-red-300 dark:bg-red-300 text-red-800 dark:text-red-800 rounded-lg mb-6">
        <p>{{ __('Ничего не найдено по вашему запросу.') }}</p>
    </div>
@else
    @foreach ($notes as $note)
    <div class="note-container bg-gray-300 dark:bg-gray-800 shadow-lg rounded-lg overflow-hidden hover:shadow-xl transition-all duration-300 mb-6"
        data-user-id="{{ $note->user_id }}"
        data-is-public="{{ $note->is_public }}"
        data-note-id="{{ $note->id }}">
        
        <!-- Верхняя часть с пользователем -->
        <div class="bg-gray-400 dark:bg-gray-700 p-4 flex items-center justify-between">
            <p class="text-sm text-gray-700 dark:text-gray-400">
                {{ __('Пользователь:') }} {{ $note->user->name }}
            </p>
            @if($tab === 'my')
                @if($note->is_public)
                    <span class="px-2 py-1 bg-green-700 dark:bg-green-900 text-green-100 dark:text-green-200 text-xs rounded-full">
                        {{ __('Публичная') }}
                    </span>
                @else
                    <span class="px-2 py-1 bg-red-700 dark:bg-red-900 text-red-100 dark:text-red-200 text-xs rounded-full">
                        {{ __('Приватная') }}
                    </span>
                @endif

            @elseif($tab === 'private')
                @if($note->is_public)
                    <span class="px-2 py-1 bg-green-700 dark:bg-green-900 text-green-100 dark:text-green-200 text-xs rounded-full">
                        {{ __('Публичная') }}
                    </span>
                @else
                    <span class="px-2 py-1 bg-red-700 dark:bg-red-900 text-red-100 dark:text-red-200 text-xs rounded-full">
                        {{ __('Приватная') }}
                    </span>
                @endif

            @elseif($tab === 'all')
                <!-- Для не-AJAX запросов в разделе "Все заметки" показываем статус только своих заметок -->
                @if($note->is_public)
                    <span class="px-2 py-1 bg-green-700 dark:bg-green-900 text-green-100 dark:text-green-200 text-xs rounded-full">
                        {{ __('Публичная') }}
                    </span>
                @else
                    <span class="px-2 py-1 bg-red-700 dark:bg-red-900 text-red-100 dark:text-red-200 text-xs rounded-full">
                        {{ __('Приватная') }}
                    </span>
                @endif
            @endif
        </div>

              <!-- Левая часть: Заголовок и описание -->
              <div class="p-4 flex-1 min-w-0">
            <!-- Заголовок заметки -->
            <h5 class="text-xl font-bold text-gray-900 dark:text-white note-title">
                <a href="{{ route('notes.show', $note) }}">{{ $note->title }}</a>
            </h5>

            <!-- Контейнер для описания -->
            <div class="relative">
                <div class="text-gray-900 dark:text-gray-300 overflow-hidden transition-all duration-300 ease-in-out ck-content note-content"
                    id="description-{{ $note->id }}">
                    {!! $note->description !!}
                </div>
            </div>
        </div>

        <!-- Теги -->
        @if($note->tags->count() > 0)
        <div class="flex flex-wrap gap-2 p-2 border-t border-gray-400 dark:border-gray-700">
            <span class="ml-2 rounded-lg text-sm text-gray-800 dark:text-gray-300">
                Теги:
            </span>
            @foreach($note->tags as $tag)
            <span class="px-2 py-1 bg-blue-600 dark:bg-blue-900 text-blue-200 dark:text-blue-200 text-xs rounded-full">
                {{ $tag->name }}
            </span>
            @endforeach
        </div>
        @endif

        <!-- Кнопки действий -->
        <div class="flex items-center justify-between p-4 border-t border-gray-400 dark:border-gray-700">
            <button onclick="toggleDescription({{$note->id}})"
                class="toggle-button px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700 transition duration-300"
                id="toggle-button-{{ $note->id }}"
                title="{{ __('Развернуть') }}">
                <i class="fas fa-angle-double-down"></i>
            </button>

            @if ($note->user_id === auth()->id() || auth()->user()->isAdmin())
            <div class="flex items-center gap-2 justify-end flex-1">
                <a href="{{ route('notes.edit', $note) }}" class="text-yellow-600 hover:text-yellow-700 transition duration-300 mr-4" title="{{ __('Изменить') }}">
                    <i class="fas fa-edit fa-lg"></i>
                </a>
                <form action="{{ route('notes.destroy', $note) }}" method="POST" onsubmit="return confirm('Вы уверены, что хотите удалить эту заметку?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="text-red-600 hover:text-red-700 transition duration-300" title="{{ __('Удалить') }}">
                        <i class="fas fa-trash fa-lg"></i>
                    </button>
                </form>
            </div>
            @endif
        </div>
        
        <!-- Нижняя часть с датами -->
        <div class="p-4 border-t border-gray-400 dark:border-gray-700">
            <div class="flex items-center justify-between text-sm text-gray-700 dark:text-gray-400">
                <p>
                    {{ __('Создана:') }} {{ $note->created_at->format('d.m.Y H:i') }}
                </p>
                <p>
                    {{ __('Изменена:') }} {{ $note->updated_at->format('d.m.Y H:i') }}
                </p>
            </div>
        </div>
    </div>
    @endforeach

    @if($notes->hasMorePages())
    <div class="pagination" style="display: none;">
        <a href="{{ $notes->nextPageUrl() }}" rel="next"></a>
    </div>
    @endif
@endif
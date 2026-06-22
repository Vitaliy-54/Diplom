@if($notes->isEmpty())
<div class="mb-5 overflow-hidden rounded-2xl border border-red-200 bg-gradient-to-r from-red-100 to-red-50 p-5 shadow-sm dark:border-red-900/50 dark:from-red-950 dark:to-red-900/40">
    <div class="flex items-center gap-3">
        <div class="flex h-11 w-11 items-center justify-center rounded-full bg-red-200 dark:bg-red-800/60">
            <i class="fas fa-search text-base text-red-700 dark:text-red-300"></i>
        </div>

        <div>
            <h3 class="text-base font-semibold text-red-800 dark:text-red-200">
                {{ __('Ничего не найдено') }}
            </h3>

            <p class="text-sm text-red-700 dark:text-red-300">
                {{ __('Ничего не найдено по вашему запросу.') }}
            </p>
        </div>
    </div>
</div>
@else

<div class="grid grid-cols-1 gap-5">
@foreach ($notes as $note)

<div class="group relative overflow-hidden rounded-2xl border border-gray-400/70 bg-white/95 shadow-md transition-all duration-300 hover:shadow-xl dark:border-gray-500/50 dark:bg-gray-900/95"
    data-user-id="{{ $note->user_id }}"
    data-is-public="{{ $note->is_public }}"
    data-note-id="{{ $note->id }}">

    <!-- Header -->
    <div class="relative flex items-center justify-between border-b border-gray-200/70 bg-gradient-to-r from-gray-400/60 to-gray-400/40 px-5 py-4 dark:border-gray-700/60 dark:from-gray-700/60 dark:to-gray-800">

        <a href="{{ route('user.info', ['user' => $note->user->id]) }}"
           class="group/user flex items-center gap-3">

            <div>
                <x-avatar :user="$note->user" />
            </div>

            <div>
                <p class="text-base font-semibold text-gray-900 transition-colors duration-300 group-hover/user:text-indigo-600 dark:text-white dark:group-hover/user:text-indigo-400">
                    {{ $note->user->name }}
                </p>

                <p class="text-xs text-gray-500 dark:text-gray-400">
                    {{ __('Автор заметки') }}
                </p>
            </div>
        </a>

        @if($note->is_public)
        <span class="inline-flex items-center gap-1 rounded-full border border-green-500/10 bg-green-500/20 px-3 py-1.5 text-xs font-medium text-green-700 dark:text-green-300">
            <span class="h-2 w-2 rounded-full bg-green-500"></span>
            {{ __('Публичная') }}
        </span>
        @else
        <span class="inline-flex items-center gap-1 rounded-full border border-red-500/20 bg-red-500/10 px-3 py-1.5 text-xs font-medium text-red-700 dark:text-red-300">
            <span class="h-2 w-2 rounded-full bg-red-500"></span>
            {{ __('Приватная') }}
        </span>
        @endif
    </div>

    <!-- Content -->
    <div class="relative px-5 py-4">

        <!-- Title -->
        <h2 class="mb-3 text-xl font-bold leading-tight text-gray-900 dark:text-white">
            <a href="{{ route('notes.show', $note) }}"
               class="transition-colors duration-300 hover:text-indigo-600 dark:hover:text-indigo-400">
                {{ $note->title }}
            </a>
        </h2>

        <!-- Description -->
        <div class="relative">
            <div class="ck-content note-content text-base leading-relaxed text-gray-700 dark:text-gray-300"
                 id="description-{{ $note->id }}">
                {!! $note->description !!}
            </div>
        </div>

        
        <!-- Meta -->
        <div class="mt-2 flex flex-wrap items-center gap-2.5">

            @if($note->tags->count() > 0)
                @foreach($note->tags as $tag)
                <span class="inline-flex items-center rounded-full border border-blue-500/20 bg-blue-500/10 px-3 py-1.5 text-xs font-medium text-blue-700 dark:text-blue-300">
                    <i class="fas fa-hashtag mr-1 text-[10px]"></i>
                    {{ $tag->name }}
                </span>
                @endforeach
            @endif

            @if($note->files->count() > 0)
            <span class="inline-flex items-center rounded-full border border-orange-500/20 bg-orange-500/10 px-3 py-1.5 text-xs font-medium text-orange-700 dark:text-orange-300">
                <i class="fas fa-paperclip mr-2"></i>

                {{ $note->files->count() }}
                {{ trans_choice('файл|файла|файлов', $note->files->count()) }}
            </span>
            @endif

            @if($note->comments->count() > 0)
            <span class="inline-flex items-center rounded-full border border-cyan-500/20 bg-cyan-500/10 px-3 py-1.5 text-xs font-medium text-cyan-700 dark:text-cyan-300">
                <i class="fas fa-comments mr-2"></i>

                {{ $note->comments->count() }}
                {{ trans_choice('комментарий|комментария|комментариев', $note->comments->count()) }}
            </span>
            @endif
        </div>
    </div>

    <!-- Footer -->
    <div class="relative border-t border-gray-200/70 bg-gray-50/80 px-5 py-4 dark:border-gray-700/60 dark:bg-gray-900/60">

        <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">

            <!-- Dates -->
            <div class="flex flex-col gap-1.5 text-sm text-gray-600 dark:text-gray-400">
                <p class="flex items-center gap-2">
                    <i class="far fa-calendar-plus text-green-500"></i>
                    {{ __('Создана:') }}
                    <span class="font-medium">
                        {{ $note->created_at->format('d.m.Y H:i') }}
                    </span>
                </p>

                <p class="flex items-center gap-2">
                    <i class="far fa-clock text-blue-500"></i>
                    {{ __('Изменена:') }}
                    <span class="font-medium">
                        {{ $note->updated_at->format('d.m.Y H:i') }}
                    </span>
                </p>
            </div>

            <!-- Actions -->
            <div class="flex items-center justify-between gap-2.5 lg:justify-end">
                <button onclick="toggleDescription({{$note->id}})"
                    class="toggle-button inline-flex h-10 w-10 items-center justify-center rounded-xl bg-gray-500 text-base text-white shadow-sm transition-all duration-300 hover:bg-gray-800 dark:bg-gray-600 dark:hover:bg-gray-800"
                    id="toggle-button-{{ $note->id }}"
                    title="{{ __('Развернуть') }}">
                    <i class="fas fa-angle-double-down"></i>
                </button>

                <div class="flex items-center gap-2.5">
                    @if ($note->user_id === auth()->id() || auth()->user()->isAdmin())
                    <a href="{{ route('notes.edit', $note) }}"
                    class="inline-flex h-10 w-10 items-center justify-center rounded-xl bg-yellow-500 text-base text-white shadow-sm transition-all duration-300 hover:bg-yellow-600 dark:bg-yellow-600 dark:hover:bg-yellow-700"
                    title="{{ __('Изменить') }}">
                        <i class="fas fa-pen"></i>
                    </a>

                    <form action="{{ route('notes.destroy', $note) }}"
                        method="POST"
                        onsubmit="return confirm('Вы уверены, что хотите удалить эту заметку?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit"
                            class="inline-flex h-10 w-10 items-center justify-center rounded-xl bg-red-500 text-base text-white shadow-sm transition-all duration-300 hover:bg-red-600 dark:bg-red-600 dark:hover:bg-red-700"
                            title="{{ __('Удалить') }}">
                            <i class="fas fa-trash"></i>
                        </button>
                    </form>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

@endforeach
</div>

@if($notes->hasMorePages())
<div class="pagination hidden">
    <a href="{{ $notes->nextPageUrl() }}" rel="next"></a>
</div>
@endif

@endif
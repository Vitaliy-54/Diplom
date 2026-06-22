{{-- resources/views/livewire/comments.blade.php --}}
<div class="yt-comments" 
     x-data="{ activeFormId: null, visible: false, deleteModalOpen: false, commentToDelete: null, sortDropdownOpen: false }" 
     x-init="$nextTick(() => { setTimeout(() => { visible = true; }, 50); })"
     x-show="visible"
     x-transition:enter="transition ease-out duration-500"
     x-transition:enter-start="opacity-0 transform scale-95"
     x-transition:enter-end="opacity-100 transform scale-100"
     @comment-added.window="document.dispatchEvent(new CustomEvent('clear-editor-main'))">

    {{-- ── Модальное окно удаления ── --}}
    <div x-show="deleteModalOpen" 
         x-cloak
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 z-50 flex items-center justify-center px-4"
         style="background-color: rgba(0, 0, 0, 0.3); backdrop-filter: blur(4px);">
        
        <div @click.away="deleteModalOpen = false"
             x-show="deleteModalOpen"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 transform scale-95"
             x-transition:enter-end="opacity-100 transform scale-100"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100 transform scale-100"
             x-transition:leave-end="opacity-0 transform scale-95"
             class="yt-modal"
             style="max-width: 400px; width: 100%; background: var(--yt-bg2); border-radius: 28px; box-shadow: 0 8px 32px rgba(0, 0, 0, 0.4); overflow: hidden;">
            
            <div style="padding: 24px;">
                <div style="text-align: center; margin-bottom: 20px;">
                    <div style="width: 56px; height: 56px; background: rgba(255, 78, 69, 0.1); border-radius: 50%; display: inline-flex; align-items: center; justify-content: center; margin-bottom: 16px;">
                        <i class="fas fa-trash-alt" style="font-size: 28px; color: var(--yt-danger);"></i>
                    </div>
                    <h3 style="font-size: 20px; font-weight: 500; margin-bottom: 8px; color: var(--yt-text);">Удалить комментарий?</h3>
                    <p style="font-size: 14px; color: var(--yt-text2); margin: 0;">
                        Это действие невозможно отменить. Комментарий будет удален навсегда.
                    </p>
                </div>
                
                <div style="display: flex; gap: 12px; justify-content: flex-end; margin-top: 24px;">
                    <button @click="deleteModalOpen = false" 
                            class="yt-btn yt-btn--text" 
                            style="flex: 1; justify-content: center;">
                        Отмена
                    </button>
                    <button @click="$wire.deleteComment(commentToDelete); deleteModalOpen = false; commentToDelete = null;"
                            wire:loading.attr="disabled"
                            wire:target="deleteComment"
                            class="yt-btn" 
                            style="flex: 1; justify-content: center; background: var(--yt-danger); color: white; border-radius: 18px;">
                        <span wire:loading.remove wire:target="deleteComment">Удалить</span>
                        <span wire:loading wire:target="deleteComment">
                            <i class="fas fa-spinner fa-spin"></i> Удаление...
                        </span>
                    </button>
                </div>
            </div>
        </div>
    </div>

{{-- ── Header ── --}}
<div class="yt-comments__header">
    @php
    $commentsCount = $this->note->comments()->count();
    $commentsWord = match($commentsCount) {
        1 => 'комментарий',
        2, 3, 4 => 'комментария',
        default => 'комментариев'
    };
@endphp

<span class="yt-comments__count" style="font-size: 20px; font-weight: bold;">
    {{ $commentsCount }} {{ $commentsWord }}
</span>

    {{-- Кнопка сортировки с выпадающим меню --}}
    <div class="yt-sort-dropdown" x-data="{ open: false }" @click.away="open = false">
        <button @click="open = !open" class="yt-btn yt-btn--text yt-sort-button" style="display:flex;align-items:center;gap:6px;width:100%;">
            <i class="fas fa-sort-amount-down" style="font-size:16px"></i>
            <span class="yt-sort-label">
                @if($currentSort === 'latest')
                    Сначала новые
                @elseif($currentSort === 'oldest')
                    Сначала старые
                @elseif($currentSort === 'most_replies')
                    По количеству ответов
                @elseif($currentSort === 'most_likes')
                    По количеству лайков
                @else
                    Сортировка
                @endif
            </span>
            <i class="fas fa-chevron-down yt-sort-icon" style="font-size: 12px; transition: transform 0.2s; margin-left: auto;" :style="open ? 'transform: rotate(180deg)' : ''"></i>
        </button>
        
        <div x-show="open" 
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0 transform scale-95"
             x-transition:enter-end="opacity-100 transform scale-100"
             class="yt-dropdown-menu yt-sort-menu"
             style="left: auto; right: 0; min-width: 220px;"
             @click.stop>
            
            <button wire:click="setSortBy('latest')" 
                    @click="open = false"
                    class="yt-dropdown-item {{ $currentSort === 'latest' ? 'yt-dropdown-item--active' : '' }}">
                <i class="fas fa-clock"></i>
                <span>Сначала новые</span>
                @if($currentSort === 'latest')
                    <i class="fas fa-check" style="margin-left: auto; font-size: 12px;"></i>
                @endif
            </button>
            
            <button wire:click="setSortBy('oldest')" 
                    @click="open = false"
                    class="yt-dropdown-item {{ $currentSort === 'oldest' ? 'yt-dropdown-item--active' : '' }}">
                <i class="fas fa-history"></i>
                <span>Сначала старые</span>
                @if($currentSort === 'oldest')
                    <i class="fas fa-check" style="margin-left: auto; font-size: 12px;"></i>
                @endif
            </button>
            
            <div class="yt-dropdown-divider"></div>
            
            <button wire:click="setSortBy('most_replies')" 
                    @click="open = false"
                    class="yt-dropdown-item {{ $currentSort === 'most_replies' ? 'yt-dropdown-item--active' : '' }}">
                <i class="fas fa-reply-all"></i>
                <span>По количеству ответов</span>
                @if($currentSort === 'most_replies')
                    <i class="fas fa-check" style="margin-left: auto; font-size: 12px;"></i>
                @endif
            </button>
            
            <button wire:click="setSortBy('most_likes')" 
                    @click="open = false"
                    class="yt-dropdown-item {{ $currentSort === 'most_likes' ? 'yt-dropdown-item--active' : '' }}">
                <i class="fas fa-thumbs-up"></i>
                <span>По количеству лайков</span>
                @if($currentSort === 'most_likes')
                    <i class="fas fa-check" style="margin-left: auto; font-size: 12px;"></i>
                @endif
            </button>
        </div>
    </div>
</div>

    {{-- ── Compose (Main Comment Form) ── --}}
    @auth
    <div class="yt-compose" wire:key="comment-form-{{ auth()->id() }}" x-data="{ active: false }">
        <div wire:ignore wire:key="avatar-compose-{{ auth()->id() }}">
            <x-avatar :user="auth()->user()" class="yt-avatar" />
        </div>
        <div class="yt-compose__field">
            <form wire:submit.prevent="addComment">
                <div
                    contenteditable="plaintext-only"
                    wire:ignore
                    x-data="contentEditableEditor(@entangle('content'))"
                    @clear-editor-main.window="clearContent()"
                    @input="update()"
                    @focus="active = true"
                    class="yt-compose__input"
                    role="textbox"
                    :class="{ 'empty': isEmpty }"
                    data-placeholder="Введите комментарий…"
                    style="min-height: 40px; overflow: hidden; resize: none; white-space: pre-wrap; word-wrap: break-word; cursor: text;"
                ></div>

                {{-- Нижняя панель --}}
                <div class="flex items-center justify-between mt-2" x-show="active" x-cloak>
                    <div
                        class="text-xs transition-colors duration-200 "
                        :class="{
                            'dark:text-gray-200 text-gray-900': ($wire.content?.length || 0) < 4500,
                            'text-yellow-500': ($wire.content?.length || 0) >= 4500,
                            'text-red-500 font-semibold': ($wire.content?.length || 0) >= 4900
                        }"
                    >
                        <span x-text="$wire.content?.length || 0"></span>/5000
                    </div>

                    @error('content')
                        <p class="yt-error">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Кнопки --}}
                <div class="yt-compose__btns" x-show="active" x-cloak>
                    <button type="button" class="yt-btn yt-btn--text" @click="active = false; $dispatch('clear-editor-main')">
                        Отмена
                    </button>

                    <button
                        type="submit"
                        class="yt-btn yt-btn--primary"
                        style="min-width: 140px; justify-content: center; user-select: none;"
                        wire:loading.attr="disabled"
                        wire:target="addComment"
                        :disabled="!$wire.content || !$wire.content.trim()"
                        :class="{ 'opacity-60 cursor-not-allowed pointer-events-none': !$wire.content || !$wire.content.trim() }"
                    >
                        <span wire:loading.remove wire:target="addComment">Отправить</span>
                        <span wire:loading wire:target="addComment"><i class="fas fa-spinner fa-spin"></i> Отправка</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
    @else
    <div class="yt-auth-prompt" wire:key="login-message">
        <i class="fas fa-user-circle" style="font-size:40px;color:var(--yt-text3);margin-bottom:8px"></i>
        <p><a href="{{ route('login') }}" class="yt-link">Войдите</a>, чтобы оставить комментарий.</p>
    </div>
    @endauth

    {{-- ── Comments List ── --}}
    <div wire:key="comments-list-{{ $comments->currentPage() }}-{{ $currentSort }}">
        @forelse($comments as $comment)
        <div id="comment-{{ $comment->id }}" class="yt-comment" wire:key="comment-{{ $comment->id }}">
            
            <a href="{{ route('user.info', $comment->user) }}" class="yt-comment__avatar-link">
                <div wire:ignore wire:key="avatar-{{ $comment->user->id }}">
                    <x-avatar :user="$comment->user" class="yt-avatar" />
                </div>
            </a>

            <div class="yt-comment__body">
                <div class="yt-comment__header-wrapper">
                    <div class="yt-comment__meta">
                        <a href="{{ route('user.info', $comment->user) }}" class="yt-comment__author">
                            <b>&#64;{{ $comment->user->name }}</b>
                        </a>
                        <time class="yt-comment__time" title="{{ $comment->created_at->format('d.m.Y H:i:s') }}">
                            {{ $comment->formatted_date }}
                        </time>
                        
                        {{-- Дополнительная информация для сортировки (опционально) --}}
                        @if($currentSort === 'most_replies' && $comment->replies->count() > 0)
                            
                               {{-- Вместо строки с fa-reply-all --}}
                                @php
                                    $repliesCount = $comment->replies->count();
                                    $repliesWord = match($repliesCount) {
                                        0 => 'ответов',
                                        1 => 'ответ',
                                        2, 3, 4 => 'ответа',
                                        default => 'ответов'
                                    };
                                @endphp
                                <span class="yt-comment__badge">
                                    <i class="fas fa-reply-all"></i> {{ $repliesCount }} {{ $repliesWord }}
                                </span>
                         
                        @endif
                        
                        @if($currentSort === 'most_likes' && ($comment->reactions_count['like'] ?? 0) > 0)
                            @php
                                $likesCount = $comment->reactions_count['like'] ?? 0;
                                $likesWord = match($likesCount) {
                                    0 => 'лайков',
                                    1 => 'лайк',
                                    2, 3, 4 => 'лайка',
                                    default => 'лайков'
                                };
                            @endphp
                            <span class="yt-comment__badge">
                                <i class="fas fa-thumbs-up"></i> {{ $likesCount }} {{ $likesWord }}
                            </span>
                        @endif
                    </div>

                    {{-- Меню с тремя точками для комментария --}}
                    @canany(['update', 'delete'], $comment)
                    <div class="yt-dropdown" x-data="{ open: false }" @click.away="open = false">
                        <button @click="open = !open" class="yt-act yt-act--menu" title="Действия">
                            <i class="fas fa-ellipsis-v"></i>
                        </button>
                        
                        <div x-show="open" 
                            x-transition:enter="transition ease-out duration-200"
                            x-transition:enter-start="opacity-0 transform scale-95"
                            x-transition:enter-end="opacity-100 transform scale-100"
                            class="yt-dropdown-menu"
                            @click.stop>
                            @can('update', $comment)
                            <button wire:click="startEdit({{ $comment->id }})" 
                                    @click="open = false"
                                    class="yt-dropdown-item">
                                <i class="fas fa-pen"></i>
                                <span>Редактировать</span>
                            </button>
                            @endcan
                            
                            @can('delete', $comment)
                            <button @click="open = false; commentToDelete = {{ $comment->id }}; deleteModalOpen = true" 
                                    class="yt-dropdown-item yt-dropdown-item--danger">
                                <i class="fas fa-trash"></i>
                                <span>Удалить</span>
                            </button>
                            @endcan
                        </div>
                    </div>
                    @endcanany
                </div>

                {{-- Редактирование основного комментария --}}
                @if($editingComment === $comment->id)
                    <div class="yt-edit-form">
                        <div
                            contenteditable="plaintext-only"
                            wire:ignore
                            x-data="contentEditableEditor(@entangle('editContent'))"
                            @input="update()"
                            class="yt-compose__input"
                            role="textbox"
                            :class="{ 'empty': isEmpty }"
                            data-placeholder="Редактировать комментарий…"
                            style="min-height: 40px; overflow: hidden; resize: none; white-space: pre-wrap; word-wrap: break-word;"
                        ></div>
                        <div class="flex items-center justify-between mt-2">
                            <div
                                class="text-xs transition-colors duration-200"
                                :class="{
                                    'text-gray-900 dark:text-gray-200': ($wire.editContent?.length || 0) < 4500,
                                    'text-yellow-500': ($wire.editContent?.length || 0) >= 4500,
                                    'text-red-500 font-semibold': ($wire.editContent?.length || 0) >= 4900
                                }"
                            >
                                <span x-text="$wire.editContent?.length || 0"></span>/5000
                            </div>

                            @error('editContent')
                                <p class="yt-error">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="yt-compose__btns" style="display:flex">
                            <button wire:click="cancelEdit" class="yt-btn yt-btn--text">Отмена</button>
                            <button
                                wire:click="updateComment({{ $comment->id }})"
                                wire:loading.attr="disabled"
                                wire:target="updateComment"
                                class="yt-btn yt-btn--primary"
                                :disabled="!$wire.editContent || !$wire.editContent.trim()"
                                :class="{
                                    'opacity-60 cursor-not-allowed pointer-events-none':
                                    !$wire.editContent || !$wire.editContent.trim()
                                }"
                            >
                                <span wire:loading.remove wire:target="updateComment">
                                    Сохранить
                                </span>
                                <span wire:loading wire:target="updateComment">
                                    <i class="fas fa-spinner fa-spin"></i> Сохранение
                                </span>
                            </button>
                        </div>
                    </div>
                @else
                    <div class="yt-comment__text">{!! nl2br(e($comment->content)) !!}</div>

                    {{-- Действия --}}
                    <div class="yt-comment__actions">
                        @php $rc = $comment->reactions_count ?? []; $ur = $comment->user_reaction ?? null; @endphp
                        <button wire:click="toggleReaction({{ $comment->id }}, 'like')" class="yt-act {{ $ur === 'like' ? 'yt-act--active' : '' }}">
                            <i class="{{ $ur === 'like' ? 'fas' : 'far' }} fa-thumbs-up"></i>
                            @if(($rc['like'] ?? 0) > 0) <span>{{ $rc['like'] }}</span> @endif
                        </button>

                        <button wire:click="toggleReaction({{ $comment->id }}, 'dislike')" class="yt-act {{ $ur === 'dislike' ? 'yt-act--active' : '' }}" style="margin-right:4px">
                            <i class="{{ $ur === 'dislike' ? 'fas' : 'far' }} fa-thumbs-down"></i>
                            @if(($rc['dislike'] ?? 0) > 0) <span>{{ $rc['dislike'] }}</span> @endif
                        </button>

                        @auth
                        <button wire:click="startReply({{ $comment->id }})" class="yt-btn yt-btn--text yt-btn--reply">
                            Ответить
                        </button>
                        @endauth
                    </div>

                    {{-- ── Форма ответа (Contenteditable) ── --}}
                    @if($replyingTo === $comment->id)
                    <div class="yt-reply-compose" wire:key="reply-form-{{ $comment->id }}">
                        <div wire:ignore wire:key="avatar-reply-{{ auth()->id() }}">
                            <x-avatar :user="auth()->user()" class="yt-avatar yt-avatar--sm" />
                        </div>
                        <div style="flex:1">
                            <form wire:submit.prevent="addReply({{ $comment->id }})">
                                <div
                                    contenteditable="plaintext-only"
                                    wire:ignore
                                    x-data="replyEditor(@entangle('replyContent.' . $comment->id), {{ $comment->id }})"
                                    @input="update()"
                                    class="yt-compose__input"
                                    role="textbox"
                                    :class="{ 'empty': isEmpty }"
                                    data-placeholder="Ответить &#64;{{ $comment->user->name }}…"
                                    style="min-height: 32px; overflow: hidden; resize: none; white-space: pre-wrap; word-wrap: break-word; cursor: text;"
                                ></div>

                                {{-- Счетчик символов для ответа --}}
                                <div class="flex items-center justify-between mt-2">
                                    <div 
                                        class="text-xs transition-colors duration-200"
                                        x-data="{ commentId: {{ $comment->id }} }"
                                        x-init="
                                            $watch('$wire.replyContent', () => {
                                                const length = $wire.replyContent?.[commentId]?.length || 0;
                                                $el.querySelector('span').textContent = length;
                                                
                                                if (length < 4500) {
                                                    $el.className = 'text-xs transition-colors duration-200 text-gray-400';
                                                } else if (length >= 4500 && length < 4900) {
                                                    $el.className = 'text-xs transition-colors duration-200 text-yellow-500';
                                                } else if (length >= 4900) {
                                                    $el.className = 'text-xs transition-colors duration-200 text-red-500 font-semibold';
                                                }
                                            }, { deep: true })
                                        "
                                    >
                                        <span x-text="$wire.replyContent?.[{{ $comment->id }}]?.length || 0"></span>/5000
                                    </div>
                                </div>

                                <div class="yt-compose__btns" style="display:flex">
                                    <button type="button" wire:click="startReply(null)" class="yt-btn yt-btn--text">Отмена</button>
                                    <button
                                        type="submit"
                                        wire:loading.attr="disabled"
                                        wire:target="addReply"
                                        class="yt-btn yt-btn--primary"
                                        x-data="{ commentId: {{ $comment->id }} }"
                                        x-init="
                                            $watch('$wire.replyContent', () => {
                                                const content = $wire.replyContent?.[commentId];
                                                const isEmpty = !content || !content.trim();
                                                
                                                if (isEmpty) {
                                                    $el.disabled = true;
                                                    $el.classList.add('opacity-60');
                                                } else {
                                                    $el.disabled = false;
                                                    $el.classList.remove('opacity-60');
                                                }
                                            }, { deep: true })
                                        "
                                        :disabled="!$wire.replyContent?.[{{ $comment->id }}] || !$wire.replyContent?.[{{ $comment->id }}].trim()"
                                    >
                                        <span wire:loading.remove wire:target="addReply">Ответить</span>
                                        <span wire:loading wire:target="addReply"><i class="fas fa-spinner fa-spin"></i> Отправка</span>
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                    @endif

                    {{-- ── Вложенные ответы (Replies List) ── --}}
                    @if($comment->replies->count() > 0)
                    <div x-data="{ open: false }">
                        <button @click="open = !open" class="yt-replies-toggle" :class="{ 'yt-replies-toggle--open': open }">
                            <i class="fas fa-chevron-down"></i>
                            <span x-text="open ? 'Скрыть ответы' : '{{ $comment->replies->count() }} ' + ({{ $comment->replies->count() }} === 1 ? 'ответ' : ({{ $comment->replies->count() }} < 5 ? 'ответа' : 'ответов'))"></span>
                        </button>

                        <div x-show="open" x-collapse>
                            @foreach($comment->replies as $reply)
                            <div class="yt-comment--reply" wire:key="reply-{{ $reply->id }}">
                                <a href="{{ route('user.info', $reply->user) }}" class="yt-comment__avatar-link">
                                    <div wire:ignore wire:key="avatar-reply-item-{{ $reply->user->id }}">
                                        <x-avatar :user="$reply->user" class="yt-avatar yt-avatar--sm" />
                                    </div>
                                </a>
                                <div class="yt-comment__body">
                                    <div class="yt-comment__header-wrapper">
                                        <div class="yt-comment__meta">
                                            <a href="{{ route('user.info', $reply->user) }}" class="yt-comment__author">
                                                <b>&#64;{{ $reply->user->name }}</b>
                                            </a>
                                            <time class="yt-comment__time" title="{{ $reply->created_at->format('d.m.Y H:i:s') }}">
                                                {{ $reply->formatted_date }}
                                            </time>
                                        </div>

                                        {{-- Меню с тремя точками для ответов --}}
                                        @canany(['update', 'delete'], $reply)
                                        <div class="yt-dropdown" x-data="{ open: false }" @click.away="open = false">
                                            <button @click="open = !open" class="yt-act yt-act--menu" title="Действия">
                                                <i class="fas fa-ellipsis-v"></i>
                                            </button>
                                            
                                            <div x-show="open" 
                                                x-transition:enter="transition ease-out duration-200"
                                                x-transition:enter-start="opacity-0 transform scale-95"
                                                x-transition:enter-end="opacity-100 transform scale-100"
                                                class="yt-dropdown-menu"
                                                @click.stop>
                                                @can('update', $reply)
                                                <button wire:click="startEdit({{ $reply->id }})" 
                                                        @click="open = false"
                                                        class="yt-dropdown-item">
                                                    <i class="fas fa-pen"></i>
                                                    <span>Редактировать</span>
                                                </button>
                                                @endcan
                                                
                                                @can('delete', $reply)
                                                <button @click="open = false; commentToDelete = {{ $reply->id }}; deleteModalOpen = true" 
                                                        class="yt-dropdown-item yt-dropdown-item--danger">
                                                    <i class="fas fa-trash"></i>
                                                    <span>Удалить</span>
                                                </button>
                                                @endcan
                                            </div>
                                        </div>
                                        @endcanany
                                    </div>

                                    @if($editingComment === $reply->id)
                                        <div class="yt-edit-form">
                                            <div
                                                contenteditable="plaintext-only"
                                                wire:ignore
                                                x-data="contentEditableEditor(@entangle('editContent'))"
                                                @input="update()"
                                                class="yt-compose__input"
                                                role="textbox"
                                                :class="{ 'empty': isEmpty }"
                                                data-placeholder="Редактировать ответ…"
                                                style="min-height: 32px; overflow: hidden; resize: none; white-space: pre-wrap; word-wrap: break-word;"
                                            ></div>
                                            <div class="flex items-center justify-between mt-2">
                                                <div
                                                    class="text-xs transition-colors duration-200"
                                                    :class="{
                                                        'text-gray-400': ($wire.editContent?.length || 0) < 4500,
                                                        'text-yellow-500': ($wire.editContent?.length || 0) >= 4500,
                                                        'text-red-500 font-semibold': ($wire.editContent?.length || 0) >= 4900
                                                    }"
                                                >
                                                    <span x-text="$wire.editContent?.length || 0"></span>/5000
                                                </div>

                                                @error('editContent')
                                                    <p class="yt-error">{{ $message }}</p>
                                                @enderror
                                            </div>

                                            <div class="yt-compose__btns" style="display:flex">
                                                <button wire:click="cancelEdit" class="yt-btn yt-btn--text">Отмена</button>
                                                <button
                                                    wire:click="updateComment({{ $reply->id }})"
                                                    wire:loading.attr="disabled"
                                                    wire:target="updateComment"
                                                    class="yt-btn yt-btn--primary"
                                                    :disabled="!$wire.editContent || !$wire.editContent.trim()"
                                                    :class="{
                                                        'opacity-60 cursor-not-allowed pointer-events-none':
                                                        !$wire.editContent || !$wire.editContent.trim()
                                                    }"
                                                >
                                                    <span wire:loading.remove wire:target="updateComment">
                                                        Сохранить
                                                    </span>
                                                    <span wire:loading wire:target="updateComment">
                                                        <i class="fas fa-spinner fa-spin"></i>
                                                    </span>
                                                </button>
                                            </div>
                                        </div>
                                    @else
                                        <div class="yt-comment__text">{!! nl2br(e($reply->content)) !!}</div>
                                        <div class="yt-comment__actions">
                                            @php $rrc = $reply->reactions_count ?? []; $rur = $reply->user_reaction ?? null; @endphp
                                            <button wire:click="toggleReaction({{ $reply->id }}, 'like')" class="yt-act {{ $rur === 'like' ? 'yt-act--active' : '' }}">
                                                <i class="{{ $rur === 'like' ? 'fas' : 'far' }} fa-thumbs-up"></i>
                                                @if(($rrc['like'] ?? 0) > 0)<span>{{ $rrc['like'] }}</span>@endif
                                            </button>
                                            <button wire:click="toggleReaction({{ $reply->id }}, 'dislike')" class="yt-act {{ $rur === 'dislike' ? 'yt-act--active' : '' }}" style="margin-right:4px">
                                                <i class="{{ $rur === 'dislike' ? 'fas' : 'far' }} fa-thumbs-down"></i>
                                                @if(($rrc['dislike'] ?? 0) > 0)<span>{{ $rrc['dislike'] }}</span>@endif
                                            </button>
                                        </div>
                                    @endif
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    @endif
                @endif
            </div>
        </div>
        @empty
        <div class="yt-empty">
            <i class="fas fa-comment-slash" style="font-size:48px;color:var(--yt-text3);display:block;margin-bottom:12px"></i>
            <p style="color:var(--yt-text3);font-size:14px">Комментариев пока нет. Начните обсуждение!</p>
        </div>
        @endforelse
    </div>

    {{-- Infinite Scroll --}}
    @if($hasMorePages)
    <div x-data="{
        init() {
            this.observer = new IntersectionObserver((entries) => {
                if (entries[0].isIntersecting && !$wire.loadingMore) {
                    $wire.call('loadMore');
                }
            }, { threshold: 0.1 });
            this.observer.observe(this.$el);
        },
        destroy() { if (this.observer) this.observer.disconnect(); }
    }" class="yt-loading-trigger">
        <div wire:loading wire:target="loadMore" class="yt-loading-spinner">
            <i class="fas fa-spinner fa-spin"></i>
            <span>Загрузка комментариев...</span>
        </div>
        <div wire:loading.remove wire:target="loadMore" class="yt-loading-placeholder"></div>
    </div>
    @endif
</div>

@push('scripts')
<style>
/* CSS Стили */

/* CSS Стили */

/* Header wrapper для размещения метаданных и точек на одной строке */
.yt-comment__header-wrapper {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 12px;
    margin-bottom: 4px;
    width: 100%;
}

.yt-comment__meta {
    display: flex;
    align-items: baseline;
    flex-wrap: wrap;
    gap: 8px;
    flex: 1;
}

/* На экранах меньше 370px убираем gap и принудительно переносим на новую строку */
@media (max-width: 709px) {
    .yt-comment__meta {
        gap: 0px;
        flex-direction: column;
    }
}

.yt-comment__badge {
    display: inline-flex;
    align-items: center;
    gap: 4px;
    font-size: 11px;
    padding: 2px 8px;
    background: var(--yt-blue-bg);
    color: var(--yt-blue);
    border-radius: 12px;
    margin-left: 4px;
}

.yt-comment__badge i {
    font-size: 10px;
}

.yt-act--menu { 
    padding: 4px 8px;
    background: none !important;
    margin-top: -4px;
    margin-bottom: -4px;
}

.yt-act--menu i {
    font-size: 16px;
    font-weight: 900;
}

.yt-act--menu:hover {
    background: none !important;
    box-shadow: none !important;
}

.yt-act--menu:focus {
    outline: none !important;
    background: none !important;
}

.yt-act--menu:active {
    background: none !important;
}

/* Сортировка - простой и надежный вариант */
.yt-sort-dropdown {
    position: relative;
    margin-left: auto;
    flex-shrink: 0;
}

.yt-comments__header {
    display: flex;
    align-items: center;
    gap: 16px;
    margin-bottom: 1.5rem;
    padding: 0 24px;
    flex-wrap: wrap;
}

.yt-comments__count {
    font-size: 16px;
    font-weight: 400;
    color: var(--yt-text);
    flex-shrink: 0;
}

/* На мобильных показываем только иконку */
@media (max-width: 640px) {
    .yt-sort-label {
        display: none;
    }
    
    .yt-sort-dropdown button {
        padding: 8px 12px;
        min-width: auto;
    }
    
    .yt-sort-dropdown {
        margin-left: auto;
        width: auto;
    }
    
    .yt-sort-icon {
        margin-left: 0 !important;
    }
}

/* Темная тема (по умолчанию) */
.dark .yt-comments {
    border: 1px solid var(--yt-border);
    --yt-bg: #111827;
    --yt-bg2: #1f1f1f;
    --yt-bg3: #272727;
    --yt-text: #f1f1f1;
    --yt-text2: #aaaaaa;
    --yt-text3: #8a8a8a;
    --yt-blue: #4895f9;
    --yt-blue-bg: rgba(62, 166, 255, 0.1);
    --yt-hover: rgba(255, 255, 255, 0.1);
    --yt-danger: #ff4e45;
    --yt-border: rgba(255, 255, 255, 0.1);
    --yt-radius: 18px;
    background: var(--yt-bg);
    color: var(--yt-text);
    font-family: "Roboto", Arial, sans-serif;
    font-size: 14px;
    padding: 2rem 0;
    border-radius: 12px;
}

/* Светлая тема */
.yt-comments {
    border: 1px solid var(--yt-border);
    --yt-bg: #F3F4F6;
    --yt-bg2: #ffffff;
    --yt-bg3: #f8f9fa;
    --yt-text: #0f0f0f;
    --yt-text2: #606060;
    --yt-text3: #6e6e6e;
    --yt-blue: #0860d3;
    --yt-blue-bg: rgba(6, 95, 212, 0.1);
    --yt-hover: rgba(0, 0, 0, 0.05);
    --yt-danger: #cc0000;
    --yt-border: rgba(0, 0, 0, 0.1);
    --yt-radius: 18px;
    background: var(--yt-bg);
    color: var(--yt-text);
    font-family: "Roboto", Arial, sans-serif;
    font-size: 14px;
    padding: 2rem 0;
    border-radius: 12px;
}

/* Стили для светлой темы - кнопки */
.yt-comments .yt-btn--text {
    color: var(--yt-text2);
}

.yt-comments .yt-btn--text:hover {
    background: var(--yt-hover);
    color: var(--yt-text);
}

.yt-comments .yt-btn--primary {
    background: var(--yt-blue);
    color: #ffffff;
}

.yt-comments .yt-btn--primary:hover {
    background: #0c6fd4;
}

/* Стили для светлой темы - комментарии */
.yt-comments .yt-comment__time {
    color: var(--yt-text3);
}

.yt-comments .yt-comment__badge {
    background: var(--yt-blue-bg);
    color: var(--yt-blue);
}

/* Стили для светлой темы - действия */
.yt-comments .yt-act {
    color: var(--yt-text2);
}

.yt-comments .yt-act:hover {
    background: var(--yt-hover);
    color: var(--yt-text);
}

.yt-comments .yt-act--active {
    color: var(--yt-blue) !important;
}

/* Стили для светлой темы - поле ввода */
.yt-comments .yt-compose__input {
    border-bottom-color: var(--yt-border);
    color: var(--yt-text);
}

.yt-comments .yt-compose__input:focus {
    border-bottom-color: var(--yt-text);
}

.yt-comments .yt-compose__input.empty::before {
    color: var(--yt-text3);
}

/* Стили для светлой темы - выпадающие меню */
.yt-comments .yt-dropdown-menu {
    background: var(--yt-bg2);
    border-color: var(--yt-border);
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
}

.yt-comments .yt-dropdown-item {
    color: var(--yt-text);
}

.yt-comments .yt-dropdown-item i {
    color: var(--yt-text2);
}

.yt-comments .yt-dropdown-item:hover {
    background: var(--yt-hover);
}

.yt-comments .yt-dropdown-item--danger {
    color: var(--yt-danger);
}

.yt-comments .yt-dropdown-item--danger i {
    color: var(--yt-danger);
}

.yt-comments .yt-dropdown-item--danger:hover {
    background: rgba(204, 0, 0, 0.1);
}

.yt-comments .yt-dropdown-item--active {
    background: var(--yt-blue-bg);
    color: var(--yt-blue);
}

.yt-comments .yt-dropdown-item--active i {
    color: var(--yt-blue);
}

/* Стили для светлой темы - переключатель ответов */
.yt-comments .yt-replies-toggle {
    color: var(--yt-blue);
}

.yt-comments .yt-replies-toggle:hover {
    background: var(--yt-blue-bg);
}

/* Стили для светлой темы - модальное окно */
.yt-comments .yt-modal {
    background: var(--yt-bg2);
    box-shadow: 0 8px 32px rgba(0, 0, 0, 0.2);
}

/* Стили для светлой темы - загрузка */
.yt-comments .yt-loading-spinner {
    background: var(--yt-bg3);
    color: var(--yt-text2);
}

.yt-comments .yt-loading-spinner i {
    color: var(--yt-blue);
}

/* Стили для светлой темы - пустое состояние */
.yt-comments .yt-empty i {
    color: var(--yt-text3);
}

.yt-comments .yt-empty p {
    color: var(--yt-text3);
}

/* Стили для светлой темы - приглашение авторизации */
.yt-comments .yt-auth-prompt {
    border-color: var(--yt-border);
    color: var(--yt-text3);
}

/* Стили для светлой темы - ссылки */
.yt-comments .yt-link {
    color: var(--yt-blue);
}

/* Стили для светлой темы - ошибки */
.yt-comments .yt-error {
    color: var(--yt-danger);
}

/* Стили для светлой темы - разделители */
.yt-comments .yt-dropdown-divider {
    background: var(--yt-border);
}

/* Общие стили, которые не зависят от темы */
.yt-comments__header { display: flex; align-items: center; gap: 16px; margin-bottom: 1.5rem; padding: 0 24px; }
.yt-comments__count { font-size: 16px; font-weight: 400; color: var(--yt-text); }
.yt-avatar { border-radius: 50% !important; }
.yt-avatar--sm { width: 28px !important; height: 28px !important; }
.yt-compose { display: flex; gap: 16px; align-items: flex-start; padding: 0 24px; margin-bottom: 2rem; }
.yt-compose__field { flex: 1; min-width: 0; }
.yt-compose__input {
    width: 100%; background: transparent; border: none; border-bottom: 1px solid var(--yt-border); color: var(--yt-text);
    font-size: 14px; font-family: inherit; padding: 4px 0 8px; outline: none; transition: border-color .2s;
    caret-color: var(--yt-blue); box-shadow: none; border-radius: 0; position: relative;
}
.yt-compose__input:focus { border-bottom-color: var(--yt-text); }
.yt-compose__btns { display: flex; justify-content: flex-end; align-items: center; gap: 8px; margin-top: 12px; }
[x-cloak] { display: none !important; }
.yt-auth-prompt { text-align: center; padding: 2rem; color: var(--yt-text3); font-size: 14px; margin: 0 24px 2rem; border: 1px solid var(--yt-border); border-radius: 8px; }
.yt-btn { display: inline-flex; align-items: center; gap: 6px; padding: 10px 16px; border-radius: var(--yt-radius); font-size: 14px; font-weight: 500; cursor: pointer; border: none; font-family: inherit; transition: background .15s, color .15s; white-space: nowrap; line-height: 1; }
.yt-btn--reply { font-size: 13px; font-weight: 500; color: var(--yt-text2); padding: 8px 12px; }
.yt-comment {
    display: flex;
    gap: 16px;
    padding: 12px 24px;
    animation: fadeInUp 0.3s ease-out;
}
.yt-comment--reply {
    display: flex;
    gap: 16px;
    padding-top: 8px;
    padding-bottom: 8px;
}
.yt-comment__avatar-link { flex-shrink: 0; border-radius: 50%; transition: opacity .15s; }
.yt-comment__avatar-link:hover { opacity: .85; }
.yt-comment__body { flex: 1; min-width: 0; }
.yt-comment__text { font-size: 14px; line-height: 1.65; color: var(--yt-text); margin-bottom: 2px; word-break: break-word; }
.yt-comment__actions { display: flex; align-items: center; gap: 0; }
.yt-act { display: inline-flex; align-items: center; gap: 6px; padding: 8px 10px; border-radius: var(--yt-radius); font-size: 13px; cursor: pointer; background: none; border: none; color: var(--yt-text2); font-family: inherit; transition: background .15s, color .15s; line-height: 1; }
.yt-replies-toggle { display: inline-flex; align-items: center; gap: 8px; color: var(--yt-blue); font-size: 14px; font-weight: 500; cursor: pointer; background: none; border: none; padding: 8px 12px; border-radius: var(--yt-radius); font-family: inherit; margin-top: 4px; transition: background .15s; }
.yt-replies-toggle i { font-size: 14px; transition: transform .2s; }
.yt-replies-toggle--open i { transform: rotate(180deg); }
.yt-reply-compose { display: flex; gap: 12px; align-items: flex-start; margin-top: 16px; }
.yt-edit-form { margin-bottom: 4px; }
.yt-empty { text-align: center; padding: 3rem 1rem; margin: 0 24px; }
.yt-error { font-size: 12px; color: var(--yt-danger); margin-top: 4px; }
.yt-link { color: var(--yt-blue); text-decoration: none; }
.yt-link:hover { text-decoration: underline; }
.yt-loading-trigger { min-height: 60px; display: flex; justify-content: center; align-items: center; margin-top: 1rem; }
.yt-loading-spinner { display: inline-flex; align-items: center; gap: 12px; padding: 12px 24px; background: var(--yt-bg3); border-radius: var(--yt-radius); color: var(--yt-text2); font-size: 14px; }
.yt-loading-spinner i { font-size: 18px; color: var(--yt-blue); }
.yt-loading-placeholder { height: 1px; width: 100%; opacity: 0; }

/* Убираем эффект "запрет" для неактивных кнопок */
.yt-btn--primary:disabled,
.yt-btn--primary.opacity-60,
button:disabled {
    opacity: 0.6;
    cursor: default !important;
    pointer-events: none;
}

.yt-btn--primary:disabled:hover,
.yt-btn--primary.opacity-60:hover {
    background: #3ea6ff;
    transform: none;
}

button:disabled {
    pointer-events: none;
}

/* Dropdown Menu Styles */
.yt-dropdown {
    position: relative;
    display: inline-block;
    flex-shrink: 0;
}

.yt-dropdown-menu {
    position: absolute;
    right: 0;
    top: 100%;
    margin-top: 4px;
    border-radius: 12px;
    min-width: 160px;
    z-index: 1000;
    overflow: hidden;
    border: 1px solid var(--yt-border);
}

.yt-dropdown-item {
    display: flex;
    align-items: center;
    gap: 12px;
    width: 100%;
    padding: 10px 16px;
    background: none;
    border: none;
    font-size: 14px;
    cursor: pointer;
    transition: background 0.15s;
    text-align: left;
    font-family: inherit;
}

.yt-dropdown-item i {
    width: 16px;
    font-size: 14px;
}

/* Modal styles */
.yt-modal {
    animation: modalFadeIn 0.2s ease-out;
}

@keyframes modalFadeIn {
    from {
        opacity: 0;
        transform: scale(0.95);
    }
    to {
        opacity: 1;
        transform: scale(1);
    }
}

@keyframes fadeInUp { 
    from { opacity: 0; transform: translateY(20px); } 
    to { opacity: 1; transform: translateY(0); } 
}

/* Динамический CSS плейсхолдер */
.yt-compose__input.empty::before {
    content: attr(data-placeholder);
    position: absolute;
    left: 0;
    top: 4px;
    pointer-events: none;
    display: block;
}
</style>

<script>
document.addEventListener('livewire:init', function () {
    // Основной редактор для комментариев
    Alpine.data('contentEditableEditor', (entangledModel) => ({
        content: entangledModel,
        isEmpty: true,
        init() {
            this.$el.innerText = this.content || '';
            this.checkEmpty();
            this.resize();

            this.$watch('content', value => {
                if (value === undefined || value === null || value === '') {
                    if (this.$el.innerText !== '') {
                        this.$el.innerText = '';
                    }
                }
                this.checkEmpty();
                this.resize();
            });
        },
        update() {
            this.content = this.$el.innerText;
            this.checkEmpty();
            this.resize();
        },
        checkEmpty() {
            this.isEmpty = !this.$el.innerText || this.$el.innerText.trim() === '';
        },
        resize() {
            this.$el.style.height = 'auto';
            this.$el.style.height = this.$el.scrollHeight + 'px';
        },
        clearContent() {
            this.$el.innerText = '';
            this.content = '';
            this.checkEmpty();
            this.resize();
        }
    }));

    // Специальный редактор для ответов с правильной синхронизацией
    Alpine.data('replyEditor', (entangledModel, commentId) => ({
        content: entangledModel,
        isEmpty: true,
        commentId: commentId,
        init() {
            this.$el.innerText = this.content || '';
            this.checkEmpty();
            this.resize();
            
            this.$watch('content', value => {
                if (value === undefined || value === null || value === '') {
                    if (this.$el.innerText !== '') {
                        this.$el.innerText = '';
                    }
                }
                this.checkEmpty();
                this.resize();
                
                // Принудительно обновляем Wire модель
                this.$wire.set(`replyContent.${this.commentId}`, value || '');
            });
        },
        update() {
            this.content = this.$el.innerText;
            this.checkEmpty();
            this.resize();
            
            // Убеждаемся, что Wire знает об изменении
            this.$wire.set(`replyContent.${this.commentId}`, this.content || '');
        },
        checkEmpty() {
            this.isEmpty = !this.$el.innerText || this.$el.innerText.trim() === '';
        },
        resize() {
            this.$el.style.height = 'auto';
            this.$el.style.height = this.$el.scrollHeight + 'px';
        }
    }));

    // Логика кэширования аватарок
    function updateNewAvatars() {
        setTimeout(function () {
            const avatars = document.querySelectorAll('img[data-user-id]:not([data-avatar-initialized])');
            avatars.forEach(img => {
                const userId = img.getAttribute('data-user-id');
                img.setAttribute('data-avatar-initialized', 'true');
                if (window.avatarCache && window.avatarCache.has(userId)) {
                    const cached = window.avatarCache.get(userId);
                    if (cached?.url && cached.url !== img.src) img.src = cached.url;
                }
                img.addEventListener('load', function () {
                    const src = img.src;
                    if (src && !src.includes('ui-avatars.com') && window.avatarCache) {
                        window.avatarCache.set(userId, {
                            url: src,
                            hash: img.getAttribute('data-avatar-hash'),
                            timestamp: Date.now()
                        });
                    }
                }, { once: true });
            });
        }, 50);
    }

    ['comment-added', 'reply-added', 'comment-updated', 'comment-deleted']
        .forEach(e => Livewire.on(e, updateNewAvatars));

    document.addEventListener('livewire:navigated', updateNewAvatars);
    updateNewAvatars();
});
</script>
@endpush
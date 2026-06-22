<?php
// app/Livewire/Comments.php

namespace App\Livewire;

use App\Models\Comment;
use App\Models\Note;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use App\Models\CommentReaction;

class Comments extends Component
{
    use WithPagination, AuthorizesRequests;

    public Note $note;
    public $content = '';
    public $replyContent = [];
    public $replyingTo = null;
    public $editingComment = null;
    public $editContent = '';
    
    // Добавляем свойство для отслеживания загрузки
    public $perPage = 10; // Начальное количество комментариев
    public $loadingMore = false; // Состояние загрузки
    
    // Свойство для сортировки
    public $sortBy = 'latest'; // Варианты: latest, oldest, most_replies, most_likes
    
    protected $rules = [
        'content' => 'required|string|max:5000',
        'replyContent.*' => 'required|string|max:5000',
        'editContent' => 'required|string|max:5000',
    ];

    protected $messages = [
        'content.required' => 'Пожалуйста, введите текст комментария.',
        'content.max' => 'Комментарий не может превышать 5000 символов.',
        'replyContent.*.required' => 'Пожалуйста, введите текст ответа.',
        'replyContent.*.max' => 'Ответ не может превышать 5000 символов.',
        'editContent.required' => 'Пожалуйста, введите текст комментария.',
        'editContent.max' => 'Комментарий не может превышать 5000 символов.',
    ];

    protected $listeners = [
        'refreshComments' => '$refresh',
        'load-more' => 'loadMore', // Слушаем событие загрузки
    ];

    public function mount(Note $note)
    {
        $this->note = $note;
    }
    
    // Метод для изменения сортировки
    public function setSortBy($sort)
    {
        $this->sortBy = $sort;
        $this->resetPage(); // Сбрасываем пагинацию при изменении сортировки
        $this->perPage = 10; // Сбрасываем количество комментариев
        $this->dispatch('sort-changed', $sort);
    }

    // Метод для загрузки дополнительных комментариев
    public function loadMore()
    {
        $this->loadingMore = true;
        $this->perPage += 10; // Увеличиваем количество комментариев на 10
        
        $this->loadingMore = false;
    }

    public function addComment()
    {
        $this->validateOnly('content');
        
        $this->authorize('create', Comment::class);

        $comment = Comment::create([
            'content' => $this->content,
            'user_id' => auth()->id(),
            'note_id' => $this->note->id,
        ]);

        $this->reset('content');
        $this->resetPage();
        
        $comment->load('user');
        $this->dispatch('comment-added');
    }

    public function addReply($commentId)
    {
        $this->validate([
            "replyContent.{$commentId}" => 'required|string|max:5000'
        ]);

        $this->authorize('create', Comment::class);

        $reply = Comment::create([
            'content' => $this->replyContent[$commentId],
            'user_id' => auth()->id(),
            'note_id' => $this->note->id,
            'parent_id' => $commentId,
        ]);

        unset($this->replyContent[$commentId]);
        $this->replyingTo = null;
        
        $reply->load('user');
        $this->dispatch('reply-added');
    }

    public function startReply($commentId)
    {
        $this->replyingTo = $this->replyingTo === $commentId ? null : $commentId;
    }

    public function startEdit($commentId)
    {
        $comment = Comment::findOrFail($commentId);
        $this->authorize('update', $comment);
        
        $this->editingComment = $commentId;
        $this->editContent = $comment->content;
    }

    public function cancelEdit()
    {
        $this->editingComment = null;
        $this->editContent = '';
    }

    public function updateComment($commentId)
    {
        $this->validateOnly('editContent');
        
        $comment = Comment::findOrFail($commentId);
        $this->authorize('update', $comment);
        
        $comment->update([
            'content' => $this->editContent,
        ]);

        $this->editingComment = null;
        $this->editContent = '';
        
        $this->dispatch('comment-updated');
        $this->render();
    }

    public function deleteComment($commentId)
    {
        $comment = Comment::findOrFail($commentId);
        $this->authorize('delete', $comment);
        
        $comment->delete();
        
        $this->dispatch('comment-deleted');
    }

    public function render()
    {
        // Базовый запрос
        $query = $this->note->comments()->with(['user', 'replies.user', 'reactions']);
        
        // Применяем сортировку
        switch ($this->sortBy) {
            case 'oldest':
                $query->oldest();
                break;
            case 'most_replies':
                $query->withCount('replies')->orderBy('replies_count', 'desc');
                break;
            case 'most_likes':
                $query->withCount(['reactions as likes_count' => function ($query) {
                    $query->where('reaction', 'like');
                }])->orderBy('likes_count', 'desc');
                break;
            case 'latest':
            default:
                $query->latest();
                break;
        }
        
        // Используем perPage для пагинации
        $comments = $query->paginate($this->perPage);
        
        // Загружаем реакции для каждого комментария и ответа
        if (auth()->check()) {
            foreach ($comments as $comment) {
                $reactions = $comment->reactions;
                $comment->reactions_count = $reactions->groupBy('reaction')->map->count()->toArray();
                $comment->user_reaction = $reactions->where('user_id', auth()->id())->first()?->reaction;
                
                foreach ($comment->replies as $reply) {
                    $replyReactions = $reply->reactions;
                    $reply->reactions_count = $replyReactions->groupBy('reaction')->map->count()->toArray();
                    $reply->user_reaction = $replyReactions->where('user_id', auth()->id())->first()?->reaction;
                }
            }
        } else {
            foreach ($comments as $comment) {
                $comment->reactions_count = $comment->reactions->groupBy('reaction')->map->count()->toArray();
                $comment->user_reaction = null;
                
                foreach ($comment->replies as $reply) {
                    $reply->reactions_count = $reply->reactions->groupBy('reaction')->map->count()->toArray();
                    $reply->user_reaction = null;
                }
            }
        }

        return view('livewire.comments', [
            'comments' => $comments,
            'hasMorePages' => $comments->hasMorePages(),
            'currentSort' => $this->sortBy,
        ]);
    }

    public function toggleReaction($commentId, $reaction)
    {
        if (!auth()->check()) {
            return;
        }
        
        $comment = Comment::findOrFail($commentId);
        
        $existingReaction = CommentReaction::where('comment_id', $commentId)
            ->where('user_id', auth()->id())
            ->first();
        
        if ($existingReaction && $existingReaction->reaction === $reaction) {
            $existingReaction->delete();
        } else {
            CommentReaction::updateOrCreate(
                ['comment_id' => $commentId, 'user_id' => auth()->id()],
                ['reaction' => $reaction]
            );
        }
        
        $this->dispatch('reaction-updated');
    }
}
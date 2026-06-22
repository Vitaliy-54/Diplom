<?php

namespace App\Http\Controllers;

use App\Models\Note;
use App\Models\NoteReaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NoteReactionController extends Controller
{
    public function getReactions(Note $note, string $reaction)
{
    $reactions = NoteReaction::where('note_id', $note->id)
        ->where('reaction', $reaction)
        ->with('user')
        ->orderBy('created_at', 'desc')
        ->get()
        ->map(function($item) {
            return [
                'name' => $item->user->name,
                'created_at' => $item->created_at->format('Y-m-d H:i:s')
            ];
        });
    
    return response()->json([
        'users' => $reactions
    ]);
}

    public function index(Note $note)
    {
        // Получаем количество каждой реакции
        $reactions = NoteReaction::where('note_id', $note->id)
            ->selectRaw('reaction, count(*) as count')
            ->groupBy('reaction')
            ->pluck('count', 'reaction')
            ->toArray();
    
        // Инициализируем все возможные реакции с нулевыми значениями
        $allReactions = [
            'like' => 0,
            'dislike' => 0,
            'heart' => 0,
            'laugh' => 0,
            'wow' => 0
        ];
    
        // Объединяем с фактическими данными из БД
        $reactions = array_merge($allReactions, $reactions);
    
        // Получаем реакцию текущего пользователя (если авторизован)
        $userReaction = Auth::check() 
            ? NoteReaction::where('note_id', $note->id)
                ->where('user_id', Auth::id())
                ->value('reaction')
            : null;
    
        return response()->json([
            'reactions' => $reactions,
            'userReaction' => $userReaction
        ]);
    }

    public function store(Request $request, Note $note)
    {
        $request->validate([
            'reaction' => 'required|in:like,dislike,heart,laugh,wow',
            'remove' => 'boolean'
        ]);
    
        if (!Auth::check()) {
            return response()->json(['message' => 'Требуется авторизация'], 401);
        }
    
        $userReaction = NoteReaction::firstOrNew([
            'note_id' => $note->id,
            'user_id' => Auth::id()
        ]);
    
        if ($request->remove) {
            if ($userReaction->exists) {
                $userReaction->delete();
            }
        } else {
            $userReaction->reaction = $request->reaction;
            $userReaction->save();
        }
    
        // Получаем обновленные данные о реакциях
        $reactions = NoteReaction::where('note_id', $note->id)
            ->selectRaw('reaction, count(*) as count')
            ->groupBy('reaction')
            ->pluck('count', 'reaction')
            ->toArray();
    
        $allReactions = [
            'like' => 0,
            'dislike' => 0,
            'heart' => 0,
            'laugh' => 0,
            'wow' => 0
        ];
    
        $reactions = array_merge($allReactions, $reactions);
    
        // Получаем реакцию текущего пользователя
        $currentUserReaction = NoteReaction::where('note_id', $note->id)
            ->where('user_id', Auth::id())
            ->value('reaction');
    
        // Получаем пользователей для каждой реакции с датами
        $reactionsUsers = [
            'like' => $note->reactions()
                ->where('reaction', 'like')
                ->with('user')
                ->orderBy('created_at', 'desc')
                ->get()
                ->map(function($item) {
                    return [
                        'user' => $item->user->name,
                        'created_at' => $item->created_at
                    ];
                }),
            'dislike' => $note->reactions()
                ->where('reaction', 'dislike')
                ->with('user')
                ->orderBy('created_at', 'desc')
                ->get()
                ->map(function($item) {
                    return [
                        'user' => $item->user->name,
                        'created_at' => $item->created_at
                    ];
                }),
            'heart' => $note->reactions()
                ->where('reaction', 'heart')
                ->with('user')
                ->orderBy('created_at', 'desc')
                ->get()
                ->map(function($item) {
                    return [
                        'user' => $item->user->name,
                        'created_at' => $item->created_at
                    ];
                }),
            'laugh' => $note->reactions()
                ->where('reaction', 'laugh')
                ->with('user')
                ->orderBy('created_at', 'desc')
                ->get()
                ->map(function($item) {
                    return [
                        'user' => $item->user->name,
                        'created_at' => $item->created_at
                    ];
                }),
            'wow' => $note->reactions()
                ->where('reaction', 'wow')
                ->with('user')
                ->orderBy('created_at', 'desc')
                ->get()
                ->map(function($item) {
                    return [
                        'user' => $item->user->name,
                        'created_at' => $item->created_at
                    ];
                })
        ];
    
        return response()->json([
            'reactions' => $reactions,
            'userReaction' => $currentUserReaction,
            'reactionsUsers' => $reactionsUsers
        ]);
    }
}
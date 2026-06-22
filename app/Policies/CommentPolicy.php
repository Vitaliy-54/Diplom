<?php
// app/Policies/CommentPolicy.php

namespace App\Policies;

use App\Models\User;
use App\Models\Comment;

class CommentPolicy
{
    /**
     * Определить, может ли пользователь создать комментарий.
     */
    public function create(User $user): bool
    {
        return $user !== null && $user->exists;
    }

    /**
     * Определить, может ли пользователь обновить комментарий.
     */
    public function update(User $user, Comment $comment): bool
    {
        return $user->id === $comment->user_id || $user->isAdmin();
    }

    /**
     * Определить, может ли пользователь удалить комментарий.
     */
    public function delete(User $user, Comment $comment): bool
    {
        return $user->id === $comment->user_id || $user->isAdmin();
    }

    /**
     * Определить, может ли пользователь просматривать список комментариев.
     */
    public function viewAny(User $user): bool
    {
        return true; // Все могут просматривать комментарии
    }

    /**
     * Определить, может ли пользователь просматривать конкретный комментарий.
     */
    public function view(User $user, Comment $comment): bool
    {
        return true; // Все могут просматривать комментарии
    }
}
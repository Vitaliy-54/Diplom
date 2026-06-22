<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\NoteReaction;

class Note extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'is_public',
        'user_id',
    ];

    // Добавляем booted метод для каскадного удаления
    protected static function booted()
    {
        static::deleting(function ($note) {
            // Удаляем связи с тегами
            $note->tags()->detach();
            
            // Удаляем реакции
            $note->reactions()->delete();
            
            // Удаляем комментарии
            $note->comments()->delete();
            
            // Удаляем файлы
            $note->files()->delete();
        });
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function tags()
    {
        return $this->belongsToMany(Tag::class);
    }

    public function reactions()
    {
        return $this->hasMany(NoteReaction::class);
    }

    // Scope для поиска по заголовку
    public function scopeSearch($query, $search)
    {
        return $query->where('title', 'like', '%'.$search.'%');
    }

    // Scope для фильтрации по тегам
    public function scopeWithTags($query, $tags)
    {
        return $query->whereHas('tags', function($q) use ($tags) {
            $q->whereIn('id', $tags);
        });
    }

    public function comments()
    {
        return $this->hasMany(Comment::class)->whereNull('parent_id');
    }

    public function files()
    {
        return $this->hasMany(NoteFile::class);
    }
}
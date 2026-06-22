<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    use HasFactory;

    // Поля, которые можно массово назначать
    protected $fillable = [
        'user_id',
        'title',
        'description',
        'completed',
        'due_date',
        'category',
    ];

    // Связь с пользователем
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
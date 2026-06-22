<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserStatistic extends Model
{
    protected $table = 'user_statistic'; // Явно указываем таблицу

    protected $fillable = [
        'user_id',
        'page',
        'ip_address',
        'last_activity_at',
    ];

    public $timestamps = false;

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
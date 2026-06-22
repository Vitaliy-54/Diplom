<?php
// app/Models/Nd3CalculationHistory.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class Nd3CalculationHistory extends Model
{
    use HasFactory;

    protected $table = 'nd3_calculation_history';

    protected $fillable = [
        'user_id',
        'name',
        'input_data',
        'results',
        'optimization_results',
        'optimized_input_data',
        'experimental_data',
        'is_favorite'
    ];

    protected $casts = [
        'input_data' => 'array',
        'results' => 'array',
        'optimization_results' => 'array',
        'optimized_input_data' => 'array',
        'experimental_data' => 'array',
        'is_favorite' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function scopeFavorites($query)
    {
        return $query->where('is_favorite', true);
    }

    public function scopeByUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    public function toggleFavorite(): void
    {
        $this->is_favorite = !$this->is_favorite;
        $this->save();
    }
    
    /**
     * Проверка, является ли расчёт оптимизированным
     */
    public function isOptimized(): bool
    {
        return !is_null($this->optimized_input_data) && !is_null($this->optimization_results);
    }
    
    /**
     * Получение отображаемых входных данных (оптимизированных или исходных)
     */
    public function getDisplayInputData(): array
    {
        return $this->optimized_input_data ?? $this->input_data;
    }
    
    /**
     * Полиморфная связь с таблицей публичных ссылок
     * Одно вычисление может иметь множество ссылок
     */
    public function shareLinks(): MorphMany
    {
        return $this->morphMany(CalculationShareLink::class, 'calculable');
    }
    
    /**
     * Активные публичные ссылки (не истекшие и не отозванные)
     */
    public function activeShareLinks(): MorphMany
    {
        return $this->shareLinks()
            ->where('is_active', true)
            ->where(function($query) {
                $query->whereNull('expires_at')
                      ->orWhere('expires_at', '>', now());
            });
    }
    
    /**
     * Проверка наличия активных публичных ссылок
     */
    public function hasActiveShareLinks(): bool
    {
        return $this->activeShareLinks()->exists();
    }
    
    /**
     * Получить суммарное количество просмотров по всем ссылкам
     */
    public function getTotalShareViewsAttribute(): int
    {
        return $this->shareLinks()->sum('views');
    }
    
    /**
     * Получить последнюю созданную активную ссылку
     */
    public function getLatestShareLinkAttribute()
    {
        return $this->activeShareLinks()->latest()->first();
    }
}
<?php
// app/Models/CalculationShareLink.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class CalculationShareLink extends Model
{
    use SoftDeletes;
    
    protected $table = 'calculation_share_links';
    
    protected $fillable = [
        'calculable_type',
        'calculable_id',
        'created_by',
        'token',
        'title',
        'description',
        'is_active',
        'expires_at',
        'last_accessed_at',
        'views',
        'unique_views',
        'access_log',
        'password_hash',
        'allow_comments',
        'allow_download',
        'allow_copy_to_account'
    ];
    
    protected $casts = [
        'is_active' => 'boolean',
        'expires_at' => 'datetime',
        'last_accessed_at' => 'datetime',
        'access_log' => 'array',
        'allow_comments' => 'boolean',
        'allow_download' => 'boolean',
        'allow_copy_to_account' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];
    
    /**
     * Полиморфная связь с вычислением
     */
    public function calculable()
    {
        return $this->morphTo();
    }
    
    /**
     * Пользователь, создавший ссылку
     */
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
    
    /**
     * Создать новую публичную ссылку
     */
    public static function createShareLink($calculable, $options = [])
    {
        $defaults = [
            'title' => null,
            'description' => null,
            'expires_in_days' => 30,
            'password' => null,
            'allow_comments' => true,
            'allow_download' => true,
            'allow_copy_to_account' => true
        ];
        
        $options = array_merge($defaults, $options);
        
        // Генерируем уникальный токен
        do {
            $token = Str::random(32);
        } while (self::where('token', $token)->exists());
        
        $expiresAt = null;
        if ($options['expires_in_days'] !== null) {
            $expiresAt = now()->addDays((int)$options['expires_in_days']);
        }
        
        $linkData = [
            'calculable_type' => get_class($calculable),
            'calculable_id' => $calculable->id,
            'created_by' => Auth::id(),
            'token' => $token,
            'title' => $options['title'],
            'description' => $options['description'],
            'is_active' => true,
            'expires_at' => $expiresAt,
            'allow_comments' => $options['allow_comments'],
            'allow_download' => $options['allow_download'],
            'allow_copy_to_account' => $options['allow_copy_to_account'],
            'access_log' => []
        ];
        
        // Если установлен пароль
        if ($options['password']) {
            $linkData['password_hash'] = Hash::make($options['password']);
        }
        
        return self::create($linkData);
    }
    
    /**
     * Проверить, активна ли ссылка
     */
    public function isAccessible()
    {
        if (!$this->is_active) {
            return false;
        }
        
        if ($this->expires_at && $this->expires_at->isPast()) {
            return false;
        }
        
        return true;
    }
    
    /**
     * Проверить пароль
     */
    public function checkPassword($password)
    {
        if (!$this->password_hash) {
            return true;
        }
        
        return Hash::check($password, $this->password_hash);
    }
    
    /**
     * Зарегистрировать доступ к ссылке
     */
    public function registerAccess($request, $isUnique = true)
    {
        $this->increment('views');
        
        // Логируем уникальные просмотры
        if ($isUnique) {
            $this->increment('unique_views');
        }
        
        $this->last_accessed_at = now();
        
        // Логируем доступ (последние 100 записей)
        $logEntry = [
            'accessed_at' => now()->toIso8601String(),
            'ip' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'user_id' => Auth::id(),
            'referer' => $request->header('referer')
        ];
        
        $logs = $this->access_log ?? [];
        array_unshift($logs, $logEntry);
        
        // Оставляем только последние 100 записей
        if (count($logs) > 100) {
            $logs = array_slice($logs, 0, 100);
        }
        
        $this->access_log = $logs;
        $this->saveQuietly(); // Сохраняем без триггеров событий
        
        return $this;
    }
    
    /**
     * Получить полный URL для публичного доступа
     */
    public function getUrlAttribute()
    {
        return route('public.calculation.show', ['token' => $this->token]);
    }
    
    /**
     * Получить название вычисления (для отображения)
     */
    public function getDisplayTitleAttribute()
    {
        return $this->title ?? $this->calculable->name ?? 'Untitled Calculation';
    }
    
    /**
     * Отозвать ссылку (деактивировать)
     */
    public function revoke()
    {
        $this->is_active = false;
        $this->save();
    }
    
    /**
     * Активировать ссылку снова
     */
    public function activate()
    {
        $this->is_active = true;
        $this->save();
    }
    
    /**
     * Продлить срок действия ссылки
     */
    public function extendExpiration($additionalDays = 30)
    {
        if ($this->expires_at) {
            $this->expires_at = $this->expires_at->addDays($additionalDays);
        } else {
            $this->expires_at = now()->addDays($additionalDays);
        }
        $this->save();
    }
    
    /**
     * Получить все ссылки для вычисления
     */
    public static function getLinksForCalculation($calculable)
    {
        return self::where('calculable_type', get_class($calculable))
            ->where('calculable_id', $calculable->id)
            ->orderBy('created_at', 'desc')
            ->get();
    }
    
    /**
     * Получить активные ссылки для вычисления
     */
    public static function getActiveLinksForCalculation($calculable)
    {
        return self::where('calculable_type', get_class($calculable))
            ->where('calculable_id', $calculable->id)
            ->where('is_active', true)
            ->where(function($query) {
                $query->whereNull('expires_at')
                      ->orWhere('expires_at', '>', now());
            })
            ->orderBy('created_at', 'desc')
            ->get();
    }
}
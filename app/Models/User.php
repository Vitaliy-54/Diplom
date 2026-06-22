<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;
use App\Notifications\CustomVerifyEmail;
use App\Notifications\CustomPasswordReset;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Send the email verification notification.
     */
    public function sendEmailVerificationNotification()
    {
        $this->notify(new CustomVerifyEmail());
    }

    /**
     * Send the password reset notification.
     *
     * @param  string  $token
     */
    public function sendPasswordResetNotification($token)
    {
        $this->notify(new CustomPasswordReset($token));
    }

    public function isAdmin()
    {
        return $this->role === 'admin';
    }

    public function lastSession()
    {
        return $this->hasOne(Session::class)->latestOfMany();
    }

    public function logs()
    {
        return $this->hasMany(UserLog::class);
    }

    // Кэширование аватарок
    public function getAvatarCacheKey(): string
    {
        return "user_avatar_{$this->id}";
    }

    public function getCachedAvatarUrl(): ?string
    {
        return Cache::remember($this->getAvatarCacheKey(), now()->addHours(24), function () {
            $avatarDir = "avatars/{$this->id}";
            $avatarFile = collect(Storage::files($avatarDir))
                ->first(fn($f) => preg_match('/^avatars\/' . $this->id . '\/avatar\.(jpg|jpeg|png|svg|gif)$/i', $f));
            
            if ($avatarFile) {
                $timestamp = filemtime(Storage::path($avatarFile));
                return route('avatar.serve', ['user' => $this->id, 'filename' => basename($avatarFile)]) . "?v={$timestamp}";
            }
            
            return null;
        });
    }

    public function clearAvatarCache(): void
    {
        Cache::forget($this->getAvatarCacheKey());
    }

    public function statistics()
    {
        return $this->hasMany(UserStatistic::class);
    }

    public function tasks()
    {
        return $this->hasMany(Task::class);
    }

    public function notes()
    {
        return $this->hasMany(Note::class);
    }

    public function files()
    {
        return $this->hasManyThrough(NoteFile::class, Note::class);
    }

    public function getTotalStorageUsed()
    {
        return NoteFile::whereHas('note', function($query) {
            $query->where('user_id', $this->id);
        })->sum('size');
    }

    public function getTotalFileSizeAttribute()
    {
        return $this->notes()->with('files')->get()->flatMap->files->sum('size');
    }
}
<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Request;
use App\Models\UserLog;
use App\Models\UserStatistic;
use Carbon\Carbon;

class TrackUserActivity
{
    public function handle($request, Closure $next)
    {
        if (Auth::check()) {
            $user = Auth::user();
            $now = Carbon::now();

            // Обновление или создание записи в user_logs
            UserLog::updateOrCreate(
                ['user_id' => $user->id],
                ['last_activity_at' => $now]
            );

            // Добавление записи в user_statistics
            UserStatistic::create([
                'user_id'        => $user->id,
                'page' => $request->fullUrl(),
                'ip_address'     => $request->ip(),
                'last_activity_at' => $now,
            ]);
        }

        return $next($request);
    }
}
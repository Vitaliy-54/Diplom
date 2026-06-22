<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Http\Controllers\NoteController;

class CheckUserStorage
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next)
{
    $user = $request->user();
    
    if ($user && $user->getTotalStorageUsed() >= NoteController::MAX_USER_STORAGE) {
        return back()->with('error', 'Вы достигли лимита хранилища (150МБ). Удалите некоторые файлы, чтобы загрузить новые.');
    }
    
    return $next($request);
}
}

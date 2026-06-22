<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\UserLog;

class LogoutController extends Controller
{
    public function logout(Request $request)
    {
        // Получаем текущего пользователя
        $user = Auth::user();

        // Если пользователь авторизован, записываем время выхода
        if ($user) {
            UserLog::create([
                'user_id' => $user->id,
                'last_logout_at' => Carbon::now(),
            ]);
        }

        // Выполняем выход
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        // Перенаправляем на главную страницу
        return redirect('/');
    }
}
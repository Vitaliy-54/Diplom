<?php

namespace App\Livewire\Actions;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use App\Models\UserLog;
use Carbon\Carbon;

class Logout
{
    /**
     * Log the current user out of the application.
     */
    public function __invoke(): void
    {
        // Получаем текущего пользователя
        $user = Auth::user();

        // Если пользователь авторизован
        if ($user) {
            // Удаляем текущую сессию пользователя
            DB::table('sessions')
                ->where('id', Session::getId()) // Удаляем только текущую сессию
                ->delete();

            // Проверяем, есть ли у пользователя другие активные сессии
            $activeSessions = DB::table('sessions')
                ->where('user_id', $user->id)
                ->exists();

            // Если активных сессий нет, записываем время выхода
            if (!$activeSessions) {
                UserLog::updateOrCreate(
                    ['user_id' => $user->id], // Условие поиска
                    ['last_logout_at' => Carbon::now()] // Данные для обновления или создания
                );
            }
        }

        // Выполняем выход
        Auth::guard('web')->logout();

        // Очищаем сессию
        Session::invalidate();
        Session::regenerateToken();
    }
}
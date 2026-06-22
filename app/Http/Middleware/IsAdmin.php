<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class IsAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Проверка, что пользователь аутентифицирован и его роль - admin
        if (auth()->check() && auth()->user()->role === 'admin') {
            return $next($request);
        }

        // Если пользователь не админ, перенаправляем его с сообщением об ошибке
        return redirect('/')->with('error', 'У вас нет доступа к этой странице.');
    }
}
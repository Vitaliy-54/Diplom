<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Validation\ValidationException;
use Illuminate\Http\Request;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->alias([
            'is_admin' => \App\Http\Middleware\IsAdmin::class,
            'track.activity' => \App\Http\Middleware\TrackUserActivity::class,
            'storage.check' => \App\Http\Middleware\CheckUserStorage::class,
        ]);
    })

    ->withMiddleware(function (Middleware $middleware) {
        $middleware->validateCsrfTokens(except: [
            'webauthn/*',
        ]);
    })

    ->withExceptions(function (Exceptions $exceptions) {
        // Обработка MethodNotAllowed (405)
        $exceptions->renderable(function (MethodNotAllowedHttpException $e, Request $request) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Метод не поддерживается',
                    'allowed_methods' => $e->getHeaders()['Allow']
                ], 405);
            }

            return redirect('/')->with('error', 'Действие недоступно');
        });

        // Обработка 404 ошибок
        $exceptions->renderable(function (NotFoundHttpException $e, Request $request) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Ресурс не найден'
                ], 404);
            }

            return response()->view('errors.404', [], 404);
        });

        // Обработка ошибок аутентификации
        $exceptions->renderable(function (AuthenticationException $e, Request $request) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Требуется аутентификация'
                ], 401);
            }

            return redirect()->guest(route('login'));
        });

        // Обработка ошибок валидации
        $exceptions->renderable(function (ValidationException $e, Request $request) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Ошибка валидации',
                    'errors' => $e->errors()
                ], 422);
            }

            return redirect()->back()->withInput()->withErrors($e->errors());
        });
    })
    ->create();
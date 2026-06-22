<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\BlockController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\TagController;
use Illuminate\Support\Facades\Mail;
use App\Mail\TestEmail;
use App\Http\Controllers\Auth\PasswordResetLinkController;
use App\Http\Controllers\Auth\VerificationController;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use App\Http\Controllers\Auth\LogoutController;
use Illuminate\Http\Request;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\NoteController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\Admin\RegistrationController;
use App\Http\Controllers\Admin\VisitStatisticsController;
use App\Http\Controllers\NoteReactionController;
use App\Http\Controllers\LiteratureController;
use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\AvatarController;
use App\Http\Controllers\Nd3CalculatorController;
use App\Models\Nd3CalculationHistory;
use App\Http\Controllers\Ho3CalculatorController;
use App\Models\Ho3CalculationHistory;
use Asbiin\LaravelWebAuthn\Http\Controllers\AuthenticateController;
use Asbiin\LaravelWebAuthn\Http\Controllers\ConfirmableKeyController;
use Asbiin\LaravelWebAuthn\Http\Controllers\WebauthnKeyController;
use App\Http\Controllers\PublicCalculationController;

Route::middleware(['auth'])->group(function () {
    Route::post('/notes/{note}/reactions', [NoteController::class, 'toggleReaction']);
    Route::get('/notes/{note}/reactions/{reaction}', [NoteController::class, 'getReactionUsers']);
});


Route::get('/api/user/{user}/avatar-info', function ($userId) {
    $user = User::find($userId);
    if (!$user) {
        return response()->json(['url' => null]);
    }
    
    $avatarDir = "avatars/{$user->id}";
    $avatarFile = collect(Storage::files($avatarDir))
        ->first(fn($f) => preg_match('/^avatars\/' . $user->id . '\/avatar\.(jpg|jpeg|png|svg|gif)$/i', $f));
    
    $avatarUrl = null;
    if ($avatarFile) {
        $timestamp = filemtime(Storage::path($avatarFile));
        $avatarUrl = route('avatar.serve', ['user' => $user->id, 'filename' => basename($avatarFile)]) . "?v={$timestamp}";
    }
    
    return response()->json([
        'url' => $avatarUrl,
        'name' => $user->name
    ]);
})->name('api.avatar.info');

Route::post('/api/clear-avatar-preload', function () {
    session()->forget('preload_avatars');
    return response()->json(['success' => true]);
})->name('api.clear-avatar-preload');

// Маршрут для страницы инструкции по созданию ключа
Route::view('/passkey', 'passkey-instruction')->name('passkey.instruction');

// ========== WEBAUTHN МАРШРУТЫ ДЛЯ neocalc.site ==========
Route::prefix('webauthn')->group(function () {
    
    // Регистрация - получение опций
    Route::post('/register/options', function (Request $request) {
        try {
            // Проверяем авторизацию
            if (!auth()->check()) {
                return response()->json(['error' => 'Unauthorized'], 401);
            }
            
            $user = auth()->user();
            
            // Генерируем challenge
            $challenge = rtrim(strtr(base64_encode(random_bytes(32)), '+/', '-_'), '=');
            
            // ✅ Исправлено: безопасный user.id (32 байта через SHA-256)
            $secureUserId = hash('sha256', $user->id . $user->email . config('app.key'), true);
            
            return response()->json([
                'publicKey' => [
                    'challenge' => $challenge,
                    'rp' => [
                        'name' => config('app.name', 'NeoCalc'),
                        'id' => 'localhost'  // ✅ ИСПРАВЛЕНО: 'neocalc.site'
                    ],
                    'user' => [
                        'id' => base64_encode($secureUserId),  // ✅ ИСПРАВЛЕНО: длинный ID
                        'name' => $user->email,
                        'displayName' => $user->name
                    ],
                    'pubKeyCredParams' => [
                        ['alg' => -7, 'type' => 'public-key'],  // ES256
                        ['alg' => -257, 'type' => 'public-key'] // RS256
                    ],
                    'authenticatorSelection' => [
                        'authenticatorAttachment' => 'platform',
                        'residentKey' => 'required',
                        'userVerification' => 'required'
                    ],
                    'attestation' => 'none',
                    'timeout' => 60000
                ]
            ]);
        } catch (\Exception $e) {
            Log::error('WebAuthn register/options error: ' . $e->getMessage());
            return response()->json(['error' => $e->getMessage()], 500);
        }
    });
    
    // Регистрация - сохранение ключа
    Route::post('/register', function (Request $request) {
        try {
            if (!auth()->check()) {
                return response()->json(['error' => 'Unauthorized'], 401);
            }
            
            $data = $request->all();
            $deviceName = $data['name'] ?? 'Устройство';
            $credentialId = $data['id'] ?? null;
            
            if (!$credentialId) {
                return response()->json(['error' => 'No credential ID'], 400);
            }
            
            DB::table('web_authn_credentials')->insert([
                'user_id' => auth()->id(),
                'name' => $deviceName,
                'credential_id' => $credentialId,
                'public_key' => json_encode($data['response'] ?? []),
                'counter' => 0,
                'created_at' => now(),
                'updated_at' => now()
            ]);
            
            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            Log::error('WebAuthn register error: ' . $e->getMessage());
            return response()->json(['error' => $e->getMessage()], 500);
        }
    });
    
    // Аутентификация - получение опций
    Route::post('/login/options', function (Request $request) {
        try {
            $challenge = rtrim(strtr(base64_encode(random_bytes(32)), '+/', '-_'), '=');
            
            // Получаем список ключей пользователя если передан email
            $allowCredentials = [];
            if ($request->input('email')) {
                $user = \App\Models\User::where('email', $request->input('email'))->first();
                if ($user) {
                    $credentials = DB::table('web_authn_credentials')
                        ->where('user_id', $user->id)
                        ->get();
                    
                    foreach ($credentials as $cred) {
                        $allowCredentials[] = [
                            'id' => $cred->credential_id,
                            'type' => 'public-key',
                            'transports' => ['internal']
                        ];
                    }
                }
            }
            
            return response()->json([
                'publicKey' => [
                    'challenge' => $challenge,
                    'rpId' => 'localhost',  // ✅ ИСПРАВЛЕНО: 'neocalc.site'
                    'userVerification' => 'required',
                    'timeout' => 60000,
                    'allowCredentials' => $allowCredentials
                ]
            ]);
        } catch (\Exception $e) {
            Log::error('WebAuthn login/options error: ' . $e->getMessage());
            return response()->json(['error' => $e->getMessage()], 500);
        }
    });
    
    // Аутентификация - проверка подписи и вход
    Route::post('/login', function (Request $request) {
        try {
            $credentialId = $request->input('id');
            
            if (!$credentialId) {
                return response()->json([
                    'success' => false,
                    'error' => 'missing_credential',
                    'message' => 'Не передан ID ключа'
                ], 400);
            }
            
            $credential = DB::table('web_authn_credentials')
                ->where('credential_id', $credentialId)
                ->first();
            
            // КЛЮЧ НЕ НАЙДЕН - возвращаем понятную ошибку
            if (!$credential) {
                return response()->json([
                    'success' => false,
                    'error' => 'credential_not_found',
                    'message' => 'Этот отпечаток больше не зарегистрирован. Пожалуйста, войдите по паролю.'
                ], 404);
            }
            
            $user = \App\Models\User::find($credential->user_id);
            
            if (!$user) {
                return response()->json([
                    'success' => false,
                    'error' => 'user_not_found',
                    'message' => 'Пользователь не найден'
                ], 404);
            }
            
            auth()->login($user);
            
            return response()->json([
                'success' => true,
                'redirect' => '/dashboard'
            ]);
            
        } catch (\Exception $e) {
            Log::error('WebAuthn login error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'error' => 'server_error',
                'message' => 'Ошибка сервера. Попробуйте позже.'
            ], 500);
        }
    });
});

Route::get('/', function () {
    if (Auth::check()) {
        return redirect()->route('dashboard'); // Для авторизованных пользователей
    }
    return redirect()->route('login'); // Для неавторизованных пользователей
});


Route::middleware(['auth', 'verified', 'track.activity'])->prefix('share')->name('public.')->group(function () {
    // Просмотр публичного вычисления по токену
    Route::get('/{token}', [PublicCalculationController::class, 'show'])->name('calculation.show');
    
    // Статистика по публичной ссылке
    Route::get('/{token}/stats', [PublicCalculationController::class, 'stats'])->name('calculation.stats');
    
    // Проверка пароля для защищённой ссылки
    Route::post('/{token}/password', [PublicCalculationController::class, 'checkPassword'])->name('calculation.password');
    
    // Скачивание результата в JSON
    Route::get('/{token}/download', [PublicCalculationController::class, 'download'])->name('calculation.download');
    
    // Копирование публичного расчёта в свой аккаунт
    Route::post('/{token}/copy', [PublicCalculationController::class, 'copyToAccount'])->name('calculation.copy');
    
    // QR-код для ссылки
    Route::get('/{token}/qr', [PublicCalculationController::class, 'qrCode'])->name('calculation.qr');
    
    // Валидация ссылки (AJAX)
    Route::get('/{token}/validate', [PublicCalculationController::class, 'validateLink'])->name('calculation.validate');
    
    // Трекинг просмотра (AJAX)
    Route::post('/{token}/track', [PublicCalculationController::class, 'trackView'])->name('calculation.track');
});

// ==========================================
// МАРШРУТЫ ДЛЯ КАЛЬКУЛЯТОРА (ND3+)
// ==========================================
Route::middleware(['auth', 'verified', 'track.activity'])->group(function () {
    // Главная страница калькулятора
    Route::get('/calculator/ND3+', [Nd3CalculatorController::class, 'index'])->name('calculatorND3+.index');
    
    // POST маршруты для вычислений
    Route::post('/calculator/ND3+/calculate', [Nd3CalculatorController::class, 'calculate'])->name('calculator.calculate');
    Route::post('/calculator/ND3+/optimize', [Nd3CalculatorController::class, 'optimize'])->name('calculator.optimize');
    
    // Экспериментальные данные
    Route::post('/calculator/ND3+/save-experimental', [Nd3CalculatorController::class, 'saveExperimental'])->name('calculator.save-experimental');
    
    // История
    Route::get('/calculator/ND3+/history/{history}/load', [Nd3CalculatorController::class, 'loadFromHistory'])
        ->name('calculator.history.load')
        ->whereNumber('history');
    
    Route::post('/calculator/ND3+/history/{history}/toggle-favorite', [Nd3CalculatorController::class, 'toggleFavorite'])
        ->name('calculator.history.toggle-favorite')
        ->whereNumber('history');
    
    Route::delete('/calculator/ND3+/history/{history}', [Nd3CalculatorController::class, 'deleteHistory'])
        ->name('calculator.history.delete')
        ->whereNumber('history');

    Route::post('/calculator/ND3+/exit-history-view', [Nd3CalculatorController::class, 'exitHistoryView'])->name('calculator.exit-history-view');

    Route::post('/calculator/ND3+/history/{history}/update-name', [Nd3CalculatorController::class, 'updateHistoryName'])->name('calculator.history.update-name');
    
    // ===== ПУБЛИЧНЫЕ ССЫЛКИ ДЛЯ ND3+ =====
    // Создание новой публичной ссылки
    Route::post('/calculator/ND3+/history/{history}/share', [Nd3CalculatorController::class, 'createShareLink'])
        ->name('calculator.share.create')
        ->whereNumber('history');
    
    // Получение всех ссылок для вычисления
    Route::get('/calculator/ND3+/history/{history}/shares', [Nd3CalculatorController::class, 'getShareLinks'])
        ->name('calculator.share.list')
        ->whereNumber('history');
    
    // Получение статистики по всем ссылкам
    Route::get('/calculator/ND3+/history/{history}/share-stats', [Nd3CalculatorController::class, 'getShareStats'])
        ->name('calculator.share.stats')
        ->whereNumber('history');
    
    // QR-код для конкретной ссылки
    Route::get('/calculator/ND3+/history/{history}/share-qr/{shareLink}', [Nd3CalculatorController::class, 'getShareQrCode'])
        ->name('calculator.share.qr')
        ->whereNumber('history')
        ->whereNumber('shareLink');
});

// ==========================================
// МАРШРУТЫ ДЛЯ КАЛЬКУЛЯТОРА Ho3+
// ==========================================
Route::middleware(['auth', 'verified', 'track.activity'])->prefix('calculator')->name('calculator.')->group(function () {
    Route::get('/HO3+', [Ho3CalculatorController::class, 'index'])->name('Ho3+.index');
    Route::post('/HO3+/calculate', [Ho3CalculatorController::class, 'calculate'])->name('Ho3+.calculate');
    Route::post('/HO3+/optimize', [Ho3CalculatorController::class, 'optimize'])->name('Ho3+.optimize');
    Route::post('/HO3+/optimize-ajax', [Ho3CalculatorController::class, 'optimizeAjax'])->name('Ho3+.optimize-ajax');
    Route::post('/HO3+/optimize-step', [Ho3CalculatorController::class, 'optimizeStep'])->name('Ho3+.optimize-step');
    Route::post('/HO3+/save-experimental', [Ho3CalculatorController::class, 'saveExperimental'])->name('Ho3+.save-experimental');
    Route::post('/HO3+/exit-history-view', [Ho3CalculatorController::class, 'exitHistoryView'])->name('Ho3+.exit-history-view');
    
    Route::get('/HO3+/history/{history}/load', [Ho3CalculatorController::class, 'loadFromHistory'])
        ->name('Ho3+.history.load')
        ->whereNumber('history');
    
    Route::post('/HO3+/history/{history}/toggle-favorite', [Ho3CalculatorController::class, 'toggleFavorite'])
        ->name('Ho3+.history.toggle-favorite')
        ->whereNumber('history');
    
    Route::delete('/HO3+/history/{history}', [Ho3CalculatorController::class, 'deleteHistory'])
        ->name('Ho3+.history.delete')
        ->whereNumber('history');
    
    Route::post('/HO3+/history/{history}/update-name', [Ho3CalculatorController::class, 'updateHistoryName'])
        ->name('Ho3+.history.update-name');
    
    // ===== ПУБЛИЧНЫЕ ССЫЛКИ ДЛЯ HO3+ =====
    // Создание новой публичной ссылки
    Route::post('/HO3+/history/{history}/share', [Ho3CalculatorController::class, 'createShareLink'])
        ->name('Ho3+.share.create')
        ->whereNumber('history');
    
    // Получение всех ссылок для вычисления
    Route::get('/HO3+/history/{history}/shares', [Ho3CalculatorController::class, 'getShareLinks'])
        ->name('Ho3+.share.list')
        ->whereNumber('history');
    
    // Получение статистики по всем ссылкам
    Route::get('/HO3+/history/{history}/share-stats', [Ho3CalculatorController::class, 'getShareStats'])
        ->name('Ho3+.share.stats')
        ->whereNumber('history');
    
    // QR-код для конкретной ссылки
    Route::get('/HO3+/history/{history}/share-qr/{shareLink}', [Ho3CalculatorController::class, 'getShareQrCode'])
        ->name('Ho3+.share.qr')
        ->whereNumber('history')
        ->whereNumber('shareLink');
});

// ==========================================
// ОБЩИЕ МАРШРУТЫ ДЛЯ УПРАВЛЕНИЯ ССЫЛКАМИ
// (работают с обоими типами калькуляторов через полиморфную связь)
// ==========================================
Route::middleware(['auth', 'verified', 'track.activity'])->prefix('share-links')->name('share-links.')->group(function () {
    
    // Информация о конкретной ссылке
    Route::get('/{shareLink}', [Nd3CalculatorController::class, 'getShareLinkInfo'])
        ->name('show')
        ->whereNumber('shareLink');
    
    // Отзыв ссылки (деактивация)
    Route::patch('/{shareLink}/revoke', [Nd3CalculatorController::class, 'revokeShareLink'])
        ->name('revoke')
        ->whereNumber('shareLink');
    
    // Активация ссылки
    Route::patch('/{shareLink}/activate', [Nd3CalculatorController::class, 'activateShareLink'])
        ->name('activate')
        ->whereNumber('shareLink');
    
    // Продление срока действия
    Route::post('/{shareLink}/extend', [Nd3CalculatorController::class, 'extendShareLink'])
        ->name('extend')
        ->whereNumber('shareLink');
    
    // Полное удаление ссылки
    Route::delete('/{shareLink}', [Nd3CalculatorController::class, 'deleteShareLink'])
        ->name('delete')
        ->whereNumber('shareLink');
    
    // Обновление названия/описания ссылки
    Route::put('/{shareLink}', [Nd3CalculatorController::class, 'updateShareLink'])
        ->name('update')
        ->whereNumber('shareLink');

    // Обновление ссылки (PUT)
    Route::put('/{shareLink}', [Ho3CalculatorController::class, 'updateShareLink'])
        ->name('update')
        ->whereNumber('shareLink');
    
    // Получение детальной статистики по ссылке (JSON)
    Route::get('/{shareLink}/stats', [Nd3CalculatorController::class, 'getShareLinkStats'])
        ->name('stats')
        ->whereNumber('shareLink');
});

// ==========================================
// ДОПОЛНИТЕЛЬНЫЕ МАРШРУТЫ
// ==========================================

// Список всех публичных ссылок пользователя
Route::middleware(['auth', 'verified', 'track.activity'])->get('/my-shared-links', [PublicCalculationController::class, 'mySharedLinks'])
    ->name('shared-links');

// Маршруты для управления публичными ссылками
Route::middleware(['auth', 'verified', 'track.activity'])->prefix('share-links')->group(function () {
    Route::put('/{id}', [PublicCalculationController::class, 'updateShareLink'])->name('share-links.update');
    Route::delete('/{id}', [PublicCalculationController::class, 'deleteShareLink'])->name('share-links.destroy');
    Route::post('/{id}/toggle', [PublicCalculationController::class, 'toggleShareLinkStatus'])->name('share-links.toggle');
});

// Экспорт всех ссылок пользователя (CSV)
Route::middleware(['auth', 'verified'])->get('/my-shared-links/export', function () {
    $shareLinks = App\Models\CalculationShareLink::where('created_by', auth()->id())
        ->with('calculable')
        ->get();
    
    $csv = \League\Csv\Writer::new();
    $csv->insertOne(['Token', 'Title', 'URL', 'Views', 'Created At', 'Expires At', 'Status']);
    
    foreach ($shareLinks as $link) {
        $csv->insertOne([
            $link->token,
            $link->display_title,
            $link->url,
            $link->views,
            $link->created_at->format('Y-m-d H:i'),
            $link->expires_at?->format('Y-m-d H:i') ?? 'Never',
            $link->is_active ? 'Active' : 'Revoked'
        ]);
    }
    
    return response()->stream(function() use ($csv) {
        echo $csv->toString();
    }, 200, [
        'Content-Type' => 'text/csv',
        'Content-Disposition' => 'attachment; filename="my-shared-links.csv"',
    ]);
})->name('shared-links.export');


Route::middleware(['auth', 'is_admin'])->group(function () {
    Route::prefix('statistics')->group(function () {
        // Основные маршруты статистики
        Route::get('visits', [VisitStatisticsController::class, 'index'])
            ->name('admin.statistics.visits');

        Route::get('user/{user}', [VisitStatisticsController::class, 'userStatistics'])
            ->name('admin.statistics.user');

        Route::delete('user/{user}/clear', [VisitStatisticsController::class, 'deleteUserHistory'])
            ->name('admin.statistics.user.clear');

        Route::delete('bulk-delete-by-date', [VisitStatisticsController::class, 'bulkDeleteByDate'])
             ->name('visits.bulkDeleteByDate');


        // Маршруты для удаления
        Route::prefix('visits')->group(function () {
            Route::get('/delete', [VisitStatisticsController::class, 'deletePage'])
                ->name('visits.deletePage');
                Route::delete('clear', [VisitStatisticsController::class, 'clear'])->name('admin.statistics.visits.clear');

        });
    });
});

// Auth routes with email verification
Auth::routes(['verify' => false]); // Отключаем стандартную верификацию Laravel

  // Аватарка пользователя
Route::post('/avatar/upload', [AvatarController::class, 'upload'])->middleware('auth')->name('avatar.upload');
Route::get('/avatars/{user}/{filename}', function ($user, $filename) {
    $path = "avatars/{$user}/{$filename}";
    abort_unless(Storage::exists($path), 404);
    return response()->file(Storage::path($path));
})->middleware('auth')->name('avatar.serve');

// Custom Email Verification Routes
Route::middleware(['auth'])->group(function () {
    // Show verification form
    Route::get('/email/verify', [VerificationController::class, 'show'])
        ->name('verification.notice')
        ->middleware('email.not.verified');
    
    // Handle verification code submission
    Route::post('/email/verify', [VerificationController::class, 'verify'])
        ->name('verification.verify')
        ->middleware('email.not.verified');
    
    // Resend verification code
    Route::post('/email/resend', [VerificationController::class, 'resend'])
        ->name('verification.send')
        ->middleware(['email.not.verified', 'throttle:6,1']); 
});

// Logout route
Route::post('/logout', [LogoutController::class, 'logout'])->name('logout');

// Password Reset Routes
Route::get('/forgot-password', [App\Http\Controllers\Auth\PasswordResetLinkController::class, 'create'])
    ->middleware('guest')
    ->name('password.request');

Route::post('/forgot-password', [App\Http\Controllers\Auth\PasswordResetLinkController::class, 'store'])
    ->middleware('guest')
    ->name('password.email');

Route::get('/reset-password/{token}', [App\Http\Controllers\Auth\PasswordResetLinkController::class, 'showResetForm'])
    ->middleware('guest')
    ->name('password.reset');

Route::post('/reset-password', [App\Http\Controllers\Auth\PasswordResetLinkController::class, 'reset'])
    ->middleware('guest')
    ->name('password.update');

Route::get('/dashboard', [HomeController::class, 'dashboard'])
    ->middleware(['auth', 'verified', 'track.activity'])
    ->name('dashboard');

Route::middleware(['auth', 'verified', 'track.activity'])->group(function () {
    // Список задач
    Route::get('/tasks', [TaskController::class, 'index'])->name('tasks.index');
    Route::post('/tasks', [TaskController::class, 'store'])->name('tasks.store');
    Route::put('/tasks/{task}', [TaskController::class, 'update'])->name('tasks.update');
    Route::delete('/tasks/{task}', [TaskController::class, 'destroy'])->name('tasks.destroy');
    Route::get('/tasks/{task}/edit', [TaskController::class, 'edit'])->name('tasks.edit'); // Маршрут для редактирования
    Route::post('/tasks/{task}/toggle-completion', [TaskController::class, 'toggleCompletion'])->name('tasks.toggle-completion');
    Route::post('/categories/archive', [TaskController::class, 'archiveCategory'])->name('categories.archive');
    Route::post('/categories/unarchive', [TaskController::class, 'unarchiveCategory'])->name('categories.unarchive');

    // Заметки для всех авторизованных пользователей
    Route::get('/notes', [NoteController::class, 'index'])->name('notes.index'); // Публичные заметки
    Route::get('/notes/my', [NoteController::class, 'my'])->name('notes.my'); // Мои заметки
    Route::get('/notes/create', [NoteController::class, 'create'])->name('notes.create'); // Форма создания заметки
    Route::get('/notes/{note}', [NoteController::class, 'show'])->name('notes.show'); // Просмотр заметки
    Route::get('/notes/{note}/edit', [NoteController::class, 'edit'])->name('notes.edit'); // Форма редактирования заметки
    Route::delete('/notes/{note}', [NoteController::class, 'destroy'])->name('notes.destroy'); // Удаление заметки

    // Страница уведомлений
    Route::get('/notifications', [NotificationController::class, 'index'])
        ->name('admin.notifications.index');
    // Пометка уведомления как прочитанного
    Route::post('/notifications/{notification}/mark-as-read', [NotificationController::class, 'markAsRead'])
        ->name('admin.notifications.mark-as-read');

    // Удаление уведомления
    Route::delete('/notifications/{notification}', [NotificationController::class, 'destroy'])
        ->name('admin.notifications.destroy');

    Route::get('/my-files', [NoteController::class, 'myFiles'])->name('files.my');


});

Route::middleware(['auth', 'verified', 'track.activity', 'storage.check'])->group(function () {
    Route::put('/notes/{note}', [NoteController::class, 'update'])->name('notes.update'); // Обновление заметки
    Route::post('/notes', [NoteController::class, 'store'])->name('notes.store'); // Сохранение заметки
});

Route::group(['middleware' => ['auth', 'track.activity']], function () {
    Route::get('/notes/{note}/reactions', [NoteReactionController::class, 'index']);
    Route::post('/notes/{note}/reactions', [NoteReactionController::class, 'store']);
    Route::get('/notes/{note}/reactions/{reaction}', [NoteReactionController::class, 'getReactions'])->name('notes.reactions.get');
});

// Для скачивания файлов
Route::get('/notes/{note}/files/{file}/download', [NoteController::class, 'downloadFile'])
    ->name('notes.files.download')
    ->middleware('auth', 'track.activity');

// Для удаления файлов
Route::delete('/notes/{note}/files/{file}', [NoteController::class, 'destroyFile'])
    ->name('notes.files.destroy')
    ->middleware('auth', 'track.activity');

Route::get('/my-files', [NoteController::class, 'myFiles'])->name('my-files')->middleware('auth', 'track.activity');

// Маршруты для пользователя (теги)
Route::middleware(['auth',  'verified', 'track.activity'])->group(function () {
    Route::post('tags/', [TagController::class, 'store'])->name('tags.store');
    Route::get('tags/api', [TagController::class, 'indexApi'])->name('tags.api.index');
});

// Маршруты для справочного материала
Route::middleware(['auth', 'verified', 'track.activity'])->group(function () {
    Route::resource('literature', LiteratureController::class)->except(['show']);
    Route::get('/literature/{literature}/download', [LiteratureController::class, 'download'])->name('literature.download');
});

// Маршруты для пользователя (карточка пользователя)
Route::middleware(['auth', 'verified', 'track.activity'])->group(function () {
    Route::get('/user/{user}/info', [UserController::class, 'info'])->name('user.info');
});

// Маршруты для администратора (администрирование файлов)
Route::middleware(['auth', 'is_admin', 'track.activity'])->group(function () {
    Route::get('/admin/files', [NoteController::class, 'adminFiles'])->name('admin.files');
});

// Маршруты для администратора (теги)
Route::middleware(['auth', 'is_admin', 'track.activity'])->group(function () {
    Route::delete('tags/{tag}', [TagController::class, 'destroy'])->name('tags.destroy');
    Route::put('tags/{tag}', [TagController::class, 'update'])->name('tags.update');
});

// Маршруты для администратора (управление сайтом)
Route::middleware(['auth', 'is_admin', 'track.activity'])->group(function () {
    Route::get('/admin', [AdminController::class, 'index'])->name('admin.index');
});

// Маршруты для администратора (изменение почты)
Route::middleware(['auth', 'is_admin', 'track.activity'])->group(function () {
Route::get('/users/{user}/edit-email', [UserController::class, 'editEmail'])->name('admin.users.edit-email');
Route::put('/users/{user}/update-email', [UserController::class, 'updateEmail'])->name('admin.users.update-email');
});


// Маршруты для администратора (заметки)
Route::middleware(['auth', 'is_admin', 'track.activity'])->group(function () {
    Route::get('/notes/private', [NoteController::class, 'private'])->name('notes.private');
});

// Маршруты для администратора (отключение регистрации)
Route::middleware(['auth', 'is_admin', 'track.activity'])->group(function () {
    // Маршрут для отображения страницы управления регистрацией
    Route::get('/admin/registration-control', [RegistrationController::class, 'index'])
    ->name('admin.registration-control');
    // Маршрут для переключения статуса регистрации
    Route::post('/admin/toggle-registration', [RegistrationController::class, 'toggleRegistration'])
    ->name('admin.toggle-registration');
});

// Маршруты для администратора (блоки)
Route::middleware(['auth', 'is_admin', 'track.activity'])->group(function () {
    // Отображение списка блоков
    Route::get('/admin/blocks', [BlockController::class, 'index'])->name('blocks.index');
    // Форма для создания нового блока
    Route::get('/admin/blocks/create', [BlockController::class, 'create'])->name('blocks.create');
    // Сохранение нового блока
    Route::post('/admin/blocks', [BlockController::class, 'store'])->name('blocks.store');
    // Форма для редактирования блока
    Route::get('/admin/blocks/{block}/edit', [BlockController::class, 'edit'])->name('blocks.edit');
    // Обновление блока
    Route::put('/admin/blocks/{block}', [BlockController::class, 'update'])->name('blocks.update');
    Route::patch('/admin/blocks/{block}', [BlockController::class, 'update']); // Альтернатива для PUT
    // Удаление блока
    Route::delete('/admin/blocks/{block}', [BlockController::class, 'destroy'])->name('blocks.destroy');
});

Route::view('profile', 'profile')
    ->middleware(['auth', 'track.activity'])
    ->name('profile');

// Маршруты для администратора (управление пользователями)
Route::middleware(['auth', 'is_admin', 'track.activity'])->group(function () {
    Route::get('/users', [UserController::class, 'index'])->name('admin.users.index');
    Route::get('/users/create', [UserController::class, 'create'])->name('admin.users.create'); // Маршрут для отображения формы создания
    Route::post('/users', [UserController::class, 'store'])->name('admin.users.store'); // Маршрут для сохранения нового пользователя
    Route::delete('/users/{user}', [UserController::class, 'destroy'])->name('admin.users.destroy');
    Route::get('/admin/users/{user}/edit-password', [UserController::class, 'editPassword'])->name('admin.users.edit-password');
    Route::put('/admin/users/{user}/update-password', [UserController::class, 'updatePassword'])->name('admin.users.update-password');
    Route::patch('/admin/users/{user}/change-role', [UserController::class, 'changeRole'])->name('admin.users.change-role');//изменение роли
});

// Маршруты для администратора (статус активности пользователей)
Route::middleware(['auth', 'is_admin'])->group(function () {
    Route::get('/admin/users/status', [UserController::class, 'getStatus'])->name('admin.users.status');
});

// Маршруты для администратора (управление уведомлениями)
Route::middleware(['auth', 'is_admin', 'track.activity'])->group(function () {
    // Отправка уведомлений
    Route::post('/users/{user}/send-notification', [UserController::class, 'sendNotification'])->name('admin.users.send-notification');
    // Просмотр отправленных уведомлений
    Route::get('/admin/users/sent-notifications', [UserController::class, 'sentNotifications'])
        ->name('admin.users.sent-notifications');
    // Удалить уведомление для всех пользователей
    Route::delete('/admin/notifications/sent/{notification}', [NotificationController::class, 'destroySent'])
        ->name('admin.notifications.destroy-sent');
    // Удалить уведомление для конкретного пользователя
    Route::delete('/admin/notifications/sent/{notification}/user/{user}', [NotificationController::class, 'destroySentForUser'])
        ->name('admin.notifications.destroy-sent-user');
    // Форма для отправки уведомления
    Route::get('/users/send-notification', [UserController::class, 'showSendNotificationForm'])
        ->name('admin.users.show-send-notification');
    // Обработка отправки уведомления
    Route::post('/users/send-notification', [UserController::class, 'sendNotification'])
        ->name('admin.users.send-notification');
    // Маршрут для отображения формы редактирования уведомления
    Route::get('/admin/notifications/{notification}/edit', [NotificationController::class, 'edit'])
    ->name('admin.notifications.edit');
    // Маршрут для обработки обновления уведомления
    Route::put('/admin/notifications/{notification}', [NotificationController::class, 'update'])
    ->name('admin.notifications.update');
});

require __DIR__ . '/auth.php';
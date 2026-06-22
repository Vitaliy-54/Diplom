<?php

namespace App\Http\Controllers;

use App\Models\CalculationShareLink;
use App\Models\Nd3CalculationHistory;
use App\Models\Ho3CalculationHistory;
use App\Services\Nd3CalculatorService;
use App\Services\Ho3CalculatorService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class PublicCalculationController extends Controller
{
    protected $nd3CalculatorService;
    protected $ho3CalculatorService;

    public function __construct(
        Nd3CalculatorService $nd3CalculatorService,
        Ho3CalculatorService $ho3CalculatorService
    ) {
        $this->nd3CalculatorService = $nd3CalculatorService;
        $this->ho3CalculatorService = $ho3CalculatorService;
    }

    /**
     * Показать публичное вычисление по токену
     */
/**
 * Показать публичное вычисление по токену
 */
public function show($token, Request $request)
{
    // Ищем ссылку по токену
    $shareLink = CalculationShareLink::where('token', $token)
        ->where('is_active', true)
        ->where(function($query) {
            $query->whereNull('expires_at')
                  ->orWhere('expires_at', '>', now());
        })
        ->first();

    if (!$shareLink) {
        abort(404, 'The shared calculation link is invalid or has expired.');
    }

    // Проверка пароля
    if ($shareLink->password_hash) {
        $sessionKey = "share_password_{$token}";
        
        $isAuthenticated = Session::get($sessionKey) === $token;
        
        if (!$isAuthenticated) {
            if ($request->isMethod('post') && $request->has('password')) {
                if ($shareLink->checkPassword($request->password)) {
                    Session::put($sessionKey, $token);
                    $isAuthenticated = true;
                } else {
                    return view('public.password', [
                        'token' => $token,
                        'error' => 'Invalid password. Please try again.'
                    ]);
                }
            }
            
            if (!$isAuthenticated) {
                return view('public.password', [
                    'token' => $token,
                    'error' => null
                ]);
            }
        }
    }

    // Регистрируем просмотр
    $uniqueKey = 'share_view_' . $token . '_' . ($request->ip() . (Auth::id() ?? 'guest'));
    $isUnique = !Session::has($uniqueKey);
    
    if ($isUnique) {
        Session::put($uniqueKey, true, 60 * 24 * 30);
        $shareLink->registerAccess($request, $isUnique);
    }

    // Получаем вычисление
    $calculation = $shareLink->calculable;

    if (!$calculation) {
        abort(404, 'Calculation not found.');
    }

    // Подготавливаем общие данные
    $inputData = $calculation->getDisplayInputData();
    $inputOriginal = $calculation->input_data;
    $results = $calculation->results;
    $optimizationResult = $calculation->optimization_results;
    $experimentalData = $calculation->experimental_data ?? [];
    $isOptimized = $calculation->isOptimized();
    $isOwner = Auth::check() && $shareLink->created_by === Auth::id();

    // ОБЩИЕ ДАННЫЕ ДЛЯ ВСЕХ ШАБЛОНОВ
    $viewData = [
        'shareLink' => $shareLink,
        'calculation' => $calculation,
        'inputData' => $inputData,
        'inputOriginal' => $inputOriginal,
        'results' => $results,
        'optimizationResult' => $optimizationResult,
        'experimentalData' => $experimentalData,
        'isOptimized' => $isOptimized,
        'isOwner' => $isOwner,
        'allowComments' => $shareLink->allow_comments,
        'allowDownload' => $shareLink->allow_download,
        'allowCopy' => $shareLink->allow_copy_to_account
    ];

    // ОПРЕДЕЛЯЕМ ТИП КАЛЬКУЛЯТОРА И ВОЗВРАЩАЕМ СООТВЕТСТВУЮЩИЙ ШАБЛОН
    if ($calculation instanceof \App\Models\Nd3CalculationHistory) {
        return view('public.calculation-nd3', $viewData);
    } elseif ($calculation instanceof \App\Models\Ho3CalculationHistory) {
        return view('public.calculation-ho3', $viewData);
    }

    abort(404, 'Unknown calculation type.');
}

/**
 * Обновить публичную ссылку
 */
public function updateShareLink($id, Request $request)
{
    $shareLink = CalculationShareLink::where('id', $id)
        ->where('created_by', Auth::id())
        ->firstOrFail();
    
    $validated = $request->validate([
        'title' => 'nullable|string|max:255',
        'description' => 'nullable|string|max:1000',
        'expires_at' => 'nullable|date',
        'allow_copy_to_account' => 'boolean'
    ]);
    
    $shareLink->update([
        'title' => $validated['title'] ?? null,
        'description' => $validated['description'] ?? null,
        'expires_at' => $validated['expires_at'] ?? null,
        'allow_copy_to_account' => $validated['allow_copy_to_account'] ?? false
    ]);
    
    if ($request->wantsJson()) {
        return response()->json(['success' => true, 'message' => 'Ссылка обновлена']);
    }
    
    return redirect()->back()->with('success', 'Ссылка обновлена');
}

/**
 * Удалить публичную ссылку
 */
public function deleteShareLink($id, Request $request)
{
    $shareLink = CalculationShareLink::where('id', $id)
        ->where('created_by', Auth::id())
        ->firstOrFail();
    
    $shareLink->delete();
    
    if ($request->wantsJson()) {
        return response()->json(['success' => true, 'message' => 'Ссылка удалена']);
    }
    
    return redirect()->back()->with('success', 'Ссылка удалена');
}

/**
 * Отозвать/деактивировать ссылку
 */
public function toggleShareLinkStatus($id, Request $request)
{
    $shareLink = CalculationShareLink::where('id', $id)
        ->where('created_by', Auth::id())
        ->firstOrFail();
    
    $shareLink->is_active = !$shareLink->is_active;
    $shareLink->save();
    
    $status = $shareLink->is_active ? 'активирована' : 'деактивирована';
    
    if ($request->wantsJson()) {
        return response()->json(['success' => true, 'message' => "Ссылка {$status}"]);
    }
    
    return redirect()->back()->with('success', "Ссылка {$status}");
}


/**
 * Проверка пароля для защищённой ссылки (AJAX)
 */
public function checkPassword($token, Request $request)
{
    $shareLink = CalculationShareLink::where('token', $token)->firstOrFail();

    if (!$shareLink->password_hash) {
        return response()->json(['success' => true, 'no_password' => true]);
    }

    $request->validate([
        'password' => 'required|string'
    ]);

    if ($shareLink->checkPassword($request->password)) {
        $sessionKey = "share_password_{$token}";
        Session::put($sessionKey, $token);
        
        // Для AJAX-запроса возвращаем JSON
        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'redirect' => route('public.calculation.show', ['token' => $token])
            ]);
        }
        
        // Для обычного POST-запроса (если форма отправляется без AJAX)
        return redirect()->route('public.calculation.show', ['token' => $token]);
    }

    if ($request->wantsJson() || $request->ajax()) {
        return response()->json([
            'success' => false,
            'error' => 'Неверный пароль'
        ], 403);
    }

    // Возвращаем обратно на страницу ввода пароля с ошибкой
    return redirect()->route('public.calculation.show', ['token' => $token])
        ->with('password_error', 'Неверный пароль. Пожалуйста, попробуйте еще раз..');
}

/**
 * Список всех публичных ссылок пользователя
 */
public function mySharedLinks()
{
    $shareLinks = CalculationShareLink::where('created_by', Auth::id())
        ->with('calculable')
        ->orderBy('created_at', 'desc')
        ->paginate(20);
    
    // Подсчёт статистики для отображения
    $stats = [
        'total' => CalculationShareLink::where('created_by', Auth::id())->count(),
        'active' => CalculationShareLink::where('created_by', Auth::id())
            ->where('is_active', true)
            ->where(function($q) {
                $q->whereNull('expires_at')->orWhere('expires_at', '>', now());
            })->count(),
        'total_views' => CalculationShareLink::where('created_by', Auth::id())->sum('views'),
        'unique_views' => CalculationShareLink::where('created_by', Auth::id())->sum('unique_views')
    ];
    
    return view('shared-links', compact('shareLinks', 'stats'));
}

/**
 * Получить статистику по ссылке (для владельца или администратора)
 */
public function stats($token, Request $request)
{
    $shareLink = CalculationShareLink::where('token', $token)->firstOrFail();

    // Проверяем права: владелец, администратор или stats_token
    $isOwner = Auth::check() && Auth::id() === $shareLink->created_by;
    $isAdmin = Auth::check() && Auth::user()->role === 'admin';
    
    // Проверка stats_token (для случаев, когда пользователь не авторизован, но знает токен)
    $hasValidStatsToken = false;
    $statsToken = $request->get('stats_token');
    if ($statsToken && Hash::check($shareLink->token . $shareLink->created_by, $statsToken)) {
        $hasValidStatsToken = true;
    }
    
    // Доступ есть, если: владелец, администратор, или есть валидный stats_token
    if (!$isOwner && !$isAdmin && !$hasValidStatsToken) {
        abort(403, 'У вас нет разрешения на просмотр статистики');
    }

    // Агрегированная статистика
    $logs = $shareLink->access_log ?? [];
    
    // Получаем историю копирований
    $copyLogs = DB::table('share_link_copies')
        ->where('share_link_id', $shareLink->id)
        ->orderBy('created_at', 'desc')
        ->get()
        ->map(function($copy) {
            return [
                'copied_at' => $copy->created_at,
                'user_id' => $copy->copied_by,
                'new_calculation_id' => $copy->new_calculation_id,
                'new_calculation_type' => $copy->new_calculation_type,
                'success' => true,
                'status' => 'success'
            ];
        })
        ->toArray();
    
    $stats = [
        'total_views' => $shareLink->views,
        'unique_views' => $shareLink->unique_views,
        'created_at' => $shareLink->created_at,
        'created_by' => $shareLink->creator->name ?? 'Unknown',
        'last_accessed_at' => $shareLink->last_accessed_at,
        'expires_at' => $shareLink->expires_at,
        'is_active' => $shareLink->is_active,
        'access_log' => $logs,
        'copy_log' => $copyLogs, // Добавляем историю копирований
        'total_copies' => count($copyLogs), // Общее количество копирований
        'daily_views' => $this->getDailyViews($logs),
        'hourly_distribution' => $this->getHourlyDistribution($logs),
        'top_referrers' => $this->getTopReferrers($logs),
        'top_ips' => $this->getTopIps($logs),
        'user_agents' => $this->getUserAgents($logs),
        'views_over_time' => $this->getViewsOverTime($logs, 30)
    ];

    if ($request->wantsJson()) {
        return response()->json($stats);
    }

    return view('public.stats', [
        'shareLink' => $shareLink,
        'stats' => $stats,
        'chartData' => $this->prepareChartData($stats)
    ]);
}

    /**
     * Скачать результат вычисления в JSON
     */
    public function download($token, Request $request)
    {
        $shareLink = CalculationShareLink::where('token', $token)->firstOrFail();

        if (!$shareLink->isAccessible()) {
            abort(404, 'Link is no longer accessible.');
        }

        if (!$shareLink->allow_download) {
            abort(403, 'Download is disabled by the owner.');
        }

        // Проверка пароля
        if ($shareLink->password_hash && Session::get("share_password_{$token}") !== $token) {
            return redirect()->route('public.calculation.show', ['token' => $token])
                ->with('error', 'Password required to download');
        }

        $calculation = $shareLink->calculable;

        $data = [
            'meta' => [
                'share_link_id' => $shareLink->id,
                'token' => $shareLink->token,
                'calculation_name' => $calculation->name,
                'created_at' => $calculation->created_at->toISOString(),
                'exported_at' => now()->toISOString(),
                'is_optimized' => $calculation->isOptimized(),
                'title' => $shareLink->title,
                'description' => $shareLink->description
            ],
            'input_data' => $calculation->getDisplayInputData(),
            'original_input_data' => $calculation->input_data,
            'results' => $calculation->results,
            'optimization_results' => $calculation->optimization_results,
            'experimental_data' => $calculation->experimental_data,
            'quality_metrics' => $this->calculateQualityMetrics($calculation)
        ];

        $filename = 'calculation_' . ($calculation->name ?? 'export') . '_' . date('Y-m-d_His') . '.json';
        $filename = preg_replace('/[^a-zA-Z0-9_\-.]/', '_', $filename);

        return response()->json($data, 200, [
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
            'Content-Type' => 'application/json'
        ]);
    }

    /**
     * Копировать публичный расчёт в аккаунт пользователя
     */
    public function copyToAccount($token, Request $request)
    {
        if (!Auth::check()) {
            if ($request->wantsJson()) {
                return response()->json(['error' => 'Authentication required'], 401);
            }
            return redirect()->route('login')->with('error', 'Please login to copy this calculation to your account.');
        }

        $shareLink = CalculationShareLink::where('token', $token)->firstOrFail();

        if (!$shareLink->isAccessible()) {
            abort(404, 'Link is no longer accessible.');
        }

        if (!$shareLink->allow_copy_to_account) {
            abort(403, 'Copying to account is disabled by the owner.');
        }

        // Проверка пароля
        if ($shareLink->password_hash && Session::get("share_password_{$token}") !== $token) {
            return redirect()->route('public.calculation.show', ['token' => $token])
                ->with('error', 'Password required to copy');
        }

        $originalCalculation = $shareLink->calculable;
        $modelClass = get_class($originalCalculation);

        // Проверяем, не существует ли уже такой копии (опционально)
        $existingCopy = $modelClass::where('user_id', Auth::id())
            ->where('input_data', json_encode($originalCalculation->input_data))
            ->where('created_at', '>', now()->subDays(1))
            ->first();

        if ($existingCopy && $request->input('skip_duplicate_check') !== 'true') {
            if ($request->wantsJson()) {
                return response()->json([
                    'error' => 'You already have a similar calculation. Use force=true to copy anyway.',
                    'existing_id' => $existingCopy->id
                ], 409);
            }
            
            return redirect()->route('public.calculation.show', ['token' => $token])
                ->with('warning', 'You already have a similar calculation. Click "Copy Anyway" to create another copy.');
        }

        // Создаём копию
        $newCalculation = $modelClass::create([
            'user_id' => Auth::id(),
            'name' => 'Copy of: ' . ($originalCalculation->name ?? 'Untitled'),
            'input_data' => $originalCalculation->input_data,
            'results' => $originalCalculation->results,
            'optimization_results' => $originalCalculation->optimization_results,
            'optimized_input_data' => $originalCalculation->optimized_input_data,
            'experimental_data' => $originalCalculation->experimental_data,
            'is_favorite' => false
        ]);

        // Логируем копирование
        DB::table('share_link_copies')->insert([
            'share_link_id' => $shareLink->id,
            'copied_by' => Auth::id(),
            'new_calculation_id' => $newCalculation->id,
            'new_calculation_type' => $modelClass,
            'created_at' => now()
        ]);

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'calculation_id' => $newCalculation->id,
                'message' => 'Calculation copied successfully'
            ]);
        }

        $calculatorRoute = $originalCalculation instanceof Nd3CalculationHistory 
            ? 'calculatorND3+.index' 
            : 'calculator.Ho3+.index';

        return redirect()->route($calculatorRoute)
            ->with('success', 'Calculation copied to your account successfully!');
    }

    /**
     * Получить QR-код для ссылки
     */
    public function qrCode($token, Request $request)
    {
        $shareLink = CalculationShareLink::where('token', $token)->firstOrFail();

        if (!$shareLink->isAccessible()) {
            abort(404);
        }

        $url = $shareLink->url;
        
        // Генерируем QR-код с помощью простого API или библиотеки
        $size = $request->get('size', 200);
        $qrUrl = "https://quickchart.io/qr?text=" . urlencode($url) . "&size={$size}";
        
        return redirect($qrUrl);
    }

    /**
     * Валидация ссылки (проверка существования и активности)
     */
    public function validateLink($token)
    {
        $shareLink = CalculationShareLink::where('token', $token)->first();

        if (!$shareLink) {
            return response()->json(['valid' => false, 'error' => 'Link not found'], 404);
        }

        if (!$shareLink->isAccessible()) {
            return response()->json([
                'valid' => false, 
                'error' => 'Link is expired or deactivated',
                'expired' => $shareLink->expires_at && $shareLink->expires_at->isPast(),
                'deactivated' => !$shareLink->is_active
            ]);
        }

        return response()->json([
            'valid' => true,
            'has_password' => !is_null($shareLink->password_hash),
            'title' => $shareLink->display_title,
            'views' => $shareLink->views,
            'created_at' => $shareLink->created_at
        ]);
    }

    /**
     * Обновить счётчик просмотров (для AJAX)
     */
    public function trackView($token, Request $request)
    {
        $shareLink = CalculationShareLink::where('token', $token)->first();

        if (!$shareLink || !$shareLink->isAccessible()) {
            return response()->json(['error' => 'Invalid link'], 404);
        }

        $uniqueKey = 'share_view_' . $token . '_' . ($request->ip() . (Auth::id() ?? 'guest'));
        $isUnique = !Session::has($uniqueKey);

        if ($isUnique) {
            Session::put($uniqueKey, true, 60 * 24 * 30);
            $shareLink->registerAccess($request, $isUnique);
        }

        return response()->json([
            'success' => true,
            'views' => $shareLink->views,
            'unique_views' => $shareLink->unique_views
        ]);
    }

    // ==========================================
    // ПРИВАТНЫЕ ВСПОМОГАТЕЛЬНЫЕ МЕТОДЫ
    // ==========================================

    /**
     * Получить ежедневную статистику просмотров
     */
    private function getDailyViews($logs)
    {
        $daily = [];
        foreach ($logs as $log) {
            $date = date('Y-m-d', strtotime($log['accessed_at']));
            $daily[$date] = ($daily[$date] ?? 0) + 1;
        }
        ksort($daily);
        return $daily;
    }

    /**
     * Получить распределение по часам
     */
    private function getHourlyDistribution($logs)
    {
        $hourly = array_fill(0, 24, 0);
        foreach ($logs as $log) {
            $hour = (int)date('H', strtotime($log['accessed_at']));
            $hourly[$hour]++;
        }
        return $hourly;
    }

    /**
     * Получить топ рефереров
     */
    private function getTopReferrers($logs, $limit = 10)
    {
        $referrers = [];
        foreach ($logs as $log) {
            $referer = $log['referer'] ?? 'direct';
            if (empty($referer) || $referer === '') {
                $referer = 'direct';
            }
            // Нормализуем URL
            $parsed = parse_url($referer);
            if (isset($parsed['host'])) {
                $referer = $parsed['host'];
            }
            $referrers[$referer] = ($referrers[$referer] ?? 0) + 1;
        }
        arsort($referrers);
        return array_slice($referrers, 0, $limit);
    }

    /**
     * Получить топ IP-адресов
     */
    private function getTopIps($logs, $limit = 10)
    {
        $ips = [];
        foreach ($logs as $log) {
            $ip = $log['ip'] ?? 'unknown';
            $ips[$ip] = ($ips[$ip] ?? 0) + 1;
        }
        arsort($ips);
        return array_slice($ips, 0, $limit);
    }

    /**
     * Получить информацию о User Agents с более точным определением
     */
    private function getUserAgents($logs)
    {
        $agents = [];
        foreach ($logs as $log) {
            $ua = strtolower($log['user_agent'] ?? 'unknown');
            $type = $this->detectDeviceTypeFromUserAgent($ua);
            $agents[$type] = ($agents[$type] ?? 0) + 1;
        }
        return $agents;
    }

    /**
     * Определение типа устройства по User-Agent строке
     */
    private function detectDeviceTypeFromUserAgent($ua)
    {
        // Планшеты (проверяем в первую очередь, так как iPad может содержать 'mobile')
            $tabletKeywords = ['ipad', 'tablet', 'kindle', 'silk', 'playbook', 'nexus 7', 'nexus 10', 'xoom', 'galaxy tab'];
        foreach ($tabletKeywords as $keyword) {
            if (strpos($ua, $keyword) !== false) {
                return 'tablet';
            }
        }
        
        // Мобильные устройства
        $mobileKeywords = [
            'iphone', 'ipod', 'android.*mobile', 'windows phone', 
            'blackberry', 'mobile', 'samsung', 'xiaomi', 'huawei', 
            'oneplus', 'nokia', 'lg', 'sony', 'htc', 'motorola'
        ];
        foreach ($mobileKeywords as $keyword) {
            if (strpos($ua, $keyword) !== false) {
                return 'mobile';
            }
        }
        
        // Десктопы
        $desktopKeywords = ['windows', 'mac', 'linux', 'x11', 'ubuntu', 'debian', 'fedora'];
        foreach ($desktopKeywords as $keyword) {
            if (strpos($ua, $keyword) !== false) {
                return 'desktop';
            }
        }
        
        return 'other';
    }

    /**
     * Получить данные для графика просмотров за период
     */
    private function getViewsOverTime($logs, $days = 30)
    {
        $views = [];
        $startDate = Carbon::now()->subDays($days);
        
        for ($i = 0; $i <= $days; $i++) {
            $date = $startDate->copy()->addDays($i)->format('Y-m-d');
            $views[$date] = 0;
        }
        
        foreach ($logs as $log) {
            $date = date('Y-m-d', strtotime($log['accessed_at']));
            if (isset($views[$date])) {
                $views[$date]++;
            } elseif (strtotime($date) >= strtotime($startDate)) {
                $views[$date] = ($views[$date] ?? 0) + 1;
            }
        }
        
        ksort($views);
        return $views;
    }

    /**
     * Подготовить данные для графиков Chart.js
     */
    private function prepareChartData($stats)
    {
        return [
            'daily_views' => [
                'labels' => array_keys($stats['daily_views']),
                'values' => array_values($stats['daily_views'])
            ],
            'hourly_distribution' => [
                'labels' => array_map(function($h) { return $h . ':00'; }, range(0, 23)),
                'values' => array_values($stats['hourly_distribution'])
            ],
            'views_over_time' => [
                'labels' => array_keys($stats['views_over_time']),
                'values' => array_values($stats['views_over_time'])
            ]
        ];
    }

    /**
     * Рассчитать метрики качества для расчёта
     */
    private function calculateQualityMetrics($calculation)
    {
        $optResults = $calculation->optimization_results;
        if (!$optResults) return null;
        
        return [
            'rmse' => $optResults['rmse'] ?? null,
            'r_squared' => $optResults['r_squared'] ?? null,
            'ssd' => $optResults['ssd'] ?? null,
            'mae' => $optResults['mae'] ?? null,
            'data_points' => $optResults['count'] ?? null
        ];
    }

    /**
     * Получить превью изображение для Open Graph
     */
    private function getPreviewImage($calculation)
    {
        // Можно вернуть URL к динамически сгенерированному изображению графика
        // или статическое изображение по умолчанию
        return asset('images/share-preview.png');
    }
}
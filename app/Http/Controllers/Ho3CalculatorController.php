<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\Ho3CalculatorService;
use App\Services\Ho3OptimizationService;
use App\Models\Ho3CalculationHistory;
use App\Models\CalculationShareLink;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class Ho3CalculatorController extends Controller
{
    protected $calculatorService;
    protected $optimizationService;
    
    // Префикс для ключей сессии
    protected $sessionPrefix = 'ho3_';

    public function __construct(
        Ho3CalculatorService $calculatorService,
        Ho3OptimizationService $optimizationService
    ) {
        $this->calculatorService = $calculatorService;
        $this->optimizationService = $optimizationService;
    }
    
    /**
     * Получить ключ сессии с префиксом
     */
    protected function sessionKey($key)
    {
        return $this->sessionPrefix . $key;
    }
    
    /**
     * Вспомогательные методы для работы с сессией с префиксом
     */
    protected function putSession($key, $value)
    {
        session([$this->sessionKey($key) => $value]);
    }
    
    protected function getSession($key, $default = null)
    {
        return session($this->sessionKey($key), $default);
    }
    
    protected function hasSession($key)
    {
        return session()->has($this->sessionKey($key));
    }

    public function index()
    {
        // При обновлении страницы сбрасываем режим просмотра
        if (!$this->getSession('results') && !$this->getSession('optimizationResult')) {
            session()->forget($this->sessionKey('viewing_history'));
        }
        
        $input = $this->getSession('input', []);
        $inputOriginal = $this->getSession('input_original', []);
        $results = $this->getSession('results');
        $optimizationResult = $this->getSession('optimizationResult');
        $viewingHistory = $this->getSession('viewing_history', false);
        
        // Загружаем историю вычислений текущего пользователя
        $history = Ho3CalculationHistory::byUser(Auth::id())
            ->orderBy('is_favorite', 'desc')
            ->orderBy('created_at', 'desc')
            ->get();
        
        // Для каждой записи истории подгружаем активные ссылки
        foreach ($history as $item) {
            $item->active_share_links = $item->activeShareLinks()->get();
        }
        
        // Загружаем экспериментальные данные из сессии
        $experimentalData = $this->getSession('current_experimental_data', []);
        $experimentalInput = $this->getSession('experimentalInput', []);
        
        // Если есть загруженные данные из истории, но нет experimentalInput,
        // проверяем, есть ли экспериментальные данные в истории для текущего input
        if (empty($experimentalData) && !empty($input)) {
            $historyItem = Ho3CalculationHistory::byUser(Auth::id())
                ->where('input_data', json_encode($input))
                ->latest()
                ->first();
                
            if ($historyItem && !empty($historyItem->experimental_data)) {
                $experimentalData = $historyItem->experimental_data;
                $experimentalInput = [
                    'exp_temperatures' => [],
                    'exp_values' => []
                ];
                foreach ($experimentalData as $data) {
                    $experimentalInput['exp_temperatures'][] = $data['temperature'];
                    $experimentalInput['exp_values'][] = $data['value'];
                }
                
                $this->putSession('experimentalInput', $experimentalInput);
                $this->putSession('current_experimental_data', $experimentalData);
            }
        }
        
        return view('calculatorHO3+.index', [
            'results' => $results,
            'optimizationResult' => $optimizationResult,
            'experimentalData' => $experimentalData,
            'experimentalInput' => $experimentalInput,
            'input' => $input,
            'inputOriginal' => $inputOriginal,
            'history' => $history,
            'viewingHistory' => $viewingHistory
        ]);
    }

    /**
     * ==========================================
     * МЕТОДЫ ДЛЯ РАБОТЫ С ПУБЛИЧНЫМИ ССЫЛКАМИ
     * ==========================================
     */

    /**
     * Создать публичную ссылку для вычисления
     */
    public function createShareLink(Request $request, Ho3CalculationHistory $history)
    {
        // Проверяем права
        if ($history->user_id !== Auth::id()) {
            if ($request->wantsJson()) {
                return response()->json(['error' => 'Unauthorized'], 403);
            }
            return redirect()->route('calculator.Ho3+.index')
                ->with('error', 'You do not have permission to share this calculation.');
        }

        $validated = $request->validate([
            'title' => 'nullable|string|max:255',
            'description' => 'nullable|string|max:1000',
            'expires_in_days' => 'nullable|integer|min:1|max:365',
            'password' => 'nullable|string|min:4|max:50',
            'allow_comments' => 'boolean',
            'allow_download' => 'boolean',
            'allow_copy_to_account' => 'boolean'
        ]);

        $shareLink = CalculationShareLink::createShareLink($history, [
            'title' => $validated['title'] ?? null,
            'description' => $validated['description'] ?? null,
            'expires_in_days' => $validated['expires_in_days'] ?? 30,
            'password' => $validated['password'] ?? null,
            'allow_comments' => $validated['allow_comments'] ?? true,
            'allow_download' => $validated['allow_download'] ?? true,
            'allow_copy_to_account' => $validated['allow_copy_to_account'] ?? true
        ]);

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'share_url' => $shareLink->url,
                'share_token' => $shareLink->token,
                'expires_at' => $shareLink->expires_at,
                'stats_url' => $shareLink->stats_url,
                'views' => $shareLink->views
            ]);
        }

        return redirect()->route('calculator.Ho3+.index')
            ->with('success', 'Share link created successfully! URL: ' . $shareLink->url);
    }

    /**
     * Получить все ссылки для вычисления
     */
    public function getShareLinks(Ho3CalculationHistory $history)
    {
        if ($history->user_id !== Auth::id()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $links = $history->shareLinks()
            ->with('creator')
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json([
            'links' => $links->map(function($link) {
                return [
                    'id' => $link->id,
                    'token' => $link->token,
                    'title' => $link->display_title,
                    'description' => $link->description,
                    'url' => $link->url,
                    'stats_url' => $link->stats_url,
                    'views' => $link->views,
                    'unique_views' => $link->unique_views,
                    'is_active' => $link->is_active,
                    'created_at' => $link->created_at,
                    'expires_at' => $link->expires_at,
                    'has_password' => !is_null($link->password_hash),
                    'allow_comments' => $link->allow_comments,
                    'allow_download' => $link->allow_download,
                    'allow_copy_to_account' => $link->allow_copy_to_account
                ];
            })
        ]);
    }

    /**
     * Получить информацию об одной ссылке
     */
    public function getShareLinkInfo(CalculationShareLink $shareLink)
    {
        // Проверяем, что ссылка принадлежит вычислению текущего пользователя
        $calculable = $shareLink->calculable;
        if ($calculable->user_id !== Auth::id()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        return response()->json([
            'success' => true,
            'id' => $shareLink->id,
            'token' => $shareLink->token,
            'title' => $shareLink->display_title,
            'description' => $shareLink->description,
            'url' => $shareLink->url,
            'stats_url' => $shareLink->stats_url,
            'views' => $shareLink->views,
            'unique_views' => $shareLink->unique_views,
            'is_active' => $shareLink->is_active,
            'created_at' => $shareLink->created_at,
            'expires_at' => $shareLink->expires_at,
            'last_accessed_at' => $shareLink->last_accessed_at,
            'has_password' => !is_null($shareLink->password_hash),
            'allow_comments' => $shareLink->allow_comments,
            'allow_download' => $shareLink->allow_download,
            'allow_copy_to_account' => $shareLink->allow_copy_to_account
        ]);
    }

    /**
     * Отозвать публичную ссылку (деактивировать, но не удалять)
     */
    public function revokeShareLink(CalculationShareLink $shareLink)
    {
        // Проверяем, что ссылка принадлежит текущему пользователю
        $calculable = $shareLink->calculable;
        if ($calculable->user_id !== Auth::id()) {
            if (request()->wantsJson()) {
                return response()->json(['error' => 'Unauthorized'], 403);
            }
            return redirect()->route('calculator.Ho3+.index')
                ->with('error', 'Unauthorized');
        }

        $shareLink->revoke();

        if (request()->wantsJson()) {
            return response()->json([
                'success' => true,
                'is_active' => false,
                'message' => 'Share link revoked successfully'
            ]);
        }

        return redirect()->route('calculator.Ho3+.index')
            ->with('success', 'Share link revoked successfully.');
    }

    /**
     * Активировать ранее отозванную ссылку
     */
    public function activateShareLink(CalculationShareLink $shareLink)
    {
        $calculable = $shareLink->calculable;
        if ($calculable->user_id !== Auth::id()) {
            if (request()->wantsJson()) {
                return response()->json(['error' => 'Unauthorized'], 403);
            }
            return redirect()->route('calculator.Ho3+.index')
                ->with('error', 'Unauthorized');
        }

        $shareLink->activate();

        if (request()->wantsJson()) {
            return response()->json([
                'success' => true,
                'is_active' => true,
                'message' => 'Share link activated successfully'
            ]);
        }

        return redirect()->route('calculator.Ho3+.index')
            ->with('success', 'Share link activated successfully.');
    }

    /**
     * Продлить срок действия ссылки
     */
    public function extendShareLink(Request $request, CalculationShareLink $shareLink)
    {
        $request->validate([
            'additional_days' => 'required|integer|min:1|max:365'
        ]);

        $calculable = $shareLink->calculable;
        if ($calculable->user_id !== Auth::id()) {
            if ($request->wantsJson()) {
                return response()->json(['error' => 'Unauthorized'], 403);
            }
            return redirect()->route('calculator.Ho3+.index')
                ->with('error', 'Unauthorized');
        }

        $shareLink->extendExpiration($request->additional_days);

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'expires_at' => $shareLink->expires_at,
                'message' => 'Share link extended successfully'
            ]);
        }

        return redirect()->route('calculator.Ho3+.index')
            ->with('success', 'Share link extended until ' . $shareLink->expires_at->format('d.m.Y H:i'));
    }

/**
 * Обновить публичную ссылку
 */
public function updateShareLink(Request $request, CalculationShareLink $shareLink)
{
    // Проверяем, что ссылка принадлежит текущему пользователю
    $calculable = $shareLink->calculable;
    if ($calculable->user_id !== Auth::id()) {
        return response()->json(['error' => 'Unauthorized'], 403);
    }

    try {
        // Обновляем название
        if ($request->has('title')) {
            $shareLink->title = $request->input('title');
        }
        
        // Обновляем описание
        if ($request->has('description')) {
            $shareLink->description = $request->input('description');
        }
        
        // Обновляем разрешение на копирование
        if ($request->has('allow_copy_to_account')) {
            $shareLink->allow_copy_to_account = filter_var($request->input('allow_copy_to_account'), FILTER_VALIDATE_BOOLEAN);
        }

        // Обновляем срок действия
        if ($request->has('expires_in_days')) {
            $expiresInDays = $request->input('expires_in_days');
            if ($expiresInDays && is_numeric($expiresInDays) && $expiresInDays > 0) {
                $shareLink->expires_at = now()->addDays((int)$expiresInDays);
            } else {
                $shareLink->expires_at = null;
            }
        }

        // Обновляем пароль
        if ($request->has('password')) {
            $newPassword = $request->input('password');
            
            // Если передан специальный маркер удаления пароля
            if ($newPassword === '' || $newPassword === null || $newPassword === 'DELETE_PASSWORD') {
                $shareLink->password_hash = null;
            }
            // Если передан новый пароль (не пустой)
            elseif (!empty($newPassword) && strlen($newPassword) >= 4) {
                $shareLink->password_hash = Hash::make($newPassword);
            }
            // Если передан пустой пароль - ничего не меняем
            // (оставляем текущий пароль)
        }

        $shareLink->save();

        \Log::info('Share link updated successfully', [
            'link_id' => $shareLink->id,
            'has_password' => !is_null($shareLink->password_hash),
            'expires_at' => $shareLink->expires_at
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Ссылка успешно обновлена',
            'data' => [
                'has_password' => !is_null($shareLink->password_hash)
            ]
        ]);
        
    } catch (\Exception $e) {
        \Log::error('Update share link error: ' . $e->getMessage(), [
            'link_id' => $shareLink->id,
            'file' => $e->getFile(),
            'line' => $e->getLine()
        ]);
        
        return response()->json([
            'success' => false,
            'error' => 'Ошибка сервера: ' . $e->getMessage()
        ], 500);
    }
}

/**
 * Удалить публичную ссылку (полностью из базы данных)
 */
public function deleteShareLink(CalculationShareLink $shareLink)
{
    // Проверяем, что ссылка принадлежит текущему пользователю
    $calculable = $shareLink->calculable;
    if ($calculable->user_id !== Auth::id()) {
        if (request()->wantsJson()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }
        return redirect()->route('calculator.Ho3+.index')
            ->with('error', 'Unauthorized');
    }

    // Полностью удаляем ссылку из базы данных
    $shareLink->forceDelete();

    if (request()->wantsJson()) {
        return response()->json([
            'success' => true,
            'message' => 'Share link deleted successfully'
        ]);
    }

    return redirect()->route('calculator.Ho3+.index')
        ->with('success', 'Share link deleted successfully.');
}

    /**
     * Получить статистику по всем ссылкам вычисления
     */
    public function getShareStats(Ho3CalculationHistory $history)
    {
        if ($history->user_id !== Auth::id()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $links = $history->shareLinks()->get();
        
        $totalViews = $links->sum('views');
        $totalUniqueViews = $links->sum('unique_views');
        $activeLinksCount = $links->where('is_active', true)->count();
        $expiredLinksCount = $links->filter(function($link) {
            return $link->expires_at && $link->expires_at->isPast();
        })->count();

        return response()->json([
            'success' => true,
            'total_links' => $links->count(),
            'active_links' => $activeLinksCount,
            'expired_links' => $expiredLinksCount,
            'total_views' => $totalViews,
            'total_unique_views' => $totalUniqueViews,
            'links' => $links->map(function($link) {
                return [
                    'id' => $link->id,
                    'title' => $link->display_title,
                    'url' => $link->url,
                    'views' => $link->views,
                    'unique_views' => $link->unique_views,
                    'is_active' => $link->is_active,
                    'created_at' => $link->created_at,
                    'expires_at' => $link->expires_at
                ];
            })
        ]);
    }

    /**
     * ==========================================
     * ОСТАЛЬНЫЕ МЕТОДЫ (СУЩЕСТВУЮЩИЕ)
     * ==========================================
     */

    /**
     * Обновить имя вычисления в истории
     */
    public function updateHistoryName(Request $request, Ho3CalculationHistory $history)
    {
        if ($history->user_id !== Auth::id()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $request->validate([
            'name' => 'required|string|max:255'
        ]);

        $history->update([
            'name' => $request->name
        ]);

        return response()->json([
            'success' => true,
            'name' => $history->name
        ]);
    }

    public function calculate(Request $request)
    {
        session()->forget($this->sessionKey('viewing_history'));
        
        try {
            $validated = $this->validateCalculationData($request);
            
            $originalInput = [
                'i7n' => $validated['i7n'] ?? null,
                'i7e' => $validated['i7e'] ?? [],
                'i8n' => $validated['i8n'] ?? null,
                'i8e' => $validated['i8e'] ?? [],
                'kc' => $validated['kc'] ?? [],
                'fav' => $validated['fav'] ?? null,
            ];
            
            $this->putSession('input_original', $originalInput);
            
            $expTemperatures = $request->input('exp_temperatures', []);
            $expValues = $request->input('exp_values', []);
            
            $experimentalData = [];
            if (!empty($expTemperatures) && !empty($expValues)) {
                foreach ($expTemperatures as $index => $temp) {
                    if (isset($expValues[$index]) && $temp !== '' && $expValues[$index] !== '') {
                        $experimentalData[] = [
                            'temperature' => (float)$temp,
                            'value' => (float)$expValues[$index]
                        ];
                    }
                }
                
                usort($experimentalData, function($a, $b) {
                    return $a['temperature'] <=> $b['temperature'];
                });
            }
            
            $results = $this->calculatorService->calculate($validated);
            
            $this->saveToHistory(
                $validated, 
                $results, 
                null, 
                $request->input('calculation_name'),
                $experimentalData
            );
            
            $this->storeExperimentalDataInSession($experimentalData);
            
            $this->putSession('input', $validated);
            $this->putSession('results', $results);
            
            return redirect()->route('calculator.Ho3+.index')
                ->with('success', 'Calculation completed successfully!');
                
        } catch (\Illuminate\Validation\ValidationException $e) {
            $this->saveUserInputToSession($request);
            
            return redirect()->route('calculator.Ho3+.index')
                ->withErrors($e->validator)
                ->withInput($request->all());
        } catch (\Exception $e) {
            $this->saveUserInputToSession($request);
            
            return redirect()->route('calculator.Ho3+.index')
                ->with('error', 'Calculation error: ' . $e->getMessage())
                ->withInput($request->all());
        }
    }

    public function optimize(Request $request)
    {
        set_time_limit(300);
        ini_set('max_execution_time', 300);

        session()->forget($this->sessionKey('viewing_history'));
        
        try {
            $validated = $this->validateOptimizationData($request);
            
            $originalInput = [
                'i7n' => $validated['i7n'] ?? null,
                'i7e' => $validated['i7e'] ?? [],
                'i8n' => $validated['i8n'] ?? null,
                'i8e' => $validated['i8e'] ?? [],
                'kc' => $validated['kc'] ?? [],
                'fav' => $validated['fav'] ?? null,
            ];
            
            $this->putSession('input_original', $originalInput);
            
            $expTemperatures = $request->input('exp_temperatures', []);
            $expValues = $request->input('exp_values', []);
            
            $experimentalData = [];
            if (!empty($expTemperatures) && !empty($expValues)) {
                foreach ($expTemperatures as $index => $temp) {
                    if (isset($expValues[$index]) && $temp !== '' && $temp !== null && $expValues[$index] !== '' && $expValues[$index] !== null) {
                        $experimentalData[] = [
                            'temperature' => (float)$temp,
                            'value' => (float)$expValues[$index]
                        ];
                    }
                }
            }
            
            if (empty($experimentalData)) {
                $this->saveUserInputToSession($request);
                
                return redirect()->route('calculator.Ho3+.index')
                    ->with('error', 'Please add at least one experimental data point in the "Experimental Data" tab before optimizing.')
                    ->withInput($request->all());
            }
            
            $result = $this->optimizationService->optimize($validated, $experimentalData);
            
            if (isset($result['error'])) {
                $this->saveUserInputToSession($request);
                
                return redirect()->route('calculator.Ho3+.index')
                    ->with('error', $result['error'])
                    ->withInput($validated);
            }
            
            $optimizedInputData = $validated;
            $optimizedInputData['kc'] = $result['kc'];
            $optimizedInputData['fav'] = $result['fav'];
            
            $calculationResults = $this->calculatorService->calculate($optimizedInputData);
            
            $this->saveToHistory(
                $validated,
                $calculationResults,
                $result,
                $request->input('calculation_name'),
                $experimentalData,
                $optimizedInputData
            );
            
            $this->storeExperimentalDataInSession($experimentalData);
            
            $this->putSession('input', $optimizedInputData);
            $this->putSession('results', $calculationResults);
            $this->putSession('optimizationResult', $result);
            $this->putSession('optimization_applied', true);
            
            return redirect()->route('calculator.Ho3+.index')
                ->with('success', 'Optimization completed successfully! Optimized parameters have been loaded into the input fields and saved to history.');
                
        } catch (\Illuminate\Validation\ValidationException $e) {
            $this->saveUserInputToSession($request);
            
            return redirect()->route('calculator.Ho3+.index')
                ->withErrors($e->validator)
                ->withInput($request->all());
        } catch (\Exception $e) {
            $this->saveUserInputToSession($request);
            
            return redirect()->route('calculator.Ho3+.index')
                ->with('error', 'Optimization error: ' . $e->getMessage())
                ->withInput($request->all());
        }
    }

    public function optimizeAjax(Request $request)
    {
        set_time_limit(300);
        ini_set('max_execution_time', 300);
        
        try {
            $validated = $this->validateOptimizationData($request);
            
            $originalInput = [
                'i7n' => $validated['i7n'] ?? null,
                'i7e' => $validated['i7e'] ?? [],
                'i8n' => $validated['i8n'] ?? null,
                'i8e' => $validated['i8e'] ?? [],
                'kc' => $validated['kc'] ?? [],
                'fav' => $validated['fav'] ?? null,
            ];
            $this->putSession('input_original', $originalInput);
            
            $expTemperatures = $request->input('exp_temperatures', []);
            $expValues = $request->input('exp_values', []);
            
            $experimentalData = [];
            foreach ($expTemperatures as $index => $temp) {
                if (isset($expValues[$index]) && $temp !== '' && $temp !== null 
                    && $expValues[$index] !== '' && $expValues[$index] !== null) {
                    $experimentalData[] = [
                        'temperature' => (float)$temp,
                        'value' => (float)$expValues[$index]
                    ];
                }
            }
            
            if (empty($experimentalData)) {
                return response()->json(['success' => false, 'error' => 'Please add at least one experimental data point.']);
            }
            
            $result = $this->optimizationService->optimize($validated, $experimentalData);
            
            if (isset($result['error'])) {
                return response()->json(['success' => false, 'error' => $result['error']]);
            }
            
            $optimizedInputData = $validated;
            $optimizedInputData['kc'] = $result['kc'];
            $optimizedInputData['fav'] = $result['fav'];
            
            $calculationResults = $this->calculatorService->calculate($optimizedInputData);
            
            $this->saveToHistory(
                $validated,
                $calculationResults,
                $result,
                $request->input('calculation_name'),
                $experimentalData,
                $optimizedInputData
            );
            
            $this->storeExperimentalDataInSession($experimentalData);
            
            $this->putSession('input', $optimizedInputData);
            $this->putSession('results', $calculationResults);
            $this->putSession('optimizationResult', $result);
            $this->putSession('optimization_applied', true);
            
            return response()->json(['success' => true, 'redirect' => route('calculator.Ho3+.index')]);
            
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json(['success' => false, 'error' => implode('<br>', $e->validator->errors()->all())]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'error' => 'Optimization error: ' . $e->getMessage()]);
        }
    }

    public function optimizeStep(Request $request)
    {
        set_time_limit(55);
        ini_set('max_execution_time', 55);
        
        try {
            $validated = $this->validateOptimizationData($request);
            
            $expTemperatures = $request->input('exp_temperatures', []);
            $expValues = $request->input('exp_values', []);
            $experimentalData = [];
            
            foreach ($expTemperatures as $index => $temp) {
                if (isset($expValues[$index]) && $temp !== '' && $temp !== null
                    && $expValues[$index] !== '' && $expValues[$index] !== null) {
                    $experimentalData[] = [
                        'temperature' => (float)$temp,
                        'value' => (float)$expValues[$index]
                    ];
                }
            }
            
            if (empty($experimentalData)) {
                return response()->json(['success' => false, 'error' => 'No experimental data.']);
            }
            
            $this->optimizationService->setExperimentalData($experimentalData);
            
            $bestValue = $request->input('best_value', INF);
            $bestParams = $request->input('best_params', null);
            $step = $request->input('step', 0);
            $totalSteps = $request->input('total_steps', 5);
            
            $y0 = $this->optimizationService->getInitialVectorPublic($validated);
            
            $start = $y0;
            if ($step > 0) {
                foreach ($start as &$val) {
                    $val *= 1.0 + (mt_rand() / mt_getrandmax() - 0.5) * 0.4;
                }
            }
            
            if ($bestParams !== null && $step > 0 && mt_rand(0, 1)) {
                $start = $bestParams;
                foreach ($start as &$val) {
                    $val *= 1.0 + (mt_rand() / mt_getrandmax() - 0.5) * 0.2;
                }
            }
            
            $result = $this->optimizationService->runSingleDescent($start, $validated);
            
            if ($result && $result['value'] < $bestValue) {
                $bestValue = $result['value'];
                $bestParams = $result['params'];
            }
            
            $nextStep = $step + 1;
            $isFinished = $nextStep >= $totalSteps;
            
            if ($isFinished && $bestParams !== null) {
                $optimizedKc = array_map(fn($val) => exp($val), array_slice($bestParams, 0, $validated['i7n'] - 1));
                array_unshift($optimizedKc, 1.0);
                $optimizedFav = exp($bestParams[count($bestParams) - 1]);
                
                $optimizedInputData = $validated;
                $optimizedInputData['kc'] = $optimizedKc;
                $optimizedInputData['fav'] = $optimizedFav;
                
                $calculationResults = $this->calculatorService->calculate($optimizedInputData);
                
                $this->optimizationService->setExperimentalData($experimentalData);
                $qualityMetrics = $this->optimizationService->calculateQualityMetrics($optimizedInputData);
                
                $finalResult = [
                    'kc' => $optimizedKc,
                    'fav' => $optimizedFav,
                    'objective' => $bestValue,
                    'ssd' => $qualityMetrics['ssd'],
                    'rmse' => $qualityMetrics['rmse'],
                    'mse' => $qualityMetrics['mse'],
                    'mae' => $qualityMetrics['mae'],
                    'r_squared' => $qualityMetrics['r_squared'],
                    'count' => $qualityMetrics['count'],
                    'success' => true
                ];
                
                $originalInput = [
                    'i7n' => $validated['i7n'] ?? null,
                    'i7e' => $validated['i7e'] ?? [],
                    'i8n' => $validated['i8n'] ?? null,
                    'i8e' => $validated['i8e'] ?? [],
                    'kc'  => $validated['kc'] ?? [],
                    'fav' => $validated['fav'] ?? null,
                ];
                
                $this->putSession('input_original', $originalInput);
                
                $this->saveToHistory(
                    $validated,
                    $calculationResults,
                    $finalResult,
                    $request->input('calculation_name'),
                    $experimentalData,
                    $optimizedInputData
                );
                
                $this->storeExperimentalDataInSession($experimentalData);
                
                $this->putSession('input', $optimizedInputData);
                $this->putSession('results', $calculationResults);
                $this->putSession('optimizationResult', $finalResult);
                $this->putSession('optimization_applied', true);
                
                return response()->json([
                    'success' => true,
                    'finished' => true,
                    'step' => $nextStep,
                    'total_steps' => $totalSteps,
                    'redirect' => route('calculator.Ho3+.index')
                ]);
            }
            
            return response()->json([
                'success' => true,
                'finished' => false,
                'step' => $nextStep,
                'total_steps' => $totalSteps,
                'best_value' => $bestValue,
                'best_params' => $bestParams,
            ]);
            
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'error' => $e->getMessage()]);
        }
    }

    /**
     * Сохранить ввод пользователя в сессию для восстановления при ошибках
     */
    private function saveUserInputToSession(Request $request)
    {
        $inputData = $request->all();
        
        if (isset($inputData['kc']) && is_array($inputData['kc']) && isset($inputData['kc'][0])) {
            $inputData['kc'][0] = 1.0;
        }
        
        if (!$this->hasSession('input_original')) {
            $originalInput = [
                'i7n' => $inputData['i7n'] ?? null,
                'i7e' => $inputData['i7e'] ?? [],
                'i8n' => $inputData['i8n'] ?? null,
                'i8e' => $inputData['i8e'] ?? [],
                'kc' => $inputData['kc'] ?? [],
                'fav' => $inputData['fav'] ?? null,
            ];
            $this->putSession('input_original', $originalInput);
        }
        
        $experimentalInput = [
            'exp_temperatures' => $request->input('exp_temperatures', []),
            'exp_values' => $request->input('exp_values', [])
        ];
        
        $this->putSession('input', $inputData);
        $this->putSession('experimentalInput', $experimentalInput);
        $this->putSession('current_experimental_data', $this->prepareExperimentalData($request));
        $this->putSession('validation_error_state', true);
    }

    /**
     * Подготовить экспериментальные данные из запроса
     */
    private function prepareExperimentalData(Request $request)
    {
        $expTemperatures = $request->input('exp_temperatures', []);
        $expValues = $request->input('exp_values', []);
        
        $experimentalData = [];
        if (!empty($expTemperatures) && !empty($expValues)) {
            foreach ($expTemperatures as $index => $temp) {
                if (isset($expValues[$index]) && $temp !== '' && $temp !== null && $expValues[$index] !== '' && $expValues[$index] !== null) {
                    $experimentalData[] = [
                        'temperature' => (float)$temp,
                        'value' => (float)$expValues[$index]
                    ];
                }
            }
            
            usort($experimentalData, function($a, $b) {
                return $a['temperature'] <=> $b['temperature'];
            });
        }
        
        return $experimentalData;
    }

    /**
     * Сохранить экспериментальные данные в сессию для отображения
     */
    private function storeExperimentalDataInSession($experimentalData)
    {
        if (!empty($experimentalData)) {
            $experimentalInput = [
                'exp_temperatures' => [],
                'exp_values' => []
            ];
            
            foreach ($experimentalData as $data) {
                $experimentalInput['exp_temperatures'][] = $data['temperature'];
                $experimentalInput['exp_values'][] = $data['value'];
            }
            
            $this->putSession('experimentalInput', $experimentalInput);
            $this->putSession('current_experimental_data', $experimentalData);
        } else {
            session()->forget([$this->sessionKey('experimentalInput'), $this->sessionKey('current_experimental_data')]);
        }
    }

    /**
     * Загрузить вычисление из истории
     */
    public function loadFromHistory(Ho3CalculationHistory $history)
    {
        if ($history->user_id !== Auth::id()) {
            return redirect()->route('calculator.Ho3+.index')
                ->with('error', 'You do not have permission to load this calculation.');
        }

        $inputData = $history->optimized_input_data ?? $history->input_data;
        $originalInputData = $history->input_data;
        $results = $history->results;
        $optimizationResult = $history->optimization_results;
        $isOptimized = !is_null($history->optimized_input_data);
        
        $this->putSession('input', $inputData);
        $this->putSession('input_original', $originalInputData);
        $this->putSession('results', $results);
        $this->putSession('optimizationResult', $optimizationResult);
        
        if ($isOptimized) {
            $this->putSession('optimization_applied', true);
        } else {
            session()->forget($this->sessionKey('optimization_applied'));
        }
        
        if (!empty($history->experimental_data)) {
            $experimentalData = $history->experimental_data;
            
            usort($experimentalData, function($a, $b) {
                return $a['temperature'] <=> $b['temperature'];
            });
            
            $experimentalInput = [
                'exp_temperatures' => [],
                'exp_values' => []
            ];
            
            foreach ($experimentalData as $data) {
                $experimentalInput['exp_temperatures'][] = $data['temperature'];
                $experimentalInput['exp_values'][] = $data['value'];
            }
            
            $this->putSession('experimentalInput', $experimentalInput);
            $this->putSession('current_experimental_data', $experimentalData);
        } else {
            session()->forget([$this->sessionKey('experimentalInput'), $this->sessionKey('current_experimental_data')]);
        }

        $this->putSession('viewing_history', true);

        $createdDate = $history->created_at->format('d.m.Y H:i');
        $calculationName = $history->name ?? 'Untitled';
        
        $infoMessage = "You are viewing a calculation named: \"{$calculationName}\". Created: {$createdDate}<br>";
        
        if ($isOptimized) {
            $infoMessage .= "✓ This is an OPTIMIZED calculation.<br>";
            $infoMessage .= "Optimized parameters are loaded and shown in the input fields.<br>";
            if ($optimizationResult && isset($optimizationResult['success']) && $optimizationResult['success']) {
                $infoMessage .= "Optimization results are available on the 'Optimization Results' tab.<br>";
            }
        } else {
            $infoMessage .= "✓ This is a standard calculation.<br>";
        }
        
        $infoMessage .= "Click the \"Edit Mode\" button to modify the data and recalculate the result.";

        return redirect()->route('calculator.Ho3+.index')
            ->with('info', $infoMessage);
    }

    /**
     * Снять режим просмотра истории (сделать поля активными)
     */
    public function exitHistoryView(Request $request)
    {
        $currentInput = $this->getSession('input', []);
        $currentOriginal = $this->getSession('input_original', []);
        $currentExperimental = $this->getSession('experimentalInput', []);
        $currentExperimentalData = $this->getSession('current_experimental_data', []);
        
        session()->forget($this->sessionKey('viewing_history'));
        
        $this->putSession('input', $currentInput);
        $this->putSession('input_original', $currentOriginal);
        $this->putSession('experimentalInput', $currentExperimental);
        $this->putSession('current_experimental_data', $currentExperimentalData);
        
        return redirect()->route('calculator.Ho3+.index')
            ->with('success', 'Edit mode activated. You can now modify the data.');
    }

    /**
     * Переключить статус избранного
     */
    public function toggleFavorite(Ho3CalculationHistory $history)
    {
        if ($history->user_id !== Auth::id()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $history->toggleFavorite();

        return response()->json([
            'success' => true,
            'is_favorite' => $history->is_favorite
        ]);
    }

    /**
     * Удалить запись из истории
     */
    public function deleteHistory(Ho3CalculationHistory $history)
    {
        if ($history->user_id !== Auth::id()) {
            return redirect()->route('calculator.Ho3+.index')
                ->with('error', 'You do not have permission to delete this calculation.');
        }

        $history->delete();

        return redirect()->route('calculator.Ho3+.index')
            ->with('success', 'Calculation deleted from history.');
    }

    /**
     * Сохранить вычисление в историю
     */
    private function saveToHistory($inputData, $results, $optimizationResults = null, $name = null, $experimentalData = null, $optimizedInputData = null)
    {
        if (!Auth::check()) {
            return;
        }

        if (!$name) {
            $name = 'Calculation ' . now()->format('Y-m-d H:i');
        }

        if ($experimentalData === null && $this->hasSession('current_experimental_data')) {
            $experimentalData = $this->getSession('current_experimental_data');
        }

        if (isset($inputData['kc']) && isset($inputData['kc'][0])) {
            $inputData['kc'][0] = 1.0;
        }
        
        if ($optimizedInputData !== null && isset($optimizedInputData['kc']) && isset($optimizedInputData['kc'][0])) {
            $optimizedInputData['kc'][0] = 1.0;
        }

        $historyData = [
            'user_id' => Auth::id(),
            'name' => $name,
            'input_data' => $inputData,
            'results' => $results,
            'optimization_results' => $optimizationResults,
            'experimental_data' => !empty($experimentalData) ? $experimentalData : null,
            'is_favorite' => false
        ];
        
        if ($optimizedInputData !== null) {
            $historyData['optimized_input_data'] = $optimizedInputData;
        }

        Ho3CalculationHistory::create($historyData);
    }

    private function validateCalculationData(Request $request)
    {
        $messages = [
            'i7n.required' => 'Number of I7 energy levels is required.',
            'i7n.integer' => 'Number of I7 energy levels must be an integer.',
            'i7n.min' => 'Number of I7 energy levels must be at least 1.',
            'i7n.max' => 'Number of I7 energy levels cannot exceed 12.',
            
            'i8n.required' => 'Number of I8 energy levels is required.',
            'i8n.integer' => 'Number of I8 energy levels must be an integer.',
            'i8n.min' => 'Number of I8 energy levels must be at least 1.',
            'i8n.max' => 'Number of I8 energy levels cannot exceed 12.',
            
           'i7e.required' => 'Energies for I7 multiplet are required.',
            'i7e.*.numeric' => 'Energy value must be numbers',
            'i7e.*.required' => 'Energy value required',
            
            'i8e.required' => 'Energies for I8 multiplet are required',
            'i8e.*.numeric' => 'Energy value must be numbers',
            'i8e.*.required' => 'Energy value required',
            
            'kc.required' => 'Kc coefficients are required',
            'kc.*.numeric' => 'Kc coefficient must be numbers',
            'kc.*.required' => 'Kc coefficient required',
            
            'fav.required' => 'Fav parameter is required',
            'fav.numeric' => 'Fav must be a number',
        ];

        $rules = [
            'fav' => 'required|numeric',
            'i7n' => 'required|integer|min:1|max:12',
            'i8n' => 'required|integer|min:1|max:12',
            'i7e' => 'required|array|min:1',
            'i7e.*' => 'required|numeric',
            'i8e' => 'required|array|min:1',
            'i8e.*' => 'required|numeric',
            'kc' => 'required|array|min:1',
            'kc.*' => 'required|numeric',
            'calculation_name' => 'nullable|string|max:255',
            'exp_temperatures' => 'nullable|array',
            'exp_temperatures.*' => 'nullable|numeric',
            'exp_values' => 'nullable|array',
            'exp_values.*' => 'nullable|numeric',
        ];

        $validator = validator($request->all(), $rules, $messages);
        
        if ($validator->fails()) {
            session()->flash('validation_errors', $validator->errors());
            throw new \Illuminate\Validation\ValidationException($validator);
        }
        
        return $validator->validated();
    }

    private function validateOptimizationData(Request $request)
    {
        $messages = [
            'i7n.required' => 'Number of I7 energy levels is required.',
            'i7n.integer' => 'Number of I7 energy levels must be an integer.',
            
            'i8n.required' => 'Number of I8 energy levels is required.',
            'i8n.integer' => 'Number of I8 energy levels must be an integer.',
            
            'i7e.required' => 'Energies for I7 multiplet are required.',
            'i7e.*.numeric' => 'Energy value must be numbers',
            'i7e.*.required' => 'Energy value required',
            
            'i8e.required' => 'Energies for I8 multiplet are required',
            'i8e.*.numeric' => 'Energy value must be numbers',
            'i8e.*.required' => 'Energy value required',
            
            'kc.required' => 'Kc coefficients are required',
            'kc.*.numeric' => 'Kc coefficient must be numbers',
            'kc.*.required' => 'Kc coefficient required',
            
            'fav.required' => 'Fav parameter is required',
            'fav.numeric' => 'Fav must be a number',
            
            'exp_temperatures.*.numeric' => 'Temperature must be a number.',
            'exp_values.*.numeric' => 'Lifetime value must be a number.',
        ];

        $rules = [
            'fav' => 'required|numeric',
            'i7n' => 'required|integer',
            'i8n' => 'required|integer',
            'i7e' => 'required|array|min:1',
            'i7e.*' => 'required|numeric',
            'i8e' => 'required|array|min:1',
            'i8e.*' => 'required|numeric',
            'kc' => 'required|array|min:1',
            'kc.*' => 'required|numeric',
            'calculation_name' => 'nullable|string|max:255',
            'exp_temperatures' => 'nullable|array',
            'exp_temperatures.*' => 'nullable|numeric',
            'exp_values' => 'nullable|array',
            'exp_values.*' => 'nullable|numeric',
        ];

        $validator = validator($request->all(), $rules, $messages);
        
        if ($validator->fails()) {
            session()->flash('validation_errors', $validator->errors());
            throw new \Illuminate\Validation\ValidationException($validator);
        }
        
        $validated = $validator->validated();
        
        if (isset($validated['exp_temperatures']) || isset($validated['exp_values'])) {
            $temperatures = $validated['exp_temperatures'] ?? [];
            $values = $validated['exp_values'] ?? [];
            
            $filteredTemps = array_filter($temperatures, function($value) {
                return $value !== null && $value !== '' && $value !== 'null';
            });
            $filteredValues = array_filter($values, function($value) {
                return $value !== null && $value !== '' && $value !== 'null';
            });
            
            $tempsCount = count($filteredTemps);
            $valuesCount = count($filteredValues);
            
            if ($tempsCount > 0 && $valuesCount > 0 && $tempsCount !== $valuesCount) {
                $errors = new \Illuminate\Support\MessageBag();
                $errors->add('exp_temperatures', 'The number of temperatures must match the number of lifetime values.');
                $errors->add('exp_values', 'The number of temperatures must match the number of lifetime values.');
                session()->flash('validation_errors', $errors);
                throw new \Illuminate\Validation\ValidationException($validator);
            }
            
            if ($tempsCount > 0 && $valuesCount === 0) {
                $errors = new \Illuminate\Support\MessageBag();
                $errors->add('exp_values', 'Lifetime values are required when temperatures are provided.');
                session()->flash('validation_errors', $errors);
                throw new \Illuminate\Validation\ValidationException($validator);
            }
            
            if ($tempsCount === 0 && $valuesCount > 0) {
                $errors = new \Illuminate\Support\MessageBag();
                $errors->add('exp_temperatures', 'Temperatures are required when lifetime values are provided.');
                session()->flash('validation_errors', $errors);
                throw new \Illuminate\Validation\ValidationException($validator);
            }
        }
        
        return $validated;
    }

    /**
     * Сохранить экспериментальные данные в сессию
     */
    public function saveExperimental(Request $request)
    {
        $this->putSession('experimentalInput', [
            'exp_temperatures' => $request->exp_temperatures,
            'exp_values' => $request->exp_values
        ]);
        
        return response()->json(['success' => true]);
    }
}
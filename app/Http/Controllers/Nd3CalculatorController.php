<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\Nd3CalculatorService;
use App\Services\Nd3OptimizationService;
use App\Models\Nd3CalculationHistory;
use App\Models\CalculationShareLink;
use Illuminate\Support\Facades\Auth;

class Nd3CalculatorController extends Controller
{
    protected $calculatorService;
    protected $optimizationService;
    
    // Префикс для ключей сессии
    protected $sessionPrefix = 'nd3_';

    public function __construct(
        Nd3CalculatorService $calculatorService,
        Nd3OptimizationService $optimizationService
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
        $history = Nd3CalculationHistory::byUser(Auth::id())
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
            $historyItem = Nd3CalculationHistory::byUser(Auth::id())
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
        
        return view('calculatorND3+.index', [
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
    public function createShareLink(Request $request, Nd3CalculationHistory $history)
    {
        // Проверяем права
        if ($history->user_id !== Auth::id()) {
            if ($request->wantsJson()) {
                return response()->json(['error' => 'Unauthorized'], 403);
            }
            return redirect()->route('calculatorND3+.index')
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

        return redirect()->route('calculatorND3+.index')
            ->with('success', 'Share link created successfully! URL: ' . $shareLink->url);
    }

    /**
     * Получить все ссылки для вычисления
     */
    public function getShareLinks(Nd3CalculationHistory $history)
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
            return redirect()->route('calculatorND3+.index')
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

        return redirect()->route('calculatorND3+.index')
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
            return redirect()->route('calculatorND3+.index')
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

        return redirect()->route('calculatorND3+.index')
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
            return redirect()->route('calculatorND3+.index')
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

        return redirect()->route('calculatorND3+.index')
            ->with('success', 'Share link extended until ' . $shareLink->expires_at->format('d.m.Y H:i'));
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
        return redirect()->route('calculatorND3+.index')
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

    return redirect()->route('calculatorND3+.index')
        ->with('success', 'Share link deleted successfully.');
}

    /**
     * Получить статистику по всем ссылкам вычисления
     */
    public function getShareStats(Nd3CalculationHistory $history)
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
     * ОСТАЛЬНЫЕ МЕТОДЫ (БЕЗ ИЗМЕНЕНИЙ)
     * ==========================================
     */

    /**
     * Обновить имя вычисления в истории
     */
    public function updateHistoryName(Request $request, Nd3CalculationHistory $history)
    {
        if ($history->user_id !== Auth::id()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $request->validate([
            'name' => 'required|string|max:255'
        ]);

        $history->update(['name' => $request->name]);

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
                'kc' => $validated['kc'] ?? [],
                'j9c' => $validated['j9c'] ?? null,
                'j11c' => $validated['j11c'] ?? null,
                'fe' => $validated['fe'] ?? [],
                'j9e' => $validated['j9e'] ?? [],
                'j11e' => $validated['j11e'] ?? [],
                'fn' => $validated['fn'] ?? null,
                'j9n' => $validated['j9n'] ?? null,
                'j11n' => $validated['j11n'] ?? null,
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
            
            return redirect()->route('calculatorND3+.index')
                ->with('success', 'Calculation completed successfully!');
                
        } catch (\Illuminate\Validation\ValidationException $e) {
            $this->saveUserInputToSession($request);
            return redirect()->route('calculatorND3+.index')
                ->withErrors($e->validator)
                ->withInput($request->all());
        } catch (\Exception $e) {
            $this->saveUserInputToSession($request);
            return redirect()->route('calculatorND3+.index')
                ->with('error', 'Calculation error: ' . $e->getMessage())
                ->withInput($request->all());
        }
    }

    public function optimize(Request $request)
    {
        session()->forget($this->sessionKey('viewing_history'));
        
        try {
            $validated = $this->validateOptimizationData($request);
            
            $originalInput = [
                'kc' => $validated['kc'] ?? [],
                'j9c' => $validated['j9c'] ?? null,
                'j11c' => $validated['j11c'] ?? null,
                'fe' => $validated['fe'] ?? [],
                'j9e' => $validated['j9e'] ?? [],
                'j11e' => $validated['j11e'] ?? [],
                'fn' => $validated['fn'] ?? null,
                'j9n' => $validated['j9n'] ?? null,
                'j11n' => $validated['j11n'] ?? null,
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
                return redirect()->route('calculatorND3+.index')
                    ->with('error', 'Please add at least one experimental data point in the "Experimental Data" tab before optimizing.')
                    ->withInput($request->all());
            }
            
            $result = $this->optimizationService->optimize($validated, $experimentalData);
            
            if (isset($result['error'])) {
                $this->saveUserInputToSession($request);
                return redirect()->route('calculatorND3+.index')
                    ->with('error', $result['error'])
                    ->withInput($validated);
            }
            
            $optimizedInputData = $validated;
            $optimizedInputData['j9c'] = $result['j9c'];
            $optimizedInputData['j11c'] = $result['j11c'];
            $optimizedInputData['kc'] = $result['kc'];
            
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
            
            return redirect()->route('calculatorND3+.index')
                ->with('success', 'Optimization completed successfully! Optimized parameters have been loaded into the input fields and saved to history.');
                
        } catch (\Illuminate\Validation\ValidationException $e) {
            $this->saveUserInputToSession($request);
            return redirect()->route('calculatorND3+.index')
                ->withErrors($e->validator)
                ->withInput($request->all());
        } catch (\Exception $e) {
            $this->saveUserInputToSession($request);
            return redirect()->route('calculatorND3+.index')
                ->with('error', 'Optimization error: ' . $e->getMessage())
                ->withInput($request->all());
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
                'kc' => $inputData['kc'] ?? [],
                'j9c' => $inputData['j9c'] ?? null,
                'j11c' => $inputData['j11c'] ?? null,
                'fe' => $inputData['fe'] ?? [],
                'j9e' => $inputData['j9e'] ?? [],
                'j11e' => $inputData['j11e'] ?? [],
                'fn' => $inputData['fn'] ?? null,
                'j9n' => $inputData['j9n'] ?? null,
                'j11n' => $inputData['j11n'] ?? null,
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
    public function loadFromHistory(Nd3CalculationHistory $history)
    {
        if ($history->user_id !== Auth::id()) {
            return redirect()->route('calculatorND3+.index')
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

        return redirect()->route('calculatorND3+.index')
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
        
        return redirect()->route('calculatorND3+.index')
            ->with('success', 'Edit mode activated. You can now modify the data.');
    }

    /**
     * Переключить статус избранного
     */
    public function toggleFavorite(Nd3CalculationHistory $history)
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
    public function deleteHistory(Nd3CalculationHistory $history)
    {
        if ($history->user_id !== Auth::id()) {
            return redirect()->route('calculatorND3+.index')
                ->with('error', 'You do not have permission to delete this calculation.');
        }

        $history->delete();

        return redirect()->route('calculatorND3+.index')
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

        Nd3CalculationHistory::create($historyData);
    }

    private function validateCalculationData(Request $request)
    {
        $messages = [
            'fn.required' => 'The number of components for 4F3.2 multiplet is required.',
            'fn.integer' => 'The number of components must be an integer.',
            'fn.min' => 'The number of components must be at least 1.',
            'fn.max' => 'The number of components cannot exceed 12.',
            
            'j9n.required' => 'The number of components for 4J9/2 multiplet is required.',
            'j9n.integer' => 'The number of components must be an integer.',
            'j9n.min' => 'The number of components must be at least 1.',
            'j9n.max' => 'The number of components cannot exceed 13.',
            
            'j11n.required' => 'The number of components for 4J11/2 multiplet is required.',
            'j11n.integer' => 'The number of components must be an integer.',
            'j11n.min' => 'The number of components must be at least 1.',
            'j11n.max' => 'The number of components cannot exceed 13.',
            
            'fe.required' => 'Energies for 4F3.2 multiplet are required.',
            'fe.*.numeric' => 'All energy values must be numbers.',
            'fe.*.required' => 'All energy fields must be filled.',
            
            'kc.required' => 'Kc coefficients are required.',
            'kc.*.numeric' => 'All Kc coefficients must be numbers.',
            'kc.*.required' => 'All Kc coefficient fields must be filled.',
            
            'j9e.required' => 'Energies for 4J9/2 multiplet are required.',
            'j9e.*.numeric' => 'All energy values must be numbers.',
            'j9e.*.required' => 'All energy fields must be filled.',
            
            'j9c.required' => 'J9C coefficient is required.',
            'j9c.numeric' => 'J9C coefficient must be a number.',
            
            'j11e.required' => 'Energies for 4J11/2 multiplet are required.',
            'j11e.*.numeric' => 'All energy values must be numbers.',
            'j11e.*.required' => 'All energy fields must be filled.',
            
            'j11c.required' => 'J11C coefficient is required.',
            'j11c.numeric' => 'J11C coefficient must be a number.',
            
            'fav.required' => 'Fav parameter is required.',
            'fav.numeric' => 'Fav must be a number.',
        ];

        $rules = [
            'fav' => 'required|numeric',
            'fn' => 'required|integer|min:1|max:12',
            'j9n' => 'required|integer|min:1|max:13',
            'j11n' => 'required|integer|min:1|max:13',
            'fe' => 'required|array|min:1',
            'fe.*' => 'required|numeric',
            'kc' => 'required|array|min:1',
            'kc.*' => 'required|numeric',
            'j9e' => 'required|array|min:1',
            'j9e.*' => 'required|numeric',
            'j9c' => 'required|numeric',
            'j11e' => 'required|array|min:1',
            'j11e.*' => 'required|numeric',
            'j11c' => 'required|numeric',
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
            'fn.required' => 'The number of components for 4F3.2 multiplet is required.',
            'fn.integer' => 'The number of components must be an integer.',
            
            'j9n.required' => 'The number of components for 4J9/2 multiplet is required.',
            'j9n.integer' => 'The number of components must be an integer.',
            
            'j11n.required' => 'The number of components for 4J11/2 multiplet is required.',
            'j11n.integer' => 'The number of components must be an integer.',
            
            'fe.required' => 'Energies for 4F3.2 multiplet are required.',
            'fe.*.numeric' => 'All energy values must be numbers.',
            'fe.*.required' => 'All energy fields must be filled.',
            
            'kc.required' => 'Kc coefficients are required.',
            'kc.*.numeric' => 'All Kc coefficients must be numbers.',
            'kc.*.required' => 'All Kc coefficient fields must be filled.',
            
            'j9e.required' => 'Energies for 4J9/2 multiplet are required.',
            'j9e.*.numeric' => 'All energy values must be numbers.',
            'j9e.*.required' => 'All energy fields must be filled.',
            
            'j9c.required' => 'J9C coefficient is required.',
            'j9c.numeric' => 'J9C coefficient must be a number.',
            
            'j11e.required' => 'Energies for 4J11/2 multiplet are required.',
            'j11e.*.numeric' => 'All energy values must be numbers.',
            'j11e.*.required' => 'All energy fields must be filled.',
            
            'j11c.required' => 'J11C coefficient is required.',
            'j11c.numeric' => 'J11C coefficient must be a number.',
            
            'fav.required' => 'Fav parameter is required.',
            'fav.numeric' => 'Fav must be a number.',
            
            'exp_temperatures.*.numeric' => 'Temperature must be a number.',
            'exp_values.*.numeric' => 'Lifetime value must be a number.',
        ];

        $rules = [
            'fav' => 'required|numeric',
            'fn' => 'required|integer',
            'j9n' => 'required|integer',
            'j11n' => 'required|integer',
            'fe' => 'required|array|min:1',
            'fe.*' => 'required|numeric',
            'kc' => 'required|array|min:1',
            'kc.*' => 'required|numeric',
            'j9e' => 'required|array|min:1',
            'j9e.*' => 'required|numeric',
            'j9c' => 'required|numeric',
            'j11e' => 'required|array|min:1',
            'j11e.*' => 'required|numeric',
            'j11c' => 'required|numeric',
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
<?php

namespace App\Services;

class Ho3OptimizationService
{
    private $calculator;
    private $experimentalData;

    public function __construct(Ho3CalculatorService $calculator)
    {
        $this->calculator = $calculator;
    }

    public function getInitialVectorPublic(array $data): array
    {
        return $this->getInitialVector($data);
    }

    public function runSingleDescent(array $start, array $data): ?array
    {
        return $this->adaptiveCoordinateDescent($start, $data);
    }

    public function setExperimentalData(array $experimentalData)
    {
        $this->experimentalData = [];
        foreach ($experimentalData as $data) {
            if (isset($data['temperature']) && isset($data['value'])) {
                $this->experimentalData[(float)$data['temperature']] = (float)$data['value'];
            }
        }
        
        ksort($this->experimentalData);
    }

    public function optimize(array $data, array $experimentalData = null)
    {
        if ($experimentalData !== null) {
            $this->setExperimentalData($experimentalData);
        }
        
        if (empty($this->experimentalData)) {
            return ['error' => 'No experimental data found. Please add experimental data in the "Experimental Data" tab.'];
        }

        // Фиксируем Kc[0] = 1 (как в C#)
        $originalKc0 = $data['kc'][0];
        $data['kc'][0] = 1.0;
        
        // Начальные параметры для оптимизации
        // Оптимизируем: Kc[1]...Kc[n-1] и Fav
        $y0 = $this->getInitialVector($data);
        
        // Многозапусковая оптимизация
        $bestResult = $this->multiStartOptimization($y0, $data);
        
        // Восстанавливаем оптимизированные значения
        $optimizedKc = array_map(function($val) { 
            return exp($val); 
        }, array_slice($bestResult['params'], 0, $data['i7n'] - 1));
        
        // Добавляем Kc[0] = 1 в начало массива
        array_unshift($optimizedKc, 1.0);
        
        $optimizedFav = exp($bestResult['params'][count($bestResult['params']) - 1]);
        
        // Вычисляем финальные метрики качества
        $optimizedData = $data;
        $optimizedData['kc'] = $optimizedKc;
        $optimizedData['fav'] = $optimizedFav;
        
        $qualityMetrics = $this->calculateQualityMetrics($optimizedData);

        return [
            'kc' => $optimizedKc,
            'fav' => $optimizedFav,
            'objective' => $bestResult['value'],
            'ssd' => $qualityMetrics['ssd'],
            'rmse' => $qualityMetrics['rmse'],
            'mse' => $qualityMetrics['mse'],
            'mae' => $qualityMetrics['mae'],
            'r_squared' => $qualityMetrics['r_squared'],
            'count' => $qualityMetrics['count'],
            'success' => true
        ];
    }

    public function calculateQualityMetrics(array $data): array
    {
        if (empty($this->experimentalData)) {
            return [
                'ssd' => null,
                'rmse' => null,
                'mse' => null,
                'mae' => null,
                'r_squared' => null,
                'count' => 0
            ];
        }

        try {
            $results = $this->calculator->calculate($data);
            
            $ssd = 0;
            $mae = 0;
            $count = 0;
            $experimentalValues = [];
            $calculatedValues = [];
            
            foreach ($this->experimentalData as $temp => $expValue) {
                $closestIndex = null;
                $minDiff = INF;
                
                foreach ($results['temperatures'] as $index => $calcTemp) {
                    $diff = abs($calcTemp - $temp);
                    if ($diff < $minDiff) {
                        $minDiff = $diff;
                        $closestIndex = $index;
                    }
                }
                
                if ($closestIndex !== null && $minDiff <= 5) {
                    $calcValue = $results['delta'][$closestIndex];
                    $diff = $calcValue - $expValue;
                    
                    $ssd += $diff * $diff;
                    $mae += abs($diff);
                    
                    $experimentalValues[] = $expValue;
                    $calculatedValues[] = $calcValue;
                    $count++;
                }
            }
            
            if ($count == 0) {
                return [
                    'ssd' => null,
                    'rmse' => null,
                    'mse' => null,
                    'mae' => null,
                    'r_squared' => null,
                    'count' => 0
                ];
            }
            
            $mse = $ssd / $count;
            $rmse = sqrt($mse);
            $mae = $mae / $count;
            
            $meanExp = array_sum($experimentalValues) / $count;
            $ssTotal = 0;
            $ssResidual = $ssd;
            
            foreach ($experimentalValues as $value) {
                $ssTotal += pow($value - $meanExp, 2);
            }
            
            $rSquared = $ssTotal > 0 ? 1 - ($ssResidual / $ssTotal) : null;
            
            return [
                'ssd' => $ssd,
                'rmse' => $rmse,
                'mse' => $mse,
                'mae' => $mae,
                'r_squared' => $rSquared,
                'count' => $count
            ];
            
        } catch (\Exception $e) {
            return [
                'ssd' => null,
                'rmse' => null,
                'mse' => null,
                'mae' => null,
                'r_squared' => null,
                'count' => 0,
                'error' => $e->getMessage()
            ];
        }
    }

    private function multiStartOptimization(array $y0, array $data, $restarts = 5)
    {
        $bestValue = INF;
        $bestParams = null;

        for ($i = 0; $i < $restarts; $i++) {
            $start = $y0;
            
            // Случайное возмущение начальных значений (±20%)
            foreach ($start as &$val) {
                $val *= 1.0 + (mt_rand() / mt_getrandmax() - 0.5) * 0.2;
            }

            $result = $this->adaptiveCoordinateDescent($start, $data);
            
            if ($result && $result['value'] < $bestValue) {
                $bestValue = $result['value'];
                $bestParams = $result['params'];
            }
        }

        return [
            'params' => $bestParams,
            'value' => $bestValue
        ];
    }

    private function adaptiveCoordinateDescent(array $y, array $data)
    {
        $step = 0.05;
        $finalStep = 1e-6;
        $bestValue = $this->objectiveFunction($y, $data);
        
        if ($bestValue === INF) {
            return null;
        }

        $maxIterations = 10000;
        $noImproveCount = 0;

        for ($iter = 0; $iter < $maxIterations; $iter++) {
            $improved = false;

            for ($k = 0; $k < count($y); $k++) {
                $original = $y[$k];

                $y[$k] = $original + $step;
                $fPlus = $this->objectiveFunction($y, $data);
                
                if ($fPlus < $bestValue) {
                    $bestValue = $fPlus;
                    $improved = true;
                    $noImproveCount = 0;
                    continue;
                }

                $y[$k] = $original - $step;
                $fMinus = $this->objectiveFunction($y, $data);
                
                if ($fMinus < $bestValue) {
                    $bestValue = $fMinus;
                    $improved = true;
                    $noImproveCount = 0;
                    continue;
                }

                $y[$k] = $original;
            }

            if (!$improved) {
                $noImproveCount++;
                
                if ($noImproveCount > 5) {
                    $step *= 0.7;
                    $noImproveCount = 0;
                    
                    if ($step < $finalStep) {
                        break;
                    }
                }
            }
        }

        return [
            'params' => $y,
            'value' => $bestValue
        ];
    }

    private function objectiveFunction(array $y, array $data)
    {
        if (empty($this->experimentalData)) {
            return INF;
        }

        try {
            // Восстанавливаем Kc (Kc[0] = 1, остальные из логарифмов)
            $optimizedKc = [1.0];
            for ($i = 1; $i < $data['i7n']; $i++) {
                $optimizedKc[] = exp($y[$i - 1]);
            }
            
            // Восстанавливаем Fav (последний параметр)
            $optimizedFav = exp($y[count($y) - 1]);
            
            $data['kc'] = $optimizedKc;
            $data['fav'] = $optimizedFav;

            $results = $this->calculator->calculate($data);
            
            $sum = 0;
            $count = 0;
            
            foreach ($this->experimentalData as $temp => $expValue) {
                $closestIndex = null;
                $minDiff = INF;
                
                foreach ($results['temperatures'] as $index => $calcTemp) {
                    $diff = abs($calcTemp - $temp);
                    if ($diff < $minDiff) {
                        $minDiff = $diff;
                        $closestIndex = $index;
                    }
                }
                
                if ($closestIndex !== null && $minDiff <= 5) {
                    $calcValue = $results['delta'][$closestIndex];
                    $diff = $calcValue - $expValue;
                    $sum += $diff * $diff;
                    $count++;
                }
            }
            
            if ($count == 0) {
                return INF;
            }
            
            return $sum / $count;
        } catch (\Exception $e) {
            return INF;
        }
    }

    private function getInitialVector(array $data)
    {
        // Оптимизируем: Kc[1]...Kc[n-1] и Fav
        $totalParams = ($data['i7n'] - 1) + 1; // Kc (кроме первого) + Fav
        $y = array_fill(0, $totalParams, 0);
        
        // Kc[1]...Kc[n-1] (в логарифмах)
        for ($i = 1; $i < $data['i7n']; $i++) {
            $kcValue = isset($data['kc'][$i]) && $data['kc'][$i] > 0 ? $data['kc'][$i] : 1.0;
            $y[$i - 1] = log($kcValue);
        }
        
        // Fav (в логарифмах)
        $favValue = isset($data['fav']) && $data['fav'] > 0 ? $data['fav'] : 1.0;
        $y[count($y) - 1] = log($favValue);
        
        return $y;
    }
}
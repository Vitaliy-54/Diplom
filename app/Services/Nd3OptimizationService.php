<?php

namespace App\Services;

class Nd3OptimizationService
{
    private $calculator;
    private $experimentalData;

    public function __construct(Nd3CalculatorService $calculator)
    {
        $this->calculator = $calculator;
    }

    /**
     * Установить экспериментальные данные из запроса
     */
    public function setExperimentalData(array $experimentalData)
    {
        // Преобразуем массив экспериментальных данных в формат [температура => значение]
        $this->experimentalData = [];
        foreach ($experimentalData as $data) {
            if (isset($data['temperature']) && isset($data['value'])) {
                $this->experimentalData[(float)$data['temperature']] = (float)$data['value'];
            }
        }
        
        // Сортируем по температуре
        ksort($this->experimentalData);
    }

    public function optimize(array $data, array $experimentalData = null)
    {
        // Если переданы экспериментальные данные, используем их
        if ($experimentalData !== null) {
            $this->setExperimentalData($experimentalData);
        }
        
        if (empty($this->experimentalData)) {
            return ['error' => 'No experimental data found. Please add experimental data in the "Experimental Data" tab.'];
        }

        // Фиксируем Kc[0] = 1
        $originalKc0 = $data['kc'][0];
        $data['kc'][0] = 1.0;
        
        // Начальные параметры в логарифмическом пространстве
        // Теперь оптимизируем только J9C, J11C и Kc[1]...Kc[n-1]
        $y0 = $this->getInitialVector($data);
        
        // Многозапусковая оптимизация
        $bestResult = $this->multiStartOptimization($y0, $data);
        
        // Восстанавливаем Kc[0] = 1 в результатах
        $optimizedKc = array_map(function($val) { 
            return exp($val); 
        }, array_slice($bestResult['params'], 2));
        
        // Добавляем Kc[0] = 1 в начало массива
        array_unshift($optimizedKc, 1.0);
        
        // Вычисляем финальные метрики качества с оптимизированными параметрами
        $optimizedData = $data;
        $optimizedData['j9c'] = exp($bestResult['params'][0]);
        $optimizedData['j11c'] = exp($bestResult['params'][1]);
        $optimizedData['kc'] = $optimizedKc;
        
        $qualityMetrics = $this->calculateQualityMetrics($optimizedData);

        return [
            'j9c' => exp($bestResult['params'][0]),
            'j11c' => exp($bestResult['params'][1]),
            'kc' => $optimizedKc,
            'objective' => $bestResult['value'], // MSE (Mean Squared Error)
            'ssd' => $qualityMetrics['ssd'],     // Sum of Squared Deviations
            'rmse' => $qualityMetrics['rmse'],   // Root Mean Square Error
            'mse' => $qualityMetrics['mse'],     // Mean Squared Error
            'mae' => $qualityMetrics['mae'],     // Mean Absolute Error
            'r_squared' => $qualityMetrics['r_squared'], // R-squared
            'count' => $qualityMetrics['count'], // Количество точек
            'success' => true
        ];
    }

    /**
     * Рассчитать метрики качества модели
     * 
     * @param array $data Входные данные с оптимизированными параметрами
     * @return array Массив с метриками: ssd, rmse, mse, mae, r_squared, count
     */
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
            // Выполняем расчет с данными параметрами
            $results = $this->calculator->calculate($data);
            
            $ssd = 0;        // Sum of Squared Deviations
            $mae = 0;        // Mean Absolute Error
            $count = 0;
            $experimentalValues = [];
            $calculatedValues = [];
            
            // Для каждой экспериментальной точки ищем ближайшее расчетное значение
            foreach ($this->experimentalData as $temp => $expValue) {
                // Находим ближайшую температуру в расчетных данных
                $closestIndex = null;
                $minDiff = INF;
                
                foreach ($results['temperatures'] as $index => $calcTemp) {
                    $diff = abs($calcTemp - $temp);
                    if ($diff < $minDiff) {
                        $minDiff = $diff;
                        $closestIndex = $index;
                    }
                }
                
                if ($closestIndex !== null && $minDiff <= 5) { // Допустимое отклонение 5K
                    $calcValue = $results['delta'][$closestIndex];
                    $diff = $calcValue - $expValue;
                    
                    $ssd += $diff * $diff;      // Сумма квадратов отклонений
                    $mae += abs($diff);          // Сумма абсолютных отклонений
                    
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
            
            // Вычисляем метрики
            $mse = $ssd / $count;           // Mean Squared Error
            $rmse = sqrt($mse);              // Root Mean Square Error
            $mae = $mae / $count;            // Mean Absolute Error
            
            // Вычисляем R-squared (коэффициент детерминации)
            $meanExp = array_sum($experimentalValues) / $count;
            $ssTotal = 0;
            $ssResidual = $ssd;
            
            foreach ($experimentalValues as $value) {
                $ssTotal += pow($value - $meanExp, 2);
            }
            
            $rSquared = $ssTotal > 0 ? 1 - ($ssResidual / $ssTotal) : null;
            
            return [
                'ssd' => $ssd,           // Сумма квадратов отклонений
                'rmse' => $rmse,         // Среднеквадратичная ошибка
                'mse' => $mse,           // Средняя квадратичная ошибка
                'mae' => $mae,           // Средняя абсолютная ошибка
                'r_squared' => $rSquared, // Коэффициент детерминации
                'count' => $count        // Количество точек
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

    private function multiStartOptimization(array $y0, array $data, $restarts = 3)
    {
        $bestValue = INF;
        $bestParams = null;

        for ($i = 0; $i < $restarts; $i++) {
            $start = $y0;
            
            // Случайное возмущение начальных значений
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

        for ($iter = 0; $iter < $maxIterations; $iter++) {
            $improved = false;

            for ($k = 0; $k < count($y); $k++) {
                $original = $y[$k];

                $y[$k] = $original + $step;
                $fPlus = $this->objectiveFunction($y, $data);
                
                if ($fPlus < $bestValue) {
                    $bestValue = $fPlus;
                    $improved = true;
                    continue;
                }

                $y[$k] = $original - $step;
                $fMinus = $this->objectiveFunction($y, $data);
                
                if ($fMinus < $bestValue) {
                    $bestValue = $fMinus;
                    $improved = true;
                    continue;
                }

                $y[$k] = $original;
            }

            if (!$improved) {
                $step *= 0.7;
                if ($step < $finalStep) {
                    break;
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
            $data['j9c'] = exp($y[0]);
            $data['j11c'] = exp($y[1]);
            
            // Kc[0] всегда = 1, не оптимизируем
            $data['kc'][0] = 1.0;
            
            // Остальные Kc оптимизируем
            for ($i = 1; $i < $data['fn']; $i++) {
                $data['kc'][$i] = exp($y[1 + $i]); // +1 потому что y[0] и y[1] это J9C и J11C
            }

            $results = $this->calculator->calculate($data);
            
            $sum = 0;
            $count = 0;
            
            // Для каждой экспериментальной точки ищем ближайшее расчетное значение
            foreach ($this->experimentalData as $temp => $expValue) {
                // Находим ближайшую температуру в расчетных данных
                $closestIndex = null;
                $minDiff = INF;
                
                foreach ($results['temperatures'] as $index => $calcTemp) {
                    $diff = abs($calcTemp - $temp);
                    if ($diff < $minDiff) {
                        $minDiff = $diff;
                        $closestIndex = $index;
                    }
                }
                
                if ($closestIndex !== null && $minDiff <= 5) { // Допустимое отклонение 5K
                    $calcValue = $results['delta'][$closestIndex];
                    $diff = $calcValue - $expValue;
                    $sum += $diff * $diff;
                    $count++;
                }
            }
            
            // Если нет совпадающих температур, возвращаем большое число
            if ($count == 0) {
                return INF;
            }
            
            // Возвращаем MSE (Mean Squared Error) для оптимизации
            return $sum / $count;
        } catch (\Exception $e) {
            return INF;
        }
    }

    private function getInitialVector(array $data)
    {
        // Оптимизируем: J9C, J11C, и Kc[1]...Kc[n-1] (исключая Kc[0])
        $totalParams = 2 + ($data['fn'] - 1); // -1 потому что Kc[0] не оптимизируем
        $y = array_fill(0, $totalParams, 0);

        $y[0] = log(abs($data['j9c']) + 1e-10);
        $y[1] = log(abs($data['j11c']) + 1e-10);

        // Начинаем с индекса 1, пропуская Kc[0]
        for ($i = 1; $i < $data['fn']; $i++) {
            $y[1 + $i] = log(abs($data['kc'][$i]) + 1e-10);
        }

        return $y;
    }
}
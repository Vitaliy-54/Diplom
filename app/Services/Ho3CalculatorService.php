<?php

namespace App\Services;

class Ho3CalculatorService
{
    // Физические константы (как в C# программе)
    const h = 6.626176e-34;      // Дж·с
    const k = 1.3806503e-23;     // Дж/К
    const m = 9.109534e-28;      // г (CGS)
    const c = 2.99792458e8;      // м/с
    const e = 1.602176462e-19;   // Кл
    const cc = 2.99792458e10;    // см/с
    const ec = 4.803e-10;        // CGS
    
    const NRowRez = 17;           // Количество температурных точек (как в C#: 12,13,20,...,300)
    
    // Температурные точки (как в C# программе)
    private $temperatures = [12, 13, 20, 40, 60, 80, 100, 120, 140, 160, 180, 200, 220, 240, 260, 280, 300];
    
    public function calculate(array $data)
    {
        // Проверка наличия обязательных полей
        if (!isset($data['i7n']) || !isset($data['i8n']) ||
            !isset($data['i7e']) || !isset($data['i8e']) || !isset($data['kc'])) {
            throw new \Exception('Missing required fields');
        }
        
        $n = 1.58;  // Показатель преломления
        $Ctau = (8 * M_PI * M_PI * $n * $n * self::ec * self::ec) / (self::m * self::cc);
        
        $I7N = (int)$data['i7n'];
        $I8N = (int)$data['i8n'];
        
        // Проверка на пустые массивы
        if (empty($data['i7e']) || empty($data['i8e']) || empty($data['kc'])) {
            throw new \Exception('Required arrays cannot be empty');
        }
        
        // Берем первые I7N элементов
        $I7E = array_slice($data['i7e'], 0, $I7N);
        $I8E = array_slice($data['i8e'], 0, $I8N);
        $Kc = array_slice($data['kc'], 0, $I7N);
        
        // Kc[0] всегда должен быть 1
        if (!isset($Kc[0]) || $Kc[0] != 1) {
            $Kc[0] = 1.0;
        }
        
        $fav = isset($data['fav']) && $data['fav'] !== '' ? (float)$data['fav'] : 0.0;
        
        if ($fav == 0) {
            throw new \Exception('Fav parameter cannot be zero');
        }
        
        // Вычисление средних значений (как в C#)
        $SEI8 = array_sum($I8E) / $I8N;
        $SEI7 = array_sum($I7E) / $I7N;
        $DEI8 = $SEI7 - $SEI8;
        
        if ($DEI8 == 0) {
            throw new \Exception('Energy difference cannot be zero');
        }
        
        $results = [
            'temperatures' => [],
            'w' => [],
            'delta' => []
        ];
        
        // Используем температурные точки из C# программы
        $temperatures = $this->temperatures;
        
        foreach ($temperatures as $T) {
            $SW = 0;
            $Z = 0;
            
            for ($i = 0; $i < $I7N; $i++) {
                // Расчет фактора Больцмана (как в C#)
                $expArg = (-100 * ($I7E[$i] - $I7E[0]) * self::c * self::h) / (self::k * $T);
                $boltz = exp($expArg);
                $Z += $boltz;
                
                for ($j = 0; $j < $I8N; $j++) {
                    $energyDiff = $I7E[$i] - $I8E[$j];
                    if ($energyDiff > 0) {
                        $SW += $Kc[$i] * pow($energyDiff, 3) * $boltz;
                    }
                }
            }
            
            if ($Z == 0) {
                throw new \Exception('Partition function is zero');
            }
            
            // Расчет W (как в C#)
            $W = $Ctau * $fav * 1e-6 * $SW / ($Z * $DEI8);
            
            // Расчет tau (как в C#)
            $Delta = $W > 0 ? 1000.0 / $W : 0;
            
            $results['temperatures'][] = $T;
            $results['w'][] = $W;
            $results['delta'][] = $Delta;
        }
        
        return $results;
    }
    
    /**
     * Получить стандартные температурные точки
     */
    public function getTemperatures(): array
    {
        return $this->temperatures;
    }
}
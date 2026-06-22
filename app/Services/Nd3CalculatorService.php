<?php

namespace App\Services;

class Nd3CalculatorService
{
    // Константы
    const h = 6.626176e-34;
    const k = 1.3806503e-23;
    const m = 9.109534e-28;
    const c = 2.99792458e8;
    const e = 1.602176462e-19;
    const cc = 2.99792458e10;
    const ec = 4.803e-10;
    const NRowRez = 32;

    public function calculate(array $data)
    {
        // Проверка наличия обязательных полей
        if (!isset($data['fn']) || !isset($data['j9n']) || !isset($data['j11n']) ||
            !isset($data['fe']) || !isset($data['kc']) || !isset($data['j9e']) || 
            !isset($data['j11e']) || !isset($data['j9c']) || !isset($data['j11c'])) {
            throw new \Exception('Missing required fields');
        }

        $n = 1.45;
        $Ctau = (8 * M_PI * M_PI * $n * $n * self::ec * self::ec) / (self::m * self::cc);
        
        $FN = (int)$data['fn'];
        $J9N = (int)$data['j9n'];
        $J11N = (int)$data['j11n'];
        
        // Проверка на пустые массивы
        if (empty($data['fe']) || empty($data['kc']) || empty($data['j9e']) || empty($data['j11e'])) {
            throw new \Exception('Required arrays cannot be empty');
        }
        
        $FE = array_slice($data['fe'], 0, $FN);
        $Kc = array_slice($data['kc'], 0, $FN);
        $J9E = array_slice($data['j9e'], 0, $J9N);
        $J11E = array_slice($data['j11e'], 0, $J11N);
        $J9C = (float)$data['j9c'];
        $J11C = (float)$data['j11c'];
        $fav = isset($data['fav']) && $data['fav'] !== '' ? (float)$data['fav'] : 0.0;

        // Проверка на деление на ноль
        if ($fav == 0) {
            throw new \Exception('Fav parameter cannot be zero');
        }

        // Вычисление средних значений
        $SEF = array_sum($FE) / $FN;
        $avgJ9E = array_sum($J9E) / $J9N;
        $avgJ11E = array_sum($J11E) / $J11N;
        
        $DEJ9 = $SEF - $avgJ9E;
        $DEJ11 = $SEF - $avgJ11E;
        
        // Проверка на деление на ноль
        if ($DEJ9 == 0 || $DEJ11 == 0) {
            throw new \Exception('Energy differences cannot be zero');
        }

        $results = [
            'temperatures' => [],
            'w' => [],
            'delta' => []
        ];

        for ($ik = 0; $ik < self::NRowRez; $ik++) {
            $SJ9W = 0;
            $SJ11W = 0;
            $Z = 0;
            $T = 10 * ($ik + 1);

            for ($i = 0; $i < $FN; $i++) {
                // Проверка на отрицательные значения в экспоненте
                $expArg = (-100 * ($FE[$i] - $FE[0]) * self::c * self::h) / (self::k * $T);
                $boltz = exp($expArg);
                $Z += $boltz;

                for ($j = 0; $j < $J9N; $j++) {
                    $energyDiff = $FE[$i] - $J9E[$j];
                    if ($energyDiff > 0) { // Проверка, что разница энергий положительная
                        $SJ9W += $Kc[$i] * $J9C * pow($energyDiff, 3) * $boltz;
                    }
                }

                for ($j = 0; $j < $J11N; $j++) {
                    $energyDiff = $FE[$i] - $J11E[$j];
                    if ($energyDiff > 0) { // Проверка, что разница энергий положительная
                        $SJ11W += $Kc[$i] * $J11C * pow($energyDiff, 3) * $boltz;
                    }
                }
            }

            // Проверка на деление на ноль
            if ($Z == 0) {
                throw new \Exception('Partition function is zero');
            }

            $W = $Ctau * $fav * 1e-6 * $SJ9W / ($Z * $DEJ9) 
                + $Ctau * $fav * 1e-6 * $SJ11W / ($Z * $DEJ11);
            
            // Проверка на деление на ноль
            if ($W == 0) {
                $Delta = 0;
            } else {
                $Delta = 1000 / $W;
            }

            $results['temperatures'][] = $T;
            $results['w'][] = $W;
            $results['delta'][] = $Delta;
        }

        return $results;
    }
}
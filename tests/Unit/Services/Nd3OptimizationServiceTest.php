<?php

namespace Tests\Unit\Services;

use Tests\TestCase;
use App\Services\Nd3CalculatorService;
use App\Services\Nd3OptimizationService;
use Illuminate\Foundation\Testing\RefreshDatabase;

class Nd3OptimizationServiceTest extends TestCase
{
    protected Nd3OptimizationService $optimizationService;
    protected Nd3CalculatorService $calculatorService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->calculatorService = new Nd3CalculatorService();
        $this->optimizationService = new Nd3OptimizationService($this->calculatorService);
    }

    /**
     * Оптимизация должна вернуть оптимизированные параметры при наличии экспериментальных данных.
     */
    public function test_optimize_should_return_optimized_parameters_when_experimental_data_provided()
    {
        $inputData = [
            'fn' => 2,
            'fe' => [0, 100],
            'kc' => [1, 0.5],
            'j9n' => 5,
            'j9e' => [1000, 1020, 1040, 1060, 1080],
            'j9c' => 0.8,
            'j11n' => 6,
            'j11e' => [2000, 2020, 2040, 2060, 2080, 2100],
            'j11c' => 0.6,
            'fav' => 1.2
        ];

        $experimentalData = [
            ['temperature' => 77, 'value' => 280],
            ['temperature' => 100, 'value' => 275],
            ['temperature' => 150, 'value' => 265],
            ['temperature' => 200, 'value' => 250],
            ['temperature' => 250, 'value' => 230],
            ['temperature' => 300, 'value' => 210],
        ];

        $result = $this->optimizationService->optimize($inputData, $experimentalData);

        $this->assertArrayHasKey('success', $result);
        $this->assertArrayHasKey('j9c', $result);
        $this->assertArrayHasKey('j11c', $result);
        $this->assertArrayHasKey('kc', $result);
        $this->assertArrayHasKey('ssd', $result);
        $this->assertArrayHasKey('objective', $result);

        $this->assertTrue($result['success']);

        $this->assertIsNumeric($result['j9c']);
        $this->assertIsNumeric($result['j11c']);
        $this->assertIsArray($result['kc']);
    }

    /**
     * Оптимизация должна вернуть ошибку, если экспериментальные данные отсутствуют.
     */
    public function test_optimize_should_return_error_when_no_experimental_data()
    {
        $inputData = [
            'fn' => 2,
            'fe' => [0, 100],
            'kc' => [1, 0.5],
            'j9n' => 5,
            'j9e' => [1000, 1020, 1040, 1060, 1080],
            'j9c' => 0.8,
            'j11n' => 6,
            'j11e' => [2000, 2020, 2040, 2060, 2080, 2100],
            'j11c' => 0.6,
            'fav' => 1.2
        ];

        $experimentalData = [];

        $result = $this->optimizationService->optimize($inputData, $experimentalData);

        $this->assertArrayHasKey('error', $result);
    }
}
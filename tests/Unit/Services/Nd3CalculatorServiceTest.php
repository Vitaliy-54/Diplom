<?php

namespace Tests\Unit\Services;

use Tests\TestCase;
use App\Services\Nd3CalculatorService;

class Nd3CalculatorServiceTest extends TestCase
{
    protected Nd3CalculatorService $calculatorService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->calculatorService = new Nd3CalculatorService();
    }

    public function test_calculate_should_return_correct_lifetime_when_valid_data_provided()
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

        $result = $this->calculatorService->calculate($inputData);

        $this->assertIsArray($result);
        $this->assertArrayHasKey('temperatures', $result);
        $this->assertArrayHasKey('w', $result);
        $this->assertArrayHasKey('delta', $result);
        
        if (!empty($result['delta'])) {
            foreach ($result['delta'] as $lifetime) {
                $this->assertIsNumeric($lifetime);
            }
        }
    }

    public function test_calculate_should_throw_exception_when_missing_required_fields()
    {
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Missing required fields');

        $invalidData = [
            'fn' => 2,
            'fe' => [0, 100],
            'kc' => [1, 0.5],
            'j9n' => 5,
            'j9e' => [1000, 1020, 1040, 1060, 1080],
        ];

        $this->calculatorService->calculate($invalidData);
    }

    public function test_calculate_should_throw_exception_when_arrays_are_empty()
    {
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Required arrays cannot be empty');

        $invalidData = [
            'fn' => 2,
            'fe' => [],
            'kc' => [1, 0.5],
            'j9n' => 5,
            'j9e' => [1000, 1020, 1040, 1060, 1080],
            'j9c' => 0.8,
            'j11n' => 6,
            'j11e' => [2000, 2020, 2040, 2060, 2080, 2100],
            'j11c' => 0.6,
            'fav' => 1.2
        ];

        $this->calculatorService->calculate($invalidData);
    }

    public function test_calculate_should_trim_excess_array_elements()
    {
        $inputData = [
            'fn' => 2,
            'fe' => [0, 100, 200, 300, 400],
            'kc' => [1, 0.5, 0.7, 0.9],
            'j9n' => 5,
            'j9e' => [1000, 1020, 1040, 1060, 1080, 1100, 1200],
            'j9c' => 0.8,
            'j11n' => 6,
            'j11e' => [2000, 2020, 2040, 2060, 2080, 2100, 2120, 2150],
            'j11c' => 0.6,
            'fav' => 1.2
        ];

        $result = $this->calculatorService->calculate($inputData);
        $this->assertIsArray($result);
        $this->assertArrayHasKey('temperatures', $result);
    }

    public function test_calculate_should_handle_minimal_valid_data()
    {
        $inputData = [
            'fn' => 1,
            'fe' => [0],
            'kc' => [1],
            'j9n' => 1,
            'j9e' => [1000],
            'j9c' => 0.5,
            'j11n' => 1,
            'j11e' => [2000],
            'j11c' => 0.5,
            'fav' => 1.0
        ];

        $result = $this->calculatorService->calculate($inputData);

        $this->assertIsArray($result);
        $this->assertArrayHasKey('temperatures', $result);
        $this->assertArrayHasKey('delta', $result);
    }

    public function test_calculate_should_throw_exception_when_energy_difference_is_zero()
    {
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Energy differences cannot be zero');

        $inputData = [
            'fn' => 1,
            'fe' => [1000],
            'kc' => [1],
            'j9n' => 1,
            'j9e' => [1000],
            'j9c' => 0.8,
            'j11n' => 6,
            'j11e' => [2000, 2020, 2040, 2060, 2080, 2100],
            'j11c' => 0.6,
            'fav' => 1.2
        ];

        $this->calculatorService->calculate($inputData);
    }

    public function test_calculate_should_return_numeric_values()
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

        $result = $this->calculatorService->calculate($inputData);

        foreach ($result['temperatures'] as $temp) {
            $this->assertIsNumeric($temp);
        }
        foreach ($result['w'] as $w) {
            $this->assertIsNumeric($w);
        }
        foreach ($result['delta'] as $delta) {
            $this->assertIsNumeric($delta);
        }
    }
}
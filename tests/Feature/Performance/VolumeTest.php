<?php

namespace Tests\Feature\Performance;

use Tests\TestCase;
use App\Models\User;
use App\Models\Note;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\DB;

class VolumeTest extends TestCase
{
    use DatabaseTransactions;

    protected User $user;

    protected function setUp(): void
    {
        parent::setUp();
        
        if (DB::connection()->getDriverName() === 'mysql') {
            DB::statement('SET FOREIGN_KEY_CHECKS=0');
        }
        
        $this->user = User::create([
            'name' => 'Test User',
            'email' => 'test_' . uniqid() . '@example.com',
            'password' => bcrypt('password'),
            'role' => 'user'
        ]);
    }

    protected function tearDown(): void
    {
        if (DB::connection()->getDriverName() === 'mysql') {
            DB::statement('SET FOREIGN_KEY_CHECKS=1');
        }
        parent::tearDown();
    }

    /**
     * Тест объёмной загрузки: измерение времени выполнения запроса публичных заметок
     * при разном количестве записей в базе данных
     */
    public function test_public_notes_query_performance_with_different_volumes()
    {
        $volumes = [10, 50, 100, 500, 1000];
        $results = [];
        
        foreach ($volumes as $volume) {
            Note::where('user_id', $this->user->id)->delete();
            
            $this->seedNotes($volume);
            
            $startTime = microtime(true);
            $startMemory = memory_get_usage();
            
            $publicNotes = Note::where('is_public', true)->get();
            
            $endTime = microtime(true);
            $endMemory = memory_get_usage();
            
            $executionTime = round(($endTime - $startTime) * 1000, 2);
            $memoryUsed = round(($endMemory - $startMemory) / 1024 / 1024, 2);
            
            $results[$volume] = [
                'time' => $executionTime,
                'memory' => $memoryUsed,
                'count' => $publicNotes->count()
            ];
            
            echo "\nОбъем: {$volume} записей - Время: {$executionTime} мс - Память: {$memoryUsed} МБ";
        }
        
        $this->assertNotEmpty($results);
    }

    /**
     * Тест производительности с пагинацией
     */
    public function test_paginated_notes_performance()
    {
        $this->seedNotes(1000);
        
        $startTime = microtime(true);
        
        $notes = Note::where('is_public', true)
            ->orderBy('created_at', 'desc')
            ->paginate(10);
        
        $endTime = microtime(true);
        $executionTime = round(($endTime - $startTime) * 1000, 2);
        
        echo "\nЗапрос с постраничной разбивкой (1000 записей, 10 на странице): {$executionTime} мс";
        
        $this->assertLessThanOrEqual(10, $notes->count());
    }

    /**
     * Вспомогательный метод для генерации тестовых данных
     */
    protected function seedNotes(int $count): void
    {
        $notes = [];
        $chunkSize = 500;
        
        for ($i = 0; $i < $count; $i++) {
            $notes[] = [
                'title' => 'Test Note ' . ($i + 1),
                'description' => 'Description for test note ' . ($i + 1),
                'is_public' => ($i + 1) % 3 === 0, // ~33% публичных заметок
                'user_id' => $this->user->id,
                'created_at' => now(),
                'updated_at' => now(),
            ];
            
            if (count($notes) >= $chunkSize) {
                Note::insert($notes);
                $notes = [];
            }
        }
        
        if (!empty($notes)) {
            Note::insert($notes);
        }
    }
}
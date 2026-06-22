<?php

namespace Database\Seeders;

use App\Models\RegistrationSetting;
use Illuminate\Database\Seeder;

class RegistrationSettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Добавляем начальную запись
        RegistrationSetting::create([
            'registration_enabled' => true, // По умолчанию регистрация включена
        ]);
    }
}
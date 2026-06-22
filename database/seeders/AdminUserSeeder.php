<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        User::create([
            'name' => 'Andreev',
            'email' => 'andreev.vstu@gmail.com',
            'password' => Hash::make('135791112'),
            'role' => 'admin',
        ]);
    }
}

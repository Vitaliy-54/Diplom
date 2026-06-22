<?php

namespace App\Http\Controllers;

use App\Models\Block;
use Illuminate\Http\Request;
use App\Models\UserStatistic;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    // Метод для отображения dashboard с блоками
    public function dashboard()
    {
        $blocks = Block::all();

        // Получаем статистику пользователей (топ 5 по количеству посещений)
        $userStats = UserStatistic::select('user_id', DB::raw('count(*) as visits'))
            ->with('user')
            ->groupBy('user_id')
            ->orderByDesc('visits')
            ->take(5)
            ->get();

        return view('dashboard', compact('blocks', 'userStats'));
    }
}
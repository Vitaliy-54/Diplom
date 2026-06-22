<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\UserStatistic;
use App\Models\User;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class VisitStatisticsController extends Controller
{
    /**
     * Показать статистику посещений
     */
    public function index(Request $request)
    {
        $userId = $request->input('user_id');
        $dateFrom = $request->input('date_from');
        $dateTo = $request->input('date_to');

        // Основной запрос для таблицы
        $query = UserStatistic::with('user')->orderBy('last_activity_at', 'desc');

        if ($userId) {
            $query->where('user_id', $userId);
        }

        if ($dateFrom) {
            $query->where('last_activity_at', '>=', Carbon::parse($dateFrom));
        }

        if ($dateTo) {
            $query->where('last_activity_at', '<=', Carbon::parse($dateTo));
        }

        $statistics = $query->get();
        $users = User::all();

        // Статистика по страницам
        $pageStatsQuery = UserStatistic::select('page', DB::raw('count(*) as visits'))
            ->groupBy('page')
            ->orderByDesc('visits');

        // Статистика по пользователям
        $userStatsQuery = UserStatistic::select('user_id', DB::raw('count(*) as visits'))
            ->with('user')
            ->groupBy('user_id')
            ->orderByDesc('visits');

        // Применяем фильтры к статистическим запросам
        if ($userId) {
            $pageStatsQuery->where('user_id', $userId);
            $userStatsQuery->where('user_id', $userId);
        }

        if ($dateFrom) {
            $pageStatsQuery->where('last_activity_at', '>=', Carbon::parse($dateFrom));
            $userStatsQuery->where('last_activity_at', '>=', Carbon::parse($dateFrom));
        }

        if ($dateTo) {
            $pageStatsQuery->where('last_activity_at', '<=', Carbon::parse($dateTo));
            $userStatsQuery->where('last_activity_at', '<=', Carbon::parse($dateTo));
        }

        $pageStats = $pageStatsQuery->get();
        $userStats = $userStatsQuery->get();

        // Подготовка данных для графика (30 дней)
        $days = 30;
        $chartData = [
            'labels' => [],
            'data' => []
        ];

        for ($i = $days; $i >= 0; $i--) {
            $date = now()->subDays($i)->format('Y-m-d');
            $chartData['labels'][] = now()->subDays($i)->format('d.m.Y');
            
            $dayQuery = UserStatistic::whereDate('last_activity_at', $date);
            
            if ($userId) {
                $dayQuery->where('user_id', $userId);
            }
            
            if ($dateFrom) {
                $dayQuery->where('last_activity_at', '>=', Carbon::parse($dateFrom));
            }
            
            if ($dateTo) {
                $dayQuery->where('last_activity_at', '<=', Carbon::parse($dateTo));
            }
            
            $chartData['data'][] = $dayQuery->count();
        }

        return view('admin.statistics.visits', compact(
            'statistics',
            'users',
            'pageStats',
            'userStats',
            'chartData'
        ));
    }

    /**
     * Получить детализированную статистику по пользователю
     */
    public function userStatistics(Request $request, $userId)
    {
        $user = User::findOrFail($userId);

        $query = $user->statistics()->orderBy('last_activity_at', 'desc');

        // Фильтрация по странице
        if ($request->filled('page')) {
            $query->where('page', 'like', '%' . $request->page . '%');
        }

        // Фильтрация по дате и времени "от"
        if ($request->filled('from')) {
            $query->where('last_activity_at', '>=', Carbon::parse($request->from));
        }

        // Фильтрация по дате и времени "до"
        if ($request->filled('to')) {
            $query->where('last_activity_at', '<=', Carbon::parse($request->to));
        }

        $statistics = $query->get();

        // Группированная статистика по страницам (не зависит от фильтров)
        $pageStats = $user->statistics()
            ->select(
                'page',
                DB::raw('count(*) as visits'),
                DB::raw('MAX(last_activity_at) as last_visit')
            )
            ->groupBy('page')
            ->orderByDesc('visits')
            ->get();

        return view('admin.statistics.user', compact('user', 'statistics', 'pageStats'));
    }

public function deletePage()
{
    $users = User::withCount('statistics')->get();

    $totalVisits = $users->sum('statistics_count');
    $totalUsers = $users->count();

    $maxVisits = $users->max('statistics_count');
    $mostActiveUsers = $users->filter(fn($user) => $user->statistics_count === $maxVisits);

    $minVisits = $users->min('statistics_count');
    $leastActiveUsers = $users->filter(fn($user) => $user->statistics_count === $minVisits);

    return view('admin.statistics.bulk-delete', compact(
        'users',
        'totalVisits',
        'totalUsers',
        'mostActiveUsers',
        'leastActiveUsers'
    ));
}

public function deleteUserHistory(Request $request, User $user)
{
    if ($request->has('delete_all_time')) {
        $count = $user->statistics()->count();
        $user->statistics()->delete();
        return back()->with('success', "Удалено $count посещений для пользователя {$user->name}.");
    }

    $request->validate([
        'from' => 'required|date',
        'to' => 'required|date|after_or_equal:from',
    ]);

    $from = Carbon::parse($request->from);
    $to = Carbon::parse($request->to);

    $count = $user->statistics()
        ->whereBetween('last_activity_at', [$from, $to])
        ->delete();

    return back()->with('success', "Удалено $count посещений пользователя {$user->name} за выбранный период.");
}


public function bulkDeleteByDate(Request $request)
{
    // Если чекбокс "Удалить за всё время" выбран — удаляем всё без фильтра
    if ($request->has('delete_all_time')) {
        $deletedCount = UserStatistic::query()->delete();

        return back()->with('success', "Удалено $deletedCount записей за всё время.");
    }

    // Валидация для удаления по диапазону дат
    $request->validate([
        'from' => 'required|date',
        'to' => 'required|date|after_or_equal:from',
    ]);

    $from = Carbon::parse($request->from);
    $to = Carbon::parse($request->to);

    $deletedCount = UserStatistic::whereBetween('last_activity_at', [$from, $to])->delete();

    return back()->with('success', "Удалено $deletedCount записей за выбранный период.");
}


}

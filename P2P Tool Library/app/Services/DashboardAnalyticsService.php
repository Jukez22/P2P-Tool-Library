<?php

namespace App\Services;

use App\Models\Reservation;
use Illuminate\Support\Facades\DB;

class DashboardAnalyticsService
{

    public function calculateRentalTrends(): array
    {
        return Reservation::select(DB::raw('DATE(created_at) as date'), DB::raw('count(*) as count'))
            ->where('created_at', '>=', now()->subDays(30))
            ->groupBy('date')
            ->orderBy('date')
            ->get()
            ->pluck('count', 'date')
            ->toArray();
    }

    public function calculateReturnRates(): array
    {
        $totalCompleted = Reservation::where('status', 'completed')->count();

        if ($totalCompleted === 0) {
            return ['on_time' => 0, 'late' => 0];
        }

        $lateCount = DB::table('late_return_logs')->count();
        $onTimeCount = max(0, $totalCompleted - $lateCount);

        return [
            'on_time_percentage' => round(($onTimeCount / $totalCompleted) * 100, 2),
            'late_percentage'    => round(($lateCount / $totalCompleted) * 100, 2),
        ];
    }

    public function calculateOverdueStatistics(): array
    {
        $now = now();

        $overdueCount = Reservation::where('status', '!=', 'completed')
            ->where('end_datetime', '<', $now)
            ->count();

        $activeCount = Reservation::where('status', 'pending')->count();

        return [
            'total_overdue'   => $overdueCount,
            'overdue_ratio'   => $activeCount > 0 ? round(($overdueCount / $activeCount) * 100, 2) : 0,
            'average_delay'   => Reservation::where('status', '!=', 'completed')
                ->where('end_datetime', '<', $now)
                ->select(DB::raw('AVG(TIMESTAMPDIFF(HOUR, end_datetime, NOW())) as avg_delay_hours'))
                ->first()
                ->avg_delay_hours ?? 0,
        ];
    }
}

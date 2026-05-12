<?php

namespace App\Services;

use App\Models\Reservation;
use Illuminate\Support\Facades\DB;

// Logic for dashboard charts and aggregates
class DashboardAnalyticsService
{
    // Rental trends (last 30 days)
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

    // Return rates (On-time vs Late)
    public function calculateReturnRates(): array
    {
        $totalCompleted = Reservation::where('status', 'completed')->count();
        
        if ($totalCompleted === 0) {
            return ['on_time' => 0, 'late' => 0];
        }

        // On-time = completed and not in late_return_logs
        $lateCount = DB::table('late_return_logs')->count();
        $onTimeCount = max(0, $totalCompleted - $lateCount);

        return [
            'on_time_percentage' => round(($onTimeCount / $totalCompleted) * 100, 2),
            'late_percentage'    => round(($lateCount / $totalCompleted) * 100, 2),
        ];
    }

    // Overdue stats
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

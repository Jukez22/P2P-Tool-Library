<?php

namespace App\Http\Controllers;

use App\Models\DashboardActivityLog;
use App\Services\DashboardAnalyticsService;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    // Aggregated metrics and analytics for the dashboard
    public function getDashboardMetrics(DashboardAnalyticsService $analyticsService)
    {
        return response()->json([
            'trends'    => $analyticsService->calculateRentalTrends(),
            'rates'     => $analyticsService->calculateReturnRates(),
            'overdue'   => $analyticsService->calculateOverdueStatistics(),
        ]);
    }

    // Most recent activities
    public function getRecentActivities()
    {
        $activities = DashboardActivityLog::with(['user', 'tool', 'borrow'])
            ->orderBy('activity_time', 'desc')
            ->limit(20)
            ->get();

        return response()->json([
            'data' => $activities
        ]);
    }

    // Currently active rentals (reservations)
    public function getActiveRentals()
    {
        $rentals = \App\Models\Reservation::with(['borrower', 'tool'])
            ->where('status', 'pending') // 'pending' = active status based on current enum
            ->get();

        return response()->json([
            'data' => $rentals
        ]);
    }

    // Tools that are due for return
    public function getPendingReturns()
    {
        $pendingReturns = \App\Models\Reservation::with(['borrower', 'tool'])
            ->where('status', '!=', 'completed')
            ->where('end_datetime', '<', now())
            ->get();

        return response()->json([
            'data' => $pendingReturns
        ]);
    }

    // Overdue rentals list
    public function getOverdueRentals()
    {
        $overdueRentals = \App\Models\Reservation::with(['borrower', 'tool'])
            ->where('status', '!=', 'completed')
            ->where('end_datetime', '<', now())
            ->get();

        return response()->json([
            'data' => $overdueRentals
        ]);
    }
}

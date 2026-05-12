<?php

namespace App\Console\Commands;

use App\Models\Reservation;
use App\Services\DashboardActivityService;
use Illuminate\Console\Command;

class RefreshDashboardMetrics extends Command
{
    // Command signature
    protected $signature = 'app:refresh-dashboard-metrics';

    // Command description
    protected $description = 'Refresh dashboard metrics and detect overdue rentals';

    // Execute command
    public function handle(DashboardActivityService $activityService)
    {
        $this->info('Refreshing dashboard metrics...');

        // Detect Overdue Rentals
        $overdueRentals = Reservation::where('status', '!=', 'completed')
            ->where('end_datetime', '<', now())
            ->get();

        foreach ($overdueRentals as $rental) {
            // Throttling: only log once every 24 hours
            $alreadyLogged = \App\Models\DashboardActivityLog::where('activity_type', 'overdue_return')
                ->where('borrow_id', $rental->id)
                ->where('activity_time', '>', now()->subDay())
                ->exists();

            if (!$alreadyLogged) {
                $activityService->logOverdueReturn($rental);
                $this->warn("Logged overdue return for rental #{$rental->id}");
            }
        }

        // Identify Pending Returns (due in next 24 hours)
        $pendingReturns = Reservation::where('status', '!=', 'completed')
            ->whereBetween('end_datetime', [now(), now()->addDay()])
            ->get();

        foreach ($pendingReturns as $rental) {
            $alreadyLogged = \App\Models\DashboardActivityLog::where('activity_type', 'pending_return')
                ->where('borrow_id', $rental->id)
                ->where('activity_time', '>', now()->subDay())
                ->exists();

            if (!$alreadyLogged) {
                $activityService->logPendingReturn($rental);
                $this->info("Logged pending return for rental #{$rental->id}");
            }
        }

        $this->info('Dashboard metrics refresh completed.');
    }
}

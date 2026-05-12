<?php

namespace App\Console\Commands;

use App\Models\InsuranceClaim;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;

class MonitorInsuranceClaims extends Command
{

    protected $signature = 'app:monitor-insurance-claims';

    protected $description = 'Monitor insurance claims for delays';

    public function handle()
    {
        $this->info('Starting insurance claim monitoring...');

        $now = Carbon::now();
        $pendingThreshold = $now->copy()->subDays(3);
        $reviewThreshold = $now->copy()->subDays(7);

        $delayedPending = InsuranceClaim::where('claim_status', 'pending')
            ->where('created_at', '<=', $pendingThreshold)
            ->get();

        if ($delayedPending->isNotEmpty()) {
            $this->warn("Found {$delayedPending->count()} delayed pending claims.");
            $this->notifyAdmins("Delayed Pending Claims", $delayedPending);
        }

        $escalatedClaims = InsuranceClaim::where('claim_status', 'under_review')
            ->where('updated_at', '<=', $reviewThreshold)
            ->get();

        if ($escalatedClaims->isNotEmpty()) {
            $this->error("Found {$escalatedClaims->count()} escalated unresolved claims.");
            $this->notifyAdmins("Escalated Unresolved Claims", $escalatedClaims);
        }

        $this->info('Monitoring completed.');
    }

    protected function notifyAdmins(string $type, $claims)
    {
        $message = "[Insurance Monitor] {$type}: " . $claims->pluck('id')->implode(', ');

        Log::warning($message);
    }
}

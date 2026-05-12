<?php

namespace App\Console\Commands;

use App\Models\InsuranceClaim;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;

class MonitorInsuranceClaims extends Command
{
    // command signature
    protected $signature = 'app:monitor-insurance-claims';

    // command description
    protected $description = 'Monitor insurance claims for delays';

    // execute command
    public function handle()
    {
        $this->info('Starting insurance claim monitoring...');

        $now = Carbon::now();
        $pendingThreshold = $now->copy()->subDays(3);
        $reviewThreshold = $now->copy()->subDays(7);

        // Delayed pending claims (3+ days)
        $delayedPending = InsuranceClaim::where('claim_status', 'pending')
            ->where('created_at', '<=', $pendingThreshold)
            ->get();

        if ($delayedPending->isNotEmpty()) {
            $this->warn("Found {$delayedPending->count()} delayed pending claims.");
            $this->notifyAdmins("Delayed Pending Claims", $delayedPending);
        }

        // Escalated unresolved claims (7+ days)
        $escalatedClaims = InsuranceClaim::where('claim_status', 'under_review')
            ->where('updated_at', '<=', $reviewThreshold)
            ->get();

        if ($escalatedClaims->isNotEmpty()) {
            $this->error("Found {$escalatedClaims->count()} escalated unresolved claims.");
            $this->notifyAdmins("Escalated Unresolved Claims", $escalatedClaims);
        }

        $this->info('Monitoring completed.');
    }

    // Notify admins about delayed claims
    protected function notifyAdmins(string $type, $claims)
    {
        $message = "[Insurance Monitor] {$type}: " . $claims->pluck('id')->implode(', ');
        
        // Log for now
        Log::warning($message);
    }
}

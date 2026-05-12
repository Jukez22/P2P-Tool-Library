<?php

namespace App\Console\Commands;

use App\Models\InventoryAudit;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;

class ExpireInventoryAudits extends Command
{

    protected $signature = 'app:expire-inventory-audits';

    protected $description = 'Expire audits that are past due';

    public function handle()
    {
        $expiredCount = InventoryAudit::where('audit_status', 'pending')
            ->where('expires_at', '<', Carbon::now())
            ->update(['audit_status' => 'expired']);

        $this->info("Successfully expired {$expiredCount} inventory audits.");
    }
}

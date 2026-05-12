<?php

namespace App\Services;

use App\Models\InventoryAudit;
use App\Models\InventoryAuditItem;
use App\Models\User;
use App\Models\Tool;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB; 
use Illuminate\Database\Eloquent\Collection;


class AuditGenerationService
{
    // Generate random audits for testing/priority
    public function generateRandomAudits(int $limit = 5): int
    {
        $priorityUserIds = $this->getPriorityUserIds($limit * 2);

        if ($priorityUserIds->isEmpty()) {
            return 0;
        }

        $createdCount = 0;

        foreach ($priorityUserIds->shuffle()->take($limit) as $userId) {
            $tools = Tool::whereHas('reservations', function($q) use ($userId) {
            })->limit(3)->get();
        }

        return $createdCount;
    }

    // Logic to find high-priority users for audits
    protected function getPriorityUserIds(int $limit)
    {
        return User::query()
            // Users with reported issues
            ->whereHas('reportedUser') 
            // Users with failed previous audits
            ->orWhereHas('inventoryAudits', function ($query) {
                $query->where('audit_status', 'rejected');
            })
            // Users with high-value tools
            ->orWhereHas('inventoryAudits.items.tool', function ($query) {
                $query->where('price', '>', 500);
            })
            ->pluck('id')
            ->unique();
    }

    // Create a new audit for a user
    public function createAuditForUser(int $userId, array $toolIds): ?InventoryAudit
    {
        return DB::transaction(function () use ($userId, $toolIds) {
            $audit = InventoryAudit::create([
                'lender_id'    => $userId,
                'audit_status' => 'pending',
                'assigned_at'  => Carbon::now(),
                'expires_at'   => Carbon::now()->addDays(3),
            ]);

            foreach ($toolIds as $toolId) {
                InventoryAuditItem::create([
                    'inventory_audit_id' => $audit->id,
                    'tool_id'           => $toolId,
                    'item_status'       => 'pending',
                ]);
            }

            return $audit;
        });
    }
}

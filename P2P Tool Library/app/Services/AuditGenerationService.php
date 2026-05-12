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

    protected function getPriorityUserIds(int $limit)
    {
        return User::query()

            ->whereHas('reportedUser') 

            ->orWhereHas('inventoryAudits', function ($query) {
                $query->where('audit_status', 'rejected');
            })

            ->orWhereHas('inventoryAudits.items.tool', function ($query) {
                $query->where('price', '>', 500);
            })
            ->pluck('id')
            ->unique();
    }

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

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InventoryAuditItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'inventory_audit_id',
        'tool_id',
        'proof_image',
        'proof_video',
        'item_status',
        'rejection_reason',
    ];

    // Parent audit
    public function audit()
    {
        return $this->belongsTo(InventoryAudit::class, 'inventory_audit_id');
    }

    // The tool
    public function tool()
    {
        return $this->belongsTo(Tool::class);
    }
}

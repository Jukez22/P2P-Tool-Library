<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InventoryAudit extends Model
{
    use HasFactory;

    protected $fillable = [
        'lender_id',
        'audit_status',
        'assigned_at',
        'submitted_at',
        'reviewed_at',
        'expires_at',
        'reviewer_id',
        'notes',
    ];

    protected $casts = [
        'assigned_at'  => 'datetime',
        'submitted_at' => 'datetime',
        'reviewed_at'  => 'datetime',
        'expires_at'   => 'datetime',
    ];

    public function lender()
    {
        return $this->belongsTo(User::class, 'lender_id');
    }

    public function reviewer()
    {
        return $this->belongsTo(User::class, 'reviewer_id');
    }

    public function items()
    {
        return $this->hasMany(InventoryAuditItem::class);
    }
}

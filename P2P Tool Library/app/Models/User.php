<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\Date;

class User extends Authenticatable
{
    protected $table = 'users';

    const UPDATED_AT = null;

    protected $fillable = [
        'name',
        'phone',
        'email',
        'password',
        'role',
        'address',
        'membership_tier_id',
        'trust_score',
    ];

    protected $hidden = [
        'password',
    ];

    protected $casts = [
        'password'           => 'hashed',
        'membership_tier_id' => 'integer',
        'trust_score'        => 'integer',
    ];

    // User tier
    public function membershipTier()
    {
        return $this->belongsTo(MembershipTier::class);
    }

    // Suspension records
    public function suspensions()
    {
        return $this->hasMany(UserSuspension::class);
    }

    // Check if user is blocked
    public function isSuspended(): bool
    {
        $suspension = $this->suspensions()
            ->where('is_active', true)
            ->latest()
            ->first();

        if (!$suspension) {
            return false;
        }

        if ($suspension->type === 'permanent') {
            return true;
        }

        if ($suspension->suspended_until && $suspension->suspended_until->gt(Date::now())) {
            return true;
        }

        return false;
    }

    public function inventoryAudits()
    {
        return $this->hasMany(InventoryAudit::class, 'lender_id');
    }

    public function reviewedAudits()
    {
        return $this->hasMany(InventoryAudit::class, 'reviewer_id');
    }

    public function borrowerDisputes()
    {
        return $this->hasMany(Dispute::class, 'borrower_id');
    }

    public function lenderDisputes()
    {
        return $this->hasMany(Dispute::class, 'lender_id');
    }

    public function handledDisputes()
    {
        return $this->hasMany(Dispute::class, 'librarian_id');
    }
}
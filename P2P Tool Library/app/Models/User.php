<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;

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

    /**
     * Get the membership tier that the user belongs to.
     */
    public function membershipTier()
    {
        return $this->belongsTo(MembershipTier::class);
    }
}
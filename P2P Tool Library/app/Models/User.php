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

    /**
     * Get the tools owned by the user.
     */
    public function tools()
    {
        return $this->hasMany(Tool::class, 'owner_id');
    }

    /**
     * Get the reservations made by the user.
     */
    public function reservations()
    {
        return $this->hasMany(Reservation::class, 'borrower_id');
    }

    /**
     * Get the reviews received by the user.
     */
    public function reviewsReceived()
    {
        return $this->hasMany(Review::class, 'reviewed_user_id');
    }
}
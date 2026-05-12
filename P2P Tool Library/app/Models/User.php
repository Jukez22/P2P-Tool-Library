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
        'trust_score'        => 'float',
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

    /**
     * Get the messages received by the user.
     */
    public function messagesReceived()
    {
        return $this->hasMany(Message::class, 'receiver_id');
    }

    /**
     * Get the messages sent by the user.
     */
    public function messagesSent()
    {
        return $this->hasMany(Message::class, 'sender_id');
    }

    /**
     * Get the referrals made by the user.
     */
    public function referrals()
    {
        return $this->hasMany(Referral::class, 'referrer_user_id');
    }

    /**
     * Get the trust score logs for the user.
     */
    public function trustScoreLogs()
    {
        return $this->hasMany(TrustScoreLog::class, 'user_id');
    }

    /**
     * Get the reports submitted by the user.
     */
    public function reports()
    {
        return $this->hasMany(Report::class, 'reporter_id');
    }
}
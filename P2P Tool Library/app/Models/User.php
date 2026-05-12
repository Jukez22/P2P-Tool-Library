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
        'trust_score'        => 'float',
    ];

    public function membershipTier()
    {
        return $this->belongsTo(MembershipTier::class);
    }

    public function tools()
    {
        return $this->hasMany(Tool::class, 'owner_id');
    }

    public function reservations()
    {
        return $this->hasMany(Reservation::class, 'borrower_id');
    }

    public function reviewsReceived()
    {
        return $this->hasMany(Review::class, 'reviewed_user_id');
    }

    public function messagesReceived()
    {
        return $this->hasMany(Message::class, 'receiver_id');
    }

    public function messagesSent()
    {
        return $this->hasMany(Message::class, 'sender_id');
    }

    public function referrals()
    {
        return $this->hasMany(Referral::class, 'referrer_user_id');
    }

    public function trustScoreLogs()
    {
        return $this->hasMany(TrustScoreLog::class, 'user_id');
    }

    public function reports()
    {
        return $this->hasMany(Report::class, 'reporter_id');
    }
}
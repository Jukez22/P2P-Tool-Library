<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Referral extends Model
{
    protected $fillable = [
        'referrer_user_id',
        'referred_user_id',
        'reward',
        'status',
    ];

    protected $casts = [
        'reward' => 'decimal:2',
    ];

    const UPDATED_AT = null;

    public function referrer()
    {
        return $this->belongsTo(User::class, 'referrer_user_id');
    }

    public function referred()
    {
        return $this->belongsTo(User::class, 'referred_user_id');
    }
}

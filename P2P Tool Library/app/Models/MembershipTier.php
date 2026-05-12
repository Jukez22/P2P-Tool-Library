<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MembershipTier extends Model
{
    protected $table = 'membership_tiers';

    protected $fillable = [
        'name',
        'discount_rate',
        'boost_limit',
        'max_active_rentals',
    ];

    protected $casts = [
        'discount_rate'      => 'decimal:2',
        'boost_limit'        => 'integer',
        'max_active_rentals' => 'integer',
    ];

    public function users()
    {
        return $this->hasMany(User::class);
    }
}


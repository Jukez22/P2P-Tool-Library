<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PromotionCampaign extends Model
{
    protected $fillable = [
        'name',
        'category',
        'discount',
        'code',
        'start_date',
        'end_date',
        'is_active',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date'   => 'date',
        'is_active'  => 'boolean',
    ];
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Deposit extends Model
{
    protected $fillable = [
        'amount',
        'reservation_id',
        'status',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
    ];

    const UPDATED_AT = null;

    public function reservation()
    {
        return $this->belongsTo(Reservation::class);
    }
}

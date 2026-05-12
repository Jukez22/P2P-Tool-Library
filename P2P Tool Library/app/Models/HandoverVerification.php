<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HandoverVerification extends Model
{
    use HasFactory;

    protected $fillable = [
        'borrow_id',
        'qr_code',
        'is_verified',
        'verified_at',
    ];

    protected $casts = [
        'verified_at' => 'datetime',
        'is_verified' => 'boolean',
    ];

    // Link to the borrow record
    public function reservation()
    {
        return $this->belongsTo(Reservation::class, 'borrow_id');
    }
}

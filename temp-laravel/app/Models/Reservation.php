<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Reservation extends Model
{
    protected $fillable = [
        'start_datetime',
        'end_datetime',
        'borrower_id',
        'status',
        'tool_id',
        'total_price',
    ];

    protected $casts = [
        'start_datetime' => 'datetime',
        'end_datetime' => 'datetime',
        'total_price' => 'decimal:2',
    ];

    const UPDATED_AT = null;

    public function borrower()
    {
        return $this->belongsTo(User::class, 'borrower_id');
    }

    public function tool()
    {
        return $this->belongsTo(Tool::class);
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    public function deposits()
    {
        return $this->hasMany(Deposit::class);
    }
}

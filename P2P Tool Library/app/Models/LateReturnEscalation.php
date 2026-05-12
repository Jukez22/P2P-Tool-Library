<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LateReturnEscalation extends Model
{
    use HasFactory;

    protected $fillable = [
        'borrow_id',
        'escalation_level',
        'days_late',
        'penalty_amount',
        'notification_sent',
        'escalated_at',
        'resolved_at',
    ];

    protected $casts = [
        'notification_sent' => 'boolean',
        'penalty_amount'    => 'decimal:2',
        'escalated_at'      => 'datetime',
        'resolved_at'       => 'datetime',
    ];

    public function reservation()
    {
        return $this->belongsTo(Reservation::class, 'borrow_id');
    }

    // Escalation history logs
    public function logs()
    {
        return $this->hasMany(LateReturnLog::class);
    }
}

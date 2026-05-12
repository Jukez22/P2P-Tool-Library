<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LateReturnLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'late_return_escalation_id',
        'notification_type',
        'message',
        'sent_at',
    ];

    protected $casts = [
        'sent_at' => 'datetime',
    ];

    public function escalation()
    {
        return $this->belongsTo(LateReturnEscalation::class, 'late_return_escalation_id');
    }
}

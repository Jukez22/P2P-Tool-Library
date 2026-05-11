<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BatteryHealthLog extends Model
{
    protected $fillable = [
        'tool_id',
        'charge_cycles',
        'health_percentage',
        'logged_at',
    ];

    protected $casts = [
        'logged_at' => 'datetime',
    ];

    public function tool()
    {
        return $this->belongsTo(Tool::class);
    }
}

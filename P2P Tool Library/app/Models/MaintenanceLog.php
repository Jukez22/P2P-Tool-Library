<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MaintenanceLog extends Model
{
    protected $fillable = [
        'description',
        'date',
        'cost',
        'tool_id',
        'status',
        'technician_id',
        'type',
        'started_at',
        'completed_at',
        'is_successful',
    ];

    protected $casts = [
        'date' => 'date',
        'cost' => 'decimal:2',
        'started_at' => 'datetime',
        'completed_at' => 'datetime',
        'is_successful' => 'boolean',
    ];

    public $timestamps = false;

    public function tool()
    {
        return $this->belongsTo(Tool::class);
    }

    public function technician()
    {
        return $this->belongsTo(User::class, 'technician_id');
    }
}

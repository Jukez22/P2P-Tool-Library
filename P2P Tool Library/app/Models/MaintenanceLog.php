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
    ];

    protected $casts = [
        'date' => 'date',
        'cost' => 'decimal:2',
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

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ToolAvailability extends Model
{
    protected $table = 'tool_availability';

    protected $fillable = [
        'start_datetime',
        'end_datetime',
        'tool_id',
        'status',
    ];

    protected $casts = [
        'start_datetime' => 'datetime',
        'end_datetime' => 'datetime',
    ];

    public $timestamps = false;

    public function tool()
    {
        return $this->belongsTo(Tool::class);
    }
}

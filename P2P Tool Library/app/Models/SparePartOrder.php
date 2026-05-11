<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SparePartOrder extends Model
{
    protected $fillable = [
        'tool_id',
        'part_name',
        'order_date',
        'expected_arrival_date',
        'status',
    ];

    protected $casts = [
        'order_date' => 'date',
        'expected_arrival_date' => 'date',
    ];

    public function tool()
    {
        return $this->belongsTo(Tool::class);
    }
}

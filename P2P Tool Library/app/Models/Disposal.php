<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Disposal extends Model
{
    protected $fillable = [
        'tool_id',
        'reason',
        'disposal_method',
        'disposed_at',
    ];

    protected $casts = [
        'disposed_at' => 'date',
    ];

    public function tool()
    {
        return $this->belongsTo(Tool::class);
    }
}

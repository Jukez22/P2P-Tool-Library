<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ToolDocument extends Model
{
    protected $fillable = [
        'file_url',
        'type',
        'tool_id',
    ];

    const UPDATED_AT = null;

    public function tool()
    {
        return $this->belongsTo(Tool::class);
    }
}

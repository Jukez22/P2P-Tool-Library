<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DashboardActivityLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'activity_type',
        'borrow_id',
        'user_id',
        'tool_id',
        'activity_message',
        'activity_time',
    ];

    protected $casts = [
        'activity_time' => 'datetime',
    ];

    public function borrow()
    {
        return $this->belongsTo(Borrow::class, 'borrow_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function tool()
    {
        return $this->belongsTo(Tool::class);
    }
}

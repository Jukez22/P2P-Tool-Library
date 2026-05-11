<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Report extends Model
{
    protected $fillable = [
        'reporter_id',
        'reported_user_id',
        'reported_tool_id',
        'reservation_id',
        'reason',
        'description',
        'status',
    ];

    const UPDATED_AT = null;

    public function reporter()
    {
        return $this->belongsTo(User::class, 'reporter_id');
    }

    public function reportedUser()
    {
        return $this->belongsTo(User::class, 'reported_user_id');
    }

    public function reportedTool()
    {
        return $this->belongsTo(Tool::class, 'reported_tool_id');
    }

    public function reservation()
    {
        return $this->belongsTo(Reservation::class);
    }
}

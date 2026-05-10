<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TrustScoreLog extends Model
{
    protected $fillable = [
        'user_id',
        'change_value',
        'reason',
    ];

    const UPDATED_AT = null;

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

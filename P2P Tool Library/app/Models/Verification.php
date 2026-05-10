<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Verification extends Model
{
    protected $fillable = [
        'user_id',
        'national_id',
        'status',
    ];

    const UPDATED_AT = null;
    const CREATED_AT = 'verified_at';

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

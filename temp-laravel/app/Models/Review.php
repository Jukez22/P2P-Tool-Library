<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    protected $fillable = [
        'rating',
        'comment',
        'reviewer_user_id',
        'reviewed_user_id',
    ];

    protected $casts = [
        'rating' => 'decimal:1',
    ];

    const UPDATED_AT = null;

    public function reviewer()
    {
        return $this->belongsTo(User::class, 'reviewer_user_id');
    }

    public function reviewed()
    {
        return $this->belongsTo(User::class, 'reviewed_user_id');
    }
}

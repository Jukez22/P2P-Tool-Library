<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserSuspension extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'type',
        'reason',
        'suspended_until',
        'is_active',
        'created_by',
    ];

    protected $casts = [
        'suspended_until' => 'datetime',
        'is_active'       => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function librarian()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}

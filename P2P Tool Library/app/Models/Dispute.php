<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Dispute extends Model
{
    use HasFactory;

    protected $fillable = [
        'borrow_id',
        'borrower_id',
        'lender_id',
        'dispute_reason',
        'dispute_status',
        'resolution',
        'librarian_id',
        'deposit_forfeited',
        'forfeited_amount',
        'resolved_at',
    ];

    protected $casts = [
        'deposit_forfeited' => 'boolean',
        'forfeited_amount'  => 'decimal:2',
        'resolved_at'       => 'datetime',
    ];

    public function reservation()
    {
        return $this->belongsTo(Reservation::class, 'borrow_id');
    }

    // The borrower
    public function borrower()
    {
        return $this->belongsTo(User::class, 'borrower_id');
    }

    // The lender
    public function lender()
    {
        return $this->belongsTo(User::class, 'lender_id');
    }

    // Handling librarian
    public function librarian()
    {
        return $this->belongsTo(User::class, 'librarian_id');
    }

    // Dispute evidence
    public function evidences()
    {
        return $this->hasMany(DisputeEvidence::class);
    }
}

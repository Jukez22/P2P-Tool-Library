<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InsuranceClaim extends Model
{
    use HasFactory;

    protected $fillable = [
        'borrow_id',
        'tool_id',
        'claimant_id',
        'claim_type',
        'claim_status',
        'incident_description',
        'estimated_loss',
        'insurance_report',
        'reviewed_by',
        'reviewed_at',
        'completed_at',
    ];

    protected $casts = [
        'estimated_loss' => 'decimal:2',
        'reviewed_at'    => 'datetime',
        'completed_at'   => 'datetime',
    ];

    public function reservation()
    {
        return $this->belongsTo(Reservation::class, 'borrow_id');
    }

    public function tool()
    {
        return $this->belongsTo(Tool::class);
    }

    public function claimant()
    {
        return $this->belongsTo(User::class, 'claimant_id');
    }

    public function reviewer()
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }

    public function evidences()
    {
        return $this->hasMany(InsuranceClaimEvidence::class);
    }
}

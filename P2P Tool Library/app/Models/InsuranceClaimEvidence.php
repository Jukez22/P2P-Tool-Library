<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InsuranceClaimEvidence extends Model
{
    use HasFactory;

    protected $table = 'insurance_claim_evidences';

    protected $fillable = [
        'insurance_claim_id',
        'evidence_type',
        'file_path',
        'uploaded_by',
    ];

    // The insurance claim
    public function claim()
    {
        return $this->belongsTo(InsuranceClaim::class, 'insurance_claim_id');
    }

    // User who uploaded this
    public function uploader()
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }
}

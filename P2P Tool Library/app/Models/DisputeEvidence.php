<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DisputeEvidence extends Model
{
    use HasFactory;

    protected $table = 'dispute_evidences';

    protected $fillable = [
        'dispute_id',
        'uploaded_by',
        'evidence_type',
        'file_path',
        'message',
    ];

    public function dispute()
    {
        return $this->belongsTo(Dispute::class);
    }

    public function uploader()
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }
}

<?php

namespace App\Services;

use App\Models\InsuranceClaim;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Exception;

class InsuranceClaimService
{

    public function calculateCompensation(InsuranceClaim $claim): float
    {
        $toolValue = $claim->tool->price; 
        $lossEstimate = (float) $claim->estimated_loss;

        $compensation = min($toolValue * 0.9, $lossEstimate);

        return round($compensation, 2);
    }

    public function validateClaim(InsuranceClaim $claim): void
    {
        if ($claim->evidences->count() === 0) {
            throw new Exception("Claim cannot be processed without supporting evidence.");
        }

        if ($claim->claim_type === 'theft' && !$claim->evidences()->where('evidence_type', 'police_report')->exists()) {
            throw new Exception("Theft claims require a police report evidence.");
        }
    }

    public function generateInsuranceDocument(InsuranceClaim $claim): string
    {
        return DB::transaction(function () use ($claim) {
            $this->validateClaim($claim);

            $compensation = $this->calculateCompensation($claim);

            $documentData = [
                'claim_reference'      => 'INS-' . str_pad($claim->id, 6, '0', STR_PAD_LEFT),
                'claimant'             => $claim->claimant->name,
                'tool_details'         => $claim->tool->title,
                'incident_type'        => $claim->claim_type,
                'compensation_amount'  => $compensation,
                'status'               => $claim->claim_status,
                'finalized_at'         => now()->toDateTimeString(),
            ];

            $fileName = 'insurance/documents/claim_' . $claim->id . '_final.json';
            Storage::disk('public')->put($fileName, json_encode($documentData, JSON_PRETTY_PRINT));

            return $fileName;
        });
    }
}

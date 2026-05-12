<?php

namespace App\Http\Controllers\Librarian;

use App\Http\Controllers\Controller;

use App\Models\InsuranceClaim;
use App\Models\InsuranceClaimEvidence;
use App\Notifications\InsuranceClaimNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class InsuranceClaimController extends Controller
{
    // Create new insurance claim
    public function createClaim(Request $request)
    {
        $request->validate([
            'borrow_id'            => 'required|exists:reservations,id',
            'tool_id'              => 'required|exists:tools,id',
            'claim_type'           => 'required|in:theft,total_destruction',
            'incident_description' => 'required|string',
            'estimated_loss'       => 'required|numeric',
        ]);

        $claim = InsuranceClaim::create([
            'borrow_id'            => $request->borrow_id,
            'tool_id'              => $request->tool_id,
            'claimant_id'          => Auth::id(),
            'claim_type'           => $request->claim_type,
            'claim_status'         => 'pending',
            'incident_description' => $request->incident_description,
            'estimated_loss'       => $request->estimated_loss,
        ]);

        $claim->claimant->notify(new InsuranceClaimNotification($claim, 'created'));

        return response()->json([
            'message' => 'Insurance claim created successfully',
            'data'    => $claim
        ], 201);
    }

    // Attach evidence to claim
    public function uploadEvidence(Request $request, $claimId)
    {
        $request->validate([
            'evidence_type' => 'required|in:image,video,police_report,receipt,other',
            'file'          => 'required|file|max:20480',
        ]);

        $claim = InsuranceClaim::find($claimId);

        if (!$claim) {
            return response()->json(['message' => 'Insurance claim not found'], 404);
        }

        $filePath = $request->file('file')->store('insurance/evidences', 'public');

        $evidence = InsuranceClaimEvidence::create([
            'insurance_claim_id' => $claimId,
            'evidence_type'      => $request->evidence_type,
            'file_path'          => $filePath,
            'uploaded_by'        => Auth::id(),
        ]);

        $claim->claimant->notify(new InsuranceClaimNotification($claim, 'evidence'));

        return response()->json([
            'message' => 'Evidence uploaded successfully',
            'data'    => $evidence
        ], 201);
    }

    // Generate formal report for the claim
    public function generateReport($claimId)
    {
        $claim = InsuranceClaim::with(['claimant', 'tool', 'borrow', 'evidences'])->find($claimId);

        if (!$claim) {
            return response()->json(['message' => 'Insurance claim not found'], 404);
        }

        $reportData = [
            'claim_id'             => $claim->id,
            'claimant'             => $claim->claimant->name,
            'tool'                 => $claim->tool->title,
            'incident_type'        => $claim->claim_type,
            'incident_description' => $claim->incident_description,
            'estimated_loss'       => $claim->estimated_loss,
            'evidence_count'       => $claim->evidences->count(),
            'generated_at'         => now()->toDateTimeString(),
        ];

        $reportContent = json_encode($reportData, JSON_PRETTY_PRINT);
        $reportPath = 'insurance/reports/claim_' . $claim->id . '_report.json';
        
        \Illuminate\Support\Facades\Storage::disk('public')->put($reportPath, $reportContent);

        $claim->update([
            'insurance_report' => $reportPath
        ]);

        return response()->json([
            'message' => 'Insurance report generated successfully',
            'report_path' => $reportPath,
            'data' => $reportData
        ]);
    }

    // Approve or reject claim
    public function reviewClaim(Request $request, $claimId)
    {
        $request->validate([
            'claim_status' => 'required|in:approved,rejected',
            'review_notes' => 'nullable|string',
        ]);

        $claim = InsuranceClaim::find($claimId);

        if (!$claim) {
            return response()->json(['message' => 'Insurance claim not found'], 404);
        }

        $claim->update([
            'claim_status' => $request->claim_status,
            'reviewed_by'  => Auth::id(),
            'reviewed_at'  => now(),
        ]);

        $claim->claimant->notify(new InsuranceClaimNotification($claim, $request->claim_status));

        return response()->json([
            'message' => 'Insurance claim reviewed successfully',
            'data'    => $claim
        ]);
    }

    // Finalize claim
    public function completeClaim($claimId)
    {
        $claim = InsuranceClaim::find($claimId);

        if (!$claim) {
            return response()->json(['message' => 'Insurance claim not found'], 404);
        }

        $claim->update([
            'claim_status' => 'completed',
            'completed_at' => now(),
        ]);

        $claim->claimant->notify(new InsuranceClaimNotification($claim, 'completed'));

        return response()->json([
            'message' => 'Insurance claim completed successfully',
            'data'    => $claim
        ]);
    }
}

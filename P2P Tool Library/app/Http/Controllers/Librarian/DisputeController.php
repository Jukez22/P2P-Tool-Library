<?php

namespace App\Http\Controllers;

use App\Models\Dispute;
use App\Models\DisputeEvidence;
use App\Models\Reservation;
use App\Notifications\DisputeNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Notification;

class DisputeController extends Controller
{
    // Create new dispute for a borrow
    public function createDispute(Request $request)
    {
        $request->validate([
            'borrow_id'      => 'required|exists:reservations,id',
            'dispute_reason' => 'required|string',
        ]);

        $dispute = Dispute::create([
            'borrow_id'      => $request->borrow_id,
            'borrower_id'    => Auth::id(),
            'lender_id'      => 0, // placeholder
            'dispute_reason' => $request->dispute_reason,
            'dispute_status' => 'pending',
        ]);

        // Notify Lender (and borrower confirmation)
        Notification::send(Auth::user(), new DisputeNotification($dispute, 'created'));

        return response()->json([
            'message' => 'Dispute created successfully',
            'data'    => $dispute
        ], 201);
    }

    // Upload evidence for dispute
    public function uploadEvidence(Request $request, $disputeId)
    {
        $request->validate([
            'evidence_type' => 'required|in:image,video,log',
            'file'          => 'nullable|file|max:10240', // max 10MB
            'message'       => 'nullable|string',
        ]);

        $dispute = Dispute::find($disputeId);

        if (!$dispute) {
            return response()->json(['message' => 'Dispute not found'], 404);
        }

        $filePath = null;
        if ($request->hasFile('file')) {
            $filePath = $request->file('file')->store('disputes/evidences', 'public');
        }

        $evidence = DisputeEvidence::create([
            'dispute_id'    => $disputeId,
            'uploaded_by'   => Auth::id(),
            'evidence_type' => $request->evidence_type,
            'file_path'     => $filePath,
            'message'       => $request->message,
        ]);

        // Notify parties
        $dispute->borrower->notify(new DisputeNotification($dispute, 'evidence'));

        return response()->json([
            'message' => 'Evidence uploaded successfully',
            'data'    => $evidence
        ], 201);
    }

    // List pending disputes
    public function getPendingDisputes()
    {
        $disputes = Dispute::with(['borrower', 'lender', 'evidences', 'borrow'])
            ->where('dispute_status', 'pending')
            ->get();

        return response()->json([
            'data' => $disputes
        ]);
    }

    // Start reviewing a dispute
    public function startReview($disputeId)
    {
        $dispute = Dispute::find($disputeId);

        if (!$dispute) {
            return response()->json(['message' => 'Dispute not found'], 404);
        }

        $dispute->update([
            'dispute_status' => 'under_review',
            'librarian_id'   => Auth::id(),
        ]);

        // Notify both parties
        $dispute->borrower->notify(new DisputeNotification($dispute, 'review'));

        return response()->json([
            'message' => 'Dispute review started',
            'data'    => $dispute
        ]);
    }

    // Resolve dispute with final decision
    public function resolveDispute(Request $request, $disputeId)
    {
        $request->validate([
            'resolution'        => 'required|string',
            'deposit_forfeited' => 'required|boolean',
            'forfeited_amount'  => 'required_if:deposit_forfeited,true|nullable|numeric|min:0',
        ]);

        $dispute = Dispute::find($disputeId);

        if (!$dispute) {
            return response()->json(['message' => 'Dispute not found'], 404);
        }

        $dispute->update([
            'dispute_status'    => 'resolved',
            'resolution'        => $request->resolution,
            'deposit_forfeited' => $request->deposit_forfeited,
            'forfeited_amount'  => $request->deposit_forfeited ? $request->forfeited_amount : 0,
            'resolved_at'       => Carbon::now(),
        ]);

        // Notify parties
        $dispute->borrower->notify(new DisputeNotification($dispute, 'resolved'));

        return response()->json([
            'message' => 'Dispute resolved successfully',
            'data'    => $dispute
        ]);
    }

    // Reject dispute
    public function rejectDispute(Request $request, $disputeId)
    {
        $request->validate([
            'resolution' => 'required|string',
        ]);

        $dispute = Dispute::find($disputeId);

        if (!$dispute) {
            return response()->json(['message' => 'Dispute not found'], 404);
        }

        $dispute->update([
            'dispute_status' => 'rejected',
            'resolution'     => $request->resolution,
            'resolved_at'    => Carbon::now(),
        ]);

        // Notify parties
        $dispute->borrower->notify(new DisputeNotification($dispute, 'rejected'));

        return response()->json([
            'message' => 'Dispute rejected successfully',
            'data'    => $dispute
        ]);
    }
}

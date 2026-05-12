<?php

namespace App\Http\Controllers\Librarian;

use App\Http\Controllers\Controller;

use App\Models\InventoryAudit;
use App\Models\InventoryAuditItem;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class InventoryAuditController extends Controller
{
    // Generate new inventory audit for a lender
    public function generateAudit(Request $request)
    {
        $request->validate([
            'lender_id' => 'required|exists:users,id',
            'tool_ids'  => 'required|array',
            'tool_ids.*' => 'exists:tools,id',
        ]);

        $audit = InventoryAudit::create([
            'lender_id'    => $request->lender_id,
            'audit_status' => 'pending',
            'assigned_at'  => Carbon::now(),
            'expires_at'   => Carbon::now()->addDays(3),
        ]);

        foreach ($request->tool_ids as $toolId) {
            InventoryAuditItem::create([
                'inventory_audit_id' => $audit->id,
                'tool_id'           => $toolId,
                'item_status'       => 'pending',
            ]);
        }

        return response()->json([
            'message' => 'Inventory audit generated successfully',
            'data'    => $audit->load('items')
        ], 201);
    }

    // Submit proof (image/video) for an audit item
    public function submitAuditProof(Request $request, $auditItemId)
    {
        $request->validate([
            'proof_image' => 'nullable|image|max:5120', // max 5MB
            'proof_video' => 'nullable|mimes:mp4,mov,avi|max:20480', // max 20MB
        ]);

        $item = InventoryAuditItem::find($auditItemId);

        if (!$item) {
            return response()->json(['message' => 'Audit item not found'], 404);
        }

        $data = ['item_status' => 'submitted'];

        if ($request->hasFile('proof_image')) {
            $data['proof_image'] = $request->file('proof_image')->store('audits/images', 'public');
        }

        if ($request->hasFile('proof_video')) {
            $data['proof_video'] = $request->file('proof_video')->store('audits/videos', 'public');
        }

        $item->update($data);

        // Check if audit is fully submitted
        $audit = $item->audit;
        if ($audit->items()->where('item_status', 'pending')->count() === 0) {
            $audit->update([
                'audit_status' => 'submitted',
                'submitted_at' => Carbon::now()
            ]);
        }

        return response()->json([
            'message' => 'Audit proof submitted successfully',
            'data'    => $item
        ]);
    }

    // Review audit item (Approve/Reject)
    public function reviewAuditItem(Request $request, $auditItemId)
    {
        $request->validate([
            'status'           => 'required|in:approved,rejected',
            'rejection_reason' => 'required_if:status,rejected|nullable|string|max:1000',
        ]);

        $item = InventoryAuditItem::find($auditItemId);

        if (!$item) {
            return response()->json(['message' => 'Audit item not found'], 404);
        }

        $item->update([
            'item_status'      => $request->status,
            'rejection_reason' => $request->status === 'rejected' ? $request->rejection_reason : null,
        ]);

        // Check if overall audit is reviewed
        $audit = $item->audit;
        $unreviewedCount = $audit->items()
            ->whereIn('item_status', ['pending', 'submitted'])
            ->count();

        if ($unreviewedCount === 0) {
            // Determine final audit status
            $hasRejected = $audit->items()->where('item_status', 'rejected')->exists();
            
            $audit->update([
                'audit_status' => $hasRejected ? 'rejected' : 'approved',
                'reviewed_at'  => Carbon::now(),
                'reviewer_id'  => auth()->id(),
            ]);
        }

        return response()->json([
            'message' => "Audit item {$request->status} successfully",
            'data'    => $item->load('audit')
        ]);
    }

    // Finalize overall status of an inventory audit
    public function completeAudit($auditId)
    {
        $audit = InventoryAudit::with('items')->find($auditId);

        if (!$audit) {
            return response()->json(['message' => 'Inventory audit not found'], 404);
        }

        $unreviewedCount = $audit->items()
            ->whereIn('item_status', ['pending', 'submitted'])
            ->count();

        if ($unreviewedCount > 0) {
            return response()->json(['message' => 'All items must be reviewed before completing the audit'], 422);
        }

        $hasRejected = $audit->items()->where('item_status', 'rejected')->exists();

        $audit->update([
            'audit_status' => $hasRejected ? 'rejected' : 'approved',
            'reviewed_at'  => Carbon::now(),
            'reviewer_id'  => auth()->id(),
        ]);

        return response()->json([
            'message' => 'Inventory audit completed successfully',
            'data'    => $audit
        ]);
    }

    // List pending audits
    public function getPendingAudits()
    {
        $audits = InventoryAudit::with(['lender', 'items.tool'])
            ->where('audit_status', 'pending')
            ->get();

        return response()->json([
            'data' => $audits
        ]);
    }
}

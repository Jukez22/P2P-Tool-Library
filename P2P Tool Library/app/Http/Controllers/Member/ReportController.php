<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use App\Models\Report;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReportController extends Controller
{
    // List user reports
    public function index()
    {
        $reports = Report::where('reporter_id', Auth::id())->get();
        return response()->json($reports);
    }

    // New report
    public function store(Request $request)
    {
        $request->validate([
            'reason'           => 'required|in:damaged_tool,no_show,fraud,late_return,other',
            'description'      => 'required|string|min:10',
            'reported_user_id' => 'nullable|integer',
            'reported_tool_id' => 'nullable|integer',
            'reservation_id'   => 'nullable|integer',
        ]);

        $report = Report::create([
            'reporter_id'      => Auth::id(),
            'reported_user_id' => $request->reported_user_id,
            'reported_tool_id' => $request->reported_tool_id,
            'reservation_id'   => $request->reservation_id,
            'reason'           => $request->reason,
            'description'      => $request->description,
            'status'           => 'pending',
        ]);

        return response()->json($report, 201);
    }

    // View specific report
    public function show($id)
    {
        $report = Report::where('id', $id)
                        ->where('reporter_id', Auth::id())
                        ->first();

        if (!$report) {
            return response()->json(['message' => 'Report not found'], 404);
        }

        return response()->json($report);
    }

    // Delete pending report
    public function destroy($id)
    {
        $report = Report::where('id', $id)
                        ->where('reporter_id', Auth::id())
                        ->first();

        if (!$report) {
            return response()->json(['message' => 'Report not found'], 404);
        }

        if ($report->status !== 'pending') {
            return response()->json(['message' => 'Reviewed reports cannot be deleted'], 403);
        }

        $report->delete();

        return response()->json(['message' => 'Report deleted successfully']);
    }
}

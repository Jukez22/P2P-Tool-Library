<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use App\Models\Report;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReportController extends Controller
{

    public function index()
    {
        $reports = Report::where('reporter_id', Auth::id())->get();
        return response()->json($reports);
    }

    public function create(Request $request)
    {
        $reservation_id = $request->reservation_id;
        $tool_id = $request->tool_id;

        $reported_user_id = null;
        if ($reservation_id) {
            $reservation = \App\Models\Reservation::with('tool')->find($reservation_id);
            if ($reservation) {

                $reported_user_id = (auth()->id() == $reservation->borrower_id) 
                    ? $reservation->tool->owner_id 
                    : $reservation->borrower_id;
            }
        }

        return view('member.reports.create', compact('reservation_id', 'tool_id', 'reported_user_id'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'reason'           => 'required|in:damaged_tool,no_show,fraud,late_return,other',
            'description'      => 'required|string|min:10',
            'reported_user_id' => 'nullable|integer',
            'reported_tool_id' => 'nullable|integer',
            'reservation_id'   => 'nullable|integer',
        ]);

        Report::create([
            'reporter_id'      => auth()->id(),
            'reported_user_id' => $request->reported_user_id,
            'reported_tool_id' => $request->reported_tool_id,
            'reservation_id'   => $request->reservation_id,
            'reason'           => $request->reason,
            'description'      => $request->description,
            'status'           => 'pending',
        ]);

        return redirect()->route('member.dashboard')->with('success', 'Damage report submitted. Our team will review it soon.');
    }

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

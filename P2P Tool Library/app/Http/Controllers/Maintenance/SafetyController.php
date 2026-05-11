<?php

namespace App\Http\Controllers\Maintenance;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Tool;
use App\Models\Reservation;

class SafetyController extends Controller
{
    public function updateSafetyCertification(Request $request)
    {
        $request->validate([
            'tool_id' => 'required|exists:tools,id',
            'safety_cert_expiry_date' => 'required|date',
        ]);

        $tool = Tool::findOrFail($request->tool_id);
        $tool->safety_cert_expiry_date = $request->safety_cert_expiry_date;
        $tool->save();

        return response()->json([
            'message' => 'Safety certification updated successfully',
            'tool' => $tool
        ]);
    }


    public function markUnfit(Request $request)
    {
        $request->validate([
            'tool_id' => 'required|exists:tools,id',
        ]);

        $tool = Tool::findOrFail($request->tool_id);
        $tool->is_unfit = true;
        $tool->condition_status = 'poor';
        $tool->save();

        $now = now();
        Reservation::where('tool_id', $tool->id)
            ->where('start_datetime', '>', $now)
            ->where('status', 'pending')
            ->update(['status' => 'cancelled']);

        return response()->json([
            'message' => 'Tool marked as unfit. Future reservations cancelled.',
            'tool' => $tool
        ]);
    }
}

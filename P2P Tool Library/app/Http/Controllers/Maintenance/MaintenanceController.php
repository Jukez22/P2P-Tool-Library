<?php

namespace App\Http\Controllers\Maintenance;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Tool;
use Carbon\Carbon;

class MaintenanceController extends Controller
{

    public function logUsage(Request $request)
    {
        $request->validate([
            'tool_id' => 'required|exists:tools,id',
        ]);

        $tool = Tool::findOrFail($request->tool_id);
        
        $tool->usage_count += 1;
        
        if ($tool->usage_count >= $tool->maintenance_interval_uses) {
            $tool->needs_inspection = true;
        }
        
        $tool->save();

        return response()->json([
            'message' => 'Tool usage logged successfully',
            'tool' => $tool
        ]);
    }

    public function checkWarrantyExpiry()
    {
        $thirtyDaysFromNow = Carbon::now()->addDays(30);
        $today = Carbon::now();

        $expiringTools = Tool::whereNotNull('warranty_expiry_date')
            ->whereBetween('warranty_expiry_date', [$today, $thirtyDaysFromNow])
            ->get();

        return response()->json([
            'message' => 'Warranty expiry check completed',
            'expiring_tools' => $expiringTools
        ]);
    }
    public function getHistoryPortfolio($toolId)
    {
        $tool = Tool::with(['maintenanceLogs' => function ($query) {
            $query->where('status', 'done')->orderBy('date', 'desc');
        }])->findOrFail($toolId);

        return response()->json([
            'tool_id' => $tool->id,
            'title' => $tool->title,
            'history' => $tool->maintenanceLogs
        ]);
    }

    public function disposeTool(Request $request)
    {
        $request->validate([
            'tool_id' => 'required|exists:tools,id',
            'reason' => 'required|string',
            'disposal_method' => 'required|in:recycle,trashed,donated',
            'disposed_at' => 'required|date'
        ]);

        $tool = Tool::findOrFail($request->tool_id);
        $tool->condition_status = 'poor';
        $tool->is_unfit = true;
        $tool->save();

        $disposal = \App\Models\Disposal::create([
            'tool_id' => $request->tool_id,
            'reason' => $request->reason,
            'disposal_method' => $request->disposal_method,
            'disposed_at' => $request->disposed_at
        ]);

        return response()->json([
            'message' => 'Tool disposed successfully',
            'disposal_record' => $disposal
        ]);
    }

    public function getPriorityQueue()
    {
        $queue = \App\Models\MaintenanceLog::with('tool')
            ->whereIn('status', ['scheduled', 'in-progress', '']) // 'pending' equivalents
            ->join('tools', 'maintenance_logs.tool_id', '=', 'tools.id')
            ->leftJoin('reservations', 'tools.id', '=', 'reservations.tool_id')
            ->select('maintenance_logs.*') // Select only log columns
            ->selectRaw('count(reservations.id) as past_reservations')
            ->groupBy('maintenance_logs.id')
            ->orderBy('past_reservations', 'desc')
            ->get();

        return response()->json([
            'message' => 'Priority queue generated',
            'queue' => $queue
        ]);
    }
}

<?php

namespace App\Http\Controllers\Maintenance;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\RepairCostEstimate;
use App\Models\ExternalRepair;
use App\Models\Tool;

class RepairController extends Controller
{

    public function getCostEstimate(Request $request)
    {
        $request->validate([
            'category_id' => 'required|exists:categories,id',
        ]);

        $query = RepairCostEstimate::where('category_id', $request->category_id);

        if ($request->has('issue_name')) {
            $query->where('issue_name', 'like', '%' . $request->issue_name . '%');
        }

        $estimates = $query->get();

        return response()->json([
            'message' => 'Repair cost estimates retrieved',
            'estimates' => $estimates
        ]);
    }

    public function dispatchExternalRepair(Request $request)
    {
        $request->validate([
            'tool_id' => 'required|exists:tools,id',
            'shop_name' => 'required|string',
            'expected_return_date' => 'nullable|date',
            'maintenance_log_id' => 'nullable|exists:maintenance_logs,id'
        ]);

        $repair = ExternalRepair::create([
            'tool_id' => $request->tool_id,
            'maintenance_log_id' => $request->maintenance_log_id,
            'shop_name' => $request->shop_name,
            'dispatch_date' => now()->toDateString(),
            'expected_return_date' => $request->expected_return_date,
            'status' => 'dispatched'
        ]);

        $tool = Tool::findOrFail($request->tool_id);
        $tool->is_unfit = true; 
        $tool->save();

        return response()->json([
            'message' => 'Tool dispatched for external repair',
            'external_repair' => $repair
        ]);
    }

    public function orderSparePart(Request $request)
    {
        $request->validate([
            'tool_id' => 'required|exists:tools,id',
            'part_name' => 'required|string',
            'expected_arrival_date' => 'nullable|date'
        ]);

        $order = \App\Models\SparePartOrder::create([
            'tool_id' => $request->tool_id,
            'part_name' => $request->part_name,
            'order_date' => now()->toDateString(),
            'expected_arrival_date' => $request->expected_arrival_date,
            'status' => 'ordered'
        ]);

        return response()->json([
            'message' => 'Spare part ordered successfully',
            'order' => $order
        ]);
    }

    public function updateSparePartStatus(Request $request)
    {
        $request->validate([
            'order_id' => 'required|exists:spare_part_orders,id',
            'status' => 'required|in:ordered,arrived,installed'
        ]);

        $order = \App\Models\SparePartOrder::findOrFail($request->order_id);
        $order->status = $request->status;
        $order->save();

        return response()->json([
            'message' => 'Spare part order status updated',
            'order' => $order
        ]);
    }

    public function getTechnicianMetrics($technicianId)
    {
        $logs = \App\Models\MaintenanceLog::where('technician_id', $technicianId)
            ->whereNotNull('completed_at')
            ->whereNotNull('started_at')
            ->get();

        $totalCompleted = $logs->count();
        $totalSuccessful = $logs->where('is_successful', true)->count();

        $successRate = $totalCompleted > 0 ? ($totalSuccessful / $totalCompleted) * 100 : 0;

        $totalTimeMinutes = 0;
        foreach ($logs as $log) {
            $totalTimeMinutes += $log->completed_at->diffInMinutes($log->started_at);
        }

        $avgCompletionTimeMinutes = $totalCompleted > 0 ? ($totalTimeMinutes / $totalCompleted) : 0;

        return response()->json([
            'technician_id' => $technicianId,
            'total_repairs_completed' => $totalCompleted,
            'success_rate_percentage' => round($successRate, 2),
            'avg_completion_time_minutes' => round($avgCompletionTimeMinutes, 2)
        ]);
    }
}

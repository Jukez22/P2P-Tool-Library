<?php

namespace App\Http\Controllers\Maintenance;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Tool;
use Carbon\Carbon;

class MaintenanceController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        
        // 1. Priority Queue
        $queue = \App\Models\MaintenanceLog::with('tool')
            ->whereIn('status', ['scheduled', 'in-progress', 'pending'])
            ->get();

        // 2. Usage Triggers
        $toolsForTriggers = Tool::all();

        // 3. Safety Certs — guard against missing column
        $safetyTools = \Illuminate\Support\Facades\Schema::hasColumn('tools', 'safety_cert_expiry_date')
            ? Tool::whereNotNull('safety_cert_expiry_date')->get()
            : collect();

        // 4. Battery Health — use the dedicated BatteryHealthLog model
        $batteryTools = \App\Models\BatteryHealthLog::with('tool')->latest()->get();

        // 5. Cost Estimator Data
        $categories = \App\Models\Category::all();
        $estimates = \App\Models\RepairCostEstimate::all();

        // 6. External Repairs
        $externalRepairs = \App\Models\ExternalRepair::with('tool')->get();

        // 7. Spare Part Orders
        $sparePartOrders = \App\Models\SparePartOrder::with('tool')->get();

        // 8. Consumables
        $consumables = \App\Models\Consumable::all();

        // 9. Warranty Alerts — guard against missing column
        $warrantyTools = \Illuminate\Support\Facades\Schema::hasColumn('tools', 'warranty_expiry_date')
            ? Tool::whereNotNull('warranty_expiry_date')->orderBy('warranty_expiry_date', 'asc')->get()
            : collect();

        // 10. Disposal Workflow
        $disposals = \App\Models\Disposal::with('tool')->get();

        // 11. Knowledge Base
        $articles = \App\Models\DiagnosticArticle::all();

        // 12. Tech Metrics (Self)
        $metrics = $this->getTechnicianMetrics($user->id)->getData();

        return view('maintenance.dashboard', compact(
            'user', 'queue', 'toolsForTriggers', 'safetyTools', 'batteryTools', 
            'categories', 'estimates', 'externalRepairs', 'sparePartOrders', 
            'consumables', 'warrantyTools', 'disposals', 'articles', 'metrics'
        ));
    }


    public function store(Request $request)
    {
        $request->validate([
            'tool_id'   => 'required|exists:tools,id',
            'threshold' => 'required|integer|min:1',
        ]);

        $tool = Tool::findOrFail($request->tool_id);
        $tool->maintenance_interval_uses = $request->threshold;
        $tool->save();

        return redirect()->route('maintenance.dashboard')
            ->with('success', 'Usage trigger threshold saved for ' . $tool->title . '.');
    }

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
            $totalTimeMinutes += \Carbon\Carbon::parse($log->completed_at)->diffInMinutes(\Carbon\Carbon::parse($log->started_at));
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

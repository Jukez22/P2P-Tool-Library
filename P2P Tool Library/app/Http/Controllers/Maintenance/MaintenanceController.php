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

        $queue = \App\Models\MaintenanceLog::with('tool')
            ->whereIn('status', ['scheduled', 'in-progress'])
            ->get();

        $toolsForTriggers = Tool::all();

        $safetyTools = \Illuminate\Support\Facades\Schema::hasColumn('tools', 'safety_cert_expiry_date')
            ? Tool::whereNotNull('safety_cert_expiry_date')->get()
            : collect();

        $batteryTools = \App\Models\BatteryHealthLog::with('tool')->latest()->get();

        $categories = \App\Models\Category::all();
        $estimates = \App\Models\RepairCostEstimate::all();

        $externalRepairs = \App\Models\ExternalRepair::with('tool')->get();

        $sparePartOrders = \App\Models\SparePartOrder::with('tool')->get();

        $consumables = \App\Models\Consumable::all();

        $warrantyTools = \Illuminate\Support\Facades\Schema::hasColumn('tools', 'warranty_expiry_date')
            ? Tool::whereNotNull('warranty_expiry_date')->orderBy('warranty_expiry_date', 'asc')->get()
            : collect();

        $disposals = \App\Models\Disposal::with('tool')->get();

        $articles = \App\Models\DiagnosticArticle::all();

        $metrics = $this->getTechnicianMetrics($user->id)->getData();

        return view('maintenance.dashboard', compact(
            'user', 'queue', 'toolsForTriggers', 'safetyTools', 'batteryTools', 
            'categories', 'estimates', 'externalRepairs', 'sparePartOrders', 
            'consumables', 'warrantyTools', 'disposals', 'articles', 'metrics'
        ));
    }

    public function startWork(Request $request)
    {
        $request->validate([
            'log_id' => 'required|exists:maintenance_logs,id',
        ]);

        $log = \App\Models\MaintenanceLog::findOrFail($request->log_id);
        $log->status = 'in-progress';
        $log->technician_id = auth()->id();
        $log->started_at = now();
        $log->save();

        return redirect()->route('maintenance.dashboard')
            ->with('success', 'Started work on: ' . ($log->tool->title ?? 'Tool') . '.');
    }

    public function completeWork(Request $request)
    {
        $request->validate([
            'log_id' => 'required|exists:maintenance_logs,id',
            'is_successful' => 'required|boolean',
        ]);

        $log = \App\Models\MaintenanceLog::findOrFail($request->log_id);
        $log->status = 'done';
        $log->completed_at = now();
        $log->is_successful = $request->is_successful;
        $log->save();

        return redirect()->route('maintenance.dashboard')
            ->with('success', 'Completed work on: ' . ($log->tool->title ?? 'Tool') . '.');
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
            ->whereIn('status', ['scheduled', 'in-progress', '']) 
            ->join('tools', 'maintenance_logs.tool_id', '=', 'tools.id')
            ->leftJoin('reservations', 'tools.id', '=', 'reservations.tool_id')
            ->select('maintenance_logs.*') 
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

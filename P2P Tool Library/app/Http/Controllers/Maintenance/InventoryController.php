<?php

namespace App\Http\Controllers\Maintenance;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Consumable;

class InventoryController extends Controller
{
    public function trackConsumable(Request $request)
    {
        $request->validate([
            'consumable_id' => 'required|exists:consumables,id',
            'quantity_used' => 'required|integer|min:1',
        ]);

        $consumable = Consumable::findOrFail($request->consumable_id);
        $consumable->stock_level -= $request->quantity_used;
        
        if ($consumable->stock_level < 0) {
            $consumable->stock_level = 0;
        }
        
        $consumable->save();

        $needsReorder = $consumable->stock_level <= $consumable->reorder_threshold;

        return response()->json([
            'message' => 'Consumable stock updated',
            'consumable' => $consumable,
            'needs_reorder' => $needsReorder
        ]);
    }
    public function logBatteryHealth(Request $request)
    {
        $request->validate([
            'tool_id' => 'required|exists:tools,id',
            'charge_cycles' => 'required|integer|min:0',
            'health_percentage' => 'required|integer|min:0|max:100',
        ]);

        $log = \App\Models\BatteryHealthLog::create([
            'tool_id' => $request->tool_id,
            'charge_cycles' => $request->charge_cycles,
            'health_percentage' => $request->health_percentage,
            'logged_at' => now(),
        ]);

        return response()->json([
            'message' => 'Battery health logged successfully',
            'log' => $log
        ]);
    }
}

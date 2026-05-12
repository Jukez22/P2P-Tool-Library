<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Category;
use App\Models\Tool;
use App\Models\Consumable;
use App\Models\RepairCostEstimate;
use App\Models\DiagnosticArticle;
use App\Models\BatteryHealthLog;
use App\Models\ExternalRepair;
use App\Models\Disposal;
use App\Models\SparePartOrder;
use App\Models\MaintenanceLog;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;

class MaintenanceTestSeeder extends Seeder
{
    public function run(): void
    {
        $user = User::firstOrCreate(
            ['email' => 'maintenance.tester@example.com'],
            [
                'name' => 'Maintenance Tester',
                'password' => Hash::make('password'),
                'role' => 'technician',
                'phone' => '+1234567890',
                'address' => '123 Test St',
                'trust_score' => 5,
                'membership_tier_id' => 1
            ]
        );

        $category = Category::firstOrCreate(
            ['name' => 'Power Tools'],
            ['description' => 'Various power tools']
        );

        Consumable::firstOrCreate(['name' => 'Drill Bits Set'], ['stock_level' => 3, 'reorder_threshold' => 10]);
        Consumable::firstOrCreate(['name' => 'Lubricant Oil'], ['stock_level' => 15, 'reorder_threshold' => 5]);
        Consumable::firstOrCreate(['name' => 'Sanding Paper'], ['stock_level' => 0, 'reorder_threshold' => 20]);

        RepairCostEstimate::firstOrCreate(['issue_name' => 'Motor Replacement'], ['estimated_cost' => 120.00, 'category_id' => $category->id]);
        RepairCostEstimate::firstOrCreate(['issue_name' => 'Power Cord Repair'], ['estimated_cost' => 35.50, 'category_id' => $category->id]);

        DiagnosticArticle::firstOrCreate(
            ['title' => 'Drill Motor Diagnosis'],
            ['content' => 'If the drill sparks excessively, check the carbon brushes...', 'author_id' => $user->id]
        );

        $baseTool = [
            'price' => 20.00,
            'description' => 'Test tool for maintenance module.',
            'condition_status' => 'Good',
            'is_boosted' => false,
            'location_lng' => 0.0,
            'location_lat' => 0.0,
            'category_id' => $category->id,
            'owner_id' => $user->id,
            'maintenance_interval_uses' => 10,
        ];

        $tool1 = Tool::create(array_merge($baseTool, [
            'title' => 'Standard Drill (Good Condition)',
            'usage_count' => 2,
            'needs_inspection' => false,
            'is_unfit' => false,
            'safety_cert_expiry_date' => Carbon::now()->addMonths(6),
            'warranty_expiry_date' => Carbon::now()->addYears(1),
        ]));

        $tool2 = Tool::create(array_merge($baseTool, [
            'title' => 'High Usage Saw (Requires Maintenance)',
            'usage_count' => 15,
            'needs_inspection' => false,
            'is_unfit' => false,
            'safety_cert_expiry_date' => Carbon::now()->addMonths(6),
        ]));

        Tool::create(array_merge($baseTool, [
            'title' => 'Dropped Hammer Drill (Needs Inspection)',
            'usage_count' => 5,
            'needs_inspection' => true,
            'is_unfit' => false,
        ]));

        Tool::create(array_merge($baseTool, [
            'title' => 'Old Welder (Safety Cert Expired)',
            'usage_count' => 3,
            'needs_inspection' => false,
            'is_unfit' => false,
            'safety_cert_expiry_date' => Carbon::now()->subDays(5),
        ]));

        Tool::create(array_merge($baseTool, [
            'title' => 'Angle Grinder (Warranty Expired)',
            'usage_count' => 8,
            'warranty_expiry_date' => Carbon::now()->subMonth(),
        ]));

        Tool::create(array_merge($baseTool, [
            'title' => 'Broken Jigsaw (Unfit for Use)',
            'condition_status' => 'Needs Repair',
            'is_unfit' => true,
        ]));

        $batteryTool = Tool::create(array_merge($baseTool, [
            'title' => 'Cordless Drill (Battery Monitored)',
        ]));
        BatteryHealthLog::create([
            'tool_id' => $batteryTool->id,
            'charge_cycles' => 150,
            'health_percentage' => 75,
            'logged_at' => Carbon::now()
        ]);

        $repairTool = Tool::create(array_merge($baseTool, [
            'title' => 'Table Saw (Out for Repair)',
            'is_unfit' => true,
        ]));
        ExternalRepair::create([
            'tool_id' => $repairTool->id,
            'shop_name' => 'Bob\'s Fix-it Shop',
            'dispatch_date' => Carbon::now()->subDays(2),
            'expected_return_date' => Carbon::now()->addDays(5),
            'status' => 'dispatched'
        ]);

        $disposedTool = Tool::create(array_merge($baseTool, [
            'title' => 'Burnt Out Sander (Disposed)',
            'is_unfit' => true,
        ]));
        Disposal::create([
            'tool_id' => $disposedTool->id,
            'reason' => 'Motor completely burnt out, unrepairable.',
            'disposal_method' => 'recycle',
            'disposed_at' => Carbon::now()->subDay()
        ]);

        $sparePartTool = Tool::create(array_merge($baseTool, [
            'title' => 'Air Compressor (Awaiting Parts)',
        ]));
        SparePartOrder::create([
            'tool_id' => $sparePartTool->id,
            'part_name' => 'Pressure Valve',
            'order_date' => Carbon::now()->subDays(1),
            'expected_arrival_date' => Carbon::now()->addDays(3),
            'status' => 'ordered'
        ]);

        MaintenanceLog::create([
            'tool_id' => $tool1->id,
            'status' => 'scheduled',
            'technician_id' => $user->id,
            'description' => 'Routine cleaning and safety check',
            'cost' => 0.00,
            'date' => Carbon::now()->format('Y-m-d')
        ]);

        MaintenanceLog::create([
            'tool_id' => $tool2->id,
            'status' => 'in-progress',
            'technician_id' => $user->id,
            'description' => 'Replacing worn blade bearings',
            'cost' => 50.00,
            'started_at' => Carbon::now()->subHours(2),
            'date' => Carbon::now()->format('Y-m-d')
        ]);

        MaintenanceLog::create([
            'tool_id' => $tool1->id,
            'status' => 'done',
            'technician_id' => $user->id,
            'description' => 'Motor brushes alignment and switch servicing',
            'cost' => 30.00,
            'started_at' => Carbon::now()->subDays(2)->subMinutes(45),
            'completed_at' => Carbon::now()->subDays(2),
            'is_successful' => true,
            'date' => Carbon::now()->subDays(2)->format('Y-m-d')
        ]);

        MaintenanceLog::create([
            'tool_id' => $batteryTool->id ?? $tool1->id,
            'status' => 'done',
            'technician_id' => $user->id,
            'description' => 'Battery terminal cleaning and diagnostics check',
            'cost' => 15.00,
            'started_at' => Carbon::now()->subDays(1)->subMinutes(30),
            'completed_at' => Carbon::now()->subDays(1),
            'is_successful' => true,
            'date' => Carbon::now()->subDays(1)->format('Y-m-d')
        ]);
    }
}

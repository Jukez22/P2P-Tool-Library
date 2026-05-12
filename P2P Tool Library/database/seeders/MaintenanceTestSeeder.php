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
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;

class MaintenanceTestSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Ensure we have an owner User
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

        // 2. Ensure we have a Category
        $category = Category::firstOrCreate(
            ['name' => 'Power Tools'],
            ['description' => 'Various power tools']
        );

        // 3. Seed Consumables
        Consumable::firstOrCreate(['name' => 'Drill Bits Set'], ['stock_level' => 3, 'reorder_threshold' => 10]);
        Consumable::firstOrCreate(['name' => 'Lubricant Oil'], ['stock_level' => 15, 'reorder_threshold' => 5]);
        Consumable::firstOrCreate(['name' => 'Sanding Paper'], ['stock_level' => 0, 'reorder_threshold' => 20]);

        // 4. Seed Repair Cost Estimates
        RepairCostEstimate::firstOrCreate(['issue_name' => 'Motor Replacement'], ['estimated_cost' => 120.00, 'category_id' => $category->id]);
        RepairCostEstimate::firstOrCreate(['issue_name' => 'Power Cord Repair'], ['estimated_cost' => 35.50, 'category_id' => $category->id]);

        // 5. Seed Diagnostic Articles
        DiagnosticArticle::firstOrCreate(
            ['title' => 'Drill Motor Diagnosis'],
            ['content' => 'If the drill sparks excessively, check the carbon brushes...', 'author_id' => $user->id]
        );

        // 6. Seed Tools with specific conditions
        
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

        // Tool 1: Standard Tool
        Tool::create(array_merge($baseTool, [
            'title' => 'Standard Drill (Good Condition)',
            'usage_count' => 2,
            'needs_inspection' => false,
            'is_unfit' => false,
            'safety_cert_expiry_date' => Carbon::now()->addMonths(6),
            'warranty_expiry_date' => Carbon::now()->addYears(1),
        ]));

        // Tool 2: High Usage Tool (Needs Maintenance)
        Tool::create(array_merge($baseTool, [
            'title' => 'High Usage Saw (Requires Maintenance)',
            'usage_count' => 15, // Greater than maintenance_interval_uses (10)
            'needs_inspection' => false,
            'is_unfit' => false,
            'safety_cert_expiry_date' => Carbon::now()->addMonths(6),
        ]));

        // Tool 3: Needs Inspection
        Tool::create(array_merge($baseTool, [
            'title' => 'Dropped Hammer Drill (Needs Inspection)',
            'usage_count' => 5,
            'needs_inspection' => true,
            'is_unfit' => false,
        ]));

        // Tool 4: Expired Safety Certificate
        Tool::create(array_merge($baseTool, [
            'title' => 'Old Welder (Safety Cert Expired)',
            'usage_count' => 3,
            'needs_inspection' => false,
            'is_unfit' => false,
            'safety_cert_expiry_date' => Carbon::now()->subDays(5),
        ]));

        // Tool 5: Warranty Expired
        Tool::create(array_merge($baseTool, [
            'title' => 'Angle Grinder (Warranty Expired)',
            'usage_count' => 8,
            'warranty_expiry_date' => Carbon::now()->subMonth(),
        ]));

        // Tool 6: Unfit for Use
        Tool::create(array_merge($baseTool, [
            'title' => 'Broken Jigsaw (Unfit for Use)',
            'condition_status' => 'Needs Repair',
            'is_unfit' => true,
        ]));

        // Tool 7: Battery Health Log Tool
        $batteryTool = Tool::create(array_merge($baseTool, [
            'title' => 'Cordless Drill (Battery Monitored)',
        ]));
        BatteryHealthLog::create([
            'tool_id' => $batteryTool->id,
            'charge_cycles' => 150,
            'health_percentage' => 75,
            'logged_at' => Carbon::now()
        ]);

        // Tool 8: In External Repair
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

        // Tool 9: Disposed Tool
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

        // Tool 10: Spare Part Ordered
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
    }
}

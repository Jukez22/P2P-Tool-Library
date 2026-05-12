<?php

namespace Database\Seeders;

use App\Models\ToolCategory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ToolCategorySeeder extends Seeder
{
    // Run seeds
    public function run(): void
    {
        $categories = [
            'Power Tools' => [
                'Drills & Drivers',
                'Saws & Cutting Tools',
                'Sanders & Grinders',
            ],
            'Hand Tools' => [
                'Hammers & Striking',
                'Wrenches & Sockets',
                'Screwdrivers & Nut Drivers',
                'Pliers & Cutters',
            ],
            'Garden Tools' => [
                'Lawn Mowers',
                'Hedge Trimmers',
                'Leaf Blowers',
                'Pressure Washers',
            ],
            'Construction Tools' => [
                'Concrete Mixers',
                'Ladders & Scaffolding',
                'Generators',
            ],
            'Electrical Tools' => [
                'Multimeters',
                'Circuit Testers',
                'Wire Strippers',
            ],
        ];

        foreach ($categories as $parentName => $children) {
            $parent = ToolCategory::create([
                'name'        => $parentName,
                'slug'        => Str::slug($parentName),
                'is_active'   => true,
            ]);

            foreach ($children as $childName) {
                ToolCategory::create([
                    'name'        => $childName,
                    'slug'        => Str::slug($childName),
                    'parent_id'   => $parent->id,
                    'is_active'   => true,
                ]);
            }
        }
    }
}

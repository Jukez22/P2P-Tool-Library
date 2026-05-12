<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        DB::table('categories')->insert([
            ['id' => 1, 'name' => 'Power Tools', 'parent_id' => 0],
            ['id' => 2, 'name' => '3D Printing', 'parent_id' => 0],
            ['id' => 3, 'name' => 'Measurement', 'parent_id' => 0],
            ['id' => 4, 'name' => 'Hand Tools', 'parent_id' => 0],
            ['id' => 5, 'name' => 'Garden Tools', 'parent_id' => 0],
        ]);
    }
}

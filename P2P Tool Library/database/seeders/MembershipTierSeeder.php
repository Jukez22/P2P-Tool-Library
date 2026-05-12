<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MembershipTierSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('membership_tiers')->insert([
            [
                'id' => 1,
                'name' => 'casual',
                'discount_rate' => 0.00,
                'boost_limit' => 2,
                'max_active_rentals' => 3,
            ],
            [
                'id' => 2,
                'name' => 'pro',
                'discount_rate' => 10.00,
                'boost_limit' => 5,
                'max_active_rentals' => 10,
            ],
            [
                'id' => 3,
                'name' => 'premium',
                'discount_rate' => 20.00,
                'boost_limit' => 10,
                'max_active_rentals' => 20,
            ],
        ]);
    }
}

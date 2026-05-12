<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    // Seed the database
    public function run(): void
    {
        $this->call([
            MembershipTierSeeder::class,
            CategorySeeder::class,
        ]);
    }
}

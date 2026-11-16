<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            LanguageSeeder::class,
            RoleSeeder::class,
            AccommodationSeeder::class,
            SeasonSeeder::class,
            RateSeeder::class,
            ReservationSeeder::class,
            BlockedPeriodSeeder::class,
            CmsSeeder::class,
            AdminUserSeeder::class,
        ]);
    }
}

<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Role::updateOrCreate([
            'code' => 'admin',
            'name' => 'Administrator',
            'description' => 'Volledige toegang tot de beheeromgeving',
        ]);
        Role::updateOrCreate([
            'code' => 'owner',
            'name' => 'Eigenaar',
            'description' => 'Beheerder van de accommodatie',
        ]);
    }
}

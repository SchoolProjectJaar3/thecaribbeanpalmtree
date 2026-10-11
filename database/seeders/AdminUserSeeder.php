<?php

namespace Database\Seeders;

use App\Models\Language;
use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        $email = env('ADMIN_SEED_EMAIL');
        $password = env('ADMIN_SEED_PASSWORD');

        if (! $email || ! $password) {
            $this->command->warn('ADMIN_SEED_EMAIL of ADMIN_SEED_PASSWORD ontbreekt in .env, beheerder overgeslagen.');
            return;
        }

        User::updateOrCreate(['email' => $email], [
            'role_id' => Role::where('code', 'admin')->firstOrFail()->id,
            'language_id' => Language::where('code', 'nl')->firstOrFail()->id,
            'first_name' => 'Test',
            'last_name' => 'Beheerder',
            'password' => $password, // get hashed by the model's cast
        ]);
    }
}

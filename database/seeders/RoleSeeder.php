<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use App\Models\User;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        $superAdmin = Role::firstOrCreate(['name' => 'Super Admin']);
        $operator = Role::firstOrCreate(['name' => 'Operator']);

        // Assign Super Admin role to all seeded admin users
        $users = User::all();
        foreach ($users as $user) {
            if (!$user->hasRole('Super Admin')) {
                $user->assignRole($superAdmin);
            }
        }
    }
}

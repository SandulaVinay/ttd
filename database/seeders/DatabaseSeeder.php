<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            BookingTypeSeeder::class,
            SevaTypeSeeder::class,
        ]);

        $user = User::firstOrCreate(
            ['email' => 'garuda008@gmail.com'],
            [
                'name' => 'garuda booking',
                'password' => Hash::make('password123'),
                'status' => 'active',
            ]
        );

        $this->call([
            RoleSeeder::class,
        ]);
    }
}

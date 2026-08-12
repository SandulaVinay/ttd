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

        // Main Admin User requested by user
        $vinayUser = User::updateOrCreate(
            ['email' => 'sandulavinay@gmail.com'],
            [
                'name' => 'Vinay Sandula',
                'password' => Hash::make('Python#1989'),
                'status' => 'active',
            ]
        );

        // Garuda Admin User
        $garudaUser = User::updateOrCreate(
            ['email' => 'garuda008@gmail.com'],
            [
                'name' => 'garuda booking',
                'password' => Hash::make('Python#1989'),
                'status' => 'active',
            ]
        );

        $this->call([
            RoleSeeder::class,
            GrowthCircleSeeder::class,
        ]);
    }
}

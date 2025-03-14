<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        if (!User::where('email', 'admin2@gmail.com')->exists()) {
            User::create([
                'name' => 'Admin',
                'email' => 'admin2@gmail.com',
                'password' => Hash::make('password'),
                'role' => 'admin',
            ]);
        }


        User::factory()->count(20)->create();
    }
}

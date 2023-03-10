<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::firstOrCreate([
            'email' => 'admin@hc.com',
        ], [
            'name' => fake()->name(),
            'password' => 'secret@1234',
            'email_verified_at' => now(),
        ]);
    }
}

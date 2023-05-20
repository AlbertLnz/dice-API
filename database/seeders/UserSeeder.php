<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use App\Models\User;
use Illuminate\Support\Str;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'name' => 'Albert Lanza',
            'email' => 'albert@gmail.com',
            'email_verified_at' => now(),
            'password' => bcrypt('password'),
            'winRate' => 0,
            'remember_token' => Str::random(10),
        ]);
    }
}

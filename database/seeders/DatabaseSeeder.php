<?php

namespace Database\Seeders;

use App\Models\User;
use App\Http\Services\UserService;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::factory(15)->create(); // $this->call(UserSeeder::class);
        $this->call(GameSeeder::class);
        
        $userServiceMethods = new UserService;
        User::query()->update(['winRate' => $userServiceMethods->updateWinRateAllUsers()]); //fresh winRate column
    }
}

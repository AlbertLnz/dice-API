<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use App\Models\Game;
use Illuminate\Support\Facades\DB;

class GameSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        for($i=0 ; $i<20 ; $i++){
            $game = new Game;
            Game::create([
                'dice1' => $game->dice1,
                'dice2' => $game->dice2,    
                'numberResult' => $game->numberResult,
                'textResult' => $game->stringResult(),
                'user_id' => DB::table('users')->inRandomOrder()->first()->id,
            ]);
        }
    }
}

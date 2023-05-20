<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use App\Models\Game;

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
                'user_id' => 1,
            ]);
        }
    }
}

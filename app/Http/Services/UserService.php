<?php

namespace App\Http\Services;

use App\Models\Game;
use Illuminate\Support\Facades\DB;
use App\Models\User;

class UserService{

    public function updateWinRate($user):void{
        $user['winRate'] = DB::table('games')->where('user_id', $user->id)->where('numberResult', 7)->count() / DB::table('games')->where('user_id', $user->id)->count('id');
        $user->update();
    }

    public function updateWinRateAllUsers():void{
        $allPlayers = User::all();
        $countUsers = User::all()->count();

        for($i=0 ; $i<$countUsers ; $i++){
            if(isset($allPlayers[$i]['id']) && Game::where('user_id', $i+1)->exists()){
                $this->updateWinRate($allPlayers[$i]);
            }
        }
    }

}

?>
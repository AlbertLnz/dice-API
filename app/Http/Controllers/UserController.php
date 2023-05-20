<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\User;
use App\Models\Game;
use App\Http\Services\UserService;

class UserController extends Controller
{
    //ADMIN ROUTES
    public function index(){

        $allPlayers = User::all(); //object
        $allGames = Game::all(); //object
        $playersList=array();
        $userServiceMethods = new UserService;

        for($i=0 ; $i<count($allPlayers) ; $i++){
            
            if(isset($allPlayers[$i]['id'])){
                $allGamesFiltred = $allGames->where('user_id', $i+1);
                $allPlayers[$i]['winRate'] = $userServiceMethods->percentageWinRate($allGamesFiltred, $i);
                $allPlayers[$i]->save();
                $userGames = User::with('games')->find($i+1);

                array_push($playersList, $userGames);
            }
        }
        
        return $playersList;
    }

    
    //USER ROUTES
    public function show($id){
        $games = Game::where('user_id', $id)->get();
        return response()->json($games);
    }

}

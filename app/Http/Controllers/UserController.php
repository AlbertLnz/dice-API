<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\User;
use App\Models\Game;
use App\Http\Services\UserService;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

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

    public function store($id){

        $user = User::find($id);
        $userServiceMethods = new UserService;

        $game = new Game;
        $game['dice1'] = $game->dice1;
        $game['dice2'] = $game->dice2;
        $game['numberResult'] = $game->numberResult;
        $game['textResult'] = $game->textResult;
        $game['user_id'] = $user->id;
        
        $game->save();

        $userServiceMethods->updateWinRate($user);
        $user->update();

        return response()->json($game);
    }

    public function update($id, Request $request){
        $player = User::find($id);
        $data = $request->all();

        $validator = Validator::make($data, [
            'name' => 'required | min:3',
            'email' => 'required | email | max:50',
            'password' => 'required | min:6'
        ]);

        if($validator->fails()){
            return response(['error' => $validator->errors()]);
        }else{
            return response(['message' => "Player updated!", "Sent" => $player->update($request->all())]);
        }
    }

    public function destroy($id){
        Game::where('user_id', $id)->delete();
        return response(['message' => "Player Games deleted!"]);
    }


    //GENERAL ROUTES
    public function generalRanking(){
        return response(['Average win rate of users' => DB::table('users')->sum('winRate') / User::all()->count()."%"]);
    }

    public function winnerRanking(){
        return DB::table('users')->where('winRate', DB::table('users')->max('winRate'))->get();
    }

    public function loserRanking(){
        return DB::table('users')->where('winRate', DB::table('users')->min('winRate'))->get();
    }
}

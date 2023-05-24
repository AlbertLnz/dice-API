<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\User;
use App\Models\Game;
use App\Http\Services\UserService;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    //ADMIN ROUTES
    public function index(){

        if(Auth::user()->hasRole('admin')){
            $allPlayers = User::all(); //object
            $userServiceMethods = new UserService;
            $userServiceMethods->updateWinRateAllUsers();
    
            return response()->json(['allPlayers' => $allPlayers], 200);
        }else if(Auth::user()->hasRole('client')){
            return response()->json(['error' => "Unauthorized, you're not an admin"], 403);
        }else{
            return response()->json(['error' => "Unauthorized, you're not logged"], 401);
        }

    }


    //USER ROUTES
    public function show($id){
        $games = Game::where('user_id', $id)->get();
        return response()->json(["Your win rate" => User::find($id)['winRate'] , "Games" => $games]);
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
        User::where('id', $id)->update(['winRate' => null]);

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

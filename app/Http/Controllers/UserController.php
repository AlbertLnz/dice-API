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
        if(Auth::user()->id == $id){
            $games = Game::where('user_id', $id)->get();
            return response()->json(["Your win rate" => User::find($id)['winRate'] , "Games" => $games], 200);
        }else{
            return response()->json(['error' => "You can't view the games of other player"], 401);
        }
    }

    public function store($id){

        if(Auth::user()->id == $id){
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

            return response()->json(['game' => $game], 200);
        }else{
            return response()->json(['error' => "You can't view the games of other player"], 401);
        }
    }

    public function update($id, Request $request){
        $player = User::find($id);
        $data = $request->all();

        if(Auth::user()->id == $id){
            $validator = Validator::make($data, [
                'name' => 'required | min:3',
                'email' => 'required | email | max:50',
                'password' => 'required | min:6'
            ]);
    
            if($validator->fails()){
                return response(['error' => $validator->errors()], 401);
            }else{
                return response(['message' => "Player updated!", "Sent" => $player->update($request->all())], 200);
            }
        }else{
            return response(['error' => "You can't edit data of other player!"], 401);
        }

    }

    public function destroy($id){

        if(Auth::user()->id == $id){
            Game::where('user_id', $id)->delete();
            User::where('id', $id)->update(['winRate' => null]);

            return response(['message' => "Player Games deleted!"], 200);

        }else{
            return response(['error' => "You can't delete games of other player!"], 401);
        }
    }


    //GENERAL ROUTES
    public function generalRanking(){
        return DB::table('users')->select('id', 'name', 'email', 'winRate')->orderBy('winRate', 'desc')->get();
    }

    public function winnerRanking(){
        return DB::table('users')->select('id', 'name', 'email', 'winRate')->where('winRate', DB::table('users')->max('winRate'))->get();
    }

    public function loserRanking(){
        return DB::table('users')->select('id', 'name', 'email', 'winRate')->where('winRate', DB::table('users')->min('winRate'))->get();
    }
}

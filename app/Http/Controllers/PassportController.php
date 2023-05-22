<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Validator;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

use function PHPUnit\Framework\isNull;

class PassportController extends Controller
{
    public function register(Request $request){
        $data = $request->all();

        $validator = Validator::make($data, [
            'name' => 'min:4',
            'email' => 'required | email',
            'password' => 'min:6',
        ]);

        if($validator->fails()){
            return response(['error' => $validator->errors(), "Validation error"]);
        }
    
        $user = User::create([
            'name' => $request->fillable('name') ? $request->name : "anonymous",
            'email' => $request->email,
            'password' => $request->fillable('password') ? $request->password : "password",
            'password' => bcrypt($request->password),
        ]);

        $token = $user->createToken('Personal Access Token')->accessToken;

        return response()->json(['token' => $token], 200);
    }

    public function login(Request $request){
        $data = $request->all();

        $validator = Validator::make($data, [
            'email' => 'required | email',
            'password' => 'required | min:6',
        ]);

        if($validator->fails()){
            return response(['error' => $validator->errors(), "Validation error"]);
        }

        if(auth()->attempt($data)){
            $token = auth()->user()->createToken('Personal Access Token')->accessToken;
            return response()->json(['token' => $token], 200);
        }else{
            return response()->json(['error' => 'Unauthorized'], 401);
        }
    }

    public function logout(){
        $token = Auth::user()->token();
        $token->revoke();

        return response()->json(['message' => 'Succesfully logged out']);
    }
}

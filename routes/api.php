<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\UserController;
use App\Http\Controllers\PassportController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('register', [PassportController::class, 'register'])->name('api.register');
Route::post('login', [PassportController::class, 'login'])->name('api.login');

//Admin Routes
Route::middleware('auth:api')->group(function(){
    Route::get('players', [UserController::class, 'index'])->name('api.players.index')->middleware('role:admin');
});

//User Routes
Route::middleware('auth:api')->group(function(){
    Route::get('players/{id}/games', [UserController::class, 'show'])->name('api.players.show')->middleware('role:client|admin');
    Route::post('players/{id}/games', [UserController::class, 'store'])->name('api.players.store')->middleware('role:admin|client');
    Route::put('players/{id}', [UserController::class, 'update'])->name('api.players.update')->middleware('role:admin|client');
    Route::delete('players/{id}/games', [UserController::class, 'destroy'])->name('api.players.destroy')->middleware('role:admin|client');
    Route::post('logout', [PassportController::class, 'logout'])->name('api.logout')->middleware('role:admin|client');
});

//General Routes
Route::get('players/ranking', [UserController::class, 'generalRanking'])->name('api.players.generalRanking');
Route::get('players/ranking/winner', [UserController::class, 'winnerRanking'])->name('api.players.winnerRanking');
Route::get('players/ranking/loser', [UserController::class, 'loserRanking'])->name('api.players.loserRanking');
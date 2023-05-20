<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\UserController;

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


//Admin Routes
Route::get('players', [UserController::class, 'index'])->name('api.players.index');

//User Routes
Route::get('players/{id}/games', [UserController::class, 'show'])->name('api.players.show');
Route::post('players/{id}/games', [UserController::class, 'store'])->name('api.players.store');
Route::put('players/{id}', [UserController::class, 'update'])->name('api.players.update');
Route::delete('players/{id}/games', [UserController::class, 'destroy'])->name('api.players.destroy');

//General Routes
Route::get('players/ranking', [UserController::class, 'generalRanking'])->name('api.players.generalRanking');
Route::get('players/ranking/winner', [UserController::class, 'winnerRanking'])->name('api.players.winnerRanking');
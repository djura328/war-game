<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::post('/games', 'GameController@createGame'); // "name": string
Route::get('/games', 'GameController@index');

Route::post('/army', 'ArmyController@createArmy'); // "name": string ,"game_id": int, "strategy": [1, 2, 3]
Route::get('/army/{id}', 'ArmyController@show');

Route::post('/games/attack', 'GameController@attack'); //games: [1, 2,...]
Route::post('/games/step', 'GameController@step'); //games: [1, 2,...]

Route::post('/games//restart', 'GameController@restart'); //games: [1, 2,...]
Route::get('/games/restart-all', 'GameController@resetAll');
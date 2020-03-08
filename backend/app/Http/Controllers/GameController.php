<?php

namespace App\Http\Controllers;

use App\Game;
use Illuminate\Http\Request;

class GameController extends Controller
{

    public function index(){
        return Game::all();
    }

    public function createGame(Request $request){

        $game = new Game;

        $game->name = $request->name;

        $game->save();

        if($game) return response()->json($game, 200);
        return response()->json(['status' => 'failed'], 500);

    }

    public function resetAll(){
        Game::restartAll();

        return true;
    }

    public function restart(Game $game){
        $game->restart();

        return true;
    }
}

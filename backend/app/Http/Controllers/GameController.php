<?php

namespace App\Http\Controllers;

use App\Game;
use Illuminate\Http\Request;
use App\Modules\War;

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

    public function restart(Request $request){

        foreach ($request->games as $id) {
            $game = Game::find($id);
            $game->restart();
        }

        return true;
    }

    public function step(Request $request){

        $logs = [];
        foreach ($request->games as $key => $id) {
            $war[$key] = new War($id);
            $log = $war[$key]->startStep();
            $logs[] = $log;
        }

        return $logs;
    }

    public function attack(Request $request){

        $logs = [];
        foreach ($request->games as $key => $id) {
            $war[$key] = new War($id);
            $log = $war[$key]->startWar();
            $logs[] = $log;
        }

        return $logs;

    }
}

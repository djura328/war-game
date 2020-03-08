<?php

namespace App\Http\Controllers;

use App\Army;
use Illuminate\Http\Request;

class ArmyController extends Controller
{

    public function show($id){
        return Army::where('game_id', '=', $id)->get();
    }

    public function createArmy(Request $request){

        $army = new Army;

        $army->name = $request->name;
        $army->game_id = $request->game_id;
        $army->strategy = $request->strategy;

        $army->save();

        $number_of_units = mt_rand(1, 5);

        $i = 0;
        $arr = [];
        while($i <= $number_of_units){
            $arr[] = ['name' => 'unit-'. ($i+1)];
            $i++;
        }

        $units = $army->units()->createMany($arr);

        if($army) return response()->json($army, 200);
        return response()->json(['status' => 'failed'], 500);

    }

}

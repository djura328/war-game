<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Game extends Model
{
    public function armies(){

        return $this->hasMany('App\Army');
    }

    public function restart(){

        $this->armies()->update(['status' => 1]);
        $this->armies()->each(function($amies){
            $amies->units()->update(['status' => 1, 'health' => 1]);
        });
    }

    public static function restartAll(){

        $games = self::all();

        collect($games)->each(function($game){

            $game->armies()->update(['status' => 1]);

            $game->armies()->each(function($amies){
                $amies->units()->update(['status' => 1, 'health' => 1]);
            });
        });

    }
}

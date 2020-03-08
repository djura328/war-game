<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Army extends Model
{
    protected $hidden = ['game_id', 'strategy', 'created_at', 'updated_at'];

    protected $fillable = ['status'];

    public function units()
    {
        return $this->hasMany('App\Units');
    }

    public static function removeDeadArmies($id){
        $armies = self::withCount(['units' => function($q){
            $q->where('status', '=', 1);
        }])
            ->where('game_id', '=', $id)
            ->where('status', '=', 1)
            ->having('units_count', '=', 0)
            ->get();

        collect($armies)->each(function($q){
            $q->update(['status' => 0]);
        });

        if($armies->count()) return $armies;
        return false;
    }

    public static function attacker($id){

        return self::with(['units' => function($q) {
            $q->where('status', '=', 1);
            $q->inRandomOrder();
            $q->limit(1);
        }])
            ->where('game_id', '=', $id)
            ->where('status', '=', 1)
            ->withCount(['units' => function($q){
                $q->where('status', '=', 1);
            }])
            ->having('units_count', '>', 0)
            ->inRandomOrder()
            ->first();

    }

    public function victim(){

        return Army::with(['units' => function($q){
            $q->where('status', '=', 1);
            $q->inRandomOrder();
            $q->limit(1);
        }])
            ->where('game_id', '=', $this->game_id)
            ->where('status', '=', 1)
            ->where('id', '<>', $this->id)
            ->withCount(['units' => function($q){
                $q->where('status', '=', 1);
            }])
            ->having('units_count', '>', 0)
            ->when($this->strategy === 1, function($q){
                $q->inRandomOrder();
            })
            ->when($this->strategy === 2, function($q){
                $q->orderBy('units_count', 'DESC');
            })
            ->when($this->strategy === 3, function($q){
                $q->orderBy('units_count', 'ASC');
            })
            ->first();
    }
}
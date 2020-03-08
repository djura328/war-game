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
}

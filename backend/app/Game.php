<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Game extends Model
{
    public function armies()
    {
        return $this->hasMany('App\Army');
    }
}

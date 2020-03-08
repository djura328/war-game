<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Units extends Model
{
    protected $fillable = ['name', 'health'];

    protected $hidden = ['army_id', 'created_at', 'updated_at'];

    public function armies()
    {
        return $this->belongsTo('App\Army');
    }

    public function removeDeadUnite(){

        $count = $this
            ->where('health', '<=', 0)
            ->where('status', '=', 1)
            ->update(['status' => 0]);

        if($count) return $this;
        return false;
    }
}

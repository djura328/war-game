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
}

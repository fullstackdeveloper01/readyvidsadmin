<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class State extends Model
{
    protected $table = 'state';

    public function items()
    {
        return $this->hasMany('App\City','state_id','id');
    }

    // public function restorant()
    // {
    //     return $this->belongsTo('App\Restorant');
    // }
}

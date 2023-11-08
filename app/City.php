<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class City extends Model
{
    protected $table = 'city';

    public function state()
    {
        return $this->hasMany('App\State','state_id','id');
    }

   
}

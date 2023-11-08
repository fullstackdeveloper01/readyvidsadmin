<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Team extends Model
{
    protected $table = 'team';
    protected $fillable = [

        'first_name', 'last_name','email','role','user_id'

    ];
}

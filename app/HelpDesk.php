<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class HelpDesk extends Model
{
    protected $table = 'helpdesk';
     protected $fillable = [

        'user_id','name', 'email','phone','subject','message','created_at','updated_at'

    ];

}

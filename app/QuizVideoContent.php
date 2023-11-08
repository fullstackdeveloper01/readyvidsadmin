<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class QuizVideoContent extends Model
{
    protected $table = 'quizvideocontent';
    
    protected $fillable = [

       'user_id','video_id'

    ];
}

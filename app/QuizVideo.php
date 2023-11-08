<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class QuizVideo extends Model
{
    protected $table = 'quiz_video';
    
    public function videotext()
    {
        return $this->hasOne('App\QuizVideoText');
    }
}

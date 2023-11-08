<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class VideoContent extends Model
{
    protected $table = 'videocontent';
    
    protected $fillable = [

       'user_id','video_id'

    ];
}

<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MyVideo extends Model
{
    protected $table = 'myvideo';
   protected $fillable = [

        'user_id', 'video','without_watermark_video','with_watermark_video','ratio','created_at','updated_at','short_video','long_video','package_id'

    ];
}

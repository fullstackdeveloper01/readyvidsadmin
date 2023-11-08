<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RecentSearch extends Model
{
    protected $table = 'recent_search';
    protected $fillable = ['listing_id','title','device_id','image','status'];
}

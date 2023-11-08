<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PackageHistory extends Model
{
    protected $table = 'package_history';
     protected $fillable = [

        'user_id', 'created_at','updated_at','package_id','package_price','purchase_date','expired_date'

    ];
}

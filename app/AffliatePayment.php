<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AffliatePayment extends Model
{
    protected $table = 'affliate_payment';

    protected $fillable = [

        'user_id','month_name','year','commission','payment_status','payment_date','transaction','payment_method'

    ];

}

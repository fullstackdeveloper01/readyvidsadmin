<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BusinessPartnerCommission extends Model
{
    protected $table = 'business_partner_commission';

    protected $fillable = [

        'business_partner_user_id','user_id','month','year','commission','sales','commission_rate','conversion'

    ];

}

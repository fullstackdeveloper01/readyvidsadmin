<?php



namespace App;



use Illuminate\Database\Eloquent\Model;



class TransactionRequest extends Model

{

    protected $table = 'transaction_request';



    // public function state()

    // {

    //     return $this->hasMany('App\State','state_id','id');

    // }



    // public function restorant()

    // {

    //     return $this->belongsTo('App\Restorant');

    // }

}


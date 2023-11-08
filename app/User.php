<?php



namespace App;



use Illuminate\Notifications\Notifiable;

use Illuminate\Contracts\Auth\MustVerifyEmail;

use Illuminate\Foundation\Auth\User as Authenticatable;

use Spatie\Permission\Traits\HasRoles;

use Laravel\Cashier\Billable;

use Tymon\JWTAuth\Contracts\JWTSubject;





class User extends Authenticatable implements JWTSubject

{

    use Notifiable;

    use HasRoles;

    use Billable;



    /**

     * The attributes that are mass assignable.

     *

     * @var array

     */

    protected $fillable = [

        'name', 'email', 'password', 'phone','dob', 'token','user_ref','role','otp','otp_generation_time','device_token','apple_id','google_id','login_type','bc_id','city','state','country','code','customize_name','package_id','payment_status','package_expired','short_video','long_video','discount_type','discount_price','image'

    ];



    /**

     * The attributes that should be hidden for arrays.

     *

     * @var array

     */

    protected $hidden = [

        'password', 'remember_token', 'api_token'

    ];



    /**

     * The attributes that should be cast to native types.

     *

     * @var array

     */

    protected $casts = [

        'email_verified_at' => 'datetime',

    ];



    public function restorant()

    {

        return $this->hasOne('App\Restorant');

    }



    public function addresses(){

        return $this->hasMany('App\Address')->where(['address.active' => 1]);

    }



    public function orders(){

        return $this->hasMany('App\Order','client_id','id');

    }



    public function routeNotificationForOneSignal()

    {

        return ['include_external_user_ids' => [$this->id.""]];

    }

    public function getJWTIdentifier()

    {

        return $this->getKey();

    }

    public function getJWTCustomClaims()

    {

        return [];

    }



}


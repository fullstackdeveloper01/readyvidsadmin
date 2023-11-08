<?php

namespace App\Http\Controllers;

use JWTAuth;


use App\User;
use App\Listing;
use App\Wishlist;
use App\Galleries;
use App\Gallery;
use App\Rating;
use App\Pages;
use App\Package;
use App\Darshan;
use App\Advertisement;
use App\Categories;
use App\Event;
use App\Notification;
use App\NotificationStatus;
use App\OrderHistory;
use Mail;
use App\RecentSearch;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Exceptions\JWTException;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use App\Helpers\Helper;
use Image;
class UserApiController extends Controller
{
    protected $user;
 
    public function __construct()
    {   
        date_default_timezone_set('Asia/Kolkata');
       
    }
    /*public function register_old(Request $request)
    {
        //Validate data
        $data = $request->only('name', 'email', 'password');
        // print_r($data);
        // die;
        $validator = Validator::make($data, [
            'name' => 'required|string',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6|max:50'
        ]);

        //Send failed response if request is not valid
        if ($validator->fails()) {
            return response()->json(['error' => $validator->messages()], 200);
        }

        //Request is valid, create new user
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password)
        ]);

        //User created, return success response
        return response()->json([
            'success' => true,
            'message' => 'User created successfully',
            'data' => $user
        ], Response::HTTP_OK);
    }*/

    public function register(Request $request)
    {
        //Validate data
        $data = $request->only('name', 'email', 'phone','dob');
        $validator = Validator::make($data, [
            'name' => 'required|string',
            'email' => 'required|email|unique:users',
            'phone' => 'required|min:10|max:10|unique:users',
            'dob' => 'required'
        ]);

        //Send failed response if request is not valid
        if ($validator->fails()) {
            return response()->json(['success' => false,'error' => $validator->messages()]);
        }

        $otp = rand(1000,9999);
        $data['otp']=$otp;
        $data['password']=bcrypt($otp);
        $data['otp_generation_time']=time();

        //Request is valid, create new user
        $user = User::create($data);

        // Send otp
        $html= view('mail.index',$data);
        $email=$data['email'];
        \Mail::send([], [], function ($message) use ($html,$email)
        {
            $message->to($email)
                ->subject('Your One Time Password (OTP)');
            $message->from('welcome@shyamnaamtrust.com')->setBody($html, 'text/html');
        });
        Helper::send_sms(['mobile'=>$data['phone'],'message'=>$otp]);

        //User created, return success response
        return response()->json([
            'success' => true,
            'message' => 'User created successfully',
            'data' => $user
        ], Response::HTTP_OK);
    }

    /**Match otp */
    public function match_otp(Request $request)
    {   
        $data = $request->only('otp','phone','device_id');
        $validator = Validator::make($data, [
             'otp' => 'required',
             'phone' => 'required',         
             'device_id' => 'required',         
            ]);
 
         //Send failed response if request is not valid
         if ($validator->fails()) {
             return response()->json(['error' => $validator->messages()], 200);
         }
           
         try {
             $user = User::where(['phone'=>$request->phone])->orWhere(['email'=>$request->phone])->first();
             $otp = $user->otp;     
             $otp_generation_time= $user->otp_generation_time;
             $after_3_min_time=$otp_generation_time+3*60;
             $target=time()-$after_3_min_time;
            
            if($otp==$request->otp){
                if($target<= 180){
                    $newCredentials['phone']=$data['phone'];
                    $newCredentials['password']=$data['otp'];
                    if (! $token = JWTAuth::attempt($newCredentials)) {
                        return response()->json([
                            'success' => false,
                            'message' => 'Invalid Otp.',
                        ]);
                    }else{
                        $user->otp=null;
                        $user->otp_generation_time=null;
                        $user->otp_verification=date('Y-m-d H:i:s');
                        $user->device_id=$data['device_id'];
                        $user->update();
                        $user->token = $token;

                        $email=$user->email;
                        \Mail::send([], [], function ($message) use ($email)
                        {
                            $message->to($email)
                                ->subject('Welcome');
                            $message->from('welcome@shyamnaamtrust.com')->setBody('Congratulation for being a part of our Shyam Naam Trust (R). We would like to welcome you to our family and extremely happy to have you as one of us.', 'text/html');
                        });

                        Helper::send_sms(['mobile'=>$user->phone,'message'=>'Congratulation for being a part of our Shyam Naam Trust (R). We would like to welcome you to our family and extremely happy to have you as one of us.']);
                        return response()->json([
                             'data' =>$user,
                             'success' => true,
                             'message' => 'Account verify successfully.',
                             'status'=>200
                         ]);
                    }
                }else{
                    return response()->json([
                         'data' =>$user,
                         'success' => false,
                         'message' => 'OTP expired.'
                     ]);
                }
            }else{
                return response()->json([
                    'data' =>$user,
                    'success' => false,
                    'message' => 'OTP mismatch.',
                ]);
            }
         } catch (JWTException $exception) {
             return response()->json([
                 'success' => false,
                 'message' => 'user not found'
             ]);
         }
    }

    /**login send otp */
    public function login(Request $request)
    {   ///valid credential
        $data = $request->only('phone');
        
        $validator = Validator::make($data, [
            'phone' => 'required'
        ]);

        //Send failed response if request is not valid
        if ($validator->fails()) {
            return response()->json(['error' => $validator->messages()], 200);
        }
            
        try {
            // echo $data['phone'];die;
            // $user = User::where(['phone'=>$data])->first();
            $user = User::where('email', 'like', '%' . $data['phone'] . '%')->orWhere('phone', 'like', '%' . $data['phone'] . '%')->first();
            // print_r($user);die;
            if($user!==null){
                // $token = JWTAuth::fromUser($user);
                $result['otp']=mt_rand(1000,9999);
                // $result['token']=$token;
                $user->otp=$result['otp'];
                $user->password=bcrypt($result['otp']);
                $user->otp_generation_time=time();
                $user->otp_verification=null;
                $user->save();
                // Send otp
                $html= view('mail.index',$result);
                $email=$user->email;
                \Mail::send([], [], function ($message) use ($html,$email)
                {
                    $message->to($email)
                        ->subject('Your One Time Password (OTP)');
                    $message->from('welcome@shyamnaamtrust.com')->setBody($html, 'text/html');
                });
                Helper::send_sms(['mobile'=>$user->phone,'message'=>$result['otp']]);
                return response()->json([
                    'data' =>$result,
                    'success' => true,
                    'message' => 'send otp to given mobile number',
                    'status'=>200
                ]);
            }else{
                return response()->json([
                    'success' => false,
                    'message' => 'user not found'
                ]);
            }            
            
        } catch (JWTException $exception) {
            return response()->json([
                'success' => false,
                'message' => 'user not found'
            ]);
        }
    }
    
    /*user authentication*/
    public function authenticate(Request $request)
    {
        $credentials = $request->only('phone', 'otp','device_id');
        //valid credential
        $validator = Validator::make($credentials, [
            'phone' => 'required',
            'device_id' => 'required',
            'otp' => 'required|min:4|max:4'
        ]);

        //Send failed response if request is not valid
        if ($validator->fails()) {
            return response()->json(['error' => $validator->messages()], 200);
        }
        //Crean token
        try {
            $user = User::where(['phone'=>$request->phone])->orWhere(['email'=>$request->phone])->first();
            $otp = $user->otp;     
            $otp_generation_time= $user->otp_generation_time;
            $after_3_min_time=$otp_generation_time+3*60;
            $target=time()-$after_3_min_time;
            if($otp==$request->otp){
                if($target <= 180){
                    $user->otp=null;
                    $user->otp_generation_time=null;
                    $user->otp_verification=date('Y-m-d H:i:s');
                    $user->device_id=$credentials['device_id'];
                    $user->update();

                    $newCredentials['phone']=$user->phone;
                    $newCredentials['password']=$credentials['otp'];

                    if (! $token = JWTAuth::attempt($newCredentials)) {
                        return response()->json([
                            'success' => false,
                            'message' => 'Login credentials are invalid.',
                        ], 400);
                    }else{
                        return response()->json([
                            'token' => $token,
                            'success' => true,
                            'message'=>'User Login Successfully',
                            'status'=>200
                        ]);
                    }
                }else{
                    return response()->json([
                         // 'data' =>$user,
                         'success' => false,
                         'message' => 'OTP expired.'
                     ]);
                }
            }else{
                return response()->json([
                     'success' => false,
                     'message' => 'OTP mismatch.',
                ]);
            }

        } catch (JWTException $e) {
            return response()->json([
                    'success' => false,
                    'message' => 'Could not create token.',
                ], 500);
        }
    
        // //Token created, return with success response and jwt token
        // return response()->json([
        //     'success' => true,
        //     'token' => $token,
        // ]);
    }

    /**resend otp */
    public function resend_otp(Request $request)
    {   
        $data['phone']=$request->phone;
        $validator = Validator::make($data, [
            'phone' => 'required'
        ]);

        //Send failed response if request is not valid
        if ($validator->fails()) {
            return response()->json(['error' => $validator->messages()], 200);
        }
          
        try {
            $user = User::where(['phone'=>$data])->first();
            if($user!==null){
                // $token = JWTAuth::fromUser($user);
                $result['otp']=mt_rand(1000,9999);
                // $result['token']=$token;
                $user->otp=$result['otp'];
                $user->password=bcrypt($result['otp']);
                $user->otp_generation_time=time();
                $user->otp_verification=null;
                $user->save();
                // Send otp
                $html= view('mail.index',$result);
                $email=$user->email;
                \Mail::send([], [], function ($message) use ($html,$email)
                {
                    $message->to($email)
                        ->subject('Your One Time Password (OTP)');
                    $message->from('welcome@shyamnaamtrust.com')->setBody($html, 'text/html');
                });
                Helper::send_sms(['mobile'=>$data['phone'],'message'=>$result['otp']]);
                return response()->json([
                    'data' =>$result,
                    'success' => true,
                    'message' => 'send otp to given mobile number',
                    'status'=>200
                ]);
            }
            else{
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid Mobile Number'
                ]);
            }
            
        } catch (JWTException $exception) {
            return response()->json([
                'success' => false,
                'message' => 'user not found'
            ]);
        }
    }

    
 
    /*public function authenticate_old(Request $request)
    {
        $credentials = $request->only('email', 'password');
 
        //valid credential
        $validator = Validator::make($credentials, [
            'email' => 'required|email',
            'password' => 'required|string|min:6|max:50'
        ]);

        //Send failed response if request is not valid
        if ($validator->fails()) {
            return response()->json(['error' => $validator->messages()], 200);
        }

        //Request is validated
        //Crean token
        // ,['exp' => Carbon\Carbon::now()->addDays(7)->timestamp]
        try {
            if (! $token = JWTAuth::attempt($credentials)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Login credentials are invalid.',
                ], 400);
            }
        } catch (JWTException $e) {
        return $credentials;
            return response()->json([
                    'success' => false,
                    'message' => 'Could not create token.',
                ], 500);
        }
    
        //Token created, return with success response and jwt token
        return response()->json([
            'success' => true,
            'token' => $token,
        ]);
    }*/
 
    public function logout(Request $request)
    {
        //valid credential
        $validator = Validator::make($request->only('token'), [
            'token' => 'required'
        ]);

        //Send failed response if request is not valid
        if ($validator->fails()) {
            return response()->json(['error' => $validator->messages()], 200);
        }

        //Request is validated, do logout        
        try {
            JWTAuth::invalidate($request->token);
 
            return response()->json([
                'success' => true,
                'message' => 'User has been logged out'
            ]);
        } catch (JWTException $exception) {
            return response()->json([
                'success' => false,
                'message' => 'Sorry, user cannot be logged out'
            ]);
        }
    }
 
    public function get_user(Request $request)
    {
        $this->validate($request, [
            'token' => 'required'
        ]);
 
        $user = JWTAuth::authenticate($request->token);
 
        return response()->json(['user' => $user]);
    }


    /**send otp */
    public function send_otp(Request $request)
    {   ///valid credential
        $data = $request->only('mobile');
        
        $validator = Validator::make($data, [
            'mobile' => 'required'
        ]);

        //Send failed response if request is not valid
        if ($validator->fails()) {
            return response()->json(['error' => $validator->messages()], 200);
        }
            
        try {
            $user = User::where(['phone'=>$data])->first();
            if($user!==null){
                $token = JWTAuth::fromUser($user);
                $result['otp']=mt_rand(100000,999999);
                $result['token']=$token;
                $user->otp=$result['otp'];
                $user->otp_generation_time=time();
                $user->save();
                return response()->json([
                    'data' =>$result,
                    'success' => true,
                    'message' => 'send otp to given mobile number',
                    'status'=>200
                ]);
            }
            else{
                
                $user=new User;
                $user->phone=$request->mobile;
                $user->otp=mt_rand(100000,999999);//rand();
                $user->otp_generation_time=time();
                $user->save();
                $token = JWTAuth::fromUser($user);
                $result['otp']=$otp;
                $result['token']=$token;//
                
                return response()->json([
                    'data' =>$result,
                    'success' => true,
                    'message' => 'send otp to given mobile number',
                    'status'=>200
                ]);
            }
            
            
        } catch (JWTException $exception) {
            return response()->json([
                'success' => false,
                'message' => 'user not found'
            ]);
        }
    }

    /**verify otp */
    public function verify_otp(Request $request)
    {   
        $data = $request->only('otp','token');
        $validator = Validator::make($data, [
             'otp' => 'required',
             'token' => 'required',         
            ]);
 
         //Send failed response if request is not valid
         if ($validator->fails()) {
             return response()->json(['error' => $validator->messages()], 200);
         }
           
         try {
             $user = JWTAuth::authenticate($request->token);
             $otp = $user->otp;     
             $otp_generation_time= $user->otp_generation_time;
             $after_3_min_time=$otp_generation_time+3*60;
             $target=time()-$after_3_min_time;
            
            if($otp==$request->otp){
                 if($target<= 180){
                    $user->otp=null;
                    $user->otp_generation_time=null;
                    $user->update();
                    return response()->json([
                         'data' =>$user,
                         'success' => true,
                         'message' => 'verify otp and login successfully.',
                         'status'=>200
                     ]);
                 }else{
                    return response()->json([
                         'data' =>$user,
                         'success' => false,
                         'message' => 'OTP expired.'
                     ]);
                 }
             }else{
                 return response()->json([
                     'data' =>$user,
                     'success' => true,
                     'message' => 'OTP mismatch.',
                     'status'=>200
                 ]);
             }
         } catch (JWTException $exception) {
             return response()->json([
                 'success' => false,
                 'message' => 'user not found'
             ]);
         }
     }
 
    /**resend otp */
    public function resend_otp_old(Request $request)
    {   
        $data['token']=$request->token;
        $validator = Validator::make($data, [
            'token' => 'required'
        ]);

        //Send failed response if request is not valid
        if ($validator->fails()) {
            return response()->json(['error' => $validator->messages()], 200);
        }
          
        try {
            $user = JWTAuth::authenticate($request->token);
            if($user!==null){
                $result['otp']=mt_rand(100000,999999);
                $result['token']=$request->token;
                $user->otp=$result['otp'];
                $user->otp_generation_time=time();
                $user->update();
                return response()->json([
                    'data' =>$result,
                    'success' => true,
                    'message' => 'resend send otp to given mobile number',
                    'status'=>200
                ]);
            }
            else{
                return response()->json([
                    'success' => false,
                    'message' => 'Token mismatch'
                ]);
            }
            
        } catch (JWTException $exception) {
            return response()->json([
                'success' => false,
                'message' => 'user not found'
            ]);
        }
    }

    /**token expired */
    public function token_expired(Request $request)
    {
       $data['token']=$request->token;
       $validator = Validator::make($data, [
           'token' => 'required'
       ]);

       //Send failed response if request is not valid
       if ($validator->fails()) {
           return response()->json(['error' => $validator->messages()], 200);
       }
         
       try{
            if($user= JWTAuth::authenticate($request->token)){
               return response()->json([
                   'success' => true,
                   'message' => 'Working',
                   'status'=>200
               ]);
              
            }

       }catch (JWTException $exception) {
           return response()->json([
               'success' => false,
               'message' => 'Token is expired'
           ]);
       }
       
      
    }

    /**save profile */
    public function profile(Request $request)
    {
        $data['token']=$request->token;
        $validator = Validator::make($data, [
            'token' => 'required'
        ]);

        //Send failed response if request is not valid
        if ($validator->fails()) {
            return response()->json(['error' => $validator->messages()], 200);
        }
            
        try{
            if($user= JWTAuth::authenticate($request->token)){
                
                if (!empty($request->name) || !empty($request->email) || !empty($request->dob) || !empty($request->business_info)) {
                    if (!empty($request->email)) {
                        $user->email=$request->email;
                    }
                    if (!empty($request->name)) {
                        $user->name=$request->name;
                    }
                    if (!empty($request->dob)) {
                        $user->dob=date('Y-m-d',strtotime($request->dob));
                    }
                    if (!empty($request->business_info)) {
                        $user->business_info=$request->business_info;
                    }
                    $user->update();
                    $user->dob=date('d-m-Y',strtotime($user->dob));
                    $user->path=url('/');
                    return response()->json([
                        'data' =>$user,
                        'success' => true,
                        'message' => 'profile detail save successfully',
                        'status'=>200
                    ]);
                }else{
                    $user->dob=date('d-m-Y',strtotime($user->dob));
                    $user->path=url('/');
                    return response()->json([
                        'data' =>$user,
                        'success' => true,
                        'message' => 'profile detail Found successfully',
                        'status'=>200
                    ]);
                }
                
            
            }

        }catch (JWTException $exception) {
            return response()->json([
                'success' => true,
                'message' => 'Token is expired'
            ]);
        }
        
        
    }
    /*user add listings*/
    public function add_listing(Request $request)
    {   
        $data = $request->only('category','subcategory','title','address','phone','business_time','about_business','service','gallery','token','map','lat','longi','pincode','cover_image');
        $validator = Validator::make($data, [
            'category' => 'required',
            'subcategory' => 'required',
            'title' => 'required',
            'address' => 'required',
            'phone' => 'required',
            'business_time' => 'required',
            'about_business' => 'required',
            'service' => 'required',
            'gallery' =>'required',
            'pincode'=>'required',
            'token'=>'required'
        ]);
        //Send failed response if request is not valid
        if ($validator->fails()) {
            return response()->json(['error' => $validator->messages()], 200);
        }
            
        try {
            if($user = JWTAuth::authenticate($request->token)){
                if($user->package_id!='0' || $user->package_limit!="0"){
                    // $package_listing_limit = Package::where('id','=',$user->package_id)->first()->package_listing_limit;
                    $package_listing_limit = $user->package_limit;
                    $listing_count=Listing::where(['user_id'=>$user->id,'status'=>1])->count('id');
                    if($listing_count< $package_listing_limit){
                        $listing = new Listing;
                        $listing->user_id=$user->id;
                        $listing->category=$request->category;
                        $listing->subcategory=$request->subcategory;
                        $listing->title=$request->title;
                        $listing->address=$request->address;
                        $listing->phone=$request->phone;
                        $listing->business_time=$request->business_time;
                        $listing->about_business=$request->about_business;
                        $listing->service=$request->service;
                        $listing->map=($request->map!="")?$request->map:'';
                        $listing->lat=($request->lat!="")?$request->lat:'';
                        $listing->longi=($request->longi!="")?$request->longi:'';
                        $listing->pincode=($request->pincode!="")?$request->pincode:'';
                        $listing->save();
                        // Add To Gallery If any
                        $lastid = $listing->id;
                        $thumb=[];
                        if ($files = $request->file('gallery')){
                            foreach ($files as  $key => $file){
                                    $gallery = new Galleries;
                                    $mime_type=explode('/', $file->getMimeType());
                                    
                                    $name = time().str_replace(' ', '', $file->getClientOriginalName());
                                    $file->move(public_path('uploads/listing_gallery'),$name);
                                    $gallery['image_video'] = $name;
                                    $gallery['listing_id'] = $lastid;
                                    $gallery['created_at'] = now();
                                    $gallery['updated_at'] = now();
                                    $gallery->save();
                                    $thumb[] =$name;
                                    // if($mime_type[0]=='image' && $thumb==''){
                                    //     $thumb =$name;
                                    //     $listing->image  = $thumb;
                                    //     $listing->update();
    
                                    // }
                            }
                        }
                        if ($files1 = $request->file('cover_image')){
                            $gallery = new Galleries;
                            $names = time().str_replace(' ', '', $files1->getClientOriginalName());
                            $files1->move(public_path('uploads/listing_gallery'),$names);
                            $gallery['image_video'] = $names;
                            $gallery['listing_id'] = $lastid;
                            $gallery['created_at'] = now();
                            $gallery['updated_at'] = now();
                            $gallery->save();
                            $listing->cover_image =$names;
                                    
                        }
                        if (!empty($thumb)) {
                            $listing->image  = implode(',', $thumb);
                            $listing->update();
                        }
                        //logic Section Ends
                        // $listing->save();
                        return response()->json([
                            'data' =>$listing,
                            'success' => true,
                            'message' => 'add listing successfully.',
                            'status'=>200
                        ]);
                    }
                    else{
                        return response()->json([
                            'data' =>'',
                            'success' => false,
                            'message' => 'your listing limit exhausted , please update your plan',
                        ]);
                    }

                }
                else{
                    return response()->json([
                        // 'data' =>$user,
                        'success' => false,
                        'message' => 'please make payment first then add listing',
                    ]);
                }
            
            }
            
        } catch (JWTException $exception) {
            return response()->json([
                'success' => false,
                'message' => 'user not found'
            ]);
        }
    }
    /*user personal listing*/
    public function my_listing(Request $request)
    {   
        $data = $request->only('token');
        $validator = Validator::make($data, [
            'token'=>'required'
        ]);

        //Send failed response if request is not valid
        if ($validator->fails()) {
            return response()->json(['error' => $validator->messages()], 200);
        }
            
        try {
            if($user = JWTAuth::authenticate($request->token)){
                // $datas['listings'] =Listing::where(['user_id'=>$user->id,'status'=>1])->get()->toarray();
                $listings =Listing::select('listing.*','categories.name as category_name','subcategory.name as subcategory_name')
                                ->leftjoin('categories','listing.category','=','categories.id')
                                ->leftjoin('categories as subcategory','listing.subcategory','=','subcategory.id')
                                ->where(['listing.user_id'=>$user->id,'listing.status'=>1])
                                ->get();
                   
                foreach($listings as $listing){
                    $stars = Rating::where('listing_id',$listing->id)->avg('rating');
                    $stars = number_format((float)$stars, 1, '.', '');
                    $listing->average_rating =$stars;
                    $listing->stars =$stars*20;
                    // $img = explode(',', $listing->image);
                    // $listing->image= asset("uploads/listing_gallery/") .'/'.$img[0];
                    $listing->image= asset("uploads/listing_gallery/") .'/'.$listing->cover_image;
                    
                }
                $datas['listings']=$listings;
                if (!empty($datas['listings'])) {
                	$datas['path'] =  asset("uploads/listing_gallery/");
	                return response()->json([
	                    'data' =>$datas,
	                    'success' => true,
	                    'message' => 'Get my listing successfully.',
	                    'status'=>200
	                ]);
                }else{
                	return response()->json([
                        'success' => false,
                        'message' => 'Listings not found'
                    ]);
                }
            
            }

            
        } catch (JWTException $exception) {
            return response()->json([
                'success' => false,
                'message' => 'user not found'
            ]);
        }
    }
    /*dashboard listing api*/
    public function listing(Request $request)
    {   
        $data = $request->only('category_id','subcategory_id');
        $validator = Validator::make($data, [
            'category_id'=>'required',
            'subcategory_id'=>'required'
        ]);

        //Send failed response if request is not valid
        if ($validator->fails()) {
            return response()->json(['error' => $validator->messages()], 200);
        }
            
        try {
                $listings =Listing::select('listing.*','categories.name as category_name','subcategory.name as subcategory_name')
                                ->leftjoin('categories','listing.category','=','categories.id')
                                ->leftjoin('categories as subcategory','listing.subcategory','=','subcategory.id')
                                ->where('listing.category','=',$request->category_id)
                                ->where('listing.subcategory','=',$request->subcategory_id)
                                ->where('listing.status','=',1)
                                ->get();
                   
                foreach($listings as $listing){
                    $stars = Rating::where('listing_id',$listing->id)->avg('rating');
                    $stars = number_format((float)$stars, 1, '.', '');
                    $listing->average_rating =$stars;
                    $listing->stars =$stars*20;
                    // $img = explode(',', $listing->image);
                    // $listing->image= asset("uploads/listing_gallery/") .'/'.$img[0];
                    $listing->image= asset("uploads/listing_gallery/") .'/'.$listing->cover_image;
                    
                }

                if (!empty($listings->toarray())) {
                    return response()->json([
                        'data' =>$listings,
                        'success' => true,
                        'message' => 'Get listing successfully.',
                        'status'=>200
                    ]);
                }else{
                    return response()->json([
                        'success' => false,
                        'message' => 'Listings not found'
                    ]);
                }
                
                

            
        } catch (JWTException $exception) {
            return response()->json([
                'success' => false,
                'message' => 'user not found'
            ]);
        }
    }
    /*listing details*/
    public function listing_detail(Request $request)
    {   
        $data = $request->only('token','listing_id');
        $validator = Validator::make($data, [
            'token'=>'required',
            'listing_id'=>'required'
        ]);

        //Send failed response if request is not valid
        if ($validator->fails()) {
            return response()->json(['error' => $validator->messages()], 200);
        }
        try {
            if($user = JWTAuth::authenticate($request->token)){
                // $listing =Listing::join('gallery','listing.id','=','gallery.listing_id')->where('listing.id','=',$request->listing_id)->get();
                $result['listing'] =Listing::findOrFail($request->listing_id);
                $wishlists = Wishlist::where('user_id','=',$user->id)->where('listing_id','=',$request->listing_id)->count();
                // $result['gallery'] = Galleries::where('listing_id','=',$request->listing_id)->get();
                $stars = Rating::where('listing_id',$request->listing_id)->orderBy('id','DESC')->avg('rating');
                $stars = number_format((float)$stars, 1, '.', '');
                $result['rating'] =$stars;
                $result['favorite'] =($wishlists > 0)? 1 : 0;
                $result['stars'] =$stars*20;
                $rating_response = Rating::leftJoin('users', 'ratings.user_id', '=', 'users.id')->where('listing_id',$request->listing_id)->get();
                foreach ($rating_response as  $value) {
                    $value->review_date= date('d M, H:i A',strtotime($value->review_date));
                }
                $result['review_list']=$rating_response;
                // print_r($rating_response->toarray());die;
                $result['path'] = asset("uploads/listing_gallery/");
                return
                 response()->json([
                    'data' =>$result,
                    'success' => true,
                    'message' => 'Get listing detail successfully.',
                    'status'=>200
                ]);
            
            }
            
        } catch (JWTException $exception) {
            return response()->json([
                'success' => false,
                'message' => 'user not found'
            ]);
        }
    }

    /*user update listings*/
    public function update_listing(Request $request)
    {   
        $data = $request->only('category','subcategory','title','address','phone','business_time','about_business','service','gallery','token','listing_id','map','lat','longi','pincode','cover_image','old_img');
        $validator = Validator::make($data, [
            'listing_id'=>'required',
            'token'=>'required',
            'pincode'=>'required'
        ]);

        //Send failed response if request is not valid
        if ($validator->fails()) {
            return response()->json(['error' => $validator->messages()], 200);
        }
            
        try {
            if($user = JWTAuth::authenticate($request->token)){
                $listing=Listing::where(['user_id'=>$user->id,'id'=>$data['listing_id']])->first();
                $count= $listing->toarray();
                if(count($count) >0 ){
                    // $listing = new Listing;
                    // $listing->user_id=$user->id;
                    $listing->category=$request->category;
                    $listing->subcategory=$request->subcategory;
                    $listing->title=$request->title;
                    $listing->address=$request->address;
                    $listing->phone=$request->phone;
                    $listing->business_time=$request->business_time;
                    $listing->about_business=$request->about_business;
                    $listing->service=$request->service;
                    $listing->map=$request->map;
                    $listing->lat=($request->lat!="")?$request->lat:'';
                    $listing->longi=($request->longi!="")?$request->longi:'';
                    $listing->pincode=($request->pincode!="")?$request->pincode:'';
                    $listing->update();
                    // Add To Gallery If any
                    $lastid = $listing->id;
                    $thumb=[];
                    if ($files = $request->file('gallery')){
                        foreach ($files as  $key => $file){
                                $gallery = new Galleries;
                                $mime_type=explode('/', $file->getMimeType());
                                
                                $name = time().str_replace(' ', '', $file->getClientOriginalName());
                                $file->move(public_path('uploads/listing_gallery'),$name);
                                $gallery['image_video'] = $name;
                                $gallery['listing_id'] = $lastid;
                                $gallery['created_at'] = now();
                                $gallery['updated_at'] = now();
                                $gallery->save();
                                $thumb[] =$name;
                                // if($mime_type[0]=='image' && $thumb==''){
                                    // $thumb =$name;
                                    // $listing->image  = $thumb;
                                    // $listing->update();
                                // }
                        }
                    }
                    if ($files1 = $request->file('cover_image')){
                        $gallery = new Galleries;
                        $names = time().str_replace(' ', '', $files1->getClientOriginalName());
                        $files1->move(public_path('uploads/listing_gallery'),$names);
                        $gallery['image_video'] = $names;
                        $gallery['listing_id'] = $lastid;
                        $gallery['created_at'] = now();
                        $gallery['updated_at'] = now();
                        $gallery->save();
                        $listing->cover_image =$names;
                        $listing->update();
                    }

                    if (!empty($thumb)) {
                        $listing->image = isset($data['old_img'])&& $data['old_img']!=""?$data['old_img'].','.implode(',', $thumb):implode(',', $thumb);
                        $listing->update();
                    }elseif ($data['old_img']!="") {
                        $listing->image = $data['old_img'];
                        $listing->update();
                    }

                    //logic Section Ends
                    // $listing->save();
                    return response()->json([
                        'data' =>$listing,
                        'success' => true,
                        'message' => 'Updated listing successfully.',
                        'status'=>200
                    ]);
                }else{
                    return response()->json([
                        'success' => false,
                        'message' => 'Listing With This user Not Found'
                    ]);
                }
                
            }
            
        } catch (JWTException $exception) {
            return response()->json([
                'success' => false,
                'message' => 'user not found'
            ]);
        }
    }

    /*user Delete listings*/
    public function delete_listing(Request $request)
    {   
        $data = $request->only('token','listing_id');
        $validator = Validator::make($data, [
            'listing_id'=>'required',
            'token'=>'required'
        ]);

        //Send failed response if request is not valid
        if ($validator->fails()) {
            return response()->json(['error' => $validator->messages()], 200);
        }
            
        try {
            if($user = JWTAuth::authenticate($request->token)){
                $listing=Listing::where(['user_id'=>$user->id,'id'=>$data['listing_id']])->first();
                // $count= $listing->toarray();
                if($listing){
                    $listing->status=0;
                    $listing->update();
                    // $listing->save();
                    return response()->json([
                        'success' => true,
                        'message' => 'listing Deleted successfully.',
                        'status'=>200
                    ]);
                }else{
                    return response()->json([
                        'success' => false,
                        'message' => 'Listing With This user Not Found'
                    ]);
                }
                
            }
            
        } catch (JWTException $exception) {
            return response()->json([
                'success' => false,
                'message' => 'user not found'
            ]);
        }
    }

    // public function getPhoto(Request $request)
    // {   
    //     $data = $request->only('token');
    //     $validator = Validator::make($data, [
    //         'token'=>'required'
    //     ]);

    //     //Send failed response if request is not valid
    //     if ($validator->fails()) {
    //         return response()->json(['error' => $validator->messages()], 200);
    //     }
            
    //     try {
    //             if($user = JWTAuth::authenticate($request->token)){
    //                 $all_photo = Gallery::where(['status'=>1,'image_type'=>'image','user_id'=>$user->id])->orderBy('id','desc')->get();
                   
    //                 return response()->json([
    //                     'data' =>$all_photo,
    //                     'success' => true,
    //                     'message' => 'Get my all photo successfully.',
    //                     'status'=>200
    //                 ]);
                
    //             }

            
    //     } catch (JWTException $exception) {
    //         return response()->json([
    //             'success' => false,
    //             'message' => 'user not found'
    //         ]);
    //     }
    // }

    // public function getVideo(Request $request)
    // {   
    //     $data = $request->only('token');
    //     $validator = Validator::make($data, [
    //         'token'=>'required'
    //     ]);

    //     //Send failed response if request is not valid
    //     if ($validator->fails()) {
    //         return response()->json(['error' => $validator->messages()], 200);
    //     }
            
    //     try {
    //             if($user = JWTAuth::authenticate($request->token)){
    //                 $all_video = Gallery::where(['status'=>1,'image_type'=>'video','user_id'=>$user->id])->orderBy('id','desc')->get();
                   
    //                 return response()->json([
    //                     'data' =>$all_video,
    //                     'success' => true,
    //                     'message' => 'Get my all video successfully.',
    //                     'status'=>200
    //                 ]);
                
    //             }

            
    //     } catch (JWTException $exception) {
    //         return response()->json([
    //             'success' => false,
    //             'message' => 'user not found'
    //         ]);
    //     }
    // }

    public function get_favorite(Request $request)
    {   
        $data = $request->only('token');
        $validator = Validator::make($data, [
            'token'=>'required'
        ]);

        //Send failed response if request is not valid
        if ($validator->fails()) {
            return response()->json(['error' => $validator->messages()], 200);
        }
            
        try {
            if($user = JWTAuth::authenticate($request->token)){
                
                $listings = Wishlist::select('listing.*','categories.name as category_name','subcategory.name as subcategory_name')
                ->leftjoin('listing','listing.id','=','wishlist.listing_id')
                ->leftjoin('categories','listing.category','=','categories.id')
                ->leftjoin('categories as subcategory','listing.subcategory','=','subcategory.id')
                ->where('listing.status','=',1)
                ->where('wishlist.user_id','=',$user->id)
                ->get();
                   
                foreach($listings as $listing){
                    $stars = Rating::where('listing_id',$listing->id)->avg('rating');
                    $stars = number_format((float)$stars, 1, '.', '');
                    $listing->average_rating =$stars;
                    $listing->stars =$stars*20;
                    $img = explode(',', $listing->image);
                    $listing->image= asset("uploads/listing_gallery/") .'/'.$img[0];
                    
                }

                $wishlists = $listings;
                // $wishlists['list'] = Wishlist::where('user_id','=',$user->id)->orderBy('id','desc')->get()->toarray();
                if (!empty($wishlists)) {
                    return response()->json([
                        'data' =>$wishlists,
                        'success' => true,
                        'message' => 'Get my all favorite listing successfully.',
                        'status'=>200
                    ]);
                }else{
                    return response()->json([
                        'success' => false,
                        'message' => 'Get favorite listing Not Found.',
                    ]);
                }
            }
        } catch (JWTException $exception) {
            return response()->json([
                'success' => false,
                'message' => 'user not found'
            ]);
        }
    }

    public function favorite(Request $request)
    {   
        $data = $request->only('token','listing_id');
        $validator = Validator::make($data, [
            'token'=>'required',
            'listing_id'=>'required'
        ]);

        //Send failed response if request is not valid
        if ($validator->fails()) {
            return response()->json(['error' => $validator->messages()], 200);
        }
            
        try {
            if($user = JWTAuth::authenticate($request->token)){
                $wishlist = Wishlist::where('user_id','=',$user->id)
                                ->where('listing_id','=',$request->listing_id)->first();
               
                if($wishlist==''){
                    $wishlist = new Wishlist;
                    $wishlist->listing_id = $request->listing_id;
                    $wishlist->user_id = $user->id;
                    $wishlist->save();
                    $wishlist->favorite=1;
                    return response()->json([
                        'data' =>$wishlist,
                        'success' => true,
                        'message' => 'add listing to wishlist successfully.',
                        'status'=>200
                    ]);
                }else{
                    $data = Wishlist::findOrFail($wishlist->id);
                    $data->delete();
                    $data->favorite=0;
                    return response()->json([
                        'data' =>$data,
                        'success' => true,
                        'message' => 'remove listing from wishlist successfully.',
                        'status'=>200
                    ]);

                }
                
            
            }
            
        } catch (JWTException $exception) {
            return response()->json([
                'success' => false,
                'message' => 'user not found'
            ]);
        }
    }
    /*submit review ratings*/
    public function review_submit(Request $request)
    {   
        $data = $request->only('token','listing_id','review','rating');
        $validator = Validator::make($data, [
            'token'=>'required',
            'listing_id'=>'required',
            'review'=>'required',
            'rating'=>'required'
        ]);

        //Send failed response if request is not valid
        if ($validator->fails()) {
            return response()->json(['error' => $validator->messages()], 200);
        }
            
        try {
                if($user = JWTAuth::authenticate($request->token)){
                    // print_r($user->toarray());die;
                    $prev = Rating::where('listing_id','=',$request->listing_id)->where('user_id','=',$user->id)->get();
                    if(count($prev) > 0)
                    {
                      $id=Rating::where('listing_id','=',$request->listing_id)->where('user_id','=',$user->id)->first()->id;
                      $input=$request->all();
                      $input['review_date'] =date('Y-m-d H:i:s');
                      //echo "<pre>";print_r($input);die;
                      $data = Rating::findOrFail($id);
                      $data->update($input);
                      
                      return response()->json([
                        'data' =>$data,
                        'success' => true,
                        'message' => 'Your Rating Updated Successfully.',
                        'status'=>200
                      ]);
                     
                    }
                    $Rating = new Rating;
                    $Rating->fill($request->all());
                    $Rating['review_date'] = date('Y-m-d H:i:s');
                    $Rating['user_id'] = $user->id;
                    $Rating->save();
                   // $data[0] = 'Your Rating Submitted Successfully.';
                    //$data[1] = Rating::rating($request->product_id);
                    return response()->json([
                        'data' =>$Rating,
                        'success' => true,
                        'message' => 'Your Rating Submitted Successfully.',
                        'status'=>200
                      ]);
    
                }
            
        } catch (JWTException $exception) {
            return response()->json([
                'success' => false,
                'message' => 'user not found'
            ]);
        }
    }

    public function about_us(Request $request)
    {   
        $about_us = Pages::where(['title'=>'About Us'])->first()->toarray();
        if (!empty($about_us)) {
            return response()->json([
                'data' =>$about_us,
                'success' => true,
                'message' => 'Get about us page successfully.',
                'status'=>200
            ]);
        }else{
            return response()->json([
                'success' => false,
                'message' => 'About Us Page Not Found'
            ]);
        }
                
    }

    public function term_condition(Request $request)
    {   
        $term_condition = Pages::where(['title'=>'Terms and conditions'])->first()->toarray();
        if (!empty($term_condition)) {
            return response()->json([
                'data' =>$term_condition,
                'success' => true,
                'message' => 'Get term & condition page successfully.',
                'status'=>200
            ]);
        }else{
            return response()->json([
                'success' => false,
                'message' => 'Term & condition page Not Found.',
             ]);
        }
                
    }

    public function policy(Request $request)
    {   
        $policy = Pages::where(['title'=>'Policy'])->first()->toarray();
        if (!empty($policy)) {
            return response()->json([
                'data' =>$policy,
                'success' => true,
                'message' => 'Get data policy page successfully.',
                'status'=>200
            ]);
        }else{
            return response()->json([
                'success' => false,
                'message' => 'Get data policy page Not Found.',
            ]);
        }
                
    }

    public function get_package(Request $request)
    {   
        $packages['list'] = Package::where('status','=',1)->get()->toarray();
        if (!empty($packages['list'])) {
            return response()->json([
                'data' =>$packages,
                'success' => true,
                'message' => 'Get Package List Successfully.',
                'status'=>200
            ]);
        }else{
            return response()->json([
                'success' => true,
                'message' => 'Package List Not Found.',
            ]);
        }
                
    }

    public function imageUpload(Request $request)
    {   
        $data = $request->only('token','image');
        $validator = Validator::make($data, [
            'image'=>'required',
            'token'=>'required'
        ]);

        //Send failed response if request is not valid
        if ($validator->fails()) {
            return response()->json(['error' => $validator->messages()], 200);
        }
            
        try {
            if($user = JWTAuth::authenticate($request->token)){
                $file = $request->image;                                        
                $name = time().str_replace(' ', '', $file->getClientOriginalName());
                $file->move(public_path('uploads/profile_pic'),$name);
                $user->image="uploads/profile_pic/".$name;
                $user->update();
                      
                return response()->json([
                    'data' =>$user,
                    'success' => true,
                    'message' => 'Profile pic upload successfully',
                    'status'=>200
                ]);
            }
            
        } catch (JWTException $exception) {
            return response()->json([
                'success' => false,
                'message' => 'user not found'
            ]);
        }
    }


    public function online_darshan(Request $request)
    {   
        $darshan = Darshan::where(['status'=>1,'trash'=>1])->orderBy('id','DESC')->get();
        foreach ($darshan as  $value) {
            if($value->image!=""){
                $value->image = url($value->image);
            }
        }
        if (!empty($darshan)) {
            return response()->json([
                'data' =>$darshan,
                'success' => true,
                'message' => 'Darshan Found successfully.',
                'status'=>200
            ]);
        }else{
            return response()->json([
                'success' => false,
                'message' => 'Darshan Not Found',
            ]);
        }
                
    }

    public function annivarsary_list(Request $request)
    {   
        try {
                // if($user = JWTAuth::authenticate($request->token)){
                    // start range 7 days ago
                    $start = date('z') + 1;
                    // end range 7 days from now
                    $end = date('z') + 1 + 30;
                    $data = User::whereRaw("DAYOFYEAR(created_at) BETWEEN $start AND $end")->get();
                    // $data['list'] = User::whereRaw("DAYOFYEAR(created_at) BETWEEN $start AND $end")->get()->toarray();
                    if(count($data->toarray()) > 0)
                    {
                        $response['list']=[];
                        foreach ($data->toarray() as $key=>  $value) {
                            $response['list'][$key]=$value;
                            $response['list'][$key]['image'] = ($value['image']!="")? url('/').'/'.$value['image'] : url('uploads/user.png');
                            $response['list'][$key]['created_at'] = date('d-m-Y',strtotime($value['created_at']));
                        }
                        // foreach ($data as  $value) {
                        //     if($value->image==""){
                        //         $value->image = ($value->image!="")?$value->image:url('uploads/user.png');
                        //     }
                        // }
                      // $userslist['list'] =$data;
                      return response()->json([
                        'data' =>$response,
                        'success' => true,
                        'message' => 'Upcoming Annivarsary List Found',
                        'status'=>200
                      ]);                        
                    }  
                    return response()->json([
                        'success' => false,
                        'message' => 'Annivarsary List Not Found'
                      ]);
                // } 
            
        } catch (JWTException $exception) {
            return response()->json([
                'success' => false,
                'message' => 'user not found'
            ]);
        }
    }

    public function crateAds_request(Request $request)
    {   
        $data = $request->only('title','image','start_date','end_date','link','token');
        $validator = Validator::make($data, [
            'title'=>'required',
            'image'=>'required',
            'start_date'=>'required',
            'end_date'=>'required',
            'token'=>'required'
        ]);
        // print_r($request->all());die;
        //Send failed response if request is not valid
        if ($validator->fails()) {
            return response()->json(['error' => $validator->messages()], 200);
        }
        try {
                if($user = JWTAuth::authenticate($request->token)){
                    $data['user_id']=$user->id;
                    $data['start_date']=strtotime($data['start_date']);
                    $data['end_date']=strtotime($data['end_date']);
                    $data['created_by']='user';
                    $data['approve']=0;

                    $file = $request->image;                                        
                    $filenameWithExt = time().str_replace(' ', '', $file->getClientOriginalName());
                    // Get Filename
                    $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);
                    $filename = str_replace(' ', '_', $filename);
                    // Get just Extension
                    $extension = $file->getClientOriginalExtension();
                    // Filename To store
                    $fileNameToStore = $filename. '_'. time().'.'.$extension;

                    $file->move(public_path('uploads/advertisement'), $fileNameToStore);
                    $data['thumbnail']="uploads/advertisement/".$fileNameToStore;
                    unset($data['token']);
                    unset($data['image']);
                    $res = Advertisement::create($data);
                    if($res)
                    {
                      return response()->json([
                        'success' => true,
                        'message' => 'Advertisement Send Successfully',
                        'status'=>200
                      ]);                        
                    }  
                    return response()->json([
                        'success' => false,
                        'message' => 'Advertisement Not created'
                      ]);
                } 
            
        } catch (JWTException $exception) {
            return response()->json([
                'success' => false,
                'message' => 'user not found'
            ]);
        }
    }
    
    /*get advertisement */
    public function getAdvertisementList(){
        $today=strtotime(date('Y-m-d'));
        // $data['list'] = Advertisement::whereIn('created_by',['admin'])->where(['status'=>1,'approve'=>1])->orwhere('start_date','>=',$today)->orwhere('end_date','<=',$today)->orderBy('id','desc')->get()->toarray();
        $data['list'] = Advertisement::whereIn('created_by',['admin'])->orwhere('start_date','<=',$today)->where('end_date','>=',$today)->where(['status'=>1,'approve'=>1])->orderBy('id','desc')->get();
        // $data['path'] =  asset("uploads/advertisement/") ;
        if (!empty($data['list'])) {
            $data['path'] =  url('/');
            return response()->json([
                'data' =>$data,
                'success' => true,
                'message' => 'Advertisement list Found successfully',
                'status'=>200
            ]);
        }else{
               return response()->json([
                'success' => false,
                'message' => 'Advertisement list Not Found'
            ]); 
        }
    }

    /*get contact support */
    public function contact_support(Request $request)
    {   
        $contact_support = Pages::where(['title'=>'Customer Support'])->first()->toarray();
        if (!empty($contact_support)) {
            return response()->json([
                'data' =>$contact_support,
                'success' => true,
                'message' => 'Get Customer Support page successfully.',
                'status'=>200
            ]);
        }else{
             return response()->json([
                'success' => false,
                'message' => 'Get Customer Support page Not Found.',
                'status'=>200
            ]); 
        }           
                
    }

    /*get Category List */
    public function getCategoryList(){
        $data['category_list'] = Categories::where(['parent_id'=>0])->where(['status'=>1])->get()->toarray();
        if (!empty($data['category_list'])) {
            $data['path'] =  asset("uploads/category/") ;
            return response()->json([
                'data' =>$data,
                'success' => true,
                'message' => 'Get Category List successfully.',
                'status'=>200
            ]);
        }else{
            return response()->json([
                'success' => false,
                'message' => 'Category List Not Found.',
            ]);
        }
    }

    /*get Event List */
    public function allEvent(){
        
        $data = Event::where(['status'=>1])->orderBy('id','desc')->get()->toarray();
        $response['list']=[];
        foreach ($data as $key=>  $value) {
            $response['list'][$key]=$value;
            $response['list'][$key]['created_at'] = date('d-m-Y',strtotime($value['created_at']));
        }
        if (!empty($response['list'])) {
            // $data['path'] =  url("/") ;
            $response['path'] =  asset("uploads/event/") ;
            return response()->json([
                'data' =>$response,
                'success' => true,
                'message' => 'Get All Event successfully.',
                'status'=>200
            ]);
        }else{
            return response()->json([
                'success' => false,
                'message' => 'Event List Not Found.',
            ]);
        }
    }

    /*get Event List */
    public function eventDetails(Request $request){
        $data = $request->only('token','eventid');

        $validator = validator::make($data,[
            'token'=>'required',
            'eventid'=>'required'
        ]);

        //Send failed response if request is not valid
        if ($validator->fails()) {
            return response()->json(['error' => $validator->messages()], 200);
        }

        try {
            if($user= JWTAuth::authenticate($request->token)){
                $event = Event::where(['status'=>1,'id'=>$data['eventid']])->first();
                if (!empty($event)) {
                    $event->path =  asset("uploads/event/") ;
                    return response()->json([
                        'data' =>$event,
                        'success' => true,
                        'message' => 'Get Event Details successfully.',
                        'status'=>200
                    ]);
                }
            }
            return response()->json([
                'success' => false,
                'message' => 'Invalid Event Id'
            ]);
            
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'No User Found'
            ]);
        }
        
    }

    /*get upcoming birthday list*/
    public function upcomingBirthday(){
        // $thisDayNo = Carbon::now()->day;
        // $thisMonthNo = Carbon::today()->month;
        // $nextMonthNo = $thisMonthNo+1;
        // if($nextMonthNo > 12)
        //     $nextMonthNo = 1;


        // $upcoming_birthday = User::where('id','!=',1)->whereBetween(DB::raw('MONTH(dob)'), [Carbon::today()->month,Carbon::today()->month+1])
        //     ->where(function ($query) use ($thisMonthNo,$nextMonthNo,$thisDayNo) {
        //         $query->where(function ($q1) use ($thisMonthNo,$thisDayNo) {
        //             $q1->where(DB::raw('MONTH(dob)'), $thisMonthNo)
        //             ->where(DB::raw('DAY(dob)'), '>=', $thisDayNo);
        //         })
        //     ->orWhere(function ($q2) use ($nextMonthNo,$thisDayNo) {
        //             $q2->where(DB::raw('MONTH(dob)'), $nextMonthNo)
        //             ->where(DB::raw('DAY(dob)'), '<=', $thisDayNo);
        //         });
        //     })
        //     ->orderByRaw('DATE_FORMAT(dob, "%m/%d")','DESC')
        //     ->get();

        $start = date('z') + 1;
        // end range 7 days from now
        $end = date('z') + 1 + 30;
        $upcoming_birthday = User::whereRaw("DAYOFYEAR(dob) BETWEEN $start AND $end")->get();

            
        if (!empty($upcoming_birthday->toarray())) {
            foreach ($upcoming_birthday as  $value) {
                $value->image = ($value->image!="")?$value->image:url('uploads/user.png');
                $value->dob=date('d-m-Y',strtotime($value->dob));
            }
            return response()->json([
                'data' =>$upcoming_birthday,
                'success' => true,
                'message' => 'Bithday List found Successfully',
                'status'=>200
            ]);
        }else{
            return response()->json([
                'success' => false,
                'message' => 'Bithday List Not Found'
            ]);
        }
    }

    /*get photo list*/
    public function getPhoto(){
        
        $all_photo['list'] = Gallery::where(['status'=>1,'image_type'=>'Photo'])->orderBy('id','desc')->get()->toarray();
        if (!empty($all_photo['list'])) {
            $all_photo['path']= asset('uploads/gallery/');
            return response()->json([
                'data' =>$all_photo,
                'success' => true,
                'message' => 'Photo List Found Successfully',
                'status'=>200
            ]);
        }else{
            return response()->json([
                'success' => false,
                'message' => 'Photo List Not Found'
            ]);
        }
    }

    /*get Video list*/
    public function getVideo(){
        
        $all_video['list'] = Gallery::where(['status'=>1,'image_type'=>'Video'])->orderBy('id','desc')->get()->toarray();
        if (!empty($all_video['list'])) {
            $all_video['path']= asset('uploads/gallery/');
            return response()->json([
                'data' =>$all_video,
                'success' => true,
                'message' => 'Video List Found Successfully',
                'status'=>200
            ]);
        }else{
            return response()->json([
                'success' => false,
                'message' => 'Video List not Found'
            ]);
        }
    }

    /*get SubCategory list*/
    public function getSubCategoryList(Request $request){
        if ($request->category_id!="") {
            $data['list']= Categories::where(['parent_id'=>$request->category_id])->where(['status'=>1])->orderBy('name','ASC')->get()->toarray();
            if (!empty($data['list'])) {
                $data['path'] =  asset("uploads/category/") ;
                return response()->json([
                    'data' =>$data,
                    'success' => true,
                    'message' => 'get SubCategory List Successfully',
                    'status'=>200
                ]);
            }else{
                return response()->json([
                    'success' => false,
                    'message' => 'get SubCategory List not Found'
                ]);
            }
        }else{
            return response()->json([
                'success' => false,
                'message' => 'Category Id Required'
            ]);
        }
    }

    /*get Top SubCategory list*/
    public function getTopSubCategoryList(Request $request){
        if ($request->category_id!="") {
            $data['list']= Categories::where(['parent_id'=>$request->category_id])->where(['status'=>1,'top'=>1])->orderBy('name','ASC')->get()->toarray();
            if (!empty($data['list'])) {
                $data['path'] =  asset("uploads/category/") ;
                return response()->json([
                    'data' =>$data,
                    'success' => true,
                    'message' => 'get Top SubCategory List Successfully',
                    'status'=>200
                ]);
            }else{
                return response()->json([
                    'success' => false,
                    'message' => 'get Top SubCategory List not Found'
                ]);
            }
        }else{
            return response()->json([
                'success' => false,
                'message' => 'Category Id Required'
            ]);
        }
    }

    /*get search list*/
    public function search(Request $request){
        //Validate data
        $data = $request->only('keyword');
        $validator = Validator::make($data,[
            'keyword'=>'required',
        ]);

        //Send failed response if request is not valid
        if ($validator->fails()) {
            return response()->json(['error' => $validator->messages()], 200);
        }
        $listings =Listing::select('listing.*','categories.name as category_name','subcategory.name as subcategory_name')
                            ->join('categories','listing.category','=','categories.id')
                            ->join('categories as subcategory','listing.subcategory','=','subcategory.id')
                            ->orWhere('categories.name','like','%'.$data['keyword'].'%')
                            ->orWhere('subcategory.name','like','%'.$data['keyword'].'%')
                            ->orWhere('listing.title','like','%'.$data['keyword'].'%')
                            ->where('listing.status','=',1)
                            ->get();
                            // die;
        foreach($listings as $listing){
            $stars = Rating::where('listing_id',$listing->id)->avg('rating');
            $stars = number_format((float)$stars, 1, '.', '');
            $listing->average_rating =$stars;
            $listing->stars =$stars*20;
            // $listing->image= asset("uploads/listing_gallery/") .'/'.$listing->image;
            $img = explode(',', $listing->image);
            $listing->image= asset("uploads/listing_gallery/") .'/'.$img[0];
            
        }
        // $listings['list']=$listings;
        if (!empty($listings)) {
            $data['list']=$listings;
            $data['path'] =  asset("uploads/listing_gallery/") ;
            return response()->json([
                'data' =>$data,
                'success' => true,
                'message' => 'get Search List Successfully',
                'status'=>200
            ]);
        }else{
            return response()->json([
                'success' => false,
                'message' => 'Search List not Found'
            ]);
        }
    }

    /**latest latest event list */
    public function latestEvent(){
        
        $data = Event::where(['status'=>1])->orderBy('id','desc')->take(2)->get()->toarray();
        $response['list']=[];
        foreach ($data as $key=>  $value) {
            $response['list'][$key]=$value;
            $response['list'][$key]['created_at'] = date('d-m-Y',strtotime($value['created_at']));
        }
        if (count($response['list'])>0) {
            $response['path'] =  asset("uploads/event/") ;
            return response()->json([
                'data' =>$response,
                'success' => true,
                'message' => 'Latest Event List Found Successfully',
                'status'=>200
            ]);
        }else{
            return response()->json([
                'success' => false,
                'message' => 'Latest Event list not found'
            ]);
        }
    }

    /**latest updated event to oldest */
    public function latestUpdatedEvent(){
        
        $data['list'] = Event::where(['status'=>1])->orderBy('updated_at','desc')->get()->toarray();
        if (!empty($data['list'])) {
            $data['path'] =  asset("uploads/event/") ;
            return response()->json([
                'data' =>$data,
                'success' => true,
                'message' => 'Latest Updated Event List Found Successfully',
                'status'=>200
            ]);
        }else{
            return response()->json([
                'success' => false,
                'message' => 'Latest Event list not found'
            ]);
        }
    }

    /**oldest updated event to latest */
    public function oldestUpdatedEvent(){
    
        $data['list'] = Event::where(['status'=>1])->orderBy('updated_at','asc')->get()->toarray();
        if (!empty($data['list'])) {
            $data['path'] =  asset("uploads/event/") ;
            return response()->json([
                'data' =>$data,
                'success' => true,
                'message' => 'Latest Oldest Event List Found Successfully',
                'status'=>200
            ]);
        }else{
            return response()->json([
                'success' => false,
                'message' => 'Oldest Event list not found'
            ]);
        }
    }
    /*Response::HTTP_INTERNAL_SERVER_ERROR*/

    /*add Package List*/
    public function add_package(Request $request){
        //Validate data
        $data = $request->only('phone','package_id','transaction_id');
        $validator = Validator::make($data,[
            'phone'=>'required|min:10|max:10',
            'package_id'=>'required',
        ]);

        //Send failed response if request is not valid
        if ($validator->fails()) {
            return response()->json(['success' => false,'error' => $validator->messages()], 200);
        }

        try{
            $users = User::where(['phone'=>$data['phone']])->first();
            $orderhistory = OrderHistory::create([
                'userid'=>$users->id,
                'package_id'=>$data['package_id'],
                'transaction_id'=>isset($data['transaction_id'])?$data['transaction_id']:''
            ]);
            if ($orderhistory) {
                $packcount =Package::where(['id'=>$data['package_id']])->first('package_listing_limit');
                $package_Count = $users->package_limit+$packcount->package_listing_limit;
            	$res= User::where(['phone'=>$data['phone']])->update([
            		'package_id'=>$data['package_id'],
            		'package_limit'=>$package_Count
            	]);
                if($res){
                   return response()->json([
                       'success' => true,
                       'message' => 'Package Added Successfully',
                       'status'=>200
                   ]);
                  
                }else{
                	return response()->json([
    	               'success' => false,
    	               'message' => 'Package Not Updated Successfully'
    	            ]);
                }
            }else{

            }

        }catch (JWTException $exception) {
           return response()->json([
               'success' => false,
               'message' => 'Some Error Found Please Try again'
           ]);
        }
    }

    /**get notification list */
    public function notification(Request $request){
        //Validate data
        $data = $request->only('token');
        $validator = Validator::make($data,[
            'token'=>'required',
        ]);
        //Send failed response if request is not valid
        if ($validator->fails()) {
            return response()->json(['error' => $validator->messages()], 200);
        }
        try {
            if($user = JWTAuth::authenticate($request->token)){
                $res = NotificationStatus::select('notifications.*')->join('notifications','notifications.id','=','notification_status.notification_id')->where([
                    'status'=>1,
                    'user_id'=>$user->id
                ])->orderBy('id','DESC')->get();
               
                if(count($res)>0)
                {
                  return response()->json([
                    'data' =>$res,
                    'success' => true,
                    'message' => 'Notification list Found',
                    'status'=>200
                  ]);                        
                }  
                return response()->json([
                    'success' => false,
                    'message' => 'No New Notification found'
                  ]);
            } 
            
        } catch (JWTException $exception) {
            return response()->json([
                'success' => false,
                'message' => 'user not found'
            ]);
        }
    }

    /**read notification list */
    public function read_notification(Request $request){
        //Validate data
        $data = $request->only('token','id');
        $validator = Validator::make($data,[
            'token'=>'required',
            'id'=>'required',
        ]);
        //Send failed response if request is not valid
        if ($validator->fails()) {
            return response()->json(['error' => $validator->messages()], 200);
        }
        try {
            if($user = JWTAuth::authenticate($request->token)){
                $res = NotificationStatus::where([
                    'notification_id'=>$data['id'],
                    'user_id'=>$user->id
                ])->update(['status'=>0]);
               
                if($res)
                {
                    return response()->json([
                        'data' =>$res,
                        'success' => true,
                        'message' => 'Notification read successfully',
                        'status'=>200
                    ]);                        
                }  
                return response()->json([
                    'success' => false,
                    'message' => 'Notification Not read successfully'
                ]);
            } 
            
        } catch (JWTException $exception) {
            return response()->json([
                'success' => false,
                'message' => 'user not found'
            ]);
        }
    }

    /**read order history list */
    public function orderhistory(Request $request){
        //Validate data
        $data = $request->only('token');
        $validator = Validator::make($data,[
            'token'=>'required',
        ]);
        //Send failed response if request is not valid
        if ($validator->fails()) {
            return response()->json(['error' => $validator->messages()], 200);
        }
        try {
            if($user = JWTAuth::authenticate($request->token)){
                $res = OrderHistory::where([
                    'userid'=>$user->id
                ])->get();
               
                if(count($res)>0)
                {
                    return response()->json([
                        'data' =>$res,
                        'success' => true,
                        'message' => 'Data Found successfully',
                        'status'=>200
                    ]);                        
                }  
                return response()->json([
                    'success' => false,
                    'message' => 'Data Not Found'
                ]);
            } 
            
        } catch (JWTException $exception) {
            return response()->json([
                'success' => false,
                'message' => 'user not found'
            ]);
        }
    }

    /**read recent search list */
    public function getrecentsearchList(Request $request){
        //Validate data
        $data = $request->only('device_id');
        $validator = Validator::make($data,[
            'device_id'=>'required',
        ]);
        //Send failed response if request is not valid
        if ($validator->fails()) {
            return response()->json(['error' => $validator->messages()], 200);
        }

        $response = RecentSearch::where(['device_id'=>$data['device_id'],'status'=>1])->orderBy('id','DESC')->limit(15)->get();
        if(count($response)>0){
            return response()->json([
                'data' =>$response,
                'success' => true,
                'message' => 'Data Found successfully',
                'status'=>200
            ]);                        
        }else{
            return response()->json([
                'success' => false,
                'message' => 'Data Not Found'
            ]);
        } 
    }

    /**Delete Recent Search */
    public function deleterecentsearch(Request $request){
        //Validate data
        $data = $request->only('id');
        $validator = Validator::make($data,[
            'id'=>'required',
        ]);
        //Send failed response if request is not valid
        if ($validator->fails()) {
            return response()->json(['error' => $validator->messages()], 200);
        }
        $res = RecentSearch::where([
            'id'=>$data['id']
        ])->update(['status'=>0]);
       
        if($res)
        {
            return response()->json([
                'data' =>$res,
                'success' => true,
                'message' => 'Deleted successfully',
                'status'=>200
            ]);                        
        }  
        return response()->json([
            'success' => false,
            'message' => 'Not Deleted successfully'
        ]);
            
    }
    /*add recent Search*/
    public function addrecentsearch(Request $request){
        //Validate data
        $data = $request->only('device_id','image','listing_id','title');
        $validator = Validator::make($data,[
            'device_id'=>'required',
            'title'=>'required',
        ]);

        //Send failed response if request is not valid
        if ($validator->fails()) {
            return response()->json(['success' => false,'error' => $validator->messages()], 200);
        }

        try{
            RecentSearch::where($data)->update(['status'=>0]);
            $response = RecentSearch::create($data);
            if ($response) {
               return response()->json([
                   'success' => true,
                   'message' => 'Added Successfully',
                   'status'=>200
               ]);
              
            }else{
                return response()->json([
                   'success' => false,
                   'message' => 'Not Added Successfully'
                ]);
            }

        }catch (JWTException $exception) {
           return response()->json([
               'success' => false,
               'message' => 'Some Error Found Please Try again'
           ]);
        }
    }
    /*upload title*/
    public function saveTitle(Request $request){
        
        ini_set('max_execution_time', '0');
        if($request->token!=''){
            try{
                if($user = JWTAuth::authenticate($request->token)){
                    
                    if(!empty($request->image))
                    {
                        $image = $request->image;                                        
    
                        $base64_str = substr($image, strpos($image, ",")+1);
        
                        //decode base64 string
                        $image = base64_decode($base64_str);
                        $png_url = time().".jpg";
                        $path = public_path('uploads/title/' .$user->id."/".$png_url);
                        
                        $imagepath=public_path().'/uploads/title/' . $user->id;
                        if( !is_dir( $imagepath ) ) mkdir( $imagepath, 0755, true );
                        
                        Image::make(file_get_contents($request->image))->save($path);
                        $imagepath=env("APP_URL")."public/uploads/title/".$user->id."/".$png_url;
                        
                        if(!empty($request->video_format)){
                            $video_url = "/uploads/make_video/".$user->id."/".time().'.'.$request->video_format;
                            $output_path= public_path().$video_url;
                        }else{ 
                            $video_url = "/uploads/make_video/".$user->id."/".time().'.mp4';
                            $output_path= public_path().$video_url;
                        }
                        $blankaudio = public_path("/silence_no_sound.mp3");
                        
                        if(!empty($request->ratio)){
                            $ratio=" -r 15 -aspect ".$request->ratio." -strict -2 ";
                            //-r 15 -vf scale=1080:-1 -aspect 1:1 -strict -2
                        }
                        $output= shell_exec("ffmpeg  -r 1/5 -start_number 0 -i $path -i $blankaudio -r 15 -c:v libx264 -pix_fmt yuv420p -vf scale=1440:1024 $ratio $output_path 2>&1");
                        
                        $title = env("APP_URL")."public".$video_url;
                        
                        if(file_exists($path)) { 
                              unlink($path); //remove the file
                        }
                    }
                    $msg  = array('status'=>true,'message' => "Title upload successfully",'data'=>$title);
                    echo json_encode($msg);
                }else{
                    $msg  = array('status'=>false,'message' => "User not found","data"=>[]);
                    echo json_encode($msg);
                }
            }catch (JWTException $e) {
    
                $msg  = array('status'=>false,'message' => "Could Not Create Token.",'data'=>[]);
                echo json_encode($msg);
            }
        }else{
            $msg  = array('status'=>false,'message' => "Please send all parameters",'data'=>[]);
            echo json_encode($msg);
        }
 

    }
    
    /*upload video1*/
    public function introVideo(Request $request){
        
        ini_set('max_execution_time', '0');
        if($request->token!=''){
            try{
                if($user = JWTAuth::authenticate($request->token)){
                    
                    if ($request->hasFile('video1')) {
                        $extension = $request->file('video1')->getClientOriginalExtension();
                        // Filename To store
                        $fileNameToStore = time().'.'.$extension;
            
                        $request->video1->move(public_path('uploads/make_video/'.$user->id."/"), $fileNameToStore);
                        echo $video1 =public_path('/uploads/make_video/'.$user->id."/".$fileNameToStore);
                       
                        if(!empty($request->ratio)){
                            $ratio=" -r 15 -aspect ".$request->ratio." -strict -2 ";
                            
                        }
                        else{
                            $ratio=" -r 15 -aspect 16:9 -strict -2 ";
                        }
                        
                        $output_path = public_path('/uploads/make_video/'.$user->id."/video1_".$fileNameToStore);
                        $output= shell_exec("ffmpeg -i $video1 -vf scale=1440:1024 $ratio $output_path 2>&1");
                        $video_url= env("APP_URL")."public/uploads/make_video/".$user->id."/video1_".$fileNameToStore;
                        
                        if(file_exists($video1)) { 
                              unlink($video1); //remove the file
                        }
                    }
                    $msg  = array('status'=>true,'message' => "Intro video upload successfully",'data'=>$video_url);
                    echo json_encode($msg);
                }else{
                    $msg  = array('status'=>false,'message' => "User not found","data"=>[]);
                    echo json_encode($msg);
                }
            }catch (JWTException $e) {
    
                $msg  = array('status'=>false,'message' => "Could Not Create Token.",'data'=>[]);
                echo json_encode($msg);
            }
        }else{
            $msg  = array('status'=>false,'message' => "Please send all parameters",'data'=>[]);
            echo json_encode($msg);
        }
 

    }
    
    /*upload video2*/
    public function outroVideo(Request $request){
        
        ini_set('max_execution_time', '0');
        if($request->token!=''){
            try{
                if($user = JWTAuth::authenticate($request->token)){
                    
                    if ($request->hasFile('outro_video')) {
                        $extension = $request->file('outro_video')->getClientOriginalExtension();
                        // Filename To store
                        $fileNameToStore = time().'.'.$extension;
            
                        $request->outro_video->move(public_path('uploads/make_video/'.$user->id."/"), $fileNameToStore);
                        $outro_video =public_path('/uploads/make_video/'.$user->id."/".$fileNameToStore);
                       
                        if(!empty($request->ratio)){
                            $ratio=" -r 15 -aspect ".$request->ratio." -strict -2 ";
                            
                        }
                        else{
                            $ratio=" -r 15 -aspect 16:9 -strict -2 ";
                        }
                        
                        $output_path = public_path('/uploads/make_video/'.$user->id."/video1_".$fileNameToStore);
                        
                        $output= shell_exec("ffmpeg -i $outro_video -vf scale=1440:1024 $ratio $output_path 2>&1");
                        dd($output);
                        $video_url= env("APP_URL")."public/uploads/make_video/".$user->id."/video1_".$fileNameToStore;
                        
                        if(file_exists($outro_video)) { 
                              unlink($outro_video); //remove the file
                        }
                    }
                    $msg  = array('status'=>true,'message' => "Outro video upload successfully",'data'=>$video_url);
                    echo json_encode($msg);
                }else{
                    $msg  = array('status'=>false,'message' => "User not found","data"=>[]);
                    echo json_encode($msg);
                }
            }catch (JWTException $e) {
    
                $msg  = array('status'=>false,'message' => "Could Not Create Token.",'data'=>[]);
                echo json_encode($msg);
            }
        }else{
            $msg  = array('status'=>false,'message' => "Please send all parameters",'data'=>[]);
            echo json_encode($msg);
        }
 

    }
    
    /*upload video2*/
    public function mainVideo(Request $request){
        
        ini_set('max_execution_time', '0');
        if($request->token!=''){
            try{
                if($user = JWTAuth::authenticate($request->token)){
                    
                    if(!empty($request->main_video)){
                        
                        $fileNameToStore= time().".mp4";
                        
                        if(!empty($request->ratio)){
                            $ratio=" -r 15 -aspect ".$request->ratio." -strict -2 ";
                            
                        }
                        else{
                            $ratio=" -r 15 -aspect 16:9 -strict -2 ";
                        }
                        
                        $output_path = public_path('/uploads/make_video/'.$user->id."/main_".$fileNameToStore);
                        
                        $output= shell_exec("ffmpeg -i $request->main_video -vf scale=1440:1024 $ratio $output_path 2>&1");
                       
                        $video_url= env("APP_URL")."public/uploads/make_video/".$user->id."/main_".$fileNameToStore;
                        
                        if(file_exists($request->main_video)) { 
                              unlink($request->main_video); //remove the file
                        }
                        
                        
                    }
                    $msg  = array('status'=>true,'message' => "Main video scaled successfully",'data'=>$video_url);
                    echo json_encode($msg);
                }else{
                    $msg  = array('status'=>false,'message' => "User not found","data"=>[]);
                    echo json_encode($msg);
                }
            }catch (JWTException $e) {
    
                $msg  = array('status'=>false,'message' => "Could Not Create Token.",'data'=>[]);
                echo json_encode($msg);
            }
        }else{
            $msg  = array('status'=>false,'message' => "Please send all parameters",'data'=>[]);
            echo json_encode($msg);
        }
 

    }
    
     /*upload video2*/
    public function watermark(Request $request){
        
        ini_set('max_execution_time', '0');
        if($request->token!=''){
            try{
                if($user = JWTAuth::authenticate($request->token)){
                    
                    if ($request->hasFile('watermark')) {
                        $extension = $request->file('watermark')->getClientOriginalExtension();
                        // Filename To store
                        $fileNameToStore = time().'.'.$extension;
                        
                        Image::make($request->watermark)->resize(150, 100)->save(public_path('uploads/watermark/'.$user->id."/".$fileNameToStore));
                        
                        $logopath =env("APP_URL"). 'public/uploads/watermark/'.$user->id."/".$fileNameToStore;
                    
                    }
                    $msg  = array('status'=>true,'message' => "Watermark uploaded successfully",'data'=>$logopath);
                    echo json_encode($msg);
                }else{
                    $msg  = array('status'=>false,'message' => "User not found","data"=>[]);
                    echo json_encode($msg);
                }
            }catch (JWTException $e) {
    
                $msg  = array('status'=>false,'message' => "Could Not Create Token.",'data'=>[]);
                echo json_encode($msg);
            }
        }else{
            $msg  = array('status'=>false,'message' => "Please send all parameters",'data'=>[]);
            echo json_encode($msg);
        }
 

    }
    
    public function finalVideo(Request $request){
        
        ini_set('max_execution_time', '0');
        if($request->token!=''){
            try{
                if($user = JWTAuth::authenticate($request->token)){
                    
                    $destinationtempfilePath=public_path().'/uploads/temp_file/' . $user->id;
                    if( !is_dir( $destinationtempfilePath ) ) mkdir( $destinationtempfilePath, 0755, true );
                    $txtfile = fopen($destinationtempfilePath."/video.txt", "w") or die("Unable to open file!");
                    
                    
                    if(!empty($request->title))
                    {
                       
                      $txt = "file ".$request->title."\n";
                      fwrite($txtfile, $txt);
                        sleep(1);
                    }
                  
                    if(!empty($request->intro_video))
                    {   
                        $txt = "file ".$request->intro_video."\n";
                        fwrite($txtfile, $txt);
                        sleep(1);
                    }
       
                    
                    if(!empty($request->main_video)){
                        $txt = "file ".$request->main_video."\n";
                        fwrite($txtfile, $txt);    
                    }
                    
            
                    if(!empty($request->outro_video)){
                        $txt = "file ".$request->outro_video."\n";
                        fwrite($txtfile, $txt);    
                    }
                    
                    
                    fclose($txtfile);    
                  
                    $destinationVideoPath=public_path()."/uploads/final_video/".$user->id;
                    if( !is_dir( $destinationVideoPath ) ) mkdir( $destinationVideoPath, 0755, true );
                    
                    if(!empty($request->video_format)){
                        $video_url = "/uploads/final_video/".$user->id."/".time().'.'.$request->video_format;
                        $output_path= public_path().$video_url;
                    }else{ 
                        $video_url = "/uploads/final_video/".$user->id."/".time().'.mp4';
                        $output_path= public_path().$video_url;
                    }
                    
                    $video_file_path = public_path().'/uploads/temp_file/' . $user->id.'/video.txt';
                    
                    if(!empty($request->ratio)){
                        $ratio="-vf scale=1440:1024 -r 15 -aspect ".$request->ratio." -strict -2 ";
                       
                    }
                    else{
                        $ratio=" -r 15 -aspect 16:9 -strict -2 ";
                    }
                    $output= shell_exec("ffmpeg -f concat -safe 0 -i $video_file_path $ratio $output_path 2>&1");
                    
                  dd($output);
                    $vurl= env('APP_URL')."public".$video_url;
                    
                     
                    if(!empty($request->video_format)){
                        $videourl = "/uploads/final_video/".$user->id."/".time().'.'.$request->video_format;
                        $outputpath= public_path().$videourl;
                    }else{ 
                        $videourl = "/uploads/final_video/".$user->id."/".time().'.mp4';
                        $outputpath= public_path().$videourl;
                    }
                    
                    if (!empty($request->watermark)){
                       
                        $output= shell_exec("ffmpeg -i $vurl -i $request->watermark -filter_complex 'overlay=x=(main_w-overlay_w-10):y=(main_h-overlay_h)/(main_h-overlay_h)' -acodec:a copy $outputpath 2>&1");//top right
                       
                    }
                   
                   
                     echo "<pre>";print_r($output);die;
                    
                    $result=MyVideo::findorfail($request->video_id);
                    $result->video = env('APP_URL')."public/".$videourl;
                    $result->update();
                    
                    $msg  = array('status'=>true,'message' => "Video created successfully",'data'=>$result);
                    echo json_encode($msg);
                }else{
                    $msg  = array('status'=>false,'message' => "User not found","data"=>[]);
                    echo json_encode($msg);
                }
            }catch (JWTException $e) {
    
                $msg  = array('status'=>false,'message' => "Could Not Create Token.",'data'=>[]);
                echo json_encode($msg);
            }
        }else{
            $msg  = array('status'=>false,'message' => "Please send all parameters",'data'=>[]);
            echo json_encode($msg);
        }
 
    }
    
     public function saveFinalVideo(Request $request){
        
        //  $output_path = "/home/readyvids/public_html/public/uploads/".time().".avi";
        // //echo "ffmpeg -i $request->title -i $request->main_video  -filter_complex '[0:v] [0:a] [1:v] [1:a] concat=n=2:v=1:a=1[outv][outa]' -map '[outv]' -map '[outa]' $output_path 2>&1";die;
        //  $output=shell_exec("ffmpeg -i $request->title -i $request->intro_video -i $request->main_video -i $request->outro_video -filter_complex '[0:v] [0:a] [1:v] [1:a] [2:v] [2:a] [3:v] [3:a] concat=n=4:v=1:a=1[outv][outa]' -map '[outv]' -map '[outa]' $output_path 2>&1");
        //           echo "<pre>";
        //           print_r($output);
        //           die;
        ini_set('max_execution_time', '0');
        if($request->token!=''){
            try{
                if($user = JWTAuth::authenticate($request->token)){
                    
                    $cmd= "ffmpeg";
                    $n=0;
                    $filter=' -filter_complex ';
                    $str='';
                    if(!empty($request->title))
                    {
                       $cmd .= " -i $request->title";
                       $str .= '['.$n.':v]['.$n.':a]';
                       $n++;
                       
                     
                    }
                  
                    if(!empty($request->intro_video))
                    {   
                         $cmd .= " -i $request->intro_video"; 
                          $str .= '['.$n.':v]['.$n.':a]';
                         $n++;
                    }
       
                    
                    if(!empty($request->main_video)){
                        
                         $cmd .= " -i $request->main_video"; 
                          $str .= '['.$n.':v]['.$n.':a]';
                          $n++;
                    }
                    
            
                    if(!empty($request->outro_video)){
                        
                        $cmd .= " -i $request->outro_video";
                         $str .= '['.$n.':v]['.$n.':a]';
                        $n++;
                    }
                    
                       
                       
                    if($n>0){
                        
                        $fileNameToStore= time().".mp4";
                        
                        $output_path = public_path('/uploads/final_video/'.$user->id."/final_".$fileNameToStore);
                       echo $cmd = $cmd.$filter."'".$str." concat=n=".$n.":v=1:a=1[outv][outa]' -c:v libx264 -map '[outv]' -map '[outa]' -r 25 -pix_fmt yuv420p -preset ultrafast -y ".$output_path." 2>&1";
                        
                        $output = shell_exec($cmd);
                        dd($output);
                    }
                    
                    fclose($txtfile);    
                  
                    $destinationVideoPath=public_path()."/uploads/final_video/".$user->id;
                    if( !is_dir( $destinationVideoPath ) ) mkdir( $destinationVideoPath, 0755, true );
                    
                    if(!empty($request->video_format)){
                        $video_url = "/uploads/final_video/".$user->id."/".time().'.'.$request->video_format;
                        $output_path= public_path().$video_url;
                    }else{ 
                        $video_url = "/uploads/final_video/".$user->id."/".time().'.mp4';
                        $output_path= public_path().$video_url;
                    }
                    
                    $video_file_path = public_path().'/uploads/temp_file/' . $user->id.'/video.txt';
                    
                    if(!empty($request->ratio)){
                        $ratio="-vf scale=1440:1024 -r 15 -aspect ".$request->ratio." -strict -2 ";
                       
                    }
                    else{
                        $ratio=" -r 15 -aspect 16:9 -strict -2 ";
                    }
                     
                    $output= shell_exec("ffmpeg -f concat -safe 0 -i $video_file_path $ratio $output_path 2>&1");
                    
                    
                    
                     
                  
                    $vurl= env('APP_URL')."public".$video_url;
                    
                     
                    if(!empty($request->video_format)){
                        $videourl = "/uploads/final_video/".$user->id."/".time().'.'.$request->video_format;
                        $outputpath= public_path().$videourl;
                    }else{ 
                        $videourl = "/uploads/final_video/".$user->id."/".time().'.mp4';
                        $outputpath= public_path().$videourl;
                    }
                    
                    if(!empty($request->watermark)){
                       
                        $output= shell_exec("ffmpeg -i $vurl -i $logopath -filter_complex 'overlay=x=(main_w-overlay_w-10):y=(main_h-overlay_h)/(main_h-overlay_h)' -codec:a copy $outputpath 2>&1");//top right
                       
                    }
                   
                   
                     echo "<pre>";print_r($output);die;
                    
                    $result=MyVideo::findorfail($request->video_id);
                    $result->video = env('APP_URL')."public/".$videourl;
                    $result->update();
                    
                    $msg  = array('status'=>true,'message' => "Video created successfully",'data'=>$result);
                    echo json_encode($msg);
                }else{
                    $msg  = array('status'=>false,'message' => "User not found","data"=>[]);
                    echo json_encode($msg);
                }
            }catch (JWTException $e) {
    
                $msg  = array('status'=>false,'message' => "Could Not Create Token.",'data'=>[]);
                echo json_encode($msg);
            }
        }else{
            $msg  = array('status'=>false,'message' => "Please send all parameters",'data'=>[]);
            echo json_encode($msg);
        }
 
    }
    
}    

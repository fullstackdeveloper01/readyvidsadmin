<?php



namespace App\Http\Controllers;
use Stripe\Exception\CardException;
use Stripe\StripeClient;

use DateTime;
use JWTAuth;
use App\AffliateLink;
use App\AffliateCommission;
use App\User;
use App\Country;
use App\Package;
use App\Subject;
use App\Topic;
use App\QuizTemplate;
use App\QuizVoice;
use App\TalkToAdvisor;
use App\Team;
use App\MyVideo;
use App\MyQuizVideo;
use App\Languages;
use Image;
use App\Video;
use App\VideoContent;
use App\QuizVideoContent;
use App\QuizVideo;
use App\VideoText;
use App\Section;
use App\VideoTextMapping;
use App\Ratio;
use App\QuizRatio;
use App\VideoSize;
use App\Categories;
use App\Countries;
use App\States;
use App\Cities;
use App\TemplateType;
use App\Template;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;

use Tymon\JWTAuth\Exceptions\JWTException;

//use Symfony\Component\HttpFoundation\Response;

use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

use Illuminate\Support\Facades\DB;

use App\Helpers\Helper;

use Dompdf\Dompdf;
use Dompdf\Options;

class ApiController extends Controller

{
    private $stripe;

    protected $user;


    public function __construct()

    {   
        $this->stripe = new StripeClient(env('STRIPE_SECRET_KEY'));
        date_default_timezone_set('Asia/Kolkata');

    }


    /**Registration api */

    public function register(Request $request)

    { 
        
        //Validate data

        $data = $request->only('name', 'email', 'password','confirm_password','login_type','id');

        $validator = Validator::make($data, [

            'name' => 'required|string|min:3|max:50',

            //'email' => 'required|email',
             'email' =>  [
                             'required', 
                            Rule::unique('users')
                                   
                                    ->where('role',$request->role)
                            ],

            'login_type' =>'required|string',


        ]);

        //Send failed response if request is not valid

        if ($validator->fails()) {

           
            $msg  = array('success'=>false,'message' => $validator->messages()->first(),'status'=>200);
             echo json_encode($msg); 

        }
        else{

           
    
            if ($request->login_type=="email") {
    
                $validator = Validator::make($data, [
    
                    'password' => 'required|string|min:8',
    
                ]);
    
                //Send failed response if request is not valid
    
                if ($validator->fails()) {
    
                    //return response()->json(['success'=>false,'message' => $validator->messages()->first()], 200);
                     $msg  = array('success'=>false,'message' => $validator->messages()->first(),'status'=>200);
                        echo json_encode($msg); 
    
                }
  
                $insertData['password'] = bcrypt($request->password);
    
                $insertData['bc_id'] = base64_encode($request->password);
    
                $token_pass=$request->password;
     
            }
            elseif ($request->login_type=="affiliate_email") {
    
                $validator = Validator::make($data, [
    
                    'password' => 'required|string|min:8',
    
                ]);
    
                //Send failed response if request is not valid
    
                if ($validator->fails()) {
    
                    //return response()->json(['success'=>false,'message' => $validator->messages()->first()], 200);
                    $msg  = array('success'=>false,'message' => $validator->messages()->first(),'status'=>200);
                    echo json_encode($msg); 
    
                }
    
                $insertData['password'] = bcrypt($request->password);
                $insertData['phone'] = $request->mobile;
                $insertData['state'] = $request->state;
                $insertData['city'] = $request->city;
                $insertData['bc_id'] = base64_encode($request->password);
    
                $token_pass=$request->password;
    
            }
            elseif ($data['login_type']=="google") {
    
                $validator = Validator::make($data, [
    
                    'id' => 'required',
    
                ]);
    
                if ($validator->fails()) {
    
                    return response()->json(['success'=>false,'message' => $validator->messages()->first()], 200);
    
                }
    
                $insertData['google_id'] = $request->id;
    
                $insertData['password'] = bcrypt('ali786');
    
                $insertData['bc_id'] = base64_encode('ali786');
    
                $token_pass='ali786';
    
    
    
            }
            else{
                
            }
    
           if($request->code!=''){
               $insertData['code']=$request->code;
           }
           if($request->customize_name!=''){
               $insertData['customize_name']=$request->customize_name;
           }
    
            $insertData['name']=$request->name;
    
            $insertData['email']=$request->email;
    
            $insertData['role']=$request->role;
    
            $insertData['login_type']=$request->login_type;
    
            //Request is valid, create new user
            
           
            $insertData['package_id']=Package::where('package_price','=','0')->first()->id;
            
            $insertData['payment_status']=false;
           
            

        //     $login['password'] = $token_pass;
    
    	   // $login['email'] = $request->email;
    
    	   // $token = JWTAuth::attempt($login);
    
        //      print_r($token);die;
    
            //User created, return success response
    
            try {
    
                //if ($token = JWTAuth::attempt($login)) {
    
                    //$user->token=$token;
                    $user = User::create($insertData);
    
                    if($request->code!=''){
                        $insertDataCommission['code']=$request->code; 
                        $insertDataCommission['customize_name']=$request->customize_name;
                        $insertDataCommission['register_user_id']=$user->id;
                        $this->registerBy($insertDataCommission);
                   }
                    $res['data']=$user;
                    $res['success']=true;
                    $res['message']='User Created Successfully';
                    echo json_encode($res);
    
                  
    
                //}
    
            } catch (JWTException $e) {
    
               $msg  = array('success'=>false,'message' => 'Could Not Create Token','status'=>500);
                echo json_encode($msg); 
    
            }
        }
    }
    public function checkEmail(Request $request){
        $data = $request->only('email');

 
        //valid credential

        $validator = Validator::make($data, [

            'email' => 'required|email|unique:users',


        ]);


        //Send failed response if request is not valid

       

        //Crean token

        $user = User::where(['email'=>$request->email,'role'=>$request->role])->first(); 
        dd($user);
        if (!$user) {
             $msg  = array('status'=>true,'message' => "Go further");
             echo json_encode($msg); 

        }else { 
            $msg  = array('status'=>true,'message' => "Email is already registered.");
            echo json_encode($msg); 
        }
    }



    /**Login Api */

    public function login(Request $request)

    {
        
        $data = $request->only('email', 'password','login_type');

 
        //CHECK LOGIN TYPE
       if ($data['login_type']=="google") {
            
            //valid credential

            $validator = Validator::make($data, [

                'id' => 'required',

            ]);
        }else{

            //valid credential

            $validator = Validator::make($data, [

                'email' => 'required|email',

                'login_type' => 'required',
                
                'password'=>'required'

            ]);
        }



        //Send failed response if request is not valid

   
        if($request->login_type!='' && $request->email!=''){
            $login['email'] = $request->email;
            // login_type: app,google,apple
    
            if ($data['login_type']=="email") {
    
               if($request->password!=''){
                    $login['password'] = $request->password;
                    $login['role'] = 2;
               }else{
                    $msg  = array('status'=>false,'message' => "Please send all parameters",'data'=>[]);
                    echo json_encode($msg);
               }
    
            }
            elseif ($data['login_type']=="affiliate_email") {
    
               if($request->password!=''){
                    $login['password'] = $request->password;
                    $login['role'] = 4;
               }else{
                    $msg  = array('status'=>false,'message' => "Please send all parameters",'data'=>[]);
                    echo json_encode($msg);
               }
    
            }
            elseif ($data['login_type']=="google"){
                
                $response = User::where(['google_id'=>$data['id']])->first();
                if ($response!="") {
                    $login['password'] = base64_decode($response->bc_id);
                    $login['email'] = $response->email;
                     $login['role'] = 2;
                }else{
                     $msg  = array('status'=>false,'message' => "Login credentials are invalid.",'data'=>[]);
                    echo json_encode($msg);
                }
    
            }
            else{}
            
             try {


            if (!$token = JWTAuth::attempt($login)) {
                
                $msg  = array('status'=>false,'message' => "Login credentials are invalid.",'data'=>[]);
                echo json_encode($msg);
                
            }
            else{
                $user = JWTAuth::user();
                if($request->role==$user->role){
                    if($user->role=='4'  || $user->role=='5'){
                        if($user->email_verified_at==''){
                            $msg  = array('status'=>false,'message' => "Please wait for admin approval.",'data'=>[]);
                            echo json_encode($msg);
                        }
                        else{
                            
                            //Token created, return with success response and jwt token
                            $msg  = array('status'=>true,'message' => "Login credentials are valid.",'data'=>$token);
                            echo json_encode($msg);
                        }
                    }
                    else{
                                        
                        //Token created, return with success response and jwt token
                        $msg  = array('status'=>true,'message' => "Login credentials are valid.",'data'=>$token);
                        echo json_encode($msg);
                    }
                    // $email=$user->email;
                    // $password['user_name']=ucfirst($user->name);
                    // $html= view('mail.welcome',$password);
    
                
                    // Helper::send_email($email,'Welcome',$html);
                }
                else{
                    $msg  = array('status'=>false,'message' => "Login credentials are invalid.",'data'=>[]);
                    echo json_encode($msg);
                }
            }        
            

        } catch (JWTException $e) {

            $msg  = array('status'=>false,'message' => "Could not create token.",'data'=>[]);
            echo json_encode($msg);

          

        }
        
        }
        //Crean token
        
        else{
            $msg  = array('status'=>false,'message' => "Please send all parameters",'data'=>[]);
            echo json_encode($msg);
        }
       

       
    }



    /**forgot password Api */

    public function forgotpassword(Request $request)

    {

        // $data = $request->only('email');

 

        // //valid credential

        // $validator = Validator::make($data, [

        //     'email' => 'required|email',

        // ]);



        // //Send failed response if request is not valid

        // if ($validator->fails()) {

        //     return response()->json(['success'=>false,'message' => $validator->messages()->first()], 200);

        // }
        
        if($request->email!=''){
            //Crean token

            $user = User::where(['email'=>$request->email])->first();
    
            if ($user) {
    
                $password['password']= rand(1000,9999);
    
                // $user->password= bcrypt($password['password']);
    
                // $user->bc_id= base64_encode($password['password']);
                $user->otp= $password['password'];
    
                $user->otp_generation_time= time();
    
                $user->save();
    
                
                $email=$user->email;
                $password['email']=$email;
                $password['user_name']=ucfirst($user->name);
                $html= view('mail.forgot_password',$password);
    
             
                Helper::send_email($email,'Forgot Password',$html);
                
                $msg  = array('status'=>true,'message' => "OTP Send To Register Email Id",'data'=>[]);
                echo json_encode($msg);
                // return response()->json([
    
                //     'success' => true,
    
                //     'message' => "OTP Send To Register Email Id",
    
                // ]);
    
            }else {
    
                // return response()->json([
    
                //     'success' => false,
    
                //     'message' =>"Please Enter a Valid Mail I'd",
    
                // ], 500);
                 $msg  = array('status'=>false,'message' => "Please Enter a Valid Mail I'd",'data'=>[]);
                echo json_encode($msg);
    
            }
        }else{
            $msg  = array('status'=>false,'message' => "Please send all parameters",'data'=>[]);
            echo json_encode($msg);
        }

    }



    /**Logout Api */

    public function logout(Request $request)

    {

        //valid credential

        $validator = Validator::make($request->only('token'), [

            'token' => 'required'

        ]);



        //Send failed response if request is not valid

        if ($validator->fails()) {

            return response()->json(['success'=>false,'message' => $validator->messages()->first()], 200);

        }



        //Request is validated, do logout        

        try {

            // if ($user = JWTAuth::authenticate($request->token)) {

            //     print_r($user);die;

                JWTAuth::invalidate($request->token);

 

                return response()->json([

                    'success' => true,

                    'message' => 'User has been logged out',

                    'status'=>200

                ]);

            // }

        } catch (JWTException $exception) {

            return response()->json([

                'success' => false,

                'message' => 'Sorry, user cannot be logged out'

            ], Response::HTTP_INTERNAL_SERVER_ERROR);

        }

    }


     /**User Details */

     public function userdetails(Request $request)
    {
        
        if($request->token!=''){
            if($user = JWTAuth::authenticate($request->token)){
                if($user->role==4){
                    $user->cities= Cities::where('state_id','=',$user->state)->get();
                }
              $msg  = array('status'=>true,'message' => "Data Found successfully",'data'=>$user);
              echo json_encode($msg);
            }
            else{
                 $msg  = array('status'=>true,'message' => "Please send valid token",'data'=>[]);
                echo json_encode($msg);
            }
        }else{
           
              $msg  = array('status'=>false,'message' => "Please send token",'data'=>[]);
              echo json_encode($msg);
        }
 
   }
    
    /**save profile */

    public function profileupdate(Request $request)

    {

        if($request->token!=''){
            try{

                if($user= JWTAuth::authenticate($request->token)){

                    if (!empty($request->name)) {
    
                        $user->name=$request->name;
                    }

                    if (!empty($request->phone)) {
    
                        $user->phone=$request->phone;
    
                    }

                    if (!empty($request->old_password) && !empty($request->new_password) ) {

                   

                        if (Hash::check($request->old_password, $user->password)) {
    
                            $user->password = bcrypt($request->new_password);
                            $user->bc_id = base64_encode($request->new_password);
                            
                            $data['user_name']=ucfirst($user->name);
                            $html= view('mail.change_password',$data);
    
                            $email=$user->email;
                    
                            Helper::send_email($email,'Change Password',$html);
                            if($user->role==4){
                                $user->state= $request->state;
                                $user->city= $request->city;
                            }
                            $user->update();
            
                            $user->path=url('/');
                            
                            $msg  = array('status'=>true,'message' => "Profile Detail Save Successfully",'data'=>$user);
                            echo json_encode($msg);
                        }else{
    
                            //Send failed response if request is not valid
                            
                            $msg  = array('status'=>false,'message' => "Old Password Mis-Match",'data'=>[]);
                            echo json_encode($msg);
    
                            //return response()->json(['success' => false,'message' => 'Old Password Mis-Match'], 200);
                        }
                    }else{
                        if($user->role==4){
                            $user->state= $request->state;
                            $user->city= $request->city;
                        }
                        $user->update();
        
                        $user->path=url('/');
                        
                        $msg  = array('status'=>true,'message' => "Profile Detail Save Successfully",'data'=>$user);
                        echo json_encode($msg);
                    }
                    
                    
                
            }

            }catch (JWTException $exception) {
                
                $msg  = array('status'=>true,'message' => "Token Is Expired",'data'=>[]);
                echo json_encode($msg);
               
            }
        }else{
           
              $msg  = array('status'=>false,'message' => "Please send token",'data'=>[]);
              echo json_encode($msg);
        }
        

    }

     /**forgot password Api */

     public function verifyOtp(Request $request)

     {
 
        if($request->otp!='' && $request->email!=''){    
             
             $user = User::where(['email'=>$request->email,'otp'=>$request->otp])->first();

             if ($user) {
                $diff= time()-$user->otp_generation_time;
                
                if($diff<120){
                    $user->password= bcrypt($request->otp);
    
                    $user->bc_id= base64_encode($request->otp);
    
                    $user->otp='';
     
                    $user->otp_generation_time= '';
       
                    $user->otp_verification= 'true';
    
                    $user->save();
                    
                    $msg  = array('status'=>true,'message' => "OTP verify successfully",'data'=>[]);
                    
                    echo json_encode($msg);
                  
        
    
                }else{
                    
                     $msg  = array('status'=>false,'message' => "OTP Expired.",'data'=>[]);
                    
                    echo json_encode($msg);
        
    
                }
                           
             }else {
                   $msg  = array('status'=>false,'message' => "Please enter a valid otp",'data'=>[]);
                    
                    echo json_encode($msg);
                  
     
                //  return response()->json([
     
                //      'success' => false,
     
                //      'message' =>"Please Enter a Valid Mail I'd and otp",
     
                //  ], 500);
     
             }
         }
         else{
            $msg  = array('status'=>false,'message' => "Please send all parameters",'data'=>[]);
            echo json_encode($msg);
         }
 
     }
 
       /**forgot password Api */

     public function resetPassword(Request $request)

     {
         
 
         //Crean token
        if($request->new_password!='' && $request->email!='' && $request->confirmPassword!=''){
            
            if($request->new_password==$request->confirmPassword){
                 
                $user = User::where(['email'=>$request->email])->first();
                
                $user->password= bcrypt($request->new_password);
    
                $user->bc_id= base64_encode($request->new_password);
                
                $user->update();
                
                 $msg  = array('status'=>true,'message' => "Reset Password successfully.",'data'=>[]);
                        
                echo json_encode($msg);
            }
            else{
                 $msg  = array('status'=>false,'message' => "Password does not match.",'data'=>[]);
                echo json_encode($msg);
            }
              
    
        }
        else{
             $msg  = array('status'=>false,'message' => "Please send all parameters",'data'=>[]);
            echo json_encode($msg);
        }
         
 
       
 
     }


    // upload user image

    public function imageUpload(Request $request)

    {   

        // $data = $request->only('token','image');

        // $validator = Validator::make($data, [

                       
        //     'image'=>'required|mimes:jpeg,png,jpg|max:5120|min:1024',

        //     'token'=>'required'

        // ]);



        // //Send failed response if request is not valid

        // if ($validator->fails()) {

        //     return response()->json(['success'=>false,'message' => $validator->messages()->first()], 200);

        // }

         // print_r($request->all());die;
        if(!empty($request->token) && !empty($request->image)){
            try {
    
                if($user = JWTAuth::authenticate($request->token)){
    
                    // $file = $request->image;                                        
    
                    // $name = time().'.'.$file->getClientOriginalExtension();
    
                    // $file->move(public_path('uploads/profile_pic'),$name);
                    
                    $image = $request->image;                                        

                     $base64_str = substr($image, strpos($image, ",")+1);
    
                    //decode base64 string
                    $image = base64_decode($base64_str);
                    $png_url = time().".png";
                    $path = public_path('uploads/profile_pic/' . $png_url);
    
                    // $ext = explode(';base64',$image);
                    // $ext = explode(',',$ext[1]);
    
                    //$name = time().'.'.$image->getClientOriginalExtension();
                    Image::make(file_get_contents($request->image))->save($path);
                    //$image->move(public_path('uploads/profile_pic'),$png_url);
    
                   // $user->image=public_path()."uploads/profile_pic/".$png_url;
                    $user->image=env("APP_URL")."public/uploads/profile_pic/".$png_url;
                    
    
                 //   $user->image="uploads/profile_pic/".$name;
    
                    $user->update();
    
                   $msg  = array('status'=>true,'message' => "Profile Pic Upload Sccessfully",'data'=>$user);
                    echo json_encode($msg);      
    
                    // return response()->json([
    
                    //     'data' =>$user,
    
                    //     'success' => true,
    
                    //     'message' => 'Profile Pic Upload Sccessfully',
    
                    //     'status'=>200
    
                    // ]);
    
                }
    
                
    
            } catch (JWTException $exception) {
                $msg  = array('status'=>false,'message' => "User Not Found",'data'=>[]);
                echo json_encode($msg);
                // return response()->json([
    
                //     'success' => false,
    
                //     'message' => 'User Not Found'
    
                // ]);
    
            }
        }
        else{
         $msg  = array('status'=>false,'message' => "Please send all parameter",'data'=>[]);
              echo json_encode($msg);
        }
    }


    
    

    public function addTeam(Request $request)

    { 
        
        //Validate data

        // $data = $request->only('first_name','last_name', 'email','token');

        // $validator = Validator::make($data, [

        //     'first_name' => 'required|string|min:3|max:30',

        //     'last_name' => 'required|string|min:3|max:30',

        //     'email' => 'required|email|unique:users|unique:team',

        //     'token' => 'required',


        // ],[
        //     'email.unique' => "This Mail I'd Is Already Register",
        // ]);

        // //Send failed response if request is not valid

        // if ($validator->fails()) {

        //   // return response()->json(['success'=>false,'message' => $validator->messages()->first()], 200);
        //     $msg  = array('status'=>false,'message' =>$validator->messages()->first(),'data'=>[]);
        //     echo json_encode($msg);

        // }
        try{
            if(!empty($request->token)){
                if($user = JWTAuth::authenticate($request->token)){
            

                $insertData['name']=$request->first_name.' '.$request->last_name;
    
                $insertData['email']=$request->email;
    
                $insertData['role']=3;
    
                //Request is valid, create new user
    
                User::create($insertData);
            
                $insertData['first_name']=$request->first_name;
    
                $insertData['last_name']=$request->last_name;
    
                $insertData['email']=$request->email;
    
                $insertData['role']=3;
                
                $insertData['user_id']=$user->id;
    
                //Request is valid, create new user
    
                $team = Team::create($insertData);
              
                $email=$team->email;
                $password['email']=base64_encode($email);
                $password['user_name']=ucfirst($team->first_name);
                $html= view('mail.set_password',$password);
    
             
                Helper::send_email($email,'Set Password',$html);
    
                $msg  = array('status'=>true,'message' => "Team Created Successfully",'data'=>$team);
                echo json_encode($msg);
    
            }
            }
            else{
                 $msg  = array('status'=>false,'message' => "Please send all parameters",'data'=>[]);
                echo json_encode($msg);
            }
        } catch (JWTException $e) {

            $msg  = array('status'=>false,'message' => "Could Not Create Token.",'data'=>[]);
            echo json_encode($msg);
        }

    }

    public function primaryLanguageList(Request $request){
        //dd($request);
        $this->validate($request, [
 
            'token' => 'required'

        ]);

 
        try{
            if($user = JWTAuth::authenticate($request->token)){
                $data= Languages::where(['parent_id'=>0])->get();
                // foreach($data as $value){
                //     $url=env("APP_URL")."public/".$value->icon;
                //     $value->name ="<img src='".$url."'>". $value->name." ".$value->description;
                // }
                $msg  = array('status'=>true,'message' => "Primary language get successfully",'data'=>$data);
                echo json_encode($msg);
            }else{
                $msg  = array('status'=>false,'message' => "User not found","data"=>[]);
                echo json_encode($msg);
            }
        }catch (JWTException $e) {

            $msg  = array('status'=>false,'message' => "Could Not Create Token.",'data'=>[]);
            echo json_encode($msg);
        }
    
    }
    

    public function secondaryLanguageList(Request $request){
        
        if($request->token!='' && $request->primary_language!=""){
            try{
                if($user = JWTAuth::authenticate($request->token)){
                    $data= Languages::where(['parent_id'=>$request->primary_language])->get();
                    $msg  = array('status'=>true,'message' => "Secondary language get successfully",'data'=>$data);
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
 
        
       
        
             
        // return response()->json([
        //     'data' =>$data,
        //     'status' => true,
        //     'errMsg' => ''
        // ]);
    }
    
    public function voiceList(Request $request){
        
        if($request->token!='' && $request->primary_language!=""){
            try{
                if($user = JWTAuth::authenticate($request->token)){
                    $data= Languages::where(['id'=>$request->primary_language])->first();
                    $msg  = array('status'=>true,'message' => "Voice list get successfully",'data'=>$data);
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


    public function videoTypeList(Request $request){
        
        if($request->token!=''){
            try{
                if($user = JWTAuth::authenticate($request->token)){
                    $data= Section::get();
                    $msg  = array('status'=>true,'message' => "Video type listing get successfully",'data'=>$data);
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

    public function ratioList(Request $request){
        
        if($request->token!=''){
            try{
                if($user = JWTAuth::authenticate($request->token)){
                    $data= Ratio::where(['status'=>1])->get();
                    $msg  = array('status'=>true,'message' => "Ratio listing get successfully",'data'=>$data);
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

    public function categoryList(Request $request){
        
        if($request->token!=''){
            try{
                if($user = JWTAuth::authenticate($request->token)){
                    $data= Categories::where(['parent_id'=>'0'])->skip($request->offset)->take($request->limit)->get();
                    // if($request->offset==0){
                    //     $totalrecord= Categories::where(['parent_id'=>'0'])->count();
                    // }else{
                    //     $totalrecord='';
                    // }
                    $msg  = array('status'=>true,'message' => "Category listing get successfully",'data'=>$data);
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

    public function subcategoryList(Request $request){
        
        if($request->token!='' && $request->category!=''){
            try{
                if($user = JWTAuth::authenticate($request->token)){
                    $data= Categories::where(['parent_id'=>$request->category])->skip($request->offset)->take($request->limit)->get();
                    $msg  = array('status'=>true,'message' => "Sub Category listing get successfully",'data'=>$data);
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
    
    public function getTemplate(Request $request){
        
        if($request->token!=''){
            try{
                if($user = JWTAuth::authenticate($request->token)){
                   // $data= Template::select('templates.*','templates_type.type as template_type')->join('templates_type','templates.name','=','templates_type.id')->where(['templates.status'=>1,'ratio'=>$request->ratio])->groupBy('templates.type')->groupBy('templates.name')->get();
                    $data= Template::select('templates.*','templates_type.type as template_type')->join('templates_type','templates.name','=','templates_type.id')->where(['templates.status'=>1,'ratio'=>$request->ratio])->whereRaw("find_in_set('$request->subcategory',subcategory)")->groupBy('templates.type')->groupBy('templates.pattern')->skip($request->offset)->take($request->limit)->get();
                    $msg  = array('status'=>true,'message' => "Template listing get successfully",'data'=>$data);
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


    public function templateTypeList(Request $request){
        
        if($request->token!=''){
            try{
                if($user = JWTAuth::authenticate($request->token)){
                    $data= TemplateType::where(['status'=>1])->get();
                    $msg  = array('status'=>true,'message' => "Template listing get successfully",'data'=>$data);
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
    public function templateList(Request $request){
        
        if($request->token!=''){
            try{
                if($user = JWTAuth::authenticate($request->token)){
                    //$data= Template::where(['status'=>1,'name'=>$request->template_id,'type'=>$request->template_type]);
                    $data= Template::where(['status'=>1,'pattern'=>$request->pattern])->whereRaw("find_in_set('$request->subcategory',subcategory)");
                    
                    if(!empty($request->ratio)){
                        $data= $data->where(['ratio'=>$request->ratio]);
                    }
                    $data= $data->skip($request->offset)->take($request->limit)->get();

                    $msg  = array('status'=>true,'message' => "Template listing get successfully",'data'=>$data);
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
    
    // public function templateList(Request $request){
        
    //     if($request->token!=''){
    //         try{
    //             if($user = JWTAuth::authenticate($request->token)){
    //                 $data= Template::where(['status'=>1,'name'=>$request->template_type]);
    //                 if(!empty($request->image_type)){
    //                     $data= $data->where(['type'=>$request->image_type]);
    //                 }
    //                 if(!empty($request->ratio)){
    //                     $data= $data->where(['ratio'=>$request->ratio]);
    //                 }
    //                 $data= $data->get();
                   
    //                 $msg  = array('status'=>true,'message' => "Template listing get successfully",'data'=>$data);
    //                 echo json_encode($msg);
    //             }else{
    //                 $msg  = array('status'=>false,'message' => "User not found","data"=>[]);
    //                 echo json_encode($msg);
    //             }
    //         }catch (JWTException $e) {
    
    //             $msg  = array('status'=>false,'message' => "Could Not Create Token.",'data'=>[]);
    //             echo json_encode($msg);
    //         }
    //     }else{
    //         $msg  = array('status'=>false,'message' => "Please send all parameters",'data'=>[]);
    //         echo json_encode($msg);
    //     }
 
    // }
    
    
    public function makeVideo(Request $request){
        ini_set('max_execution_time', '0');
        if($request->token!=''){
            try{
                if($user = JWTAuth::authenticate($request->token)){
                    
                    /*check which content already used in video*/
                    $content = VideoContent::select('video_id')->where('user_id','=',$user->id)->distinct('video_id')->get();
                  
                    $totalcontent = count($content);
                    
                    $template = Template::findorfail($request->template_id);
                    
                    $data= Video::select('video.*','secondary_language.name as secondary_language_name','primary_language.name as primary_language_name','section.name as section_name')
                                ->join('languages as primary_language','video.primary_language','=','primary_language.id')
                                ->join('languages as secondary_language','video.secondary_language','=','secondary_language.id')
                                ->join('section','video.section','=','section.id')
                                ->where(['video.status'=>1]);//->whereNotIn('id',$content);
                    if(!empty($request->videotype_id)){
                        $data= $data->where(['video.section'=>$request->videotype_id]);
                    }
                    if(!empty($template->name)){
                        $data= $data->where(['video.template_type'=>$template->name]);
                    }
                    if(!empty($request->primary_language)){
                        $data= $data->where(['video.primary_language'=>$request->primary_language]);
                    }
                    if(!empty($request->secondary_language)){
                        $data= $data->where(['video.secondary_language'=>$request->secondary_language]);
                    }
                    if(!empty($request->category)){
                        $data= $data->whereRaw("find_in_set('$request->category',category)");//->where(['video.category'=>$request->category]);
                    }
                    if(!empty($request->subcategory)){
                        $data= $data->whereRaw("find_in_set('$request->subcategory',subcategory)");//->where(['video.subcategory'=>$request->subcategory]);
                    }
                    $data=$data->inRandomOrder();
                   
                    if(!empty($request->video_size)){
                        $data= $data->limit($request->video_size);
                    }
                   
                    if($data->count()>=$request->video_size){
                        if(($data->count()-$totalcontent)>$request->video_size || ($data->count()-$totalcontent)==$request->video_size){
                           $data= $data->whereNotIn('video.id',$content);
                        }
                        $data= $data->get();
                       
                       
                       $destinationtempfilePath=public_path().'/uploads/temp_file/' . $user->id;
                        if( !is_dir( $destinationtempfilePath ) ) mkdir( $destinationtempfilePath, 0755, true );
                        $txtfile = fopen($destinationtempfilePath."/input.txt", "w") or die("Unable to open file!");
                        $soundfile = fopen($destinationtempfilePath."/input1.txt", "w") or die("Unable to open file!");
                        
                        $audio_column=$request->voice;
                        
                        foreach($data as $video){
                            $video->id;
                            $videocontent['user_id']=$user->id;
                            $videocontent['video_id']=$video->id;
                            VideoContent::create($videocontent);
                            $video_text = VideoTextMapping::join('video_text','video_text.id','=','video_text_mapping.text_id')->where('video_text_mapping.video_id','=',$video->id)->get();
                            $video_html = $template->template_html_string;
    
                            $template_type= explode(' ',$video->templatetype);
                            $lineno = $template_type[0];
                            foreach($video_text as $key=>$text){
                                $counter= $key+1;
                               
                                $searchtext = '{text'.$counter.'}';
                                if (strpos($video_html,$searchtext) !== false) {
                                   $video_html= str_replace($searchtext,$text['text'],$video_html);
                                }
                            }
                           $image_size = $template->template_image_size;
                          
                             $thumbnail_400_320 = 'uploads/video/thumbnail_image/'.time().".jpg";
                             Image::make(public_path($video->image))->resize(420, 420)->save(public_path($thumbnail_400_320));
               
                            if (strpos($video_html,"{img}") !== false) {
                                if(!empty($video->$image_size) && $video->$image_size!='0'){
                                   
                                        $path = config('app.asset_url').$video->$image_size;
                                 
                                    
                                     
                                 }else{
                                    $path = config('app.asset_url').'/'.$thumbnail_400_320;
                                   
                                }
                                $video_html= str_replace('{img}',$path,$video_html);
                             }
                             
                            if (strpos($video_html,"{openbody}") !== false) {
                               $video_html= str_replace('{openbody}',"<body style='",$video_html);
                            }
                            if (strpos($video_html,"{closebody}") !== false) {
                               $video_html= str_replace('{closebody}',"'>",$video_html);
                            }
                            if (strpos($video_html,"{endbody}") !== false) {
                               $video_html= str_replace('{endbody}',"</body>",$video_html);
                            }
                            
                         
                            
                            switch ($video->secondary_language_name) {
                                case 'Hindi':
                                    $font_family2="'Hind', serif;";
                                    break;
                                case 'Bengali':
                                    $font_family2="'Noto Sans Bengali', sans-serif;";
                                    break;
                                case 'Tamil':
                                    $font_family2="'Noto Sans Tamil', sans-serif;";
                                    break;
                                case 'German':
                                    $font_family2="'Noto Sans', sans-serif;";
                                    break;
                                case 'Telugu':
                                    $font_family2="'Noto Sans Telugu', sans-serif;";
                                    break;
                                case 'Urdu':
                                      $font_family2="'Noto Nastaliq Urdu', serif;";
                                    break;
                                
                               default:
                                    $font_family2=$template->fonts.";";
                            }
                            
                            switch ($video->primary_language_name) {
                                case 'Hindi':
                                    $font_family1="'Hind', serif;";
                                    break;
                                case 'Bengali':
                                    $font_family1="'Noto Sans Bengali', sans-serif;";
                                    break;
                                case 'Tamil':
                                    $font_family1="'Noto Sans Tamil', sans-serif;";
                                    break;
                                case 'German':
                                    $font_family1="'Noto Sans', sans-serif;";
                                    break;
                                case 'Telugu':
                                    $font_family1="'Noto Sans Telugu', sans-serif;";
                                    break;
                                case 'Urdu':
                                    $font_family1="'Noto Nastaliq Urdu', serif;";
                                    break;
                                
                               default:
                                    $font_family1=$template->fonts.";";
                            }
                            
                            if (strpos($video_html,"{font_family1}") !== false) {
                               $video_html= str_replace('{font_family1}',$font_family1,$video_html);
                            }
                            
                            if (strpos($video_html,"{font_family2}") !== false) {
                               $video_html= str_replace('{font_family2}',$font_family2,$video_html);
                            }

                          
                             $video->video_html=$video_html;
    
                             $options = new Options();
                             $options->setIsRemoteEnabled(true);
                             $dompdf = new Dompdf($options);
                             $dompdf->loadHtml( $video_html); 
                              
                            // (Optional) Setup the paper size and orientation
                            if(!empty($request->ratio_name) && $request->ratio_name=="16:9"){
                                $customPaper = array(0,0,1440,1024);
                            }
                            if(!empty($request->ratio_name) && $request->ratio_name=="9:16"){
                                   $customPaper = array(0,0,600,800);
                            }
                            
                         
                            $dompdf->setPaper($customPaper);
                            $dompdf->curlAllowUnsafeSslRequests = true;
                            
                
                            
                            // Render the HTML as PDF
                            $dompdf->render();
                          
                            
                            $pdfpath = "uploads/temp_pdf/".$user->id;
                            $pdf_url = $pdfpath."/".time().'.pdf';
                            $pdf_path=public_path()."/".$pdf_url;
                            $destinationpdfPath = public_path($pdfpath);   
                            if( !is_dir( $destinationpdfPath ) ) mkdir( $destinationpdfPath, 0755, true );
                            
                            file_put_contents($pdf_path, $dompdf->output());
                             
                            $imagick = new \Imagick();
                            
                            // $imagick->setResolution(700,600);
                            // $imagick->setResolution(72,72);
                          
                            
                            $imagepath = "uploads/temp_image/".$user->id;
                            $image_url = $imagepath."/".time().'.jpg';
                            $image_path=public_path()."/".$image_url;
                             $destinationPath = public_path($imagepath);   
                            if( !is_dir( $destinationPath ) ) mkdir( $destinationPath, 0755, true );
                            
                            
                          
                            if(!empty($request->ratio_name) && $request->ratio_name=="16:9"){
                                $imagick->readImage($pdf_path);
                                $imagick->writeImages($image_path, true);
                            }
                            if(!empty($request->ratio_name) && $request->ratio_name=="9:16"){
                                
                                shell_exec("convert -geometry 1600x1600 -density 200x200 -quality 100 -resize 600x $pdf_path $image_path"); 
                                
                            }
                        
                            $audio_path = public_path().'/'.$video->$audio_column;
                           
                            $duration = shell_exec("ffprobe -i $audio_path -show_entries format=duration");
                            $duration = str_replace("\n", "", $duration);
                           
                           
                           $duration = str_replace("[FORMAT]", "", $duration);
                           $duration = str_replace("[/FORMAT]", "", $duration);
                           $duration = str_replace("duration=", "", $duration);
                            
                          
                            
                            if(file_exists($image_path)) { 
                                $txt = "file ".$image_path."\n";
                            }else{
                                 $txt = "file ".public_path('uploads/design.jpg')." \n";
                            }
                            fwrite($txtfile, $txt);
                            $txt = "duration ".$duration." \n";
                            fwrite($txtfile, $txt);
    
                          
                            
                            $txt = "file ".public_path()."/".$video->$audio_column."\n";
                            fwrite($soundfile, $txt);
                            $txt = "outpoint ".$duration." \n";
                            fwrite($soundfile, $txt);
                            
                            sleep(2);
                        }
                       
                        fclose($txtfile);
    
                        fclose($soundfile);
                       
                        
                        
                         $file_path = public_path().'/uploads/temp_file/' . $user->id.'/input.txt';
                        $file_path1 = public_path().'/uploads/temp_file/' . $user->id.'/input1.txt';
                        
                         $destinationVideoPath=public_path()."/uploads/make_video/".$user->id;
                        if( !is_dir( $destinationVideoPath ) ) mkdir( $destinationVideoPath, 0755, true );
                        
                        if(!empty($request->video_format)){
                            $video_url = "/uploads/make_video/".$user->id."/".time().'.'.$request->video_format;
                            $output_path= public_path().$video_url;
                           
                            $watermark_url = "/uploads/make_video/".$user->id."/watermark_".time().'.'.$request->video_format;
                            $watermark_output_path =public_path().$watermark_url;
                        }else{ 
                            $video_url = "/uploads/make_video/".$user->id."/".time().'.mp4';
                            $output_path= public_path().$video_url;
                          
                            $watermark_url = "/uploads/make_video/".$user->id."/watermark".time().'.mp4';
                            $watermark_output_path =public_path().$watermark_url;
                        }
    
                        if(!empty($request->ratio_name)){
                            $ratio=" -r 15 -aspect ".$request->ratio_name." -strict -2";
                            //-r 15 -vf scale=1080:-1 -aspect 1:1 -strict -2
                        }
                        
                        
                      
                        if(!empty($request->ratio_name) && $request->ratio_name=="16:9"){
                            $output= shell_exec("ffmpeg -f concat -safe 0 -i $file_path -f concat -safe 0 -i $file_path1 -r 15 -c:v libx264 -pix_fmt yuv420p $ratio $output_path 2>&1");
                        }
                         if(!empty($request->ratio_name) && $request->ratio_name=="9:16"){
                            //output= shell_exec("ffmpeg -f concat -safe 0 -i $file_path -f concat -safe 0 -i $file_path1 -r 15 -c:v libx264 -pix_fmt yuv420p $output_path 2>&1");
                             $output= shell_exec("ffmpeg -f concat -safe 0 -i $file_path -f concat -safe 0 -i $file_path1 -r 15 -c:v libx264 $output_path 2>&1");
                        }
                        
                        sleep(2);
                        
                        $without_watermark = env('APP_URL')."/public/".$video_url;
                       
                        if(!empty($user->package_id) && $user->payment_status=='0'){
                            if(!empty($request->ratio_name) && $request->ratio_name=="16:9"){
                                
                                $watermark = 'https://admin.readyvids.com/public/WideScreen.png';
                                 
                                //$watremarkoutput= shell_exec("ffmpeg -i $without_watermark -i $watermark -filter_complex 'overlay=x=(main_w-overlay_w-200):y=(main_h-overlay_h-700)' -codec:a copy $watermark_output_path 2>&1");//top right
                                $watremarkoutput= shell_exec("ffmpeg -i $without_watermark -i $watermark -filter_complex 'overlay=x=0:y=(main_h-overlay_h-300)' -codec:a copy $watermark_output_path 2>&1");//top right
                            }
                            if(!empty($request->ratio_name) && $request->ratio_name=="9:16"){
                                
                                $watermark = 'https://admin.readyvids.com/public/VerticalScreen.png';
                              
                                $watremarkoutput= shell_exec("ffmpeg -i $without_watermark -i $watermark -filter_complex 'overlay=x=0:y=(main_h-overlay_h-300)' -codec:a copy $watermark_output_path 2>&1");//top right
                            }
                            $myvideo['video']= env('APP_URL')."/public/".$watermark_url;
                            $myvideo['with_watermark_video']= env('APP_URL')."/public/".$watermark_url;
                        }
                        else{
                            $myvideo['video']= env('APP_URL')."/public/".$video_url;
                            $myvideo['without_watermark_video']= env('APP_URL')."/public/".$video_url;
                        }
                      
                        // $watremarkoutput= shell_exec("ffmpeg -i $without_watermark -i $watermark -filter_complex 'overlay=x=(main_w-overlay_w-200):y=(main_h-overlay_h-700)' -codec:a copy $watermark_output_path 2>&1");//top right
                       
                       
                      
                        
                        $myvideo['user_id']= $user->id;
                       
                        
                      
                        $myvideo['ratio']= $request->ratio_name;
                        if($request->video_size=='5'){
                            $myvideo['short_video']= 1;
                        }
                        if($request->video_size=='15'){
                            $myvideo['long_video']= 1;
                        }
                      
                        if($request->video_id!=''){
                            $result=MyVideo::findorfail($request->video_id);
                            $result->video =  $myvideo['video'];//env('APP_URL')."/public/".$watermark_url;
                            // $result->without_watermark_video =  env('APP_URL')."/public/".$video_url;
                            // $result->with_watermark_video =  env('APP_URL')."/public/".$watermark_url;
                        }else{
                            $result['video']=MyVideo::create($myvideo);
                        }
                        if(file_exists($output_path)){
                           $size = filesize($output_path)/1024;
                           $result['size']= number_format($size, 2, '.', '');
                        }
                       
                       
                        $msg  = array('status'=>true,'message' => "Video created successfully",'data'=>$result);
                        echo json_encode($msg);
                    }
                    else{
                         $msg  = array('status'=>false,'message' => "Video is not created successfully because data is not available regarding your parameter",'data'=>[]);
                        echo json_encode($msg);
                    }
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
    
    public function makeVideonew(Request $request){
        ini_set('max_execution_time', '0');
        if($request->token!=''){
            try{
                if($user = JWTAuth::authenticate($request->token)){
                    
                    /*check which content already used in video*/
                    $content = VideoContent::select('video_id')->where('user_id','=',$user->id)->distinct('video_id')->get();
                  
                    $totalcontent = count($content);
                    
                    $data= Video::select('video.*','secondary_language.name as secondary_language_name','primary_language.name as primary_language_name','section.name as section_name')
                                ->join('languages as primary_language','video.primary_language','=','primary_language.id')
                                ->join('languages as secondary_language','video.secondary_language','=','secondary_language.id')
                                ->join('section','video.section','=','section.id')
                                ->where(['video.status'=>1]);//->whereNotIn('id',$content);
                    if(!empty($request->videotype_id)){
                        $data= $data->where(['video.section'=>$request->videotype_id]);
                    }
                    if(!empty($request->template_type)){
                        $data= $data->where(['video.template_type'=>$request->template_type]);
                    }
                    if(!empty($request->primary_language)){
                        $data= $data->where(['video.primary_language'=>$request->primary_language]);
                    }
                    if(!empty($request->secondary_language)){
                        $data= $data->where(['video.secondary_language'=>$request->secondary_language]);
                    }
                    if(!empty($request->category)){
                        $data= $data->where(['video.category'=>$request->category]);
                    }
                    if(!empty($request->subcategory)){
                        $data= $data->where(['video.subcategory'=>$request->subcategory]);
                    }
                    $data=$data->inRandomOrder();
                   
                    if(!empty($request->video_size)){
                        $data= $data->limit($request->video_size);
                    }
                   
                    if($data->count()>=$request->video_size){
                        if(($data->count()-$totalcontent)>$request->video_size || ($data->count()-$totalcontent)==$request->video_size){
                           $data= $data->whereNotIn('id',$content);
                        }
                        $data= $data->get();
                        //dd($data);
                        $template = Template::findorfail($request->template_id);
                       
                       $destinationtempfilePath=public_path().'/uploads/temp_file/' . $user->id;
                        if( !is_dir( $destinationtempfilePath ) ) mkdir( $destinationtempfilePath, 0755, true );
                        $txtfile = fopen($destinationtempfilePath."/input.txt", "w") or die("Unable to open file!");
                        $soundfile = fopen($destinationtempfilePath."/input1.txt", "w") or die("Unable to open file!");
                        
                        $audio_column=$request->voice;
                        
                        foreach($data as $video){
                            $videocontent['user_id']=$user->id;
                            $videocontent['video_id']=$video->id;
                            VideoContent::create($videocontent);
                            $video_text = VideoTextMapping::join('video_text','video_text.id','=','video_text_mapping.text_id')->where('video_text_mapping.video_id','=',$video->id)->get();
                            $video_html = $template->template_html_string;
    
                            $template_type= explode(' ',$video->templatetype);
                            $lineno = $template_type[0];
                            foreach($video_text as $key=>$text){
                                $counter= $key+1;
                               
                                $searchtext = '{text'.$counter.'}';
                                if (strpos($video_html,$searchtext) !== false) {
                                   $video_html= str_replace($searchtext,$text['text'],$video_html);
                                }
                            }
                           $image_size = $template->template_image_size;
                          
                             $thumbnail_400_320 = 'uploads/video/thumbnail_image/'.time().".jpg";
                             Image::make(public_path($video->image))->resize(420, 420)->save(public_path($thumbnail_400_320));
               
                            if (strpos($video_html,"{img}") !== false) {
                                if(!empty($video->$image_size) && $video->$image_size!='0'){
                                    //if($video->section_name=='Word'){
                                        $path = config('app.asset_url').'/'.$video->$image_size;
                                    // }
                                    // else{
                                      //$path = config('app.asset_url').'/'.$thumbnail_400_320;
                                    // }
                                    
                                     
                                 }else{
                                    $path = config('app.asset_url').'/'.$thumbnail_400_320;
                                    //    $path = config('app.asset_url').'/'.$video->image;
                                }
                                $video_html= str_replace('{img}',$path,$video_html);
                             }
                             
                            if (strpos($video_html,"{openbody}") !== false) {
                               $video_html= str_replace('{openbody}',"<body style='",$video_html);
                            }
                            if (strpos($video_html,"{closebody}") !== false) {
                               $video_html= str_replace('{closebody}',"'>",$video_html);
                            }
                            if (strpos($video_html,"{endbody}") !== false) {
                               $video_html= str_replace('{endbody}',"</body>",$video_html);
                            }
                            
                            // /*replace font size*/
                            // if (strpos($video_html,"{font_size}") !== false) {
                            //     if($video->section_name=='Word'){
                            //         $font_size=75;
                            //     }
                            //     else{
                            //         $font_size=40;
                            //     }
                            //   $video_html= str_replace('{font_size}',$font_size,$video_html);
                            // }
                            
                            switch ($video->secondary_language_name) {
                                case 'Hindi':
                                    $font_family2="'Hind', serif;";
                                    break;
                                case 'Bengali':
                                    $font_family2="'Noto Sans Bengali', sans-serif;";
                                    break;
                                case 'Tamil':
                                    $font_family2="'Noto Sans Tamil', sans-serif;";
                                    break;
                                case 'German':
                                    $font_family2="'Noto Sans', sans-serif;";
                                    break;
                                case 'Telugu':
                                    $font_family2="'Noto Sans Telugu', sans-serif;";
                                    break;
                                case 'Urdu':
                                      $font_family2="'Noto Nastaliq Urdu', serif;";
                                    break;
                                
                               default:
                                    $font_family2=$template->fonts.";";
                            }
                            
                            switch ($video->primary_language_name) {
                                case 'Hindi':
                                    $font_family1="'Hind', serif;";
                                    break;
                                case 'Bengali':
                                    $font_family1="'Noto Sans Bengali', sans-serif;";
                                    break;
                                case 'Tamil':
                                    $font_family1="'Noto Sans Tamil', sans-serif;";
                                    break;
                                case 'German':
                                    $font_family1="'Noto Sans', sans-serif;";
                                    break;
                                case 'Telugu':
                                    $font_family1="'Noto Sans Telugu', sans-serif;";
                                    break;
                                case 'Urdu':
                                    $font_family1="'Noto Nastaliq Urdu', serif;";
                                    break;
                                
                               default:
                                    $font_family1=$template->fonts.";";
                            }
                            
                            if (strpos($video_html,"{font_family1}") !== false) {
                               $video_html= str_replace('{font_family1}',$font_family1,$video_html);
                            }
                            
                            if (strpos($video_html,"{font_family2}") !== false) {
                               $video_html= str_replace('{font_family2}',$font_family2,$video_html);
                            }

                            //echo $video_html;die;
                             $video->video_html=$video_html;
    
                             $options = new Options();
                             $options->setIsRemoteEnabled(true);
                             $dompdf = new Dompdf($options);
                             $dompdf->loadHtml( $video_html); 
                              
                            // (Optional) Setup the paper size and orientation
                            if(!empty($request->ratio_name) && $request->ratio_name=="16:9"){
                                $customPaper = array(0,0,1440,1024);
                            }
                            if(!empty($request->ratio_name) && $request->ratio_name=="9:16"){
                                   $customPaper = array(0,0,600,800);
                            }
                            
                            // $customPaper = array(0,0,2048,1152);
                            // $customPaper = array(0,0,2048,1152);
                            $dompdf->setPaper($customPaper);
                            $dompdf->curlAllowUnsafeSslRequests = true;
                            
                
                            
                            // Render the HTML as PDF
                            $dompdf->render();
                           // $pdfname= 'Brochure'.time().'.pdf';
                            
                            $pdfpath = "uploads/temp_pdf/".$user->id;
                            $pdf_url = $pdfpath."/".time().'.pdf';
                            echo $pdf_path=public_path()."/".$pdf_url;
                            $destinationpdfPath = public_path($pdfpath);   
                            if( !is_dir( $destinationpdfPath ) ) mkdir( $destinationpdfPath, 0755, true );
                            
                            file_put_contents($pdf_path, $dompdf->output());
                             
                            $imagick = new \Imagick();
                            $imagick->readImage($pdf_path);
                            $imagepath = "uploads/temp_image/".$user->id;
                            $image_url = $imagepath."/".time().'.jpg';
                            echo $image_path=public_path()."/".$image_url;
                             $destinationPath = public_path($imagepath);   
                            if( !is_dir( $destinationPath ) ) mkdir( $destinationPath, 0755, true );
                            
                            $imagick->writeImages($image_path, true);
                            
                           
                           $audio_path = public_path().'/'.$video->$audio_column;
                            //echo "ffprobe -i $audio_path";die;
                            $duration = shell_exec("ffprobe -i $audio_path -show_entries format=duration");
                            $duration = str_replace("\n", "", $duration);
                           
                           
                           $duration = str_replace("[FORMAT]", "", $duration);
                           $duration = str_replace("[/FORMAT]", "", $duration);
                           $duration = str_replace("duration=", "", $duration);
                            
                            //  if($request->voice=="male"){                 
                            //     $audio_path = public_path().'/'.$video->audio_m;
                            //     //echo "ffprobe -i $audio_path";die;
                            //     $duration = shell_exec("ffprobe -i $audio_path -show_entries format=duration");
                            //     $duration = str_replace("\n", "", $duration);
                               
                               
                            //   $duration = str_replace("[FORMAT]", "", $duration);
                            //   $duration = str_replace("[/FORMAT]", "", $duration);
                            //   $duration = str_replace("duration=", "", $duration);
                              
                               
                            //     //$output_array = explode('=',$duration); 
                                  
                            //     // $duration_array= explode('[/FORMAT]',$output_array[1]);

                            //     // $duration = $duration_array[0];
                                
                            //     //$duration = str_replace("[/FORMAT]", "", $output_array[1]);
                            //  }else{
                            //     $audio_path = public_path().'/'.$video->audio_f;
                            //     //echo "ffprobe -i $audio_path";die;
                            //     $duration = shell_exec("ffprobe -i $audio_path -show_entries format=duration");
                            //     $duration = str_replace("\n", "", $duration);
                            //     //$output_array = explode('=',$duration);
                            //     // $duration_array= explode('[/FORMAT]',$output_array[1]);
                               
                            //     // $duration = $duration_array[0];
                            //     // $duration = str_replace("[/FORMAT]", "", $output_array[1]);
                                
                            //     $duration = str_replace("[FORMAT]", "", $duration);
                            //     $duration = str_replace("[/FORMAT]", "", $duration);
                            //     $duration = str_replace("duration=", "", $duration);
                            //  }
                            
                            if(file_exists($image_path)) { 
                                $txt = "file ".$image_path."\n";
                            }else{
                                 $txt = "file ".public_path('uploads/design.jpg')." \n";
                            }
                            fwrite($txtfile, $txt);
                            $txt = "duration ".$duration." \n";
                            fwrite($txtfile, $txt);
    
                            if($request->voice="male"){
                                $txt = "file ".public_path()."/".$video->audio_m."\n";
                                fwrite($soundfile, $txt);
                                $txt = "outpoint ".$duration." \n";
                                fwrite($soundfile, $txt);
                            }else{
                                $txt = "file ".public_path()."/".$video->audio_f."\n";
                                fwrite($soundfile, $txt);
                                $txt = "outpoint ".$duration." \n";
                                fwrite($soundfile, $txt);
                            }
                            
                            sleep(2);
                        }
                       
                        fclose($txtfile);
    
                        fclose($soundfile);
                       
                        // $fileName = "input.txt";
                       
                        // $destinationFilePath = public_path($path);   
                          
                        // //  make all directories if they do not exist
                        // if( !is_dir( $destinationFilePath ) ) mkdir( $destinationFilePath, 0755, true );
                    
                       
         
    
                        //  $file_path = public_path().$path.'/input.txt';
                        // // //$file_path = env('APP_URL').'public/uploads/input.txt';
                        //  $file_path1 = public_path().$path.'/input1.txt';
                        
                         $file_path = public_path().'/uploads/temp_file/' . $user->id.'/input.txt';
                        $file_path1 = public_path().'/uploads/temp_file/' . $user->id.'/input1.txt';
                        
                         $destinationVideoPath=public_path()."/uploads/make_video/".$user->id;
                        if( !is_dir( $destinationVideoPath ) ) mkdir( $destinationVideoPath, 0755, true );
                        
                        if(!empty($request->video_format)){
                            $video_url = "/uploads/make_video/".$user->id."/".time().'.'.$request->video_format;
                            $output_path= public_path().$video_url;
                        }else{ 
                            $video_url = "/uploads/make_video/".$user->id."/".time().'.mp4';
                            $output_path= public_path().$video_url;
                        }
    
                        if(!empty($request->ratio_name)){
                            $ratio=" -r 15 -aspect ".$request->ratio_name." -strict -2";
                            //-r 15 -vf scale=1080:-1 -aspect 1:1 -strict -2
                        }
                        
                        
                        //$output= shell_exec("ffmpeg -f concat -i $file_path -f concat -i $file_path1 -r 25 -pix_fmt yuv420p  $output_path 2>&1");
                        if(!empty($request->ratio_name) && $request->ratio_name=="16:9"){
                            $output= shell_exec("ffmpeg -f concat -safe 0 -i $file_path -f concat -safe 0 -i $file_path1 -r 15 -c:v libx264 -pix_fmt yuv420p $ratio $output_path 2>&1");
                        }
                         if(!empty($request->ratio_name) && $request->ratio_name=="9:16"){
                             $output= shell_exec("ffmpeg -f concat -safe 0 -i $file_path -f concat -safe 0 -i $file_path1 -r 15 -c:v libx264 -pix_fmt yuv420p $output_path 2>&1");
                        }
                       
                        //               echo "<pre>";
                        // print_r($output);die;
                        echo env('APP_URL')."/public/".$video_url;//die;
                        $myvideo['user_id']= $user->id;
                        $myvideo['video']= env('APP_URL')."/public/".$video_url;
                        $result['video']=MyVideo::create($myvideo);
                        
                        $size = filesize($output_path)/1024;
                        $result['size']= number_format($size, 2, '.', '');
                        $msg  = array('status'=>true,'message' => "Video created successfully",'data'=>$result);
                        echo json_encode($msg);
                    }
                    else{
                         $msg  = array('status'=>false,'message' => "Video is not created successfully because data is not available regarding your parameter",'data'=>[]);
                        echo json_encode($msg);
                    }
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
    public function myVideo(Request $request){
        ini_set('max_execution_time', '0');
        if($request->token!=''){
            try{
                if($user = JWTAuth::authenticate($request->token)){
                   $data=MyVideo::where('user_id','=',$user->id)->skip($request->offset)->take($request->limit)->orderBy('id','desc')->get();
                    
                   
                    $msg  = array('status'=>true,'message' => "My video get successfully",'data'=>$data);
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
    
    public function removeVideo(Request $request){
        ini_set('max_execution_time', '0');
        if($request->token!=''){
            try{
                if($user = JWTAuth::authenticate($request->token)){
                    $video= MyVideo::find($request->id);
                    $video->delete();
                   
                    $msg  = array('status'=>true,'message' => "Video delete successfully");
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
    
    public function talkToAdvisorList(Request $request){
        
        if($request->token!=''){
            try{
                if($user = JWTAuth::authenticate($request->token)){
                    $data= TalkToAdvisor::where(['status'=>1])->get();
                    $msg  = array('status'=>true,'message' => "TalkToAdvisor listing get successfully",'data'=>$data);
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
    
    /*upload title*/
    public function saveTitle(Request $request){
       
        ini_set('max_execution_time', '0');
        if($request->token!=''){
            try{
                if($user = JWTAuth::authenticate($request->token)){
                    
                    if(!empty($request->title))
                    {
                        $image = $request->title;                                        
                        
                        $URL= env("APP_URL").'/title.html';
                        $contents=file_get_contents($URL);
                       
                        if (strpos($contents,"{title}") !== false) {
                          
                            $contents= str_replace('{title}',$request->title,$contents);
                        }
                        if (strpos($contents,"{bgcolor}") !== false) {
                          
                            $contents= str_replace('{bgcolor}',$request->bgcolor,$contents);
                        }
                        if (strpos($contents,"{fontcolor}") !== false) {
                          
                            $contents= str_replace('{fontcolor}',$request->fontcolor,$contents);
                        }
                        $font_array['30']='40';
                        $font_array['35']='50';
                        $font_array['40']='60';
                        $font_array['45']='65';
                        $font_array['50']='70';
                        $font_array['55']='75';
                        $font_array['60']='80';
                        $font_array['65']='90';
                        $font_array['70']='100';
                        
                        
                        if (strpos($contents,"{fontsize}") !== false) {
                            if(!empty($request->fontsize)){
                                if (array_key_exists($request->fontsize, $font_array)){
                                    $contents= str_replace('{fontsize}',$font_array[$request->fontsize],$contents);
                                }
                                //$contents= str_replace('{fontsize}',$request->fontsize,$contents);
                            }else{
                                  $contents= str_replace('{fontsize}',40,$contents);
                              }
                        }
                        // echo $contents;die;
                         $options = new Options();
                         $options->setIsRemoteEnabled(true);
                         $dompdf = new Dompdf($options);
                         $dompdf->loadHtml( $contents); 
                          
                        // (Optional) Setup the paper size and orientation
                        //$customPaper = array(0,0,1440,1024);
                        //$customPaper = array(0,0,1920,768);
                        
                        if(!empty($request->ratio_name) && $request->ratio_name=="16:9"){
                            $customPaper = array(0,0,1440,1024);
                        }
                        if(!empty($request->ratio_name) && $request->ratio_name=="9:16"){
                               $customPaper = array(0,0,600,800);
                        }
                        $dompdf->setPaper($customPaper);
                        $dompdf->curlAllowUnsafeSslRequests = true;
                        
            
                        
                        // Render the HTML as PDF
                        $dompdf->render();
                       // $pdfname= 'Brochure'.time().'.pdf';
                        
                        $pdfpath = "uploads/temp_pdf/".$user->id;
                        $pdf_url = $pdfpath."/".time().'.pdf';
                        $pdf_path=public_path()."/".$pdf_url;
                        $destinationpdfPath = public_path($pdfpath);   
                        if( !is_dir( $destinationpdfPath ) ) mkdir( $destinationpdfPath, 0755, true );
                        
                        file_put_contents($pdf_path, $dompdf->output());
                         
                        $imagick = new \Imagick();
                        $imagick->readImage($pdf_path);
                        $imagepath = "uploads/temp_image/".$user->id;
                        $image_url = $imagepath."/".time().'.jpg';
                        $image_path=public_path()."/".$image_url;
                         $destinationPath = public_path($imagepath);   
                        if( !is_dir( $destinationPath ) ) mkdir( $destinationPath, 0755, true );
                        
                        $imagick->writeImages($image_path, true);
                        // echo $image_path;die;
                        // $base64_str = substr($image, strpos($image, ",")+1);
        
                        // //decode base64 string
                        // $image = base64_decode($base64_str);
                        // $png_url = time().".jpg";
                        // $path = public_path('uploads/title/' .$user->id."/".$png_url);
                        
                        // $imagepath=public_path().'/uploads/title/' . $user->id;
                        // if( !is_dir( $imagepath ) ) mkdir( $imagepath, 0755, true );
                        
                        // Image::make(file_get_contents($request->title))->save($path);
                        // $imagepath=env("APP_URL")."public/uploads/title/".$user->id."/".$png_url;
                        $outputpath= public_path("/uploads/make_video/".$user->id);
                        if( !is_dir( $outputpath ) ) mkdir( $outputpath, 0755, true );
                          
                        if(!empty($request->video_format)){
                            $video_url ="/uploads/make_video/".$user->id."/".time().'.'.$request->video_format;
                            $output_path= public_path().$video_url;
                        }else{ 
                            $video_url = "/uploads/make_video/".$user->id."/".time().'.mp4';
                            $output_path= public_path().$video_url;
                        }
                         $blankaudio = public_path("/audio2.mp3");
                        
                        if(!empty($request->ratio_name)){
                            $ratio=" -r 15 -aspect ".$request->ratio_name." -strict -2 ";
                            //-r 15 -vf scale=1080:-1 -aspect 1:1 -strict -2
                        }
                         $output= shell_exec("ffmpeg  -r 1/5 -start_number 0 -i $image_path -i $blankaudio -r 15 -c:v libx264 -pix_fmt yuv420p -vf scale=1440:1024 $ratio $output_path 2>&1");
                      
                        $title = env("APP_URL")."public".$video_url;
                        
                        if(file_exists($image_path)) { 
                              unlink($image_path); //remove the file
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
                    
                    if ($request->hasFile('intro_video')) {
                        $extension = $request->file('intro_video')->getClientOriginalExtension();
                        // Filename To store
                        $fileNameToStore = time().'.'.$extension;
            
                        $request->intro_video->move(public_path('uploads/make_video/'.$user->id."/"), $fileNameToStore);
                        $video1 =public_path('/uploads/make_video/'.$user->id."/".$fileNameToStore);
                        
                        $stream = shell_exec("ffprobe -i $video1 -show_streams -select_streams a -loglevel error");
                        if($stream==null){
                            $blankaudio = public_path("/audio2.mp3");
                            $withaudio = $video1." -i $blankaudio ";
                        }else{
                            $withaudio = $video1;
                        }
                        if(!empty($request->ratio_name)){
                            $ratio=" -r 15 -aspect ".$request->ratio_name." -strict -2 ";
                            
                        }
                        else{
                            $ratio=" -r 15 -aspect 16:9 -strict -2 ";
                        }
                        
                        $output_path = public_path('/uploads/make_video/'.$user->id."/video1_".$fileNameToStore);
                        
                        $outputpath= public_path("/uploads/make_video/".$user->id);
                        if( !is_dir( $outputpath ) ) mkdir( $outputpath, 0755, true );
                          
                          
                        $output= shell_exec("ffmpeg -i $withaudio -vf scale=1440:1024 -af 'aformat=channel_layouts=stereo,asetrate=44100' $ratio $output_path 2>&1");
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
                        $stream = shell_exec("ffprobe -i $outro_video -show_streams -select_streams a -loglevel error");
                        if($stream==null){
                            $blankaudio = public_path("/audio2.mp3");
                            $withaudio = $outro_video." -i $blankaudio ";
                        }else{
                            $withaudio = $outro_video;
                        }
                        if(!empty($request->ratio_name)){
                            $ratio=" -r 15 -aspect ".$request->ratio_name." -strict -2 ";
                            
                        }
                        else{
                            $ratio=" -r 15 -aspect 16:9 -strict -2 ";
                        }
                        
                        $outputpath= public_path("/uploads/make_video/".$user->id);
                        if( !is_dir( $outputpath ) ) mkdir( $outputpath, 0755, true );
                        
                        $output_path = public_path('/uploads/make_video/'.$user->id."/video1_".$fileNameToStore);
                        
                        $output= shell_exec("ffmpeg -i $withaudio -vf scale=1440:1024 -af 'aformat=channel_layouts=stereo,asetrate=44100' $ratio $output_path 2>&1");
                        
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
                        
                        if(!empty($request->ratio_name)){
                            $ratio=" -r 15 -aspect ".$request->ratio_name." -strict -2  ";
                            
                        }
                        else{
                            $ratio=" -r 15 -aspect 16:9 -strict -2 ";
                        } 
                        
                        $outputpath= public_path("/uploads/make_video/".$user->id);
                        if( !is_dir( $outputpath ) ) mkdir( $outputpath, 0755, true );
                        
                        $output_path = public_path('/uploads/make_video/'.$user->id."/main_".$fileNameToStore);
                        
                        $output= shell_exec("ffmpeg -i $request->main_video -r 15 -c:v libx264 -pix_fmt yuv420p -vf scale=1440:1024 -af 'aformat=channel_layouts=stereo,asetrate=24000'  $ratio $output_path 2>&1");
                      
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
                        
                        $destinationWatermarkPath=public_path()."/uploads/watermark/".$user->id;
                        if( !is_dir( $destinationWatermarkPath ) ) mkdir( $destinationWatermarkPath, 0755, true );
                        
                        if(!empty($request->height) && ($request->height!="null") && !empty($request->width) && ($request->width!="null")){
                            Image::make($request->watermark)->resize($request->width, $request->height)->save(public_path('uploads/watermark/'.$user->id."/".$fileNameToStore));
                        }else{
                            Image::make($request->watermark)->resize(150, 100)->save(public_path('uploads/watermark/'.$user->id."/".$fileNameToStore));
                        }
                        
                        
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
    
     /*add watermark any video*/
    public function watermarkVideo(Request $request){
        
        ini_set('max_execution_time', '0');
        if($request->token!=''){
            try{
                if($user = JWTAuth::authenticate($request->token)){
                    
                    if(!empty($request->video) && !empty($request->watermark)){
                        
                        if(!empty($request->video_format)){
                            $fileNameToStore= time().".".$request->video_format;
                        }else{ 
                            $fileNameToStore= time().".mp4";
                        }
                        $output_path = public_path('/uploads/make_video/'.$user->id."/watermark_".$fileNameToStore);
                        try{
                            $output= shell_exec("ffmpeg -i $request->video -i $request->watermark -filter_complex 'overlay=x=(main_w-overlay_w-10):y=(main_h-overlay_h)/(main_h-overlay_h)' -codec:a copy $output_path 2>&1");//top right
                        }
                        catch(Exception $e){
                            echo "this will never execute";
                        }
                       $video_url= env("APP_URL")."public"."/uploads/make_video/".$user->id."/watermark_".$fileNameToStore;
                    //   if(file_exists($vurl)) { 
                    //           unlink($vurl); //remove the file
                    //     }
                    
                    }
                    $msg  = array('status'=>true,'message' => "Add watermark on video successfully",'data'=>$video_url);
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
    // public function saveFinalVideo_06_june(Request $request){
    //   // echo "<pre>";print_r($request->all());die;
    //     //  $output_path = "/home/readyvids/public_html/public/uploads/".time().".avi";
    //     // //echo "ffmpeg -i $request->title -i $request->main_video  -filter_complex '[0:v] [0:a] [1:v] [1:a] concat=n=2:v=1:a=1[outv][outa]' -map '[outv]' -map '[outa]' $output_path 2>&1";die;
    //     //  $output=shell_exec("ffmpeg -i $request->title -i $request->intro_video -i $request->main_video -i $request->outro_video -filter_complex '[0:v] [0:a] [1:v] [1:a] [2:v] [2:a] [3:v] [3:a] concat=n=4:v=1:a=1[outv][outa]' -map '[outv]' -map '[outa]' $output_path 2>&1");
    //     //           echo "<pre>";
    //     //           print_r($output);
    //     //           die;
    //     ini_set('max_execution_time', '0');
    //     if($request->token!=''){
    //         try{
    //             if($user = JWTAuth::authenticate($request->token)){
                    
    //                 $cmd= "ffmpeg";
    //                 $n=0;
    //                 $filter=' -filter_complex ';
    //                 $str='';
    //                 if(!empty($request->title))
    //                 {
    //                   $cmd .= " -i $request->title";
    //                   $str .= '['.$n.':v]['.$n.':a]';
    //                   $n++;
                       
                     
    //                 }
                  
    //                 if(!empty($request->intro_video))
    //                 {   
    //                      $cmd .= " -i $request->intro_video"; 
    //                       $str .= '['.$n.':v]['.$n.':a]';
    //                      $n++;
    //                 }
       
                    
    //                 if(!empty($request->main_video)){
                        
    //                      $cmd .= " -i $request->main_video"; 
    //                       $str .= '['.$n.':v]['.$n.':a]';
    //                       $n++;
    //                 }
                    
            
    //                 if(!empty($request->outro_video)){
                        
    //                     $cmd .= " -i $request->outro_video";
    //                     $str .= '['.$n.':v]['.$n.':a]';
    //                     $n++;
    //                 }
                    
                       
                       
    //                 if($n>0){
    //                     if(!empty($request->video_format)){
    //                         $fileNameToStore= time().".".$request->video_format;
    //                     }else{ 
    //                         $fileNameToStore= time().".mp4";
    //                     }
    //                     $output_path = public_path('/uploads/final_video/'.$user->id."/final_".$fileNameToStore);
    //                   $cmd = $cmd.$filter."'".$str." concat=n=".$n.":v=1:a=1[outv][outa]' -c:v libx264 -map '[outv]' -map '[outa]' -r 25 -pix_fmt yuv420p -preset ultrafast -y ".$output_path." 2>&1";
                        
    //                     $output = shell_exec($cmd);
    //                     //dd($output);
    //                 }
                    
                  
    //                 $vurl= env('APP_URL')."public/uploads/final_video/".$user->id."/final_".$fileNameToStore;
                    
    //                 $result=MyVideo::findorfail($request->video_id);
    //                 $result->video = $vurl;
    //                 $result->update();
                    
    //                 $msg  = array('status'=>true,'message' => "Video updated successfully",'data'=>$result);
    //                 echo json_encode($msg);
    //             }else{
    //                 $msg  = array('status'=>false,'message' => "User not found","data"=>[]);
    //                 echo json_encode($msg);
    //             }
    //         }catch (JWTException $e) {
    
    //             $msg  = array('status'=>false,'message' => "Could Not Create Token.",'data'=>[]);
    //             echo json_encode($msg);
    //         }
    //     }else{
    //         $msg  = array('status'=>false,'message' => "Please send all parameters",'data'=>[]);
    //         echo json_encode($msg);
    //     }
 
    // }
    
    public function saveFinalVideo(Request $request){
       
        ini_set('max_execution_time', '0');
        if($request->token!=''){
            try{
                if($user = JWTAuth::authenticate($request->token)){
                    
                    $cmd= "ffmpeg";
                    $n=0;
                    $filter=' -filter_complex ';
                    $str='';
                    
                    if(!empty($request->video_format)){
                        $fileNameToStore= time().".".$request->video_format;
                    }else{ 
                        $fileNameToStore= time().".mp4";
                    }
                    
                    
                    if(!empty($request->title_main))
                    {
                        // if(!empty($request->title)){
                        //     $fontfile=public_path()."/OpenSans-Regular.ttf";
                        //     $outputpath = public_path('/uploads/quiz_make_video/'.$user->id."/title".$fileNameToStore);
                        //     $out =shell_exec("ffmpeg -i $request->main_video -vf 'drawtext=fontfile=$fontfile:text=$request->title:fontcolor=black:fontsize=35:x=(w-text_w)/2:y=10:,format=yuv420p' -c:v libx264 -c:a copy -movflags +faststart $outputpath");
                        //     $request->main_video=env("APP_URL")."public"."/uploads/quiz_make_video/".$user->id."/title".$fileNameToStore;
                        // }    
                      $cmd .= " -i $request->title_main";
                      $str .= '['.$n.':v]['.$n.':a]';
                      $n++;
                       
                     
                    }
                    
                    $destinationWatermarkVideoPath=public_path()."/uploads/watermark_video/".$user->id;
                    if( !is_dir( $destinationWatermarkVideoPath ) ) mkdir( $destinationWatermarkVideoPath, 0755, true );
                        
                //   if(!empty($request->video_format)){
                //         $fileNameToStore= time().".".$request->video_format;
                //     }else{ 
                //         $fileNameToStore= time().".mp4";
                //     }
                    
                    if(!empty($request->intro_video))
                    {   
                        if(!empty($request->watermark)){
                            
                            $output_path = public_path('/uploads/watermark_video/'.$user->id."/introwatermark_".$fileNameToStore);
                            try{
                                //$output= shell_exec("ffmpeg -i $request->intro_video -i $request->watermark -filter_complex 'overlay=x=(main_w-overlay_w-10):y=(main_h-overlay_h)/(main_h-overlay_h)' -codec:a copy $output_path 2>&1");//top right
                                 if(!empty($request->position) && $request->position=='Top Left'){
                                
                                    $output= shell_exec("ffmpeg -i $request->intro_video -i $request->watermark -filter_complex 'overlay=10:80' -codec:a copy $output_path 2>&1");//top left
                                }
                                elseif(!empty($request->position) && $request->position=='Top Right'){
                                    
                                      $output= shell_exec("ffmpeg -i $request->intro_video -i $request->watermark -filter_complex 'overlay=(main_w-overlay_w-10):80' -codec:a copy $output_path 2>&1");//top right
                                
                                } 
                                elseif(!empty($request->position) && $request->position=='Center'){
                                   
                                   $output= shell_exec("ffmpeg -i $request->intro_video -i $request->watermark -filter_complex 'overlay=x=(main_w-overlay_w)/2:y=(main_h-overlay_h)/2' -codec:a copy $output_path 2>&1");//center
                                }
                                elseif(!empty($request->position) && $request->position=='Bottom Left'){
                                    
                                    $output= shell_exec("ffmpeg -i $request->intro_video -i $request->watermark -filter_complex 'overlay=10:(main_h-overlay_h-20)' -codec:a copy $output_path 2>&1");//bottom left
                                }
                                elseif(!empty($request->position) && $request->position=='Bottom Right'){
                                    
                                    $output= shell_exec("ffmpeg -i $request->intro_video -i $request->watermark -filter_complex 'overlay=(main_w-overlay_w-10):(main_h-overlay_h-20)' -codec:a copy $output_path 2>&1");//bottom right
                                }
                                else{
                                    $output= shell_exec("ffmpeg -i $request->intro_video -i $request->watermark -filter_complex 'overlay=(main_w-overlay_w-10):80' -codec:a copy $output_path 2>&1");//top right
                                }
                            }
                            catch(Exception $e){
                                echo "this will never execute";
                            }
                            $video_url= env("APP_URL")."public"."/uploads/watermark_video/".$user->id."/introwatermark_".$fileNameToStore;
                            $request->intro_video=$video_url;
                        }
                        $cmd .= " -i $request->intro_video"; 
                          $str .= '['.$n.':v]['.$n.':a]';
                         $n++;
                       
                         
                    }
       
                    
                    if(!empty($request->main_video)){
                        
                        if(!empty($request->watermark)){
                            
                            $output_path1 = public_path('/uploads/watermark_video/'.$user->id."/mainvideowatermark_".$fileNameToStore);
                            try{
                                //$output= shell_exec("ffmpeg -i $request->main_video -i $request->watermark -filter_complex 'overlay=x=(main_w-overlay_w-10):y=(main_h-overlay_h)/(main_h-overlay_h)' -codec:a copy $output_path1 2>&1");//top right
                                 if(!empty($request->position) && $request->position=='Top Left'){
                                
                                    $output= shell_exec("ffmpeg -i $request->main_video -i $request->watermark -filter_complex 'overlay=10:80' -codec:a copy $output_path1 2>&1");//top left
                                }
                                elseif(!empty($request->position) && $request->position=='Top Right'){
                                    
                                      $output= shell_exec("ffmpeg -i $request->main_video -i $request->watermark -filter_complex 'overlay=(main_w-overlay_w-10):80' -codec:a copy $output_path1 2>&1");//top right
                                
                                } 
                                elseif(!empty($request->position) && $request->position=='Center'){
                                   
                                   $output= shell_exec("ffmpeg -i $request->main_video -i $request->watermark -filter_complex 'overlay=x=(main_w-overlay_w)/2:y=(main_h-overlay_h)/2' -codec:a copy $output_path1 2>&1");//center
                                }
                                elseif(!empty($request->position) && $request->position=='Bottom Left'){
                                    
                                    $output= shell_exec("ffmpeg -i $request->main_video -i $request->watermark -filter_complex 'overlay=10:(main_h-overlay_h-20)' -codec:a copy $output_path1 2>&1");//bottom left
                                }
                                elseif(!empty($request->position) && $request->position=='Bottom Right'){
                                    
                                    $output= shell_exec("ffmpeg -i $request->main_video -i $request->watermark -filter_complex 'overlay=(main_w-overlay_w-10):(main_h-overlay_h-20)' -codec:a copy $output_path1 2>&1");//bottom right
                                }
                                else{
                                    $output= shell_exec("ffmpeg -i $request->main_video -i $request->watermark -filter_complex 'overlay=(main_w-overlay_w-10):80' -codec:a copy $output_path1 2>&1");//top right
                                }
                            }
                            catch(Exception $e){
                                echo "this will never execute";
                            }
                            $video_url1= env("APP_URL")."public"."/uploads/watermark_video/".$user->id."/mainvideowatermark_".$fileNameToStore;
                            $request->main_video=$video_url1;
                        }
                        
                        $cmd .= " -i $request->main_video"; 
                        $str .= '['.$n.':v]['.$n.':a]';
                        $n++;
                       
                    }
                    
            
                    if(!empty($request->outro_video)){
                        
                        if(!empty($request->watermark)){
                            
                            $output_path2 = public_path('/uploads/watermark_video/'.$user->id."/outrowatermark_".$fileNameToStore);
                            try{
                               // $output= shell_exec("ffmpeg -i $request->outro_video -i $request->watermark -filter_complex 'overlay=x=(main_w-overlay_w-10):y=(main_h-overlay_h)/(main_h-overlay_h)' -codec:a copy $output_path2 2>&1");//top right
                                if(!empty($request->position) && $request->position=='Top Left'){
                                
                                    $output= shell_exec("ffmpeg -i $request->outro_video -i $request->watermark -filter_complex 'overlay=10:80' -codec:a copy $output_path2 2>&1");//top left
                                }
                                elseif(!empty($request->position) && $request->position=='Top Right'){
                                    
                                      $output= shell_exec("ffmpeg -i $request->outro_video -i $request->watermark -filter_complex 'overlay=(main_w-overlay_w-10):80' -codec:a copy $output_path2 2>&1");//top right
                                
                                } 
                                elseif(!empty($request->position) && $request->position=='Center'){
                                   
                                   $output= shell_exec("ffmpeg -i $request->outro_video -i $request->watermark -filter_complex 'overlay=x=(main_w-overlay_w)/2:y=(main_h-overlay_h)/2' -codec:a copy $output_path2 2>&1");//center
                                }
                                elseif(!empty($request->position) && $request->position=='Bottom Left'){
                                    
                                    $output= shell_exec("ffmpeg -i $request->outro_video -i $request->watermark -filter_complex 'overlay=10:(main_h-overlay_h-20)' -codec:a copy $output_path2 2>&1");//bottom left
                                }
                                elseif(!empty($request->position) && $request->position=='Bottom Right'){
                                    
                                    $output= shell_exec("ffmpeg -i $request->outro_video -i $request->watermark -filter_complex 'overlay=(main_w-overlay_w-10):(main_h-overlay_h-20)' -codec:a copy $output_path2 2>&1");//bottom right
                                }
                                else{
                                    $output= shell_exec("ffmpeg -i $request->outro_video -i $request->watermark -filter_complex 'overlay=(main_w-overlay_w-10):80' -codec:a copy $output_path2 2>&1");//top right
                                }
                            }
                            catch(Exception $e){
                                echo "this will never execute";
                            }
                            $video_url2= env("APP_URL")."public"."/uploads/watermark_video/".$user->id."/outrowatermark_".$fileNameToStore;
                            $request->outro_video=$video_url2;
                        }
                        
                        $cmd .= " -i $request->outro_video";
                        $str .= '['.$n.':v]['.$n.':a]';
                        $n++;
                    }
                    
                    
                    // if(!empty($request->TitleRes))
                    // {
                    //   $cmd .= " -i $request->TitleRes";
                    //   $str .= '['.$n.':v]['.$n.':a]';
                    //   $n++;
                       
                     
                    // }
                  
                    // if(!empty($request->IntroRes))
                    // {   
                    //      $cmd .= " -i $request->IntroRes"; 
                    //       $str .= '['.$n.':v]['.$n.':a]';
                    //      $n++;
                    // }
       
                    
                    // if(!empty($request->MainRes)){
                        
                    //      $cmd .= " -i $request->MainRes"; 
                    //       $str .= '['.$n.':v]['.$n.':a]';
                    //       $n++;
                    // }
                    
            
                    // if(!empty($request->OutroRes)){
                        
                    //     $cmd .= " -i $request->OutroRes";
                    //     $str .= '['.$n.':v]['.$n.':a]';
                    //     $n++;
                    // }
                    
                       
                       
                    if($n>1){
                        if(!empty($request->video_format)){
                            $fileNameToStore= time().".".$request->video_format;
                        }else{ 
                            $fileNameToStore= time().".mp4";
                        }
                        
                        $destinationVideoPath=public_path()."/uploads/final_video/".$user->id;
                        if( !is_dir( $destinationVideoPath ) ) mkdir( $destinationVideoPath, 0755, true );
                        
                        $output_path = public_path('/uploads/final_video/'.$user->id."/final_".$fileNameToStore);
                        $cmd = $cmd.$filter."'".$str." concat=n=".$n.":v=1:a=1[outv][outa]' -c:v libx264 -map '[outv]' -map '[outa]' -r 25 -pix_fmt yuv420p -preset ultrafast -y ".$output_path." 2>&1";
                        
                        $output = shell_exec($cmd);
                        sleep(2);
                        //dd($output);
                        
                        $vurl= env('APP_URL')."public/uploads/final_video/".$user->id."/final_".$fileNameToStore;
                    }
                    else{
                        $vurl=$request->main_video;
                    }
                  
                  
                    
                    $result=MyVideo::findorfail($request->video_id);
                    $result->video = $vurl;
                    $result->update();
                    
                    $msg  = array('status'=>true,'message' => "Video updated successfully",'data'=>$result);
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
    public function saveQuizFinalVideo(Request $request){
      
        ini_set('max_execution_time', '0');
        if($request->token!=''){
            try{
                if($user = JWTAuth::authenticate($request->token)){
                    
                    $cmd= "ffmpeg";
                    $n=0;
                    $filter=' -filter_complex ';
                    $str='';
                    
                    if(!empty($request->video_format)){
                        $fileNameToStore= time().".".$request->video_format;
                    }else{ 
                        $fileNameToStore= time().".mp4";
                    }
                    
                    
                    if(!empty($request->title_main))
                    {
                        if(!empty($request->title)){
                            $fontfile=public_path()."/OpenSans-Regular.ttf";
                            $outputpath = public_path('/uploads/quiz_make_video/'.$user->id."/title".$fileNameToStore);
                            echo "ffmpeg -i $request->main_video -vf 'drawtext=fontfile=$fontfile:text=$request->title:fontcolor=black:fontsize=35:x=(w-text_w)/2:y=10:,format=yuv420p' -c:v libx264 -c:a copy -movflags +faststart $outputpath";
                            $out =shell_exec("ffmpeg -i $request->main_video -vf 'drawtext=fontfile=$fontfile:text=$request->title:fontcolor=black:fontsize=35:x=(w-text_w)/2:y=10:,format=yuv420p' -c:v libx264 -c:a copy -movflags +faststart $outputpath");
                            $request->main_video=env("APP_URL")."public"."/uploads/quiz_make_video/".$user->id."/title".$fileNameToStore;
                        }    
                      $cmd .= " -i $request->title_main";
                      $str .= '['.$n.':v]['.$n.':a]';
                      $n++;
                       
                     
                    }
                    
                    $destinationWatermarkVideoPath=public_path()."/uploads/watermark_quiz_video/".$user->id;
                    if( !is_dir( $destinationWatermarkVideoPath ) ) mkdir( $destinationWatermarkVideoPath, 0755, true );
                        
                //   if(!empty($request->video_format)){
                //         $fileNameToStore= time().".".$request->video_format;
                //     }else{ 
                //         $fileNameToStore= time().".mp4";
                //     }
                    
                    if(!empty($request->intro_video))
                    {   
                        if(!empty($request->watermark)){
                            
                            $output_path = public_path('/uploads/watermark_quiz_video/'.$user->id."/introwatermark_".$fileNameToStore);
                            try{
                                //$output= shell_exec("ffmpeg -i $request->intro_video -i $request->watermark -filter_complex 'overlay=x=(main_w-overlay_w-10):y=(main_h-overlay_h)/(main_h-overlay_h)' -codec:a copy $output_path 2>&1");//top right
                                 if(!empty($request->position) && $request->position=='Top Left'){
                                
                                    $output= shell_exec("ffmpeg -i $request->intro_video -i $request->watermark -filter_complex 'overlay=10:80' -codec:a copy $output_path 2>&1");//top left
                                }
                                elseif(!empty($request->position) && $request->position=='Top Right'){
                                    
                                      $output= shell_exec("ffmpeg -i $request->intro_video -i $request->watermark -filter_complex 'overlay=(main_w-overlay_w-10):80' -codec:a copy $output_path 2>&1");//top right
                                
                                } 
                                elseif(!empty($request->position) && $request->position=='Center'){
                                   
                                   $output= shell_exec("ffmpeg -i $request->intro_video -i $request->watermark -filter_complex 'overlay=x=(main_w-overlay_w)/2:y=(main_h-overlay_h)/2' -codec:a copy $output_path 2>&1");//center
                                }
                                elseif(!empty($request->position) && $request->position=='Bottom Left'){
                                    
                                    $output= shell_exec("ffmpeg -i $request->intro_video -i $request->watermark -filter_complex 'overlay=10:(main_h-overlay_h-20)' -codec:a copy $output_path 2>&1");//bottom left
                                }
                                elseif(!empty($request->position) && $request->position=='Bottom Right'){
                                    
                                    $output= shell_exec("ffmpeg -i $request->intro_video -i $request->watermark -filter_complex 'overlay=(main_w-overlay_w-10):(main_h-overlay_h-20)' -codec:a copy $output_path 2>&1");//bottom right
                                }
                                else{
                                    $output= shell_exec("ffmpeg -i $request->intro_video -i $request->watermark -filter_complex 'overlay=(main_w-overlay_w-10):80' -codec:a copy $output_path 2>&1");//top right
                                }
                            }
                            catch(Exception $e){
                                echo "this will never execute";
                            }
                            $video_url= env("APP_URL")."public"."/uploads/watermark_quiz_video/".$user->id."/introwatermark_".$fileNameToStore;
                            $request->intro_video=$video_url;
                        }
                        $cmd .= " -i $request->intro_video"; 
                          $str .= '['.$n.':v]['.$n.':a]';
                         $n++;
                       
                         
                    }
       
                    
                    if(!empty($request->main_video)){
                        
                        if(!empty($request->watermark)){
                            
                            $output_path1 = public_path('/uploads/watermark_quiz_video/'.$user->id."/mainvideowatermark_".$fileNameToStore);
                            try{
                                //$output= shell_exec("ffmpeg -i $request->main_video -i $request->watermark -filter_complex 'overlay=x=(main_w-overlay_w-10):y=(main_h-overlay_h)/(main_h-overlay_h)' -codec:a copy $output_path1 2>&1");//top right
                                 if(!empty($request->position) && $request->position=='Top Left'){
                                
                                    $output= shell_exec("ffmpeg -i $request->main_video -i $request->watermark -filter_complex 'overlay=10:80' -codec:a copy $output_path1 2>&1");//top left
                                }
                                elseif(!empty($request->position) && $request->position=='Top Right'){
                                    
                                      $output= shell_exec("ffmpeg -i $request->main_video -i $request->watermark -filter_complex 'overlay=(main_w-overlay_w-10):80' -codec:a copy $output_path1 2>&1");//top right
                                
                                } 
                                elseif(!empty($request->position) && $request->position=='Center'){
                                   
                                   $output= shell_exec("ffmpeg -i $request->main_video -i $request->watermark -filter_complex 'overlay=x=(main_w-overlay_w)/2:y=(main_h-overlay_h)/2' -codec:a copy $output_path1 2>&1");//center
                                }
                                elseif(!empty($request->position) && $request->position=='Bottom Left'){
                                    
                                    $output= shell_exec("ffmpeg -i $request->main_video -i $request->watermark -filter_complex 'overlay=10:(main_h-overlay_h-20)' -codec:a copy $output_path1 2>&1");//bottom left
                                }
                                elseif(!empty($request->position) && $request->position=='Bottom Right'){
                                    
                                    $output= shell_exec("ffmpeg -i $request->main_video -i $request->watermark -filter_complex 'overlay=(main_w-overlay_w-10):(main_h-overlay_h-20)' -codec:a copy $output_path1 2>&1");//bottom right
                                }
                                else{
                                    $output= shell_exec("ffmpeg -i $request->main_video -i $request->watermark -filter_complex 'overlay=(main_w-overlay_w-10):80' -codec:a copy $output_path1 2>&1");//top right
                                }
                            }
                            catch(Exception $e){
                                echo "this will never execute";
                            }
                            $video_url1= env("APP_URL")."public"."/uploads/watermark_quiz_video/".$user->id."/mainvideowatermark_".$fileNameToStore;
                            $request->main_video=$video_url1;
                        }
                        
                        $cmd .= " -i $request->main_video"; 
                        $str .= '['.$n.':v]['.$n.':a]';
                        $n++;
                       
                    }
                    
            
                    if(!empty($request->outro_video)){
                        
                        if(!empty($request->watermark)){
                            
                            $output_path2 = public_path('/uploads/watermark_quiz_video/'.$user->id."/outrowatermark_".$fileNameToStore);
                            try{
                               // $output= shell_exec("ffmpeg -i $request->outro_video -i $request->watermark -filter_complex 'overlay=x=(main_w-overlay_w-10):y=(main_h-overlay_h)/(main_h-overlay_h)' -codec:a copy $output_path2 2>&1");//top right
                                if(!empty($request->position) && $request->position=='Top Left'){
                                
                                    $output= shell_exec("ffmpeg -i $request->outro_video -i $request->watermark -filter_complex 'overlay=10:80' -codec:a copy $output_path2 2>&1");//top left
                                }
                                elseif(!empty($request->position) && $request->position=='Top Right'){
                                    
                                      $output= shell_exec("ffmpeg -i $request->outro_video -i $request->watermark -filter_complex 'overlay=(main_w-overlay_w-10):80' -codec:a copy $output_path2 2>&1");//top right
                                
                                } 
                                elseif(!empty($request->position) && $request->position=='Center'){
                                   
                                   $output= shell_exec("ffmpeg -i $request->outro_video -i $request->watermark -filter_complex 'overlay=x=(main_w-overlay_w)/2:y=(main_h-overlay_h)/2' -codec:a copy $output_path2 2>&1");//center
                                }
                                elseif(!empty($request->position) && $request->position=='Bottom Left'){
                                    
                                    $output= shell_exec("ffmpeg -i $request->outro_video -i $request->watermark -filter_complex 'overlay=10:(main_h-overlay_h-20)' -codec:a copy $output_path2 2>&1");//bottom left
                                }
                                elseif(!empty($request->position) && $request->position=='Bottom Right'){
                                    
                                    $output= shell_exec("ffmpeg -i $request->outro_video -i $request->watermark -filter_complex 'overlay=(main_w-overlay_w-10):(main_h-overlay_h-20)' -codec:a copy $output_path2 2>&1");//bottom right
                                }
                                else{
                                    $output= shell_exec("ffmpeg -i $request->outro_video -i $request->watermark -filter_complex 'overlay=(main_w-overlay_w-10):80' -codec:a copy $output_path2 2>&1");//top right
                                }
                            }
                            catch(Exception $e){
                                echo "this will never execute";
                            }
                            $video_url2= env("APP_URL")."public"."/uploads/watermark_quiz_video/".$user->id."/outrowatermark_".$fileNameToStore;
                            $request->outro_video=$video_url2;
                        }
                        
                        $cmd .= " -i $request->outro_video";
                        $str .= '['.$n.':v]['.$n.':a]';
                        $n++;
                    }
                    
                    
                    // if(!empty($request->TitleRes))
                    // {
                    //   $cmd .= " -i $request->TitleRes";
                    //   $str .= '['.$n.':v]['.$n.':a]';
                    //   $n++;
                       
                     
                    // }
                  
                    // if(!empty($request->IntroRes))
                    // {   
                    //      $cmd .= " -i $request->IntroRes"; 
                    //       $str .= '['.$n.':v]['.$n.':a]';
                    //      $n++;
                    // }
       
                    
                    // if(!empty($request->MainRes)){
                        
                    //      $cmd .= " -i $request->MainRes"; 
                    //       $str .= '['.$n.':v]['.$n.':a]';
                    //       $n++;
                    // }
                    
            
                    // if(!empty($request->OutroRes)){
                        
                    //     $cmd .= " -i $request->OutroRes";
                    //     $str .= '['.$n.':v]['.$n.':a]';
                    //     $n++;
                    // }
                    
                       
                       
                    if($n>1){
                        if(!empty($request->video_format)){
                            $fileNameToStore= time().".".$request->video_format;
                        }else{ 
                            $fileNameToStore= time().".mp4";
                        }
                        
                        $destinationVideoPath=public_path()."/uploads/final_quiz_video/".$user->id;
                        if( !is_dir( $destinationVideoPath ) ) mkdir( $destinationVideoPath, 0755, true );
                        
                        $output_path = public_path('/uploads/final_quiz_video/'.$user->id."/final_".$fileNameToStore);
                        $cmd = $cmd.$filter."'".$str." concat=n=".$n.":v=1:a=1[outv][outa]' -c:v libx264 -map '[outv]' -map '[outa]' -r 25 -pix_fmt yuv420p -preset ultrafast -y ".$output_path." 2>&1";
                        
                        $output = shell_exec($cmd);
                        sleep(2);
                        //dd($output);
                        
                        $vurl= env('APP_URL')."public/uploads/final_quiz_video/".$user->id."/final_".$fileNameToStore;
                    }
                    else{
                        $vurl=$request->main_video;
                    }
                  
                  
                    
                    $result=MyQuizVideo::findorfail($request->video_id);
                    $result->video = $vurl;
                    $result->update();
                    
                    $msg  = array('status'=>true,'message' => "Video updated successfully",'data'=>$result);
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
    
    public function getDownload(Request $request){
        
        
        $videoarray=array_reverse(explode('/',$request->main_video));
        //echo "<pre>";print_r($array);die;
       echo $file= public_path(). "/uploads/make_video/".$videoarray[1]."/".$videoarray[0];//2/1669725175.mp4";//"/sample.csv";die;

        // $headers = array(
        //         'Content-Type: application/csv',
        //         );
        $headers = array(
                'Content-Type: application/mp4',
                );

        return response()->download($file);
    }
    
    public function createOrder(Request $request){
        
        //dd($request->all());
        if($request->token!='' && $request->amount!=''){
            try{
                if($user = JWTAuth::authenticate($request->token)){
                    
                    $curl = curl_init();
        
                    $data["order_amount"]= $request->amount;
                    $data["order_currency"]= "INR";
                    $data["order_note"]= "Additional order info";
                    $data["customer_details"]['customer_id']="2";//$user['id'];//$user["customer_id"];
                    $data["customer_details"]['customer_name']=$user['name'];//$request["customer_name"];
                    $data["customer_details"]['customer_email']=$user['email'];//$request["customer_email"];
                    $data["customer_details"]['customer_phone']=$user['phone'];//$request["customer_phone"];
                    //echo json_encode($data);die;
                    curl_setopt_array($curl, array(
                      CURLOPT_URL => 'https://sandbox.cashfree.com/pg/orders',
                      CURLOPT_RETURNTRANSFER => true,
                      CURLOPT_ENCODING => '',
                      CURLOPT_MAXREDIRS => 10,
                      CURLOPT_TIMEOUT => 0,
                      CURLOPT_FOLLOWLOCATION => true,
                      CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                      CURLOPT_CUSTOMREQUEST => 'POST',
                      CURLOPT_POSTFIELDS =>json_encode($data),
                      CURLOPT_HTTPHEADER => array(
                        'x-api-version: 2022-09-01',
                        // 'x-client-id: 281215b593d589a79a02db952a512182',
                        // 'x-client-secret: bdbfa33d3e295f3202b9bfc9fae90571ddf2a2b7',
                          'x-client-id: 2812969b0fae55207fb23f9050692182',
                        'x-client-secret: 7b90ff22ece94384d828c7a3a2a55f9a6d526289',
                        'Content-Type: application/json'
                      ),
                    ));
                    
                    $response = curl_exec($curl);
                    //dd($response);
                    curl_close($curl);
                    echo $response;
                    // $msg  = array('status'=>true,'message' => "Order create successfully","data"=>json_decode($response));
                    // echo json_encode($msg);
                    
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
    
    
    public function contactUs(Request $request){
        
        dd($request->all());
        if($request->token!='' && $request->amount!=''){
            try{
                if($user = JWTAuth::authenticate($request->token)){
                    
                  
                    // $msg  = array('status'=>true,'message' => "Order create successfully","data"=>json_decode($response));
                    // echo json_encode($msg);
                    
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
    
    public function videoCount(Request $request){
        
       
        if($request->token!=''){
            try{
                if($user = JWTAuth::authenticate($request->token)){
                    if($user->payment_status){
                        $result['short_video'] =MyVideo::where('without_watermark_video','!=','')->where('short_video','=','1')->count('short_video'); 
                        $result['long_video'] =MyVideo::where('without_watermark_video','!=','')->where('long_video','=','1')->count('long_video');
                        
                    }else{
                        $result['short_video'] =MyVideo::where('with_watermark_video','!=','')->where('short_video','=','1')->count('short_video');
                        $result['long_video'] =MyVideo::where('with_watermark_video','!=','')->where('long_video','=','1')->count('long_video');
                    }
                    $result['package_detail']=Package::where('id','=',$user->package_id)->first();
                    $msg  = array('status'=>true,'message' => "Video count get successfully","data"=>$result);
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
    
    public function customizationVideo(Request $request){
          if($user = JWTAuth::authenticate($request->token)){
         
               $output_path = "/home/readyvids/public_html/public/uploads/final_video/61/".time().".mp4";
        
                   echo  $output_path;
                   
            
            $output=shell_exec("ffmpeg -i https://readyvids.manageprojects.in/public/uploads/make_video/61/V1_1440*1024_1668084349.mp4 -i https://readyvids.manageprojects.in/public/uploads/make_video/61/1668087268.mp4  filters -filter_complex '[0:v] [0:a] [1:v] [1:a] concat=n=2:v=1:a=1[outv][outa] ' -map '[outv]' -map '[outa]' $output_path 2>&1");
                  echo "<pre>";
                  print_r($output);
                  die;  
                   
          }
          
        ini_set('max_execution_time', '0');
        if($request->token!=''){
            try{
                if($user = JWTAuth::authenticate($request->token)){
                    
                    $destinationtempfilePath=public_path().'/uploads/temp_file/' . $user->id;
                    if( !is_dir( $destinationtempfilePath ) ) mkdir( $destinationtempfilePath, 0755, true );
                    $txtfile = fopen($destinationtempfilePath."/video.txt", "w") or die("Unable to open file!");
                    
                    $destinationtempfileaudioPath=public_path().'/uploads/temp_file/' . $user->id;
                    if( !is_dir( $destinationtempfilePath ) ) mkdir( $destinationtempfileaudioPath, 0755, true );
                    $txtaudiofile = fopen($destinationtempfileaudioPath."/audio.txt", "w") or die("Unable to open file!");
                    
                 
                     
                    /*upload video 1*/  
                    
                      if(!empty($request->video1)){
                 
                       
                        $v11= $request->video1; ///env("APP_URL").'public/uploads/make_video/'.$user->id."/V1_1440*1024_".$fileNameToStore;
                    
                        $txt = "file ".$v11."\n";
                        fwrite($txtfile, $txt);
                        sleep(1);
                    }
                    
                    if(!empty($request->video2)){
                        
                     
                        
                        $v2= $request->video2;//env("APP_URL")."public".$video_url;
                        
                        $txt = "file ".$v2."\n";
                        fwrite($txtfile, $txt);    
                        
                       
                        sleep(1);
                    }
                 
                    
                  
                    
                  
                    
                    fclose($txtfile);
                    
                    fclose($txtaudiofile);
                  
                    $destinationVideoPath=public_path()."/uploads/final_video/".$user->id;
                    if( !is_dir( $destinationVideoPath ) ) mkdir( $destinationVideoPath, 0755, true );
                    
                    if(!empty($request->video_format)){
                        $video_url = "/uploads/final_video/".$user->id."/final_1440*1024_".time().'.'.$request->video_format;
                        $output_path= public_path().$video_url;
                    }else{ 
                        $video_url = "/uploads/final_video/".$user->id."/final_1440*1024_".time().'.mp4';
                        $output_path= public_path().$video_url;
                    }
                    
                    $video_file_path = public_path().'/uploads/temp_file/' . $user->id.'/video.txt';
                    
                    //$audio_file_path = public_path().'/uploads/temp_file/' . $user->id.'/audio.txt';
                    
                    if(!empty($request->ratio_name)){
                        $ratio=" -r 15 -aspect ".$request->ratio_name." -strict -2 ";
                        //-r 15 -vf scale=1080:-1 -aspect 1:1 -strict -2
                    }
                    else{
                        $ratio=" -r 15 -aspect 16:9 -strict -2 ";
                    }
                  //  echo $TEXTvideo;
                    
                    echo  $v11;
                    echo $v2;
                     //$output= shell_exec("ffmpeg -f concat -safe 0  -i $video_file_path $ratio   $output_path 2>&1");
                    $output=shell_exec("ffmpeg -i $v11 -i $v2 -filter_complex '[0:v] [0:a] [1:v] [1:a] concat=n=2:v=1:a=1[outv][outa]' -map '[outv]' -map '[outa]' $output_path 2>&1");
                     echo "<pre>";print_r($output);die;
                    
                    $vurl= env('APP_URL')."public".$video_url;
                    
                     
                    if(!empty($request->video_format)){
                        $videourl = "/uploads/final_video/".$user->id."/".time().'.'.$request->video_format;
                        $outputpath= public_path().$videourl;
                    }else{ 
                        $videourl = "/uploads/final_video/".$user->id."/".time().'.mp4';
                        $outputpath= public_path().$videourl;
                    }
                    
                    if ($request->hasFile('watermark')) {
                        $extension = $request->file('watermark')->getClientOriginalExtension();
                        // Filename To store
                        $fileNameToStore = time().'.'.$extension;
                        
                        Image::make($request->watermark)->resize(150, 100)->save(public_path('uploads/watermark/'.$user->id."/".$fileNameToStore));
                        
                        //$request->watermark->move(public_path('uploads/watermark/'.$user->id."/"), $fileNameToStore);
                        $logopath =env("APP_URL"). 'public/uploads/watermark/'.$user->id."/".$fileNameToStore;
                    
                        sleep(1);
                        
                        $output= shell_exec("ffmpeg -i $vurl -i $logopath -filter_complex 'overlay=x=(main_w-overlay_w-10):y=(main_h-overlay_h)/(main_h-overlay_h)' -codec:a copy $outputpath 2>&1");//top right
                       
                    }
                   
                   //echo $f= "ffmpeg -i $vurl -i $logopath -filter_complex 'overlay=(main_w-overlay_w)/2:(main_h-overlay_h)/2' -codec:a copy $outputpath 2>&1";die;
                   
                    //echo "ffmpeg -i $vurl -i $logopath -filter_complex 'overlay=(main_w-overlay_w)/2:(main_h-overlay_h)/2' -codec:a copy $output_path 2>&1";die;//middle
                    //$output= shell_exec("ffmpeg -i $vurl -i $logopath -filter_complex 'overlay=W-w-10:H-h-10' -codec:a copy $outputpath 2>&1");//bootom right
                    //$output= shell_exec("ffmpeg -i $vurl -i $logopath -filter_complex 'overlay=x=(main_w-overlay_w)/(main_w-overlay_w):y=(main_h-overlay_h)/(main_h-overlay_h)' -codec:a copy $outputpath 2>&1");//top left
                  
                     echo "<pre>";print_r($output);die;
                    //$output= shell_exec('ffmpeg -i "concat:$request->video1|$request->video2|$request->video3" -c copy $output_path 2>&1');

                    //$output= shell_exec("ffmpeg -f concat -safe 0 -i $file_path -f concat -safe 0 -i $file_path1 -r 25 -pix_fmt yuv420p  $ratio $output_path 2>&1");
                    
                    //$myvideo['user_id']= $user->id;
                    //$myvideo['video']= env('APP_URL')."/public/".$video_url;
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
    
    
    /******************************************************************Start Quiz module*******************************************////
    public function videoSizeList(Request $request){
        //dd($request);
        $this->validate($request, [
 
            'token' => 'required'

        ]);

 
        try{
            if($user = JWTAuth::authenticate($request->token)){
                $data= VideoSize::where('status','=',1)->get();
               
                $msg  = array('status'=>true,'message' => "Video Size get successfully",'data'=>$data);
                echo json_encode($msg);
            }else{
                $msg  = array('status'=>false,'message' => "User not found","data"=>[]);
                echo json_encode($msg);
            }
        }catch (JWTException $e) {

            $msg  = array('status'=>false,'message' => "Could Not Create Token.",'data'=>[]);
            echo json_encode($msg);
        }
    
    }
    
     public function quizRatioList(Request $request){
        
        if($request->token!=''){
            try{
                if($user = JWTAuth::authenticate($request->token)){
                    $data= QuizRatio::where(['status'=>1])->get();
                    $msg  = array('status'=>true,'message' => "Ratio listing get successfully",'data'=>$data);
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
    
    public function countryList(Request $request){
        //dd($request);
        $this->validate($request, [
 
            'token' => 'required'

        ]);

 
        try{
            if($user = JWTAuth::authenticate($request->token)){
                $data= Country::where('deleted_at','=','0')->skip($request->offset)->take($request->limit)->get();
               
                $msg  = array('status'=>true,'message' => "Country get successfully",'data'=>$data);
                echo json_encode($msg);
            }else{
                $msg  = array('status'=>false,'message' => "User not found","data"=>[]);
                echo json_encode($msg);
            }
        }catch (JWTException $e) {

            $msg  = array('status'=>false,'message' => "Could Not Create Token.",'data'=>[]);
            echo json_encode($msg);
        }
    
    }
    
    public function subjectList(Request $request){
        //dd($request);
        $this->validate($request, [
 
            'token' => 'required',
            'country'=>'required'

        ]);

 
        try{
            if($user = JWTAuth::authenticate($request->token)){
                $data= Subject::where('deleted_at','=','0')->where('country_id','=',$request->country)->skip($request->offset)->take($request->limit)->get();
               
                $msg  = array('status'=>true,'message' => "Subject get successfully",'data'=>$data);
                echo json_encode($msg);
            }else{
                $msg  = array('status'=>false,'message' => "User not found","data"=>[]);
                echo json_encode($msg);
            }
        }catch (JWTException $e) {

            $msg  = array('status'=>false,'message' => "Could Not Create Token.",'data'=>[]);
            echo json_encode($msg);
        }
    
    }
    
    public function topicList(Request $request){
        //dd($request);
        $this->validate($request, [
 
            'token' => 'required',
            'country'=>'required',
            'subject'=>'required'

        ]);

 
        try{
            if($user = JWTAuth::authenticate($request->token)){
                $data= Topic::where('deleted_at','=','0')
                            ->where('country_id','=',$request->country)
                            ->where('subject_id','=',$request->subject)
                            ->skip($request->offset)->take($request->limit)
                            ->get();
               
                $msg  = array('status'=>true,'message' => "Topic get successfully",'data'=>$data);
                echo json_encode($msg);
            }else{
                $msg  = array('status'=>false,'message' => "User not found","data"=>[]);
                echo json_encode($msg);
            }
        }catch (JWTException $e) {

            $msg  = array('status'=>false,'message' => "Could Not Create Token.",'data'=>[]);
            echo json_encode($msg);
        }
    
    }
    
    public function getQuizTemplate(Request $request){
        
        if($request->token!=''){
            try{
                if($user = JWTAuth::authenticate($request->token)){
                   
                    $topic =Topic::where("id",'=',$request->topic)->first();
                    $data= QuizTemplate::select('quiz_templates.*')->join('quiz_templates_type','quiz_templates.name','=','quiz_templates_type.id')->where(['quiz_templates.status'=>1,'ratio'=>$request->ratio,'option_type_id'=>$topic->option_type_id])->whereRaw("find_in_set('$request->topic',topic_id)")->groupBy('quiz_templates.pattern')->skip($request->offset)->take($request->limit)->get();
                    $msg  = array('status'=>true,'message' => "Template listing get successfully",'data'=>$data);
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
    
    public function quizTemplateList(Request $request){
        
        if($request->token!=''){
            try{
                if($user = JWTAuth::authenticate($request->token)){
                   
                    $data= QuizTemplate::where(['status'=>1,'pattern'=>$request->pattern])->whereRaw("find_in_set('$request->topic',topic_id)");
                    
                    if(!empty($request->ratio)){
                        $data= $data->where(['ratio'=>$request->ratio]);
                    }
                    $data= $data->skip($request->offset)->take($request->limit)->get();
        
                    $msg  = array('status'=>true,'message' => "Template listing get successfully",'data'=>$data);
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
    
    public function quizVoiceList(Request $request){
        
        if($request->token!=''){
            try{
                if($user = JWTAuth::authenticate($request->token)){
                   
                    $data= QuizVoice::where(['status'=>1,'country_id'=>$request->country]);
                    
                    $data= $data->get();
        
                    $msg  = array('status'=>true,'message' => "Voice listing get successfully",'data'=>$data);
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
    
    public function makeQuizVideoNew(Request $request){
        // dd($request);
        $output_path = '/home/readyvids/public_html/public/uploads/final_quiz_video/2/out5.mp4';
        $file_path = public_path().'/uploads/quiz_temp_file/2/input2.txt';
        $file_path1 = public_path().'/uploads/quiz_temp_file/2/input3.txt';
        $fontfile='/home/readyvids/public_html/public/OpenSans-Regular.ttf';
        $output= "ffmpeg -i https://readyvids.manageprojects.in//public//uploads/quiz_make_video/2/1681280808.mp4 -i $file_path  -vf drawtext=fontfile=$fontfile:text='%{eif\:trunc(mod(((-i $file_path1-t)/3600),24))\:d\:2} \:%{eif\:trunc(mod(((-i $file_path1-t)/60),60))\:d\:2} \:%{eif\:trunc(mod(-i $file_path1-t\,60))\:d\:2}':fontcolor=orange:fontsize=24:x=680: y=20:,-r 15 -c:v libx264 -pix_fmt yuv420p  $output_path 2>&1";
        //$output= shell_exec("ffmpeg -f concat -safe 0 -i $file_path -f concat -safe 0 -i $file_path1 -r 15 -c:v libx264 -pix_fmt yuv420p $ratio $output_path 2>&1");
        
        dd($output);
        
        ini_set('max_execution_time', '0');
        if($request->token!=''){
            try{
                if($user = JWTAuth::authenticate($request->token)){
                    
                    /*check which content already used in video*/
                    $content = QuizVideoContent::select('video_id')->where('user_id','=',$user->id)->distinct('video_id')->get();
                  
                    $totalcontent = count($content);
                    
                    $video_size_result= VideoSize::where('id','=',$request->video_size)->first();
                    
                    $data= QuizVideo::where(['quiz_video.status'=>1]);
                    if(!empty($request->country)){
                        $data= $data->where(['quiz_video.country_id'=>$request->country]);
                    }
                    if(!empty($request->subject)){
                        $data= $data->where(['quiz_video.subject_id'=>$request->subject]);
                    }
                    if(!empty($request->topic)){
                        $data= $data->where(['quiz_video.topic_id'=>$request->topic]);
                    }
                  
                    $data=$data->inRandomOrder();
                   
                    if(!empty($request->video_size)){
                        $data= $data->limit($video_size_result->display_video);
                    }
                   
                    if($data->count()>=$video_size_result->display_video){
                        if(($data->count()-$totalcontent)>$video_size_result->display_video || ($data->count()-$totalcontent)==$video_size_result->display_video){
                           $data= $data->whereNotIn('id',$content);
                        }
                        $data= $data->get();
                        //dd($data);
                        $template = QuizTemplate::findorfail($request->template_id);
                      
                       $destinationtempfilePath=public_path().'/uploads/quiz_temp_file/' . $user->id;
                        if( !is_dir( $destinationtempfilePath ) ) mkdir( $destinationtempfilePath, 0755, true );
                        $txtfile = fopen($destinationtempfilePath."/input.txt", "w") or die("Unable to open file!");
                        $soundfile = fopen($destinationtempfilePath."/input1.txt", "w") or die("Unable to open file!");
                        
                        $durationfile = fopen($destinationtempfilePath."/input2.txt", "w") or die("Unable to open file!");
                        $timerfile = fopen($destinationtempfilePath."/input3.txt", "w") or die("Unable to open file!");
                        
                        $audio_column=$request->voice;
                        
                        foreach($data as $key=>$video){
                            $videocontent['user_id']=$user->id;
                            $videocontent['video_id']=$video->id;
                            QuizVideoContent::create($videocontent);
                           // $video_text = VideoTextMapping::join('video_text','video_text.id','=','video_text_mapping.text_id')->where('video_text_mapping.video_id','=',$video->id)->get();
                            $video_html = $template->template_html_string;
                            
                            $answerbackgroundcolor = $template->answerbackgroundcolor;
                            
                            // $template_type= explode(' ',$video->templatetype);
                            // $lineno = $template_type[0];
                            // foreach($video_text as $key=>$text){
                            //     $counter= $key+1;
                               
                            //     $searchtext = '{text'.$counter.'}';
                            $question_number= $key+1;
                             $question_number= $question_number.'/'.$video_size_result->display_video;
                            if (strpos($video_html,"{question_number}") !== false) {
                               $video_html= str_replace('{question_number}',$question_number,$video_html);
                            }
                            
                            if (strpos($video_html,"{openbody}") !== false) {
                               $video_html= str_replace('{openbody}',"<body style='",$video_html);
                            }
                            
                            if (strpos($video_html,"{closebody}") !== false) {
                               $video_html= str_replace('{closebody}',"'>",$video_html);
                            }
                            
                            if (strpos($video_html,"{endbody}") !== false) {
                               $video_html= str_replace('{endbody}',"</body>",$video_html);
                            }
                            
                            if (strpos($video_html,'{question}') !== false) {
                                if(isset($video->question)){
                                     
                                    $video_html= str_replace('{question}',$video->question,$video_html);
                                     
                                    $video_html1=$video_html;
                                     
                                    $video_html1= str_replace('{option1}','',$video_html1);
                                    $video_html1= str_replace('{option2}','',$video_html1);
                                    $video_html1= str_replace('{option3}','',$video_html1);
                                    $video_html1= str_replace('{option4}','',$video_html1);
                                    
                                    $options = new Options();
                                    $options->setIsRemoteEnabled(true);
                                    $dompdf = new Dompdf($options);
                                    $dompdf->loadHtml( $video_html1); 
                                      
                                    // (Optional) Setup the paper size and orientation
                                    if(!empty($request->ratio_name) && $request->ratio_name=="16:9"){
                                        $customPaper = array(0,0,1440,1024);
                                    }
                                    if(!empty($request->ratio_name) && $request->ratio_name=="9:16"){
                                          $customPaper = array(0,0,600,800);
                                    }
                                    
                                    // $customPaper = array(0,0,2048,1152);
                                    // $customPaper = array(0,0,2048,1152);
                                    $dompdf->setPaper($customPaper);
                                    $dompdf->curlAllowUnsafeSslRequests = true;
                                    
                        
                                    
                                    // Render the HTML as PDF
                                    $dompdf->render();
                                  // $pdfname= 'Brochure'.time().'.pdf';
                                    
                                    $pdfpath = "uploads/quiz_temp_pdf/".$user->id;
                                    $pdf_url = $pdfpath."/".time().'.pdf';
                                    $pdf_path=public_path()."/".$pdf_url;
                                    $destinationpdfPath = public_path($pdfpath);   
                                    if( !is_dir( $destinationpdfPath ) ) mkdir( $destinationpdfPath, 0755, true );
                                    
                                    file_put_contents($pdf_path, $dompdf->output());
                                     
                                    $imagick = new \Imagick();
                                    $imagick->readImage($pdf_path);
                                    $imagepath = "uploads/quiz_temp_image/".$user->id;
                                    $image_url = $imagepath."/".time().'.jpg';
                                    $image_path=public_path()."/".$image_url;
                                     $destinationPath = public_path($imagepath);   
                                    if( !is_dir( $destinationPath ) ) mkdir( $destinationPath, 0755, true );
                                    
                                    $imagick->writeImages($image_path, true);
                                    
                                      
                                    if(file_exists($image_path)) { 
                                        $txt = "file ".$image_path."\n";
                                    }else{
                                         $txt = "file ".public_path('uploads/design.jpg')." \n";
                                    }
                                    fwrite($txtfile, $txt);
                                    $txt = "duration 1 \n";
                                    fwrite($txtfile, $txt);
                                    
                                    $txt = "file ".public_path()."/1-second-of-silence.mp3 \n";
                                    fwrite($soundfile, $txt);
                                    $txt = "outpoint 1 \n";
                                    fwrite($soundfile, $txt);
                                }
                              
                            }
                            
                            if (strpos($video_html,'{option1}') !== false) {
                                if(isset($video->option1)){
                                     $video_html= str_replace('{option1}',$video->option1,$video_html);
                                     
                                     $video_html1=$video_html;
                                     
                                 
                                    $video_html1= str_replace('{option2}','',$video_html1);
                                    $video_html1= str_replace('{option3}','',$video_html1);
                                    $video_html1= str_replace('{option4}','',$video_html1);
                                    
                                    $options = new Options();
                                    $options->setIsRemoteEnabled(true);
                                    $dompdf = new Dompdf($options);
                                    $dompdf->loadHtml( $video_html1); 
                                      
                                    // (Optional) Setup the paper size and orientation
                                    if(!empty($request->ratio_name) && $request->ratio_name=="16:9"){
                                        $customPaper = array(0,0,1440,1024);
                                    }
                                    if(!empty($request->ratio_name) && $request->ratio_name=="9:16"){
                                          $customPaper = array(0,0,600,800);
                                    }
                                    
                                    // $customPaper = array(0,0,2048,1152);
                                    // $customPaper = array(0,0,2048,1152);
                                    $dompdf->setPaper($customPaper);
                                    $dompdf->curlAllowUnsafeSslRequests = true;
                                    
                        
                                    
                                    // Render the HTML as PDF
                                    $dompdf->render();
                                  // $pdfname= 'Brochure'.time().'.pdf';
                                    
                                    $pdfpath = "uploads/quiz_temp_pdf/".$user->id;
                                    $pdf_url = $pdfpath."/".time().'.pdf';
                                    $pdf_path=public_path()."/".$pdf_url;
                                    $destinationpdfPath = public_path($pdfpath);   
                                    if( !is_dir( $destinationpdfPath ) ) mkdir( $destinationpdfPath, 0755, true );
                                    
                                    file_put_contents($pdf_path, $dompdf->output());
                                     
                                    $imagick = new \Imagick();
                                    $imagick->readImage($pdf_path);
                                    $imagepath = "uploads/quiz_temp_image/".$user->id;
                                    $image_url = $imagepath."/".time().'.jpg';
                                    $image_path=public_path()."/".$image_url;
                                     $destinationPath = public_path($imagepath);   
                                    if( !is_dir( $destinationPath ) ) mkdir( $destinationPath, 0755, true );
                                    
                                    $imagick->writeImages($image_path, true);
                                    
                                      
                                    if(file_exists($image_path)) { 
                                        $txt = "file ".$image_path."\n";
                                    }else{
                                         $txt = "file ".public_path('uploads/design.jpg')." \n";
                                    }
                                    fwrite($txtfile, $txt);
                                    $txt = "duration 1 \n";
                                    fwrite($txtfile, $txt);
                                    
                                    $txt = "file ".public_path()."/1-second-of-silence.mp3 \n";
                                    fwrite($soundfile, $txt);
                                    $txt = "outpoint 1 \n";
                                    fwrite($soundfile, $txt);
                                }
                              
                            }
                            
                            if (strpos($video_html,'{option2}') !== false) {
                                if(isset($video->option2)){
                                     $video_html= str_replace('{option2}',$video->option2,$video_html);
                                     
                                    $video_html1=$video_html;
                                     
                                    $video_html1= str_replace('{option3}','',$video_html1);
                                    $video_html1= str_replace('{option4}','',$video_html1);
                                    
                                    $options = new Options();
                                    $options->setIsRemoteEnabled(true);
                                    $dompdf = new Dompdf($options);
                                    $dompdf->loadHtml( $video_html1); 
                                      
                                    // (Optional) Setup the paper size and orientation
                                    if(!empty($request->ratio_name) && $request->ratio_name=="16:9"){
                                        $customPaper = array(0,0,1440,1024);
                                    }
                                    if(!empty($request->ratio_name) && $request->ratio_name=="9:16"){
                                          $customPaper = array(0,0,600,800);
                                    }
                                    
                                    // $customPaper = array(0,0,2048,1152);
                                    // $customPaper = array(0,0,2048,1152);
                                    $dompdf->setPaper($customPaper);
                                    $dompdf->curlAllowUnsafeSslRequests = true;
                                    
                        
                                    
                                    // Render the HTML as PDF
                                    $dompdf->render();
                                  // $pdfname= 'Brochure'.time().'.pdf';
                                    
                                    $pdfpath = "uploads/quiz_temp_pdf/".$user->id;
                                    $pdf_url = $pdfpath."/".time().'.pdf';
                                    $pdf_path=public_path()."/".$pdf_url;
                                    $destinationpdfPath = public_path($pdfpath);   
                                    if( !is_dir( $destinationpdfPath ) ) mkdir( $destinationpdfPath, 0755, true );
                                    
                                    file_put_contents($pdf_path, $dompdf->output());
                                     
                                    $imagick = new \Imagick();
                                    $imagick->readImage($pdf_path);
                                    $imagepath = "uploads/quiz_temp_image/".$user->id;
                                    $image_url = $imagepath."/".time().'.jpg';
                                    $image_path=public_path()."/".$image_url;
                                     $destinationPath = public_path($imagepath);   
                                    if( !is_dir( $destinationPath ) ) mkdir( $destinationPath, 0755, true );
                                    
                                    $imagick->writeImages($image_path, true);
                                    
                                      
                                    if(file_exists($image_path)) { 
                                        $txt = "file ".$image_path."\n";
                                    }else{
                                         $txt = "file ".public_path('uploads/design.jpg')." \n";
                                    }
                                    fwrite($txtfile, $txt);
                                    $txt = "duration 1 \n";
                                    fwrite($txtfile, $txt);
                                    
                                    $txt = "file ".public_path()."/1-second-of-silence.mp3 \n";
                                    fwrite($soundfile, $txt);
                                    $txt = "outpoint 1 \n";
                                    fwrite($soundfile, $txt);
                                }
                              
                            }
                            
                            if (strpos($video_html,'{option3}') !== false) {
                                if(isset($video->option3)){
                                     $video_html= str_replace('{option3}',$video->option3,$video_html);
                                     
                                    $video_html1=$video_html;
                                     
                                    $video_html1= str_replace('{option4}','',$video_html1);
                                    
                                    $options = new Options();
                                    $options->setIsRemoteEnabled(true);
                                    $dompdf = new Dompdf($options);
                                    $dompdf->loadHtml( $video_html1); 
                                      
                                    // (Optional) Setup the paper size and orientation
                                    if(!empty($request->ratio_name) && $request->ratio_name=="16:9"){
                                        $customPaper = array(0,0,1440,1024);
                                    }
                                    if(!empty($request->ratio_name) && $request->ratio_name=="9:16"){
                                          $customPaper = array(0,0,600,800);
                                    }
                                    
                                    // $customPaper = array(0,0,2048,1152);
                                    // $customPaper = array(0,0,2048,1152);
                                    $dompdf->setPaper($customPaper);
                                    $dompdf->curlAllowUnsafeSslRequests = true;
                                    
                        
                                    
                                    // Render the HTML as PDF
                                    $dompdf->render();
                                  // $pdfname= 'Brochure'.time().'.pdf';
                                    
                                    $pdfpath = "uploads/quiz_temp_pdf/".$user->id;
                                    $pdf_url = $pdfpath."/".time().'.pdf';
                                    $pdf_path=public_path()."/".$pdf_url;
                                    $destinationpdfPath = public_path($pdfpath);   
                                    if( !is_dir( $destinationpdfPath ) ) mkdir( $destinationpdfPath, 0755, true );
                                    
                                    file_put_contents($pdf_path, $dompdf->output());
                                     
                                    $imagick = new \Imagick();
                                    $imagick->readImage($pdf_path);
                                    $imagepath = "uploads/quiz_temp_image/".$user->id;
                                    $image_url = $imagepath."/".time().'.jpg';
                                    $image_path=public_path()."/".$image_url;
                                     $destinationPath = public_path($imagepath);   
                                    if( !is_dir( $destinationPath ) ) mkdir( $destinationPath, 0755, true );
                                    
                                    $imagick->writeImages($image_path, true);
                                    
                                      
                                    if(file_exists($image_path)) { 
                                        $txt = "file ".$image_path."\n";
                                    }else{
                                         $txt = "file ".public_path('uploads/design.jpg')." \n";
                                    }
                                    fwrite($txtfile, $txt);
                                    $txt = "duration 1 \n";
                                    fwrite($txtfile, $txt);
                                    
                                    $txt = "file ".public_path()."/1-second-of-silence.mp3 \n";
                                    fwrite($soundfile, $txt);
                                    $txt = "outpoint 1 \n";
                                    fwrite($soundfile, $txt);
                                     
                                }
                              
                            }
                            
                            if (strpos($video_html,'{option4}') !== false) {
                                if(isset($video->option4)){
                                     $video_html= str_replace('{option4}',$video->option4,$video_html);
                                }
                              
                            }
                                
                      
                            
                         
                            //echo $video_html;die;
                             //$video->video_html=$video_html;
                            
                            /***********************set question ***********************/
                             $options = new Options();
                             $options->setIsRemoteEnabled(true);
                             $dompdf = new Dompdf($options);
                             $dompdf->loadHtml( $video_html); 
                              
                            // (Optional) Setup the paper size and orientation
                            if(!empty($request->ratio_name) && $request->ratio_name=="16:9"){
                                $customPaper = array(0,0,1440,1024);
                            }
                            if(!empty($request->ratio_name) && $request->ratio_name=="9:16"){
                                   $customPaper = array(0,0,600,800);
                            }
                            
                            // $customPaper = array(0,0,2048,1152);
                            // $customPaper = array(0,0,2048,1152);
                            $dompdf->setPaper($customPaper);
                            $dompdf->curlAllowUnsafeSslRequests = true;
                            
                
                            
                            // Render the HTML as PDF
                            $dompdf->render();
                           // $pdfname= 'Brochure'.time().'.pdf';
                            
                            $pdfpath = "uploads/quiz_temp_pdf/".$user->id;
                            $pdf_url = $pdfpath."/".time().'.pdf';
                            $pdf_path=public_path()."/".$pdf_url;
                            $destinationpdfPath = public_path($pdfpath);   
                            if( !is_dir( $destinationpdfPath ) ) mkdir( $destinationpdfPath, 0755, true );
                            
                            file_put_contents($pdf_path, $dompdf->output());
                             
                            $imagick = new \Imagick();
                            $imagick->readImage($pdf_path);
                            $imagepath = "uploads/quiz_temp_image/".$user->id;
                            $image_url = $imagepath."/".time().'.jpg';
                            $image_path=public_path()."/".$image_url;
                             $destinationPath = public_path($imagepath);   
                            if( !is_dir( $destinationPath ) ) mkdir( $destinationPath, 0755, true );
                            
                            $imagick->writeImages($image_path, true);
                            
                           
                           $audio_path = public_path().'/'.$video->$audio_column;
                            
                            $duration = shell_exec("ffprobe -i $audio_path -show_entries format=duration");
                            $duration = str_replace("\n", "", $duration);
                           
                           
                           $duration = str_replace("[FORMAT]", "", $duration);
                           $duration = str_replace("[/FORMAT]", "", $duration);
                           $duration = str_replace("duration=", "", $duration);
                            
                          
                            if(file_exists($image_path)) { 
                                $txt = "file ".$image_path."\n";
                            }else{
                                 $txt = "file ".public_path('uploads/design.jpg')." \n";
                            }
                            fwrite($txtfile, $txt);
                            $txt = "duration ".$duration." \n";
                            fwrite($txtfile, $txt);
    
                            
                            $txt = "file ".public_path()."/".$video->$audio_column."\n";
                            fwrite($soundfile, $txt);
                            $txt = "outpoint ".$duration." \n";
                            fwrite($soundfile, $txt);
                            
                           
                           
                           /*************************set quetion********************************/
                           
                            sleep(2);
                            
                            
                           /*******************display answer***************/
                           
                            if($video->answer==$video->option1){
                                
                                if (strpos($video_html,"{answeroption1}") !== false) {
                                    $video_html= str_replace('{answeroption1}',"background-color:".$answerbackgroundcolor,$video_html);
                                }
                            
                            }
                            if($video->answer==$video->option2){
                                if (strpos($video_html,"{answeroption2}") !== false) {
                                    $video_html= str_replace('{answeroption2}',"background-color:".$answerbackgroundcolor,$video_html);
                                }
                               
                            }
                            if($video->answer==$video->option3){
                               if (strpos($video_html,"{answeroption3}") !== false) {
                                    $video_html= str_replace('{answeroption3}',"background-color:".$answerbackgroundcolor,$video_html);
                                }
                            }
                            if($video->answer==$video->option4){
                               if (strpos($video_html,"{answeroption4}") !== false) {
                                    $video_html= str_replace('{answeroption4}',"background-color:".$answerbackgroundcolor,$video_html);
                                }
                            }
                           
                             $options = new Options();
                             $options->setIsRemoteEnabled(true);
                             $dompdf = new Dompdf($options);
                             $dompdf->loadHtml( $video_html); 
                              
                            // (Optional) Setup the paper size and orientation
                            if(!empty($request->ratio_name) && $request->ratio_name=="16:9"){
                                $customPaper = array(0,0,1440,1024);
                            }
                            if(!empty($request->ratio_name) && $request->ratio_name=="9:16"){
                                   $customPaper = array(0,0,600,800);
                            }
                            
                            // $customPaper = array(0,0,2048,1152);
                            // $customPaper = array(0,0,2048,1152);
                            $dompdf->setPaper($customPaper);
                            $dompdf->curlAllowUnsafeSslRequests = true;
                            
                
                            
                            // Render the HTML as PDF
                            $dompdf->render();
                           // $pdfname= 'Brochure'.time().'.pdf';
                            
                            $pdfpath = "uploads/quiz_temp_pdf/".$user->id;
                            $pdf_url = $pdfpath."/".time().'.pdf';
                            $pdf_path=public_path()."/".$pdf_url;
                            $destinationpdfPath = public_path($pdfpath);   
                            if( !is_dir( $destinationpdfPath ) ) mkdir( $destinationpdfPath, 0755, true );
                            
                            file_put_contents($pdf_path, $dompdf->output());
                             
                            $imagick = new \Imagick();
                            $imagick->readImage($pdf_path);
                            $imagepath = "uploads/quiz_temp_image/".$user->id;
                            $image_url = $imagepath."/".time().'.jpg';
                            $image_path=public_path()."/".$image_url;
                             $destinationPath = public_path($imagepath);   
                            if( !is_dir( $destinationPath ) ) mkdir( $destinationPath, 0755, true );
                            
                            $imagick->writeImages($image_path, true);
                            
                            $answeraudio_column="answer_".$audio_column;
                           
                           $answer_audio_path = public_path().'/'.$video->$answeraudio_column;
                            
                            $answer_duration = shell_exec("ffprobe -i $answer_audio_path -show_entries format=duration");
                            $answer_duration = str_replace("\n", "", $answer_duration);
                           
                           
                           $answer_duration = str_replace("[FORMAT]", "", $answer_duration);
                           $answer_duration = str_replace("[/FORMAT]", "", $answer_duration);
                           $answer_duration = str_replace("duration=", "", $answer_duration);
                            
                          
                            if(file_exists($image_path)) { 
                                $txt = "file ".$image_path."\n";
                            }else{
                                 $txt = "file ".public_path('uploads/design.jpg')." \n";
                            }
                            fwrite($txtfile, $txt);
                            $txt = "duration ".$answer_duration." \n";
                            fwrite($txtfile, $txt);
    
                            
                            $txt = "file ".public_path()."/".$video->$answeraudio_column."\n";
                            fwrite($soundfile, $txt);
                            $txt = "outpoint ".$answer_duration." \n";
                            fwrite($soundfile, $txt);
                           
                           /*************************************/
                            
                            sleep(2);
                        }
                       
                        fclose($txtfile);
    
                        fclose($soundfile);
                       
                       
                        
                         $file_path = public_path().'/uploads/quiz_temp_file/' . $user->id.'/input.txt';
                        $file_path1 = public_path().'/uploads/quiz_temp_file/' . $user->id.'/input1.txt';
                        
                         $destinationVideoPath=public_path()."/uploads/quiz_make_video/".$user->id;
                        if( !is_dir( $destinationVideoPath ) ) mkdir( $destinationVideoPath, 0755, true );
                        
                        if(!empty($request->video_format)){
                            $video_url = "/uploads/quiz_make_video/".$user->id."/".time().'.'.$request->video_format;
                            $output_path= public_path().$video_url;
                        }else{ 
                            $video_url = "/uploads/quiz_make_video/".$user->id."/".time().'.mp4';
                            $output_path= public_path().$video_url;
                        }
    
                        if(!empty($request->ratio_name)){
                            $ratio=" -r 15 -aspect ".$request->ratio_name." -strict -2";
                            //-r 15 -vf scale=1080:-1 -aspect 1:1 -strict -2
                        }
                        
                        
                        //$output= shell_exec("ffmpeg -f concat -i $file_path -f concat -i $file_path1 -r 25 -pix_fmt yuv420p  $output_path 2>&1");
                        if(!empty($request->ratio_name) && $request->ratio_name=="16:9"){
                            $output= shell_exec("ffmpeg -f concat -safe 0 -i $file_path -f concat -safe 0 -i $file_path1 -r 15 -c:v libx264 -pix_fmt yuv420p $ratio $output_path 2>&1");
                        }
                         if(!empty($request->ratio_name) && $request->ratio_name=="9:16"){
                             $output= shell_exec("ffmpeg -f concat -safe 0 -i $file_path -f concat -safe 0 -i $file_path1 -r 15 -c:v libx264 -pix_fmt yuv420p $output_path 2>&1");
                        }
                       
                        //               echo "<pre>";
                        // print_r($output);die;
                        //echo env('APP_URL')."/public/".$video_url;die;
                        $myvideo['user_id']= $user->id;
                        $myvideo['video']= env('APP_URL')."/public/".$video_url;
                        $result['video']=MyQuizVideo::create($myvideo);
                        
                        $size = filesize($output_path)/1024;
                        $result['size']= number_format($size, 2, '.', '');
                        $msg  = array('status'=>true,'message' => "Video created successfully",'data'=>$result);
                        echo json_encode($msg);
                    }
                    else{
                         $msg  = array('status'=>false,'message' => "Video is not created successfully because data is not available regarding your parameter",'data'=>[]);
                        echo json_encode($msg);
                    }
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
    
    /**27-04-2023*/
    public function makeQuizVideoOld(Request $request){
        // dd($request);
        ini_set('max_execution_time', '0');
        if($request->token!=''){
            try{
                if($user = JWTAuth::authenticate($request->token)){
                    
                    /*check which content already used in video*/
                    $content = QuizVideoContent::select('video_id')->where('user_id','=',$user->id)->distinct('video_id')->get();
                  
                    $totalcontent = count($content);
                    
                    $video_size_result= VideoSize::where('id','=',$request->video_size)->first();
                    
                    $data= QuizVideo::where(['quiz_video.status'=>1]);
                    if(!empty($request->country)){
                        $data= $data->where(['quiz_video.country_id'=>$request->country]);
                    }
                    if(!empty($request->subject)){
                        $data= $data->where(['quiz_video.subject_id'=>$request->subject]);
                    }
                    if(!empty($request->topic)){
                        $data= $data->where(['quiz_video.topic_id'=>$request->topic]);
                    }
                  
                    $data=$data->inRandomOrder();
                   
                    if(!empty($request->video_size)){
                        $data= $data->limit($video_size_result->display_video);
                    }
                   
                    if($data->count()>=$video_size_result->display_video){
                        if(($data->count()-$totalcontent)>$video_size_result->display_video || ($data->count()-$totalcontent)==$video_size_result->display_video){
                           $data= $data->whereNotIn('id',$content);
                        }
                        $data= $data->get();
                        //dd($data);
                        $template = QuizTemplate::findorfail($request->template_id);
                      
                       $destinationtempfilePath=public_path().'/uploads/quiz_temp_file/' . $user->id;
                        if( !is_dir( $destinationtempfilePath ) ) mkdir( $destinationtempfilePath, 0755, true );
                        $txtfile = fopen($destinationtempfilePath."/input.txt", "w") or die("Unable to open file!");
                        $soundfile = fopen($destinationtempfilePath."/input1.txt", "w") or die("Unable to open file!");
                        
                        $audio_column=$request->voice;
                        $cmd='[in]';
                        $starttime = 0;
                        $endtime = 0;
                        foreach($data as $key=>$video){
                            $videocontent['user_id']=$user->id;
                            $videocontent['video_id']=$video->id;
                            QuizVideoContent::create($videocontent);
                           // $video_text = VideoTextMapping::join('video_text','video_text.id','=','video_text_mapping.text_id')->where('video_text_mapping.video_id','=',$video->id)->get();
                            $video_html = $template->template_html_string;
                            
                            $answerbackgroundcolor = $template->answerbackgroundcolor;
                            
                            // $template_type= explode(' ',$video->templatetype);
                            // $lineno = $template_type[0];
                            // foreach($video_text as $key=>$text){
                            //     $counter= $key+1;
                               
                            //     $searchtext = '{text'.$counter.'}';
                            $question_number= $key+1;
                             $question_number= $question_number.'/'.$video_size_result->display_video;
                            if (strpos($video_html,"{question_number}") !== false) {
                               $video_html= str_replace('{question_number}',$question_number,$video_html);
                            }
                            
                            if (strpos($video_html,"{openbody}") !== false) {
                               $video_html= str_replace('{openbody}',"<body style='",$video_html);
                            }
                            
                            if (strpos($video_html,"{closebody}") !== false) {
                               $video_html= str_replace('{closebody}',"'>",$video_html);
                            }
                            
                            if (strpos($video_html,"{endbody}") !== false) {
                               $video_html= str_replace('{endbody}',"</body>",$video_html);
                            }
                            
                            if (strpos($video_html,'{question}') !== false) {
                                if(isset($video->question)){
                                     
                                    $video_html= str_replace('{question}',$video->question,$video_html);
                                     
                                    $video_html1=$video_html;
                                     
                                    $video_html1= str_replace('{option1}','',$video_html1);
                                    $video_html1= str_replace('{option2}','',$video_html1);
                                    $video_html1= str_replace('{option3}','',$video_html1);
                                    $video_html1= str_replace('{option4}','',$video_html1);
                                    
                                    $options = new Options();
                                    $options->setIsRemoteEnabled(true);
                                    $dompdf = new Dompdf($options);
                                    $dompdf->loadHtml( $video_html1); 
                                      
                                    // (Optional) Setup the paper size and orientation
                                    if(!empty($request->ratio_name) && $request->ratio_name=="16:9"){
                                        $customPaper = array(0,0,1440,1024);
                                    }
                                    if(!empty($request->ratio_name) && $request->ratio_name=="9:16"){
                                          $customPaper = array(0,0,600,800);
                                    }
                                    
                                    // $customPaper = array(0,0,2048,1152);
                                    // $customPaper = array(0,0,2048,1152);
                                    $dompdf->setPaper($customPaper);
                                    $dompdf->curlAllowUnsafeSslRequests = true;
                                    
                        
                                    
                                    // Render the HTML as PDF
                                    $dompdf->render();
                                  // $pdfname= 'Brochure'.time().'.pdf';
                                    
                                    $pdfpath = "uploads/quiz_temp_pdf/".$user->id;
                                    $pdf_url = $pdfpath."/".time().'.pdf';
                                    $pdf_path=public_path()."/".$pdf_url;
                                    $destinationpdfPath = public_path($pdfpath);   
                                    if( !is_dir( $destinationpdfPath ) ) mkdir( $destinationpdfPath, 0755, true );
                                    
                                    file_put_contents($pdf_path, $dompdf->output());
                                     
                                    $imagick = new \Imagick();
                                    $imagick->readImage($pdf_path);
                                    $imagepath = "uploads/quiz_temp_image/".$user->id;
                                    $image_url = $imagepath."/".time().'.jpg';
                                    $image_path=public_path()."/".$image_url;
                                     $destinationPath = public_path($imagepath);   
                                    if( !is_dir( $destinationPath ) ) mkdir( $destinationPath, 0755, true );
                                    
                                    $imagick->writeImages($image_path, true);
                                    
                                      
                                    if(file_exists($image_path)) { 
                                        $txt = "file ".$image_path."\n";
                                    }else{
                                         $txt = "file ".public_path('uploads/design.jpg')." \n";
                                    }
                                    fwrite($txtfile, $txt);
                                    $txt = "duration 1 \n";
                                    fwrite($txtfile, $txt);
                                    
                                    $txt = "file ".public_path()."/1-second-of-silence.mp3 \n";
                                    fwrite($soundfile, $txt);
                                    $txt = "outpoint 1 \n";
                                    fwrite($soundfile, $txt);
                                }
                              
                            }
                            
                            if (strpos($video_html,'{option1}') !== false) {
                                if(isset($video->option1)){
                                     $video_html= str_replace('{option1}',$video->option1,$video_html);
                                     
                                     $video_html1=$video_html;
                                     
                                 
                                    $video_html1= str_replace('{option2}','',$video_html1);
                                    $video_html1= str_replace('{option3}','',$video_html1);
                                    $video_html1= str_replace('{option4}','',$video_html1);
                                    
                                    $options = new Options();
                                    $options->setIsRemoteEnabled(true);
                                    $dompdf = new Dompdf($options);
                                    $dompdf->loadHtml( $video_html1); 
                                      
                                    // (Optional) Setup the paper size and orientation
                                    if(!empty($request->ratio_name) && $request->ratio_name=="16:9"){
                                        $customPaper = array(0,0,1440,1024);
                                    }
                                    if(!empty($request->ratio_name) && $request->ratio_name=="9:16"){
                                          $customPaper = array(0,0,600,800);
                                    }
                                    
                                    // $customPaper = array(0,0,2048,1152);
                                    // $customPaper = array(0,0,2048,1152);
                                    $dompdf->setPaper($customPaper);
                                    $dompdf->curlAllowUnsafeSslRequests = true;
                                    
                        
                                    
                                    // Render the HTML as PDF
                                    $dompdf->render();
                                  // $pdfname= 'Brochure'.time().'.pdf';
                                    
                                    $pdfpath = "uploads/quiz_temp_pdf/".$user->id;
                                    $pdf_url = $pdfpath."/".time().'.pdf';
                                    $pdf_path=public_path()."/".$pdf_url;
                                    $destinationpdfPath = public_path($pdfpath);   
                                    if( !is_dir( $destinationpdfPath ) ) mkdir( $destinationpdfPath, 0755, true );
                                    
                                    file_put_contents($pdf_path, $dompdf->output());
                                     
                                    $imagick = new \Imagick();
                                    $imagick->readImage($pdf_path);
                                    $imagepath = "uploads/quiz_temp_image/".$user->id;
                                    $image_url = $imagepath."/".time().'.jpg';
                                    $image_path=public_path()."/".$image_url;
                                     $destinationPath = public_path($imagepath);   
                                    if( !is_dir( $destinationPath ) ) mkdir( $destinationPath, 0755, true );
                                    
                                    $imagick->writeImages($image_path, true);
                                    
                                      
                                    if(file_exists($image_path)) { 
                                        $txt = "file ".$image_path."\n";
                                    }else{
                                         $txt = "file ".public_path('uploads/design.jpg')." \n";
                                    }
                                    fwrite($txtfile, $txt);
                                    $txt = "duration 1 \n";
                                    fwrite($txtfile, $txt);
                                    
                                    $txt = "file ".public_path()."/1-second-of-silence.mp3 \n";
                                    fwrite($soundfile, $txt);
                                    $txt = "outpoint 1 \n";
                                    fwrite($soundfile, $txt);
                                }
                              
                            }
                            
                            if (strpos($video_html,'{option2}') !== false) {
                                if(isset($video->option2)){
                                     $video_html= str_replace('{option2}',$video->option2,$video_html);
                                     
                                    $video_html1=$video_html;
                                     
                                    $video_html1= str_replace('{option3}','',$video_html1);
                                    $video_html1= str_replace('{option4}','',$video_html1);
                                    
                                    $options = new Options();
                                    $options->setIsRemoteEnabled(true);
                                    $dompdf = new Dompdf($options);
                                    $dompdf->loadHtml( $video_html1); 
                                      
                                    // (Optional) Setup the paper size and orientation
                                    if(!empty($request->ratio_name) && $request->ratio_name=="16:9"){
                                        $customPaper = array(0,0,1440,1024);
                                    }
                                    if(!empty($request->ratio_name) && $request->ratio_name=="9:16"){
                                          $customPaper = array(0,0,600,800);
                                    }
                                    
                                    // $customPaper = array(0,0,2048,1152);
                                    // $customPaper = array(0,0,2048,1152);
                                    $dompdf->setPaper($customPaper);
                                    $dompdf->curlAllowUnsafeSslRequests = true;
                                    
                        
                                    
                                    // Render the HTML as PDF
                                    $dompdf->render();
                                  // $pdfname= 'Brochure'.time().'.pdf';
                                    
                                    $pdfpath = "uploads/quiz_temp_pdf/".$user->id;
                                    $pdf_url = $pdfpath."/".time().'.pdf';
                                    $pdf_path=public_path()."/".$pdf_url;
                                    $destinationpdfPath = public_path($pdfpath);   
                                    if( !is_dir( $destinationpdfPath ) ) mkdir( $destinationpdfPath, 0755, true );
                                    
                                    file_put_contents($pdf_path, $dompdf->output());
                                     
                                    $imagick = new \Imagick();
                                    $imagick->readImage($pdf_path);
                                    $imagepath = "uploads/quiz_temp_image/".$user->id;
                                    $image_url = $imagepath."/".time().'.jpg';
                                    $image_path=public_path()."/".$image_url;
                                     $destinationPath = public_path($imagepath);   
                                    if( !is_dir( $destinationPath ) ) mkdir( $destinationPath, 0755, true );
                                    
                                    $imagick->writeImages($image_path, true);
                                    
                                      
                                    if(file_exists($image_path)) { 
                                        $txt = "file ".$image_path."\n";
                                    }else{
                                         $txt = "file ".public_path('uploads/design.jpg')." \n";
                                    }
                                    fwrite($txtfile, $txt);
                                    $txt = "duration 1 \n";
                                    fwrite($txtfile, $txt);
                                    
                                    $txt = "file ".public_path()."/1-second-of-silence.mp3 \n";
                                    fwrite($soundfile, $txt);
                                    $txt = "outpoint 1 \n";
                                    fwrite($soundfile, $txt);
                                }
                              
                            }
                            
                            if (strpos($video_html,'{option3}') !== false) {
                                if(isset($video->option3)){
                                     $video_html= str_replace('{option3}',$video->option3,$video_html);
                                     
                                    $video_html1=$video_html;
                                     
                                    $video_html1= str_replace('{option4}','',$video_html1);
                                    
                                    $options = new Options();
                                    $options->setIsRemoteEnabled(true);
                                    $dompdf = new Dompdf($options);
                                    $dompdf->loadHtml( $video_html1); 
                                      
                                    // (Optional) Setup the paper size and orientation
                                    if(!empty($request->ratio_name) && $request->ratio_name=="16:9"){
                                        $customPaper = array(0,0,1440,1024);
                                    }
                                    if(!empty($request->ratio_name) && $request->ratio_name=="9:16"){
                                          $customPaper = array(0,0,600,800);
                                    }
                                    
                                    // $customPaper = array(0,0,2048,1152);
                                    // $customPaper = array(0,0,2048,1152);
                                    $dompdf->setPaper($customPaper);
                                    $dompdf->curlAllowUnsafeSslRequests = true;
                                    
                        
                                    
                                    // Render the HTML as PDF
                                    $dompdf->render();
                                  // $pdfname= 'Brochure'.time().'.pdf';
                                    
                                    $pdfpath = "uploads/quiz_temp_pdf/".$user->id;
                                    $pdf_url = $pdfpath."/".time().'.pdf';
                                    $pdf_path=public_path()."/".$pdf_url;
                                    $destinationpdfPath = public_path($pdfpath);   
                                    if( !is_dir( $destinationpdfPath ) ) mkdir( $destinationpdfPath, 0755, true );
                                    
                                    file_put_contents($pdf_path, $dompdf->output());
                                     
                                    $imagick = new \Imagick();
                                    $imagick->readImage($pdf_path);
                                    $imagepath = "uploads/quiz_temp_image/".$user->id;
                                    $image_url = $imagepath."/".time().'.jpg';
                                    $image_path=public_path()."/".$image_url;
                                     $destinationPath = public_path($imagepath);   
                                    if( !is_dir( $destinationPath ) ) mkdir( $destinationPath, 0755, true );
                                    
                                    $imagick->writeImages($image_path, true);
                                    
                                      
                                    if(file_exists($image_path)) { 
                                        $txt = "file ".$image_path."\n";
                                    }else{
                                         $txt = "file ".public_path('uploads/design.jpg')." \n";
                                    }
                                    fwrite($txtfile, $txt);
                                    $txt = "duration 1 \n";
                                    fwrite($txtfile, $txt);
                                    
                                    $txt = "file ".public_path()."/1-second-of-silence.mp3 \n";
                                    fwrite($soundfile, $txt);
                                    $txt = "outpoint 1 \n";
                                    fwrite($soundfile, $txt);
                                     
                                }
                              
                            }
                            
                            if (strpos($video_html,'{option4}') !== false) {
                                if(isset($video->option4)){
                                     $video_html= str_replace('{option4}',$video->option4,$video_html);
                                }
                              
                            }
                            if($key!='0'){
                                $cmd .=',';
                            }
                         
                            //echo $video_html;die;
                             //$video->video_html=$video_html;
                            
                            /***********************set question ***********************/
                             $options = new Options();
                             $options->setIsRemoteEnabled(true);
                             $dompdf = new Dompdf($options);
                             $dompdf->loadHtml( $video_html); 
                              
                            // (Optional) Setup the paper size and orientation
                            if(!empty($request->ratio_name) && $request->ratio_name=="16:9"){
                                $customPaper = array(0,0,1440,1024);
                            }
                            if(!empty($request->ratio_name) && $request->ratio_name=="9:16"){
                                   $customPaper = array(0,0,600,800);
                            }
                            
                            // $customPaper = array(0,0,2048,1152);
                            // $customPaper = array(0,0,2048,1152);
                            $dompdf->setPaper($customPaper);
                            $dompdf->curlAllowUnsafeSslRequests = true;
                            
                
                            
                            // Render the HTML as PDF
                            $dompdf->render();
                           // $pdfname= 'Brochure'.time().'.pdf';
                            
                            $pdfpath = "uploads/quiz_temp_pdf/".$user->id;
                            $pdf_url = $pdfpath."/".time().'.pdf';
                            $pdf_path=public_path()."/".$pdf_url;
                            $destinationpdfPath = public_path($pdfpath);   
                            if( !is_dir( $destinationpdfPath ) ) mkdir( $destinationpdfPath, 0755, true );
                            
                            file_put_contents($pdf_path, $dompdf->output());
                             
                            $imagick = new \Imagick();
                            $imagick->readImage($pdf_path);
                            $imagepath = "uploads/quiz_temp_image/".$user->id;
                            $image_url = $imagepath."/".time().'.jpg';
                            $image_path=public_path()."/".$image_url;
                             $destinationPath = public_path($imagepath);   
                            if( !is_dir( $destinationPath ) ) mkdir( $destinationPath, 0755, true );
                            
                            $imagick->writeImages($image_path, true);
                            
                           
                           $audio_path = public_path().'/'.$video->$audio_column;
                            
                            $duration = shell_exec("ffprobe -i $audio_path -show_entries format=duration");
                            $duration = str_replace("\n", "", $duration);
                           
                           
                           $duration = str_replace("[FORMAT]", "", $duration);
                           $duration = str_replace("[/FORMAT]", "", $duration);
                           $duration = str_replace("duration=", "", $duration);
                        
                          
                            if(file_exists($image_path)) { 
                                $txt = "file ".$image_path."\n";
                            }else{
                                 $txt = "file ".public_path('uploads/design.jpg')." \n";
                            }
                            fwrite($txtfile, $txt);
                            $txt = "duration ".$duration." \n";
                            fwrite($txtfile, $txt);
    
                            
                            $txt = "file ".public_path()."/".$video->$audio_column."\n";
                            fwrite($soundfile, $txt);
                            $txt = "outpoint ".$duration." \n";
                            fwrite($soundfile, $txt);
                            $starttime=$endtime;
                            $endtime=  $endtime+4+$duration;
                            //$cmd .= "drawtext=text='%{eif\:trunc(mod((($endtime-t)/3600),24))\:d\:2}\:%{eif\:trunc(mod((($endtime-t)/60),60))\:d\:2}\:%{eif\:trunc(mod($endtime-t\,60))\:d\:2}':fontcolor=DarkSlateGray:fontfile=/home/readyvids/public_html/public/OpenSans-Regular.ttf:fontsize=40: \x=1280: y=20:enable='between(t,$starttime,$endtime)', \drawtext=text='': fontcolor=orange:fontfile=/home/readyvids/public_html/public/OpenSans-Regular.ttf: fontsize=24: x=680: y=20:enable=";
                            $cmd .= "drawtext=text='%{eif\:trunc(mod((($endtime-t)/3600),24))\:d\:2}\:%{eif\:trunc(mod((($endtime-t)/60),60))\:d\:2}\:%{eif\:trunc(mod($endtime-t\,60))\:d\:2}':fontcolor=DarkSlateGray:fontfile=/home/readyvids/public_html/public/OpenSans-Regular.ttf:fontsize=40: \x=1240: y=20:enable='between(t,$starttime,$endtime)'";
                           
                           /*************************set quetion********************************/
                           
                            sleep(2);
                            
                            
                           /*******************display answer***************/
                           
                            if($video->answer==$video->option1){
                                
                                if (strpos($video_html,"{answeroption1}") !== false) {
                                    $video_html= str_replace('{answeroption1}',"background-color:".$answerbackgroundcolor,$video_html);
                                }
                            
                            }
                            if($video->answer==$video->option2){
                                if (strpos($video_html,"{answeroption2}") !== false) {
                                    $video_html= str_replace('{answeroption2}',"background-color:".$answerbackgroundcolor,$video_html);
                                }
                               
                            }
                            if($video->answer==$video->option3){
                               if (strpos($video_html,"{answeroption3}") !== false) {
                                    $video_html= str_replace('{answeroption3}',"background-color:".$answerbackgroundcolor,$video_html);
                                }
                            }
                            if($video->answer==$video->option4){
                               if (strpos($video_html,"{answeroption4}") !== false) {
                                    $video_html= str_replace('{answeroption4}',"background-color:".$answerbackgroundcolor,$video_html);
                                }
                            }
                           
                             $options = new Options();
                             $options->setIsRemoteEnabled(true);
                             $dompdf = new Dompdf($options);
                             $dompdf->loadHtml( $video_html); 
                              
                            // (Optional) Setup the paper size and orientation
                            if(!empty($request->ratio_name) && $request->ratio_name=="16:9"){
                                $customPaper = array(0,0,1440,1024);
                            }
                            if(!empty($request->ratio_name) && $request->ratio_name=="9:16"){
                                   $customPaper = array(0,0,600,800);
                            }
                            
                            // $customPaper = array(0,0,2048,1152);
                            // $customPaper = array(0,0,2048,1152);
                            $dompdf->setPaper($customPaper);
                            $dompdf->curlAllowUnsafeSslRequests = true;
                            
                
                            
                            // Render the HTML as PDF
                            $dompdf->render();
                           // $pdfname= 'Brochure'.time().'.pdf';
                            
                            $pdfpath = "uploads/quiz_temp_pdf/".$user->id;
                            $pdf_url = $pdfpath."/".time().'.pdf';
                            $pdf_path=public_path()."/".$pdf_url;
                            $destinationpdfPath = public_path($pdfpath);   
                            if( !is_dir( $destinationpdfPath ) ) mkdir( $destinationpdfPath, 0755, true );
                            
                            file_put_contents($pdf_path, $dompdf->output());
                             
                            $imagick = new \Imagick();
                            $imagick->readImage($pdf_path);
                            $imagepath = "uploads/quiz_temp_image/".$user->id;
                            $image_url = $imagepath."/".time().'.jpg';
                            $image_path=public_path()."/".$image_url;
                             $destinationPath = public_path($imagepath);   
                            if( !is_dir( $destinationPath ) ) mkdir( $destinationPath, 0755, true );
                            
                            $imagick->writeImages($image_path, true);
                            
                            $answeraudio_column="answer_".$audio_column;
                           
                           $answer_audio_path = public_path().'/'.$video->$answeraudio_column;
                            
                            $answer_duration = shell_exec("ffprobe -i $answer_audio_path -show_entries format=duration");
                            $answer_duration = str_replace("\n", "", $answer_duration);
                           
                           
                           $answer_duration = str_replace("[FORMAT]", "", $answer_duration);
                           $answer_duration = str_replace("[/FORMAT]", "", $answer_duration);
                           $answer_duration = str_replace("duration=", "", $answer_duration);
                            
                          
                            if(file_exists($image_path)) { 
                                $txt = "file ".$image_path."\n";
                            }else{
                                 $txt = "file ".public_path('uploads/design.jpg')." \n";
                            }
                            fwrite($txtfile, $txt);
                            $txt = "duration ".$answer_duration." \n";
                            fwrite($txtfile, $txt);
    
                            
                            $txt = "file ".public_path()."/".$video->$answeraudio_column."\n";
                            fwrite($soundfile, $txt);
                            $txt = "outpoint ".$answer_duration." \n";
                            fwrite($soundfile, $txt);
                           
                           $starttime=$endtime;
                           $endtime= $endtime+$answer_duration;
                           
                          // $cmd .="'between(t,$starttime,$endtime)'";
                           /*************************************/
                            
                            sleep(2);
                        }
                       
                        fclose($txtfile);
    
                        fclose($soundfile);
                       
                       
                        
                         $file_path = public_path().'/uploads/quiz_temp_file/' . $user->id.'/input.txt';
                        $file_path1 = public_path().'/uploads/quiz_temp_file/' . $user->id.'/input1.txt';
                        
                         $destinationVideoPath=public_path()."/uploads/quiz_make_video/".$user->id;
                        if( !is_dir( $destinationVideoPath ) ) mkdir( $destinationVideoPath, 0755, true );
                        
                        if(!empty($request->video_format)){
                            $video_url = "/uploads/quiz_make_video/".$user->id."/".time().'.'.$request->video_format;
                            $output_path= public_path().$video_url;
                            $main_url = "/uploads/quiz_make_video/".$user->id."/main_".time().'.'.$request->video_format;
                            $timer_output_path =public_path().$main_url;
                            $watermark_url = "/uploads/quiz_make_video/".$user->id."/watermark_".time().'.'.$request->video_format;
                            $watermark_output_path =public_path().$watermark_url;
                        }else{ 
                            $video_url = "/uploads/quiz_make_video/".$user->id."/".time().'.mp4';
                            $output_path= public_path().$video_url;
                            $main_url = "/uploads/quiz_make_video/".$user->id."/main_".time().'.mp4';
                            $timer_output_path =public_path().$main_url;
                            $watermark_url = "/uploads/quiz_make_video/".$user->id."/watermark".time().'.mp4';
                            $watermark_output_path =public_path().$watermark_url;
                        }
    
                        if(!empty($request->ratio_name)){
                            $ratio=" -r 15 -aspect ".$request->ratio_name." -strict -2";
                            //-r 15 -vf scale=1080:-1 -aspect 1:1 -strict -2
                        }
                        
                        
                        //$output= shell_exec("ffmpeg -f concat -i $file_path -f concat -i $file_path1 -r 25 -pix_fmt yuv420p  $output_path 2>&1");
                        if(!empty($request->ratio_name) && $request->ratio_name=="16:9"){
                            $output= shell_exec("ffmpeg -f concat -safe 0 -i $file_path -f concat -safe 0 -i $file_path1 -r 15 -c:v libx264 -pix_fmt yuv420p $ratio $output_path 2>&1");
                        }
                         if(!empty($request->ratio_name) && $request->ratio_name=="9:16"){
                             $output= shell_exec("ffmpeg -f concat -safe 0 -i $file_path -f concat -safe 0 -i $file_path1 -r 15 -c:v libx264 -pix_fmt yuv420p $output_path 2>&1");
                        }
                        
                        sleep(2);
                        $vurl =env('APP_URL')."/public/".$video_url;
                        $cmd = 'ffmpeg -i '.$vurl.' -vf "'.$cmd.'[out]" -codec:a copy '.$timer_output_path;
                        $timeroutput = shell_exec("$cmd 2>&1");
                        
                        sleep(2);
                        
                        $without_watermark = env('APP_URL')."/public/".$main_url;
                        $watermark = 'https://readyvids.manageprojects.in/public/watermark_new.png';
                        $watremarkoutput= shell_exec("ffmpeg -i $without_watermark -i $watermark -filter_complex 'overlay=x=(main_w-overlay_w-200):y=(main_h-overlay_h-700)' -codec:a copy $watermark_output_path 2>&1");//top right
                       
                       
                        //               echo "<pre>";
                        // print_r($watremarkoutput);die;
                        //echo env('APP_URL')."/public/".$video_url;die;
                        $myvideo['user_id']= $user->id;
                        $myvideo['video']= env('APP_URL')."/public/".$watermark_url;
                        $myvideo['without_watermark_video']= env('APP_URL')."/public/".$main_url;
                        $myvideo['with_watermark_video']= env('APP_URL')."/public/".$watermark_url;
                        $myvideo['ratio']= $request->ratio_name;
                        
                       
                        if($request->video_id!=''){
                            $result=MyQuizVideo::findorfail($request->video_id);
                            $result->video =  env('APP_URL')."/public/".$watermark_url;
                            $result->without_watermark_video =  env('APP_URL')."/public/".$main_url;
                            $result->with_watermark_video =  env('APP_URL')."/public/".$watermark_url;
                        }else{
                            $result['video']=MyQuizVideo::create($myvideo);
                        }
                        
                        
                        $size = filesize($output_path)/1024;
                        $result['size']= number_format($size, 2, '.', '');
                        $msg  = array('status'=>true,'message' => "Video created successfully",'data'=>$result);
                        echo json_encode($msg);
                    }
                    else{
                         $msg  = array('status'=>false,'message' => "Video is not created successfully because data is not available regarding your parameter",'data'=>[]);
                        echo json_encode($msg);
                    }
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
    
    
    public function makeQuizVideo(Request $request){
        //dd($request);
        ini_set('max_execution_time', '0');
        if($request->token!=''){
            try{
                if($user = JWTAuth::authenticate($request->token)){
                    
                    /*check which content already used in video*/
                    $content = QuizVideoContent::select('video_id')->where('user_id','=',$user->id)->distinct('video_id')->get();
                  
                    $totalcontent = count($content);
                    
                    $video_size_result= VideoSize::where('id','=',$request->video_size)->first();
                    
                    $data= QuizVideo::where(['quiz_video.status'=>1]);
                    if(!empty($request->country)){
                        $data= $data->whereRaw("find_in_set('$request->country',country_id)");//->where(['quiz_video.country_id'=>$request->country]);
                    }
                    if(!empty($request->subject)){
                        $data= $data->whereRaw("find_in_set('$request->subject',subject_id)");//->where(['quiz_video.subject_id'=>$request->subject]);
                    }
                    if(!empty($request->topic)){
                        $data= $data->whereRaw("find_in_set('$request->topic',topic_id)");//->where(['quiz_video.topic_id'=>$request->topic]);
                    }
                  
                    $data=$data->inRandomOrder();
                   
                    if(!empty($request->video_size)){
                        $data= $data->limit($video_size_result->display_video);
                    }
                   
                    if($data->count()>=$video_size_result->display_video){
                        if(($data->count()-$totalcontent)>$video_size_result->display_video || ($data->count()-$totalcontent)==$video_size_result->display_video){
                           $data= $data->whereNotIn('quiz_video.id',$content);
                        }
                        $data= $data->get();
                        //dd($data);
                        $template = QuizTemplate::findorfail($request->template_id);
                      
                       $destinationtempfilePath=public_path().'/uploads/quiz_temp_file/' . $user->id;
                        if( !is_dir( $destinationtempfilePath ) ) mkdir( $destinationtempfilePath, 0755, true );
                        $txtfile = fopen($destinationtempfilePath."/input.txt", "w") or die("Unable to open file!");
                        $soundfile = fopen($destinationtempfilePath."/input1.txt", "w") or die("Unable to open file!");
                        
                        $audio_column=$request->voice;
                        $cmd='[in]';
                        $starttime = 0;
                        $endtime = 0;
                        $question_display_time = $video_size_result->question_display_time;
                        $answer_display_time = $video_size_result->answer_display_time;
                        foreach($data as $key=>$video){
                            $videocontent['user_id']=$user->id;
                            $videocontent['video_id']=$video->id;
                            QuizVideoContent::create($videocontent);
                           // $video_text = VideoTextMapping::join('video_text','video_text.id','=','video_text_mapping.text_id')->where('video_text_mapping.video_id','=',$video->id)->get();
                            $video_html = $template->template_html_string;
                            
                            $answerbackgroundcolor = $template->answerbackgroundcolor;
                            
                            // $template_type= explode(' ',$video->templatetype);
                            // $lineno = $template_type[0];
                            // foreach($video_text as $key=>$text){
                            //     $counter= $key+1;
                               
                            //     $searchtext = '{text'.$counter.'}';
                            $question_number= $key+1;
                             $question_number= $question_number.'/'.$video_size_result->display_video;
                            if (strpos($video_html,"{question_number}") !== false) {
                               $video_html= str_replace('{question_number}',$question_number,$video_html);
                            }
                            
                            if (strpos($video_html,"{openbody}") !== false) {
                               $video_html= str_replace('{openbody}',"<body style='",$video_html);
                            }
                            
                            if (strpos($video_html,"{closebody}") !== false) {
                               $video_html= str_replace('{closebody}',"'>",$video_html);
                            }
                            
                            if (strpos($video_html,"{endbody}") !== false) {
                               $video_html= str_replace('{endbody}',"</body>",$video_html);
                            }
                            
                            if (strpos($video_html,'{question}') !== false) {
                                if(isset($video->question)){
                                     
                                    $video_html= str_replace('{question}',$video->question,$video_html);
                                     
                                //     $video_html1=$video_html;
                                     
                                //     $video_html1= str_replace('{option1}','',$video_html1);
                                //     $video_html1= str_replace('{option2}','',$video_html1);
                                //     $video_html1= str_replace('{option3}','',$video_html1);
                                //     $video_html1= str_replace('{option4}','',$video_html1);
                                    
                                //     $options = new Options();
                                //     $options->setIsRemoteEnabled(true);
                                //     $dompdf = new Dompdf($options);
                                //     $dompdf->loadHtml( $video_html1); 
                                      
                                //     // (Optional) Setup the paper size and orientation
                                //     if(!empty($request->ratio_name) && $request->ratio_name=="16:9"){
                                //         $customPaper = array(0,0,1440,1024);
                                //     }
                                //     if(!empty($request->ratio_name) && $request->ratio_name=="9:16"){
                                //           $customPaper = array(0,0,600,800);
                                //     }
                                    
                                //     // $customPaper = array(0,0,2048,1152);
                                //     // $customPaper = array(0,0,2048,1152);
                                //     $dompdf->setPaper($customPaper);
                                //     $dompdf->curlAllowUnsafeSslRequests = true;
                                    
                        
                                    
                                //     // Render the HTML as PDF
                                //     $dompdf->render();
                                //   // $pdfname= 'Brochure'.time().'.pdf';
                                    
                                //     $pdfpath = "uploads/quiz_temp_pdf/".$user->id;
                                //     $pdf_url = $pdfpath."/".time().'.pdf';
                                //     $pdf_path=public_path()."/".$pdf_url;
                                //     $destinationpdfPath = public_path($pdfpath);   
                                //     if( !is_dir( $destinationpdfPath ) ) mkdir( $destinationpdfPath, 0755, true );
                                    
                                //     file_put_contents($pdf_path, $dompdf->output());
                                     
                                //     $imagick = new \Imagick();
                                //     $imagick->readImage($pdf_path);
                                //     $imagepath = "uploads/quiz_temp_image/".$user->id;
                                //     $image_url = $imagepath."/".time().'.jpg';
                                //     $image_path=public_path()."/".$image_url;
                                //      $destinationPath = public_path($imagepath);   
                                //     if( !is_dir( $destinationPath ) ) mkdir( $destinationPath, 0755, true );
                                    
                                //     $imagick->writeImages($image_path, true);
                                    
                                      
                                //     if(file_exists($image_path)) { 
                                //         $txt = "file ".$image_path."\n";
                                //     }else{
                                //          $txt = "file ".public_path('uploads/design.jpg')." \n";
                                //     }
                                //     fwrite($txtfile, $txt);
                                //     $txt = "duration 1 \n";
                                //     fwrite($txtfile, $txt);
                                    
                                //     $txt = "file ".public_path()."/1-second-of-silence.mp3 \n";
                                //     fwrite($soundfile, $txt);
                                //     $txt = "outpoint 1 \n";
                                //     fwrite($soundfile, $txt);
                                }
                              
                            }
                            
                            if (strpos($video_html,'{option1}') !== false) {
                                if(isset($video->option1)){
                                     $video_html= str_replace('{option1}',$video->option1,$video_html);
                                     
                                //      $video_html1=$video_html;
                                     
                                 
                                //     $video_html1= str_replace('{option2}','',$video_html1);
                                //     $video_html1= str_replace('{option3}','',$video_html1);
                                //     $video_html1= str_replace('{option4}','',$video_html1);
                                    
                                //     $options = new Options();
                                //     $options->setIsRemoteEnabled(true);
                                //     $dompdf = new Dompdf($options);
                                //     $dompdf->loadHtml( $video_html1); 
                                      
                                //     // (Optional) Setup the paper size and orientation
                                //     if(!empty($request->ratio_name) && $request->ratio_name=="16:9"){
                                //         $customPaper = array(0,0,1440,1024);
                                //     }
                                //     if(!empty($request->ratio_name) && $request->ratio_name=="9:16"){
                                //           $customPaper = array(0,0,600,800);
                                //     }
                                    
                                //     // $customPaper = array(0,0,2048,1152);
                                //     // $customPaper = array(0,0,2048,1152);
                                //     $dompdf->setPaper($customPaper);
                                //     $dompdf->curlAllowUnsafeSslRequests = true;
                                    
                        
                                    
                                //     // Render the HTML as PDF
                                //     $dompdf->render();
                                //   // $pdfname= 'Brochure'.time().'.pdf';
                                    
                                //     $pdfpath = "uploads/quiz_temp_pdf/".$user->id;
                                //     $pdf_url = $pdfpath."/".time().'.pdf';
                                //     $pdf_path=public_path()."/".$pdf_url;
                                //     $destinationpdfPath = public_path($pdfpath);   
                                //     if( !is_dir( $destinationpdfPath ) ) mkdir( $destinationpdfPath, 0755, true );
                                    
                                //     file_put_contents($pdf_path, $dompdf->output());
                                     
                                //     $imagick = new \Imagick();
                                //     $imagick->readImage($pdf_path);
                                //     $imagepath = "uploads/quiz_temp_image/".$user->id;
                                //     $image_url = $imagepath."/".time().'.jpg';
                                //     $image_path=public_path()."/".$image_url;
                                //      $destinationPath = public_path($imagepath);   
                                //     if( !is_dir( $destinationPath ) ) mkdir( $destinationPath, 0755, true );
                                    
                                //     $imagick->writeImages($image_path, true);
                                    
                                      
                                //     if(file_exists($image_path)) { 
                                //         $txt = "file ".$image_path."\n";
                                //     }else{
                                //          $txt = "file ".public_path('uploads/design.jpg')." \n";
                                //     }
                                //     fwrite($txtfile, $txt);
                                //     $txt = "duration 1 \n";
                                //     fwrite($txtfile, $txt);
                                    
                                //     $txt = "file ".public_path()."/1-second-of-silence.mp3 \n";
                                //     fwrite($soundfile, $txt);
                                //     $txt = "outpoint 1 \n";
                                //     fwrite($soundfile, $txt);
                                }
                              
                            }
                            
                            if (strpos($video_html,'{option2}') !== false) {
                                if(isset($video->option2)){
                                     $video_html= str_replace('{option2}',$video->option2,$video_html);
                                     
                                //     $video_html1=$video_html;
                                     
                                //     $video_html1= str_replace('{option3}','',$video_html1);
                                //     $video_html1= str_replace('{option4}','',$video_html1);
                                    
                                //     $options = new Options();
                                //     $options->setIsRemoteEnabled(true);
                                //     $dompdf = new Dompdf($options);
                                //     $dompdf->loadHtml( $video_html1); 
                                      
                                //     // (Optional) Setup the paper size and orientation
                                //     if(!empty($request->ratio_name) && $request->ratio_name=="16:9"){
                                //         $customPaper = array(0,0,1440,1024);
                                //     }
                                //     if(!empty($request->ratio_name) && $request->ratio_name=="9:16"){
                                //           $customPaper = array(0,0,600,800);
                                //     }
                                    
                                //     // $customPaper = array(0,0,2048,1152);
                                //     // $customPaper = array(0,0,2048,1152);
                                //     $dompdf->setPaper($customPaper);
                                //     $dompdf->curlAllowUnsafeSslRequests = true;
                                    
                        
                                    
                                //     // Render the HTML as PDF
                                //     $dompdf->render();
                                //   // $pdfname= 'Brochure'.time().'.pdf';
                                    
                                //     $pdfpath = "uploads/quiz_temp_pdf/".$user->id;
                                //     $pdf_url = $pdfpath."/".time().'.pdf';
                                //     $pdf_path=public_path()."/".$pdf_url;
                                //     $destinationpdfPath = public_path($pdfpath);   
                                //     if( !is_dir( $destinationpdfPath ) ) mkdir( $destinationpdfPath, 0755, true );
                                    
                                //     file_put_contents($pdf_path, $dompdf->output());
                                     
                                //     $imagick = new \Imagick();
                                //     $imagick->readImage($pdf_path);
                                //     $imagepath = "uploads/quiz_temp_image/".$user->id;
                                //     $image_url = $imagepath."/".time().'.jpg';
                                //     $image_path=public_path()."/".$image_url;
                                //      $destinationPath = public_path($imagepath);   
                                //     if( !is_dir( $destinationPath ) ) mkdir( $destinationPath, 0755, true );
                                    
                                //     $imagick->writeImages($image_path, true);
                                    
                                      
                                //     if(file_exists($image_path)) { 
                                //         $txt = "file ".$image_path."\n";
                                //     }else{
                                //          $txt = "file ".public_path('uploads/design.jpg')." \n";
                                //     }
                                //     fwrite($txtfile, $txt);
                                //     $txt = "duration 1 \n";
                                //     fwrite($txtfile, $txt);
                                    
                                //     $txt = "file ".public_path()."/1-second-of-silence.mp3 \n";
                                //     fwrite($soundfile, $txt);
                                //     $txt = "outpoint 1 \n";
                                //     fwrite($soundfile, $txt);
                                }
                              
                            }
                            
                            if (strpos($video_html,'{option3}') !== false) {
                                if(isset($video->option3)){
                                     $video_html= str_replace('{option3}',$video->option3,$video_html);
                                     
                                //     $video_html1=$video_html;
                                     
                                //     $video_html1= str_replace('{option4}','',$video_html1);
                                    
                                //     $options = new Options();
                                //     $options->setIsRemoteEnabled(true);
                                //     $dompdf = new Dompdf($options);
                                //     $dompdf->loadHtml( $video_html1); 
                                      
                                //     // (Optional) Setup the paper size and orientation
                                //     if(!empty($request->ratio_name) && $request->ratio_name=="16:9"){
                                //         $customPaper = array(0,0,1440,1024);
                                //     }
                                //     if(!empty($request->ratio_name) && $request->ratio_name=="9:16"){
                                //           $customPaper = array(0,0,600,800);
                                //     }
                                    
                                //     // $customPaper = array(0,0,2048,1152);
                                //     // $customPaper = array(0,0,2048,1152);
                                //     $dompdf->setPaper($customPaper);
                                //     $dompdf->curlAllowUnsafeSslRequests = true;
                                    
                        
                                    
                                //     // Render the HTML as PDF
                                //     $dompdf->render();
                                //   // $pdfname= 'Brochure'.time().'.pdf';
                                    
                                //     $pdfpath = "uploads/quiz_temp_pdf/".$user->id;
                                //     $pdf_url = $pdfpath."/".time().'.pdf';
                                //     $pdf_path=public_path()."/".$pdf_url;
                                //     $destinationpdfPath = public_path($pdfpath);   
                                //     if( !is_dir( $destinationpdfPath ) ) mkdir( $destinationpdfPath, 0755, true );
                                    
                                //     file_put_contents($pdf_path, $dompdf->output());
                                     
                                //     $imagick = new \Imagick();
                                //     $imagick->readImage($pdf_path);
                                //     $imagepath = "uploads/quiz_temp_image/".$user->id;
                                //     $image_url = $imagepath."/".time().'.jpg';
                                //     $image_path=public_path()."/".$image_url;
                                //      $destinationPath = public_path($imagepath);   
                                //     if( !is_dir( $destinationPath ) ) mkdir( $destinationPath, 0755, true );
                                    
                                //     $imagick->writeImages($image_path, true);
                                    
                                      
                                //     if(file_exists($image_path)) { 
                                //         $txt = "file ".$image_path."\n";
                                //     }else{
                                //          $txt = "file ".public_path('uploads/design.jpg')." \n";
                                //     }
                                //     fwrite($txtfile, $txt);
                                //     $txt = "duration 1 \n";
                                //     fwrite($txtfile, $txt);
                                    
                                //     $txt = "file ".public_path()."/1-second-of-silence.mp3 \n";
                                //     fwrite($soundfile, $txt);
                                //     $txt = "outpoint 1 \n";
                                //     fwrite($soundfile, $txt);
                                     
                                }
                              
                            }
                            
                            if (strpos($video_html,'{option4}') !== false) {
                                if(isset($video->option4)){
                                     $video_html= str_replace('{option4}',$video->option4,$video_html);
                                }
                              
                            }
                            if($key!='0'){
                                $cmd .=',';
                            }
                         
                            //echo $video_html;die;
                             //$video->video_html=$video_html;
                            
                            /***********************set question ***********************/
                             $options = new Options();
                             $options->setIsRemoteEnabled(true);
                             $dompdf = new Dompdf($options);
                             $dompdf->loadHtml( $video_html); 
                              
                            // (Optional) Setup the paper size and orientation
                            if(!empty($request->ratio_name) && $request->ratio_name=="16:9"){
                                $customPaper = array(0,0,1440,1024);
                                $x=1240;
                            }
                            if(!empty($request->ratio_name) && $request->ratio_name=="9:16"){
                                   $customPaper = array(0,0,600,800);
                                   $x=410;
                            }
                            
                            // $customPaper = array(0,0,2048,1152);
                            // $customPaper = array(0,0,2048,1152);
                            $dompdf->setPaper($customPaper);
                            $dompdf->curlAllowUnsafeSslRequests = true;
                            
                
                            
                            // Render the HTML as PDF
                            $dompdf->render();
                           // $pdfname= 'Brochure'.time().'.pdf';
                            
                            $pdfpath = "uploads/quiz_temp_pdf/".$user->id;
                            $pdf_url = $pdfpath."/".time().'.pdf';
                            $pdf_path=public_path()."/".$pdf_url;
                            $destinationpdfPath = public_path($pdfpath);   
                            if( !is_dir( $destinationpdfPath ) ) mkdir( $destinationpdfPath, 0755, true );
                            
                            file_put_contents($pdf_path, $dompdf->output());
                             
                            $imagick = new \Imagick();
                            $imagick->readImage($pdf_path);
                            $imagepath = "uploads/quiz_temp_image/".$user->id;
                            $image_url = $imagepath."/".time().'.jpg';
                            $image_path=public_path()."/".$image_url;
                             $destinationPath = public_path($imagepath);   
                            if( !is_dir( $destinationPath ) ) mkdir( $destinationPath, 0755, true );
                            
                            $imagick->writeImages($image_path, true);
                            
                           
                           $audio_path = public_path().'/'.$video->$audio_column;
                            
                            $duration = shell_exec("ffprobe -i $audio_path -show_entries format=duration");
                            $duration = str_replace("\n", "", $duration);
                           
                           
                           $duration = str_replace("[FORMAT]", "", $duration);
                           $duration = str_replace("[/FORMAT]", "", $duration);
                           $duration = str_replace("duration=", "", $duration);
                        
                          
                            if(file_exists($image_path)) { 
                                $txt = "file ".$image_path."\n";
                            }else{
                                 $txt = "file ".public_path('uploads/design.jpg')." \n";
                            }
                            fwrite($txtfile, $txt);
                            $txt = "duration ".$duration." \n";
                            fwrite($txtfile, $txt);
    
                            
                            $txt = "file ".public_path()."/".$video->$audio_column."\n";
                            fwrite($soundfile, $txt);
                            $txt = "outpoint ".$duration." \n";
                            fwrite($soundfile, $txt);
                            
                            $roundoff = (int)$duration;
                            if($question_display_time>$roundoff){
                                $remainingtime= $question_display_time-$roundoff;
                                
                                if(file_exists($image_path)) { 
                                    $txt = "file ".$image_path."\n";
                                }else{
                                     $txt = "file ".public_path('uploads/design.jpg')." \n";
                                }
                                fwrite($txtfile, $txt);
                                $txt = "duration ".$remainingtime." \n";
                                fwrite($txtfile, $txt);
        
                                
                                $txt = "file ".public_path()."/muted/".$remainingtime."_Second.mp3\n";
                                fwrite($soundfile, $txt);
                                $txt = "outpoint ".$remainingtime." \n";
                                fwrite($soundfile, $txt);
                            }
                            
                            
                            $starttime=$endtime;
                            //$endtime=  $endtime+4+$duration;
                            $endtime=  $endtime+$remainingtime+$duration;
                            //$cmd .= "drawtext=text='%{eif\:trunc(mod((($endtime-t)/3600),24))\:d\:2}\:%{eif\:trunc(mod((($endtime-t)/60),60))\:d\:2}\:%{eif\:trunc(mod($endtime-t\,60))\:d\:2}':fontcolor=DarkSlateGray:fontfile=/home/readyvids/public_html/public/OpenSans-Regular.ttf:fontsize=40: \x=1280: y=20:enable='between(t,$starttime,$endtime)', \drawtext=text='': fontcolor=orange:fontfile=/home/readyvids/public_html/public/OpenSans-Regular.ttf: fontsize=24: x=680: y=20:enable=";
                            $cmd .= "drawtext=text='%{eif\:trunc(mod((($endtime-t)/3600),24))\:d\:2}\:%{eif\:trunc(mod((($endtime-t)/60),60))\:d\:2}\:%{eif\:trunc(mod($endtime-t\,60))\:d\:2}':fontcolor=DarkSlateGray:fontfile=/home/readyvids/public_html/admin/public/OpenSans-Regular.ttf:fontsize=40: \x=$x: y=20:enable='between(t,$starttime,$endtime)'";
                           
                           /*************************set quetion********************************/
                           
                            sleep(2);
                            
                            
                           /*******************display answer***************/
                           
                            if($video->answer==$video->option1){
                                
                                if (strpos($video_html,"{answeroption1}") !== false) {
                                    $video_html= str_replace('{answeroption1}',"background-color:".$answerbackgroundcolor,$video_html);
                                }
                            
                            }
                            if($video->answer==$video->option2){
                                if (strpos($video_html,"{answeroption2}") !== false) {
                                    $video_html= str_replace('{answeroption2}',"background-color:".$answerbackgroundcolor,$video_html);
                                }
                               
                            }
                            if($video->answer==$video->option3){
                               if (strpos($video_html,"{answeroption3}") !== false) {
                                    $video_html= str_replace('{answeroption3}',"background-color:".$answerbackgroundcolor,$video_html);
                                }
                            }
                            if($video->answer==$video->option4){
                               if (strpos($video_html,"{answeroption4}") !== false) {
                                    $video_html= str_replace('{answeroption4}',"background-color:".$answerbackgroundcolor,$video_html);
                                }
                            }
                           
                             $options = new Options();
                             $options->setIsRemoteEnabled(true);
                             $dompdf = new Dompdf($options);
                             $dompdf->loadHtml( $video_html); 
                              
                            // (Optional) Setup the paper size and orientation
                            if(!empty($request->ratio_name) && $request->ratio_name=="16:9"){
                                $customPaper = array(0,0,1440,1024);
                            }
                            if(!empty($request->ratio_name) && $request->ratio_name=="9:16"){
                                   $customPaper = array(0,0,600,800);
                            }
                            
                            // $customPaper = array(0,0,2048,1152);
                            // $customPaper = array(0,0,2048,1152);
                            $dompdf->setPaper($customPaper);
                            $dompdf->curlAllowUnsafeSslRequests = true;
                            
                
                            
                            // Render the HTML as PDF
                            $dompdf->render();
                           // $pdfname= 'Brochure'.time().'.pdf';
                            
                            $pdfpath = "uploads/quiz_temp_pdf/".$user->id;
                            $pdf_url = $pdfpath."/".time().'.pdf';
                            $pdf_path=public_path()."/".$pdf_url;
                            $destinationpdfPath = public_path($pdfpath);   
                            if( !is_dir( $destinationpdfPath ) ) mkdir( $destinationpdfPath, 0755, true );
                            
                            file_put_contents($pdf_path, $dompdf->output());
                             
                            $imagick = new \Imagick();
                            $imagick->readImage($pdf_path);
                            $imagepath = "uploads/quiz_temp_image/".$user->id;
                            $image_url = $imagepath."/".time().'.jpg';
                            $image_path=public_path()."/".$image_url;
                             $destinationPath = public_path($imagepath);   
                            if( !is_dir( $destinationPath ) ) mkdir( $destinationPath, 0755, true );
                            
                            $imagick->writeImages($image_path, true);
                            
                            $answeraudio_column="answer_".$audio_column;
                           
                           $answer_audio_path = public_path().'/'.$video->$answeraudio_column;
                            
                            $answer_duration = shell_exec("ffprobe -i $answer_audio_path -show_entries format=duration");
                            $answer_duration = str_replace("\n", "", $answer_duration);
                           
                           
                           $answer_duration = str_replace("[FORMAT]", "", $answer_duration);
                           $answer_duration = str_replace("[/FORMAT]", "", $answer_duration);
                           $answer_duration = str_replace("duration=", "", $answer_duration);
                            
                          
                            if(file_exists($image_path)) { 
                                $txt = "file ".$image_path."\n";
                            }else{
                                 $txt = "file ".public_path('uploads/design.jpg')." \n";
                            }
                            fwrite($txtfile, $txt);
                            $txt = "duration ".$answer_duration." \n";
                            fwrite($txtfile, $txt);
    
                            
                            $txt = "file ".public_path()."/".$video->$answeraudio_column."\n";
                            fwrite($soundfile, $txt);
                            $txt = "outpoint ".$answer_duration." \n";
                            fwrite($soundfile, $txt);
                           
                            
                            $answerroundoff = (int)$answer_duration;
                            if($answer_display_time>$answerroundoff){
                                $answerremainingtime= $answer_display_time-$answerroundoff;
                                
                                if(file_exists($image_path)) { 
                                $txt = "file ".$image_path."\n";
                                }else{
                                     $txt = "file ".public_path('uploads/design.jpg')." \n";
                                }
                                fwrite($txtfile, $txt);
                                $txt = "duration ".$answerremainingtime." \n";
                                fwrite($txtfile, $txt);
        
                                
                                $txt = "file ".public_path()."/muted/".$answerremainingtime."_Second.mp3\n";
                                fwrite($soundfile, $txt);
                                $txt = "outpoint ".$answerremainingtime." \n";
                                fwrite($soundfile, $txt);
                            }
                            
                            
                           $starttime=$endtime;
                          // $endtime= $endtime+$answer_duration;
                           $endtime= $endtime+$answer_duration+$answerremainingtime;
                           
                          // $cmd .="'between(t,$starttime,$endtime)'";
                           /*************************************/
                            
                            sleep(2);
                        }
                       
                        fclose($txtfile);
    
                        fclose($soundfile);
                       
                       
                        
                        $file_path = public_path().'/uploads/quiz_temp_file/' . $user->id.'/input.txt';
                        $file_path1 = public_path().'/uploads/quiz_temp_file/' . $user->id.'/input1.txt';
                        
                         $destinationVideoPath=public_path()."/uploads/quiz_make_video/".$user->id;
                        if( !is_dir( $destinationVideoPath ) ) mkdir( $destinationVideoPath, 0755, true );
                        
                        if(!empty($request->video_format)){
                            $video_url = "/uploads/quiz_make_video/".$user->id."/".time().'.'.$request->video_format;
                            $output_path= public_path().$video_url;
                            $main_url = "/uploads/quiz_make_video/".$user->id."/main_".time().'.'.$request->video_format;
                            $timer_output_path =public_path().$main_url;
                            $watermark_url = "/uploads/quiz_make_video/".$user->id."/watermark_".time().'.'.$request->video_format;
                            $watermark_output_path =public_path().$watermark_url;
                        }else{ 
                            $video_url = "/uploads/quiz_make_video/".$user->id."/".time().'.mp4';
                            $output_path= public_path().$video_url;
                            $main_url = "/uploads/quiz_make_video/".$user->id."/main_".time().'.mp4';
                            $timer_output_path =public_path().$main_url;
                            $watermark_url = "/uploads/quiz_make_video/".$user->id."/watermark".time().'.mp4';
                            $watermark_output_path =public_path().$watermark_url;
                        }
    
                        if(!empty($request->ratio_name)){
                            $ratio=" -r 15 -aspect ".$request->ratio_name." -strict -2";
                            //-r 15 -vf scale=1080:-1 -aspect 1:1 -strict -2
                        }
                        
                        
                        //$output= shell_exec("ffmpeg -f concat -i $file_path -f concat -i $file_path1 -r 25 -pix_fmt yuv420p  $output_path 2>&1");
                        if(!empty($request->ratio_name) && $request->ratio_name=="16:9"){
                            $output= shell_exec("ffmpeg -f concat -safe 0 -i $file_path -f concat -safe 0 -i $file_path1 -r 15 -c:v libx264 -pix_fmt yuv420p $ratio $output_path 2>&1");
                        }
                         if(!empty($request->ratio_name) && $request->ratio_name=="9:16"){
                             $output= shell_exec("ffmpeg -f concat -safe 0 -i $file_path -f concat -safe 0 -i $file_path1 -r 15 -c:v libx264 -pix_fmt yuv420p $output_path 2>&1");
                        }
                        
                        sleep(2);
                        $vurl =env('APP_URL')."/public/".$video_url;
                        $cmd = 'ffmpeg -i '.$vurl.' -vf "'.$cmd.'[out]" -codec:a copy '.$timer_output_path;
                        $timeroutput = shell_exec("$cmd 2>&1");
                        
                        sleep(2);
                        
                        $without_watermark = env('APP_URL')."/public/".$main_url;
                        $watermark = 'https://readyvids.manageprojects.in/public/watermark_new.png';
                        
                        if(!empty($request->ratio_name) && $request->ratio_name=="16:9"){
                            
                            $watermark = 'https://admin.readyvids.com/public/WideScreen.png';
                           
                            $watremarkoutput= shell_exec("ffmpeg -i $without_watermark -i $watermark -filter_complex 'overlay=x=0:y=(main_h-overlay_h-300)' -codec:a copy $watermark_output_path 2>&1");//top right
                            //$watremarkoutput= shell_exec("ffmpeg -i $without_watermark -i $watermark -filter_complex 'overlay=x=(main_w-overlay_w-200):y=(main_h-overlay_h-700)' -codec:a copy $watermark_output_path 2>&1");//top right
                        }
                        if(!empty($request->ratio_name) && $request->ratio_name=="9:16"){
                            $watermark = 'https://admin.readyvids.com/public/VerticalScreen.png';
                            $watremarkoutput= shell_exec("ffmpeg -i $without_watermark -i $watermark -filter_complex 'overlay=x=0:y=(main_h-overlay_h-300)' -codec:a copy $watermark_output_path 2>&1");//top right
                            //$watremarkoutput= shell_exec("ffmpeg -i $without_watermark -i $watermark -filter_complex 'overlay=x=(main_w-overlay_w-200):y=(main_h-overlay_h-630)' -codec:a copy $watermark_output_path 2>&1");//top right
                        }
                        
                      
                       
                       
                        //               echo "<pre>";
                        // print_r($watremarkoutput);die;
                        //echo env('APP_URL')."/public/".$video_url;die;
                        $myvideo['user_id']= $user->id;
                        $myvideo['video']= env('APP_URL')."/public/".$watermark_url;
                        $myvideo['without_watermark_video']= env('APP_URL')."/public/".$main_url;
                        $myvideo['with_watermark_video']= env('APP_URL')."/public/".$watermark_url;
                        $myvideo['ratio']= $request->ratio_name;
                        
                       
                        if($request->video_id!=''){
                            $result=MyQuizVideo::findorfail($request->video_id);
                            $result->video =  env('APP_URL')."/public/".$watermark_url;
                            $result->without_watermark_video =  env('APP_URL')."/public/".$main_url;
                            $result->with_watermark_video =  env('APP_URL')."/public/".$watermark_url;
                        }else{
                            $result['video']=MyQuizVideo::create($myvideo);
                        }
                        
                        
                        $size = filesize($output_path)/1024;
                        $result['size']= number_format($size, 2, '.', '');
                        $msg  = array('status'=>true,'message' => "Video created successfully",'data'=>$result);
                        echo json_encode($msg);
                    }
                    else{
                         $msg  = array('status'=>false,'message' => "Video is not created successfully because data is not available regarding your parameter",'data'=>[]);
                        echo json_encode($msg);
                    }
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
    
    public function makeQuizVideo1(Request $request){
        // dd($request);
        ini_set('max_execution_time', '0');
        if($request->token!=''){
            try{
                if($user = JWTAuth::authenticate($request->token)){
                    
                    /*check which content already used in video*/
                    $content = QuizVideoContent::select('video_id')->where('user_id','=',$user->id)->distinct('video_id')->get();
                  
                    $totalcontent = count($content);
                    
                    $video_size_result= VideoSize::where('id','=',$request->video_size)->first();
                    
                    $data= QuizVideo::where(['quiz_video.status'=>1]);
                    if(!empty($request->country)){
                        $data= $data->where(['quiz_video.country_id'=>$request->country]);
                    }
                    if(!empty($request->subject)){
                        $data= $data->where(['quiz_video.subject_id'=>$request->subject]);
                    }
                    if(!empty($request->topic)){
                        $data= $data->where(['quiz_video.topic_id'=>$request->topic]);
                    }
                  
                    $data=$data->inRandomOrder();
                   
                    if(!empty($request->video_size)){
                        $data= $data->limit($video_size_result->display_video);
                    }
                   
                    if($data->count()>=$video_size_result->display_video){
                        if(($data->count()-$totalcontent)>$video_size_result->display_video || ($data->count()-$totalcontent)==$video_size_result->display_video){
                           $data= $data->whereNotIn('id',$content);
                        }
                        $data= $data->get();
                        //dd($data);
                        $template = QuizTemplate::findorfail($request->template_id);
                      
                       $destinationtempfilePath=public_path().'/uploads/quiz_temp_file/' . $user->id;
                        if( !is_dir( $destinationtempfilePath ) ) mkdir( $destinationtempfilePath, 0755, true );
                        $txtfile = fopen($destinationtempfilePath."/input.txt", "w") or die("Unable to open file!");
                        $soundfile = fopen($destinationtempfilePath."/input1.txt", "w") or die("Unable to open file!");
                        
                        $audio_column=$request->voice;
                        $cmd='[in]';
                        $starttime = 0;
                        $endtime = 0;
                        $question_display_time = $video_size_result->question_display_time;
                        $answer_display_time = $video_size_result->answer_display_time;
                        foreach($data as $key=>$video){
                            $videocontent['user_id']=$user->id;
                            $videocontent['video_id']=$video->id;
                            QuizVideoContent::create($videocontent);
                           // $video_text = VideoTextMapping::join('video_text','video_text.id','=','video_text_mapping.text_id')->where('video_text_mapping.video_id','=',$video->id)->get();
                            $video_html = $template->template_html_string;
                            
                            $answerbackgroundcolor = $template->answerbackgroundcolor;
                            
                            // $template_type= explode(' ',$video->templatetype);
                            // $lineno = $template_type[0];
                            // foreach($video_text as $key=>$text){
                            //     $counter= $key+1;
                               
                            //     $searchtext = '{text'.$counter.'}';
                            $question_number= $key+1;
                             $question_number= $question_number.'/'.$video_size_result->display_video;
                            if (strpos($video_html,"{question_number}") !== false) {
                               $video_html= str_replace('{question_number}',$question_number,$video_html);
                            }
                            
                            if (strpos($video_html,"{openbody}") !== false) {
                               $video_html= str_replace('{openbody}',"<body style='",$video_html);
                            }
                            
                            if (strpos($video_html,"{closebody}") !== false) {
                               $video_html= str_replace('{closebody}',"'>",$video_html);
                            }
                            
                            if (strpos($video_html,"{endbody}") !== false) {
                               $video_html= str_replace('{endbody}',"</body>",$video_html);
                            }
                            
                            if (strpos($video_html,'{question}') !== false) {
                                if(isset($video->question)){
                                     
                                    $video_html= str_replace('{question}',$video->question,$video_html);
                                     
                                //     $video_html1=$video_html;
                                     
                                //     $video_html1= str_replace('{option1}','',$video_html1);
                                //     $video_html1= str_replace('{option2}','',$video_html1);
                                //     $video_html1= str_replace('{option3}','',$video_html1);
                                //     $video_html1= str_replace('{option4}','',$video_html1);
                                    
                                //     $options = new Options();
                                //     $options->setIsRemoteEnabled(true);
                                //     $dompdf = new Dompdf($options);
                                //     $dompdf->loadHtml( $video_html1); 
                                      
                                //     // (Optional) Setup the paper size and orientation
                                //     if(!empty($request->ratio_name) && $request->ratio_name=="16:9"){
                                //         $customPaper = array(0,0,1440,1024);
                                //     }
                                //     if(!empty($request->ratio_name) && $request->ratio_name=="9:16"){
                                //           $customPaper = array(0,0,600,800);
                                //     }
                                    
                                //     // $customPaper = array(0,0,2048,1152);
                                //     // $customPaper = array(0,0,2048,1152);
                                //     $dompdf->setPaper($customPaper);
                                //     $dompdf->curlAllowUnsafeSslRequests = true;
                                    
                        
                                    
                                //     // Render the HTML as PDF
                                //     $dompdf->render();
                                //   // $pdfname= 'Brochure'.time().'.pdf';
                                    
                                //     $pdfpath = "uploads/quiz_temp_pdf/".$user->id;
                                //     $pdf_url = $pdfpath."/".time().'.pdf';
                                //     $pdf_path=public_path()."/".$pdf_url;
                                //     $destinationpdfPath = public_path($pdfpath);   
                                //     if( !is_dir( $destinationpdfPath ) ) mkdir( $destinationpdfPath, 0755, true );
                                    
                                //     file_put_contents($pdf_path, $dompdf->output());
                                     
                                //     $imagick = new \Imagick();
                                //     $imagick->readImage($pdf_path);
                                //     $imagepath = "uploads/quiz_temp_image/".$user->id;
                                //     $image_url = $imagepath."/".time().'.jpg';
                                //     $image_path=public_path()."/".$image_url;
                                //      $destinationPath = public_path($imagepath);   
                                //     if( !is_dir( $destinationPath ) ) mkdir( $destinationPath, 0755, true );
                                    
                                //     $imagick->writeImages($image_path, true);
                                    
                                      
                                //     if(file_exists($image_path)) { 
                                //         $txt = "file ".$image_path."\n";
                                //     }else{
                                //          $txt = "file ".public_path('uploads/design.jpg')." \n";
                                //     }
                                //     fwrite($txtfile, $txt);
                                //     $txt = "duration 1 \n";
                                //     fwrite($txtfile, $txt);
                                    
                                //     $txt = "file ".public_path()."/1-second-of-silence.mp3 \n";
                                //     fwrite($soundfile, $txt);
                                //     $txt = "outpoint 1 \n";
                                //     fwrite($soundfile, $txt);
                                }
                              
                            }
                            
                            if (strpos($video_html,'{option1}') !== false) {
                                if(isset($video->option1)){
                                     $video_html= str_replace('{option1}',$video->option1,$video_html);
                                     
                                //      $video_html1=$video_html;
                                     
                                 
                                //     $video_html1= str_replace('{option2}','',$video_html1);
                                //     $video_html1= str_replace('{option3}','',$video_html1);
                                //     $video_html1= str_replace('{option4}','',$video_html1);
                                    
                                //     $options = new Options();
                                //     $options->setIsRemoteEnabled(true);
                                //     $dompdf = new Dompdf($options);
                                //     $dompdf->loadHtml( $video_html1); 
                                      
                                //     // (Optional) Setup the paper size and orientation
                                //     if(!empty($request->ratio_name) && $request->ratio_name=="16:9"){
                                //         $customPaper = array(0,0,1440,1024);
                                //     }
                                //     if(!empty($request->ratio_name) && $request->ratio_name=="9:16"){
                                //           $customPaper = array(0,0,600,800);
                                //     }
                                    
                                //     // $customPaper = array(0,0,2048,1152);
                                //     // $customPaper = array(0,0,2048,1152);
                                //     $dompdf->setPaper($customPaper);
                                //     $dompdf->curlAllowUnsafeSslRequests = true;
                                    
                        
                                    
                                //     // Render the HTML as PDF
                                //     $dompdf->render();
                                //   // $pdfname= 'Brochure'.time().'.pdf';
                                    
                                //     $pdfpath = "uploads/quiz_temp_pdf/".$user->id;
                                //     $pdf_url = $pdfpath."/".time().'.pdf';
                                //     $pdf_path=public_path()."/".$pdf_url;
                                //     $destinationpdfPath = public_path($pdfpath);   
                                //     if( !is_dir( $destinationpdfPath ) ) mkdir( $destinationpdfPath, 0755, true );
                                    
                                //     file_put_contents($pdf_path, $dompdf->output());
                                     
                                //     $imagick = new \Imagick();
                                //     $imagick->readImage($pdf_path);
                                //     $imagepath = "uploads/quiz_temp_image/".$user->id;
                                //     $image_url = $imagepath."/".time().'.jpg';
                                //     $image_path=public_path()."/".$image_url;
                                //      $destinationPath = public_path($imagepath);   
                                //     if( !is_dir( $destinationPath ) ) mkdir( $destinationPath, 0755, true );
                                    
                                //     $imagick->writeImages($image_path, true);
                                    
                                      
                                //     if(file_exists($image_path)) { 
                                //         $txt = "file ".$image_path."\n";
                                //     }else{
                                //          $txt = "file ".public_path('uploads/design.jpg')." \n";
                                //     }
                                //     fwrite($txtfile, $txt);
                                //     $txt = "duration 1 \n";
                                //     fwrite($txtfile, $txt);
                                    
                                //     $txt = "file ".public_path()."/1-second-of-silence.mp3 \n";
                                //     fwrite($soundfile, $txt);
                                //     $txt = "outpoint 1 \n";
                                //     fwrite($soundfile, $txt);
                                }
                              
                            }
                            
                            if (strpos($video_html,'{option2}') !== false) {
                                if(isset($video->option2)){
                                     $video_html= str_replace('{option2}',$video->option2,$video_html);
                                     
                                //     $video_html1=$video_html;
                                     
                                //     $video_html1= str_replace('{option3}','',$video_html1);
                                //     $video_html1= str_replace('{option4}','',$video_html1);
                                    
                                //     $options = new Options();
                                //     $options->setIsRemoteEnabled(true);
                                //     $dompdf = new Dompdf($options);
                                //     $dompdf->loadHtml( $video_html1); 
                                      
                                //     // (Optional) Setup the paper size and orientation
                                //     if(!empty($request->ratio_name) && $request->ratio_name=="16:9"){
                                //         $customPaper = array(0,0,1440,1024);
                                //     }
                                //     if(!empty($request->ratio_name) && $request->ratio_name=="9:16"){
                                //           $customPaper = array(0,0,600,800);
                                //     }
                                    
                                //     // $customPaper = array(0,0,2048,1152);
                                //     // $customPaper = array(0,0,2048,1152);
                                //     $dompdf->setPaper($customPaper);
                                //     $dompdf->curlAllowUnsafeSslRequests = true;
                                    
                        
                                    
                                //     // Render the HTML as PDF
                                //     $dompdf->render();
                                //   // $pdfname= 'Brochure'.time().'.pdf';
                                    
                                //     $pdfpath = "uploads/quiz_temp_pdf/".$user->id;
                                //     $pdf_url = $pdfpath."/".time().'.pdf';
                                //     $pdf_path=public_path()."/".$pdf_url;
                                //     $destinationpdfPath = public_path($pdfpath);   
                                //     if( !is_dir( $destinationpdfPath ) ) mkdir( $destinationpdfPath, 0755, true );
                                    
                                //     file_put_contents($pdf_path, $dompdf->output());
                                     
                                //     $imagick = new \Imagick();
                                //     $imagick->readImage($pdf_path);
                                //     $imagepath = "uploads/quiz_temp_image/".$user->id;
                                //     $image_url = $imagepath."/".time().'.jpg';
                                //     $image_path=public_path()."/".$image_url;
                                //      $destinationPath = public_path($imagepath);   
                                //     if( !is_dir( $destinationPath ) ) mkdir( $destinationPath, 0755, true );
                                    
                                //     $imagick->writeImages($image_path, true);
                                    
                                      
                                //     if(file_exists($image_path)) { 
                                //         $txt = "file ".$image_path."\n";
                                //     }else{
                                //          $txt = "file ".public_path('uploads/design.jpg')." \n";
                                //     }
                                //     fwrite($txtfile, $txt);
                                //     $txt = "duration 1 \n";
                                //     fwrite($txtfile, $txt);
                                    
                                //     $txt = "file ".public_path()."/1-second-of-silence.mp3 \n";
                                //     fwrite($soundfile, $txt);
                                //     $txt = "outpoint 1 \n";
                                //     fwrite($soundfile, $txt);
                                }
                              
                            }
                            
                            if (strpos($video_html,'{option3}') !== false) {
                                if(isset($video->option3)){
                                     $video_html= str_replace('{option3}',$video->option3,$video_html);
                                     
                                //     $video_html1=$video_html;
                                     
                                //     $video_html1= str_replace('{option4}','',$video_html1);
                                    
                                //     $options = new Options();
                                //     $options->setIsRemoteEnabled(true);
                                //     $dompdf = new Dompdf($options);
                                //     $dompdf->loadHtml( $video_html1); 
                                      
                                //     // (Optional) Setup the paper size and orientation
                                //     if(!empty($request->ratio_name) && $request->ratio_name=="16:9"){
                                //         $customPaper = array(0,0,1440,1024);
                                //     }
                                //     if(!empty($request->ratio_name) && $request->ratio_name=="9:16"){
                                //           $customPaper = array(0,0,600,800);
                                //     }
                                    
                                //     // $customPaper = array(0,0,2048,1152);
                                //     // $customPaper = array(0,0,2048,1152);
                                //     $dompdf->setPaper($customPaper);
                                //     $dompdf->curlAllowUnsafeSslRequests = true;
                                    
                        
                                    
                                //     // Render the HTML as PDF
                                //     $dompdf->render();
                                //   // $pdfname= 'Brochure'.time().'.pdf';
                                    
                                //     $pdfpath = "uploads/quiz_temp_pdf/".$user->id;
                                //     $pdf_url = $pdfpath."/".time().'.pdf';
                                //     $pdf_path=public_path()."/".$pdf_url;
                                //     $destinationpdfPath = public_path($pdfpath);   
                                //     if( !is_dir( $destinationpdfPath ) ) mkdir( $destinationpdfPath, 0755, true );
                                    
                                //     file_put_contents($pdf_path, $dompdf->output());
                                     
                                //     $imagick = new \Imagick();
                                //     $imagick->readImage($pdf_path);
                                //     $imagepath = "uploads/quiz_temp_image/".$user->id;
                                //     $image_url = $imagepath."/".time().'.jpg';
                                //     $image_path=public_path()."/".$image_url;
                                //      $destinationPath = public_path($imagepath);   
                                //     if( !is_dir( $destinationPath ) ) mkdir( $destinationPath, 0755, true );
                                    
                                //     $imagick->writeImages($image_path, true);
                                    
                                      
                                //     if(file_exists($image_path)) { 
                                //         $txt = "file ".$image_path."\n";
                                //     }else{
                                //          $txt = "file ".public_path('uploads/design.jpg')." \n";
                                //     }
                                //     fwrite($txtfile, $txt);
                                //     $txt = "duration 1 \n";
                                //     fwrite($txtfile, $txt);
                                    
                                //     $txt = "file ".public_path()."/1-second-of-silence.mp3 \n";
                                //     fwrite($soundfile, $txt);
                                //     $txt = "outpoint 1 \n";
                                //     fwrite($soundfile, $txt);
                                     
                                }
                              
                            }
                            
                            if (strpos($video_html,'{option4}') !== false) {
                                if(isset($video->option4)){
                                     $video_html= str_replace('{option4}',$video->option4,$video_html);
                                }
                              
                            }
                            if($key!='0'){
                                $cmd .=',';
                            }
                         
                            //echo $video_html;die;
                             //$video->video_html=$video_html;
                            
                            /***********************set question ***********************/
                             $options = new Options();
                             $options->setIsRemoteEnabled(true);
                             $dompdf = new Dompdf($options);
                             $dompdf->loadHtml( $video_html); 
                              
                            // (Optional) Setup the paper size and orientation
                            if(!empty($request->ratio_name) && $request->ratio_name=="16:9"){
                                $customPaper = array(0,0,1440,1024);
                            }
                            if(!empty($request->ratio_name) && $request->ratio_name=="9:16"){
                                   $customPaper = array(0,0,600,800);
                            }
                            
                            // $customPaper = array(0,0,2048,1152);
                            // $customPaper = array(0,0,2048,1152);
                            $dompdf->setPaper($customPaper);
                            $dompdf->curlAllowUnsafeSslRequests = true;
                            
                
                            
                            // Render the HTML as PDF
                            $dompdf->render();
                           // $pdfname= 'Brochure'.time().'.pdf';
                            
                            $pdfpath = "uploads/quiz_temp_pdf/".$user->id;
                            $pdf_url = $pdfpath."/".time().'.pdf';
                            $pdf_path=public_path()."/".$pdf_url;
                            $destinationpdfPath = public_path($pdfpath);   
                            if( !is_dir( $destinationpdfPath ) ) mkdir( $destinationpdfPath, 0755, true );
                            
                            file_put_contents($pdf_path, $dompdf->output());
                             
                            $imagick = new \Imagick();
                            $imagick->readImage($pdf_path);
                            $imagepath = "uploads/quiz_temp_image/".$user->id;
                            $image_url = $imagepath."/".time().'.jpg';
                            $image_path=public_path()."/".$image_url;
                             $destinationPath = public_path($imagepath);   
                            if( !is_dir( $destinationPath ) ) mkdir( $destinationPath, 0755, true );
                            
                            $imagick->writeImages($image_path, true);
                            
                           
                           $audio_path = public_path().'/'.$video->$audio_column;
                            
                            $duration = shell_exec("ffprobe -i $audio_path -show_entries format=duration");
                            $duration = str_replace("\n", "", $duration);
                           
                           
                           $duration = str_replace("[FORMAT]", "", $duration);
                           $duration = str_replace("[/FORMAT]", "", $duration);
                           $duration = str_replace("duration=", "", $duration);
                        
                          
                            if(file_exists($image_path)) { 
                                $txt = "file ".$image_path."\n";
                            }else{
                                 $txt = "file ".public_path('uploads/design.jpg')." \n";
                            }
                            fwrite($txtfile, $txt);
                            $txt = "duration ".$duration." \n";
                            fwrite($txtfile, $txt);
    
                            
                            $txt = "file ".public_path()."/".$video->$audio_column."\n";
                            fwrite($soundfile, $txt);
                            $txt = "outpoint ".$duration." \n";
                            fwrite($soundfile, $txt);
                            
                            $starttime=$endtime;
                            //$endtime=  $endtime+4+$duration;
                            $endtime=  $endtime+$duration;
                            //$cmd .= "drawtext=text='%{eif\:trunc(mod((($endtime-t)/3600),24))\:d\:2}\:%{eif\:trunc(mod((($endtime-t)/60),60))\:d\:2}\:%{eif\:trunc(mod($endtime-t\,60))\:d\:2}':fontcolor=DarkSlateGray:fontfile=/home/readyvids/public_html/public/OpenSans-Regular.ttf:fontsize=40: \x=1280: y=20:enable='between(t,$starttime,$endtime)', \drawtext=text='': fontcolor=orange:fontfile=/home/readyvids/public_html/public/OpenSans-Regular.ttf: fontsize=24: x=680: y=20:enable=";
                            $cmd .= "drawtext=text='%{eif\:trunc(mod((($endtime-t)/3600),24))\:d\:2}\:%{eif\:trunc(mod((($endtime-t)/60),60))\:d\:2}\:%{eif\:trunc(mod($endtime-t\,60))\:d\:2}':fontcolor=DarkSlateGray:fontfile=/home/readyvids/public_html/public/OpenSans-Regular.ttf:fontsize=40: \x=1240: y=20:enable='between(t,$starttime,$endtime)'";
                           
                           /*************************set quetion********************************/
                           
                            sleep(2);
                            
                            
                           /*******************display answer***************/
                           
                            if($video->answer==$video->option1){
                                
                                if (strpos($video_html,"{answeroption1}") !== false) {
                                    $video_html= str_replace('{answeroption1}',"background-color:".$answerbackgroundcolor,$video_html);
                                }
                            
                            }
                            if($video->answer==$video->option2){
                                if (strpos($video_html,"{answeroption2}") !== false) {
                                    $video_html= str_replace('{answeroption2}',"background-color:".$answerbackgroundcolor,$video_html);
                                }
                               
                            }
                            if($video->answer==$video->option3){
                               if (strpos($video_html,"{answeroption3}") !== false) {
                                    $video_html= str_replace('{answeroption3}',"background-color:".$answerbackgroundcolor,$video_html);
                                }
                            }
                            if($video->answer==$video->option4){
                               if (strpos($video_html,"{answeroption4}") !== false) {
                                    $video_html= str_replace('{answeroption4}',"background-color:".$answerbackgroundcolor,$video_html);
                                }
                            }
                           
                             $options = new Options();
                             $options->setIsRemoteEnabled(true);
                             $dompdf = new Dompdf($options);
                             $dompdf->loadHtml( $video_html); 
                              
                            // (Optional) Setup the paper size and orientation
                            if(!empty($request->ratio_name) && $request->ratio_name=="16:9"){
                                $customPaper = array(0,0,1440,1024);
                            }
                            if(!empty($request->ratio_name) && $request->ratio_name=="9:16"){
                                   $customPaper = array(0,0,600,800);
                            }
                            
                            // $customPaper = array(0,0,2048,1152);
                            // $customPaper = array(0,0,2048,1152);
                            $dompdf->setPaper($customPaper);
                            $dompdf->curlAllowUnsafeSslRequests = true;
                            
                
                            
                            // Render the HTML as PDF
                            $dompdf->render();
                           // $pdfname= 'Brochure'.time().'.pdf';
                            
                            $pdfpath = "uploads/quiz_temp_pdf/".$user->id;
                            $pdf_url = $pdfpath."/".time().'.pdf';
                            $pdf_path=public_path()."/".$pdf_url;
                            $destinationpdfPath = public_path($pdfpath);   
                            if( !is_dir( $destinationpdfPath ) ) mkdir( $destinationpdfPath, 0755, true );
                            
                            file_put_contents($pdf_path, $dompdf->output());
                             
                            $imagick = new \Imagick();
                            $imagick->readImage($pdf_path);
                            $imagepath = "uploads/quiz_temp_image/".$user->id;
                            $image_url = $imagepath."/".time().'.jpg';
                            $image_path=public_path()."/".$image_url;
                             $destinationPath = public_path($imagepath);   
                            if( !is_dir( $destinationPath ) ) mkdir( $destinationPath, 0755, true );
                            
                            $imagick->writeImages($image_path, true);
                            
                            $answeraudio_column="answer_".$audio_column;
                           
                           $answer_audio_path = public_path().'/'.$video->$answeraudio_column;
                            
                            $answer_duration = shell_exec("ffprobe -i $answer_audio_path -show_entries format=duration");
                            $answer_duration = str_replace("\n", "", $answer_duration);
                           
                           
                           $answer_duration = str_replace("[FORMAT]", "", $answer_duration);
                           $answer_duration = str_replace("[/FORMAT]", "", $answer_duration);
                           $answer_duration = str_replace("duration=", "", $answer_duration);
                            
                          
                            if(file_exists($image_path)) { 
                                $txt = "file ".$image_path."\n";
                            }else{
                                 $txt = "file ".public_path('uploads/design.jpg')." \n";
                            }
                            fwrite($txtfile, $txt);
                            $txt = "duration ".$answer_duration." \n";
                            fwrite($txtfile, $txt);
    
                            
                            $txt = "file ".public_path()."/".$video->$answeraudio_column."\n";
                            fwrite($soundfile, $txt);
                            $txt = "outpoint ".$answer_duration." \n";
                            fwrite($soundfile, $txt);
                           
                           $starttime=$endtime;
                           $endtime= $endtime+$answer_duration;
                           
                          // $cmd .="'between(t,$starttime,$endtime)'";
                           /*************************************/
                            
                            sleep(2);
                        }
                       
                        fclose($txtfile);
    
                        fclose($soundfile);
                       
                       
                        
                         $file_path = public_path().'/uploads/quiz_temp_file/' . $user->id.'/input.txt';
                        $file_path1 = public_path().'/uploads/quiz_temp_file/' . $user->id.'/input1.txt';
                        
                         $destinationVideoPath=public_path()."/uploads/quiz_make_video/".$user->id;
                        if( !is_dir( $destinationVideoPath ) ) mkdir( $destinationVideoPath, 0755, true );
                        
                        if(!empty($request->video_format)){
                            $video_url = "/uploads/quiz_make_video/".$user->id."/".time().'.'.$request->video_format;
                            $output_path= public_path().$video_url;
                            $main_url = "/uploads/quiz_make_video/".$user->id."/main_".time().'.'.$request->video_format;
                            $timer_output_path =public_path().$main_url;
                            $watermark_url = "/uploads/quiz_make_video/".$user->id."/watermark_".time().'.'.$request->video_format;
                            $watermark_output_path =public_path().$watermark_url;
                        }else{ 
                            $video_url = "/uploads/quiz_make_video/".$user->id."/".time().'.mp4';
                            $output_path= public_path().$video_url;
                            $main_url = "/uploads/quiz_make_video/".$user->id."/main_".time().'.mp4';
                            $timer_output_path =public_path().$main_url;
                            $watermark_url = "/uploads/quiz_make_video/".$user->id."/watermark".time().'.mp4';
                            $watermark_output_path =public_path().$watermark_url;
                        }
    
                        if(!empty($request->ratio_name)){
                            $ratio=" -r 15 -aspect ".$request->ratio_name." -strict -2";
                            //-r 15 -vf scale=1080:-1 -aspect 1:1 -strict -2
                        }
                        
                        
                        //$output= shell_exec("ffmpeg -f concat -i $file_path -f concat -i $file_path1 -r 25 -pix_fmt yuv420p  $output_path 2>&1");
                        if(!empty($request->ratio_name) && $request->ratio_name=="16:9"){
                            $output= shell_exec("ffmpeg -f concat -safe 0 -i $file_path -f concat -safe 0 -i $file_path1 -r 15 -c:v libx264 -pix_fmt yuv420p $ratio $output_path 2>&1");
                        }
                         if(!empty($request->ratio_name) && $request->ratio_name=="9:16"){
                             $output= shell_exec("ffmpeg -f concat -safe 0 -i $file_path -f concat -safe 0 -i $file_path1 -r 15 -c:v libx264 -pix_fmt yuv420p $output_path 2>&1");
                        }
                        
                        sleep(2);
                        $vurl =env('APP_URL')."/public/".$video_url;
                        $cmd = 'ffmpeg -i '.$vurl.' -vf "'.$cmd.'[out]" -codec:a copy '.$timer_output_path;
                        $timeroutput = shell_exec("$cmd 2>&1");
                        
                        sleep(2);
                        
                        $without_watermark = env('APP_URL')."/public/".$main_url;
                        $watermark = 'https://readyvids.manageprojects.in/public/watermark_new.png';
                        $watremarkoutput= shell_exec("ffmpeg -i $without_watermark -i $watermark -filter_complex 'overlay=x=(main_w-overlay_w-200):y=(main_h-overlay_h-700)' -codec:a copy $watermark_output_path 2>&1");//top right
                       
                       
                        //               echo "<pre>";
                        // print_r($watremarkoutput);die;
                        //echo env('APP_URL')."/public/".$video_url;die;
                        $myvideo['user_id']= $user->id;
                        $myvideo['video']= env('APP_URL')."/public/".$watermark_url;
                        $myvideo['without_watermark_video']= env('APP_URL')."/public/".$main_url;
                        $myvideo['with_watermark_video']= env('APP_URL')."/public/".$watermark_url;
                        $myvideo['ratio']= $request->ratio_name;
                        
                       
                        if($request->video_id!=''){
                            $result=MyQuizVideo::findorfail($request->video_id);
                            $result->video =  env('APP_URL')."/public/".$watermark_url;
                            $result->without_watermark_video =  env('APP_URL')."/public/".$main_url;
                            $result->with_watermark_video =  env('APP_URL')."/public/".$watermark_url;
                        }else{
                            $result['video']=MyQuizVideo::create($myvideo);
                        }
                        
                        
                        $size = filesize($output_path)/1024;
                        $result['size']= number_format($size, 2, '.', '');
                        $msg  = array('status'=>true,'message' => "Video created successfully",'data'=>$result);
                        echo json_encode($msg);
                    }
                    else{
                         $msg  = array('status'=>false,'message' => "Video is not created successfully because data is not available regarding your parameter",'data'=>[]);
                        echo json_encode($msg);
                    }
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
    
    public function myQuizVideo(Request $request){
        ini_set('max_execution_time', '0');
        if($request->token!=''){
            try{
                if($user = JWTAuth::authenticate($request->token)){
                   $data=MyQuizVideo::where('user_id','=',$user->id)->skip($request->quizoffset)->take($request->quizlimit)->orderBy('id','desc')->get();
                    
                   
                    $msg  = array('status'=>true,'message' => "My quiz video get successfully",'data'=>$data);
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
    
    public function removeQuizVideo(Request $request){
        ini_set('max_execution_time', '0');
        if($request->token!=''){
            try{
                if($user = JWTAuth::authenticate($request->token)){
                    $video= MyQuizVideo::find($request->id);
                    $video->delete();
                   
                    $msg  = array('status'=>true,'message' => "Quiz Video delete successfully");
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
    
       /*add watermark any video*/
    public function watermarkQuizVideo(Request $request){
      
        ini_set('max_execution_time', '0');
        if($request->token!=''){
            try{
                if($user = JWTAuth::authenticate($request->token)){
                    
                    if(!empty($request->video) && !empty($request->watermark)){
                        
                        if(!empty($request->video_format)){
                            $fileNameToStore= time().".".$request->video_format;
                        }else{ 
                            $fileNameToStore= time().".mp4";
                        }
                        $output_path = public_path('/uploads/make_video/'.$user->id."/watermark_".$fileNameToStore);
                        try{
                            if(!empty($request->position) && $request->position=='Top Left'){
                                
                                $output= shell_exec("ffmpeg -i $request->video -i $request->watermark -filter_complex 'overlay=10:80' -codec:a copy $output_path 2>&1");//top left
                            }
                            elseif(!empty($request->position) && $request->position=='Top Right'){
                                
                                  $output= shell_exec("ffmpeg -i $request->video -i $request->watermark -filter_complex 'overlay=(main_w-overlay_w-10):80' -codec:a copy $output_path 2>&1");//top right
                            
                            } 
                            elseif(!empty($request->position) && $request->position=='Center'){
                               
                               $output= shell_exec("ffmpeg -i $request->video -i $request->watermark -filter_complex 'overlay=x=(main_w-overlay_w)/2:y=(main_h-overlay_h)/2' -codec:a copy $output_path 2>&1");//center
                            }
                            elseif(!empty($request->position) && $request->position=='Bottom Left'){
                                
                                $output= shell_exec("ffmpeg -i $request->video -i $request->watermark -filter_complex 'overlay=10:(main_h-overlay_h-20)' -codec:a copy $output_path 2>&1");//bottom left
                            }
                            elseif(!empty($request->position) && $request->position=='Bottom Right'){
                                
                                $output= shell_exec("ffmpeg -i $request->video -i $request->watermark -filter_complex 'overlay=(main_w-overlay_w-10):(main_h-overlay_h-20)' -codec:a copy $output_path 2>&1");//bottom right
                            }
                            else{
                                $output= shell_exec("ffmpeg -i $request->video -i $request->watermark -filter_complex 'overlay=(main_w-overlay_w-10):80' -codec:a copy $output_path 2>&1");//top right
                            }
                           
                        }
                        catch(Exception $e){
                            echo "this will never execute";
                        }
                       $video_url= env("APP_URL")."public"."/uploads/make_video/".$user->id."/watermark_".$fileNameToStore;
                    //   if(file_exists($vurl)) { 
                    //           unlink($vurl); //remove the file
                    //     }
                    
                    }
                    $msg  = array('status'=>true,'message' => "Add watermark on video successfully",'data'=>$video_url);
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
    /******************************************************************end Quiz module*******************************************////
    
    
    /************************************************************Affiliate Module**************************************************/
    
         
    public function generateLink(Request $request){
    
        if($request->token!='' && $request->title!='' ){
            try{
                if($user = JWTAuth::authenticate($request->token)){
                    
                    $AffliateLink = new AffliateLink();
                    $AffliateLink->user_id=$user->id;  
                    $AffliateLink->code=rand(100000,999999);
                    $AffliateLink->title=$request->title;
                    $AffliateLink->customize_name=$request->customize_name;
                    $AffliateLink->description=$request->description;
                    $AffliateLink->original_url=$request->original_url;
                    $AffliateLink->link='https://www.readyvids.com/affiliated/share.php?code='.$AffliateLink->code.'&&name='.$AffliateLink->customize_name;
                    
                    if(!empty($request->image)){
                        $image = $request->image;                                        
    
                        $base64_str = substr($image, strpos($image, ",")+1);
        
                        //decode base64 string
                        $image = base64_decode($base64_str);
                        $png_url = time().".png";
                        $path = public_path('uploads/metatag_image/' . $png_url);
        
                        
                        Image::make(file_get_contents($request->image))->save($path);
                     
                        $AffliateLink->image=env("APP_URL")."public/uploads/metatag_image/".$png_url;
                    }
                    
                   
                   
                    $AffliateLink->save();
                    $msg  = array('status'=>true,'message' => "Generate link successfully",'data'=>$AffliateLink);
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
    
    public function getMetadata(Request $request){
      
       
      $data = AffliateLink::where('link','=',$request->url)->first();
 
        if($data!=null)
        {   
            $msg  = array('status'=>true,'message' => "Fetch meta data successfully",'data'=>$data);
            echo json_encode($msg);
        }else{
            $msg  = array('status'=>false,'message' => "No url found","data"=>[]);
            echo json_encode($msg);
        }
    }
    
    public function generateLinkList(Request $request){
    
        if($request->token!=''){
            try{
                if($user = JWTAuth::authenticate($request->token)){
                    
                   $AffliateLink =AffliateLink::where('user_id','=',$user->id)->get();
                 
                    $msg  = array('status'=>true,'message' => "Get link list successfully",'data'=>$AffliateLink);
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
    
    public function generateLinkDelete(Request $request){
    
        if($request->token!='' && $request->id){
            try{
                if($user = JWTAuth::authenticate($request->token)){
                    
                   AffliateLink::where('user_id','=',$user->id)->where('id','=',$request->id)->delete();
                 
                    $msg  = array('status'=>true,'message' => "Link successfully deleted.",'data'=>[]);
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
    
    public function generateLinkEdit(Request $request){
    
        if($request->token!='' && $request->id!='' ){
            try{
                if($user = JWTAuth::authenticate($request->token)){
                    
                    $AffliateLink = AffliateLink::findorfail($request->id);
                  
                    $msg  = array('status'=>true,'message' => "Generate link get successfully",'data'=>$AffliateLink);
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
    
    public function generateLinkUpdate(Request $request){
   
        if($request->token!='' && $request->id!='' ){
            try{
                if($user = JWTAuth::authenticate($request->token)){
                    
                    $AffliateLink = AffliateLink::findorfail($request->id);
                 
                    $AffliateLink->title=$request->title;
                    $AffliateLink->customize_name=$request->customize_name;
                    $AffliateLink->description=$request->description;
                    $AffliateLink->original_url=$request->original_url;
                    $AffliateLink->link='https://www.readyvids.com/affiliated/share.php?code='.$AffliateLink->code.'&&name='.$AffliateLink->customize_name;
                    
                    $image = $request->image;                                        

                    $base64_str = substr($image, strpos($image, ",")+1);
                    if($base64_str!=''){
                        //decode base64 string
                        $image = base64_decode($base64_str);
                        $png_url = time().".png";
                        $path = public_path('uploads/metatag_image/' . $png_url);
        
                        
                        Image::make(file_get_contents($request->image))->save($path);
                     
                        $AffliateLink->image=env("APP_URL")."public/uploads/metatag_image/".$png_url;
                    }
                   
                   
                    $AffliateLink->update();
                    $msg  = array('status'=>true,'message' => "Generate link updated successfully",'data'=>$AffliateLink);
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
    
    public function registerBy($data){
        $registerBy = AffliateLink::where('code','=',$data['code'])->first();
        $data['register_user_by_id'] = $registerBy->user_id;
        $commission = new AffliateCommission();
        $commission->register_user_id =$data['register_user_id'];
         $commission->register_user_by_id =$registerBy->user_id;
        $commission->code =$data['code'];
        $commission->customize_name =$data['customize_name'];
        $commission->save();
 

    }
    
    public function getCommission(Request $request){
    
        if($request->token!='' && $request->link_id!='' ){
            try{
                if($user = JWTAuth::authenticate($request->token)){
                    
                    $AffliateLink = AffliateLink::findorfail($request->link_id);
                    $num = '#'.sprintf("%06d", $AffliateLink->id);
                    $AffliateLink->link_id= $num;
                    $AffliateLink->signup = User::where('code','=',$AffliateLink->code)->count(); 
                     $AffliateLink->totalsignup = User::where('code','=',$AffliateLink->code)->get(); 
                    $AffliateLink->subscription = User::where('code','=',$AffliateLink->code)->where('package_id','!=','0')->count(); 
                    $AffliateLink->amount = AffliateCommission::where('code','=',$AffliateLink->code)->sum('commission'); 
                    $msg  = array('status'=>true,'message' => "Generate link get successfully",'data'=>$AffliateLink);
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
    
    public function getDasboardValue(Request $request){
    
        if($request->token!=''){
            try{
                if($user = JWTAuth::authenticate($request->token)){
                    
                    $AffliateLink['total_sales'] =AffliateCommission::where('register_user_by_id','=',$user->id)->sum('sales');
                      
                    $AffliateLink['total_link'] =AffliateLink::where('user_id','=',$user->id)->count();
                    
                    $AffliateLink['total_clicks'] =AffliateLink::where('user_id','=',$user->id)->sum('click');
                     
                    $AffliateLink['total_signup'] =AffliateCommission::where('register_user_by_id','=',$user->id)->count();
                    
                    $AffliateLink['total_conversion'] =AffliateCommission::where('register_user_by_id','=',$user->id)->where('package_id','!=','0')->count();
                     
                    $AffliateLink['total_commission'] =AffliateCommission::where('register_user_by_id','=',$user->id)->sum('commission');
                    
                    $AffliateLink['current_month_commission'] =AffliateCommission::where('register_user_by_id','=',$user->id)->whereMonth('created_at','=',date('m'))->sum('commission');
                    
                    $AffliateLink['last_month_commission'] =AffliateCommission::where('register_user_by_id','=',$user->id)->whereMonth('created_at','=',date('m', strtotime('-1 month')) )->sum('commission');
                    
                    $msg  = array('status'=>true,'message' => "Get link list successfully",'data'=>$AffliateLink);
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
    
    /************************************************************End Affiliate Module*************************************************/
    
    /*********************************************************Payment regarding api ***************************************************/
    
    public function payment(Request $request)
    {
      
        // $stripe = new \Stripe\StripeClient('sk_test_51NXkq3SHlFVdYHTr0D7gZdKB6RZTkZir8I6GHl0EbJptEDkV5tN5kc9P6hBKdC7cbOQEtSmLBwgSM14orlcJp8Nr004dVUwn03');
        // $country = $stripe->countrySpecs->retrieve('US', []);
        // dd($country);
        
        $validator = Validator::make($request->all(), [
            'billingName' => 'required',
            'cardNumber' => 'required',
          
             'month' => 'required',
            'year' => 'required',
            'cardCvc' => 'required'
        ]);

        if ($validator->fails()) {
            $request->session()->flash('danger', $validator->errors()->first());
            return response()->redirectTo('/');
        }
        
        
         $charge = null;
        try {
                
                $token = $this->createToken($request);
              
 
                $charge=$this->stripe->paymentIntents->create([
                  'amount' => 99*100,
                  'currency' => 'inr',
                  'payment_method_types' => ['card'],
                  'payment_method'=>$token->id,
                   'error_on_requires_action' => false,
			        'confirm' => true,
			        'setup_future_usage' => 'on_session',
				    'description'=>'test',
                    // 'shipping' => [
                    //     'name' => 'Jenny Rosen',
                    //     'address' => [
                    //       'line1' => '510 Townsend St',
                    //       'postal_code' => '98140',
                    //       'city' => 'San Francisco',
                    //       'state' => 'CA',
                    //       'country' => 'US',
                    //     ],
                    //  ],
                ]);
                
                $msg  = array('success'=>true,'message' => "Payment successfully",'data'=>$charge,'token'=>$token);
                echo json_encode($msg);
                    
            } catch (Exception $e) {
               $msg  = array('success'=>false,'message' => "Could Not Create Token.",'data'=>[]);
                echo json_encode($msg);
      
            }
      
    }

    private function createToken($cardData)
    {
        $token = null;
        try {
           if(strlen($cardData['year'])==2){
                $dt = DateTime::createFromFormat('y', trim($cardData['year']));
                $year = $dt->format('Y'); // output: 2012
             }else{
                 $year = $cardData['year'];
             }
            $stripe = new \Stripe\StripeClient('pk_test_51NXkq3SHlFVdYHTrj444e8AAUJPVjNmltCWhmRnu1Lb6mTahAAG4uXbqY0pQmGY3p4v8ypwFohmKHWEME4lUiKn300EtpiIgjq');
            $token=$stripe->paymentMethods->create([
              'type' => 'card',
              'card' => [
                    'number' => $cardData['cardNumber'],
                    'exp_month' => $cardData['month'],
                    'exp_year' => $year,
                    'cvc' => $cardData['cardCvc']
                ],
                // 'billing_details'=>[
                //     "address"=>[ "country"=>"US"]     
                // ],
        
             
       
             ]);
             
            // $token = $this->stripe->tokens->create([
            //     'card' => [
            //         'number' => $cardData['cardNumber'],
            //         'exp_month' => $cardData['month'],
            //         'exp_year' => $year,
            //         'cvc' => $cardData['cardCvc']
            //     ]
            // ]);
        } catch (CardException $e) {
            $token['error'] = $e->getError()->message;
        } catch (Exception $e) {
            $token['error'] = $e->getMessage();
        }
       
        return $token;
    }

    private function createCharge($tokenId, $amount,$description)
    {
        
        $charge = \Stripe\Charge::create([
  'amount' => 999,
  'currency' => 'usd',
  'description' => 'Example charge',
  'source' => 'tok_1NbLkgSHlFVdYHTrh5v0Exdh',
]);

print_r($charge);die;
        $charge = null;
        try {
            $charge = $this->stripe->charges->create([
                'amount' => $amount,
                'currency' => 'INR',//'usd',
                'source' => $tokenId,
                'description' => $description
            ]);
        } catch (Exception $e) {
            print_r($e);
            $charge['error'] = $e->getMessage();
        }
        print_r($charge);;die;
        return $charge;
    }
    
    public function successUrl(Request $request)

    {   

        dd($request);

                

    }
     public function cancelUrl(Request $request)

    {   

        dd($request);

                

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



    /**Term Condition Page */

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

    

    /**Privacy Policy Page */

    public function policy(Request $request)

    {   

        $policy = Pages::where(['title'=>'Privacy Policy'])->first()->toarray();

        if (!empty($policy)) {

            return response()->json([

                'data' =>$policy,

                'success' => true,

                'message' => 'Get Privacy policy page successfully.',

                'status'=>200

            ]);

        }else{

            return response()->json([

                'success' => false,

                'message' => 'Get Privacy policy page Not Found.',

            ]);

        }

                

    }



    /**faq Page */

    public function faq(Request $request)

    {   

        $faq = Faq::where(['active'=>1])->orderBy('id','DESC')->get()->toarray();

        if (!empty($faq)) {

            return response()->json([

                'data' =>$faq,

                'success' => true,

                'message' => 'Get data Faq successfully.',

                'status'=>200

            ]);

        }else{

            return response()->json([

                'success' => false,

                'message' => 'Get data Faq Not Found.',

            ]);

        }

                

    }



 



    /**State List */

    public function getState(Request $request){

       $response = States::where(['country_id'=>'105'])->orderBy('name','ASC')->get();

        if (count($response)>0) {
            
            $msg  = array('status'=>true,'message' => "Get State successfully",'data'=>$response);
            echo json_encode($msg);
           

        } else {
            
            $msg  = array('status'=>false,'message' => "No State Found",'data'=>[]);
            echo json_encode($msg);

          
        }

      

    }



    /**City List */

    public function getCity(Request $request){
        
        $data = $request->only('state');

        $validator = Validator::make($data, [

            'state'=>'required',

        ]);


        //Send failed response if request is not valid

        if ($validator->fails()) {

            return response()->json(['success'=>false,'message' => $validator->messages()->first()], 200);

        }

   
          

        $response = Cities::where(['state_id'=>$request->state])->orderBy('city','ASC')->get();

            
        if (count($response)>0) {
            
            $msg  = array('status'=>true,'message' => "Get City successfully",'data'=>$response);
            echo json_encode($msg);

           

        } else {
            
            $msg  = array('status'=>false,'message' => "No City Found",'data'=>[]);
            echo json_encode($msg);
            
           

        }

         
    }



    /**Package List */

    public function getpackage()

    {   

        $packages = Package::where('active','=',1)->orderBy('id','DESC')->get();

        if (count($packages)>0) {

            foreach ($packages as $value) {

                $color = explode ('#',$value->package_color);

                $value->package_color=$color[1];

            }

            return response()->json([

                'data' =>$packages,

                'success' => true,

                'message' => 'Get Package List Successfully.',

                'status'=>200

            ]);

        }else{

            return response()->json([

                'success' => false,

                'message' => 'Package List Not Found.',

            ]);

        }

                

    }
    
    



    /**hours time List */

    public function gethoursTime(Request $request)

    {   

        $hourstime = Hour::where('active','=',1)->orderBy('time','ASC')->get();

        if (count($hourstime)>0) {

            foreach ($hourstime as $value) {

                $value->time = date('h:i A',strtotime($value->time));

            }

            return response()->json([

                'data' =>$hourstime,

                'success' => true,

                'message' => 'Get Hours List Successfully.',

                'status'=>200

            ]);

        }else{

            return response()->json([

                'success' => false,

                'message' => 'Hours List Not Found.',

            ]);

        }

                

    }



    /**party type List */

    public function getpartyType(Request $request)

    {   

        $party = Party::where('active','=',1)->orderBy('type','ASC')->get();

        if (count($party)>0) {

            return response()->json([

                'data' =>$party,

                'success' => true,

                'message' => 'Get Party List Successfully.',

                'status'=>200

            ]);

        }else{

            return response()->json([

                'success' => false,

                'message' => 'Party List Not Found.',

            ]);

        }
             

    }



    /**venue type List */

    public function getVenue(Request $request)

    {   

        $venue = Venue::where('active','=',1)->orderBy('type','ASC')->get();

        if (count($venue)>0) {

            return response()->json([

                'data' =>$venue,

                'success' => true,

                'message' => 'Get Venue List Successfully.',

                'status'=>200

            ]);

        }else{

            return response()->json([

                'success' => false,

                'message' => 'Venue List Not Found.',

            ]);

        }

                

    }



    /**Add Card Details */

    public function addcardDetails(Request $request)

    {

        $data=$request->only('token','card_number','name','month_expire','cvv','type');

        $validator = Validator::make($data, [

            'token' => 'required',

            'card_number' => 'required|min:16|max:16|unique:bank_details',

            // 'card_number' => 'required|unique:bank_details|min:16|max:16',

            'name' => 'required',

            'month_expire' => 'required',

            'type' => 'required',

            'cvv' => 'required|min:3|max:3',

        ]);



        //Send failed response if request is not valid

        if ($validator->fails()) {

            return response()->json(['success'=>false,'message' => $validator->messages()->first()], 200);

        }

            

        try{

            if($user= JWTAuth::authenticate($request->token)){

                Bank::insert([

                    'user_id' => $user->id,

                    'card_number' => $data['card_number'],

                    'name' => $data['name'],

                    'month_expire' => $data['month_expire'],

                    'cvv' => $data['cvv'],

                    'type' => $data['type'],

                ]);

                return response()->json([

                    'success' => true,

                    'message' => 'Card detail save successfully',

                    'status'=>200

                ]);

            

            }



        }catch (JWTException $exception) {

            return response()->json([

                'success' => true,

                'message' => 'Token is expired'

            ]);

        }

        

        

    }



    /**Card list */

    public function getcardList(Request $request)

    {

        $data=$request->only('token');

        $validator = Validator::make($data, [

            'token' => 'required',

        ]);



        //Send failed response if request is not valid

        if ($validator->fails()) {

            return response()->json(['success'=>false,'message' => $validator->messages()->first()], 200);

        }

            

        try{

            if($user= JWTAuth::authenticate($request->token)){

                $response = Bank::where(['user_id' => $user->id,'active'=>1])->get();

                if (count($response)>0) {

                    return response()->json([

                        'data' => $response,

                        'success' => true,

                        'message' => 'Get Card List successfully',

                        'status'=>200

                    ]);

                } else {

                    return response()->json([

                        'success' => false,

                        'message' => 'Card List Not Found',

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



    /**Remove Card */

    public function deleteCard(Request $request)

    {

        $data=$request->only('token','id');

        $validator = Validator::make($data, [

            'token' => 'required',

            'id' => 'required',

        ]);



        //Send failed response if request is not valid

        if ($validator->fails()) {

            return response()->json(['success'=>false,'message' => $validator->messages()->first()], 200);

        }

            

        try{

            if($user= JWTAuth::authenticate($request->token)){

                $response = Bank::where(['user_id' => $user->id,'id'=>$data['id']])->update(['active'=>0]);

                if ($response) {

                    return response()->json([

                        'success' => true,

                        'message' => 'Card Removed successfully',

                        'status'=>200

                    ]);

                } else {

                    return response()->json([

                        'success' => false,

                        'message' => 'Card Not Removed',

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



    /**add Event */

    public function addEvent(Request $request)

    {

        $data = $request->only('token','package_id','extra_hours','city_id','girl_id','party_date','party_time','party_type','name','phone','email','venue_type','venue_address','venue_city','venue_zipcode','card_id','transaction_id','transaction_status','amount','end_time','transaction_token','additional_note','state');

        $validator = Validator::make($data, [

            'token' => 'required',

            'package_id' => 'required',

            // 'country_id' => 'required',

            // 'state_id' => 'required',

            'city_id' => 'required',

            'girl_id' => 'required',

            'party_date' => 'required',

            'party_time' => 'required',

            'party_type' => 'required',

            'name' => 'required',

            'phone' => 'required',

            'email' => 'required',

            'venue_type' => 'required',

            'venue_address' => 'required',

            'venue_city' => 'required',

            'venue_zipcode' => 'required',

            // 'card_id' => 'required',

            'transaction_id' => 'required',

            'transaction_status' => 'required',

            'amount' => 'required',

            'end_time' => 'required',

            'transaction_token' => 'required',
            'state' => 'required',

        ]);



        //Send failed response if request is not valid

        if ($validator->fails()) {

            return response()->json(['success'=>false,'message' => $validator->messages()->first()], 200);

        }

        try{

            if($user= JWTAuth::authenticate($request->token)){

                $response = TransactionRequest::where(['user_id'=>$user->id,'transaction_token'=>$request->transaction_token,'package_id'=>$request->package_id,'amount'=>$request->amount,'status'=>1])->first();
                
                if ($response!="") {
                    TransactionRequest::where(['user_id'=>$user->id,'transaction_token'=>$request->transaction_token,'package_id'=>$request->package_id,'amount'=>$request->amount,'status'=>1])->update(['status'=>0]);
                    
                    $event = new Event;

                    $event->user_id = $user->id;

                    $event->show_type = $request->package_id;

                    $event->extra_hours = isset($request->extra_hours)&&!empty($request->extra_hours)?$request->extra_hours:'';

                    // $event->country_id = $request->country_id;

                    // $event->state_id = $request->state_id;

                    $event->city_id = $request->city_id;

                    $event->girl_id = $request->girl_id;

                    $event->party_date = $request->party_date;

                    $event->party_time = $request->party_time;

                    $event->party_type = $request->party_type;

                    $event->name = $request->name;

                    $event->phone = $request->phone;

                    $event->email = $request->email;

                    $event->venue_type = $request->venue_type;

                    $event->venue_address = $request->venue_address;

                    $event->venue_city = $request->venue_city;

                    $event->venue_zipcode = $request->venue_zipcode;
                    
                    $event->end_time = $request->end_time;

                    $event->state = $request->state;
                    
                    $event->additional_note = ($request->additional_note!="")?$request->additional_note:'';

                    $event->save();

                    if ($event->id!="") {

                        $order = new Order;

                        $order->user_id= $user->id;

                        $order->event_id= $event->id;

                        // $order->card_id= $request->card_id;

                        $order->transaction_id= $request->transaction_id;

                        $order->transaction_status= $request->transaction_status;

                        $order->amount= $request->amount;

                        $order->save();

                        if ($order->id!="") {

                            $oderstatus = new OrderStatus;

                            $oderstatus->user_id= $user->id;

                            $oderstatus->order_id = $order->id;

                            $oderstatus->save();

                            return response()->json([

                                'success' => true,

                                'message' => 'Event Created successfully',

                                'status'=>200

                            ]);

                        }else{

                            Event::where(['id'=>$event->id])->update(['status'=>0]);

                            return response()->json([

                                'success' => false,

                                'message' => 'Event Not Created successfully',

                            ]);

                        }

                        

                    } else {

                        return response()->json([

                            'success' => false,

                            'message' => 'Event Not Created Try After Some Time',

                        ]);

                    }
                }else{
                    return response()->json([

                        'success' => false,

                        'message' => 'Transaqtion Request Is Invalid',

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



    /**Event list */

    public function getEvent(Request $request)
    {
        $data=$request->only('token','search');

        $validator = Validator::make($data, [

            'token' => 'required',

        ]);

        //Send failed response if request is not valid

        if ($validator->fails()) {
            return response()->json(['success'=>false,'message' => $validator->messages()->first()], 200);
        }

        try{
            if($user= JWTAuth::authenticate($request->token)){
                // print_r($user->toarray());die;
                if ($data['search']!="") {
                    $where=['user_id' => $user->id,'active'=>1,'party_date'=>date('m/d/Y',strtotime($data['search']))];
                }else{
                    $where=['user_id' => $user->id,'active'=>1];
                }
                $response = Event::select('id','user_id','show_type','city_id','girl_id','extra_hours','name','party_date')->where($where)->orderBy('id','DESC')->get();
                // print_r($response->toarray());die;
                if (count($response)>0) {

                    foreach ($response as $key => $value) {

                        $value->user_id = Helper::userName($value->user_id);

                        $package = Package::where(['id'=>$value->show_type])->first();

                        $value->package_name=$package->package_title;

                        $value->hours=$package->package_hours+$value->extra_hours;

                        //$order = Order::where(['event_id'=>$value->id])->first();
                        //$value->amount=$order->amount;

                        $extra_hour_price = $package->extra_hour_price * $value->extra_hours;
                        $value->amount = (string)($package->package_price + $extra_hour_price);
                        
                        $value->city = Helper::cityName($value->city_id);

                        $girls = explode(',',$value->girl_id);

                        $img = Girls::where(['id'=>$girls[0]])->first();

                        $girlimg = explode(',',$img->image);

                        $value->image = url('uploads/girls').'/'.$girlimg[0];

                        unset($value->extra_hours);

                        unset($value->show_type);

                        unset($value->city_id);

                        unset($value->girl_id);

                    }

                    return response()->json([

                        'data' => $response,

                        'success' => true,

                        'message' => 'Event List successfully',

                        'status'=>200

                    ]);

                } else {

                    return response()->json([

                        'success' => false,

                        'message' => 'Event List Not Found',

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



    /**Event Details */

    public function getEventdetails(Request $request)

    {

        $data=$request->only('token','id');

        $validator = Validator::make($data, [

            'token' => 'required',

            'id' => 'required',

        ]);



        //Send failed response if request is not valid

        if ($validator->fails()) {

            return response()->json(['success'=>false,'message' => $validator->messages()->first()], 200);

        }

            

        try{

            if($user= JWTAuth::authenticate($request->token)){

                // $select=['events.*','package.package_title','package.package_price','package.package_hours','package.extra_hour_price','orders.amount as total_amount','bank_details.card_number','bank_details.month_expire'];
                //$select=['events.*','package.package_title','package.package_price','package.package_hours','package.extra_hour_price','orders.amount as total_amount'];
                $select=['events.*','package.package_title','package.package_price','package.package_hours','package.extra_hour_price'];

                $response = Event::select($select)

                            ->join('package','package.id','=','events.show_type')

                            //->join('orders','orders.event_id','=','events.id')

                            // ->join('bank_details','bank_details.id','=','orders.card_id')

                            ->where(['events.user_id' => $user->id,'events.active'=>1,'events.id'=>$data['id']])->first();
                // print_r($response);die;
                if ($response!="") {

                    $response->user_id = Helper::userName($response->user_id);

                    $response->extra_hours = $response->extra_hours;
                    $response->city = Helper::cityName($response->city_id);

                    $response->venue_city = ($response->venue_city);
                    // $response->venue_city = Helper::cityName($response->venue_city);

                    $response->venue_type = Helper::venueName($response->venue_type);

                    $response->party_type = Helper::PartyName($response->party_type);

                    $response->party_time = Helper::getTime($response->party_time);

                    $response->city_id = Helper::cityName($response->city_id);

                    // $response->state_id = Helper::stateName($response->state_id);

                    // $response->country_id = Helper::countryName($response->country_id);

                    $response->show_type = Helper::packageName($response->show_type);
                    $response->party_time = date('h:i A',strtotime($response->party_time));

                    $girls = explode(',',$response->girl_id);

                    $model_img=[];

                    foreach ($girls as $key => $girl) {

                        $img = Girls::where(['id'=>$girl])->first('image');

                        $girlimg = explode(',',$img->image);

                        for ($i=0; $i < count($girlimg); $i++) { 

                            $model_img[]=$girlimg[$i];

                        }

                    }

                    $response->image=$model_img;

                    //     

                    //     

                    //     $value->image = url('uploads/girls').'/'.$girlimg[0];

                    //     unset($value->extra_hours);

                    //     unset($value->show_type);

                    //     unset($value->city_id);

                    //     unset($value->girl_id);

                    // print_r($response->toarray());die;
                    $extra_hour_price = $response->extra_hour_price * $response->extra_hours;
                    $response->extra_hour_price = $response->extra_hours*$response->extra_hour_price;
                    $response->total_amount = (string)($response->package_price + $extra_hour_price);
                    return response()->json([

                        'data' => $response,

                        'success' => true,

                        'message' => 'Event Details Found successfully',

                        'status'=>200

                    ]);

                } else {

                    return response()->json([

                        'success' => false,

                        'message' => 'Event Details Not Found',

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



    /**Create Support */

    public function addSupport(Request $request)

    {

        $data = $request->only('token','topic','description','image');

        $validator = Validator::make($data, [

            'token'=>'required',

            'topic'=>'required',

            'description'=>'required'

        ]);



        //Send failed response if request is not valid

        if ($validator->fails()) {

            return response()->json(['success'=>false,'message' => $validator->messages()->first()], 200);

        }

        if($request->image!=""){

            $validator = Validator::make($data, [

                'image'=>'required|mimes:jpeg,png,jpg',

            ]);

            //Send failed response if request is not valid

            if ($validator->fails()) {

                return response()->json(['success'=>false,'message' => $validator->messages()->first()], 200);

            }

        }

            

        try {

            if($user = JWTAuth::authenticate($request->token)){



                $support = new Support;

                $support->user_id=$user->id;

                $support->topic=$request->topic;

                $support->description=$request->description;

                if($file = $request->image){

                    // $file = $request->image;                                        

                    $name = time().str_replace(' ', '', $file->getClientOriginalName());

                    $file->move(public_path('uploads/support'),$name);

                    $support->image="uploads/support/".$name;

                }

                

                $support->save();
                
                $data['user_name']=ucfirst($user->name);
                $html= view('mail.support',$data);

                $email=$user->email;
    
                Helper::send_email($email,'Contact Support',$html);      

                return response()->json([

                    'success' => true,

                    'message' => 'Support created successfully',

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



    /**Support List*/

    public function getSupport(Request $request)

    {

        $data = $request->only('token');

        $validator = Validator::make($data, [

            'token'=>'required',

        ]);



        //Send failed response if request is not valid

        if ($validator->fails()) {

            return response()->json(['success'=>false,'message' => $validator->messages()->first()], 200);

        }

            

        try {

            if($user = JWTAuth::authenticate($request->token)){



                $support =Support::where(['user_id'=>$user->id])->orderBy('id','DESC')->get();

                if (count($support)>0) {

                    $response['list']=$support;

                    $response['path']=url('/');

                    return response()->json([

                        'data' => $response,

                        'success' => true,

                        'message' => 'Support created successfully',

                        'status'=>200

                    ]);

                } else {

                    return response()->json([

                        'success' => false,

                        'message' => 'Support Not Found',

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



    /**Support topic List*/

    public function getSupporttopic(Request $request)

    {

        $data = $request->only('token');

        $validator = Validator::make($data, [

            'token'=>'required',

        ]);



        //Send failed response if request is not valid

        if ($validator->fails()) {

            return response()->json(['success'=>false,'message' => $validator->messages()->first()], 200);

        }

            

        try {

            if($user = JWTAuth::authenticate($request->token)){



                $support =SupportTopic::where(['active'=>1])->orderBy('topic','DESC')->get();

                if (count($support)>0) {

                    return response()->json([

                        'data' => $support,

                        'success' => true,

                        'message' => 'Data Found successfully',

                        'status'=>200

                    ]);

                } else {

                    return response()->json([

                        'success' => false,

                        'message' => 'Data Not Found',

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

    /**transaction Request*/

    public function transaction_request(Request $request)

    {

        $data = $request->only('token','user_id','package_id','amount');

        $validator = Validator::make($data, [

            'token'=>'required',
            'package_id'=>'required',
            'amount'=>'required',

        ]);

        //Send failed response if request is not valid

        if ($validator->fails()) {

            return response()->json(['success'=>false,'message' => $validator->messages()->first()], 200);

        }

        try {

            if($user = JWTAuth::authenticate($request->token)){

                $insert = new TransactionRequest;
                $insert->transaction_token= Hash::make($user->id.$request->package_id.$request->amount);
                $insert->user_id=$user->id;
                $insert->package_id=$request->package_id;
                $insert->amount=$request->amount;
                $insert->save();
                if ($insert->id) {

                    return response()->json([

                        'data' => ['transaction_token'=>$insert->transaction_token,'package_id'=>$insert->package_id,'amount'=>$insert->amount],

                        'success' => true,

                        'message' => 'Transaction successfully created',

                        'status'=>200

                    ]);

                } else {

                    return response()->json([

                        'success' => false,

                        'message' => 'Transaction Request Not created',

                    ]);

                }

            }

        } catch (JWTException $exception) {

            return response()->json([

                'success' => false,

                'message' => 'User Not Found'

            ]);

        }

    }



    



    //  /**add locality and shift */

    // public function token_expired(Request $request)

    // {

    //     $data['token']=$request->token;

    //     $validator = Validator::make($data, [

    //         'token' => 'required'

    //     ]);



    //     //Send failed response if request is not valid

    //     if ($validator->fails()) {

    //         return response()->json(['success'=>false,'message' => $validator->messages()->first()], 200);

    //     }

          

    //     try{

    //           if($user= JWTAuth::authenticate($request->token)){

    //             return response()->json([

    //                 'success' => true,

    //                 'message' => 'Wokring'

    //             ]);

               

    //           }

    //         // dd($driver);die;

    //         //$this->user = JWTAuth::parseToken()->refresh();

            

    //     }catch (JWTException $exception) {

    //         return response()->json([

    //             'success' => true,

    //             'message' => 'Token is expired'

    //         ]);

    //     }

        

       

    // }



    // /**order detail  api*/

    // public function order_detail(Request $request){

     

    //         $data = $request->only('token','order_id');

            

    //         $validator = Validator::make($data, [

    //             'order_id' =>'required',

    //             'token'=>'required'

    //         ]);

    

    //         //Send failed response if request is not valid

    //         if ($validator->fails()) {

    //             return response()->json(['success'=>false,'message' => $validator->messages()->first()], 200);

    //         }

              

    //         try {

    //             if($user = JWTAuth::authenticate($request->token)){

                    

    //                 $order= Order::where('order_id','=',$request->order_id)->first();

    //                // "<pre>";print_r($order);die;

    //                 if($order!=NULL){

    //                     $order->item_json =json_decode($order->item_json);

    //                     $order->order_json =json_decode($order->order_json);

    //                     if($order->driver_id!=''){

    //                         $order->driver=DriverDetails::where('user_id','=',$order->driver_id)->first();

    //                     }

    //                     $order->client=User::where('id','=',$order->client_id)->first();

    //                     $order->address=Address::where('user_id','=',$order->client_id)->first();

    //                     $order->resturant=Restorant::where('id','=',$order->restorant_id)->first();

    //                     if($order){

    //                         return response()->json([

    //                             'data' =>$order,

    //                             'success' => true,

    //                             'message' => "Get order detail successfully.",

    //                             'status'=>200

    //                         ]);

    //                     }

    //                 }

                    

                    

    //             }

                

               

                

    //         } catch (JWTException $exception) {

    //             return response()->json([

    //                 'success' => false,

    //                 'message' => 'driver not found'

    //             ], Response::HTTP_INTERNAL_SERVER_ERROR);

    //         }

    

    // }



    // /**order list  api*/

    // public function order_list(Request $request){

     

    //     $data = $request->only('token');

        

    //     $validator = Validator::make($data, [

    //         'token'=>'required'

    //     ]);



    //     //Send failed response if request is not valid

    //     if ($validator->fails()) {

    //         return response()->json(['success'=>false,'message' => $validator->messages()->first()], 200);

    //     }

          

    //     try {

    //         if($user = JWTAuth::authenticate($request->token)){

                

    //             $order= Order::select('orders.order_id','restorants.name as restorant_name','restorants.address as retorant_address','users.name as customer_name','address.address as customer_address','payment_method','order_status','orders.created_at','orders.item_json','orders.delivery_price','orders.order_price')

    //             ->join('users','orders.client_id','=','users.id')

    //             ->join('restorants','orders.restorant_id','=','restorants.id')

    //             ->join('address','orders.address_id','=','address.id')

    //             ->where('driver_id','=',$user->id)

    //             ->where('order_status','=','completed')

    //             ->get();

              

    //             if(count($order)>0){

    //                 foreach($order as $value){

    //                     $items = json_decode($value->item_json);

    //                     $value->item_json = count($items);

    //                     $value->total_price = $value->order_price+$value->delivery_price;

    //                 }

    //                 return response()->json([

    //                     'data' =>$order,

    //                     'success' => true,

    //                     'message' => "Get order list successfully.",

    //                     'status'=>200

    //                 ]);

    //             }else{

    //                 return response()->json([

    //                     'data' =>$order,

    //                     'success' => false,

    //                     'message' => "No data found.",

    //                     'status'=>200

    //                 ]);

    //             }

                

                

    //         }

            

           

            

    //     } catch (JWTException $exception) {

    //         return response()->json([

    //             'success' => false,

    //             'message' => 'driver not found'

    //         ], Response::HTTP_INTERNAL_SERVER_ERROR);

    //     }



    // }



    // /**completed order list  api*/

    // public function order_completed(Request $request){

        

    //     $data = $request->only('token');

        

    //     $validator = Validator::make($data, [

    //         'token'=>'required'

    //     ]);



    //     //Send failed response if request is not valid

    //     if ($validator->fails()) {

    //         return response()->json(['success'=>false,'message' => $validator->messages()->first()], 200);

    //     }

        

    //     try {

    //         if($user = JWTAuth::authenticate($request->token)){

    //             $nextday =date('Y-m-d', strtotime(' +1 day'));

    //             $nextday = strtotime($nextday);

    //             $previousday = date('Y-m-d', strtotime(' -2 day'));

    //             $previousday = strtotime($previousday);



    //             $order= Order::select('orders.order_id','restorants.name as restorant_name','restorants.address as retorant_address','users.name as customer_name','address.address as customer_address','payment_method','orders.order_status','orders.created_at','orders.item_json','orders.delivery_price','orders.order_price')

    //             ->join('users','orders.client_id','=','users.id')

    //             ->join('restorants','orders.restorant_id','=','restorants.id')

    //             ->join('address','orders.address_id','=','address.id')

    //             ->where('driver_id','=',$user->id)

    //             ->where('orders.created_at','<',$nextday)

    //             ->where('orders.created_at','>',$previousday)

    //             ->where('orders.order_status','=','completed')

    //             ->get();

            

    //             $custom_notification = DB::table("notifications_custom")->where('status','=',1)->first();

    //             if($custom_notification)

    //             {

    //                 $custom_notification_msg = $custom_notification->message;

    //             }

    //             else

    //             {

    //                 $custom_notification_msg = '';

    //             }



    //             if(count($order)>0){

    //                 foreach($order as $value){

    //                     $items = json_decode($value->item_json);

    //                     $value->item_json = count($items);

    //                     $value->total_price = $value->order_price+$value->delivery_price;

    //                 }

    //                 return response()->json([

    //                     'data' =>$order,

    //                     'success' => true,

    //                     'custom_notification_msg' => $custom_notification_msg,

    //                     'message' => "Get completd order list successfully.",

    //                     'status'=>200

    //                 ]);

    //             }else{

    //                 return response()->json([

    //                     'data' =>$order,

    //                     'success' => false,

    //                     'custom_notification_msg' => $custom_notification_msg,

    //                     'message' => "No data found.",

    //                     'status'=>200

    //                 ]);

    //             }

                

                

    //         }

            

        

            

    //     } catch (JWTException $exception) {

    //         return response()->json([

    //             'success' => false,

    //             'message' => 'driver not found'

    //         ], Response::HTTP_INTERNAL_SERVER_ERROR);

    //     }



    // }



    //  /**completed order list  api*/

    //  public function order_today(Request $request){

        

    //     $data = $request->only('token');

        

    //     $validator = Validator::make($data, [

    //         'token'=>'required'

    //     ]);



    //     //Send failed response if request is not valid

    //     if ($validator->fails()) {

    //         return response()->json(['success'=>false,'message' => $validator->messages()->first()], 200);

    //     }

        

    //     try {

    //         if($user = JWTAuth::authenticate($request->token)){

    //             $nextday =date('Y-m-d', strtotime(' +1 day'));

    //             $nextday = strtotime($nextday);

    //             $previousday = date('Y-m-d', strtotime(' -1 day'));

    //             $previousday = strtotime($previousday);

                

    //             $order= Order::select('orders.order_id','restorants.name as restorant_name','restorants.address as retorant_address','users.name as customer_name','address.address as customer_address','payment_method','orders.order_status','orders.created_at','orders.item_json','orders.delivery_price','orders.order_price')

    //             ->join('users','orders.client_id','=','users.id')

    //             ->join('restorants','orders.restorant_id','=','restorants.id')

    //             ->join('address','orders.address_id','=','address.id')

    //             ->where('driver_id','=',$user->id)

    //             ->where('orders.created_at','<',$nextday)

    //             ->where('orders.created_at','>',$previousday)

    //             ->orWhere('orders.order_status','=','accepted')

    //             ->orWhere('orders.order_status','=','reached restaurant')

    //             ->orWhere('orders.order_status','=','picked')

    //             ->orWhere('orders.order_status','=','reached delivery location')

    //             ->get();

            

    //             if(count($order)>0){

    //                 foreach($order as $value){

    //                     $items = json_decode($value->item_json);

    //                     $value->item_json = count($items);

    //                     $value->total_price = $value->order_price+$value->delivery_price;

    //                 }

    //                 return response()->json([

    //                     'data' =>$order,

    //                     'success' => true,

    //                     'message' => "Get completd order list successfully.",

    //                     'status'=>200

    //                 ]);

    //             }else{

    //                 return response()->json([

    //                     'data' =>$order,

    //                     'success' => false,

    //                     'message' => "No data found.",

    //                     'status'=>200

    //                 ]);

    //             }

                

                

    //         }

            

        

            

    //     } catch (JWTException $exception) {

    //         return response()->json([

    //             'success' => false,

    //             'message' => 'driver not found'

    //         ], Response::HTTP_INTERNAL_SERVER_ERROR);

    //     }



    // }



    // /**order current status  api*/

    // public function order_current_status(Request $request){

        

    //     $data = $request->only('token');

        

    //     $validator = Validator::make($data, [

    //         'token'=>'required'

    //     ]);



    //     //Send failed response if request is not valid

    //     if ($validator->fails()) {

    //         return response()->json(['success'=>false,'message' => $validator->messages()->first()], 200);

    //     }

        

    //     try {

    //         if($user = JWTAuth::authenticate($request->token)){

                

    //             $order= Order::select('orders.order_id','order_status')

    //                 ->where('driver_id','=',$user->id)

    //                 ->where('order_status','!=','completed')

    //                 ->get();

            

    //             if(count($order)>0){



    //                 return response()->json([

    //                     'data' =>$order,

    //                     'success' => true,

    //                     'message' => "Get order status successfully.",

    //                     'status'=>200

    //                 ]);

    //             }else{

    //                 return response()->json([

    //                     'data' =>$order,

    //                     'success' => false,

    //                     'message' => "No data found.",

    //                     'status'=>200

    //                 ]);

    //             }

                

                

    //         }

            

        

            

    //     } catch (JWTException $exception) {

    //         return response()->json([

    //             'success' => false,

    //             'message' => 'driver not found'

    //         ], Response::HTTP_INTERNAL_SERVER_ERROR);

    //     }

    // }





}


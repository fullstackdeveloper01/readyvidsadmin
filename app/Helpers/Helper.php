<?php 



namespace App\Helpers;

use App\User;

use App\Country;

use App\Subject;

use App\Topic;

use App\State;

use App\City;

use App\Party;

use App\Hour;

use App\Package;

use App\Venue;

use App\Girls;



class Helper

{

    public static function notify_type($id="")

    {

        

        if($id=="1")

        {

            $type = "Order Created";

        }

        else if($id=="2")

        {

            $type = "Order Accepted";

        }

        else if($id=="3")

        {

            $type = "Order Rejected";

        }

        else if($id=="4")

        {

            $type = "Order Completed";

        }

       

        return $type;

    }







    public static function sendNotification($userid, $title, $message, $notify_type,$url = null,$redirection_url = '')

    {

       // $CI =& get_instance();

        

        $fcm_token = $userid;////;$CI->db->get_where(db_prefix().'contacts', array('userid' => $userid))->row('fcm_token');

        

        if($fcm_token!='')

        {

            $registrationDeviceIds = array($fcm_token);

            // prep the bundle

            $msg = array

            (

                'message'               => $message,

                'title_message'         => $title,

                'image'                 => $url,

                'redirection_url'       => (string)$redirection_url,

                'notify_type'           => 'New Notification',//notify_type($notify_type),

                'title'                 => 'Shyam Naam Trust',

                'subtitle'              => 'New notification',

                'tickerText'            => 'Update Status',

                'vibrate'               => 1,

                'sound'                 => 1,

                'largeIcon'             => 'large_icon',

                'smallIcon'             => 'small_icon'

            );

                       // echo "<pre>";print_r($msg);die;



            $fields = array

            (

                'registration_ids'  => $registrationDeviceIds,

                'data'          => $msg

            );

          

            $headers = array

            (

                'Authorization: key=AAAA1iO-NLk:APA91bGTgA1LsyTjWSbMs3zZG22sK4NK54zTg9J0yXDC1PNT5-EVvCFGVu7VW_OANswizK8yatt1CNHeJrROLw146jSkG6XPhpGpkBp_EK8MXXxRUyjYx73HqC9N7n11NsvyHADo-esK',

                'Content-Type: application/json'

            );

             

            $ch = curl_init();

            curl_setopt( $ch,CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send' );

            curl_setopt( $ch,CURLOPT_POST, true );

            curl_setopt( $ch,CURLOPT_HTTPHEADER, $headers );

            curl_setopt( $ch,CURLOPT_RETURNTRANSFER, true );

            curl_setopt( $ch,CURLOPT_SSL_VERIFYPEER, false );

            curl_setopt( $ch,CURLOPT_POSTFIELDS, json_encode( $fields ) );

            $result = curl_exec($ch );

          //  var_dump($result);//

            curl_close( $ch );

            //echo $result;

            //die;

            return $result;

        }else{

            return true;

        }

    }

    

    /* Send Sms */

    public static function send_sms($data,$schedule='no')

    {

        // print_r($data);die;

        $authKey = "100878AzvmCguo9zJ5dbd8aeb";



        $apiKey    =   "02f43057-98e5-4a73-ab60-234b5fe220a0";        

        $clientid    =   "92f55635-54e9-4566-aeeb-4751f84a7839";        

        $senderId   =   isset($senderId) ? $senderId : "SNTRUS";         



        //Multiple mobiles numbers separated by comma

        $mobileNumber = $data['mobile'];

        //Your message to send, Add URL encoding here.

        $message = 'Welcome to Shyam Naam Trust, Your OTP is '.$data['message'];

        //API URL

        // echo $url="http://smsl.myappstores.com/vendorsms/pushsms.aspx?apiKey=".$apiKey."&clientid=".$clientid."&msisdn=".$mobileNumber."&sid=".$senderId."&msg=".$message."&fl=0&gwid=2";

        // init the resource

        $ch = curl_init();

        curl_setopt_array($ch, array(

            CURLOPT_URL => "http://smsl.myappstores.com/vendorsms/pushsms.aspx?apiKey=02f43057-98e5-4a73-ab60-234b5fe220a0&clientid=92f55635-54e9-4566-aeeb-4751f84a7839&msisdn=".$data['mobile']."&sid=SNTRUS&msg=Welcome%20to%20Shyam%20Naam%20Trust%2C%20Your%20OTP%20is%20".$data['message']."&fl=0&gwid=2",

            CURLOPT_RETURNTRANSFER => true,

            CURLOPT_ENCODING => "",

            CURLOPT_MAXREDIRS => 10,

            CURLOPT_TIMEOUT => 30,

            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,

            CURLOPT_CUSTOMREQUEST => "GET"

        ));



        //Ignore SSL certificate verification

        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);

        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);



        //get response

        $output = curl_exec($ch);

        // print_r($output);die;

        return true;    

    }

    /* Send Sms */

    public static function send_sms_old($data,$schedule='no')

    {

        // print_r($data);die;

        $authKey = "100878AzvmCguo9zJ5dbd8aeb";



    //  $authKey    =   ($authKey!='') ? $authKey : "100878AzvmCguo9zJ5dbd8aeb";        

        $senderId   =   isset($senderId) ? $senderId : "ShyamT";         



        //Multiple mobiles numbers separated by comma

        $mobileNumber = $data['mobile'];



        //Your message to send, Add URL encoding here.

        $message = urlencode($data['message']);

        //Define route 

        $route = "4";

        

        $postData['authkey'] = $authKey;

        $postData['mobiles'] = $mobileNumber;

        $postData['message'] = $message;

        $postData['sender'] = $senderId;

        $postData['route'] = $route;

        $postData['unicode'] = 1;

        if($schedule=='yes'){

           $postData['schtime'] = $data['schtime'];

        }

        //API URL

        $url="https://control.msg91.com/api/sendhttp.php";



        // init the resource

        $ch = curl_init();

        curl_setopt_array($ch, array(

            CURLOPT_URL => $url,

            CURLOPT_RETURNTRANSFER => true,

            CURLOPT_POST => true,

            CURLOPT_POSTFIELDS => $postData

            //,CURLOPT_FOLLOWLOCATION => true

        ));



        //Ignore SSL certificate verification

        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);

        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);



        //get response

        $output = curl_exec($ch);



        return true;    

    }

    /* Send Email */

    public static function send_email($email,$subject,$message)

    {   

        $params = array(

            'to'        => $email,   

            'subject'   => $subject,

            'html'      => $message,

            'from'      => 'support@readyvids.com',//'support@html.manageprojects.in',
            
            'fromname'=>'Ready Vids'

        );

        

        $request =  'https://api.sendgrid.com/api/mail.send.json';

        $headr = array();

     
         //$pass = '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';//'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';
            $pass ='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';

        // set authorization header

        $headr[] = 'Authorization: Bearer '.$pass;

        

        $session = curl_init($request);

        curl_setopt ($session, CURLOPT_POST, true);

        curl_setopt ($session, CURLOPT_POSTFIELDS, $params);

        curl_setopt($session, CURLOPT_HEADER, false);

        curl_setopt($session, CURLOPT_RETURNTRANSFER, true);

        

        // add authorization header

        curl_setopt($session, CURLOPT_HTTPHEADER,$headr);

        

        $response = curl_exec($session);

        curl_close($session);


        return true;

    }

    //  this function is to get county name

    public static function countryName($id)

    {

        if ($id!="") {

            $countryName = Country::where(['id'=>$id])->first('country_name');

            return $countryName->country_name;

        }else{

            return '';

        }

    }
    
    
    //  this function is to get subject name

    public static function subjectName($id)

    {

        if ($id!="") {

            $subjectName = Subject::where(['id'=>$id])->first('name');

            return $subjectName->name;

        }else{

            return '';

        }

    }
    
    
     //  this function is to get subject name

    public static function topicName($id)

    {

        if ($id!="") {

            $topicName = Topic::where(['id'=>$id])->first('name');

            return $topicName->name;

        }else{

            return '';

        }

    }



    // this function is to get state name

    public static function stateName($id)

    {

        if ($id!="") {

            $stateName = State::where(['id'=>$id])->first('state_name');

            return $stateName->state_name;

        }else{

            return '';

        }

    }

    // this function is to get city name

    public static function cityName($id)

    {

        if ($id!="") {

            $cityName = City::where(['id'=>$id])->first('city_name');

            return $cityName->city_name;

        }else{

            return '';

        }

    }



    //  this function is to get user name

    public static function userName($id)

    {

        if ($id!="") {

            $userName = User::where(['id'=>$id])->first('name');

            return $userName->name;

        }else{

            return '';

        }

    }



    //  this function is to get package name

    public static function packageName($id)

    {

        if ($id!="") {

            $packageName = Package::where(['id'=>$id])->first('package_title');

            return $packageName->package_title;

        }else{

            return '';

        }

    }



    //  this function is to get Package time

    public static function packageTime($id)

    {

        if ($id!="") {

            $packageTime = Package::where(['id'=>$id])->first('package_hours');

            return $packageTime->package_hours;

        }else{

            return '';

        }

    }



    //  this function is to get time

    public static function getTime($id)

    {

        if ($id!="") {

            $getTime = Hour::where(['id'=>$id])->first('time');

            return $getTime->time;

        }else{

            return '';

        }

    }



    //  this function is to get party name

    public static function PartyName($id)

    {

        if ($id!="") {

            $PartyName = Party::where(['id'=>$id])->first('type');

            return $PartyName->type;

        }else{

            return '';

        }

    }



    //  this function is to get venue name

    public static function venueName($id)

    {

        if ($id!="") {

            $venueName = Venue::where(['id'=>$id])->first('type');

            return $venueName->type;

        }else{

            return '';

        }

    }



    //  this function is to get girl name

    public static function girlName($id)

    {

        if ($id!="") {

            $girlsName = Girls::where(['id'=>$id])->first('full_name');

            return $girlsName->full_name;

        }else{

            return '';

        }

    }

}


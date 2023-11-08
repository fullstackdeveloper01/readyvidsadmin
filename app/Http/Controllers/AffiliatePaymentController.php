<?php

namespace App\Http\Controllers;

use App\AffliatePayment;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AffiliatePaymentController extends Controller
{
    /**
     * Display a listing of the users
     *
     * @param  \App\User  $model
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $affiliatepayments= AffliatePayment::select('affliate_payment.*','users.name','users.email')->join('users','users.id','=','affliate_payment.user_id')->orderBy('id','desc');
        // if(!empty($_GET['role'])){
        //     $users=$users->where('role','=',$_GET['role']);
        // }
        
        $affiliatepayments =$affiliatepayments->paginate(15);
    
        return view('affiliatepayment.index', ['affiliatepayments' => $affiliatepayments]);
    }

    /**
     * Show the form for creating a new user
     *
     * @return \Illuminate\View\View
     */
    // public function create()
    // {
    //     return view('users.create');
    // }

    /**
     * Store a newly created user in storage
     *
     * @param  \App\Http\Requests\UserRequest  $request
     * @param  \App\User  $model
     * @return \Illuminate\Http\RedirectResponse
     */
    // public function store(UserRequest $request, User $model)
    // {
    //     $model->create($request->merge(['password' => Hash::make($request->get('password'))])->all());

    //     return redirect()->route('user.index')->withStatus(__('User successfully created.'));
    // }

    /**
     * Show the form for editing the specified user
     *
     * @param  \App\User  $user
     * @return \Illuminate\View\View
     */
    public function payment($id)
    {    
        $affiliatepayment = AffliatePayment::select('affliate_payment.*','users.name','users.email')
                                            ->join('users','users.id','=','affliate_payment.user_id')->where('affliate_payment.id','=',$id)->first();
        
        return view('affiliatepayment.payment', compact('affiliatepayment'));
    }

    /**
     * Update the specified user in storage
     *
     * @param  \App\Http\Requests\UserRequest  $request
     * @param  \App\User  $user
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request)
    {
        AffliatePayment::where('id','=',$request->id)->update(['payment_method'=>$request->payment_method,'payment_status'=>'Paid','transaction'=>$request->transaction,'payment_date'=>now()]);
    
        return redirect()->route('affiliatepayment.index')->withStatus(__('Affiliate payment successfully.'));
    }

    /**
     * Remove the specified user from storage
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\RedirectResponse
     */
    public function inactive($user,$status)
    {
        User::where(['id'=>$user])->update(['active'=>$status]);
        return redirect()->route('user.index')->withStatus(__('User successfully Updated.'));
    }

    // public function checkPushNotificationId(UserRequest $request)
    // {
    //     return response()->json([
    //         'userId' => $request->userId,
    //         'status' => true,
    //         'errMsg' => ''
    //     ]);
    // }

    public function show(User $user)
    {
        $event  = Event::where('user_id','=',$user->id)->where('active','=',1)->orderBy('id','desc')->paginate(15);
        
        return view('users.show',['event'=>$event,'user'=>$user]);
    }

    /**
     * Show the form for creating a new user
     *
     * @return \Illuminate\View\View
     */
    // public function details()
    // {
    //     return view('users.create');
    // }

    //*** GET Request Status
    public function status($id1,$id2)
    {  
        $data = User::findOrFail($id1);
        $data->active = $id2;
        $data->update();
        echo true;
    }
    public function approved($id,$approved)
    {  
        $user = User::findorfail($id);
        if($user->email_verified_at!=NULL){
            $user->email_verified_at=NULL;
            
        }else{
             $user->email_verified_at=now();
           
        }
        
        $user->update();

      
        echo true;
        
    }
    
    /*get review list*/
    // public function review($id)
    // {
    //     $ratings  = Rating::Select('users.name','users.email', 'ratings.*')->join('users', function ($join) {
    //         $join->on('users.id', '=', 'ratings.user_id')
    //         ->where('ratings.user_id', '>', 1);
    //     })->where('listing_id','=',$id)->orderBy('ratings.id','desc')->paginate(15);
    //     return view('users.review',['rating'=>$ratings]);
    // }

    //*** GET review Status
    // public function reviewStatus($id1,$id2)
    // {  
    //     $data = Rating::findOrFail($id1);
    //     $data->status = $id2;
    //     $data->update();
       
    //     echo true;
    // }
}

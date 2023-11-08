<?php

namespace App\Http\Controllers;

use App\HelpDesk;
use App\Reply;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class HelpDeskController extends Controller
{
    /**
     * Display a listing of the users
     *
     * @param  \App\User  $model
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $users= HelpDesk::orderBy('id','desc');
        $users =$users->get();
    
        return view('helpdesk.index', ['users' => $users]);
    }
    
    public function reply(Request $request)
    {
        
        $reply = new Reply;
        $reply->user_id = $request->user_id;
        $reply->message_id = $request->message_id;
        $reply->role = $request->role_id;
        $reply->reply = $request->message;
         $reply->subject = $request->subject;
        $reply->save();
        return redirect()->route('helpdesk.index')->withStatus(__('Reply send successfully .'));
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
    // public function edit(User $user)
    // {
    //     return view('users.edit', compact('user'));
    // }

    /**
     * Update the specified user in storage
     *
     * @param  \App\Http\Requests\UserRequest  $request
     * @param  \App\User  $user
     * @return \Illuminate\Http\RedirectResponse
     */
    // public function update(UserRequest $request, User  $user)
    // {
    //     $user->update(
    //         $request->merge(['password' => Hash::make($request->get('password'))])
    //             ->except([$request->get('password') ? '' : 'password']
    //     ));

    //     return redirect()->route('user.index')->withStatus(__('User successfully updated.'));
    // }

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
 
}

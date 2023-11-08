<?php



namespace App\Http\Controllers;


use App\User;

use App\Bank;

use App\Pages;

use App\Support;

use Mail;

use Illuminate\Http\Request;

use Spatie\Permission\Models\Role;

use Illuminate\Support\Facades\Hash;

use App\City;

use App\Status;

use Carbon\Carbon;

use Illuminate\Support\Facades\DB;

use Illuminate\Support\Str;

use App\Notifications\DriverCreated;

use App\Helpers\Helper;

class CustomerSupportController extends Controller

{

    /**

     * Show the form for editing the profile.

     *

     * @return \Illuminate\View\View

     */

    public function index()

    {

        return view('contactSupport.index',['response'=>Support::orderBy('id','DESC')->paginate(20)]);

    }



    /**

     * Show the form for editing the profile.

     *

     * @return \Illuminate\View\View

     */

    public function edit()

    {

        return view('customerSupport.edit');

    }



    /**

     * Update the profile

     *

     * @param  \App\Http\Requests\ProfileRequest  $request

     * @return \Illuminate\Http\RedirectResponse

     */

    public function update(Request $request)
    {
        $email = User::where(['id'=>$request->userid])->first()->email;
        
        $data['message']=$request->shyamtrusteditor;
        $html= view('mail.reply',$data);

         
        Helper::send_email($email,'Reply',$html);

        Support::where(['id'=>$request->id])->update(['reply'=>1]);

        return back()->withStatus(__('Support Reply successfully.'));

    }



    public function store(Request $request)

    {

        //Validate

        $request->validate([

            'shyamtrusteditor' => ['required'],

        ]);



        $pages = new Pages;

        $pages->title ='Customer Support';

        $pages->content = $request->shyamtrusteditor;

        $pages->save();

        // return redirect()->route('customerSupport')->withStatus(__('Customer Support successfully created.'));

        return back()->withStatus(__('Customer Support successfully created.'));



    }

}


<?php



namespace App\Http\Controllers;



use App\TalkToAdvisor;

use Illuminate\Http\Request;

use Spatie\Permission\Models\Role;

use Illuminate\Support\Facades\Hash;

use Carbon\Carbon;

use Illuminate\Support\Facades\DB;

use Illuminate\Support\Str;

use App\Notifications\DriverCreated;

use Validator;



class TalkToAdvisorController extends Controller

{

    /**

     * Display a listing of the resource.

     *

     * @return \Illuminate\Http\Response

     */

    public function index()

    {

        return view('talk_to_advisor.index', ['talk_to_advisors' =>TalkToAdvisor::orderBy('id','desc')->paginate(10)]);

    }



    /**

     * Show the form for creating a new resource.

     *

     * @return \Illuminate\Http\Response

     */

    public function create()

    {

        return view('talk_to_advisor.create',['title'=>'Add Talk To Advisor']);

    }



    /**

     * Store a newly created resource in storage.

     *

     * @param  \Illuminate\Http\Request  $request

     * @return \Illuminate\Http\Response

     */

    public function store(Request $request)

    {

        //Validate

        $request->validate([

            'name' => ['required', 'unique:talk_to_advisor'],
            'phone' => ['required'],

        ]);

        $talk_to_advisor = new TalkToAdvisor;

        $talk_to_advisor->name = $request->name;

        $talk_to_advisor->phone = $request->phone;

        
        $talk_to_advisor->save();

        return redirect()->route('talk_to_advisor.index')->withStatus(__('Talk To Advisor successfully created.'));

    }



    /**

     * Display the specified resource.

     *

     * @param  \App\Driver  $driver

     * @return \Illuminate\Http\Response

     */

    public function show(TalkToAdvisor $talk_to_advisor)

    {

        return view('talk_to_advisor.show', compact('talk_to_advisor'));

    }



    /**

     * Show the form for editing the specified resource.

     *

     * @param  \App\Driver  $driver

     * @return \Illuminate\Http\Response

     */

    public function edit(TalkToAdvisor $talk_to_advisor)

    {

        return view('talk_to_advisor.edit', compact('talk_to_advisor'));

    }



    /**

     * Update the specified resource in storage.

     *

     * @param  \Illuminate\Http\Request  $request

     * @param  \App\Driver  $driver

     * @return \Illuminate\Http\Response

     */

    public function update(Request $request, TalkToAdvisor $talk_to_advisor)

    {

        $request->validate([

            'name' => ['required'],
            'phone' => ['required'],


        ]);

      
    

        $talk_to_advisor->name = $request->name;
        $talk_to_advisor->phone = $request->phone;



        $talk_to_advisor->update();

       

        return redirect()->route('talk_to_advisor.index')->withStatus(__('Talk To Advisor Successfully Updated.'));

    }



    /**

     * Remove the specified resource from storage.

     *

     * @param  \App\Driver  $driver

     * @return \Illuminate\Http\Response

     */

    public function destroy(TalkToAdvisor $talk_to_advisor)

    {  
        $talk_to_advisor->delete();
        return redirect()->route('talk_to_advisor.index')->withStatus(__('TalkToAdvisor successfully deleted.'));
        // $ratio = ratio::where(['id'=>$ratio])->first();

        // if($ratio->active == 1){

        //     $ratio->active=0;

        //     $ratio->update();

        //     return redirect()->route('ratio.index')->withStatus(__('ratio Successfully Inactive.'));

        // }else{

        //     $ratio->active=1;

        //     $ratio->update();

        //     return redirect()->route('ratio.index')->withStatus(__('ratio Successfully Active.'));

        // }

        

    }

    public function status($id,$status)
    { 
        $talk_to_advisor = TalkToAdvisor::findorfail($id);
        if($talk_to_advisor->status==1){
            $talk_to_advisor->status=0;
        }else{
            $talk_to_advisor->status=1;
        }
       // $ratio->status=$status;
        $talk_to_advisor->update();

        echo true;
        
    }



}


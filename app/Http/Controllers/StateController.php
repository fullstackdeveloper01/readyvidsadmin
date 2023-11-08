<?php

namespace App\Http\Controllers;

use App\City;
use App\Country;
use App\State;
use App\Zone;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;
use App\Order;
use App\Status;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use App\Notifications\DriverCreated;

class StateController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
            return view('state.index', ['state' =>State::select('state.*','country.country_name')->join('country','country.id','=','state.country_id')->paginate(10)]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
            return view('state.create');
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
            'country_id' => ['required', 'int'],
            'state_name' => ['required', 'string', 'max:255','unique:state'],
           
        ]);

        $state = new State;
        $state->country_id = ($request->country_id);
        $state->state_name = strip_tags($request->state_name);
        $state->save();
        return redirect()->route('state.index')->withStatus(__('State successfully created.'));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Driver  $driver
     * @return \Illuminate\Http\Response
     */
    // public function show(State $driver)
    // {
    //     //
    // }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Driver  $driver
     * @return \Illuminate\Http\Response
     */
    // public function edit(State $city)
    // {
    //         return view('state.edit', compact('city'));
    // }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Driver  $driver
     * @return \Illuminate\Http\Response
     */
    // public function update(Request $request, State $state)
    // {
    //     $request->validate([
    //        // 'state_name' => ['required', 'string', 'max:255','unique:state'],
    //         'state_name'=>'required|string|unique:state,state_name,'.$state->id,
    //     ]);
    //     $state->state_name = strip_tags($request->state_name);
    //     $state->active = $request->state_status;
    //     $state->update();
    //     return redirect()->route('state.index')->withStatus(__('state successfully updated.'));
    // }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Driver  $driver
     * @return \Illuminate\Http\Response
     */
    public function destroy(State $state)
    {
        if($state->active==1){
            $state->active=0;
            $state->update();
            return redirect()->route('state.index')->withStatus(__('State successfully deactivate.'));
        }else{
            $state->active=1;
            $state->update();
            return redirect()->route('state.index')->withStatus(__('State successfully activate.'));
        }
    }

    public function getState($id=null){
        $data = State::where(['active'=>1]);
        if($id!=""){
            $data=$data->where(['country_id'=>$id]);
        }
        $data = $data->get();
        return response()->json([
            'data' => $data,
            'status' => true,
            'errMsg' => ''
        ]);
    }

}

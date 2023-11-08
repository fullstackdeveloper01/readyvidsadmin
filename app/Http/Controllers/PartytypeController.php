<?php

namespace App\Http\Controllers;

use App\Party;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use App\Notifications\DriverCreated;

class PartytypeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('party.index', ['party' =>Party::orderBy('id','desc')->paginate(10)]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('party.create');
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
            'type' => ['required', 'unique:party_type'],
        ]);

        $party = new Party;
        $party->type = $request->type;
        $party->save();
        return redirect()->route('party.index')->withStatus(__('Party successfully created.'));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Driver  $driver
     * @return \Illuminate\Http\Response
     */
    // public function show(Hour $driver)
    // {
    //     //
    // }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Driver  $driver
     * @return \Illuminate\Http\Response
     */
    public function edit(Party $party)
    {
        return view('party.edit', compact('party'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Driver  $driver
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Party $party)
    {
        $request->validate([
            'type' => ['required', 'unique:party_type'],
        ]);

        $party->type = $request->type;
        $party->update();
       
        return redirect()->route('party.index')->withStatus(__('party Successfully Updated.'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Driver  $driver
     * @return \Illuminate\Http\Response
     */
    public function destroy(Party $party)
    {  
        // $party = Party::where(['id'=>$party])->first();
        if($party->active == 1){
            $party->active=0;
            $party->update();
            return redirect()->route('party.index')->withStatus(__('Party Successfully Inactive.'));
        }else{
            $party->active=1;
            $party->update();
            return redirect()->route('party.index')->withStatus(__('Party Successfully Active.'));
        }
        
    }
    public function getParty(){
        return response()->json([
            'data' =>Party::where(['active'=>1])->get(),
            'status' => true,
            'errMsg' => ''
        ]);
    }

}

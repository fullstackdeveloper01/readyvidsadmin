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

class CountryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
            return view('country.index', ['country' =>Country::where('deleted_at','=','0')->paginate(10)]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
            return view('country.create');
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
            'country_name' => ['required', 'string', 'max:255','unique:country'],
            'icon' => ['required'],
        ]);

        $country = new Country;
        $country->country_name = strip_tags($request->country_name);
        if ($request->hasFile('icon')) {
          
            $extension = $request->file('icon')->getClientOriginalExtension();
            // Filename To store
            $fileNameToStore =time().'.'.$extension;

            $request->icon->move(public_path('uploads/country/'), $fileNameToStore);
            $country->icon ='uploads/country/'.$fileNameToStore;
        }
        $country->save();
        return redirect()->route('country.index')->withStatus(__('Country successfully created.'));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Driver  $driver
     * @return \Illuminate\Http\Response
     */
    public function show(Country $driver)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Driver  $driver
     * @return \Illuminate\Http\Response
     */
    public function edit(Country $country)
    {
            return view('country.edit', compact('country'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Driver  $driver
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Country $country)
    {
        $request->validate([
            'country_name'=>'required|string|unique:country,country_name,'.$country->id,
        ]);
        $country->country_name = strip_tags($request->country_name);
        
        if ($request->hasFile('icon')) {
            if($country->icon != ''){
                $path = public_path().'/'.$country->icon;
                if(file_exists($path)){
                    unlink($path);  
                }                
            }
            $extension = $request->file('icon')->getClientOriginalExtension();
            // Filename To store
            $fileNameToStore =time().'.'.$extension;

            $request->icon->move(public_path('uploads/country/'), $fileNameToStore);
            $country->icon ='uploads/country/'.$fileNameToStore;
           
            
        }
       
        $country->update();
        return redirect()->route('country.index')->withStatus(__('Country successfully updated.'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Driver  $driver
     * @return \Illuminate\Http\Response
     */
    public function destroy(Country $country)
    {
        $country->deleted_at=1;
        $country->active=0;
        $country->update();
        // $country->delete();
        // if($country->icon != ''){
        //         $path = public_path().'/'.$country->icon;
        //         if(file_exists($path)){
        //             unlink($path);  
        //         }                
        // }
        
        return redirect()->route('country.index')->withStatus(__('Country successfully deleted.'));
       
    }

    public function status($id,$status)
    {  
        $country = Country::findorfail($id);
        if($country->active==1){
            $country->active=0;
        }else{
            $country->active=1;
        }
      
        $country->update();

        echo true;
        
    }
   

    public function getCountry(){
        return response()->json([
            'data' => Country::where(['active'=>1])->get(),
            'status' => true,
        ]);
    }

}

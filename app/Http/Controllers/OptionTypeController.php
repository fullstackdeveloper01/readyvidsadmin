<?php

namespace App\Http\Controllers;

use App\OptionType;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use App\Notifications\DriverCreated;

class OptionTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $optiontype  = OptionType::where('deleted_at','=','0')->orderBy('id','desc')->paginate(10);
      
        return view('optiontype.index',compact('optiontype') );
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if(auth()->user()->hasRole('admin')){
           return view('optiontype.create');
        }else return redirect()->route('optiontype.index')->withStatus(__('No Access'));
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
            'type' => ['required', 'string', 'max:255','unique:option_type'],
        ]);

        $template = new OptionType;
        $template->type = $request->type;
       
        $template->save();
        return redirect()->route('optiontype.index')->withStatus(__('Option type successfully created.'));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Driver  $driver
     * @return \Illuminate\Http\Response
     */
    public function show(template $driver)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Driver  $driver
     * @return \Illuminate\Http\Response
     */
    public function edit(OptionType $optiontype)
    {
        return view('optiontype.edit', compact('optiontype'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Driver  $driver
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, OptionType $optiontype)
    {
        $request->validate([
            //'type' => ['required', 'string', 'max:255','unique:templates_type'],
            'type' => "required|string|max:255|unique:option_type,id"
        ]);

        $optiontype->type = $request->type;
        $optiontype->update();
        return redirect()->route('optiontype.index')->withStatus(__('Option Type successfully updated.'));

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Driver  $driver
     * @return \Illuminate\Http\Response
     */
    public function destroy(OptionType $optiontype)
    {
         $optiontype->deleted_at=1;
         $optiontype->status=0;
          $optiontype->update();
        //$affectedRows =  $optiontype->delete();
        return redirect()->route('optiontype.index')->withStatus(__('Option type successfully deleted.'));
        
    }

   
    public function status($id,$status)
    {  
        $option = OptionType::findorfail($id);

        if( $option->status==1){
            $option->status=0;
        }else{
            $option->status=1;
        }
        // $template->status=$status;
        $option->update();
        echo true;
        
    }
}

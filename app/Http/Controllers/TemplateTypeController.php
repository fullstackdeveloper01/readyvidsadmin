<?php

namespace App\Http\Controllers;

use App\TemplateType;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use App\Notifications\DriverCreated;

class TemplateTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $templates_type  = TemplateType::orderBy('id','desc')->paginate(10);
      
        return view('template_type.index',compact('templates_type') );
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if(auth()->user()->hasRole('admin')){
           return view('template_type.create');
        }else return redirect()->route('template_type.index')->withStatus(__('No Access'));
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
            'type' => ['required', 'string', 'max:255','unique:templates_type'],
        ]);

        $template = new TemplateType;
        $template->type = $request->type;
       
        $template->save();
        return redirect()->route('template_type.index')->withStatus(__('Template type successfully created.'));
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
    public function edit(TemplateType $template_type)
    {
        return view('template_type.edit', compact('template_type'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Driver  $driver
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, TemplateType $template_type)
    {
        $request->validate([
            //'type' => ['required', 'string', 'max:255','unique:templates_type'],
            'type' => "required|string|max:255|unique:templates_type,id"
        ]);

        $template_type->type = $request->type;
        $template_type->update();
        return redirect()->route('template_type.index')->withStatus(__('Template Type successfully updated.'));

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Driver  $driver
     * @return \Illuminate\Http\Response
     */
    public function destroy(TemplateType $template_type)
    {
        $affectedRows =  $template_type->delete();
        return redirect()->route('template_type.index')->withStatus(__('Template type successfully deleted.'));
        
    }

   
    public function status($id,$status)
    {  
        $template = TemplateType::findorfail($id);

        if( $template->status==1){
            $template->status=0;
        }else{
            $template->status=1;
        }
        // $template->status=$status;
        $template->update();
        echo true;
        
    }
}

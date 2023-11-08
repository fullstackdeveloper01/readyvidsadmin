<?php

namespace App\Http\Controllers;

use App\QuizTemplateType;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use App\Notifications\DriverCreated;

class QuizTemplateTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $templates_type  = QuizTemplateType::where('deleted_at','=','0')->orderBy('id','desc')->paginate(10);
      
        return view('templatetype.index',compact('templates_type') );
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if(auth()->user()->hasRole('admin')){
           return view('templatetype.create');
        }else return redirect()->route('templatetype.index')->withStatus(__('No Access'));
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
            'type' => ['required', 'string', 'max:255','unique:quiz_templates_type'],
        ]);

        $template = new QuizTemplateType;
        $template->type = $request->type;
       
        $template->save();
        return redirect()->route('templatetype.index')->withStatus(__('Template type successfully created.'));
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
    public function edit(QuizTemplateType $templatetype)
    {
        return view('templatetype.edit', compact('templatetype'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Driver  $driver
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, QuizTemplateType $templatetype)
    {
        $request->validate([
            //'type' => ['required', 'string', 'max:255','unique:templates_type'],
            'type' => "required|string|max:255|unique:quiz_templates_type,id"
        ]);

        $templatetype->type = $request->type;
        $templatetype->update();
        return redirect()->route('templatetype.index')->withStatus(__('Template Type successfully updated.'));

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Driver  $driver
     * @return \Illuminate\Http\Response
     */
    public function destroy(QuizTemplateType $templatetype)
    {
        $templatetype->deleted_at=1;
         $templatetype->status=0;
          $templatetype->update();
        //$affectedRows =  $templatetype->delete();
        return redirect()->route('templatetype.index')->withStatus(__('Template type successfully deleted.'));
        
    }

   
    public function status($id,$status)
    {  
        $template = QuizTemplateType::findorfail($id);

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

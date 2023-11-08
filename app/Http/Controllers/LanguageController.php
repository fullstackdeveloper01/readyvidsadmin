<?php

namespace App\Http\Controllers;

use App\Language;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use App\Notifications\DriverCreated;

class LanguageController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('language.index', ['languages' =>Language::orderBy('id','desc')->paginate(10)]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('language.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        dd($request);
        //Validate
        $request->validate([
            'name' => ['required', 'unique:language'],
        ]);
        $Language = new Language;
        $Language->name = $request->name;
        $Language->save();
        return redirect()->route('language.index')->withStatus(__('Language successfully created.'));
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
    public function edit(Language $language)
    {
        return view('language.edit', compact('language'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Driver  $driver
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Language $Language)
    {
        $request->validate([
            'name' => ['required', 'unique:language'],
        ]);

        $Language->name = $request->name;
        $Language->update();
       
        return redirect()->route('language.index')->withStatus(__('Language Successfully Updated.'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Driver  $driver
     * @return \Illuminate\Http\Response
     */
    public function destroy(Language $Language)
    {  
        // $Language = Language::where(['id'=>$Language])->first();
        // if($Language->active == 1){
        //     $Language->active=0;
        //     $Language->update();
        //     return redirect()->route('language.index')->withStatus(__('Language Successfully Inactive.'));
        // }else{
        //     $Language->active=1;
        //     $Language->update();
        //     return redirect()->route('language.index')->withStatus(__('Language Successfully Active.'));
        // }

        $affectedRows = Language::where('id', '=', $Language->id)->delete();
        return redirect()->route('language.index')->withStatus(__('Language successfully deleted.'));
        
    }

    public function status($id,$status)
    {  
        $language = Language::findorfail($id);
        if( $language->active==1){
            $language->active=0;
        }else{
            $language->active=1;
        }
        //$language->active=$status;
        $language->update();
        echo true;
        // if($Language->active == 1){
        //     $Language->active=0;
        //     $Language->update();
        //     return redirect()->route('language.index')->withStatus(__('Language Successfully Inactive.'));
        // }else{
        //     $Language->active=1;
        //     $Language->update();
        //     return redirect()->route('language.index')->withStatus(__('Language Successfully Active.'));
        // }
        
    }
    public function getLanguage(){
        return response()->json([
            'data' =>Language::where(['active'=>1])->get(),
            'status' => true,
            'errMsg' => ''
        ]);
    }

}

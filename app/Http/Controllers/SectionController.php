<?php

namespace App\Http\Controllers;

use App\Section;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use App\Notifications\DriverCreated;

class SectionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('section.index', ['sections' =>Section::orderBy('id','desc')->paginate(10)]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('section.create',['title'=>'Add Video Type']);
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
            'name' => ['required'],
            'image' => ['required'],
        ]);

        $section = new Section;
        $section->name = $request->name;
       if ($request->hasFile('image')) {
          
            $extension = $request->file('image')->getClientOriginalExtension();
            // Filename To store
            $fileNameToStore = time().'.'.$extension;

            $request->image->move(public_path('uploads/section/'), $fileNameToStore);
            $section->icon = 'uploads/section/'.$fileNameToStore;
        }
        $section->save();
        return redirect()->route('section.index')->withStatus(__('Section successfully created.'));
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
    public function edit(Section $section)
    {
        return view('section.edit', compact('section'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Driver  $driver
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Section $section)
    {
        $request->validate([
            'name' => ['required'],
            //'word' => ['required'],
        ]);


        $section->name = $request->name;
       if ($request->hasFile('image')) {
            
            if($section->image != ''){
                $path = public_path().'/uploads/section/'.$section->image;
                if(file_exists($path)){
                    unlink($path);  
                }                
            }
            $extension = $request->file('image')->getClientOriginalExtension();
            // Filename To store
            $fileNameToStore = time().'.'.$extension;

            $request->image->move(public_path('uploads/section/'), $fileNameToStore);
            $section->icon = 'uploads/section/'.$fileNameToStore;
        }
        $section->Update();
       
        return redirect()->route('section.index')->withStatus(__('section Successfully Updated.'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Driver  $driver
     * @return \Illuminate\Http\Response
     */
    public function destroy(Section $section)
    {  
        $section->delete();
        return redirect()->route('section.index')->withStatus(__('Section successfully deleted.'));
        
    }

    public function status($id,$status)
    {
        $section = Section::findorfail($id);
        if( $section->status==1){
            $section->status=0;
        }else{
            $section->status=1;
        }
      
        $section->update();
        echo true;
        
    }
    public function getsection(){
        return response()->json([
            'data' =>Section::where(['active'=>1])->get(),
            'status' => true,
            'errMsg' => ''
        ]);
    }

}

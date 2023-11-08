<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Languages;

class SecondaryLanguageController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */


    public function index()
    {
        return view('secondary_language.index', ['languages' =>Languages::where('parent_id','!=',0)->orderBy('id','desc')->paginate(15)]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
       return view('secondary_language.create',['title'=>'Add Secondary Language','primaryLanguageList' =>Languages:: where(['parent_id'=>0])->where('status','=',1)->get()]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'primary_language' => ['required'],
          
            'icon' => ['required'],
        ]);
        $language = new Languages;
        $language->name = strip_tags($request->name);
        $language->parent_id = $request->primary_language;
        $language->description = $request->description;
         
        if ($request->hasFile('icon')) {
           
            $extension = $request->file('icon')->getClientOriginalExtension();
            // Filename To store
            $fileNameToStore = time().'.'.$extension;

            $request->icon->move(public_path('uploads/language_icon/'), $fileNameToStore);
            $language->icon = 'uploads/language_icon/'.$fileNameToStore;
        }
        
        $language->save();
        return redirect()->route('secondary_language.index')->withStatus(__('Secondary Language successfully created.'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $primaryLanguageList=Languages:: where(['parent_id'=>0])->where('status','=',1)->get();
        return view('secondary_language.edit', ['language' =>Languages::where(['id'=>$id])->first(),'primaryLanguageList' =>$primaryLanguageList]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {  
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
        ]);
       
        $language= Languages::findorfail($request->id);
         
        $language->parent_id = $request->primary_language;
        $language->name = $request->name;
        $language->description = $request->description;
         
        if ($request->hasFile('icon')) {
            
            if($language->icon != ''){
                $path = public_path().'/uploads/language_icon/'.$language->icon;
                if(file_exists($path)){
                    unlink($path);  
                }                
            }
            $extension = $request->file('icon')->getClientOriginalExtension();
            // Filename To store
            $fileNameToStore = time().'.'.$extension;

            $request->icon->move(public_path('uploads/language_icon/'), $fileNameToStore);
            $language->icon = 'uploads/language_icon/'.$fileNameToStore;
        }
        //Languages::where(['id'=>$request->id])->update($languages);
        $language->update();
        return redirect()->route('secondary_language.index')->withStatus(__('Secondary language successfully updated.'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $language = Languages::findorfail($id);
       
        if($language)
        {   
            if($language->icon){
                $path = public_path()."/".$language->icon;
            
                if(file_exists($path)){

                    unlink($path); 
                }
            }
           
           
            $language->delete();
        }
        return redirect()->route('secondary_language.index')->withStatus(__('Secondary language successfully deleted.'));
    }

    
   
    public function getSecondaryLanguageList($id){
        $data= Languages::where(['parent_id'=>$id])->where(['status'=>1])->orderBy('name','ASC')->get();
        
        return response()->json([
            'data' =>$data,
            'status' => true,
            'errMsg' => ''
        ]);
    }
     public function status($id,$status)
    {  
        $Languages = Languages::findorfail($id);
        if( $Languages->status==1){
            $Languages->status=0;
        }else{
            $Languages->status=1;
        }
       // $Languages->status=$status;
        $Languages->update();
        echo true;
        
    }
}

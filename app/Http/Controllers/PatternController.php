<?php

namespace App\Http\Controllers;

use App\Template;
use App\TemplateType;
use App\Pattern;
use App\Section;
use App\Categories;
use App\Ratio;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use App\Notifications\DriverCreated;

class PatternController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $patterns  = Pattern::select('pattern.*','templates_type.type as template_name','ratio.name as ratio_name')
                            ->join('templates_type','pattern.template_type','=','templates_type.id')
                            ->join('ratio','pattern.ratio','=','ratio.id')
                            ->orderBy('pattern.id','desc')
                            ->paginate(10);
        return view('pattern.index',compact('patterns') );
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if(auth()->user()->hasRole('admin')){
           $typeList =TemplateType:: where('status','=',1)->get();
           $ratioList =Ratio:: where('status','=',1)->get(); 
           $videoList =Section:: where('status','=',1)->get();
            return view('pattern.create',['typeList' =>$typeList,'ratioList'=>$ratioList,'videoList' =>$videoList]);
        }else return redirect()->route('pattern.index')->withStatus(__('No Access'));
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
            'template_type' => ['required'],
            'ratio' => ['required'],
            'image_type' => ['required'],
             'video_type' => ['required'],
        ]);
      
        $pattern = new Pattern;
        $pattern->name = $request->name;
        $pattern->template_type = $request->template_type;
        $pattern->ratio = $request->ratio;
        $pattern->image_type = $request->image_type;
        $pattern->video_type = $request->video_type;
        if ($request->hasFile('image')) {
            $filenameWithExt = $request->file('image')->getClientOriginalName ();
            // Get just Extension
            $extension = $request->file('image')->getClientOriginalExtension();
            // Filename To store
            $fileNameToStore = time().'.'.$extension;

            $request->image->move(public_path('uploads/pattern'), $fileNameToStore);
            $pattern->image = 'uploads/pattern/'.$fileNameToStore;
        }
        

        $pattern->save();
        return redirect()->route('pattern.index')->withStatus(__('Pattern successfully created.'));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Driver  $driver
     * @return \Illuminate\Http\Response
     */
    public function show(pattern $driver)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Driver  $driver
     * @return \Illuminate\Http\Response
     */
    public function edit(Pattern $pattern)
    {
        $typeList =TemplateType:: where('status','=',1)->get();
        $ratioList =Ratio:: where('status','=',1)->get();
       
        return view('pattern.edit', compact('pattern','typeList','ratioList'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Driver  $driver
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, pattern $pattern)
    {
        $request->validate([
            'name' => ['required'],
            'template_type' => ['required'],
            'ratio' => ['required'],
            'image_type' => ['required'],
        ]);
      
       
        $pattern->name = $request->name;
        $pattern->template_type = $request->template_type;
        $pattern->ratio = $request->ratio;
        $pattern->image_type = $request->image_type;
      
        if ($request->hasFile('image')) {
            
            if($pattern->image != ''){
                $path = public_path().'/'.$pattern->image;
                unlink($path);                
            }
            $filenameWithExt = $request->file('image')->getClientOriginalName ();
            // Get just Extension
            $extension = $request->file('image')->getClientOriginalExtension();
            // Filename To store
            $fileNameToStore = time().'.'.$extension;

            $request->image->move(public_path('uploads/pattern'), $fileNameToStore);
            $pattern->image = 'uploads/pattern/'.$fileNameToStore;
        }

        $pattern->update();
        return redirect()->route('pattern.index')->withStatus(__('Pattern successfully updated.'));

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Driver  $driver
     * @return \Illuminate\Http\Response
     */
    public function destroy(pattern $pattern)
    {
        $affectedRows =  $pattern->delete();
        if($affectedRows)
        {
            if($pattern->image != ''){
                $path = public_path().'/'.$pattern->image;
                unlink($path);                
            }
        }
        return redirect()->route('pattern.index')->withStatus(__('pattern successfully deleted.'));
    }


    public function status($id,$status)
    {  
        $pattern = pattern::findorfail($id);
        if($pattern->status==1){
            $pattern->status=0;
        }else{
            $pattern->status=1;
        }
       
        $pattern->update();
        echo true;
        
    }
    
    public function getPattern($id){
       
        $pattern = Pattern::select('pattern.*','templates_type.type as template_name')->join('templates_type','pattern.template_type','templates_type.id')->where(['pattern.id'=>$id])->first();
        if($pattern){
            $pattern_html = $pattern->pattern_html;
             $pattern_html = preg_replace("/\r|\n/", "", $pattern_html);
             $template_name =explode(' ',$pattern->template_name);
              return response()->json([
                'data' =>$pattern_html,
                'status' => true,
                'errMsg' => '',
                'template_type'=>$template_name[0],
                'image_size'=>$pattern->image_size
                ]);
        }else{
             return response()->json([
                'data' =>'',
                'status' => false,
                'errMsg' => ''
                ]);
        }
       
        // $image_url= public_path().'uploads/pattern_image/default-img.png';
        // $pattern_html = str_replace("[image_url]", $image_url, $pattern_html);
       
    }
    
    public function gettemplatepattern($id,$id1,$id2,$id3){
        
        $pattern = Pattern::select('pattern.*','templates_type.type as template_name')->join('templates_type','pattern.template_type','templates_type.id');
        if($id!='0'){
             $pattern = $pattern->where(['pattern.template_type'=>$id]);
        }
        if($id1!='0'){
             $pattern = $pattern->where(['pattern.image_type'=>$id1]);
        }
        if($id2!='0'){
             $pattern = $pattern->where(['pattern.ratio'=>$id2]);
        }
        if($id3!='0'){
             $pattern = $pattern->where(['pattern.video_type'=>$id3]);
             $subcategories = Categories::where('video_type','=',$id3)->get();
        }
        else{
            $subcategories='';
        }
        $pattern = $pattern->get();
        
        
        if(count($pattern)>0){
           
              return response()->json([
                'data' =>$pattern,
                'status' => true,
                'errMsg' => '',
                'subcategories'=>$subcategories
                ]);
        }else{
             return response()->json([
                'data' =>'',
                'status' => false,
                'errMsg' => '',
                 'subcategories'=>$subcategories
                ]);
        }
       
   
       
    }

}

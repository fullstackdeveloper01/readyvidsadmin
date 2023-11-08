<?php

namespace App\Http\Controllers;

use App\Template;
use App\QuizTemplateType;
use App\QuizPattern;
use App\QuizRatio;
use App\OptionType;
use App\Topic;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use App\Notifications\DriverCreated;

class QuizPatternController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $patterns  = QuizPattern::select('quiz_pattern.*','quiz_templates_type.type as template_name','quiz_ratio.name as ratio_name')
                            ->join('quiz_templates_type','quiz_pattern.template_type','=','quiz_templates_type.id')
                            ->join('quiz_ratio','quiz_pattern.ratio','=','quiz_ratio.id')
                            ->where("quiz_pattern.status",'=','1')
                            ->orderBy('quiz_pattern.id','desc')
                            ->paginate(10);
        return view('quiz_pattern.index',compact('patterns') );
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if(auth()->user()->hasRole('admin')){
           $typeList =QuizTemplateType:: where('status','=',1)->get();
           $ratioList =QuizRatio:: where('status','=',1)->get();
           $optionList =OptionType:: where('status','=',1)->get();
            return view('quiz_pattern.create',['typeList' =>$typeList,'ratioList'=>$ratioList,'optionList' =>$optionList]);
        }else return redirect()->route('quiz_pattern.index')->withStatus(__('No Access'));
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
        ]);
      
        $pattern = new QuizPattern;
        $pattern->name = $request->name;
        $pattern->template_type = $request->template_type;
        $pattern->ratio = $request->ratio;
        $pattern->option_type = $request->option_type_id;
      
        if ($request->hasFile('image')) {
            $filenameWithExt = $request->file('image')->getClientOriginalName ();
            // Get just Extension
            $extension = $request->file('image')->getClientOriginalExtension();
            // Filename To store
            $fileNameToStore = time().'.'.$extension;

            $request->image->move(public_path('uploads/quiz_pattern'), $fileNameToStore);
            $pattern->image = 'uploads/quiz_pattern/'.$fileNameToStore;
        }
        

        $pattern->save();
        return redirect()->route('quiz_pattern.index')->withStatus(__('Pattern successfully created.'));
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
        $typeList =QuizTemplateType:: where('status','=',1)->get();
        $ratioList =QuizRatio:: where('status','=',1)->get();
        $optionList =OptionType:: where('status','=',1)->get();
        return view('quiz_pattern.edit', compact('pattern','typeList','ratioList','optionList'));
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

            $request->image->move(public_path('uploads/quiz_pattern'), $fileNameToStore);
            $pattern->image = 'uploads/quiz_pattern/'.$fileNameToStore;
        }

        $pattern->update();
        return redirect()->route('quiz_pattern.index')->withStatus(__('Pattern successfully updated.'));

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
        return redirect()->route('quiz_pattern.index')->withStatus(__('pattern successfully deleted.'));
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
       
        $pattern = QuizPattern::select('quiz_pattern.*','quiz_templates_type.type as template_name')->join('quiz_templates_type','quiz_pattern.template_type','quiz_templates_type.id')->where(['quiz_pattern.id'=>$id])->first();
        if($pattern){
            $pattern_html = $pattern->pattern_html;
             $pattern_html = preg_replace("/\r|\n/", "", $pattern_html);
            
              return response()->json([
                'data' =>$pattern_html,
                'status' => true,
                'errMsg' => '',
               
                ]);
        }else{
             return response()->json([
                'data' =>'',
                'status' => false,
                'errMsg' => ''
                ]);
        }
       
     
       
    }
    
    public function gettemplatepattern($id,$id1,$id2){
      
        $pattern = QuizPattern::select('quiz_pattern.*','quiz_templates_type.type as template_name')->join('quiz_templates_type','quiz_pattern.template_type','quiz_templates_type.id')->where('quiz_pattern.status','=','1');
        if($id!='0'){
             $pattern = $pattern->where(['quiz_pattern.template_type'=>$id]);
        }
        if($id1!='0'){
             $pattern = $pattern->where(['quiz_pattern.ratio'=>$id1]);
        }
        if($id2!='0'){
            $pattern = $pattern->where(['quiz_pattern.option_type'=>$id2]);
            $topics = Topic::where('option_type_id','=',$id2)->get();
        }
        else{
            $topics='';
        }
        $pattern = $pattern->get();
        if(count($pattern)>0){
           
              return response()->json([
                'data' =>$pattern,
                'status' => true,
                'errMsg' => '',
                'topics'=>$topics
                ]);
        }else{
             return response()->json([
                'data' =>'',
                'status' => false,
                'errMsg' => ''
                ]);
        }
       
   
       
    }

}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Subject;
use App\Country;
use App\Topic;
use App\QuizVideo;
use App\QuizTemplate;
use App\OptionType;
use DB;
class TopicController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function index()
    {
        $topics = Topic::select('topics.*','country.country_name','subjects.name as subject_name')->join('subjects','topics.subject_id','=','subjects.id')->join('country','subjects.country_id','=','country.id')->where('topics.deleted_at','=','0')->paginate(15);
        return view('topics.index', ['topics' =>$topics]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $countryList = Country::where('active','=',1)->get();
        $optionTypeList = OptionType::where('status','=',1)->get();
       return view('topics.create',['title'=>'Add Topic','countryList' =>$countryList,'optionTypeList' =>$optionTypeList]);
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
            'name'=>'required|string',
            'country_id' => ['required'],
            'subject_id' => ['required'],
            'icon' => ['required'],
            // 'name' => [
            //     'required',
            //     'max:255',
            //     function ($request, $value, $fail) {
            //         //if ($request->country_id === 'foo') {
            //             dd($request);
            //             $result=Subject::where(['country_id'=>$request['country_id'],'name'=>$request['name']])->first();
            //             if($result){
            //                return $fail('The '.$attribute.' is invalid.');
            //             }
                        
            //         //}
            //     },
            // ],
        ]);
        
        $topic = new Topic;
        $topic->name = strip_tags($request->name);
        $topic->country_id = $request->country_id;
        $topic->subject_id = $request->subject_id;
        $topic->option_type_id = $request->option_type_id;

        if ($request->hasFile('icon')) {
           
            $extension = $request->file('icon')->getClientOriginalExtension();
            $fileNameToStore = time().'.'.$extension;
            $request->icon->move(public_path('uploads/topics/'), $fileNameToStore);
            $topic->icon ='uploads/topics/'.$fileNameToStore;
        }
      
        $topic->save();
        return redirect()->route('topics.index')->withStatus(__('Topics successfully created.'));
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
    public function edit(Request $request, Topic $topic)
    { 
       
        $countryList = Country::where('active','=',1)->get();
        $optionTypeList = OptionType::where('status','=',1)->get();
        $subjectList = Subject::where('country_id','=',$topic->country_id)->get();
        return view('topics.edit',['title'=>'Edit Topic','countryList' =>$countryList,'subjectList' =>$subjectList,'topic'=>$topic,'optionTypeList' =>$optionTypeList]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request,Topic $topic)
    {       
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'country_id' => ['required'],
            'subject_id' => ['required'],
        ]);
        $topic->name = strip_tags($request->name);
        $topic->country_id = $request->country_id;
        $topic->subject_id = $request->subject_id;
        $topic->option_type_id = $request->option_type_id;
        if ($request->hasFile('icon')) {
           
            if($topic->icon != ''){
                $path = public_path().'/'.$topic->icon;
                if(file_exists($path)){
                    unlink($path);  
                }                
            }
            $extension = $request->file('icon')->getClientOriginalExtension();
            $fileNameToStore = time().'.'.$extension;
            $request->icon->move(public_path('uploads/topics/'), $fileNameToStore);
            $topic->icon ='uploads/topics/'.$fileNameToStore;
        }
        
        $topic->update();

        return redirect()->route('topics.index')->withStatus(__('Topic successfully updated.'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Topic $topic)
    {
        $topic->deleted_at=1;
        $topic->status=0;
        $topic->update();
        // $topic->delete();
        // if($topic->icon!='')
        // {
        //     $path = public_path()."/".$topic->icon;
        //     unlink($path);
        //     //$affectedRows = Categories::where('id', '=', $id)->delete();
        // }
        return redirect()->route('topics.index')->withStatus(__('Topic successfully deleted.'));
    }

    
    public function status($id,$status)
    {  
        $topic = Topic::findorfail($id);
        if( $topic->status==1){
            $topic->status=0;
        }else{
            $topic->status=1;
        }
       
        $topic->update();
        echo true;
        
    }

    public function getTopicList($id,$id1,$id2){
      
        $topics = Topic::where("status",'=','1');
        if($id!='0'){
             $topics = $topics->where(['country_id'=>$id]);
        }
        if($id1!='0'){
             $topics = $topics->where(['subject_id'=>$id1]);
        }
        if($id2!='0'){
            $topics = $topics->where(['option_type_id'=>$id2]);
        }
        $topics = $topics->get();
        if(count($topics)>0){
           
              return response()->json([
                'data' =>$topics,
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
    public function clone($id)
    { 
        $topic=Topic::findorfail($id);
      
        $countryList = Country::where('active','=',1)->get();
        $optionTypeList = OptionType::where('status','=',1)->get();
        $subjectList = Subject::where('country_id','=',$topic->country_id)->where('status','=','1')->where('deleted_at','=','0')->get();
        return view('topics.clone',['title'=>'Clone Topic','countryList' =>$countryList,'subjectList' =>$subjectList,'topic'=>$topic,'optionTypeList' =>$optionTypeList]);
    }

    public function cloneTopic(Request $request,$topic_id)
    { 
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'country_id' => ['required'],
            'subject_id' => ['required'],
            'option_type_id' => ['required'],
            'icon'=>['required']
        ]);
        
        $topic = new Topic();
        $topic->name = strip_tags($request->name);
        $topic->country_id = $request->country_id;
        $topic->subject_id = $request->subject_id;
        $topic->option_type_id = $request->option_type_id;
        if ($request->hasFile('icon')) {
           
            if($topic->icon != ''){
                $path = public_path().'/'.$topic->icon;
                if(file_exists($path)){
                    unlink($path);  
                }                
            }
            $extension = $request->file('icon')->getClientOriginalExtension();
            $fileNameToStore = time().'.'.$extension;
            $request->icon->move(public_path('uploads/topics/'), $fileNameToStore);
            $topic->icon ='uploads/topics/'.$fileNameToStore;
        }
        
        
        $topic->save();
        
        $quizdata = QuizVideo::select('country_id','subject_id','topic_id')->whereRaw("find_in_set('$topic_id',topic_id)")->first();
        if($quizdata!=null){
             $country_array =explode(',',$quizdata->country_id);
             if(in_array($topic->country_id, $country_array )){
                 $quiz_data['country_id']=$quizdata->country_id;
             }else{
               $quiz_data['country_id']=$quizdata->country_id.','.$topic->country_id;
             }
             
             $subject_array =explode(',',$quizdata->subject_id);
             if(in_array($topic->subject_id, $subject_array )){
                 $quiz_data['subject_id']=$quizdata->subject_id;
             }else{
               $quiz_data['subject_id']=$quizdata->subject_id.','.$topic->subject_id;
             }
             
             $quiz_data['topic_id']=$quizdata->topic_id.','.$topic->id;
             
        } 
        
        $quiztemplate = QuizTemplate::select('topic_id')->whereRaw("find_in_set('$topic_id',topic_id)")->first();
        if($quiztemplate){
            DB::table('quiz_templates')->whereRaw("find_in_set('$topic_id',topic_id)")->update(['topic_id'=>$quiztemplate->topic_id.','.$topic->id]);
        }
       
        // $previous_topic = Topic::findorfail($topic_id);
        
        
        // if($topic->country_id!=$previous_topic->country_id){
        //     $quiz_data['country_id']=  $previous_topic->country_id.','.$topic->country_id;
           
        // }
        // else{
        //     $quiz_data['country_id']=  $previous_topic->country_id;
        // }
        // if($topic->subject_id!=$previous_topic->subject_id){
           
        //     $quiz_data['subject_id']=  $previous_topic->subject_id.','.$topic->subject_id;  
        // }
        // else{
        //     $quiz_data['subject_id']=  $previous_topic->subject_id;
        // }
        // $quiz_data['topic_id']=  $previous_topic->id.','.$topic->id;  
   
        DB::table('quiz_video')->whereRaw("find_in_set('$topic_id',topic_id)")->update(['country_id'=>$quiz_data['country_id'],'subject_id'=>$quiz_data['subject_id'],'topic_id'=>$quiz_data['topic_id']]);
        
        return redirect()->route('topics.index')->withStatus(__('Topic successfully cloned.'));
    }
}

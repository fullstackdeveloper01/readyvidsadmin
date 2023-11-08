<?php

namespace App\Http\Controllers;

use App\QuizVideo;
use App\OptionType;
use App\QuizTemplate;
use App\QuizVideoText;
use App\QuizVideoTextMapping;
use App\Topic;
use App\QuizTemplateType;
use App\NotificationStatus;
use App\Country;
use App\Subject;
use App\Helpers\Helper;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;
use Image;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use App\Notifications\DriverCreated;
use Dompdf\Dompdf;
use Dompdf\Options;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\VideoImport;
use App\Exports\QuizVideoExport;
use App\Exports\QuizVideoFullExport;

class QuizVideoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {   
        $countryList =Country::where('active','=',1)->get();
        
        $videos=QuizVideo::select('quiz_video.*');
                        // ->join('country','quiz_video.country_id','=','country.id')
                        // ->join('subjects','quiz_video.subject_id','=','subjects.id')
                        // ->join('topics','quiz_video.topic_id','=','topics.id');
                        
        if(!empty($_GET['country_id'])){
            //$videos=$videos->where('quiz_video.country_id','=',$_GET['country_id']);
             $country_id = $_GET['country_id'];
            $videos=$videos->whereRaw("find_in_set('$country_id',quiz_video.country_id)");
        }
        if(!empty($_GET['subject_id'])){
            //$videos=$videos->where('quiz_video.subject_id','=',$_GET['subject_id']);
            $subject_id = $_GET['subject_id'];
            $videos=$videos->whereRaw("find_in_set('$subject_id',quiz_video.subject_id)");
        }
        if(!empty($_GET['topic_id'])){
             $topic_id = $_GET['topic_id'];
            //$videos=$videos->where('quiz_video.topic_id','=',$_GET['topic_id']);
             $videos=$videos->whereRaw("find_in_set('$topic_id',quiz_video.topic_id)");
        }
        $videos=$videos->orderBy('id','desc')->paginate(100);
        
        return view('quiz_video.index', ['videos' =>$videos,'countryList'=>$countryList]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $countryList =Country::where('active','=',1)->get();
        $optionTypeList =OptionType::where('status','=',1)->get();
        $templateTypeList =QuizTemplateType::where('status','=',1)->get();
        return view('quiz_video.create',compact('countryList','templateTypeList','optionTypeList'));
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
            'template_type_id' => ['required'],
            'country_id' => ['required'],
            'subject_id' => ['required'],
            'option_type_id' => ['required'],
            'topic_id' => ['required'],
            'audio1'=>['required'],
            'answer_audio1'=>['required'],
            
        ]);

        $video = new QuizVideo;
        $video->template_type_id = $request->template_type_id;
        $video->country_id = $request->country_id;
        $video->subject_id = $request->subject_id;
        $video->option_type_id = $request->option_type_id;
        $video->topic_id = $request->topic_id;

        for($counter=0;$counter<count($request->text);$counter++){
            if($counter=='0'){
                $video->question = $request->text[$counter];
            }elseif($counter==(count($request->text)-1)){
                $video->answer = $request->text[$counter];
            }else{
                $optioncolumn='option'.$counter;
                $video->$optioncolumn = $request->text[$counter];
            }
            
        }
   
        
        if ($request->hasFile('audio1')) {
            $extension = $request->file('audio1')->getClientOriginalExtension();
            // Filename To store
            $fileNameToStore =time().'.'.$extension;

            $request->audio1->move(public_path('uploads/quiz_video/audio1'), $fileNameToStore);
            $video->audio1 = 'uploads/quiz_video/audio1/'.$fileNameToStore;
        }
      
        
        if ($request->hasFile('answer_audio1')) {
            $extension = $request->file('answer_audio1')->getClientOriginalExtension();
            // Filename To store
            $fileNameToStore =time().'.'.$extension;

            $request->answer_audio1->move(public_path('uploads/quiz_video/answer_audio1'), $fileNameToStore);
            $video->answer_audio1 = 'uploads/quiz_video/answer_audio1/'.$fileNameToStore;
        }
        //dd($video);
        
        if ($request->hasFile('audio2')) {
            $extension = $request->file('audio2')->getClientOriginalExtension();
            // Filename To store
            $fileNameToStore =time().'.'.$extension;

            $request->audio2->move(public_path('uploads/quiz_video/audio2'), $fileNameToStore);
            $video->audio2 = 'uploads/quiz_video/audio2/'.$fileNameToStore;
        }

        
        if ($request->hasFile('answer_audio2')) {
            $extension = $request->file('answer_audio2')->getClientOriginalExtension();
            // Filename To store
            $fileNameToStore =time().'.'.$extension;

            $request->answer_audio2->move(public_path('uploads/quiz_video/answer_audio2'), $fileNameToStore);
            $video->answer_audio2 = 'uploads/quiz_video/answer_audio2/'.$fileNameToStore;
        }

        
        if ($request->hasFile('audio3')) {
            $extension = $request->file('audio3')->getClientOriginalExtension();
            // Filename To store
            $fileNameToStore =time().'.'.$extension;

            $request->audio3->move(public_path('uploads/quiz_video/audio3'), $fileNameToStore);
            $video->audio3 = 'uploads/quiz_video/audio3/'.$fileNameToStore;
        }

        
        if ($request->hasFile('answer_audio3')) {
            $extension = $request->file('answer_audio3')->getClientOriginalExtension();
            // Filename To store
            $fileNameToStore =time().'.'.$extension;

            $request->answer_audio3->move(public_path('uploads/quiz_video/answer_audio3'), $fileNameToStore);
            $video->answer_audio3 = 'uploads/quiz_video/answer_audio3/'.$fileNameToStore;
        }

        
        if ($request->hasFile('audio4')) {
            $extension = $request->file('audio4')->getClientOriginalExtension();
            // Filename To store
            $fileNameToStore =time().'.'.$extension;

            $request->audio4->move(public_path('uploads/quiz_video/audio4'), $fileNameToStore);
            $video->audio4 = 'uploads/quiz_video/audio4/'.$fileNameToStore;
        }

        
        if ($request->hasFile('answer_audio4')) {
            $extension = $request->file('answer_audio4')->getClientOriginalExtension();
            // Filename To store
            $fileNameToStore =time().'.'.$extension;

            $request->answer_audio4->move(public_path('uploads/quiz_video/answer_audio4'), $fileNameToStore);
            $video->answer_audio4 = 'uploads/quiz_video/answer_audio4/'.$fileNameToStore;
        }

        
        if ($request->hasFile('audio5')) {
            $extension = $request->file('audio5')->getClientOriginalExtension();
            // Filename To store
            $fileNameToStore =time().'.'.$extension;

            $request->audio5->move(public_path('uploads/quiz_video/audio5'), $fileNameToStore);
            $video->audio5 = 'uploads/quiz_video/audio5/'.$fileNameToStore;
        }

        
        if ($request->hasFile('answer_audio5')) {
            $extension = $request->file('answer_audio5')->getClientOriginalExtension();
            // Filename To store
            $fileNameToStore =time().'.'.$extension;

            $request->answer_audio5->move(public_path('uploads/quiz_video/answer_audio5'), $fileNameToStore);
            $video->answer_audio5 = 'uploads/quiz_video/answer_audio5/'.$fileNameToStore;
        }

        
        if ($request->hasFile('audio6')) {
            $extension = $request->file('audio6')->getClientOriginalExtension();
            // Filename To store
            $fileNameToStore =time().'.'.$extension;

            $request->audio6->move(public_path('uploads/quiz_video/audio6'), $fileNameToStore);
            $video->audio6 = 'uploads/quiz_video/audio6/'.$fileNameToStore;
        }

        
        if ($request->hasFile('answer_audio6')) {
            $extension = $request->file('answer_audio6')->getClientOriginalExtension();
            // Filename To store
            $fileNameToStore =time().'.'.$extension;

            $request->answer_audio6->move(public_path('uploads/quiz_video/answer_audio6'), $fileNameToStore);
            $video->answer_audio6 = 'uploads/quiz_video/answer_audio6/'.$fileNameToStore;
        }

        
        if ($request->hasFile('audio7')) {
            $extension = $request->file('audio7')->getClientOriginalExtension();
            // Filename To store
            $fileNameToStore =time().'.'.$extension;

            $request->audio7->move(public_path('uploads/quiz_video/audio7'), $fileNameToStore);
            $video->audio7 = 'uploads/quiz_video/audio7/'.$fileNameToStore;
        }

        
        if ($request->hasFile('answer_audio7')) {
            $extension = $request->file('answer_audio7')->getClientOriginalExtension();
            // Filename To store
            $fileNameToStore =time().'.'.$extension;

            $request->answer_audio7->move(public_path('uploads/quiz_video/answer_audio7'), $fileNameToStore);
            $video->answer_audio7 = 'uploads/quiz_video/answer_audio7/'.$fileNameToStore;
        }
        
        
        if ($request->hasFile('audio8')) {
            $extension = $request->file('audio8')->getClientOriginalExtension();
            // Filename To store
            $fileNameToStore =time().'.'.$extension;

            $request->audio8->move(public_path('uploads/quiz_video/audio8'), $fileNameToStore);
            $video->audio8 = 'uploads/quiz_video/audio8/'.$fileNameToStore;
        }

        
        if ($request->hasFile('answer_audio8')) {
            $extension = $request->file('answer_audio8')->getClientOriginalExtension();
            // Filename To store
            $fileNameToStore =time().'.'.$extension;

            $request->answer_audio8->move(public_path('uploads/quiz_video/answer_audio8'), $fileNameToStore);
            $video->answer_audio8 = 'uploads/quiz_video/answer_audio8/'.$fileNameToStore;
        }
        
        if ($request->hasFile('audio9')) {
            $extension = $request->file('audio9')->getClientOriginalExtension();
            // Filename To store
            $fileNameToStore =time().'.'.$extension;

            $request->audio9->move(public_path('uploads/quiz_video/audio9'), $fileNameToStore);
            $video->audio9 = 'uploads/quiz_video/audio9/'.$fileNameToStore;
        }

        
        if ($request->hasFile('answer_audio9')) {
            $extension = $request->file('answer_audio9')->getClientOriginalExtension();
            // Filename To store
            $fileNameToStore =time().'.'.$extension;

            $request->answer_audio9->move(public_path('uploads/quiz_video/answer_audio9'), $fileNameToStore);
            $video->answer_audio9 = 'uploads/quiz_video/answer_audio9/'.$fileNameToStore;
        }

        
        if ($request->hasFile('audio10')) {
            $extension = $request->file('audio10')->getClientOriginalExtension();
            // Filename To store
            $fileNameToStore =time().'.'.$extension;

            $request->audio10->move(public_path('uploads/quiz_video/audio10'), $fileNameToStore);
            $video->audio10 = 'uploads/quiz_video/audio10/'.$fileNameToStore;
        }

        
        if ($request->hasFile('answer_audio10')) {
            $extension = $request->file('answer_audio10')->getClientOriginalExtension();
            // Filename To store
            $fileNameToStore =time().'.'.$extension;

            $request->answer_audio10->move(public_path('uploads/quiz_video/answer_audio10'), $fileNameToStore);
            $video->answer_audio10 = 'uploads/quiz_video/answer_audio10/'.$fileNameToStore;
        }

        
        if ($request->hasFile('audio11')) {
            $extension = $request->file('audio11')->getClientOriginalExtension();
            // Filename To store
            $fileNameToStore =time().'.'.$extension;

            $request->audio11->move(public_path('uploads/quiz_video/audio11'), $fileNameToStore);
            $video->audio11 = 'uploads/quiz_video/audio11/'.$fileNameToStore;
        }

        
        if ($request->hasFile('answer_audio11')) {
            $extension = $request->file('answer_audio11')->getClientOriginalExtension();
            // Filename To store
            $fileNameToStore =time().'.'.$extension;

            $request->answer_audio11->move(public_path('uploads/quiz_video/answer_audio11'), $fileNameToStore);
            $video->answer_audio11 = 'uploads/quiz_video/answer_audio11/'.$fileNameToStore;
        }
          
        
        if ($request->hasFile('audio12')) {
            $extension = $request->file('audio12')->getClientOriginalExtension();
            // Filename To store
            $fileNameToStore =time().'.'.$extension;

            $request->audio12->move(public_path('uploads/quiz_video/audio12'), $fileNameToStore);
            $video->audio12 = 'uploads/quiz_video/audio12/'.$fileNameToStore;
        }

        
        if ($request->hasFile('answer_audio12')) {
            $extension = $request->file('answer_audio12')->getClientOriginalExtension();
            // Filename To store
            $fileNameToStore =time().'.'.$extension;

            $request->answer_audio12->move(public_path('uploads/quiz_video/answer_audio12'), $fileNameToStore);
            $video->answer_audio12 = 'uploads/quiz_video/answer_audio12/'.$fileNameToStore;
        }
        
        if ($request->hasFile('audio13')) {
            $extension = $request->file('audio13')->getClientOriginalExtension();
            // Filename To store
            $fileNameToStore =time().'.'.$extension;

            $request->audio13->move(public_path('uploads/quiz_video/audio13'), $fileNameToStore);
            $video->audio13 = 'uploads/quiz_video/audio13/'.$fileNameToStore;
        }

        
        if ($request->hasFile('answer_audio13')) {
            $extension = $request->file('answer_audio13')->getClientOriginalExtension();
            // Filename To store
            $fileNameToStore =time().'.'.$extension;

            $request->answer_audio13->move(public_path('uploads/quiz_video/answer_audio13'), $fileNameToStore);
            $video->answer_audio13 = 'uploads/quiz_video/answer_audio13/'.$fileNameToStore;
        }
        
        if ($request->hasFile('audio14')) {
            $extension = $request->file('audio14')->getClientOriginalExtension();
            // Filename To store
            $fileNameToStore =time().'.'.$extension;

            $request->audio14->move(public_path('uploads/quiz_video/audio14'), $fileNameToStore);
            $video->audio14= 'uploads/quiz_video/audio14/'.$fileNameToStore;
        }

        
        if ($request->hasFile('answer_audio14')) {
            $extension = $request->file('answer_audio14')->getClientOriginalExtension();
            // Filename To store
            $fileNameToStore =time().'.'.$extension;

            $request->answer_audio14->move(public_path('uploads/quiz_video/answer_audio14'), $fileNameToStore);
            $video->answer_audio14 = 'uploads/quiz_video/answer_audio1/'.$fileNameToStore;
        }
        
        if ($request->hasFile('audio15')) {
            $extension = $request->file('audio15')->getClientOriginalExtension();
            // Filename To store
            $fileNameToStore =time().'.'.$extension;

            $request->audio15->move(public_path('uploads/quiz_video/audio15'), $fileNameToStore);
            $video->audio15 = 'uploads/quiz_video/audio15/'.$fileNameToStore;
        }

        
        if ($request->hasFile('answer_audio15')) {
            $extension = $request->file('answer_audio15')->getClientOriginalExtension();
            // Filename To store
            $fileNameToStore =time().'.'.$extension;

            $request->answer_audio15->move(public_path('uploads/quiz_video/answer_audio15'), $fileNameToStore);
            $video->answer_audio15 = 'uploads/quiz_video/answer_audio15/'.$fileNameToStore;
        }
           
          
        $video->save();
        
        // $video_text = new QuizVideoText;
        // for($counter=0;$counter<count($request->text);$counter++){
            
        //     $textresult= QuizVideoText::where('text','=',$request->text[$counter])->first();
        //     if($textresult==null){
        //         $video_text = new QuizVideoText;
        //         //$video_text->video_id = $video->id;
        //         $video_text->text = $request->text[$counter];
        //         $video_text->save();
                
        //         $video_text_mapping = new QuizVideoTextMapping;
        //         $video_text_mapping->video_id = $video->id;
        //         $video_text_mapping->text_id = $video_text->id;
        //         $video_text_mapping->save();
        //     }
        //     else{
                
        //         $video_text_mapping = new QuizVideoTextMapping;
        //         $video_text_mapping->video_id = $video->id;
        //         $video_text_mapping->text_id = $textresult->id;
        //         $video_text_mapping->save();
        //     }
           
        // }
        return redirect()->route('quiz_video.index')->withStatus(__('Video successfully created.'));

        
   
    }
    /**
     * Display the specified resource.
     *
     * @param  \App\Driver  $driver
     * @return \Illuminate\Http\Response
     */
    public function show(QuizVideo $driver)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Driver  $driver
     * @return \Illuminate\Http\Response
     */
    public function edit(QuizVideo $quiz_video)
    {
        if(auth()->user()->hasRole('admin')){
            $countryList =Country::where('active','=',1)->get();
            $optionTypeList =OptionType::where('status','=',1)->get();
            $templateTypeList =QuizTemplateType::where('status','=',1)->get();
            $subject_name= Subject::select('name')->where('id','=',$quiz_video->subject_id)->first();
            $topic_name= Topic::select('name')->where('id','=',$quiz_video->topic_id)->first();
            return view('quiz_video.edit',compact('quiz_video','countryList','templateTypeList','optionTypeList','subject_name','topic_name'));
           
        }else return redirect()->route('quiz_video.index')->withStatus(__('No Access'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Driver  $driver
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, QuizVideo $quiz_video)
    {
        $request->validate([
            'template_type_id' => ['required'],
            'country_id' => ['required'],
            'subject_id' => ['required'],
            'option_type_id' => ['required'],
            'topic_id' => ['required'],
                      
        ]);

        $quiz_video->template_type_id = $request->template_type_id;
        $quiz_video->country_id = $request->country_id;
        $quiz_video->subject_id = $request->subject_id;
        $quiz_video->option_type_id = $request->option_type_id;
        $quiz_video->topic_id = $request->topic_id;

        for($counter=0;$counter<count($request->text);$counter++){
            if($counter=='0'){
                $quiz_video->question = $request->text[$counter];
            }elseif($counter==(count($request->text)-1)){
                $quiz_video->answer = $request->text[$counter];
            }else{
                $optioncolumn='option'.$counter;
                $quiz_video->$optioncolumn = $request->text[$counter];
            }
            
        }

        
        if ($request->hasFile('audio1')) {
            if($quiz_video->audio1 != ''){
                $path = public_path().'/'.$quiz_video->audio1;
                if(file_exists($path)){
                    unlink($path);  
                }                
            }
            $extension = $request->file('audio1')->getClientOriginalExtension();
            // Filename To store
            $fileNameToStore =time().'.'.$extension;

            $request->audio1->move(public_path('uploads/quiz_video/audio1'), $fileNameToStore);
            $quiz_video->audio1 = 'uploads/quiz_video/audio1/'.$fileNameToStore;
        }
      
        if ($request->hasFile('answer_audio1')) {
            if($quiz_video->answer_audio1 != ''){
                $path = public_path().'/'.$quiz_video->answer_audio1;
                if(file_exists($path)){
                    unlink($path);  
                }                
            }
            $extension = $request->file('answer_audio1')->getClientOriginalExtension();
            // Filename To store
            $fileNameToStore =time().'.'.$extension;

            $request->answer_audio1->move(public_path('uploads/quiz_video/answer_audio1'), $fileNameToStore);
            $quiz_video->answer_audio1 = 'uploads/quiz_video/answer_audio1/'.$fileNameToStore;
        }
        //dd($video);
        
        if ($request->hasFile('audio2')) {

            if($quiz_video->audio2 != ''){
                $path = public_path().'/'.$quiz_video->audio2;
                if(file_exists($path)){
                    unlink($path);  
                }                
            }
            $extension = $request->file('audio2')->getClientOriginalExtension();
            // Filename To store
            $fileNameToStore =time().'.'.$extension;

            $request->audio2->move(public_path('uploads/quiz_video/audio2'), $fileNameToStore);
            $quiz_video->audio2 = 'uploads/quiz_video/audio2/'.$fileNameToStore;
        }

        
        if ($request->hasFile('answer_audio2')) {
            if($quiz_video->answer_audio2 != ''){
                $path = public_path().'/'.$quiz_video->answer_audio2;
                if(file_exists($path)){
                    unlink($path);  
                }                
            }
            $extension = $request->file('answer_audio2')->getClientOriginalExtension();
            // Filename To store
            $fileNameToStore =time().'.'.$extension;

            $request->answer_audio2->move(public_path('uploads/quiz_video/answer_audio2'), $fileNameToStore);
            $quiz_video->answer_audio2 = 'uploads/quiz_video/answer_audio2/'.$fileNameToStore;
        }

        
        if ($request->hasFile('audio3')) {
            if($quiz_video->audio3 != ''){
                $path = public_path().'/'.$quiz_video->audio3;
                if(file_exists($path)){
                    unlink($path);  
                }                
            }
            $extension = $request->file('audio3')->getClientOriginalExtension();
            // Filename To store
            $fileNameToStore =time().'.'.$extension;

            $request->audio3->move(public_path('uploads/quiz_video/audio3'), $fileNameToStore);
            $quiz_video->audio3 = 'uploads/quiz_video/audio3/'.$fileNameToStore;
        }

        
        if ($request->hasFile('answer_audio3')) {
            if($quiz_video->answer_audio3 != ''){
                $path = public_path().'/'.$quiz_video->answer_audio3;
                if(file_exists($path)){
                    unlink($path);  
                }                
            }
            
            $extension = $request->file('answer_audio3')->getClientOriginalExtension();
            // Filename To store
            $fileNameToStore =time().'.'.$extension;

            $request->answer_audio3->move(public_path('uploads/quiz_video/answer_audio3'), $fileNameToStore);
            $quiz_video->answer_audio3 = 'uploads/quiz_video/answer_audio3/'.$fileNameToStore;
        }

        
        if ($request->hasFile('audio4')) {
            if($quiz_video->audio4 != ''){
                $path = public_path().'/'.$quiz_video->audio4;
                if(file_exists($path)){
                    unlink($path);  
                }                
            }
            $extension = $request->file('audio4')->getClientOriginalExtension();
            // Filename To store
            $fileNameToStore =time().'.'.$extension;

            $request->audio4->move(public_path('uploads/quiz_video/audio4'), $fileNameToStore);
            $quiz_video->audio4 = 'uploads/quiz_video/audio4/'.$fileNameToStore;
        }

        
        if ($request->hasFile('answer_audio4')) {
            if($quiz_video->answer_audio4 != ''){
                $path = public_path().'/'.$quiz_video->answer_audio4;
                if(file_exists($path)){
                    unlink($path);  
                }                
            }
            $extension = $request->file('answer_audio4')->getClientOriginalExtension();
            // Filename To store
            $fileNameToStore =time().'.'.$extension;

            $request->answer_audio4->move(public_path('uploads/quiz_video/answer_audio4'), $fileNameToStore);
            $quiz_video->answer_audio4 = 'uploads/quiz_video/answer_audio4/'.$fileNameToStore;
        }

        
        if ($request->hasFile('audio5')) {
            if($quiz_video->audio5 != ''){
                $path = public_path().'/'.$quiz_video->audio5;
                if(file_exists($path)){
                    unlink($path);  
                }                
            }
            $extension = $request->file('audio5')->getClientOriginalExtension();
            // Filename To store
            $fileNameToStore =time().'.'.$extension;

            $request->audio5->move(public_path('uploads/quiz_video/audio5'), $fileNameToStore);
            $quiz_video->audio5 = 'uploads/quiz_video/audio5/'.$fileNameToStore;
        }

        
        if ($request->hasFile('answer_audio5')) {
            if($quiz_video->answer_audio5 != ''){
                $path = public_path().'/'.$quiz_video->answer_audio5;
                if(file_exists($path)){
                    unlink($path);  
                }                
            }
            $extension = $request->file('answer_audio5')->getClientOriginalExtension();
            // Filename To store
            $fileNameToStore =time().'.'.$extension;

            $request->answer_audio5->move(public_path('uploads/quiz_video/answer_audio5'), $fileNameToStore);
            $quiz_video->answer_audio5 = 'uploads/quiz_video/answer_audio5/'.$fileNameToStore;
        }

        
        if ($request->hasFile('audio6')) {
            if($quiz_video->audio6 != ''){
                $path = public_path().'/'.$quiz_video->audio6;
                if(file_exists($path)){
                    unlink($path);  
                }                
            }
            $extension = $request->file('audio6')->getClientOriginalExtension();
            // Filename To store
            $fileNameToStore =time().'.'.$extension;

            $request->audio6->move(public_path('uploads/quiz_video/audio6'), $fileNameToStore);
            $quiz_video->audio6 = 'uploads/quiz_video/audio6/'.$fileNameToStore;
        }

        
        if ($request->hasFile('answer_audio6')) {
            if($quiz_video->answer_audio6 != ''){
                $path = public_path().'/'.$quiz_video->answer_audio6;
                if(file_exists($path)){
                    unlink($path);  
                }                
            }
            $extension = $request->file('answer_audio6')->getClientOriginalExtension();
            // Filename To store
            $fileNameToStore =time().'.'.$extension;

            $request->answer_audio6->move(public_path('uploads/quiz_video/answer_audio6'), $fileNameToStore);
            $quiz_video->answer_audio6 = 'uploads/quiz_video/answer_audio6/'.$fileNameToStore;
        }

        
        if ($request->hasFile('audio7')) {
            if($quiz_video->audio7 != ''){
                $path = public_path().'/'.$quiz_video->audio7;
                if(file_exists($path)){
                    unlink($path);  
                }                
            }
            $extension = $request->file('audio7')->getClientOriginalExtension();
            // Filename To store
            $fileNameToStore =time().'.'.$extension;

            $request->audio7->move(public_path('uploads/quiz_video/audio7'), $fileNameToStore);
            $quiz_video->audio7 = 'uploads/quiz_video/audio7/'.$fileNameToStore;
        }

        
        if ($request->hasFile('answer_audio7')) {
            if($quiz_video->answer_audio7 != ''){
                $path = public_path().'/'.$quiz_video->answer_audio7;
                if(file_exists($path)){
                    unlink($path);  
                }                
            }
            $extension = $request->file('answer_audio7')->getClientOriginalExtension();
            // Filename To store
            $fileNameToStore =time().'.'.$extension;

            $request->answer_audio7->move(public_path('uploads/quiz_video/answer_audio7'), $fileNameToStore);
            $quiz_video->answer_audio7 = 'uploads/quiz_video/answer_audio7/'.$fileNameToStore;
        }
        
        
        if ($request->hasFile('audio8')) {
            if($quiz_video->audio8 != ''){
                $path = public_path().'/'.$quiz_video->audio8;
                if(file_exists($path)){
                    unlink($path);  
                }                
            }
            $extension = $request->file('audio8')->getClientOriginalExtension();
            // Filename To store
            $fileNameToStore =time().'.'.$extension;

            $request->audio8->move(public_path('uploads/quiz_video/audio8'), $fileNameToStore);
            $quiz_video->audio8 = 'uploads/quiz_video/audio8/'.$fileNameToStore;
        }

        
        if ($request->hasFile('answer_audio8')) {
            if($quiz_video->answer_audio8 != ''){
                $path = public_path().'/'.$quiz_video->answer_audio8;
                if(file_exists($path)){
                    unlink($path);  
                }                
            }
            $extension = $request->file('answer_audio8')->getClientOriginalExtension();
            // Filename To store
            $fileNameToStore =time().'.'.$extension;

            $request->answer_audio8->move(public_path('uploads/quiz_video/answer_audio8'), $fileNameToStore);
            $quiz_video->answer_audio8 = 'uploads/quiz_video/answer_audio8/'.$fileNameToStore;
        }
        
        if ($request->hasFile('audio9')) {
            if($quiz_video->audio9 != ''){
                $path = public_path().'/'.$quiz_video->audio9;
                if(file_exists($path)){
                    unlink($path);  
                }                
            }
            $extension = $request->file('audio9')->getClientOriginalExtension();
            // Filename To store
            $fileNameToStore =time().'.'.$extension;

            $request->audio9->move(public_path('uploads/quiz_video/audio9'), $fileNameToStore);
            $quiz_video->audio9 = 'uploads/quiz_video/audio9/'.$fileNameToStore;
        }

        
        if ($request->hasFile('answer_audio9')) {
            if($quiz_video->answer_audio9 != ''){
                $path = public_path().'/'.$quiz_video->answer_audio9;
                if(file_exists($path)){
                    unlink($path);  
                }                
            }
            $extension = $request->file('answer_audio9')->getClientOriginalExtension();
            // Filename To store
            $fileNameToStore =time().'.'.$extension;

            $request->answer_audio9->move(public_path('uploads/quiz_video/answer_audio9'), $fileNameToStore);
            $quiz_video->answer_audio9 = 'uploads/quiz_video/answer_audio9/'.$fileNameToStore;
        }

        
        if ($request->hasFile('audio10')) {
            if($quiz_video->audio10 != ''){
                $path = public_path().'/'.$quiz_video->audio10;
                if(file_exists($path)){
                    unlink($path);  
                }                
            }
            $extension = $request->file('audio10')->getClientOriginalExtension();
            // Filename To store
            $fileNameToStore =time().'.'.$extension;

            $request->audio10->move(public_path('uploads/quiz_video/audio10'), $fileNameToStore);
            $quiz_video->audio10 = 'uploads/quiz_video/audio10/'.$fileNameToStore;
        }

        
        if ($request->hasFile('answer_audio10')) {
            if($quiz_video->answer_audio10 != ''){
                $path = public_path().'/'.$quiz_video->answer_audio10;
                if(file_exists($path)){
                    unlink($path);  
                }                
            }
            $extension = $request->file('answer_audio10')->getClientOriginalExtension();
            // Filename To store
            $fileNameToStore =time().'.'.$extension;

            $request->answer_audio10->move(public_path('uploads/quiz_video/answer_audio10'), $fileNameToStore);
            $quiz_video->answer_audio10 = 'uploads/quiz_video/answer_audio10/'.$fileNameToStore;
        }

        
        if ($request->hasFile('audio11')) {
            if($quiz_video->audio11 != ''){
                $path = public_path().'/'.$quiz_video->audio11;
                if(file_exists($path)){
                    unlink($path);  
                }                
            }
            $extension = $request->file('audio11')->getClientOriginalExtension();
            // Filename To store
            $fileNameToStore =time().'.'.$extension;

            $request->audio11->move(public_path('uploads/quiz_video/audio11'), $fileNameToStore);
            $quiz_video->audio11 = 'uploads/quiz_video/audio11/'.$fileNameToStore;
        }

        
        if ($request->hasFile('answer_audio11')) {
            if($quiz_video->answer_audio11 != ''){
                $path = public_path().'/'.$quiz_video->answer_audio11;
                if(file_exists($path)){
                    unlink($path);  
                }                
            }
            $extension = $request->file('answer_audio11')->getClientOriginalExtension();
            // Filename To store
            $fileNameToStore =time().'.'.$extension;

            $request->answer_audio11->move(public_path('uploads/quiz_video/answer_audio11'), $fileNameToStore);
            $quiz_video->answer_audio11 = 'uploads/quiz_video/answer_audio11/'.$fileNameToStore;
        }
          
        
        if ($request->hasFile('audio12')) {
            if($quiz_video->audio12 != ''){
                $path = public_path().'/'.$quiz_video->audio12;
                if(file_exists($path)){
                    unlink($path);  
                }                
            }
            $extension = $request->file('audio12')->getClientOriginalExtension();
            // Filename To store
            $fileNameToStore =time().'.'.$extension;

            $request->audio12->move(public_path('uploads/quiz_video/audio12'), $fileNameToStore);
            $quiz_video->audio12 = 'uploads/quiz_video/audio12/'.$fileNameToStore;
        }

        
        if ($request->hasFile('answer_audio12')) {
            if($quiz_video->answer_audio12 != ''){
                $path = public_path().'/'.$quiz_video->answer_audio12;
                if(file_exists($path)){
                    unlink($path);  
                }                
            }
            $extension = $request->file('answer_audio12')->getClientOriginalExtension();
            // Filename To store
            $fileNameToStore =time().'.'.$extension;

            $request->answer_audio12->move(public_path('uploads/quiz_video/answer_audio12'), $fileNameToStore);
            $quiz_video->answer_audio12 = 'uploads/quiz_video/answer_audio12/'.$fileNameToStore;
        }
        
        if ($request->hasFile('audio13')) {
            if($quiz_video->audio13 != ''){
                $path = public_path().'/'.$quiz_video->audio13;
                if(file_exists($path)){
                    unlink($path);  
                }                
            }
            $extension = $request->file('audio13')->getClientOriginalExtension();
            // Filename To store
            $fileNameToStore =time().'.'.$extension;

            $request->audio13->move(public_path('uploads/quiz_video/audio13'), $fileNameToStore);
            $quiz_video->audio13 = 'uploads/quiz_video/audio13/'.$fileNameToStore;
        }

        
        if ($request->hasFile('answer_audio13')) {
            if($quiz_video->answer_audio13 != ''){
                $path = public_path().'/'.$quiz_video->answer_audio13;
                if(file_exists($path)){
                    unlink($path);  
                }                
            }
            $extension = $request->file('answer_audio13')->getClientOriginalExtension();
            // Filename To store
            $fileNameToStore =time().'.'.$extension;

            $request->answer_audio13->move(public_path('uploads/quiz_video/answer_audio13'), $fileNameToStore);
            $quiz_video->answer_audio13 = 'uploads/quiz_video/answer_audio13/'.$fileNameToStore;
        }
        
        if ($request->hasFile('audio14')) {
            if($quiz_video->audio14 != ''){
                $path = public_path().'/'.$quiz_video->audio14;
                if(file_exists($path)){
                    unlink($path);  
                }                
            }
            $extension = $request->file('audio14')->getClientOriginalExtension();
            // Filename To store
            $fileNameToStore =time().'.'.$extension;

            $request->audio14->move(public_path('uploads/quiz_video/audio14'), $fileNameToStore);
            $quiz_video->audio14= 'uploads/quiz_video/audio14/'.$fileNameToStore;
        }

        
        if ($request->hasFile('answer_audio14')) {
            if($quiz_video->answer_audio14 != ''){
                $path = public_path().'/'.$quiz_video->answer_audio14;
                if(file_exists($path)){
                    unlink($path);  
                }                
            }
            $extension = $request->file('answer_audio14')->getClientOriginalExtension();
            // Filename To store
            $fileNameToStore =time().'.'.$extension;

            $request->answer_audio14->move(public_path('uploads/quiz_video/answer_audio14'), $fileNameToStore);
            $quiz_video->answer_audio14 = 'uploads/quiz_video/answer_audio1/'.$fileNameToStore;
        }
        
        if ($request->hasFile('audio15')) {
            if($quiz_video->audio15 != ''){
                $path = public_path().'/'.$quiz_video->audio15;
                if(file_exists($path)){
                    unlink($path);  
                }                
            }
            $extension = $request->file('audio15')->getClientOriginalExtension();
            // Filename To store
            $fileNameToStore =time().'.'.$extension;

            $request->audio15->move(public_path('uploads/quiz_video/audio15'), $fileNameToStore);
            $quiz_video->audio15 = 'uploads/quiz_video/audio15/'.$fileNameToStore;
        }

        
        if ($request->hasFile('answer_audio15')) {
            if($quiz_video->answer_audio15 != ''){
                $path = public_path().'/'.$quiz_video->answer_audio15;
                if(file_exists($path)){
                    unlink($path);  
                }                
            }
            $extension = $request->file('answer_audio15')->getClientOriginalExtension();
            // Filename To store
            $fileNameToStore =time().'.'.$extension;

            $request->answer_audio15->move(public_path('uploads/quiz_video/answer_audio15'), $fileNameToStore);
            $quiz_video->answer_audio15 = 'uploads/quiz_video/answer_audio15/'.$fileNameToStore;
        }
          
          
        $quiz_video->update();
       
        return redirect()->route('quiz_video.index')->withStatus(__('video successfully updated.'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Driver  $driver
     * @return \Illuminate\Http\Response
     */
    public function destroy(QuizVideo $quiz_video)
    {
        $affectedRows = $quiz_video->delete();
        if($affectedRows)
        {
          
            if($quiz_video->audio1 != ''){
                $path = public_path().'/'.$quiz_video->audio1;
                if(file_exists($path)){
                    unlink($path);  
                }              
            }
            if($quiz_video->audio2 != ''){
                $path = public_path().'/'.$quiz_video->audio2;
                if(file_exists($path)){
                    unlink($path);  
                }              
            }
            if($quiz_video->audio3 != ''){
                $path = public_path().'/'.$quiz_video->audio3;
                if(file_exists($path)){
                    unlink($path);  
                }              
            }
            if($quiz_video->audio4 != ''){
                $path = public_path().'/'.$quiz_video->audio4;
                if(file_exists($path)){
                    unlink($path);  
                }              
            }
            if($quiz_video->audio5 != ''){
                $path = public_path().'/'.$quiz_video->audio5;
                if(file_exists($path)){
                    unlink($path);  
                }              
            }
            if($quiz_video->audio6 != ''){
                $path = public_path().'/'.$quiz_video->audio6;
                if(file_exists($path)){
                    unlink($path);  
                }              
            }
            if($quiz_video->audio7 != ''){
                $path = public_path().'/'.$quiz_video->audio7;
                if(file_exists($path)){
                    unlink($path);  
                }              
            }
            if($quiz_video->audio8 != ''){
                $path = public_path().'/'.$quiz_video->audio8;
                if(file_exists($path)){
                    unlink($path);  
                }              
            }
            if($quiz_video->audio9 != ''){
                $path = public_path().'/'.$quiz_video->audio9;
                if(file_exists($path)){
                    unlink($path);  
                }              
            }
            if($quiz_video->audio10 != ''){
                $path = public_path().'/'.$quiz_video->audio10;
                if(file_exists($path)){
                    unlink($path);  
                }              
            }
            if($quiz_video->audio11 != ''){
                $path = public_path().'/'.$quiz_video->audio11;
                if(file_exists($path)){
                    unlink($path);  
                }              
            }
            if($quiz_video->audio12 != ''){
                $path = public_path().'/'.$quiz_video->audio12;
                if(file_exists($path)){
                    unlink($path);  
                }              
            }
            if($quiz_video->audio13 != ''){
                $path = public_path().'/'.$quiz_video->audio13;
                if(file_exists($path)){
                    unlink($path);  
                }              
            }
            if($quiz_video->audio14 != ''){
                $path = public_path().'/'.$quiz_video->audio14;
                if(file_exists($path)){
                    unlink($path);  
                }              
            }
            if($quiz_video->audio15 != ''){
                $path = public_path().'/'.$quiz_video->audio15;
                if(file_exists($path)){
                    unlink($path);  
                }              
            }
            if($quiz_video->answer_audio1 != ''){
                $path = public_path().'/'.$quiz_video->answer_audio1;
                if(file_exists($path)){
                    unlink($path);  
                }              
            }
            if($quiz_video->answer_audio2 != ''){
                $path = public_path().'/'.$quiz_video->answer_audio2;
                if(file_exists($path)){
                    unlink($path);  
                }              
            }
            if($quiz_video->answer_audio3 != ''){
                $path = public_path().'/'.$quiz_video->answer_audio3;
                if(file_exists($path)){
                    unlink($path);  
                }              
            }
            if($quiz_video->answer_audio4 != ''){
                $path = public_path().'/'.$quiz_video->answer_audio4;
                if(file_exists($path)){
                    unlink($path);  
                }              
            }
            if($quiz_video->answer_audio5 != ''){
                $path = public_path().'/'.$quiz_video->answer_audio5;
                if(file_exists($path)){
                    unlink($path);  
                }              
            }

            if($quiz_video->answer_audio6 != ''){
                $path = public_path().'/'.$quiz_video->answer_audio6;
                if(file_exists($path)){
                    unlink($path);  
                }              
            }

            if($quiz_video->answer_audio7 != ''){
                $path = public_path().'/'.$quiz_video->answer_audio7;
                if(file_exists($path)){
                    unlink($path);  
                }              
            }
            if($quiz_video->answer_audio8 != ''){
                $path = public_path().'/'.$quiz_video->answer_audio8;
                if(file_exists($path)){
                    unlink($path);  
                }              
            }
            if($quiz_video->answer_audio9 != ''){
                $path = public_path().'/'.$quiz_video->answer_audio9;
                if(file_exists($path)){
                    unlink($path);  
                }              
            }
            if($quiz_video->answer_audio10 != ''){
                $path = public_path().'/'.$quiz_video->answer_audio10;
                if(file_exists($path)){
                    unlink($path);  
                }              
            }
            if($quiz_video->answer_audio11 != ''){
                $path = public_path().'/'.$quiz_video->answer_audio11;
                if(file_exists($path)){
                    unlink($path);  
                }              
            }
            if($quiz_video->answer_audio12 != ''){
                $path = public_path().'/'.$quiz_video->answer_audio12;
                if(file_exists($path)){
                    unlink($path);  
                }              
            }
            if($quiz_video->answer_audio13 != ''){
                $path = public_path().'/'.$quiz_video->answer_audio13;
                if(file_exists($path)){
                    unlink($path);  
                }              
            }
            if($quiz_video->answer_audio14 != ''){
                $path = public_path().'/'.$quiz_video->answer_audio14;
                if(file_exists($path)){
                    unlink($path);  
                }              
            }
            if($quiz_video->answer_audio15 != ''){
                $path = public_path().'/'.$quiz_video->answer_audio15;
                if(file_exists($path)){
                    unlink($path);  
                }              
            }
           
          
        }
         
        return redirect()->route('quiz_video.index')->withStatus(__('Video successfully deleted.'));
    }
    public function status($id,$status)
    {  
        $video = QuizVideo::findorfail($id);
        if($video->status==1){
            $video->status=0;
        }else{
            $video->status=1;
        }
       
        $video->update();

        echo true;
        
    }

     public function bulk_upload(){
        return view('quiz_video.bulk_upload');
    }
    
    
    public function bulkstore(Request $request){
       
         ini_set('max_execution_time', '0');
          ini_set('memory_limit ', '-1');
          
            $request->validate([
                'bulkupload' => ['required'],
            ]);
    
    
            if ($request->hasFile('bulkupload')) {
            
                $extension = $request->file('bulkupload')->getClientOriginalExtension();
                // Filename To store
                $fileNameToStore = time().'.'.$extension;
    
                $request->bulkupload->move(public_path('uploads/video/bulk_upload'), $fileNameToStore);
                $path = public_path().'/uploads/video/bulk_upload/'.$fileNameToStore;
    
                $data = Excel::toArray(new VideoImport(), $path);
                array_shift($data[0]);
              
                $csv_data=$data[0];
    
                // $csv_data = array_map('str_getcsv', file( $path));
                // array_shift($csv_data);
                //dd($csv_data);
                foreach($csv_data as $key=>$line){
                    $video = new QuizVideo;
                    
                    if($line[0]!=''){
                       
                      
                        $video->template_type_id = $line[0];
                        $video->country_id = $line[1];
                        $video->subject_id = $line[2];
                        $video->option_type_id = $line[3];
                        $video->topic_id = $line[4];
                        $video->audio1 = $line[5];
                        $video->audio2 = $line[6];
                        $video->audio3 = $line[7];
                        $video->audio4 = $line[8];
                        $video->audio5 = $line[9];
                        $video->audio6 = $line[10];
                        $video->audio7 = $line[11];
                        $video->audio8 = $line[12];
                        $video->audio9 = $line[13];
                        $video->audio10 = $line[14];
                        $video->audio11= $line[15];
                        $video->audio12 = $line[16];
                        $video->audio13 = $line[17];
                        $video->audio14 = $line[18];
                        $video->audio15 = $line[19];
                         $video->audio16= $line[20];
                        $video->audio17 = $line[21];
                        $video->audio18 = $line[22];
                        $video->audio19 = $line[23];
                        $video->audio20 = $line[24];
                        
                        $video->answer_audio1 = $line[25];
                        $video->answer_audio2 = $line[26];
                        $video->answer_audio3 = $line[27];
                        
                        $video->answer_audio4 = $line[28];
                        $video->answer_audio5 = $line[29];
                        $video->answer_audio6 = $line[30];
                        
                        $video->answer_audio7 = $line[31];
                        $video->answer_audio8 = $line[32];
                        $video->answer_audio9 = $line[33];
                        
                        $video->answer_audio10 = $line[34];
                        $video->answer_audio11 = $line[35];
                        $video->answer_audio12 = $line[36];
                        
                        $video->answer_audio13 = $line[37];
                        $video->answer_audio14 = $line[38];
                        $video->answer_audio15 = $line[39];
                        
                        $video->answer_audio16 = $line[40];
                        $video->answer_audio17 = $line[41];
                        
                        $video->answer_audio18 = $line[42];
                        $video->answer_audio19 = $line[43];
                        $video->answer_audio20 = $line[44];
                        
                        $video->question = $line[45];
                        
                        $video->answer = $line[count($line)-1];
                        
                        $i=1;
                        for($counter=46;$counter<(count($line)-1);$counter++){
                            $column = 'option'.$i;
                            $video->$column = $line[$counter];
                            $i++;
                        }
                    }
                    
                    $video->save();
                    //dd($video);
                }
                
                return redirect()->route('quiz_video.index')->withStatus(__('Bulk Upload successfully.'));
            }
    
           
        }
    
    public function download($source,$destination){
       
        $curl = curl_init();

        curl_setopt_array($curl, array(
        CURLOPT_URL => $source,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'GET',
        ));

        $response = curl_exec($curl);
        sleep(2);
        //echo $destination;
        $fh = fopen($destination, "w") or die("ERROR opening " . $destination);
        $result=file_put_contents($destination, $response);
         sleep(2);
      
        if($result!='false'){
            curl_close($curl);
           
            if(file_exists($destination)) { 
                $status="OK";
            }else{
                $status= "ERROR -";
            }
           
            return $status;
        }
        // $timeout = 30; // 30 seconds CURL timeout, increase if downloading large file

        // // (B) FILE HANDLER
        // $fh = fopen($destination, "w") or die("ERROR opening " . $destination);

        // // (C) CURL INIT
        // $ch = curl_init();
        // curl_setopt($ch, CURLOPT_URL, $source);
        // curl_setopt($ch, CURLOPT_FILE, $fh);
        // curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);

        // // (D) CURL RUN
        // curl_exec($ch);
        // if (curl_errno($ch)) {
        // // (D1) CURL ERROR
        // $status= "CURL ERROR - " . curl_error($ch);
        // } else {
        // // (D2) CURL OK
        // // NOTE: HTTP STATUS CODE 200 = OK
        // // BAD DOWNLOAD IF SERVER RETURNS 401 (UNAUTHORIZED), 403 (FORBIDDEN), 404 (NOT FOUND)
        // $status = curl_getinfo($ch);
        // // print_r($status);
        // $status= $status["http_code"] == 200 ? "OK" : "ERROR - " . $status["http_code"] ;
        //return redirect()->route('video.index')->withStatus(__('Audio Female is required'));
       // }

        // (D3) CLOSE CURL & FILE
        // curl_close($ch);
        // fclose($fh);
        // return $status;
    }
    
    public function getDownload(){
        $file= public_path(). "/sample.csv";

        $headers = array(
                'Content-Type: application/csv',
                );

        return response()->download($file);
    }

    public function makeSampleDownload(){
        $countryList =Country::where('active','=',1)->get();
        $optionTypeList =OptionType::where('status','=',1)->get();
        $templateTypeList =QuizTemplateType::where('status','=',1)->get();
        return view('quiz_video.makesampledownload',compact('countryList','templateTypeList','optionTypeList'));
        
    }
    
    public function makeSample(Request $request)
    {
       
        $request->validate([
            'template_type_id' => ['required'],
            'country_id' => ['required'],
            'subject_id' => ['required'],
            'option_type_id' => ['required'],
            'topic_id' => ['required'],
            
        ]);
        
         //return Excel::download(new QuizVideoExport( $request->all()), 'samplequiz.xlsx');
        
           
        $topic = Topic::findorfail($request->topic_id);
        $file_name = $topic->name.'.xlsx';
        // Excel file name for download 
        $fileName = "sample" . date('Y-m-d') . ".xls"; 
        
        // Column names 
        $fields = array('Template Type', 'Country', 'Subject', 'Option Type', 'Topic','Question Speaker Audio 1','Question Speaker Audio 2','Question Speaker Audio 3','Question Speaker Audio 4','Question Speaker Audio 5','Question Speaker Audio 6','Question Speaker Audio 7','Question Speaker Audio 8','Question Speaker Audio 9','Question Speaker Audio 10','Question Speaker Audio 11','Question Speaker Audio 12','Question Speaker Audio 13','Question Speaker Audio 14','Question Speaker Audio 15','Question Speaker Audio 16','Question Speaker Audio 17','Question Speaker Audio 18','Question Speaker Audio 19','Question Speaker Audio 20','Answer Speaker Audio 1','Answer Speaker Audio 2','Answer Speaker Audio 3','Answer Speaker Audio 4','Answer Speaker Audio 5','Answer Speaker Audio 6','Answer Speaker Audio 7','Answer Speaker Audio 8','Answer Speaker Audio 9','Answer Speaker Audio 10','Answer Speaker Audio 11','Answer Speaker Audio 12','Answer Speaker Audio 13','Answer Speaker Audio 14','Answer Speaker Audio 15','Answer Speaker Audio 16','Answer Speaker Audio 17','Answer Speaker Audio 18','Answer Speaker Audio 19','Answer Speaker Audio 20');
        
        $values = array($request->template_type_id, $request->country_id, $request->subject_id, $request->option_type_id,$request->topic_id,'uploads/video/audio_m/1668171961.mp3','uploads/video/audio_m/1668171961.mp3','uploads/video/audio_m/1668171961.mp3','uploads/video/audio_m/1668171961.mp3','uploads/video/audio_m/1668171961.mp3','uploads/video/audio_m/1668171961.mp3','uploads/video/audio_m/1668171961.mp3','uploads/video/audio_m/1668171961.mp3','uploads/video/audio_m/1668171961.mp3','uploads/video/audio_m/1668171961.mp3','uploads/video/audio_m/1668171961.mp3','uploads/video/audio_m/1668171961.mp3','uploads/video/audio_m/1668171961.mp3','uploads/video/audio_m/1668171961.mp3','uploads/video/audio_m/1668171961.mp3','uploads/video/audio_m/1668171961.mp3','uploads/video/audio_m/1668171961.mp3','uploads/video/audio_m/1668171961.mp3','uploads/video/audio_m/1668171961.mp3','uploads/video/audio_m/1668171961.mp3','uploads/video/audio_m/1668171961.mp3','uploads/video/audio_m/1668171961.mp3','uploads/video/audio_m/1668171961.mp3','uploads/video/audio_m/1668171961.mp3','uploads/video/audio_m/1668171961.mp3','uploads/video/audio_m/1668171961.mp3','uploads/video/audio_m/1668171961.mp3','uploads/video/audio_m/1668171961.mp3','uploads/video/audio_m/1668171961.mp3','uploads/video/audio_m/1668171961.mp3','uploads/video/audio_m/1668171961.mp3','uploads/video/audio_m/1668171961.mp3','uploads/video/audio_m/1668171961.mp3','uploads/video/audio_m/1668171961.mp3','uploads/video/audio_m/1668171961.mp3','uploads/video/audio_m/1668171961.mp3','uploads/video/audio_m/1668171961.mp3','uploads/video/audio_m/1668171961.mp3','uploads/video/audio_m/1668171961.mp3','uploads/video/audio_m/1668171961.mp3');     
     
        $templateType= QuizTemplateType::findorfail($request->template_type_id);
        $type= $templateType->type;
        $type_array = explode(' ',$type);
       // dd($type_array);
        for($counter=0;$counter<=$type_array[0];$counter++){
            if($counter=='0'){
                array_push($fields,"Question");
                array_push($values,"Test");    
            }
            else if($counter==$type_array[0]){
                array_push($fields,"Answer");
                array_push($values,"Test");    
            }else{
                array_push($fields,"Option".$counter);
                array_push($values,"Test");   
            }
            
        }
        
       // return Excel::download(new QuizVideoExport, 'samplequiz.xlsx');
         
         
        // Display column names as first row 
        $excelData = implode("\t", array_values($fields)) . "\n"; 
        $excelData .= implode("\t", array_values($values)) . "\n"; 
      
      
        return Excel::download(new QuizVideoExport( array_values($fields),array_values($values)), $file_name);
       
      // Headers for download 
        header("Content-Type: application/vnd.ms-excel"); 
        header("Content-Disposition: attachment; filename=\"$fileName\""); 
        
        
        
    // Download file with custom headers
    return response()->download($path, $filename, [
        'Content-Type' => 'application/vnd.ms-excel',
        'Content-Disposition' => 'inline; filename="' . $filename . '"'
    ]);
         // Render excel data 
        echo $excelData; 
        exit;
        
       
        $delimiter = ","; 
        $filename = "sample_" . date('Y-m-d') . ".csv"; 
         
        // Create a file pointer 
        $f = fopen('php://memory', 'w'); 
         
        // Set column headers 
       
        fputcsv($f, $fields, $delimiter); 
        fputcsv($f, $values, $delimiter); 
        // Move back to beginning of file 
        fseek($f, 0); 
        
        // Set headers to download file rather than displayed 
        header('Content-Type: text/csv'); 
        header('Content-Disposition: attachment; filename="' . $filename . '";'); 
        
        //output all remaining data on a file pointer 
        fpassthru($f); 
          exit;
    }
    public function getDownloadVideo(){
      echo "ff";die;
        $file= public_path(). "/uploads/make_video/2/1669725175.mp4";//"/sample.csv";

        // $headers = array(
        //         'Content-Type: application/csv',
        //         );
        $headers = array(
                'Content-Type: application/mp4',
                );

        return response()->download($file);
    }
    public function export(Request $request){
        
        if(!empty($_GET['country_id'])){
            
            $country=Country::findorfail($request->country_id);
            
            $file_name = $country->country_name.'.xlsx';
        }
        if(!empty($_GET['subject_id'])){
            
            $subject = Subject::findorfail($request->subject_id);
             
            $file_name = $subject->name.'.xlsx';
        }
        if(!empty($_GET['topic_id'])){
            $topic = Topic::findorfail($request->topic_id);
             
            $file_name = $topic->name.'.xlsx';
        }
        return Excel::download(new QuizVideoFullExport, $file_name);
    }
    
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\VideoVoice;
use App\Voice;
use App\Folders;
use App\QuestionAnswerVoice;
use App\QuestionAnswerVideoVoice;
use App\Exports\ImagesExport;
use Maatwebsite\Excel\Facades\Excel;
class CronController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    protected $imagePath="/uploads/video_voice/";
    public function uploadVoice()
    {
        $voice=Voice::where('isupload','=','0')->first();
        //echo "<pre>";print_r($voice['audio_m1']);die;
        if($voice!=null){
            //foreach($voices as $key=>$voice){
                //echo "<pre>";print_r($voice);die;
                $video_voice=new VideoVoice();
                if($voice['audio_m1']!=''){
                    
                    $time=time();
                    $path = public_path().'/uploads/video/audio_m/'.$time.'.mp3';
 
                     //file_put_contents($img, file_get_contents($line[4]));
                     $status=$this->download($voice['audio_m1'],$path);
                     if($status=='OK'){
                         $video_voice->audio_m1 = 'uploads/video/audio_m/'.$time.'.mp3';
                     }
                }
                if($voice['audio_m2']!=''){
                    
                    $time=time();
                    $path = public_path().'/uploads/video/audio_m/'.$time.'.mp3';
 
                     //file_put_contents($img, file_get_contents($line[4]));
                     $status=$this->download($voice['audio_m2'],$path);
                     if($status=='OK'){
                         $video_voice->audio_m2 = 'uploads/video/audio_m/'.$time.'.mp3';
                     }
                }
                if($voice['audio_m3']!=''){
                    
                    $time=time();
                    $path = public_path().'/uploads/video/audio_m/'.$time.'.mp3';
 
                     //file_put_contents($img, file_get_contents($line[4]));
                     $status=$this->download($voice->audio_m3,$path);
                     if($status=='OK'){
                         $video_voice->audio_m3 = 'uploads/video/audio_m/'.$time.'.mp3';
                     }
                }
                if($voice['audio_m4']!=''){
                    
                    $time=time();
                    $path = public_path().'/uploads/video/audio_m/'.$time.'.mp3';
 
                     //file_put_contents($img, file_get_contents($line[4]));
                     $status=$this->download($voice["audio_m4"],$path);
                     if($status=='OK'){
                         $video_voice->audio_m4 = 'uploads/video/audio_m/'.$time.'.mp3';
                     }
                }
                if($voice['audio_m5']!=''){
                    
                    $time=time();
                    $path = public_path().'/uploads/video/audio_m/'.$time.'.mp3';
 
                     //file_put_contents($img, file_get_contents($line[4]));
                     $status=$this->download($voice['audio_m5'],$path);
                     if($status=='OK'){
                         $video_voice->audio_m5 = 'uploads/video/audio_m/'.$time.'.mp3';
                     }
                }
                if($voice['audio_f1']!=''){
                    
                    $time=time();
                    $path = public_path().'/uploads/video/audio_f/'.$time.'.mp3';
 
                     //file_put_contents($img, file_get_contents($line[4]));
                     $status=$this->download($voice['audio_f1'],$path);
                     if($status=='OK'){
                         $video_voice->audio_f1 = 'uploads/video/audio_f/'.$time.'.mp3';
                     }
                }
                if($voice['audio_f2']!=''){
                    
                    $time=time();
                    $path = public_path().'/uploads/video/audio_f/'.$time.'.mp3';
 
                     //file_put_contents($img, file_get_contents($line[4]));
                     $status= $this->download($voice['audio_f2'],$path);
                     if($status=='OK'){
                         $video_voice->audio_f2 = 'uploads/video/audio_f/'.$time.'.mp3';
                     }
                }
                if($voice['audio_f3']!=''){
                    
                    $time=time();
                    $path = public_path().'/uploads/video/audio_f/'.$time.'.mp3';
 
                     //file_put_contents($img, file_get_contents($line[4]));
                     $status=$this->download($voice['audio_f3'],$path);
                     if($status=='OK'){
                         $video_voice->audio_f3 = 'uploads/video/audio_f/'.$time.'.mp3';
                     }
                }
                if($voice['audio_f4']!=''){
                    
                    $time=time();
                    $path = public_path().'/uploads/video/audio_f/'.$time.'.mp3';
 
                     //file_put_contents($img, file_get_contents($line[4]));
                     $status=$this->download($voice['audio_f4'],$path);
                     if($status=='OK'){
                         $video_voice->audio_f4 = 'uploads/video/audio_f/'.$time.'.mp3';
                     }
                }
                if($voice['audio_f5']!=''){
                    
                    $time=time();
                    $path = public_path().'/uploads/video/audio_f/'.$time.'.mp3';
 
                     //file_put_contents($img, file_get_contents($line[4]));
                     $status= $this->download($voice['audio_f5'],$path);
                     if($status=='OK'){
                         $video_voice->audio_f5 = 'uploads/video/audio_f/'.$time.'.mp3';
                     }
                }
                if($voice['audio_m1_long']!=''){
                    
                    $time=time();
                    $path = public_path().'/uploads/video/audio_m/'.$time.'.mp3';
 
                     //file_put_contents($img, file_get_contents($line[4]));
                     $status=$this->download($voice['audio_m1_long'],$path);
                     if($status=='OK'){
                         $video_voice->audio_m1_long = 'uploads/video/audio_m/'.$time.'.mp3';
                     }
                }
                if($voice['audio_m2_long']!=''){
                    
                    $time=time();
                    $path = public_path().'/uploads/video/audio_m/'.$time.'.mp3';
 
                     //file_put_contents($img, file_get_contents($line[4]));
                     $status= $this->download($voice['audio_m2_long'],$path);
                     if($status=='OK'){
                         $video_voice->audio_m2_long = 'uploads/video/audio_m/'.$time.'.mp3';
                     }
                }
                if($voice['audio_m3_long']!=''){
                    
                    $time=time();
                    $path = public_path().'/uploads/video/audio_m/'.$time.'.mp3';
 
                     //file_put_contents($img, file_get_contents($line[4]));
                     $status= $this->download($voice['audio_m3_long'],$path);
                     if($status=='OK'){
                         $video_voice->audio_m3_long = 'uploads/video/audio_m/'.$time.'.mp3';
                     }
                }
                if($voice['audio_m4_long']!=''){
                    
                    $time=time();
                    $path = public_path().'/uploads/video/audio_m/'.$time.'.mp3';
 
                     //file_put_contents($img, file_get_contents($line[4]));
                     $status=$this->download($voice['audio_m4_long'],$path);
                     if($status=='OK'){
                         $video_voice->audio_m4_long = 'uploads/video/audio_m/'.$time.'.mp3';
                     }
                }
                if($voice['audio_m5_long']!=''){
                    
                    $time=time();
                    $path = public_path().'/uploads/video/audio_m/'.$time.'.mp3';
 
                     //file_put_contents($img, file_get_contents($line[4]));
                     $status= $this->download($voice['audio_m5_long'],$path);
                     if($status=='OK'){
                         $video_voice->audio_m5_long = 'uploads/video/audio_m/'.$time.'.mp3';
                     }
                }
                if($voice['audio_f1_long']!=''){
                    
                    $time=time();
                    $path = public_path().'/uploads/video/audio_f/'.$time.'.mp3';
 
                     //file_put_contents($img, file_get_contents($line[4]));
                     $status= $this->download($voice['audio_f1_long'],$path);
                     if($status=='OK'){
                         $video_voice->audio_f1_long = 'uploads/video/audio_f/'.$time.'.mp3';
                     }
                }
                if($voice['audio_f2_long']!=''){
                    
                    $time=time();
                    $path = public_path().'/uploads/video/audio_f/'.$time.'.mp3';
 
                     //file_put_contents($img, file_get_contents($line[4]));
                     $status= $this->download($voice['audio_f2_long'],$path);
                     if($status=='OK'){
                         $video_voice->audio_f2_long = 'uploads/video/audio_f/'.$time.'.mp3';
                     }
                }
                if($voice['audio_f3_long']!=''){
                    
                    $time=time();
                    $path = public_path().'/uploads/video/audio_f/'.$time.'.mp3';
 
                     //file_put_contents($img, file_get_contents($line[4]));
                     $status= $this->download($voice['audio_f3_long'],$path);
                     if($status=='OK'){
                         $video_voice->audio_f3_long = 'uploads/video/audio_f/'.$time.'.mp3';
                     }
                }
                if($voice['audio_f4_long']!=''){
                    
                    $time=time();
                    $path = public_path().'/uploads/video/audio_f/'.$time.'.mp3';
 
                     //file_put_contents($img, file_get_contents($line[4]));
                     $status= $this->download($voice['audio_f4_long'],$path);
                     if($status=='OK'){
                         $video_voice->audio_f4_long = 'uploads/video/audio_f/'.$time.'.mp3';
                     }
                }
                if($voice['audio_f5_long']!=''){
                    
                    $time=time();
                    $path = public_path().'/uploads/video/audio_f/'.$time.'.mp3';
 
                     //file_put_contents($img, file_get_contents($line[4]));
                     $status= $this->download($voice['audio_f5_long'],$path);
                     if($status=='OK'){
                         $video_voice->audio_f5_long = 'uploads/video/audio_f/'.$time.'.mp3';
                     }
                }

                $video_voice->name=$voice->name;
                $video_voice->folder_id=$voice->folder_id;
                $video_voice->save();

                $voice->isupload=1;
                $voice->update();
                // return response()->json([

                //     'success' => true,

                //     'message' => 'Voice Uploaded Successfully',

               
                // ], Response::HTTP_OK);

            //}
        }
        
    }
    
    public function uploadQuestionAnswerVoice()
    {
        $voice=QuestionAnswerVoice::where('isupload','=','0')->first();
      
        if($voice!=null){
         
                $video_voice=new QuestionAnswerVideoVoice();
                if($voice['audio1']!=''){
                    
                    $time=time();
                    $path = public_path().'/uploads/question_answer_video_voice/question/';
                        
                     
                     $status=$this->downloadVoice($voice['audio1'],$path,'audio1_'.$time.'.mp3');
                     if($status=='OK'){
                         $video_voice->audio1 = 'uploads/question_answer_video_voice/question/audio1_'.$time.'.mp3';
                     }
                }
                if($voice['audio2']!=''){
                    
                    $time=time();
                    $path = public_path().'/uploads/question_answer_video_voice/question/';
                        
                     
                     $status=$this->downloadVoice($voice['audio2'],$path,'audio2_'.$time.'.mp3');
                     if($status=='OK'){
                         $video_voice->audio2 = 'uploads/question_answer_video_voice/question/audio2_'.$time.'.mp3';
                     }
                }
                if($voice['audio3']!=''){
                    
                    $time=time();
                    $path = public_path().'/uploads/question_answer_video_voice/question/';
                        
                     
                     $status=$this->downloadVoice($voice['audio3'],$path,'audio3_'.$time.'.mp3');
                     if($status=='OK'){
                         $video_voice->audio3 = 'uploads/question_answer_video_voice/question/audio3_'.$time.'.mp3';
                     }
                }
                if($voice['audio4']!=''){
                    
                    $time=time();
                    $path = public_path().'/uploads/question_answer_video_voice/question/';
                        
                     
                     $status=$this->downloadVoice($voice['audio4'],$path,'audio4_'.$time.'.mp3');
                     if($status=='OK'){
                         $video_voice->audio4 = 'uploads/question_answer_video_voice/question/audio4_'.$time.'.mp3';
                     }
                }
                if($voice['audio5']!=''){
                    
                    $time=time();
                    $path = public_path().'/uploads/question_answer_video_voice/question/';
                        
                     
                     $status=$this->downloadVoice($voice['audio4'],$path,'audio5_'.$time.'.mp3');
                     if($status=='OK'){
                         $video_voice->audio5 = 'uploads/question_answer_video_voice/question/audio5_'.$time.'.mp3';
                     }
                }
                if($voice['audio6']!=''){
                    
                    $time=time();
                    $path = public_path().'/uploads/question_answer_video_voice/question/';
                        
                     
                     $status=$this->downloadVoice($voice['audio6'],$path,'audio6_'.$time.'.mp3');
                     if($status=='OK'){
                         $video_voice->audio6 = 'uploads/question_answer_video_voice/question/audio6_'.$time.'.mp3';
                     }
                }
                if($voice['audio7']!=''){
                    
                    $time=time();
                    $path = public_path().'/uploads/question_answer_video_voice/question/';
                        
                     
                     $status=$this->downloadVoice($voice['audio7'],$path,'audio7_'.$time.'.mp3');
                     if($status=='OK'){
                         $video_voice->audio7 = 'uploads/question_answer_video_voice/question/audio7_'.$time.'.mp3';
                     }
                }
                if($voice['audio8']!=''){
                    
                    $time=time();
                    $path = public_path().'/uploads/question_answer_video_voice/question/';
                        
                     
                     $status=$this->downloadVoice($voice['audio8'],$path,'audio8_'.$time.'.mp3');
                     if($status=='OK'){
                         $video_voice->audio8 = 'uploads/question_answer_video_voice/question/audio8_'.$time.'.mp3';
                     }
                }
                if($voice['audio9']!=''){
                    
                    $time=time();
                    $path = public_path().'/uploads/question_answer_video_voice/question/';
                        
                     
                     $status=$this->downloadVoice($voice['audio9'],$path,'audio9_'.$time.'.mp3');
                     if($status=='OK'){
                         $video_voice->audio9 = 'uploads/question_answer_video_voice/question/audio9_'.$time.'.mp3';
                     }
                }
                if($voice['audio10']!=''){
                    
                    $time=time();
                    $path = public_path().'/uploads/question_answer_video_voice/question/';
                        
                     
                     $status=$this->downloadVoice($voice['audio10'],$path,'audio10_'.$time.'.mp3');
                     if($status=='OK'){
                         $video_voice->audio10 = 'uploads/question_answer_video_voice/question/audio10_'.$time.'.mp3';
                     }
                }
                if($voice['audio11']!=''){
                    
                    $time=time();
                    $path = public_path().'/uploads/question_answer_video_voice/question/';
                        
                     
                     $status=$this->downloadVoice($voice['audio11'],$path,'audio11_'.$time.'.mp3');
                     if($status=='OK'){
                         $video_voice->audio11 = 'uploads/question_answer_video_voice/question/audio11_'.$time.'.mp3';
                     }
                }
                if($voice['audio12']!=''){
                    
                    $time=time();
                    $path = public_path().'/uploads/question_answer_video_voice/question/';
                        
                     
                     $status=$this->downloadVoice($voice['audio12'],$path,'audio12_'.$time.'.mp3');
                     if($status=='OK'){
                         $video_voice->audio12 = 'uploads/question_answer_video_voice/question/audio12_'.$time.'.mp3';
                     }
                }
                if($voice['audio13']!=''){
                    
                    $time=time();
                    $path = public_path().'/uploads/question_answer_video_voice/question/';
                        
                     
                     $status=$this->downloadVoice($voice['audio13'],$path,'audio13_'.$time.'.mp3');
                     if($status=='OK'){
                         $video_voice->audio13 = 'uploads/question_answer_video_voice/question/audio13_'.$time.'.mp3';
                     }
                }
                if($voice['audio14']!=''){
                    
                    $time=time();
                    $path = public_path().'/uploads/question_answer_video_voice/question/';
                        
                     
                     $status=$this->downloadVoice($voice['audio14'],$path,'audio14_'.$time.'.mp3');
                     if($status=='OK'){
                         $video_voice->audio14 = 'uploads/question_answer_video_voice/question/audio14_'.$time.'.mp3';
                     }
                }
                if($voice['audio15']!=''){
                    
                    $time=time();
                    $path = public_path().'/uploads/question_answer_video_voice/question/';
                        
                     
                     $status=$this->downloadVoice($voice['audio15'],$path,'audio15_'.$time.'.mp3');
                     if($status=='OK'){
                         $video_voice->audio15 = 'uploads/question_answer_video_voice/question/audio15_'.$time.'.mp3';
                     }
                }
                if($voice['audio16']!=''){
                    
                    $time=time();
                    $path = public_path().'/uploads/question_answer_video_voice/question/';
                        
                     
                     $status=$this->downloadVoice($voice['audio16'],$path,'audio16_'.$time.'.mp3');
                     if($status=='OK'){
                         $video_voice->audio16 = 'uploads/question_answer_video_voice/question/audio16_'.$time.'.mp3';
                     }
                }
                if($voice['audio17']!=''){
                    
                    $time=time();
                    $path = public_path().'/uploads/question_answer_video_voice/question/';
                        
                     
                     $status=$this->downloadVoice($voice['audio17'],$path,'audio17_'.$time.'.mp3');
                     if($status=='OK'){
                         $video_voice->audio17 = 'uploads/question_answer_video_voice/question/audio17_'.$time.'.mp3';
                     }
                }
                if($voice['audio18']!=''){
                    
                    $time=time();
                    $path = public_path().'/uploads/question_answer_video_voice/question/';
                        
                     
                     $status=$this->downloadVoice($voice['audio18'],$path,'audio18_'.$time.'.mp3');
                     if($status=='OK'){
                         $video_voice->audio18 = 'uploads/question_answer_video_voice/question/audio18_'.$time.'.mp3';
                     }
                }
                if($voice['audio19']!=''){
                    
                    $time=time();
                    $path = public_path().'/uploads/question_answer_video_voice/question/';
                        
                     
                     $status=$this->downloadVoice($voice['audio19'],$path,'audio19_'.$time.'.mp3');
                     if($status=='OK'){
                         $video_voice->audio19 = 'uploads/question_answer_video_voice/question/audio19_'.$time.'.mp3';
                     }
                }
                if($voice['audio20']!=''){
                    
                    $time=time();
                    $path = public_path().'/uploads/question_answer_video_voice/question/';
                        
                     
                     $status=$this->downloadVoice($voice['audio20'],$path,'audio20_'.$time.'.mp3');
                     if($status=='OK'){
                         $video_voice->audio20 = 'uploads/question_answer_video_voice/question/audio20_'.$time.'.mp3';
                     }
                }
                
                 if($voice['answer_audio1']!=''){
                    
                    $time=time();
                    $path = public_path().'/uploads/question_answer_video_voice/answer/';
                        
                     
                     $status=$this->downloadVoice($voice['answer_audio1'],$path,'answer_audio1_'.$time.'.mp3');
                     if($status=='OK'){
                         $video_voice->answer_audio1 = 'uploads/question_answer_video_voice/answer/answer_audio1_'.$time.'.mp3';
                     }
                }
                if($voice['answer_audio2']!=''){
                    
                    $time=time();
                    $path = public_path().'/uploads/question_answer_video_voice/answer/';
                        
                     
                     $status=$this->downloadVoice($voice['answer_audio2'],$path,'answer_audio2_'.$time.'.mp3');
                     if($status=='OK'){
                         $video_voice->answer_audio2 = 'uploads/question_answer_video_voice/answer/answer_audio2_'.$time.'.mp3';
                     }
                }
                if($voice['answer_audio3']!=''){
                    
                    $time=time();
                    $path = public_path().'/uploads/question_answer_video_voice/answer/';
                        
                     
                     $status=$this->downloadVoice($voice['answer_audio3'],$path,'answer_audio3_'.$time.'.mp3');
                     if($status=='OK'){
                         $video_voice->answer_audio3 = 'uploads/question_answer_video_voice/answer/answer_audio3_'.$time.'.mp3';
                     }
                }
                if($voice['answer_audio4']!=''){
                    
                    $time=time();
                    $path = public_path().'/uploads/question_answer_video_voice/answer/';
                        
                     
                     $status=$this->downloadVoice($voice['answer_audio4'],$path,'answer_audio4_'.$time.'.mp3');
                     if($status=='OK'){
                         $video_voice->answer_audio4 = 'uploads/question_answer_video_voice/answer/answer_audio4_'.$time.'.mp3';
                     }
                }
                if($voice['answer_audio5']!=''){
                    
                    $time=time();
                    $path = public_path().'/uploads/question_answer_video_voice/answer/';
                        
                     
                     $status=$this->downloadVoice($voice['answer_audio4'],$path,'answer_audio5_'.$time.'.mp3');
                     if($status=='OK'){
                         $video_voice->answer_audio5 = 'uploads/question_answer_video_voice/answer/answer_audio5_'.$time.'.mp3';
                     }
                }
                if($voice['answer_audio6']!=''){
                    
                    $time=time();
                    $path = public_path().'/uploads/question_answer_video_voice/answer/';
                        
                     
                     $status=$this->downloadVoice($voice['answer_audio6'],$path,'answer_audio6_'.$time.'.mp3');
                     if($status=='OK'){
                         $video_voice->answer_audio6 = 'uploads/question_answer_video_voice/answer/answer_audio6_'.$time.'.mp3';
                     }
                }
                if($voice['answer_audio7']!=''){
                    
                    $time=time();
                    $path = public_path().'/uploads/question_answer_video_voice/answer/';
                        
                     
                     $status=$this->downloadVoice($voice['answer_audio7'],$path,'answer_audio7_'.$time.'.mp3');
                     if($status=='OK'){
                         $video_voice->answer_audio7 = 'uploads/question_answer_video_voice/answer/answer_audio7_'.$time.'.mp3';
                     }
                }
                if($voice['answer_audio8']!=''){
                    
                    $time=time();
                    $path = public_path().'/uploads/question_answer_video_voice/answer/';
                        
                     
                     $status=$this->downloadVoice($voice['answer_audio8'],$path,'answer_audio8_'.$time.'.mp3');
                     if($status=='OK'){
                         $video_voice->answer_audio8 = 'uploads/question_answer_video_voice/answer/answer_audio8_'.$time.'.mp3';
                     }
                }
                if($voice['answer_audio9']!=''){
                    
                    $time=time();
                    $path = public_path().'/uploads/question_answer_video_voice/answer/';
                        
                     
                     $status=$this->downloadVoice($voice['answer_audio9'],$path,'answer_audio9_'.$time.'.mp3');
                     if($status=='OK'){
                         $video_voice->answer_audio9 = 'uploads/question_answer_video_voice/answer/answer_audio9_'.$time.'.mp3';
                     }
                }
                if($voice['answer_audio10']!=''){
                    
                    $time=time();
                    $path = public_path().'/uploads/question_answer_video_voice/answer/';
                        
                     
                     $status=$this->downloadVoice($voice['answer_audio10'],$path,'answer_audio10_'.$time.'.mp3');
                     if($status=='OK'){
                         $video_voice->answer_audio10 = 'uploads/question_answer_video_voice/answer/answer_audio10_'.$time.'.mp3';
                     }
                }
                if($voice['answer_audio11']!=''){
                    
                    $time=time();
                    $path = public_path().'/uploads/question_answer_video_voice/answer/';
                        
                     
                     $status=$this->downloadVoice($voice['answer_audio11'],$path,'answer_audio11_'.$time.'.mp3');
                     if($status=='OK'){
                         $video_voice->answer_audio11 = 'uploads/question_answer_video_voice/answer/answer_audio11_'.$time.'.mp3';
                     }
                }
                if($voice['answer_audio12']!=''){
                    
                    $time=time();
                    $path = public_path().'/uploads/question_answer_video_voice/answer/';
                        
                     
                     $status=$this->downloadVoice($voice['answer_audio12'],$path,'answer_audio12_'.$time.'.mp3');
                     if($status=='OK'){
                         $video_voice->answer_audio12 = 'uploads/question_answer_video_voice/answer/answer_audio12_'.$time.'.mp3';
                     }
                }
                if($voice['answer_audio13']!=''){
                    
                    $time=time();
                    $path = public_path().'/uploads/question_answer_video_voice/answer/';
                        
                     
                     $status=$this->downloadVoice($voice['answer_audio13'],$path,'answer_audio13_'.$time.'.mp3');
                     if($status=='OK'){
                         $video_voice->answer_audio13 = 'uploads/question_answer_video_voice/answer/answer_audio13_'.$time.'.mp3';
                     }
                }
                if($voice['answer_audio14']!=''){
                    
                    $time=time();
                    $path = public_path().'/uploads/question_answer_video_voice/answer/';
                        
                     
                     $status=$this->downloadVoice($voice['audio14'],$path,'answer_audio14_'.$time.'.mp3');
                     if($status=='OK'){
                         $video_voice->answer_audio14 = 'uploads/question_answer_video_voice/answer/answer_audio14_'.$time.'.mp3';
                     }
                }
                if($voice['answer_audio15']!=''){
                    
                    $time=time();
                    $path = public_path().'/uploads/question_answer_video_voice/answer/';
                        
                     
                     $status=$this->downloadVoice($voice['answer_audio15'],$path,'answer_audio15_'.$time.'.mp3');
                     if($status=='OK'){
                         $video_voice->answer_audio15 = 'uploads/question_answer_video_voice/answer/answer_audio15_'.$time.'.mp3';
                     }
                }
                if($voice['answer_audio16']!=''){
                    
                    $time=time();
                    $path = public_path().'/uploads/question_answer_video_voice/answer/';
                        
                     
                     $status=$this->downloadVoice($voice['answer_audio16'],$path,'answer_audio16_'.$time.'.mp3');
                     if($status=='OK'){
                         $video_voice->answer_audio16 = 'uploads/question_answer_video_voice/answer/answer_audio16_'.$time.'.mp3';
                     }
                }
                if($voice['answer_audio17']!=''){
                    
                    $time=time();
                    $path = public_path().'/uploads/question_answer_video_voice/answer/';
                        
                     
                     $status=$this->downloadVoice($voice['answer_audio17'],$path,'answer_audio17_'.$time.'.mp3');
                     if($status=='OK'){
                         $video_voice->answer_audio17 = 'uploads/question_answer_video_voice/answer/answer_audio17_'.$time.'.mp3';
                     }
                }
                if($voice['answer_audio18']!=''){
                    
                    $time=time();
                    $path = public_path().'/uploads/question_answer_video_voice/answer/';
                        
                     
                     $status=$this->downloadVoice($voice['answer_audio18'],$path,'answer_audio18_'.$time.'.mp3');
                     if($status=='OK'){
                         $video_voice->answer_audio18 = 'uploads/question_answer_video_voice/answer/answer_audio18_'.$time.'.mp3';
                     }
                }
                if($voice['answer_audio19']!=''){
                    
                    $time=time();
                    $path = public_path().'/uploads/question_answer_video_voice/answer/';
                        
                     
                     $status=$this->downloadVoice($voice['answer_audio19'],$path,'audio19_'.$time.'.mp3');
                     if($status=='OK'){
                         $video_voice->answer_audio19 = 'uploads/question_answer_video_voice/answer/answer_audio19_'.$time.'.mp3';
                     }
                }
                if($voice['answer_audio20']!=''){
                    
                    $time=time();
                    $path = public_path().'/uploads/question_answer_video_voice/answer/';
                        
                     
                     $status=$this->downloadVoice($voice['answer_audio20'],$path,'answer_audio20_'.$time.'.mp3');
                     if($status=='OK'){
                         $video_voice->answer_audio20 = 'uploads/question_answer_video_voice/answer/answer_audio20_'.$time.'.mp3';
                     }
                }
              
              
                $video_voice->name=$voice->name;
                $video_voice->folder_id=$voice->folder_id;
                $video_voice->save();

                $voice->isupload=1;
                $voice->update();
               
        }
        
    }
    public function download($source,$destination){
     
        $curl = curl_init();

        curl_setopt_array($curl, array(
        CURLOPT_URL => $source,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 60,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'GET',
        ));

        $response = curl_exec($curl);
       // echo $destination;
        $fh = fopen($destination, "w") or die("ERROR opening " . $destination);

        file_put_contents($destination, $response);
        curl_close($curl);
       
        if(file_exists($destination)) { 
            $status="OK";
        }else{
            $status= "ERROR -";
        }
        return $status;

    }

    public function downloadVoice($source,$destination,$voicepath){
     
        $curl = curl_init();

        curl_setopt_array($curl, array(
        CURLOPT_URL => $source,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 60,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'GET',
        ));

        $response = curl_exec($curl);
       // echo $destination;
       
        if( !is_dir( $destination ) ) mkdir( $destination, 0755, true );
        
        $fh = fopen($destination.$voicepath, "w") or die("ERROR opening " . $destination.$voicepath);

        file_put_contents($destination.$voicepath, $response);
        curl_close($curl);
       
        if(file_exists($destination.$voicepath)) { 
            $status="OK";
        }else{
            $status= "ERROR -";
        }
        return $status;

    }
}

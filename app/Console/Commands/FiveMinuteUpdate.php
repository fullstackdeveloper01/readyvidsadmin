<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Http\Request;
use App\VideoVoice;
use App\Voice;
use App\Folders;
use App\Exports\ImagesExport;
use Maatwebsite\Excel\Facades\Excel;
class FiveMinuteUpdate extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'fiveminute:update';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Upload voice';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
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
                return response()->json([

                    'success' => true,

                    'message' => 'Voice Uploaded Successfully',

               
                ], Response::HTTP_OK);

            //}
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

}

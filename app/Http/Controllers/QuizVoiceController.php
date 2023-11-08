<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\QuizVoice;
use App\Country;
class QuizVoiceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

   
    public function index()
    {
        $quiz_voice = QuizVoice::select("quiz_voice.*","country.country_name")
                    ->join("country","quiz_voice.country_id","=","country.id")
                    ->where("quiz_voice.deleted_at",'=','0')->paginate(15);
        return view('quiz_voice.index', ['quiz_voice' =>$quiz_voice]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $countryList = Country::where('active','=','1')->get();
        return view('quiz_voice.create',['countryList'=>$countryList]);
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
            'country_id' => ['required'],
            'speaker_profile_picture' => ['required'],
            'voice_text' => ['required'],
            'voice_sample' => ['required']
          
        ]);
        
        
        $quiz_voice = new QuizVoice;
       
        $quiz_voice->country_id = $request->country_id;
         
        if ($request->hasFile('speaker_profile_picture')) {
           
            $extension = $request->file('speaker_profile_picture')->getClientOriginalExtension();
            // Filename To store
            $fileNameToStore = time().'.'.$extension;

            $request->speaker_profile_picture->move(public_path('uploads/speaker_profile_picture/'), $fileNameToStore);
            $quiz_voice->speaker_profile_picture = 'uploads/speaker_profile_picture/'.$fileNameToStore;
        }
        
        
        $quiz_voice->voice_text = $request->voice_text;
        
        if ($request->hasFile('voice_sample')) {
           
            $extension = $request->file('voice_sample')->getClientOriginalExtension();
            // Filename To store
            $fileNameToStore = time().'.'.$extension;

            $request->voice_sample->move(public_path('uploads/voice/voice_sample/'), $fileNameToStore);
            $quiz_voice->voice_sample = 'uploads/voice/voice_sample/'.$fileNameToStore;
        }
        
        $voice = QuizVoice::where('country_id','=',$request->country_id)->orderBy('id', 'DESC')->first();
        
        if($voice!=null){
            $slug= $voice->slug;
          
            $slug= preg_replace("/[^0-9]/", '', $slug);
            $voice_slug = "audio".++$slug;
             $quiz_voice->slug =  $voice_slug;
        }else{
            $voice_slug = "audio1";
            $quiz_voice->slug =  $voice_slug;
        }
        
  
        $quiz_voice->save();
        return redirect()->route('quiz_voice.index')->withStatus(__('Quiz voice successfully created.'));
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
    public function edit(QuizVoice $quiz_voice)
    {
        $countryList = Country::where('active','=','1')->get();
        return view('quiz_voice.edit', ['quiz_voice'=>$quiz_voice,'countryList'=>$countryList]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request,QuizVoice $quiz_voice)
    {   
        $request->validate([
            'country_id' => ['required'],
          
            'voice_text' => ['required'],
            
        ]);
        
        
        
       
        $quiz_voice->country_id = $request->country_id;
         
        if ($request->hasFile('speaker_profile_picture')) {
            
            if($quiz_voice->speaker_profile_picture != ''){
                $path = public_path().'/'.$quiz_voice->speaker_profile_picture;
                if(file_exists($path)){
                    unlink($path);  
                }                
            }
            $extension = $request->file('speaker_profile_picture')->getClientOriginalExtension();
            // Filename To store
            $fileNameToStore = time().'.'.$extension;

            $request->speaker_profile_picture->move(public_path('uploads/speaker_profile_picture/'), $fileNameToStore);
            $quiz_voice->speaker_profile_picture = 'uploads/speaker_profile_picture/'.$fileNameToStore;
        }
        
        
        $quiz_voice->voice_text = $request->voice_text;
        
        if ($request->hasFile('voice_sample')) {
           
            if($quiz_voice->voice_sample != ''){
                $path = public_path().'/'.$quiz_voice->voice_sample;
                if(file_exists($path)){
                    unlink($path);  
                }                
            }
            $extension = $request->file('voice_sample')->getClientOriginalExtension();
            // Filename To store
            $fileNameToStore = time().'.'.$extension;

            $request->voice_sample->move(public_path('uploads/voice/voice_sample/'), $fileNameToStore);
            $quiz_voice->voice_sample = 'uploads/voice/voice_sample/'.$fileNameToStore;
        }
        
        
        $quiz_voice->update();
        
        return redirect()->route('quiz_voice.index')->withStatus(__('Language successfully updated.'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(QuizVoice $quiz_voice)
    {
        $quiz_voice->deleted_at=1;
         $quiz_voice->status=0; 
         $quiz_voice->update();
          return redirect()->route('quiz_voice.index')->withStatus(__('Voice successfully deleted.'));
        // $affectedRows = quiz_voice::where('parent_id', '=', $language->id)->delete();
        // if($affectedRows==1){
        //   $language->delete();
        //     return redirect()->route('quiz_voice.index')->withStatus(__('Primary language successfully deleted.'));
        // }else{
        //     $language->delete();
        //     return redirect()->route('quiz_voice.index')->withStatus(__('Primary language successfully deleted.'));
        // }
        
    }
    
    public function status($id,$status)
    {  
        $quiz_voice = quiz_voice::findorfail($id);
        if( $quiz_voice->status==1){
            $quiz_voice->status=0;
             quiz_voice::where('parent_id', '=', $id)->update(['status'=>'0']);
        }else{
            $quiz_voice->status=1;
             quiz_voice::where('parent_id', '=', $id)->update(['status'=>'1']);
        }
        //$quiz_voice->status=$status;
        $quiz_voice->update();

       // quiz_voice::where('parent_id', '=', $id)->update(['status'=>$status]);
        echo true;
        
    }
    public function primaryLanguageList(){
        $data= quiz_voice::where(['parent_id'=>0])->where(['status'=>1])->get();
        
        return response()->json([
            'data' =>$data,
            'status' => true,
            'errMsg' => ''
        ]);
    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Languages;

class PrimaryLanguageController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

   
    public function index()
    {
        return view('languages.index', ['languages' =>Languages::where(['parent_id'=>0])->paginate(15)]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
       return view('languages.create',['title'=>'Add Primary Language']);
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
          
            'icon' => ['required'],
            
            'voice_text_m1' => ['required'],
            'audio_m' => ['required'],
            'voice_upload1' => ['required'],
            
            'voice_text_m2' => ['required'],
            'audio_m1' => ['required'],
            'voice_upload2' => ['required'],
            
            'voice_text_m3' => ['required'],
            'audio_m2' => ['required'],
            'voice_upload3' => ['required'],
            
            'voice_text_m4' => ['required'],
            'audio_m3' => ['required'],
            'voice_upload4' => ['required'],
            
            'voice_text_m5' => ['required'],
            'audio_m4' => ['required'],
            'voice_upload5' => ['required'],
            
           'voice_text_m6' => ['required'],
            'audio_f' => ['required'],
            'voice_upload6' => ['required'],
            
            'voice_text_m7' => ['required'],
            'audio_f1' => ['required'],
            'voice_upload7' => ['required'],
            
            'voice_text_m8' => ['required'],
            'audio_f2' => ['required'],
            'voice_upload8' => ['required'],
            
            'voice_text_m9' => ['required'],
            'audio_f3' => ['required'],
            'voice_upload9' => ['required'],
            
            'voice_text_m10' => ['required'],
            'audio_f4' => ['required'],
            'voice_upload10' => ['required'],
        ]);
    
        $languages = new Languages;
        $languages->name = strip_tags($request->name);
        $languages->parent_id = 0;
        $languages->description = $request->description;
         
        if ($request->hasFile('icon')) {
           
            $extension = $request->file('icon')->getClientOriginalExtension();
            // Filename To store
            $fileNameToStore = time().'.'.$extension;

            $request->icon->move(public_path('uploads/language_icon/'), $fileNameToStore);
            $languages->icon = 'uploads/language_icon/'.$fileNameToStore;
        }
        
        
        $languages->voice_text_m1 = $request->voice_text_m1;
        
        if ($request->hasFile('audio_m')) {
           
            $extension = $request->file('audio_m')->getClientOriginalExtension();
            // Filename To store
            $fileImageNameToStore1 = 'audio1_'.time().'.'.$extension;

            $request->audio_m->move(public_path('uploads/voice/profile_pic/'), $fileImageNameToStore1);
            $languages->audio_m = 'uploads/voice/profile_pic/'.$fileImageNameToStore1;
        }
        
        if ($request->hasFile('voice_upload1')) {
           
            $extension = $request->file('voice_upload1')->getClientOriginalExtension();
            // Filename To store
            $fileVoiceNameToStore1 = 'voice1_'.time().'.'.$extension;

            $request->voice_upload1->move(public_path('uploads/voice/sample/'), $fileVoiceNameToStore1);
            $languages->voice_upload1 = 'uploads/voice/sample/'.$fileVoiceNameToStore1;
        }
        
        $languages->voice_text_m2 = $request->voice_text_m2;
        
        if ($request->hasFile('audio_m1')) {
           
            $extension = $request->file('audio_m1')->getClientOriginalExtension();
            // Filename To store
            $fileImageNameToStore2 = 'audio2_'.time().'.'.$extension;

            $request->audio_m1->move(public_path('uploads/voice/profile_pic/'), $fileImageNameToStore2);
            $languages->audio_m1 = 'uploads/voice/profile_pic/'.$fileImageNameToStore2;
        }
        
        if ($request->hasFile('voice_upload2')) {
           
            $extension = $request->file('voice_upload2')->getClientOriginalExtension();
            // Filename To store
            $fileVoiceNameToStore2 = 'voice2_'.time().'.'.$extension;

            $request->voice_upload2->move(public_path('uploads/voice/sample/'), $fileVoiceNameToStore2);
            $languages->voice_upload2 = 'uploads/voice/sample/'.$fileVoiceNameToStore2;
        }
        
        $languages->voice_text_m3 = $request->voice_text_m3;
        
        if ($request->hasFile('audio_m2')) {
           
            $extension = $request->file('audio_m2')->getClientOriginalExtension();
            // Filename To store
            $fileImageNameToStore3 = 'audio3_'.time().'.'.$extension;

            $request->audio_m2->move(public_path('uploads/voice/profile_pic/'), $fileImageNameToStore3);
            $languages->audio_m2 = 'uploads/voice/profile_pic/'.$fileImageNameToStore3;
        }
        
        if ($request->hasFile('voice_upload3')) {
           
            $extension = $request->file('voice_upload3')->getClientOriginalExtension();
            // Filename To store
            $fileVoiceNameToStore3 = 'voice3_'.time().'.'.$extension;

            $request->voice_upload3->move(public_path('uploads/voice/sample/'), $fileVoiceNameToStore3);
            $languages->voice_upload3 = 'uploads/voice/sample/'.$fileVoiceNameToStore3;
        }
        
        $languages->voice_text_m4 = $request->voice_text_m4;
        
        if ($request->hasFile('audio_m3')) {
           
            $extension = $request->file('audio_m3')->getClientOriginalExtension();
            // Filename To store
            $fileImageNameToStore4 = 'audio4_'.time().'.'.$extension;

            $request->audio_m3->move(public_path('uploads/voice/profile_pic/'), $fileImageNameToStore4);
            $languages->audio_m3 = 'uploads/voice/profile_pic/'.$fileImageNameToStore4;
        }
        
        if ($request->hasFile('voice_upload4')) {
           
            $extension = $request->file('voice_upload4')->getClientOriginalExtension();
            // Filename To store
            $fileVoiceNameToStore4 = 'voice4_'.time().'.'.$extension;

            $request->voice_upload4->move(public_path('uploads/voice/sample/'), $fileVoiceNameToStore4);
            $languages->voice_upload4 = 'uploads/voice/sample/'.$fileVoiceNameToStore4;
        }
        
        $languages->voice_text_m5 = $request->voice_text_m5;
        
        if ($request->hasFile('audio_m4')) {
           
            $extension = $request->file('audio_m4')->getClientOriginalExtension();
            // Filename To store
            $fileImageNameToStore5 = 'audio5_'.time().'.'.$extension;

            $request->audio_m4->move(public_path('uploads/voice/profile_pic/'), $fileImageNameToStore5);
            $languages->audio_m4 = 'uploads/voice/profile_pic/'.$fileImageNameToStore5;
        }
        
        if ($request->hasFile('voice_upload5')) {
           
            $extension = $request->file('voice_upload5')->getClientOriginalExtension();
            // Filename To store
            $fileVoiceNameToStore5 = 'voice5_'.time().'.'.$extension;

            $request->voice_upload5->move(public_path('uploads/voice/sample/'), $fileVoiceNameToStore5);
            $languages->voice_upload5 = 'uploads/voice/sample/'.$fileVoiceNameToStore5;
        }
        
       
         $languages->voice_text_f1 = $request->voice_text_m6;
        
        if ($request->hasFile('audio_f')) {
           
            $extension = $request->file('audio_f')->getClientOriginalExtension();
            // Filename To store
            $fileImageNameToStore6 = 'audio6_'.time().'.'.$extension;

            $request->audio_f->move(public_path('uploads/voice/profile_pic/'), $fileImageNameToStore6);
            $languages->audio_f = 'uploads/voice/profile_pic/'.$fileImageNameToStore6;
        }
        
        if ($request->hasFile('voice_upload6')) {
           
            $extension = $request->file('voice_upload6')->getClientOriginalExtension();
            // Filename To store
            $fileVoiceNameToStore6 = 'voice6_'.time().'.'.$extension;

            $request->voice_upload6->move(public_path('uploads/voice/sample/'), $fileVoiceNameToStore6);
            $languages->voice_upload6 = 'uploads/voice/sample/'.$fileVoiceNameToStore6;
        }
       
        $languages->voice_text_f2 = $request->voice_text_m7;
        
        if ($request->hasFile('audio_f1')) {
           
            $extension = $request->file('audio_f1')->getClientOriginalExtension();
            // Filename To store
            $fileImageNameToStore7 = 'audio7_'.time().'.'.$extension;

            $request->audio_f1->move(public_path('uploads/voice/profile_pic/'), $fileImageNameToStore7);
            $languages->audio_f1 = 'uploads/voice/profile_pic/'.$fileImageNameToStore7;
        }
        
        if ($request->hasFile('voice_upload7')) {
           
            $extension = $request->file('voice_upload7')->getClientOriginalExtension();
            // Filename To store
            $fileVoiceNameToStore7 = 'voice7_'.time().'.'.$extension;

            $request->voice_upload7->move(public_path('uploads/voice/sample/'), $fileVoiceNameToStore7);
            $languages->voice_upload7 = 'uploads/voice/sample/'.$fileVoiceNameToStore7;
        }
       
        $languages->voice_text_f3 = $request->voice_text_m8;
        
        if ($request->hasFile('audio_f2')) {
           
            $extension = $request->file('audio_f2')->getClientOriginalExtension();
            // Filename To store
            $fileImageNameToStore8 = 'audio8_'.time().'.'.$extension;

            $request->audio_f2->move(public_path('uploads/voice/profile_pic/'), $fileImageNameToStore8);
            $languages->audio_f2 = 'uploads/voice/profile_pic/'.$fileImageNameToStore8;
        }
        
        if ($request->hasFile('voice_upload8')) {
           
            $extension = $request->file('voice_upload8')->getClientOriginalExtension();
            // Filename To store
            $fileVoiceNameToStore8 = 'voice8_'.time().'.'.$extension;

            $request->voice_upload8->move(public_path('uploads/voice/sample/'), $fileVoiceNameToStore8);
            $languages->voice_upload8 = 'uploads/voice/sample/'.$fileVoiceNameToStore8;
        }
        
        $languages->voice_text_f4 = $request->voice_text_m9;
        
        if ($request->hasFile('audio_f3')) {
           
            $extension = $request->file('audio_f3')->getClientOriginalExtension();
            // Filename To store
            $fileImageNameToStore9 = 'audio9_'.time().'.'.$extension;

            $request->audio_f3->move(public_path('uploads/voice/profile_pic/'), $fileImageNameToStore9);
            $languages->audio_f3 = 'uploads/voice/profile_pic/'.$fileImageNameToStore9;
        }
        
        if ($request->hasFile('voice_upload9')) {
           
            $extension = $request->file('voice_upload9')->getClientOriginalExtension();
            // Filename To store
            $fileVoiceNameToStore9 = 'voice9_'.time().'.'.$extension;

            $request->voice_upload9->move(public_path('uploads/voice/sample/'), $fileVoiceNameToStore9);
            $languages->voice_upload9 = 'uploads/voice/sample/'.$fileVoiceNameToStore9;
        }
        
        $languages->voice_text_f5 = $request->voice_text_m10;
        
        if ($request->hasFile('audio_f4')) {
           
            $extension = $request->file('audio_f4')->getClientOriginalExtension();
            // Filename To store
            $fileImageNameToStore10 = 'audio10_'.time().'.'.$extension;

            $request->audio_f4->move(public_path('uploads/voice/profile_pic/'), $fileImageNameToStore10);
            $languages->audio_f4 = 'uploads/voice/profile_pic/'.$fileImageNameToStore10;
        }
        
        if ($request->hasFile('voice_upload10')) {
           
            $extension = $request->file('voice_upload10')->getClientOriginalExtension();
            // Filename To store
            $fileVoiceNameToStore10 = 'voice10_'.time().'.'.$extension;

            $request->voice_upload10->move(public_path('uploads/voice/sample/'), $fileVoiceNameToStore10);
            $languages->voice_upload10 = 'uploads/voice/sample/'.$fileVoiceNameToStore10;
        }

        $languages->save();
        return redirect()->route('languages.index')->withStatus(__('Language successfully created.'));
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
        return view('languages.edit', ['language' =>Languages::where(['id'=>$id])->first()]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request,Languages $language)
    {   
        $request->validate([
            'name' => 'required', 'string', 'max:255','unique:languages'.$language->id,
            
            'voice_text_m1' => ['required'],
           
            'voice_text_m2' => ['required'],
            
            'voice_text_m3' => ['required'],
         
            'voice_text_m4' => ['required'],
            
            'voice_text_m5' => ['required'],
           
            'voice_text_m6' => ['required'],
            
            'voice_text_m7' => ['required'],
            
            'voice_text_m8' => ['required'],
           
            'voice_text_m9' => ['required'],
            
            'voice_text_m10' => ['required'],
            
        ]);
      
      
        $language->name = strip_tags($request->name);
        
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
        
        
        $language->voice_text_m1 = $request->voice_text_m1;
        
        if ($request->hasFile('audio_m')) {
            
            if($language->audio_m != ''){
                $path = public_path().'/uploads/voice/profile_pic/'.$language->audio_m;
                if(file_exists($path)){
                    unlink($path);  
                }                
            }
            $extension = $request->file('audio_m')->getClientOriginalExtension();
            // Filename To store
            $fileImageNameToStore1 = 'audio1_'.time().'.'.$extension;

            $request->audio_m->move(public_path('uploads/voice/profile_pic/'), $fileImageNameToStore1);
            $language->audio_m = 'uploads/voice/profile_pic/'.$fileImageNameToStore1;
        }
        
        if ($request->hasFile('voice_upload1')) {
            
            if($language->voice_upload1 != ''){
                $path = public_path().'/uploads/voice/sample/'.$language->voice_upload1;
                if(file_exists($path)){
                    unlink($path);  
                }                
            }
            
            $extension = $request->file('voice_upload1')->getClientOriginalExtension();
            // Filename To store
            $fileVoiceNameToStore1 = 'voice1_'.time().'.'.$extension;

            $request->voice_upload1->move(public_path('uploads/voice/sample/'), $fileVoiceNameToStore1);
            $language->voice_upload1 = 'uploads/voice/sample/'.$fileVoiceNameToStore1;
        }
        
        $language->voice_text_m2 = $request->voice_text_m2;
        
        if ($request->hasFile('audio_m1')) {
            
            if($language->audio_m1 != ''){
                $path = public_path().'/uploads/voice/profile_pic/'.$language->audio_m1;
                if(file_exists($path)){
                    unlink($path);  
                }                
            }
            $extension = $request->file('audio_m1')->getClientOriginalExtension();
            // Filename To store
            $fileImageNameToStore2 = 'audio2_'.time().'.'.$extension;

            $request->audio_m1->move(public_path('uploads/voice/profile_pic/'), $fileImageNameToStore2);
            $language->audio_m1 = 'uploads/voice/profile_pic/'.$fileImageNameToStore2;
        }
        
        if ($request->hasFile('voice_upload2')) {
            
            if($language->voice_upload2 != ''){
                $path = public_path().'/uploads/voice/sample/'.$language->voice_upload2;
                if(file_exists($path)){
                    unlink($path);  
                }                
            }
            
            $extension = $request->file('voice_upload2')->getClientOriginalExtension();
            // Filename To store
            $fileVoiceNameToStore2 = 'voice2_'.time().'.'.$extension;

            $request->voice_upload2->move(public_path('uploads/voice/sample/'), $fileVoiceNameToStore2);
            $language->voice_upload2 = 'uploads/voice/sample/'.$fileVoiceNameToStore2;
        }
        
        $language->voice_text_m3 = $request->voice_text_m3;
        
        if ($request->hasFile('audio_m2')) {
            
            if($language->audio_m2 != ''){
                $path = public_path().'/uploads/voice/profile_pic/'.$language->audio_m2;
                if(file_exists($path)){
                    unlink($path);  
                }                
            }
            $extension = $request->file('audio_m2')->getClientOriginalExtension();
            // Filename To store
            $fileImageNameToStore3 = 'audio3_'.time().'.'.$extension;

            $request->audio_m2->move(public_path('uploads/voice/profile_pic/'), $fileImageNameToStore3);
            $language->audio_m2 = 'uploads/voice/profile_pic/'.$fileImageNameToStore3;
        }
        
        if ($request->hasFile('voice_upload3')) {
            
            if($language->voice_upload3 != ''){
                $path = public_path().'/uploads/voice/sample/'.$language->voice_upload3;
                if(file_exists($path)){
                    unlink($path);  
                }                
            }
            
            $extension = $request->file('voice_upload3')->getClientOriginalExtension();
            // Filename To store
            $fileVoiceNameToStore3 = 'voice3_'.time().'.'.$extension;

            $request->voice_upload3->move(public_path('uploads/voice/sample/'), $fileVoiceNameToStore3);
            $language->voice_upload3 = 'uploads/voice/sample/'.$fileVoiceNameToStore3;
        }
        
        $language->voice_text_m4 = $request->voice_text_m4;
        
        if ($request->hasFile('audio_m3')) {
            
            if($language->audio_m3 != ''){
                $path = public_path().'/uploads/voice/profile_pic/'.$language->audio_m3;
                if(file_exists($path)){
                    unlink($path);  
                }                
            }
            $extension = $request->file('audio_m3')->getClientOriginalExtension();
            // Filename To store
            $fileImageNameToStore4 = 'audio4_'.time().'.'.$extension;

            $request->audio_m3->move(public_path('uploads/voice/profile_pic/'), $fileImageNameToStore4);
            $language->audio_m3 = 'uploads/voice/profile_pic/'.$fileImageNameToStore4;
        }
        
        if ($request->hasFile('voice_upload4')) {
            
            if($language->voice_upload4 != ''){
                $path = public_path().'/uploads/voice/sample/'.$language->voice_upload4;
                if(file_exists($path)){
                    unlink($path);  
                }                
            }
            
            $extension = $request->file('voice_upload4')->getClientOriginalExtension();
            // Filename To store
            $fileVoiceNameToStore4 = 'voice4_'.time().'.'.$extension;

            $request->voice_upload4->move(public_path('uploads/voice/sample/'), $fileVoiceNameToStore4);
            $language->voice_upload4 = 'uploads/voice/sample/'.$fileVoiceNameToStore4;
        }
        
        $language->voice_text_m5 = $request->voice_text_m5;
        
        if ($request->hasFile('audio_m4')) {
            
            if($language->audio_m4 != ''){
                $path = public_path().'/uploads/voice/profile_pic/'.$language->audio_m4;
                if(file_exists($path)){
                    unlink($path);  
                }                
            }
            $extension = $request->file('audio_m4')->getClientOriginalExtension();
            // Filename To store
            $fileImageNameToStore5 = 'audio5_'.time().'.'.$extension;

            $request->audio_m4->move(public_path('uploads/voice/profile_pic/'), $fileImageNameToStore5);
            $language->audio_m4 = 'uploads/voice/profile_pic/'.$fileImageNameToStore5;
        }
        
        if ($request->hasFile('voice_upload5')) {
            
            if($language->voice_upload5 != ''){
                $path = public_path().'/uploads/voice/sample/'.$language->voice_upload5;
                if(file_exists($path)){
                    unlink($path);  
                }                
            }
            
            $extension = $request->file('voice_upload5')->getClientOriginalExtension();
            // Filename To store
            $fileVoiceNameToStore5 = 'voice5_'.time().'.'.$extension;

            $request->voice_upload5->move(public_path('uploads/voice/sample/'), $fileVoiceNameToStore5);
            $language->voice_upload5 = 'uploads/voice/sample/'.$fileVoiceNameToStore5;
        }
        
       
         $language->voice_text_f1 = $request->voice_text_m6;
        
        if ($request->hasFile('audio_f')) {
            
            if($language->audio_f != ''){
                $path = public_path().'/uploads/voice/profile_pic/'.$language->audio_f;
                if(file_exists($path)){
                    unlink($path);  
                }                
            }
            $extension = $request->file('audio_f')->getClientOriginalExtension();
            // Filename To store
            $fileImageNameToStore6 = 'audio6_'.time().'.'.$extension;

            $request->audio_f->move(public_path('uploads/voice/profile_pic/'), $fileImageNameToStore6);
            $language->audio_f = 'uploads/voice/profile_pic/'.$fileImageNameToStore6;
        }
        
        if ($request->hasFile('voice_upload6')) {
            
            if($language->voice_upload6 != ''){
                $path = public_path().'/uploads/voice/sample/'.$language->voice_upload6;
                if(file_exists($path)){
                    unlink($path);  
                }                
            }
            
            $extension = $request->file('voice_upload6')->getClientOriginalExtension();
            // Filename To store
            $fileVoiceNameToStore6 = 'voice6_'.time().'.'.$extension;

            $request->voice_upload6->move(public_path('uploads/voice/sample/'), $fileVoiceNameToStore6);
            $language->voice_upload6 = 'uploads/voice/sample/'.$fileVoiceNameToStore6;
        }
       
        $language->voice_text_f2 = $request->voice_text_m7;
        
        if ($request->hasFile('audio_f1')) {
            
            if($language->audio_f1 != ''){
                $path = public_path().'/uploads/voice/profile_pic/'.$language->audio_f1;
                if(file_exists($path)){
                    unlink($path);  
                }                
            }
            
            $extension = $request->file('audio_f1')->getClientOriginalExtension();
            // Filename To store
            $fileImageNameToStore7 = 'audio7_'.time().'.'.$extension;

            $request->audio_f1->move(public_path('uploads/voice/profile_pic/'), $fileImageNameToStore7);
            $language->audio_f1 = 'uploads/voice/profile_pic/'.$fileImageNameToStore7;
        }
        
        if ($request->hasFile('voice_upload7')) {
            
            if($language->voice_upload7 != ''){
                $path = public_path().'/uploads/voice/sample/'.$language->voice_upload7;
                if(file_exists($path)){
                    unlink($path);  
                }                
            }
            
            $extension = $request->file('voice_upload7')->getClientOriginalExtension();
            // Filename To store
            $fileVoiceNameToStore7 = 'voice7_'.time().'.'.$extension;

            $request->voice_upload7->move(public_path('uploads/voice/sample/'), $fileVoiceNameToStore7);
            $language->voice_upload7 = 'uploads/voice/sample/'.$fileVoiceNameToStore7;
        }
       
        $language->voice_text_f3 = $request->voice_text_m8;
        
        if ($request->hasFile('audio_f2')) {
            
            if($language->audio_f2 != ''){
                $path = public_path().'/uploads/voice/profile_pic/'.$language->audio_f2;
                if(file_exists($path)){
                    unlink($path);  
                }                
            }
            
            $extension = $request->file('audio_f2')->getClientOriginalExtension();
            // Filename To store
            $fileImageNameToStore8 = 'audio8_'.time().'.'.$extension;

            $request->audio_f2->move(public_path('uploads/voice/profile_pic/'), $fileImageNameToStore8);
            $language->audio_f2 = 'uploads/voice/profile_pic/'.$fileImageNameToStore8;
        }
        
        if ($request->hasFile('voice_upload8')) {
            
            if($language->voice_upload8 != ''){
                $path = public_path().'/uploads/voice/sample/'.$language->voice_upload8;
                if(file_exists($path)){
                    unlink($path);  
                }                
            }
            
            $extension = $request->file('voice_upload8')->getClientOriginalExtension();
            // Filename To store
            $fileVoiceNameToStore8 = 'voice8_'.time().'.'.$extension;

            $request->voice_upload8->move(public_path('uploads/voice/sample/'), $fileVoiceNameToStore8);
            $language->voice_upload8 = 'uploads/voice/sample/'.$fileVoiceNameToStore8;
        }
        
        $language->voice_text_f4 = $request->voice_text_m9;
        
        if ($request->hasFile('audio_f3')) {
            
            if($language->audio_f3 != ''){
                $path = public_path().'/uploads/voice/profile_pic/'.$language->audio_f3;
                if(file_exists($path)){
                    unlink($path);  
                }                
            }
            $extension = $request->file('audio_f3')->getClientOriginalExtension();
            // Filename To store
            $fileImageNameToStore9 = 'audio9_'.time().'.'.$extension;

            $request->audio_f3->move(public_path('uploads/voice/profile_pic/'), $fileImageNameToStore9);
            $language->audio_f3 = 'uploads/voice/profile_pic/'.$fileImageNameToStore9;
        }
        
        if ($request->hasFile('voice_upload9')) {
            
            if($language->voice_upload9 != ''){
                $path = public_path().'/uploads/voice/sample/'.$language->voice_upload9;
                if(file_exists($path)){
                    unlink($path);  
                }                
            }
            $extension = $request->file('voice_upload9')->getClientOriginalExtension();
            // Filename To store
            $fileVoiceNameToStore9 ='voice9_'. time().'.'.$extension;

            $request->voice_upload9->move(public_path('uploads/voice/sample/'), $fileVoiceNameToStore9);
            $language->voice_upload9 = 'uploads/voice/sample/'.$fileVoiceNameToStore9;
        }
        
        $language->voice_text_f5 = $request->voice_text_m10;
        
        if ($request->hasFile('audio_f4')) {
            
            if($language->audio_f4 != ''){
                $path = public_path().'/uploads/voice/profile_pic/'.$language->audio_f4;
                if(file_exists($path)){
                    unlink($path);  
                }                
            }
            
            $extension = $request->file('audio_f4')->getClientOriginalExtension();
            // Filename To store
            $fileImageNameToStore10 = 'audio10_'.time().'.'.$extension;

            $request->audio_f4->move(public_path('uploads/voice/profile_pic/'), $fileImageNameToStore10);
            $language->audio_f4 = 'uploads/voice/profile_pic/'.$fileImageNameToStore10;
        }
        
        if ($request->hasFile('voice_upload10')) {
            
            if($language->voice_upload10 != ''){
                $path = public_path().'/uploads/voice/sample/'.$language->voice_upload10;
                if(file_exists($path)){
                    unlink($path);  
                }                
            }
            $extension = $request->file('voice_upload10')->getClientOriginalExtension();
            // Filename To store
            $fileVoiceNameToStore10 = 'voice10_'.time().'.'.$extension;

            $request->voice_upload10->move(public_path('uploads/voice/sample/'), $fileVoiceNameToStore10);
            $language->voice_upload10 = 'uploads/voice/sample/'.$fileVoiceNameToStore10;
        }

   
        $language->update();
        
        return redirect()->route('languages.index')->withStatus(__('Language successfully updated.'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Languages $language)
    {
        $affectedRows = Languages::where('parent_id', '=', $language->id)->delete();
        if($affectedRows==1){
           $language->delete();
            return redirect()->route('languages.index')->withStatus(__('Primary language successfully deleted.'));
        }else{
            $language->delete();
            return redirect()->route('languages.index')->withStatus(__('Primary language successfully deleted.'));
        }
        
    }
    
    public function status($id,$status)
    {  
        $Languages = Languages::findorfail($id);
        if( $Languages->status==1){
            $Languages->status=0;
             Languages::where('parent_id', '=', $id)->update(['status'=>'0']);
        }else{
            $Languages->status=1;
             Languages::where('parent_id', '=', $id)->update(['status'=>'1']);
        }
        //$Languages->status=$status;
        $Languages->update();

       // Languages::where('parent_id', '=', $id)->update(['status'=>$status]);
        echo true;
        
    }
    public function primaryLanguageList(){
        $data= Languages::where(['parent_id'=>0])->where(['status'=>1])->get();
        
        return response()->json([
            'data' =>$data,
            'status' => true,
            'errMsg' => ''
        ]);
    }
}

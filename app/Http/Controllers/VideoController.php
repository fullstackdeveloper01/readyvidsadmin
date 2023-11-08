<?php

namespace App\Http\Controllers;

use App\Video;use App\Template;
use App\VideoText;
use App\VideoTextMapping;
use App\Categories;
use App\TemplateType;
use App\NotificationStatus;
use App\Section;
use App\Languages;
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
use App\Exports\VideoExport;
use App\Exports\VideoFullExport;

class VideoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $categoryList =Categories::where('parent_id','=',0)->where('status','=',1)->get();
        $primaryLanguageList =Languages::where('parent_id','=',0)->where('status','=',1)->get();
        $videos=Video::select('video.*','section.name as section_name','categories.name as category_name','sucategory.name as subcategory_name')
                        ->join('section','video.section','=','section.id')
                        ->join('categories','video.category','=','categories.id')
                        ->join('categories as sucategory','video.subcategory','=','sucategory.id')
                        ->orderBy('id','desc');
           
       if(!empty($_GET['category_id'])){
            $category = $_GET['category_id'];
            $videos=$videos->whereRaw("find_in_set('$category',video.category)");
            //$videos=$videos->where('video.category','=',$_GET['category_id']);
        }
        if(!empty($_GET['subcategory_id'])){
            $subcategory = $_GET['subcategory_id'];
            $videos=$videos->whereRaw("find_in_set('$subcategory',video.subcategory)");//$videos->where('video.subcategory','=',$_GET['subcategory_id']);
        }
        if(!empty($_GET['primary_language'])){
            $videos=$videos->where('video.primary_language','=',$_GET['primary_language']);
        }
        if(!empty($_GET['secondary_language'])){
            $videos=$videos->where('video.secondary_language','=',$_GET['secondary_language']);
        }
                        
        $videos= $videos->paginate(100);
                      
        
        return view('video.index', ['videos' =>$videos,'categoryList'=>$categoryList,'primaryLanguageList'=>$primaryLanguageList]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $categoryList =Categories::where('parent_id','=',0)->where('status','=',1)->get();
        $sectionList =Section::where('status','=',1)->get();
        $templateTypeList =TemplateType::where('status','=',1)->get();
        $primaryLanguageList =Languages::where('parent_id','=',0)->where('status','=',1)->get();
        return view('video.create',compact('categoryList','sectionList','templateTypeList','primaryLanguageList'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    // public function store(Request $request)
    // {
    //   //dd($request);
    //     $request->validate([
    //         'section' => ['required'],
    //         'template_type' => ['required'],
    //         'primary_language' => ['required'],
    //         'secondary_language' => ['required'],
    //         'category' => ['required'],
    //         'subcategory' => ['required'],
    //     ]);

    //     // $video = new Video;
    //     // $video->section = $request->section;
    //     // $video->category = $request->category;
    //     // $video->subcategory = $request->subcategory;

    //     // if ($request->hasFile('image')) {
    //     //     $filenameWithExt = $request->file('image')->getClientOriginalName ();
          
    //     //     $extension = $request->file('image')->getClientOriginalExtension();
    //     //     // Filename To store
    //     //     $fileNameToStore = time().'.'.$extension;

    //     //     $request->image->move(public_path('uploads/video/image'), $fileNameToStore);
    //     //     $video->image = 'uploads/video/image/'.$fileNameToStore;
            
    //     // }
        
    //     // if ($request->hasFile('audio_m')) {
    //     //     $extension = $request->file('audio_m')->getClientOriginalExtension();
    //     //     // Filename To store
    //     //     $fileNameToStore =time().'.'.$extension;

    //     //     $request->audio_m->move(public_path('uploads/video/audio_m'), $fileNameToStore);
    //     //     $video->audio_m = 'uploads/video/audio_m/'.$fileNameToStore;
    //     // }
           
          
    //     // if ($request->hasFile('audio_f')) {
    //     //     // // Get just Extension
    //     //     $extension = $request->file('audio_f')->getClientOriginalExtension();
    //     //     // Filename To store
    //     //     $fileNameToStore =time().'.'.$extension;

    //     //     $request->audio_f->move(public_path('uploads/video/audio_f'), $fileNameToStore);
    //     //     $video->audio_f = 'uploads/video/audio_f/'.$fileNameToStore;
    //     // }
    //     // $video->save();
        
    //     // $video_text = new VideoText;
    //     // for($counter=0;$counter<count($request->text);$counter++){
    //     //     $video_text = new VideoText;
    //     //     $video_text->video_id = $video->id;
    //     //     $video_text->text = $request->text[$counter];
    //     //     $video_text->save();
    //     // }
    //     // return redirect()->route('video.index')->withStatus(__('Video successfully created.'));
        
        
    //     // Excel file name for download 
    //     $fileName = "sample" . date('Y-m-d') . ".xls"; 
        
    //     // Column names 
    //     $fields = array('Video Type', 'Template Type', 'Primary Language', 'Secondary Language', 'Category', 'Subcategory','Image','Audio Male','Audio Female');
    //     $values = array($request->section, $request->template_type, $request->primary_language, $request->secondary_language,$request->category, $request->subcategory,'https://html.manageprojects.in/readyvids/public//uploads/video/image/1662636893.jpg','https://html.manageprojects.in/readyvids/public//uploads/video/audio_m/1662636893.mp3','https://html.manageprojects.in/readyvids/public//uploads/video/audio_m/1662636893.mp3');     
    //     $templateType= TemplateType::findorfail($request->template_type);
    //     $type= $templateType->type;
    //     $type_array = explode(' ',$type);
    //   // dd($type_array);
    //     for($counter=1;$counter<=$type_array[0];$counter++){
    //         array_push($fields,"Text".$counter);
    //         array_push($values,"Test".$counter);
    //     }
      
    //     // Display column names as first row 
    // //     $excelData = implode("\t", array_values($fields)) . "\n"; 
    // //     $excelData .= implode("\t", array_values($values)) . "\n"; 
      
    // //   // Headers for download 
    // //     header("Content-Type: application/vnd.ms-excel"); 
    // //     header("Content-Disposition: attachment; filename=\"$fileName\""); 
        
    // //     // Render excel data 
    // //     echo $excelData; 
        
      

    //     $delimiter = ","; 
    //     $filename = "sample_" . date('Y-m-d') . ".csv"; 
         
    //     // Create a file pointer 
    //     $f = fopen('php://memory', 'w'); 
         
    //     // Set column headers 
       
    //     fputcsv($f, $fields, $delimiter); 
    //     fputcsv($f, $values, $delimiter); 
    //     // Move back to beginning of file 
    //     fseek($f, 0); 
        
    //     // Set headers to download file rather than displayed 
    //     header('Content-Type: text/csv'); 
    //     header('Content-Disposition: attachment; filename="' . $filename . '";'); 
        
    //     //output all remaining data on a file pointer 
    //     fpassthru($f); 
    //       exit;
    // }
    
    
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
       //dd($request);
        $request->validate([
            'section' => ['required'],
            'template_type' => ['required'],
            'primary_language' => ['required'],
            'secondary_language' => ['required'],
            'category' => ['required'],
            'subcategory' => ['required'],
              'image'=>['required'],
            'audio_m'=>['required'],
            'audio_f'=>['required'],
            'audio_m1'=>['required'],
            'audio_f1'=>['required'],
            'audio_m2'=>['required'],
            'audio_f2'=>['required'],
            'audio_m3'=>['required'],
            'audio_f3'=>['required'],
            'audio_m4'=>['required'],
            'audio_f4'=>['required'],
            'audio_m1_long'=>['required'],
            'audio_m2_long'=>['required'],
            'audio_m3_long'=>['required'],
            'audio_m4_long'=>['required'],
            'audio_m5_long'=>['required'],
            'audio_f1_long'=>['required'],
            'audio_f2_long'=>['required'],
            'audio_f3_long'=>['required'],
            'audio_f4_long'=>['required'],
            'audio_f5_long'=>['required'],
            
        ]);

        $video = new Video;
        $video->section = $request->section;
        $video->category = $request->category;
        $video->subcategory = $request->subcategory;
        $video->template_type = $request->template_type;
        $video->primary_language = $request->primary_language;
        $video->secondary_language = $request->secondary_language;
       

        if ($request->hasFile('image')) {
            $filenameWithExt = $request->file('image')->getClientOriginalName ();
          
            $extension = $request->file('image')->getClientOriginalExtension();
            // Filename To store
            $fileNameToStore = time().'.'.$extension;

            $request->image->move(public_path('uploads/video/image'), $fileNameToStore);
            $video->image = 'uploads/video/image/'.$fileNameToStore;
        //     $video->thumbnail_600_500 = 'uploads/video/thumbnail_image/'.$fileNameToStore;
        //   Image::make(public_path('uploads/video/image/'.$fileNameToStore))->resize(600, 500)->save(public_path('uploads/video/thumbnail_image/'.$fileNameToStore));
             $video->thumbnail_600_500 = 'uploads/video/thumbnail_image/'.$fileNameToStore;
            $thumbnail_289_289 = time()."_289_289.".$extension;
            $video->thumbnail_289_289 = 'uploads/video/thumbnail_image/'.$thumbnail_289_289;
            Image::make(public_path('uploads/video/image/'.$fileNameToStore))->resize(600, 500)->save(public_path('uploads/video/thumbnail_image/'.$fileNameToStore));
            Image::make(public_path('uploads/video/image/'.$fileNameToStore))->resize(289, 289)->save(public_path('uploads/video/thumbnail_image/'.$thumbnail_289_289));
            
            $thumbnail_400_264 = 'uploads/video/thumbnail_image/'.time().'.'.$extension;
             $video->thumbnail_400_264 = $thumbnail_400_264;
            Image::make(public_path('uploads/video/image/'.$fileNameToStore))->resize(400, 264)->save(public_path($thumbnail_400_264)); 
            $thumbnail_400_320 = 'uploads/video/thumbnail_image/'.time().".jpg";
            Image::make(public_path('uploads/video/image/'.$fileNameToStore))->resize(400, 320)->save(public_path($thumbnail_400_320));
            $video->thumbnail_400_320 = $thumbnail_400_320;
        }
        
        if ($request->hasFile('audio_m')) {
            $extension = $request->file('audio_m')->getClientOriginalExtension();
            // Filename To store
            $fileNameToStore =time().'.'.$extension;

            $request->audio_m->move(public_path('uploads/video/audio_m'), $fileNameToStore);
            $video->audio_m = 'uploads/video/audio_m/'.$fileNameToStore;
        }
           
          
        if ($request->hasFile('audio_f')) {
            // // Get just Extension
            $extension = $request->file('audio_f')->getClientOriginalExtension();
            // Filename To store
            $fileNameToStore =time().'.'.$extension;

            $request->audio_f->move(public_path('uploads/video/audio_f'), $fileNameToStore);
            $video->audio_f = 'uploads/video/audio_f/'.$fileNameToStore;
        }
           if ($request->hasFile('audio_m1')) {
            $extension = $request->file('audio_m1')->getClientOriginalExtension();
            // Filename To store
            $fileNameToStore =time().'.'.$extension;

            $request->audio_m1->move(public_path('uploads/video/audio_m'), $fileNameToStore);
            $video->audio_m1 = 'uploads/video/audio_m/'.$fileNameToStore;
        }
           
          
        if ($request->hasFile('audio_f1')) {
            // // Get just Extension
            $extension = $request->file('audio_f1')->getClientOriginalExtension();
            // Filename To store
            $fileNameToStore =time().'.'.$extension;

            $request->audio_f1->move(public_path('uploads/video/audio_f'), $fileNameToStore);
            $video->audio_f1 = 'uploads/video/audio_f/'.$fileNameToStore;
        }

        if ($request->hasFile('audio_m2')) {
            $extension = $request->file('audio_m2')->getClientOriginalExtension();
            // Filename To store
            $fileNameToStore =time().'.'.$extension;

            $request->audio_m2->move(public_path('uploads/video/audio_m'), $fileNameToStore);
            $video->audio_m2 = 'uploads/video/audio_m/'.$fileNameToStore;
        }
           
          
        if ($request->hasFile('audio_f2')) {
            // // Get just Extension
            $extension = $request->file('audio_f2')->getClientOriginalExtension();
            // Filename To store
            $fileNameToStore =time().'.'.$extension;

            $request->audio_f2->move(public_path('uploads/video/audio_f'), $fileNameToStore);
            $video->audio_f2 = 'uploads/video/audio_f/'.$fileNameToStore;
        }

        if ($request->hasFile('audio_m3')) {
            $extension = $request->file('audio_m3')->getClientOriginalExtension();
            // Filename To store
            $fileNameToStore =time().'.'.$extension;

            $request->audio_m3->move(public_path('uploads/video/audio_m'), $fileNameToStore);
            $video->audio_m3 = 'uploads/video/audio_m/'.$fileNameToStore;
        }
           
          
        if ($request->hasFile('audio_f3')) {
            // // Get just Extension
            $extension = $request->file('audio_f3')->getClientOriginalExtension();
            // Filename To store
            $fileNameToStore =time().'.'.$extension;

            $request->audio_f3->move(public_path('uploads/video/audio_f'), $fileNameToStore);
            $video->audio_f3 = 'uploads/video/audio_f/'.$fileNameToStore;
        }

        if ($request->hasFile('audio_m4')) {
            $extension = $request->file('audio_m4')->getClientOriginalExtension();
            // Filename To store
            $fileNameToStore =time().'.'.$extension;

            $request->audio_m4->move(public_path('uploads/video/audio_m'), $fileNameToStore);
            $video->audio_m4 = 'uploads/video/audio_m/'.$fileNameToStore;
        }
           
          
        if ($request->hasFile('audio_f4')) {
            // // Get just Extension
            $extension = $request->file('audio_f4')->getClientOriginalExtension();
            // Filename To store
            $fileNameToStore =time().'.'.$extension;

            $request->audio_f4->move(public_path('uploads/video/audio_f'), $fileNameToStore);
            $video->audio_f4 = 'uploads/video/audio_f/'.$fileNameToStore;
        }

        if ($request->hasFile('audio_m1_long')) {
            $extension = $request->file('audio_m1_long')->getClientOriginalExtension();
            // Filename To store
            $fileNameToStore =time().'.'.$extension;

            $request->audio_m1_long->move(public_path('uploads/video/audio_m'), $fileNameToStore);
            $video->audio_m1_long = 'uploads/video/audio_m/'.$fileNameToStore;
        }
           
          
        if ($request->hasFile('audio_f1_long')) {
            // // Get just Extension
            $extension = $request->file('audio_f1_long')->getClientOriginalExtension();
            // Filename To store
            $fileNameToStore =time().'.'.$extension;

            $request->audio_f1_long->move(public_path('uploads/video/audio_f'), $fileNameToStore);
            $video->audio_f1_long = 'uploads/video/audio_f/'.$fileNameToStore;
        }

        if ($request->hasFile('audio_m2_long')) {
            $extension = $request->file('audio_m2_long')->getClientOriginalExtension();
            // Filename To store
            $fileNameToStore =time().'.'.$extension;

            $request->audio_m2_long->move(public_path('uploads/video/audio_m'), $fileNameToStore);
            $video->audio_m2_long = 'uploads/video/audio_m/'.$fileNameToStore;
        }
           
          
        if ($request->hasFile('audio_f2_long')) {
            // // Get just Extension
            $extension = $request->file('audio_f2_long')->getClientOriginalExtension();
            // Filename To store
            $fileNameToStore =time().'.'.$extension;

            $request->audio_f2_long->move(public_path('uploads/video/audio_f'), $fileNameToStore);
            $video->audio_f2_long = 'uploads/video/audio_f/'.$fileNameToStore;
        }

        if ($request->hasFile('audio_m3_long')) {
            $extension = $request->file('audio_m3_long')->getClientOriginalExtension();
            // Filename To store
            $fileNameToStore =time().'.'.$extension;

            $request->audio_m3_long->move(public_path('uploads/video/audio_m'), $fileNameToStore);
            $video->audio_m3_long = 'uploads/video/audio_m/'.$fileNameToStore;
        }
           
          
        if ($request->hasFile('audio_f3_long')) {
            // // Get just Extension
            $extension = $request->file('audio_f3_long')->getClientOriginalExtension();
            // Filename To store
            $fileNameToStore =time().'.'.$extension;

            $request->audio_f3_long->move(public_path('uploads/video/audio_f'), $fileNameToStore);
            $video->audio_f3_long = 'uploads/video/audio_f/'.$fileNameToStore;
        }

        if ($request->hasFile('audio_m4_long')) {
            $extension = $request->file('audio_m4_long')->getClientOriginalExtension();
            // Filename To store
            $fileNameToStore =time().'.'.$extension;

            $request->audio_m4_long->move(public_path('uploads/video/audio_m'), $fileNameToStore);
            $video->audio_m4_long = 'uploads/video/audio_m/'.$fileNameToStore;
        }
           
          
        if ($request->hasFile('audio_f4_long')) {
            // // Get just Extension
            $extension = $request->file('audio_f4_long')->getClientOriginalExtension();
            // Filename To store
            $fileNameToStore =time().'.'.$extension;

            $request->audio_f4_long->move(public_path('uploads/video/audio_f'), $fileNameToStore);
            $video->audio_f4_long = 'uploads/video/audio_f/'.$fileNameToStore;
        }

        if ($request->hasFile('audio_m5_long')) {
            $extension = $request->file('audio_m5_long')->getClientOriginalExtension();
            // Filename To store
            $fileNameToStore =time().'.'.$extension;

            $request->audio_m5_long->move(public_path('uploads/video/audio_m'), $fileNameToStore);
            $video->audio_m5_long = 'uploads/video/audio_m/'.$fileNameToStore;
        }
           
          
        if ($request->hasFile('audio_f5_long')) {
            // // Get just Extension
            $extension = $request->file('audio_f5_long')->getClientOriginalExtension();
            // Filename To store
            $fileNameToStore =time().'.'.$extension;

            $request->audio_f5_long->move(public_path('uploads/video/audio_f'), $fileNameToStore);
            $video->audio_f5_long = 'uploads/video/audio_f/'.$fileNameToStore;
        }
        $video->save();
        
        $video_text = new VideoText;
        for($counter=0;$counter<count($request->text);$counter++){
            
            $textresult= VideoText::where('text','=',$request->text[$counter])->first();
            if($textresult==null){
                $video_text = new VideoText;
                //$video_text->video_id = $video->id;
                $video_text->text = $request->text[$counter];
                $video_text->save();
                
                $video_text_mapping = new VideoTextMapping;
                $video_text_mapping->video_id = $video->id;
                $video_text_mapping->text_id = $video_text->id;
                $video_text_mapping->save();
            }
            else{
                
                $video_text_mapping = new VideoTextMapping;
                $video_text_mapping->video_id = $video->id;
                $video_text_mapping->text_id = $textresult->id;
                $video_text_mapping->save();
            }
           
        }
        return redirect()->route('video.index')->withStatus(__('Video successfully created.'));

        
   
    }
    /**
     * Display the specified resource.
     *
     * @param  \App\Driver  $driver
     * @return \Illuminate\Http\Response
     */
    public function show(video $driver)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Driver  $driver
     * @return \Illuminate\Http\Response
     */
    public function edit(video $video)
    {
        if(auth()->user()->hasRole('admin')){
            $templateTypeList =TemplateType::where('status','=',1)->get();
            $primaryLanguageList =Languages::where('parent_id','=',0)->where('status','=',1)->get();
            $secondaryLanguageList =Languages::where('parent_id','=',$video->primary_language)->where('status','=',1)->get();
            $categoryList =Categories::where('parent_id','=',0)->where('status','=',1)->get();
            $subcategoryList =Categories::where('parent_id','=',$video->category)->get();
            $sectionList =Section::where('status','=',1)->get();
            $videoTexts= VideoTextMapping::join('video_text','video_text_mapping.text_id','=','video_text.id')->where('video_text_mapping.video_id','=',$video->id)->get();
           
            return view('video.edit', compact('video','categoryList','sectionList','subcategoryList','videoTexts','templateTypeList','primaryLanguageList','secondaryLanguageList'));
        }else return redirect()->route('video.index')->withStatus(__('No Access'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Driver  $driver
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, video $video)
    {//dd($request);die;
         $request->validate([
            'section' => ['required'],
            'template_type' => ['required'],
            'primary_language' => ['required'],
            'secondary_language' => ['required'],
            'category' => ['required'],
            'subcategory' => ['required'],
            // 'image'=>['required'],
            // 'audio_m'=>['required'],
            // 'audio_f'=>['required'],
            
        ]);

        
        $video->section = $request->section;
        $video->category = $request->category;
        $video->subcategory = $request->subcategory;
        $video->template_type = $request->template_type;
        $video->primary_language = $request->primary_language;
        $video->secondary_language = $request->secondary_language;

        if ($request->hasFile('image')) {
            
            if($video->image != ''){
                $path = public_path().'/'.$video->image;
               if(file_exists($path)){
                    unlink($path);  
                }              
            }
            $filenameWithExt = $request->file('image')->getClientOriginalName ();
          
            $extension = $request->file('image')->getClientOriginalExtension();
            // Filename To store
            $fileNameToStore = time().'.'.$extension;

            $request->image->move(public_path('uploads/video/image'), $fileNameToStore);
            $video->image = 'uploads/video/image/'.$fileNameToStore;

            $video->thumbnail_600_500 = 'uploads/video/thumbnail_image/'.$fileNameToStore;
            $thumbnail_289_289 = time()."_289_289.".$extension;
            $video->thumbnail_289_289 = 'uploads/video/thumbnail_image/'.$thumbnail_289_289;
            Image::make(public_path('uploads/video/image/'.$fileNameToStore))->resize(600, 500)->save(public_path('uploads/video/thumbnail_image/'.$fileNameToStore));
            Image::make(public_path('uploads/video/image/'.$fileNameToStore))->resize(289, 289)->save(public_path('uploads/video/thumbnail_image/'.$thumbnail_289_289));
            
            $thumbnail_400_264 = 'uploads/video/thumbnail_image/'.time().'.'.$extension;
            $video->thumbnail_400_264 = $thumbnail_400_264;
            Image::make(public_path('uploads/video/image/'.$fileNameToStore))->resize(400, 264)->save(public_path($thumbnail_400_264));
            
        }
        
        // if ($request->hasFile('audio_m')) {
        //     if($video->audio_m != ''){
        //         $path = public_path().'/'.$video->audio_m;
        //         if(file_exists($path)){
        //             unlink($path);  
        //         }               
        //     }
        //     $extension = $request->file('audio_m')->getClientOriginalExtension();
        //     // Filename To store
        //     $fileNameToStore =time().'.'.$extension;

        //     $request->audio_m->move(public_path('uploads/video/audio_m'), $fileNameToStore);
        //     $video->audio_m = 'uploads/video/audio_m/'.$fileNameToStore;
           
            
        // }
           
          
        // if ($request->hasFile('audio_f')) {
        //     if($video->audio_f != ''){
        //         $path = public_path().'/'.$video->audio_f;
        //       if(file_exists($path)){
        //             unlink($path);  
        //         }               
        //     }
        //     // // Get just Extension
        //     $extension = $request->file('audio_f')->getClientOriginalExtension();
        //     // Filename To store
        //     $fileNameToStore =time().'.'.$extension;

        //     $request->audio_f->move(public_path('uploads/video/audio_f'), $fileNameToStore);
        //     $video->audio_f = 'uploads/video/audio_f/'.$fileNameToStore;
            
        // }
           if ($request->hasFile('audio_m')) {
            if($video->audio_m != ''){
                $path = public_path().'/'.$video->audio_m;
                if(file_exists($path)){
                    unlink($path);  
                }                
            }
            $extension = $request->file('audio_m')->getClientOriginalExtension();
            // Filename To store
            $fileNameToStore =time().'.'.$extension;

            $request->audio_m->move(public_path('uploads/video/audio_m'), $fileNameToStore);
            $video->audio_m = 'uploads/video/audio_m/'.$fileNameToStore;
           
            
        }
           
          
        if ($request->hasFile('audio_f')) {
            if($video->audio_f != ''){
                $path = public_path().'/'.$video->audio_f;
                if(file_exists($path)){
                    unlink($path);  
                }               
            }
            // // Get just Extension
            $extension = $request->file('audio_f')->getClientOriginalExtension();
            // Filename To store
            $fileNameToStore =time().'.'.$extension;

            $request->audio_f->move(public_path('uploads/video/audio_f'), $fileNameToStore);
            $video->audio_f = 'uploads/video/audio_f/'.$fileNameToStore;
            
        }

        if ($request->hasFile('audio_m1')) {
            if($video->audio_m1 != ''){
                $path = public_path().'/'.$video->audio_m1;
                if(file_exists($path)){
                    unlink($path);  
                }                
            }
            $extension = $request->file('audio_m1')->getClientOriginalExtension();
            // Filename To store
            $fileNameToStore =time().'.'.$extension;

            $request->audio_m1->move(public_path('uploads/video/audio_m'), $fileNameToStore);
            $video->audio_m1 = 'uploads/video/audio_m/'.$fileNameToStore;
        }
           
          
        if ($request->hasFile('audio_f1')) {
            if($video->audio_f1 != ''){
                $path = public_path().'/'.$video->audio_f1;
                if(file_exists($path)){
                    unlink($path);  
                }                
            }
            // // Get just Extension
            $extension = $request->file('audio_f1')->getClientOriginalExtension();
            // Filename To store
            $fileNameToStore =time().'.'.$extension;

            $request->audio_f1->move(public_path('uploads/video/audio_f'), $fileNameToStore);
            $video->audio_f1 = 'uploads/video/audio_f/'.$fileNameToStore;
        }

        if ($request->hasFile('audio_m2')) {
            if($video->audio_m2 != ''){
                $path = public_path().'/'.$video->audio_m2;
                if(file_exists($path)){
                    unlink($path);  
                }                
            }
            $extension = $request->file('audio_m2')->getClientOriginalExtension();
            // Filename To store
            $fileNameToStore =time().'.'.$extension;

            $request->audio_m2->move(public_path('uploads/video/audio_m'), $fileNameToStore);
            $video->audio_m2 = 'uploads/video/audio_m/'.$fileNameToStore;
        }
           
          
        if ($request->hasFile('audio_f2')) {
            if($video->audio_f2 != ''){
                $path = public_path().'/'.$video->audio_f2;
                if(file_exists($path)){
                    unlink($path);  
                }                
            }
            // // Get just Extension
            $extension = $request->file('audio_f2')->getClientOriginalExtension();
            // Filename To store
            $fileNameToStore =time().'.'.$extension;

            $request->audio_f2->move(public_path('uploads/video/audio_f'), $fileNameToStore);
            $video->audio_f2 = 'uploads/video/audio_f/'.$fileNameToStore;
        }

        if ($request->hasFile('audio_m3')) {
            if($video->audio_m3 != ''){
                $path = public_path().'/'.$video->audio_m3;
                if(file_exists($path)){
                    unlink($path);  
                }                
            }
            $extension = $request->file('audio_m3')->getClientOriginalExtension();
            // Filename To store
            $fileNameToStore =time().'.'.$extension;

            $request->audio_m3->move(public_path('uploads/video/audio_m'), $fileNameToStore);
            $video->audio_m3 = 'uploads/video/audio_m/'.$fileNameToStore;
        }
           
          
        if ($request->hasFile('audio_f3')) {
            if($video->audio_f3 != ''){
                $path = public_path().'/'.$video->audio_f3;
                if(file_exists($path)){
                    unlink($path);  
                }                
            }
            // // Get just Extension
            $extension = $request->file('audio_f3')->getClientOriginalExtension();
            // Filename To store
            $fileNameToStore =time().'.'.$extension;

            $request->audio_f3->move(public_path('uploads/video/audio_f'), $fileNameToStore);
            $video->audio_f3 = 'uploads/video/audio_f/'.$fileNameToStore;
        }

        if ($request->hasFile('audio_m4')) {
            if($video->audio_m4 != ''){
                $path = public_path().'/'.$video->audio_m4;
                if(file_exists($path)){
                    unlink($path);  
                }                
            }
            $extension = $request->file('audio_m4')->getClientOriginalExtension();
            // Filename To store
            $fileNameToStore =time().'.'.$extension;

            $request->audio_m4->move(public_path('uploads/video/audio_m'), $fileNameToStore);
            $video->audio_m4 = 'uploads/video/audio_m/'.$fileNameToStore;
        }
           
          
        if ($request->hasFile('audio_f4')) {
            if($video->audio_f4 != ''){
                $path = public_path().'/'.$video->audio_f4;
                if(file_exists($path)){
                    unlink($path);  
                }                
            }
            // // Get just Extension
            $extension = $request->file('audio_f4')->getClientOriginalExtension();
            // Filename To store
            $fileNameToStore =time().'.'.$extension;

            $request->audio_f4->move(public_path('uploads/video/audio_f'), $fileNameToStore);
            $video->audio_f4 = 'uploads/video/audio_f/'.$fileNameToStore;
        }

        if ($request->hasFile('audio_m1_long')) {
            if($video->audio_m1_long != ''){
                $path = public_path().'/'.$video->audio_m1_long;
                if(file_exists($path)){
                    unlink($path);  
                }                
            }
            $extension = $request->file('audio_m1_long')->getClientOriginalExtension();
            // Filename To store
            $fileNameToStore =time().'.'.$extension;

            $request->audio_m1_long->move(public_path('uploads/video/audio_m'), $fileNameToStore);
            $video->audio_m1_long = 'uploads/video/audio_m/'.$fileNameToStore;
        }
           
          
        if ($request->hasFile('audio_f1_long')) {
            if($video->audio_f1_long != ''){
                $path = public_path().'/'.$video->audio_f1_long;
                if(file_exists($path)){
                    unlink($path);  
                }                
            }
            // // Get just Extension
            $extension = $request->file('audio_f1_long')->getClientOriginalExtension();
            // Filename To store
            $fileNameToStore =time().'.'.$extension;

            $request->audio_f1_long->move(public_path('uploads/video/audio_f'), $fileNameToStore);
            $video->audio_f1_long = 'uploads/video/audio_f/'.$fileNameToStore;
        }

        if ($request->hasFile('audio_m2_long')) {
            if($video->audio_m2_long != ''){
                $path = public_path().'/'.$video->audio_m2_long;
                if(file_exists($path)){
                    unlink($path);  
                }                
            }
            $extension = $request->file('audio_m2_long')->getClientOriginalExtension();
            // Filename To store
            $fileNameToStore =time().'.'.$extension;

            $request->audio_m2_long->move(public_path('uploads/video/audio_m'), $fileNameToStore);
            $video->audio_m2_long = 'uploads/video/audio_m/'.$fileNameToStore;
        }
           
          
        if ($request->hasFile('audio_f2_long')) {
            if($video->audio_f2_long != ''){
                $path = public_path().'/'.$video->audio_f2_long;
                if(file_exists($path)){
                    unlink($path);  
                }                
            }
            // // Get just Extension
            $extension = $request->file('audio_f2_long')->getClientOriginalExtension();
            // Filename To store
            $fileNameToStore =time().'.'.$extension;

            $request->audio_f2_long->move(public_path('uploads/video/audio_f'), $fileNameToStore);
            $video->audio_f2_long = 'uploads/video/audio_f/'.$fileNameToStore;
        }

        if ($request->hasFile('audio_m3_long')) {
            if($video->audio_m3_long != ''){
                $path = public_path().'/'.$video->audio_m3_long;
                if(file_exists($path)){
                    unlink($path);  
                }                
            }
            $extension = $request->file('audio_m3_long')->getClientOriginalExtension();
            // Filename To store
            $fileNameToStore =time().'.'.$extension;

            $request->audio_m3_long->move(public_path('uploads/video/audio_m'), $fileNameToStore);
            $video->audio_m3_long = 'uploads/video/audio_m/'.$fileNameToStore;
        }
           
          
        if ($request->hasFile('audio_f3_long')) {
            if($video->audio_f3_long != ''){
                $path = public_path().'/'.$video->audio_f3_long;
                if(file_exists($path)){
                    unlink($path);  
                }                
            }
            // // Get just Extension
            $extension = $request->file('audio_f3_long')->getClientOriginalExtension();
            // Filename To store
            $fileNameToStore =time().'.'.$extension;

            $request->audio_f3_long->move(public_path('uploads/video/audio_f'), $fileNameToStore);
            $video->audio_f3_long = 'uploads/video/audio_f/'.$fileNameToStore;
        }

        if ($request->hasFile('audio_m4_long')) {
            if($video->audio_m4_long != ''){
                $path = public_path().'/'.$video->audio_m4_long;
                if(file_exists($path)){
                    unlink($path);  
                }                
            }
            $extension = $request->file('audio_m4_long')->getClientOriginalExtension();
            // Filename To store
            $fileNameToStore =time().'.'.$extension;

            $request->audio_m4_long->move(public_path('uploads/video/audio_m'), $fileNameToStore);
            $video->audio_m4_long = 'uploads/video/audio_m/'.$fileNameToStore;
        }
           
          
        if ($request->hasFile('audio_f4_long')) {
            if($video->audio_f4_long != ''){
                $path = public_path().'/'.$video->audio_f4_long;
                if(file_exists($path)){
                    unlink($path);  
                }                
            }
            // // Get just Extension
            $extension = $request->file('audio_f4_long')->getClientOriginalExtension();
            // Filename To store
            $fileNameToStore =time().'.'.$extension;

            $request->audio_f4_long->move(public_path('uploads/video/audio_f'), $fileNameToStore);
            $video->audio_f4_long = 'uploads/video/audio_f/'.$fileNameToStore;
        }

        if ($request->hasFile('audio_m5_long')) {
            if($video->audio_m5_long != ''){
                $path = public_path().'/'.$video->audio_m5_long;
                if(file_exists($path)){
                    unlink($path);  
                }                
            }
            $extension = $request->file('audio_m5_long')->getClientOriginalExtension();
            // Filename To store
            $fileNameToStore =time().'.'.$extension;

            $request->audio_m5_long->move(public_path('uploads/video/audio_m'), $fileNameToStore);
            $video->audio_m5_long = 'uploads/video/audio_m/'.$fileNameToStore;
        }
           
          
        if ($request->hasFile('audio_f5_long')) {
            if($video->audio_m5_long != ''){
                $path = public_path().'/'.$video->audio_m5_long;
                if(file_exists($path)){
                    unlink($path);  
                }                
            }
            // // Get just Extension
            $extension = $request->file('audio_f5_long')->getClientOriginalExtension();
            // Filename To store
            $fileNameToStore =time().'.'.$extension;

            $request->audio_f5_long->move(public_path('uploads/video/audio_f'), $fileNameToStore);
            $video->audio_f5_long = 'uploads/video/audio_f/'.$fileNameToStore;
        }

        $video->update();
        
        //VideoText::where('video_id','=',$video->id)->delete();
        VideoTextMapping::where('video_id','=',$video->id)->delete();
       
        for($counter=0;$counter<count($request->text);$counter++){
            // $video_text = new VideoText;
            // $video_text->video_id = $video->id;
            // $video_text->text = $request->text[$counter];
            // $video_text->save();
            $textresult= VideoText::where('text','=',$request->text[$counter])->first();
            if($textresult==null){
                $video_text = new VideoText;
                //$video_text->video_id = $video->id;
                $video_text->text = $request->text[$counter];
                $video_text->save();
                
                $video_text_mapping = new VideoTextMapping;
                $video_text_mapping->video_id = $video->id;
                $video_text_mapping->text_id = $video_text->id;
                $video_text_mapping->save();
            }
            else{
                // $textmappingresult= VideoTextMapping::where('text_id','=',$textresult->id)->where('video_id','=',$video->id)->first();
                // if($textmappingresult==null){
                    $video_text_mapping = new VideoTextMapping;
                    $video_text_mapping->video_id = $video->id;
                    $video_text_mapping->text_id = $textresult->id;
                    $video_text_mapping->save();
                //}
              
            }
        }

       
        return redirect()->route('video.index')->withStatus(__('video successfully updated.'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Driver  $driver
     * @return \Illuminate\Http\Response
     */
    public function destroy(video $video)
    {
        $affectedRows = $video->delete();
        if($affectedRows)
        {
            if($video->image != ''){
                $path = public_path().'/'.$video->image;
                if(file_exists($path)){
                    unlink($path);  
                }               
            }
            if($video->audio_m != ''){
                $path = public_path().'/'.$video->audio_m;
                if(file_exists($path)){
                    unlink($path);  
                }              
            }
            if($video->audio_f != ''){
                $path = public_path().'/'.$video->audio_f;
               if(file_exists($path)){
                    unlink($path);  
                }               
            }
            VideoTextMapping::where('video_id','=',$video->id)->delete();
        }
        /*
        if($video->status==1){
            $video->status=0;
            $video->update();
            return redirect()->route('video.index')->withStatus(__('video successfully deactivate.'));
        }else{
            $video->status=1;
            $video->update();
            return redirect()->route('video.index')->withStatus(__('video successfully activate.'));
        }*/       
        return redirect()->route('video.index')->withStatus(__('Video successfully deleted.'));
    }
    public function status($id,$status)
    {  
        $video = Video::findorfail($id);
        if($video->status==1){
            $video->status=0;
        }else{
            $video->status=1;
        }
       
        $video->update();

        echo true;
        
    }

     public function bulk_upload(){
        return view('video.bulk_upload');
    }
    
    // public function bulkstore(Request $request){
    // //    /dd($request);
    //     $request->validate([
    //         'bulkupload' => ['required'],
    //     ]);


    //     if ($request->hasFile('bulkupload')) {
        
    //         $extension = $request->file('bulkupload')->getClientOriginalExtension();
    //         // Filename To store
    //         $fileNameToStore = time().'.'.$extension;

    //         $request->bulkupload->move(public_path('uploads/video/bulk_upload'), $fileNameToStore);
    //         $path = public_path().'/uploads/video/bulk_upload/'.$fileNameToStore;



    //         $csv_data = array_map('str_getcsv', file( $path));
    //         array_shift($csv_data);
    //         //dd($csv_data);
    //         foreach($csv_data as $key=>$line){
    //             $video = new Video;
    //             // dd($line);
    //             // echo $num = count($line);die;
    //             $section = Section::where('name','=',$line[0])->first();
    //             if($section!=null){
    //                 $video->section = $section->id;
    //             }
    //             else{
    //                 return redirect()->route('video.index')->withStatus(__('section is required'));
    //             }
    //             $category = Categories::where('name','=',$line[1])->first();
    //             if($category!=null){
    //                 $video->category = $category->id;
    //                 $subcategory = Categories::where('name','=',$line[2])->where('parent_id','=', $category->id)->first();
    //                 if($subcategory!=null){
    //                     $video->subcategory = $subcategory->id;
    //                 }
    //                 else{
    //                     return redirect()->route('video.index')->withStatus(__('subcategory is required'));
    //                 }
    //             }else{
    //                 return redirect()->route('video.index')->withStatus(__('category is required'));
    //             }
              
    //             if($line[3]!=''){
    //                 $path= 'https://html.manageprojects.in/readyvids/public/uploads/video/image/1662555714.jpg';
    //                 $time=time();
    //                 $img = public_path().'/uploads/video/image/'.$time.'.jpg';
                    
    //                 $status= $this->download($line[3],$img);
                    
    //                 //file_put_contents($img, file_get_contents($line[3]));
    //                 if($status=='OK'){
    //                    $video->image = 'uploads/video/image/'.$time.'.jpg'; 
    //                 }else{
    //                      return redirect()->route('video.index')->withStatus(__($status));
    //                 }
                    
    //             }
    //             else{
    //                 return redirect()->route('video.index')->withStatus(__('Image is required'));
    //             }
              
             
    //             if($line[4]!=''){
    //                 $path= 'https://file-examples.com/storage/fe7d3a0d44631509da1f416/2017/11/file_example_MP3_700KB.mp3';
    //                 $time=time();
    //                 $img = public_path().'/uploads/video/audio_m/'.$time.'.mp3';

    //                 //file_put_contents($img, file_get_contents($line[4]));
    //                 $this->download($line[4],$img);
    //                 if($status=='OK'){
    //                     $video->audio_m = 'uploads/video/audio_m/'.$time.'.mp3';
    //                 }else{
    //                      return redirect()->route('video.index')->withStatus(__($status));
    //                 }
                    
                  
    //             }
    //             else{
    //                 return redirect()->route('video.index')->withStatus(__('Audio Male is required'));
    //             }
               
    //             if($line[5]!=''){
    //                 $path= 'https://file-examples.com/storage/fe7d3a0d44631509da1f416/2017/11/file_example_MP3_700KB.mp3';
    //                 $time=time();
    //                 $img = public_path().'/uploads/video/audio_f/'.$time.'.mp3';

    //                // file_put_contents($img, file_get_contents($line[5]));
    //                 $this->download($line[5],$img);
    //                  if($status=='OK'){
    //                     $video->audio_f = 'uploads/video/audio_f/'.$time.'.mp3';
    //                 }else{
    //                      return redirect()->route('video.index')->withStatus(__($status));
    //                 }
                   
    //             }
    //             else{
    //                 return redirect()->route('video.index')->withStatus(__('Audio Female is required'));
    //             }
               
    //             $video->save();

    //             for($counter=6;$counter<count($line);$counter++){
    //                 if($line[$counter]!=''){
    //                     $video_text = new VideoText;
    //                     $video_text->video_id = $video->id;
    //                     $video_text->text = $line[$counter];
    //                     $video_text->save();
    //                 }
    //             }
    //         }
    //         return redirect()->route('video.index')->withStatus(__('Bulk Upload successfully.'));
    //     }

       
    // }

    public function bulkstore(Request $request){
        //    /dd($request);
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
                // dd($csv_data);
                foreach($csv_data as $key=>$line){
                    $video = new Video;
                    // dd($line);
                    // echo $num = count($line);die;
                    if($line[0]!=''){
                       
                      
                        $video->section = $line[0];
                        $video->template_type = $line[1];
                        $video->primary_language = $line[2];
                        $video->secondary_language = $line[3];
                        $video->category = $line[4];
                        $video->subcategory = $line[5];
                        $video->image = $line[6];
                        $image_array = array_reverse(explode('.',$line[6]));
                     
                        $extension = $image_array[0];
                        $original_name =$image_array[1];
                        $thumbnail_600_500 = $original_name."_thumbnail_600_500.".$extension;//time()."_600_500.".$extension;
                        $thumbnail_289_289 = $original_name."_thumbnail_289_289.".$extension;//time()."_289_289.".$extension;
                        $thumbnail_400_264 = $original_name."_thumbnail_400_264.".$extension;//time()."_400_264.".$extension;
                        $thumbnail_400_320 = $original_name."_thumbnail_400_320.".$extension;//time()."_400_320.".$extension;
                        
                        // Image::make(public_path($line[6]))->resize(600, 500)->save(public_path('uploads/video/thumbnail_image/'.$thumbnail_600_500));
                        // Image::make(public_path($line[6]))->resize(289, 289)->save(public_path('uploads/video/thumbnail_image/'.$thumbnail_289_289));
                        // Image::make(public_path($line[6]))->resize(400, 264)->save(public_path('uploads/video/thumbnail_image/'.$thumbnail_400_264)); 
                        // Image::make(public_path($line[6]))->resize(400, 320)->save(public_path('uploads/video/thumbnail_image/'.$thumbnail_400_320));
                      
                        
                        // $video->thumbnail_600_500 = 'uploads/video/thumbnail_image/'.$thumbnail_600_500;
                        // $video->thumbnail_400_264 = 'uploads/video/thumbnail_image/'.$thumbnail_400_264;
                        // $video->thumbnail_289_289 = 'uploads/video/thumbnail_image/'.$thumbnail_289_289;
                        // $video->thumbnail_400_320 = 'uploads/video/thumbnail_image/'.$thumbnail_400_320;
                        
                        Image::make(public_path($line[6]))->resize(600, 500)->save(public_path($thumbnail_600_500));
                        Image::make(public_path($line[6]))->resize(289, 289)->save(public_path($thumbnail_289_289));
                        Image::make(public_path($line[6]))->resize(400, 264)->save(public_path($thumbnail_400_264)); 
                        Image::make(public_path($line[6]))->resize(400, 320)->save(public_path($thumbnail_400_320));
                      
                        
                        $video->thumbnail_600_500 = $thumbnail_600_500;
                        $video->thumbnail_400_264 = $thumbnail_400_264;
                        $video->thumbnail_289_289 = $thumbnail_289_289;
                        $video->thumbnail_400_320 =$thumbnail_400_320;
                       
                        $video->audio_m = $line[7];
                        $video->audio_m1 = $line[8];
                        $video->audio_m2 = $line[9];
                        $video->audio_m3 = $line[10];
                        $video->audio_m4 = $line[11];
                        $video->audio_f = $line[12];
                        $video->audio_f1 = $line[13];
                        $video->audio_f2 = $line[14]; 
                        $video->audio_f3 = $line[15];
                        $video->audio_f4 = $line[16];
                         $video->audio_m1_long = $line[17];
                         $video->audio_m2_long = $line[18];
                         $video->audio_m3_long = $line[19];
                         $video->audio_m4_long = $line[20];
                         $video->audio_m5_long = $line[21];
                          $video->audio_f1_long = $line[22];
                         $video->audio_f2_long = $line[23];
                         $video->audio_f3_long = $line[24];
                         $video->audio_f4_long = $line[25];
                         $video->audio_f5_long = $line[26];
                          // if($line[6]!=''){
                       
                        //     $time=time();
                        //     $img = public_path().'/uploads/video/image/'.$time.'.jpg';
                            
                        //   $status= $this->download($line[6],$img);
                            
                           
                        //     if($status=='OK'){ 
                        //         $extension='.jpg';
                        //         $fileNameToStore = $time.'.jpg';
                        //         $video->image = 'uploads/video/image/'.$fileNameToStore;
                        //         try { 
                        //             $video->thumbnail_600_500 = 'uploads/video/thumbnail_image/'.$fileNameToStore;
                        //             $thumbnail_289_289 = time()."_289_289.".$extension;
                        //             $video->thumbnail_289_289 = 'uploads/video/thumbnail_image/'.$thumbnail_289_289;
                        //             Image::make(public_path('uploads/video/image/'.$fileNameToStore))->resize(600, 500)->save(public_path('uploads/video/thumbnail_image/'.$fileNameToStore));
                        //             Image::make(public_path('uploads/video/image/'.$fileNameToStore))->resize(289, 289)->save(public_path('uploads/video/thumbnail_image/'.$thumbnail_289_289));
                                    
                        //             $thumbnail_400_264 = 'uploads/video/thumbnail_image/'.time().$extension;
                        //              $video->thumbnail_400_264 = $thumbnail_400_264;
                        //             Image::make(public_path('uploads/video/image/'.$fileNameToStore))->resize(400, 264)->save(public_path($thumbnail_400_264));
                        //         }
                        //         catch(\Exception $e){
                        //             return redirect()->route('video.index')->withStatus(__($e->getMessage()."Line No- ".++$key));
                        //         }
                        //         catch (\Throwable  $e) {
                                
                        //              return redirect()->route('video.index')->withStatus(__($e->getMessage()."Line No- ".++$key));
                        //         }
                              
                            
                        //     }else{
                        //          return redirect()->route('video.index')->withStatus(__($status));
                        //     }
                            
                        // }
                        // else{
                            
                        //     return redirect()->route('video.index')->withStatus(__('Image is required'));
                        // }
                        // if($line[6]!=''){
                       
                        //     $time=time();
                        //     $img = public_path().'/uploads/video/image/'.$time.'.jpg';
                            
                        //   $status= $this->download($line[6],$img);
                            
                           
                        //     if($status=='OK'){ 
                        //         $extension='.jpg';
                        //         $fileNameToStore = $time.'.jpg';
                        //         $video->image = 'uploads/video/image/'.$fileNameToStore;
                        //         try { 
                        //             $video->thumbnail_600_500 = 'uploads/video/thumbnail_image/'.$fileNameToStore;
                        //             $thumbnail_289_289 = time()."_289_289.".$extension;
                        //             $video->thumbnail_289_289 = 'uploads/video/thumbnail_image/'.$thumbnail_289_289;
                        //             Image::make(public_path('uploads/video/image/'.$fileNameToStore))->resize(600, 500)->save(public_path('uploads/video/thumbnail_image/'.$fileNameToStore));
                        //             Image::make(public_path('uploads/video/image/'.$fileNameToStore))->resize(289, 289)->save(public_path('uploads/video/thumbnail_image/'.$thumbnail_289_289));
                                    
                        //             $thumbnail_400_264 = 'uploads/video/thumbnail_image/'.time().$extension;
                        //              $video->thumbnail_400_264 = $thumbnail_400_264;
                        //             Image::make(public_path('uploads/video/image/'.$fileNameToStore))->resize(400, 264)->save(public_path($thumbnail_400_264));
                        //         }
                        //         catch(\Exception $e){
                        //             return redirect()->route('video.index')->withStatus(__($e->getMessage()."Line No- ".++$key));
                        //         }
                        //         catch (\Throwable  $e) {
                                
                        //              return redirect()->route('video.index')->withStatus(__($e->getMessage()."Line No- ".++$key));
                        //         }
                              
                            
                        //     }else{
                        //          return redirect()->route('video.index')->withStatus(__($status));
                        //     }
                            
                        // }
                        // else{
                            
                        //     return redirect()->route('video.index')->withStatus(__('Image is required'));
                        // }
                      
                    
                        // if($line[7]!=''){
                        //   // $path= 'https://file-examples.com/storage/fe7d3a0d44631509da1f416/2017/11/file_example_MP3_700KB.mp3';
                        //     $time=time();
                        //     $img = public_path().'/uploads/video/audio_m/'.$time.'.mp3';
        
                        //     //file_put_contents($img, file_get_contents($line[4]));
                        //     $status=$this->download($line[7],$img);
                        //     if($status=='OK'){
                        //       $video->audio_m = 'uploads/video/audio_m/'.$time.'.mp3';
                        //     }else{
                        //          return redirect()->route('video.index')->withStatus(__($status));
                        //     }
                            
                          
                        // }else{
                        //      return redirect()->route('video.index')->withStatus(__('Audio Male Or  Audio Female is required'));
                        // }
                        
                        // if($line[8]!=''){
                        //     //$path= 'https://file-examples.com/storage/fe7d3a0d44631509da1f416/2017/11/file_example_MP3_700KB.mp3';
                        //     $time=time();
                        //     $img = public_path().'/uploads/video/audio_f/'.$time.'.mp3';
        
                        //   // file_put_contents($img, file_get_contents($line[5]));
                        //     $status=$this->download($line[8],$img);
                        //      if($status=='OK'){
                        //          $video->audio_f = 'uploads/video/audio_f/'.$time.'.mp3';
                        //     }else{
                        //          return redirect()->route('video.index')->withStatus(__($status));
                        //     }
                           
                        // }
                        // else{
                        //     return redirect()->route('video.index')->withStatus(__('Audio Male Or  Audio Female is required'));
                        // }
                      
                       // dd($video);
                        $video->save();
    
                        for($counter=27;$counter<count($line);$counter++){
                            if($line[$counter]!=''){
                               
                                 $textresult= VideoText::where('text','=',$line[$counter])->first();
                                
                                if($textresult==null){
                                    $video_text = new VideoText;
                                    //$video_text->video_id = $video->id;
                                    $video_text->text = $line[$counter];
                                    $video_text->save();
                                    
                                    $video_text_mapping = new VideoTextMapping;
                                    $video_text_mapping->video_id = $video->id;
                                    $video_text_mapping->text_id = $video_text->id;
                                    $video_text_mapping->save();
                                }
                                else{
                                    
                                    $video_text_mapping = new VideoTextMapping;
                                    $video_text_mapping->video_id = $video->id;
                                    $video_text_mapping->text_id = $textresult->id;
                                    $video_text_mapping->save();
                                }
                            }
                        }
                    }
                }
                return redirect()->route('video.index')->withStatus(__('Bulk Upload successfully.'));
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
        $categoryList =Categories::where('parent_id','=',0)->where('status','=',1)->get();
        $sectionList =Section::where('status','=',1)->get();
        $templateTypeList =TemplateType::where('status','=',1)->get();
        $primaryLanguageList =Languages::where('parent_id','=',0)->where('status','=',1)->get();
        return view('video.makesampledownload',compact('categoryList','sectionList','templateTypeList','primaryLanguageList'));
    }
    
    public function makeSample(Request $request)
    {
       
        $request->validate([
            'section' => ['required'],
            'template_type' => ['required'],
            'primary_language' => ['required'],
            'secondary_language' => ['required'],
            'category' => ['required'],
            'subcategory' => ['required'],
        ]);
        
        
        $subcategory = Categories::findorfail($request->subcategory);
        $file_name = $subcategory->name.'.xlsx';
        // Excel file name for download 
        $fileName = "samplevideo_".date('Y-m-d'). "_.xlsx"; 
        
       
        
      
        
        // Column names 
        $fields = array('Video Type', 'Template Type', 'Primary Language', 'Secondary Language', 'Category', 'Subcategory','Image','Male1 (Short)','Male2 (Short)','Male3 (Short)','Male4 (Short)','Male5 (Short)','Female1 (Short)','Female2 (Short)','Female3 (Short)','Female4 (Short)','Female5 (Short)','Male1 (Long)','Male2 (Long)','Male3 (Long)','Male4 (Long)','Male5 (Long)','Female1 (Long)','Female2 (Long)','Female3 (Long)','Female4 (Long)','Female5 (Long)');
        $values = array($request->section, $request->template_type, $request->primary_language, $request->secondary_language,$request->category, $request->subcategory,'https://readyvids.manageprojects.in/public/uploads/video/image/1668507503.jpg','https://readyvids.manageprojects.in/public/uploads/video/audio_m/1668171961.mp3','https://readyvids.manageprojects.in/public/uploads/video/audio_f/1668171961.mp3','https://readyvids.manageprojects.in/public/uploads/video/audio_m/1668171961.mp3','https://readyvids.manageprojects.in/public/uploads/video/audio_f/1668171961.mp3','https://readyvids.manageprojects.in/public/uploads/video/audio_m/1668171961.mp3','https://readyvids.manageprojects.in/public/uploads/video/audio_f/1668171961.mp3','https://readyvids.manageprojects.in/public/uploads/video/audio_m/1668171961.mp3','https://readyvids.manageprojects.in/public/uploads/video/audio_f/1668171961.mp3','https://readyvids.manageprojects.in/public/uploads/video/audio_m/1668171961.mp3','https://readyvids.manageprojects.in/public/uploads/video/audio_f/1668171961.mp3','https://readyvids.manageprojects.in/public/uploads/video/audio_m/1668171961.mp3','https://readyvids.manageprojects.in/public/uploads/video/audio_f/1668171961.mp3','https://readyvids.manageprojects.in/public/uploads/video/audio_m/1668171961.mp3','https://readyvids.manageprojects.in/public/uploads/video/audio_f/1668171961.mp3','https://readyvids.manageprojects.in/public/uploads/video/audio_m/1668171961.mp3','https://readyvids.manageprojects.in/public/uploads/video/audio_f/1668171961.mp3','https://readyvids.manageprojects.in/public/uploads/video/audio_m/1668171961.mp3','https://readyvids.manageprojects.in/public/uploads/video/audio_f/1668171961.mp3','https://readyvids.manageprojects.in/public/uploads/video/audio_m/1668171961.mp3','https://readyvids.manageprojects.in/public/uploads/video/audio_f/1668171961.mp3');     
        $templateType= TemplateType::findorfail($request->template_type);
        $type= $templateType->type;
        $type_array = explode(' ',$type);
       // dd($type_array);
        for($counter=1;$counter<=$type_array[0];$counter++){
            array_push($fields,"Text".$counter);
            array_push($values,"Test".$counter);
        }
      
        return Excel::download(new VideoExport(array_values($fields),array_values($values)), $file_name);
        // Display column names as first row 
        $excelData = implode("\t", array_values($fields)) . "\n"; 
        $excelData .= implode("\t", array_values($values)) . "\n"; 
      
      // Headers for download 
        header("Content-Type: application/vnd.ms-excel"); 
        header("Content-Disposition: attachment; filename=\"$fileName\""); 
        
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
        
        if(isset($_GET['subcategory_id']) && $_GET['subcategory_id']!=''){
            
            $subcategory = Categories::findorfail($request->subcategory_id);
            
            $file_name = $subcategory->name.'.xlsx';
        }else{
            $file_name = 'video.xlsx';
        }
        
       
        return Excel::download(new VideoFullExport, $file_name);
    }
    
}

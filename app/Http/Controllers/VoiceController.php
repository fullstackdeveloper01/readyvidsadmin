<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\VideoVoiceNew;
use App\VideoVoice;
use App\Voice;
use App\Folders;
use App\Exports\VideoVoiceExport;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\VideoImport;
class VoiceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    protected $imagePath="/uploads/video_voice/";
    public function index()
    {
        $voices=VideoVoice::select('*');
        
        if(!empty($_GET['folder_id'])){
            $voices=$voices->where('folder_id','=',$_GET['folder_id']);
        }
        $voices=$voices->paginate(15);
  
        $folders= Folders::where('type','=','voice')->where('status','=',1)->get();
        return view('voice.index', ['voices' =>$voices,'folders'=>$folders]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
       $folders= Folders::where('type','=','voice')->where('status','=',1)->get();
        return view('voice.create',['title'=>'Upload Multiple Voice','folders'=>$folders]);
    }
    
    
   
    
    public function store(Request $request)
    {
        
       
        $validatedData = $request->validate([
        'folder_id' => 'required',
        'video_size' => 'required',
        'voice_type' => 'required',
        'files' => 'required',
        'voice_name'=>'required'
        ]);
        
        if($request->video_size=='long'){
            $request->voice_type .='_long';
        }
        
         if ($request->hasFile('voice_name')) {
        
            $extension = $request->file('voice_name')->getClientOriginalExtension();
            // Filename To store
            $fileNameToStore = time().'.'.$extension;

            $request->voice_name->move(public_path('uploads/video/bulk_upload'), $fileNameToStore);
            $path = public_path().'/uploads/video/bulk_upload/'.$fileNameToStore;

            $data = Excel::toArray(new VideoImport(), $path);
            array_shift($data[0]);
              
            $csv_data=$data[0];
        

            //$csv_data = array_map('str_getcsv', file( $path));
            //array_shift($csv_data);
            // dd($csv_data);
            // foreach($csv_data as $key=>$line){
               
            //     $voice = new Voice;
            // }
         }
        
        $destinationPath = public_path('uploads/video/audio/'.$request->folder_id.'/'.$request->video_size.'/'.$request->voice_type);   
        if( !is_dir( $destinationPath ) ) mkdir( $destinationPath, 0755, true );
        
        
        if($request->hasfile('files'))
         {
            foreach($request->file('files') as $key => $file)
            {
                //$path = $file->store('public/files');
                $name = $file->getClientOriginalName(); 
                
                // $extension = $file->getClientOriginalExtension();
                // $file_name= 'voice_'.time().'.'.$extension;
                $path =$file->move(public_path('uploads/video/audio/'.$request->folder_id.'/'.$request->video_size.'/'.$request->voice_type.'/'), $name);
                $insert['name'] = $csv_data[$key][0];
               
                
                
                $insert[$request->voice_type] = 'uploads/video/audio/'.$request->folder_id.'/'.$request->video_size.'/'.$request->voice_type.'/'.$name;
                //$insert[$key]['relative_voice_path'] = 'uploads/video/audio/'.$request->voice_type.'/'.$name;
                $insert['folder_id'] =$request->folder_id;
                //$insert[$key]['path'] = env("APP_URL"). 'public/uploads/video/audio/'.$request->voice_type.'/'.$name;
                $row= VideoVoice::where('folder_id','=',$request->folder_id)->where('name','=',$csv_data[$key][0])->first();
               
                if($row!=null){
                   
                   VideoVoice::where('id','=',$row->id)->update([$request->voice_type=>'uploads/video/audio/'.$request->folder_id.'/'.$request->video_size.'/'.$request->voice_type.'/'.$name]);
                }
                else{
                 // dd($insert);
                     VideoVoice::insert($insert);
                }  
            }
         }
        
       
        
        return redirect()->back()->with(['status'=> 'Multiple File has been uploaded Successfully.','video_size'=>$request->video_size,'folder_id'=>$request->folder_id,'voice_type'=>$request->voice_type]);
     
        
        //return redirect()->route('voice.index')->withStatus(__('Multiple File has been uploaded Successfully.'));
        
      
 
    }
    
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    // public function store(Request $request)
    // {
    //     $request->validate([
    //         'name' => ['required'],
    //         'voice_file' => ['required'],
    //     ]);
    //     $image = new Images;
    //     $image->name = $request->name; 
    //     $image->folder_id = $request->folder_id;
    //     if ($request->hasFile('image_file')) {
          
    //         $extension = $request->file('image_file')->getClientOriginalExtension();
    //         // Filename To store
    //         $fileNameToStore = time().'.'.$extension;

    //         $request->image_file->move(public_path('uploads/video_images'), $fileNameToStore);
    //     }
    //     else {
    //         $fileNameToStore = 'No-image.jpeg';
    //     }
    //     $image->path = env('APP_URL')."uploads/video_images/".$fileNameToStore;
    //     $image->relative_image_path = "uploads/video_images/".$fileNameToStore;
      
    //     $image->save();
    //     return redirect()->route('image.index')->withStatus(__('Image upload successfully .'));
    // }

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
        $folders= Folders::where('status','=',1)->get();
        return view('image.edit', ['title'=>'Edit Image','image' =>Images::where(['id'=>$id])->first(),'folders'=>$folders]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request,Images $image)
    {      
        $request->validate([
            'name' => ['required'],
            //'image_file' => ['required'],
        ]);
        
        $image->name = $request->name;
        $image->folder_id = $request->folder_id;
        if ($request->hasFile('image_file')) {
          
            $extension = $request->file('image_file')->getClientOriginalExtension();
            // Filename To store
            $fileNameToStore = time().'.'.$extension;

            $request->image_file->move(public_path('uploads/video_images'), $fileNameToStore); 
            $image->path = env('APP_URL')."uploads/video_images/".$fileNameToStore;
            $image->relative_image_path = "uploads/video_images/".$fileNameToStore;
        }
        // else {
        //     $fileNameToStore = 'No-image.jpeg';
        // }
       
      
        $image->update();
        return redirect()->route('image.index')->withStatus(__('Image updated successfully .'));

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(VideoVoice $voice)
    {   
        //dd($voice);
        $path = public_path()."/".$voice->audio_m1;
        unlink($path);
        $path = public_path()."/".$voice->audio_m2;
        unlink($path);
        $path = public_path()."/".$voice->audio_m3;
        unlink($path);
        $path = public_path()."/".$voice->audio_m4;
        unlink($path);
        $path = public_path()."/".$voice->audio_m5;
        unlink($path);
        $path = public_path()."/".$voice->audio_f1;
        unlink($path);
        $path = public_path()."/".$voice->audio_f2;
        unlink($path);
        $path = public_path()."/".$voice->audio_f3;
        unlink($path);
        $path = public_path()."/".$voice->audio_f4;
        unlink($path);
        $path = public_path()."/".$voice->audio_f5;
        unlink($path);
        $path = public_path()."/".$voice->audio_m1_long;
        unlink($path);
        $path = public_path()."/".$voice->audio_m2_long;
        unlink($path);
        $path = public_path()."/".$voice->audio_m3_long;
        unlink($path);
        $path = public_path()."/".$voice->audio_m4_long;
        unlink($path);
        $path = public_path()."/".$voice->audio_m5_long;
        unlink($path);
        $path = public_path()."/".$voice->audio_f1_long;
        unlink($path);
        $path = public_path()."/".$voice->audio_f2_long;
        unlink($path);
        $path = public_path()."/".$voice->audio_f3_long;
        unlink($path);
        $path = public_path()."/".$voice->audio_f4_long;
        unlink($path);
        $path = public_path()."/".$voice->audio_f5_long;
        unlink($path);
        $voice->delete();
        return redirect()->route('voice.index')->withStatus(__('Voice successfully deleted.'));
       
    }
    
    public function status($id,$status)
    {  
        $image = image::findorfail($id);
        if( $image->status==1){
            $image->status=0;
             image::where('parent_id', '=', $id)->update(['status'=>'0']);
        }else{
            $image->status=1;
             image::where('parent_id', '=', $id)->update(['status'=>'1']);
        }
        //$image->status=$status;
        $image->update();

       // image::where('parent_id', '=', $id)->update(['status'=>$status]);
        echo true;
        
    }
    public function getVoiceDownload(){
       
        $file= public_path(). "/voice.csv";

        $headers = array(
                'Content-Type: application/csv',
                );

        return response()->download($file);
    }
    public function bulk_upload(){
        $folders= Folders::where('type','=','voice')->where('status','=',1)->get();
        return view('voice.bulk_upload',['folders'=>$folders]);
    }
    
    public function bulkstore(Request $request){
        $request->validate([
            'bulkupload' => ['required'],
        ]);
    
    
        if ($request->hasFile('bulkupload')) {
        
            $extension = $request->file('bulkupload')->getClientOriginalExtension();
            // Filename To store
            $fileNameToStore = time().'.'.$extension;

            $request->bulkupload->move(public_path('uploads/video/bulk_upload'), $fileNameToStore);
            $path = public_path().'/uploads/video/bulk_upload/'.$fileNameToStore;



            $csv_data = array_map('str_getcsv', file( $path));
            array_shift($csv_data);
            foreach($csv_data as $key=>$line){
               
                $voice = new Voice;
                $voice->folder_id = $request->folder_id;
                $voice->name = $line[0];
                $voice->audio_m1 = $line[1];
                $voice->audio_m2 = $line[2];
                $voice->audio_m3 = $line[3];
                $voice->audio_m4 = $line[4];
                $voice->audio_m5 = $line[5];
                $voice->audio_f1 = $line[6];
                $voice->audio_f2 = $line[7];
                $voice->audio_f3 = $line[8];
                $voice->audio_f4 = $line[9];
                $voice->audio_f5 = $line[10];
                $voice->audio_m1_long = $line[11];
                $voice->audio_m2_long = $line[12];
                $voice->audio_m3_long = $line[13];
                $voice->audio_m4_long = $line[14];
                $voice->audio_m5_long = $line[15];
                $voice->audio_f1_long = $line[16];
                $voice->audio_f2_long = $line[17];
                $voice->audio_f3_long = $line[18];
                $voice->audio_f4_long = $line[19];
                $voice->audio_f5_long = $line[20];
                $voice->save();

            }
            return redirect()->route('voice.index')->withStatus(__('Bulk Upload successfully.'));
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
        //dd($response);
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
    public function makeFolder(Request $request)
    {
        $request->validate([
            'name' => ['required','unique:folders,folder_name'],
        ]);
        $folder = new Folders;
        $folder->folder_name = $request->name;
        $folder->type = 'voice';
        $folder->save();
        return redirect()->route('voice.index')->withStatus(__('Folder make successfully .'));
    }
    
    public function export(Request $request){
        
        if(isset($_GET['folder_id']) && $_GET['folder_id']!=''){
            $folder = Folders::findorfail($request->folder_id);
            
            $file_name = $folder->folder_name.'.xlsx';
        }else{
            $file_name = 'voice.xlsx';
        }
        
        return Excel::download(new VideoVoiceExport, $file_name);
    }
    
    public function updateFolder(Request $request)
    {
        $request->validate([
            'name' => ['required','unique:folders,folder_name,id'],
        ]);
        $folder = Folders::findorfail($request->edit_folder_id);
        $folder->folder_name = $request->name;
       
        $folder->update();
        return redirect()->route('voice.index')->withStatus(__('Folder update successfully .'));
    }
    
    public function deleteFolder(Request $request)
    {
       
        VideoVoice::where('folder_id','=',$request->folder_id)->delete();
        $folder = Folders::where('id','=',$request->folder_id)->delete();
       
        return redirect()->route('voice.index')->withStatus(__('Folder deleted successfully .'));
    }


}

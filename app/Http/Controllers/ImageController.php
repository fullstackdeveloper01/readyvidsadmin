<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Images;
use App\Folders;
use App\Exports\ImagesExport;
use Maatwebsite\Excel\Facades\Excel;
class ImageController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    protected $imagePath="/uploads/video_image/";
    public function index()
    {
        $images=Images::select('*');
        
        if(!empty($_GET['folder_id'])){
            $images=$images->where('folder_id','=',$_GET['folder_id']);
        }
        $images=$images->paginate(15);
        $folders= Folders::where('type','=','image')->where('status','=',1)->get();
        return view('image.index', ['images' =>$images,'folders'=>$folders]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
       $folders= Folders::where('status','=',1)->get();
       return view('image.create',['title'=>'Add Image','folders'=>$folders]);
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
            'name' => ['required'],
            'image_file' => ['required'],
        ]);
        $image = new Images;
        $image->name = $request->name; 
        $image->folder_id = $request->folder_id;
        if ($request->hasFile('image_file')) {
          
            $extension = $request->file('image_file')->getClientOriginalExtension();
            // Filename To store
            $fileNameToStore = time().'.'.$extension;

            $request->image_file->move(public_path('uploads/video_images/'.$request->folder_id.'/'), $fileNameToStore);
        }
        else {
            $fileNameToStore = 'No-image.jpeg';
        }
        $image->path = env('APP_URL')."public/uploads/video_images/".$request->folder_id.'/'.$fileNameToStore;
        $image->relative_image_path = "uploads/video_images/".$request->folder_id.'/'.$fileNameToStore;

        $image->save();
        return redirect()->route('image.index')->withStatus(__('Image upload successfully .'));
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
    public function destroy(Images $image)
    {
        $path = public_path()."/".$image->relative_image_path;
        unlink($path);
        $image->delete();
        return redirect()->route('image.index')->withStatus(__('Image successfully deleted.'));
       
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
    public function getImageDownload(){
       
        $file= public_path(). "/image.csv";

        $headers = array(
                'Content-Type: application/csv',
                );

        return response()->download($file);
    }
    public function bulk_upload(){
        $folders= Folders::where('type','=','image')->where('status','=',1)->get();
        return view('image.bulk_upload',['folders'=>$folders]);
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
                $image = new Images;
                 $image->folder_id = $request->folder_id;
                $image->name = $line[0];
                if($line[1]!=''){
                    //$path= 'https://html.manageprojects.in/readyvids/public/uploads/video/image/1662555714.jpg';
                    $time=time();
                    $img = public_path().'/uploads/video_images/'.$request->folder_id.'/';
                    //$img = env("APP_URL").'/uploads/video_image/'.$time.'.jpg';
                    $path = $time.'.jpg';
                    $status= $this->download($line[1],$img,$path);
                    // echo $status;die;
                    if($status=='OK'){
                        $image->relative_image_path = 'uploads/video_images/'.$request->folder_id.'/'.$time.'.jpg';  
                        $image->path =env("APP_URL"). 'public/uploads/video_images/'.$request->folder_id.'/'.$time.'.jpg'; 
                    }else{
                            return redirect()->route('image.index')->withStatus(__($status));
                    }
                    
                }
                else{
                    return redirect()->route('image.index')->withStatus(__('Image is required'));
                }
                
              
                $image->save();

            }
            return redirect()->route('image.index')->withStatus(__('Bulk Upload successfully.'));
        }

        
    }
    
    public function download($source,$destination,$path){
     
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
      
        if( !is_dir( $destination ) ) mkdir( $destination, 0755, true );
       
        $fh = fopen($destination.$path, "w") or die("ERROR opening " . $destination.$path);

        file_put_contents($destination.$path, $response);
        curl_close($curl);
       
        if(file_exists($destination.$path)) { 
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
        $folder->type = 'image';
        $folder->save();
        return redirect()->route('image.index')->withStatus(__('Folder make successfully .'));
    }
    
    public function export(Request $request){
        
        if(isset($_GET['folder_id']) && $_GET['folder_id']!=''){
            $folder = Folders::findorfail($request->folder_id);
            
            $file_name = $folder->folder_name.'.xlsx';
        }else{
            $file_name = 'image.xlsx';
        }
        return Excel::download(new ImagesExport,  $file_name);
    }
    public function filesUpload()
    {   
        $folders= Folders::where('type','=','image')->where('status','=',1)->get();
        return view('image.multiple-files-upload',['title'=>'Upload Multiple Image','folders'=>$folders]);
        
    }
    
    public function storeMultipleFile(Request $request)
    {
        
         
        $validatedData = $request->validate([
        'files' => 'required',
        'files.*' => 'mimes:jpeg,png,jpg'
        ]);
 
        $destinationPath = public_path('uploads/video_images/'.$request->folder_id);   
        if( !is_dir( $destinationPath ) ) mkdir( $destinationPath, 0755, true );
                            
                            
        if($request->hasfile('files'))
         {
            foreach($request->file('files') as $key => $file)
            {
                //$path = $file->store('public/files');
                $name = $file->getClientOriginalName(); 
                $name = str_replace(".","",$name);
                $extension = $file->getClientOriginalExtension();
                $file_name= $name.'_'.time().'.'.$extension;
                $path =$file->move(public_path('uploads/video_images/'.$request->folder_id), $file_name);
                $insert[$key]['name'] = $name;
                $insert[$key]['relative_image_path'] = 'uploads/video_images/'.$request->folder_id.'/'.$file_name;
                $insert[$key]['folder_id'] =$request->folder_id;
                $insert[$key]['path'] = env("APP_URL"). 'public/uploads/video_images/'.$request->folder_id.'/'.$file_name;
                  
            }
         }

        Images::insert($insert);
        
        return redirect()->route('image.index')->withStatus(__('Multiple File has been uploaded Successfully.'));
        
      
 
    }
    
    public function updateFolder(Request $request)
    {
        $request->validate([
            'name' => ['required','unique:folders,folder_name,id'],
        ]);
        $folder = Folders::findorfail($request->edit_folder_id);
        $folder->folder_name = $request->name;
       
        $folder->update();
        return redirect()->route('image.index')->withStatus(__('Folder update successfully .'));
    }
     public function deleteFolder(Request $request)
    {
       
        Images::where('folder_id','=',$request->folder_id)->delete();
        $folder = Folders::where('id','=',$request->folder_id)->delete();
       
        return redirect()->route('image.index')->withStatus(__('Folder deleted successfully .'));
    }


}

<?php



namespace App\Http\Controllers;



use App\VideoSize;

use Illuminate\Http\Request;

use Spatie\Permission\Models\Role;

use Illuminate\Support\Facades\Hash;

use Carbon\Carbon;

use Illuminate\Support\Facades\DB;

use Illuminate\Support\Str;

use App\Notifications\DriverCreated;

use Validator;



class VideoSizeController extends Controller

{

    /**

     * Display a listing of the resource.

     *

     * @return \Illuminate\Http\Response

     */

    public function index()

    {

        return view('video_size.index', ['video_size' =>VideoSize::orderBy('id','desc')->paginate(10)]);

    }



    /**

     * Show the form for creating a new resource.

     *

     * @return \Illuminate\Http\Response

     */

    public function create()

    {

        return view('video_size.create',['title'=>'Add Video Size']);

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

            'name' => ['required', 'unique:video_size'],
            'description' => ['required'],
             'display_video' => ['required'],
            'question_display_time' => ['required'],
            'answer_display_time' => ['required'],
            'icon'=>['required']

        ]);

        $video_size = new VideoSize;

        $video_size->name = $request->name;

        $video_size->description = $request->description;
         $video_size->display_video = $request->display_video;
        $video_size->question_display_time = $request->question_display_time;
        $video_size->answer_display_time = $request->answer_display_time;
        if ($request->hasFile('icon')) {
          
            $extension = $request->file('icon')->getClientOriginalExtension();
            // Filename To store
            $fileNameToStore =time().'.'.$extension;

            $request->icon->move(public_path('uploads/videosize/'), $fileNameToStore);
            $video_size->image ='uploads/videosize/'.$fileNameToStore;
        }
        $video_size->save();

        return redirect()->route('video_size.index')->withStatus(__('Video Size successfully created.'));

    }



    /**

     * Display the specified resource.

     *

     * @param  \App\Driver  $driver

     * @return \Illuminate\Http\Response

     */

    public function show(VideoSize $video_size)

    {

        return view('video_size.show', compact('video_size'));

    }



    /**

     * Show the form for editing the specified resource.

     *

     * @param  \App\Driver  $driver

     * @return \Illuminate\Http\Response

     */

    public function edit(VideoSize $video_size)

    {

        return view('video_size.edit', compact('video_size'));

    }



    /**

     * Update the specified resource in storage.

     *

     * @param  \Illuminate\Http\Request  $request

     * @param  \App\Driver  $driver

     * @return \Illuminate\Http\Response

     */

    public function update(Request $request, VideoSize $video_size)

    {

        $request->validate([

            'name' => ['required'],
            'description' => ['required'],
            'display_video' => ['required'],
            'question_display_time' => ['required'],
            'answer_display_time' => ['required'],

        ]);

        

        $video_size->name = $request->name;

        $video_size->description = $request->description;
           $video_size->display_video = $request->display_video;
        $video_size->question_display_time = $request->question_display_time;
        $video_size->answer_display_time = $request->answer_display_time;
        if ($request->hasFile('icon')) {
          
            $extension = $request->file('icon')->getClientOriginalExtension();
            // Filename To store
            $fileNameToStore =time().'.'.$extension;

            $request->icon->move(public_path('uploads/videosize/'), $fileNameToStore);
            $video_size->image ='uploads/videosize/'.$fileNameToStore;
        }
        $video_size->update();



        return redirect()->route('video_size.index')->withStatus(__('Video Size Successfully Updated.'));

    }



    /**

     * Remove the specified resource from storage.

     *

     * @param  \App\Driver  $driver

     * @return \Illuminate\Http\Response

     */

    public function destroy(VideoSize $video_size)

    {  
        $video_size->delete();
        return redirect()->route('video_size.index')->withStatus(__('VideoSize successfully deleted.'));
      
    }

    public function status($id,$status)
    {  
        $video_size = VideoSize::findorfail($id);
        if($video_size->status==1){
            $video_size->status=0;
        }else{
            $video_size->status=1;
        }
       // $ratio->status=$status;
        $video_size->update();

        echo true;
        
    }



}


<?php

namespace App\Http\Controllers;

use App\Gallery;
use App\Notification;
use App\NotificationStatus;
use App\User;
use App\Helpers\Helper;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;
use App\Order;
use App\Status;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use App\Notifications\DriverCreated;

class GalleryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('gallery.index', ['galleries' =>Gallery::orderBy('id','desc')->paginate(15)]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('gallery.create');
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
            'image_type' => ['required'],
            'title' => ['required'],
            'description' => ['required'],
        ]);

        $gallery = new gallery;
        $gallery->image_type = $request->image_type;
        $gallery->title = $request->title;
        $gallery->description = $request->description;

            // print_r($request->all());die;
        if($gallery->image_type == 'Photo')
        {
            // echo 'if';
            if ($request->hasFile('photo_video')) {
                $filenameWithExt = $request->file('photo_video')->getClientOriginalName ();
                // Get Filename
                $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);
                $filename = str_replace(' ', '_', $filename);
                // Get just Extension
                $extension = $request->file('photo_video')->getClientOriginalExtension();
                // Filename To store
                $fileNameToStore = $filename. '_'. time().'.'.$extension;

                $request->photo_video->move(public_path('uploads/gallery'), $fileNameToStore);
                $gallery->photo_video = $fileNameToStore;
                // echo 'if-if';
            }
            else {
                // echo 'if-else';
                $fileNameToStore = 'No-image.jpeg';
            }
            // die;
            $gallery->photo_video = $fileNameToStore;
        }
        else
        {
            if ($request->hasFile('thumbnail')) {
                $filenameWithExt = $request->file('thumbnail')->getClientOriginalName ();
                // Get Filename
                $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);
                $filename = str_replace(' ', '_', $filename);
                // Get just Extension
                $extension = $request->file('thumbnail')->getClientOriginalExtension();
                // Filename To store
                $fileNameToStore = $filename. '_'. time().'.'.$extension;

                $request->thumbnail->move(public_path('uploads/gallery'), $fileNameToStore);
                $gallery->photo_video = $fileNameToStore;
            }
            $gallery->url = $request->photo_video_url;
        }
        $gallery->save();
        $this->send_notification($gallery);
        return redirect()->route('gallery.index')->withStatus(__('gallery successfully created.'));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Driver  $driver
     * @return \Illuminate\Http\Response
     */
    public function show(Gallery $driver)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Driver  $driver
     * @return \Illuminate\Http\Response
     */
    public function edit(Gallery $gallery)
    {
        if(auth()->user()->hasRole('admin')){
            return view('gallery.edit', compact('gallery'));
        }else return redirect()->route('orders.index')->withStatus(__('No Access'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Driver  $driver
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Gallery $gallery)
    {//dd($request);die;
        $request->validate([
            'image_type'=>'required',
            'photo_video'=>'required',
        ]);
        
        $gallery->image_type = $request->image_type;

        if($gallery->image_type == 'Photo')
        {
            if ($request->hasFile('photo_video')) {
                $filenameWithExt = $request->file('photo_video')->getClientOriginalName ();
                // Get Filename
                $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);
                $filename = str_replace(' ', '_', $filename);
                // Get just Extension
                $extension = $request->file('photo_video')->getClientOriginalExtension();
                // Filename To store
                $fileNameToStore = $filename. '_'. time().'.'.$extension;

                $request->photo_video->move(public_path('uploads/gallery'), $fileNameToStore);
                $gallery->photo_video = $fileNameToStore;
            }
        }
        else
        {
            $gallery->photo_video = $request->photo_video_url;
        }

        $gallery->update();
        return redirect()->route('gallery.index')->withStatus(__('gallery successfully updated.'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Driver  $driver
     * @return \Illuminate\Http\Response
     */
    public function destroy(Gallery $gallery)
    {
        $affectedRows = $gallery->delete();
        if($affectedRows)
        {
            if($gallery->image_type == 'Photo'){
                $path = public_path()."/uploads/gallery/".$gallery->photo_video;
                unlink($path);                
            }
        }
        /*
        if($gallery->status==1){
            $gallery->status=0;
            $gallery->update();
            return redirect()->route('gallery.index')->withStatus(__('gallery successfully deactivate.'));
        }else{
            $gallery->status=1;
            $gallery->update();
            return redirect()->route('gallery.index')->withStatus(__('gallery successfully activate.'));
        }*/       
        return redirect()->route('gallery.index')->withStatus(__('gallery successfully activate.'));
    }
    public function getPhoto(){
        
        $all_photo = Gallery::where(['status'=>1,'image_type'=>'Photo'])->orderBy('id','desc')->get();
        return response()->json([
            'data' =>$all_photo,
            'status' => true,
            'errMsg' => ''
        ]);
    }
    public function getVideo(){
        
        $all_video = Gallery::where(['status'=>1,'image_type'=>'Video'])->orderBy('id','desc')->get();
        return response()->json([
            'data' =>$all_video,
            'status' => true,
            'errMsg' => ''
        ]);
    }

    // this function is for send notification
    public function send_notification($gallery){
        $notification = new Notification;
        $notification->type=$gallery->image_type;
        $notification->notifiable_type='New '.$gallery->image_type.' Added';
        $notification->notifiable_id=$gallery->id;
        $notification->data=($gallery->image_type=="Photo")?asset('uploads/gallery/').'/'.$gallery->photo_video:asset('uploads/gallery/').'/'.$gallery->photo_video.','.$gallery->url;
        $notification->save();
        foreach (User::select('id','device_id')->get() as $key => $value) {
            $notificationStatus =new NotificationStatus;
            $notificationStatus->notification_id=$notification->id;
            $notificationStatus->user_id=$value->id;
            $notificationStatus->save();
            if($value->device_id!="" && $value->device_id != null){
                Helper::sendNotification($value->device_id,$gallery->image_type,'New '.$gallery->image_type.' Added','1');
            }
        }
        return true;
    }
}

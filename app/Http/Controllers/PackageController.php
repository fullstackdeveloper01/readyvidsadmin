<?php

namespace App\Http\Controllers;

use App\Package;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use App\Notifications\DriverCreated;

class PackageController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('package.index', ['packages' =>Package::orderBy('id','desc')->paginate(10)]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if(auth()->user()->hasRole('admin')){
            return view('package.create');
        }else return redirect()->route('package.index')->withStatus(__('No Access'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //dd($request);
        //Validate
        $request->validate([
            'package_title' => ['required', 'string', 'max:255'],
            'package_price' => ['required'],
            'package_description' => ['required'],
            'short_video' => ['required'],
            'long_video' => ['required'],
            'video_type' => ['required'],
            'support' => ['required'],
            'company_logo' => ['required'],
            'add_logo' => ['required'],
            'add_intro' => ['required'],
            'add_outro' => ['required'],
            'team_collaboration' => ['required'],
            'short_video_limit' => ['required'],
            'long_video_limit' => ['required'],           
        ]);

        $package = new Package;
        $package->package_title = $request->package_title;
        $package->package_price = $request->package_price;
         $package->package_description = $request->package_description;
        $package->short_video = $request->short_video;
        $package->long_video = $request->long_video;
        $package->video_type = $request->video_type;
        $package->support = $request->support;
        $package->company_logo = $request->company_logo;
         $package->add_logo = $request->add_logo;
        $package->add_intro = $request->add_intro;
        $package->add_outro = $request->add_outro;
        $package->team_collaboration = $request->team_collaboration;
         $package->short_video_limit = $request->short_video_limit;
        $package->long_video_limit = $request->long_video_limit;
        $package->save();
        return redirect()->route('package.index')->withStatus(__('Package successfully created.'));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Driver  $driver
     * @return \Illuminate\Http\Response
     */
    public function show(Package $driver)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Driver  $driver
     * @return \Illuminate\Http\Response
     */
    public function edit(Package $package)
    {
        return view('package.edit', compact('package'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Driver  $driver
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Package $package)
    {
         $request->validate([
            'package_title' => ['required', 'string', 'max:255'],
            'package_price' => ['required'],
            'package_description' => ['required'],
            'short_video' => ['required'],
            'long_video' => ['required'],
            'video_type' => ['required'],
            'support' => ['required'],
            'company_logo' => ['required'],
            'add_logo' => ['required'],
            'add_intro' => ['required'],
            'add_outro' => ['required'],
            'team_collaboration' => ['required'],
            'short_video_limit' => ['required'],
            'long_video_limit' => ['required'],           
        ]);

        
        $package->package_title = $request->package_title;
        $package->package_description = $request->package_description;
        $package->package_price = $request->package_price;
        $package->short_video = $request->short_video;
        $package->long_video = $request->long_video;
        $package->video_type = $request->video_type;
        $package->support = $request->support;
        $package->company_logo = $request->company_logo;
         $package->add_logo = $request->add_logo;
        $package->add_intro = $request->add_intro;
        $package->add_outro = $request->add_outro;
        $package->team_collaboration = $request->team_collaboration;
         $package->short_video_limit = $request->short_video_limit;
        $package->long_video_limit = $request->long_video_limit;
       
        $package->update();
       
        return redirect()->route('package.index')->withStatus(__('Package Successfully Updated.'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Driver  $driver
     * @return \Illuminate\Http\Response
     */
    public function destroy(Package $package)
    {
        $affectedRows =  $package->delete();
        return redirect()->route('package.index')->withStatus(__('Package successfully deleted.'));
        // if($package->active==1){
        //     $package->active=0;
        //     $package->save();
        //     return redirect()->route('package.index')->withStatus(__('Package Successfully Inactive.'));
        // }else{
        //     $package->active=1;
        //     $package->save();
        //     return redirect()->route('package.index')->withStatus(__('Package Successfully Active.'));
        // }
        
    }

   
    public function getPackage(){
        $packages = Package::where('active','=',1)->get();

        if (count($packages)>0) {
            foreach($packages as $key=>$value){
                $value->updated_price=0;
                $value->text_decoration ='choose-plan__item-price fz-16';
                $value->text_decoration_month = 'choose-plan__item-price ';
            }
            $msg  = array('status'=>200,'success' => true,'message' => "Get Package List Successfully",'data'=>$packages);
            echo json_encode($msg);

        }else{
            
            $msg  = array('status'=>200,'success' => false,'message' => "Package List Not Found.",'data'=>$packages);
            echo json_encode($msg);
           
        }
       
    }

    public function status($id,$status)
    {  
        $Package = Package::findorfail($id);
         if($Package->active==1){
            $Package->active=0;
        }else{
            $Package->active=1;
        }
        //$Package->active=$status;
        $Package->update();
        echo true;
        
    }
}

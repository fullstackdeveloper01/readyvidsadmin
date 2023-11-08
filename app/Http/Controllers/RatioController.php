<?php



namespace App\Http\Controllers;



use App\Ratio;

use Illuminate\Http\Request;

use Spatie\Permission\Models\Role;

use Illuminate\Support\Facades\Hash;

use Carbon\Carbon;

use Illuminate\Support\Facades\DB;

use Illuminate\Support\Str;

use App\Notifications\DriverCreated;

use Validator;



class RatioController extends Controller

{

    /**

     * Display a listing of the resource.

     *

     * @return \Illuminate\Http\Response

     */

    public function index()

    {

        return view('ratio.index', ['ratios' =>Ratio::orderBy('id','desc')->paginate(10)]);

    }



    /**

     * Show the form for creating a new resource.

     *

     * @return \Illuminate\Http\Response

     */

    public function create()

    {

        return view('ratio.create',['title'=>'Add Ratio']);

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

            'name' => ['required', 'unique:ratio'],
            'icon'=>['required']

        ]);

        $ratio = new ratio;

        $ratio->name = $request->name;

          
        if ($request->hasFile('icon')) {
           
            $extension = $request->file('icon')->getClientOriginalExtension();
            // Filename To store
            $fileNameToStore = time().'.'.$extension;

            $request->icon->move(public_path('uploads/ratio_icon/'), $fileNameToStore);
            $ratio->icon = 'uploads/ratio_icon/'.$fileNameToStore;
        }
        
        $ratio->save();

        return redirect()->route('ratio.index')->withStatus(__('Ratio successfully created.'));

    }



    /**

     * Display the specified resource.

     *

     * @param  \App\Driver  $driver

     * @return \Illuminate\Http\Response

     */

    public function show(Ratio $ratio)

    {

        return view('ratio.show', compact('ratio'));

    }



    /**

     * Show the form for editing the specified resource.

     *

     * @param  \App\Driver  $driver

     * @return \Illuminate\Http\Response

     */

    public function edit(Ratio $ratio)

    {

        return view('ratio.edit', compact('ratio'));

    }



    /**

     * Update the specified resource in storage.

     *

     * @param  \Illuminate\Http\Request  $request

     * @param  \App\Driver  $driver

     * @return \Illuminate\Http\Response

     */

    public function update(Request $request, ratio $ratio)

    {

        $request->validate([

            'name' => ['required'],

        ]);

      
    

        $ratio->name = $request->name;
      
        if ($request->hasFile('icon')) {
            
            if($ratio->icon != ''){
                $path = public_path().'/uploads/ratio_icon/'.$ratio->icon;
                if(file_exists($path)){
                    unlink($path);  
                }                
            }
            $extension = $request->file('icon')->getClientOriginalExtension();
            // Filename To store
            $fileNameToStore = time().'.'.$extension;

            $request->icon->move(public_path('uploads/ratio_icon/'), $fileNameToStore);
            $ratio->icon = 'uploads/ratio_icon/'.$fileNameToStore;
        }


        $ratio->update();

       

        return redirect()->route('ratio.index')->withStatus(__('Ratio Successfully Updated.'));

    }



    /**

     * Remove the specified resource from storage.

     *

     * @param  \App\Driver  $driver

     * @return \Illuminate\Http\Response

     */

    public function destroy(Ratio $ratio)

    {  
        $ratio->delete();
        return redirect()->route('ratio.index')->withStatus(__('Ratio successfully deleted.'));
        // $ratio = ratio::where(['id'=>$ratio])->first();

        // if($ratio->active == 1){

        //     $ratio->active=0;

        //     $ratio->update();

        //     return redirect()->route('ratio.index')->withStatus(__('ratio Successfully Inactive.'));

        // }else{

        //     $ratio->active=1;

        //     $ratio->update();

        //     return redirect()->route('ratio.index')->withStatus(__('ratio Successfully Active.'));

        // }

        

    }

    public function status($id,$status)
    {  
        $ratio = Ratio::findorfail($id);
        if($ratio->status==1){
            $ratio->status=0;
        }else{
            $ratio->status=1;
        }
       // $ratio->status=$status;
        $ratio->update();

        echo true;
        
    }



}


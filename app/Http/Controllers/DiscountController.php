<?php

namespace App\Http\Controllers;

use App\Discount;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;


class DiscountController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
            return view('discount.index', ['discountlist' =>Discount::where('deleted_at','=','0')->paginate(10)]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
            return view('discount.create');
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
            'name' => ['required', 'string', 'max:255'],  
            'description' => ['required', 'string', 'max:500'],
            'price' => ['required'],
            'icon' => ['required'],
        ]);

        $discount = new discount;
        $discount->name = strip_tags($request->name);
        $discount->description = $request->description;
        $discount->discount_type = $request->discount_type;
        $discount->code = $request->code;
        $discount->price =$request->price;
       
        if ($request->hasFile('icon')) {
          
            $extension = $request->file('icon')->getClientOriginalExtension();
            // Filename To store
            $fileNameToStore =time().'.'.$extension;

            $request->icon->move(public_path('uploads/discount/'), $fileNameToStore);
            $discount->icon ='uploads/discount/'.$fileNameToStore;
        }
        $discount->save();
        return redirect()->route('discount.index')->withStatus(__('Discount successfully created.'));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Driver  $driver
     * @return \Illuminate\Http\Response
     */
    public function show(discount $driver)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Driver  $driver
     * @return \Illuminate\Http\Response
     */
    public function edit(discount $discount)
    {
            return view('discount.edit', compact('discount'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Driver  $driver
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request,Discount $discount)
    {
      //Validate
        $request->validate([
            'name' => ['required', 'string', 'max:255'],  
            'description' => ['required', 'string', 'max:500'],
            'price' => ['required'],
           
        ]);

        
        $discount->name = strip_tags($request->name);
        $discount->description = $request->description;
        $discount->discount_type = $request->discount_type;
        $discount->code = $request->code;
        $discount->price =$request->price;
        
        if ($request->hasFile('icon')) {
            if($discount->icon != ''){
                $path = public_path().'/'.$discount->icon;
                if(file_exists($path)){
                    unlink($path);  
                }                
            }
            $extension = $request->file('icon')->getClientOriginalExtension();
            // Filename To store
            $fileNameToStore =time().'.'.$extension;

            $request->icon->move(public_path('uploads/discount/'), $fileNameToStore);
            $discount->icon ='uploads/discount/'.$fileNameToStore;
           
            
        }
       
        $discount->update();
        return redirect()->route('discount.index')->withStatus(__('Discount successfully updated.'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Driver  $driver
     * @return \Illuminate\Http\Response
     */
    public function destroy(discount $discount)
    {
        $discount->deleted_at=1;
        $discount->status=0;
        $discount->update();
      
        
        return redirect()->route('discount.index')->withStatus(__('Discount successfully deleted.'));
       
    }

    public function status($id,$status)
    {  
        $discount = discount::findorfail($id);
        if($discount->status==1){
            $discount->status=0;
        }else{
            $discount->status=1;
        }
      
        $discount->update();

        echo true;
        
    }
   

    public function getdiscount(){
        return response()->json([
            'data' => Discount::where(['status'=>1])->first(),
            'status' => true,
        ]);
    }

}

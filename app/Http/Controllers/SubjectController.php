<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Subject;
use App\Country;
class SubjectController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function index()
    {
        $subjects = Subject::select('subjects.*','country.country_name')->join('country','subjects.country_id','=','country.id')->where('subjects.deleted_at','=','0')->paginate(15);
        return view('subjects.index', ['subjects' =>$subjects]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $countryList = Country::where('active','=',1)->get();
       return view('subjects.create',['title'=>'Add Subject','countryList' =>$countryList]);
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
            'name'=>'required|string|unique:subjects,name,NULL,id,country_id,'.$request->country_id,
          
            'icon' => ['required'],
            // 'name' => [
            //     'required',
            //     'max:255',
            //     function ($request, $value, $fail) {
            //         //if ($request->country_id === 'foo') {
            //             dd($request);
            //             $result=Subject::where(['country_id'=>$request['country_id'],'name'=>$request['name']])->first();
            //             if($result){
            //                return $fail('The '.$attribute.' is invalid.');
            //             }
                        
            //         //}
            //     },
            // ],
        ]);
       // dd($request);
        $subject = new Subject;
        $subject->name = strip_tags($request->name);
        $subject->country_id = $request->country_id;

        if ($request->hasFile('icon')) {
           
            $extension = $request->file('icon')->getClientOriginalExtension();
            $fileNameToStore = time().'.'.$extension;
            $request->icon->move(public_path('uploads/subjects/'), $fileNameToStore);
            $subject->icon ='uploads/subjects/'.$fileNameToStore;
        }
      
        $subject->save();
        return redirect()->route('subjects.index')->withStatus(__('Subject successfully created.'));
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
    public function edit(Request $request, Subject $subject)
    { 
       
        $countryList = Country::where('active','=',1)->get();
        return view('subjects.edit',['title'=>'Edit Subject','countryList' =>$countryList,'subject'=>$subject]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request,Subject $subject)
    {       
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'country_id' => ['required'],
        ]);
        $subject->name= $request->name;
        $subject->country_id= $request->country_id;
        
        if ($request->hasFile('icon')) {
            if($subject->icon != ''){
                $path = public_path().'/'.$subject->icon;
                if(file_exists($path)){
                    unlink($path);  
                }                
            }
            $extension = $request->file('icon')->getClientOriginalExtension();
            // Filename To store
            $fileNameToStore =time().'.'.$extension;

            $request->icon->move(public_path('uploads/country/'), $fileNameToStore);
            $subject->icon ='uploads/country/'.$fileNameToStore;
           
            
        }
        $subject->update();

        return redirect()->route('subjects.index')->withStatus(__('Subject successfully updated.'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Subject $subject)
    {
        $subject->deleted_at=1;
        $subject->status=0;
        $subject->update();
        
        // $subject->delete();
        // if($subject->icon!='')
        // {
        //     $path = public_path()."/".$subject->icon;
        //     unlink($path);
        //     //$affectedRows = Categories::where('id', '=', $id)->delete();
        // }
        return redirect()->route('subjects.index')->withStatus(__('Subject successfully deleted.'));
    }

    
    public function status($id,$status)
    {  
        $subject = Subject::findorfail($id);
        if( $subject->status==1){
            $subject->status=0;
        }else{
            $subject->status=1;
        }
       
        $subject->update();
        echo true;
        
    }

    public function getSubjectList($id){
        $data= Subject::where('country_id','=',$id)->where('status','=','1')->where('deleted_at','=','0')->get();
        return response()->json([
            'data' =>$data,
            'status' => true,
            'errMsg' => ''
        ]);
    }
}

<?php



namespace App\Http\Controllers;



use App\QuizRatio;

use Illuminate\Http\Request;

use Spatie\Permission\Models\Role;

use Illuminate\Support\Facades\Hash;

use Carbon\Carbon;

use Illuminate\Support\Facades\DB;

use Illuminate\Support\Str;

use App\Notifications\DriverCreated;

use Validator;



class QuizRatioController extends Controller

{

    /**

     * Display a listing of the resource.

     *

     * @return \Illuminate\Http\Response

     */

    public function index()

    {

        return view('quiz_ratio.index', ['ratios' =>QuizRatio::where('deleted_at','=','0')->orderBy('id','desc')->paginate(10)]);

    }



    /**

     * Show the form for creating a new resource.

     *

     * @return \Illuminate\Http\Response

     */

    public function create()

    {

        return view('quiz_ratio.create',['title'=>'Add Ratio']);

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
            'value' => ['required'],
            'icon' => ['required'],

        ]);

        $ratio = new QuizRatio;

        $ratio->name = $request->name;
        $ratio->value = $request->value;
        if ($request->hasFile('icon')) {
          
            $extension = $request->file('icon')->getClientOriginalExtension();
            // Filename To store
            $fileNameToStore =time().'.'.$extension;

            $request->icon->move(public_path('uploads/ratio/'), $fileNameToStore);
            $ratio->image ='uploads/ratio/'.$fileNameToStore;
        }
        
        $ratio->save();

        return redirect()->route('quiz_ratio.index')->withStatus(__('Ratio successfully created.'));

    }



    /**

     * Display the specified resource.

     *

     * @param  \App\Driver  $driver

     * @return \Illuminate\Http\Response

     */

    public function show(QuizRatio $ratio)

    {

        return view('quiz_ratio.show', compact('ratio'));

    }



    /**

     * Show the form for editing the specified resource.

     *

     * @param  \App\Driver  $driver

     * @return \Illuminate\Http\Response

     */

    public function edit($id)
    {   
        $quizratio=QuizRatio::findorfail($id);

        return view('quiz_ratio.edit', compact('quizratio'));

    }



    /**

     * Update the specified resource in storage.

     *

     * @param  \Illuminate\Http\Request  $request

     * @param  \App\Driver  $driver

     * @return \Illuminate\Http\Response

     */

    public function update(Request $request, QuizRatio $quiz_ratio)

    {

        $request->validate([

            'name' => ['required'],
             'value' => ['required'],

        ]);

      
    

        $quiz_ratio->name = $request->name;
        $quiz_ratio->value = $request->value;
        if ($request->hasFile('icon')) {
          
            $extension = $request->file('icon')->getClientOriginalExtension();
            // Filename To store
            $fileNameToStore =time().'.'.$extension;

            $request->icon->move(public_path('uploads/ratio/'), $fileNameToStore);
            $quiz_ratio->image ='uploads/ratio/'.$fileNameToStore;
        }

        $quiz_ratio->update();

       

        return redirect()->route('quiz_ratio.index')->withStatus(__('Ratio Successfully Updated.'));

    }



    /**

     * Remove the specified resource from storage.

     *

     * @param  \App\Driver  $driver

     * @return \Illuminate\Http\Response

     */

    public function destroy(QuizRatio $quiz_ratio)

    {   
        $quiz_ratio->deleted_at=1;
         $quiz_ratio->status=0;
          $quiz_ratio->update();
       // $quiz_ratio->delete();
        return redirect()->route('quiz_ratio.index')->withStatus(__('Ratio successfully deleted.'));
    }

    public function status($id,$status)
    {  
        $QuizRatio = QuizRatio::findorfail($id);
        if($QuizRatio->status==1){
            $QuizRatio->status=0;
        }else{
            $QuizRatio->status=1;
        }
       // $QuizRatio->status=$status;
        $QuizRatio->update();

        echo true;
        
    }



}


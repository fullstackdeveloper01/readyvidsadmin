<?php



namespace App\Http\Controllers;



use App\City;

use App\Country;

use App\State;

use App\Zone;



use Illuminate\Http\Request;

use Spatie\Permission\Models\Role;

use Illuminate\Support\Facades\Hash;

use App\Order;

use App\Status;

use Carbon\Carbon;

use Illuminate\Support\Facades\DB;

use Illuminate\Support\Str;

use App\Notifications\DriverCreated;



class CityController extends Controller

{

    /**

     * Display a listing of the resource.

     *

     * @return \Illuminate\Http\Response

     */

    public function index()

    {

            return view('city.index', ['cities' =>City::select('city.*')->paginate(10)]);

    }



    /**

     * Show the form for creating a new resource.

     *

     * @return \Illuminate\Http\Response

     */

    public function create()

    {

            return view('city.create');

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

            // 'state_id' => ['required'],

            'city_name' => ['required', 'string', 'max:255','unique:city'],
            'place_id' => ['required', 'string', 'max:255','unique:city'],
            'late' => ['required', 'string', 'max:255','unique:city'],
            'long' => ['required', 'string', 'max:255','unique:city'],

           

        ]);



        $city = new City;

        // $city->state_id = ($request->state_id);

        $city->city_name = strip_tags($request->city_name);
        $city->place_id = ($request->place_id);
        $city->late = ($request->late);
        $city->long = ($request->long);

        $city->save();

        return redirect()->route('city.index')->withStatus(__('City successfully created.'));

    }



    /**

     * Display the specified resource.

     *

     * @param  \App\Driver  $driver

     * @return \Illuminate\Http\Response

     */

    public function show(City $driver)

    {

        //

    }



    /**

     * Show the form for editing the specified resource.

     *

     * @param  \App\Driver  $driver

     * @return \Illuminate\Http\Response

     */

    // public function edit(City $city)

    // {

    //         return view('city.edit', compact('city'));

    // }



    /**

     * Update the specified resource in storage.

     *

     * @param  \Illuminate\Http\Request  $request

     * @param  \App\Driver  $driver

     * @return \Illuminate\Http\Response

     */

    // public function update(Request $request, City $city)

    // {//dd($request);die;

    //     $request->validate([

    //        // 'city_name' => ['required', 'string', 'max:255','unique:city'],

    //         'city_name'=>'required|string|unique:city,city_name,'.$city->id,

    //     ]);

    //     $city->city_name = strip_tags($request->city_name);

    //     $city->active = $request->city_status;

    //     $city->update();

    //     return redirect()->route('city.index')->withStatus(__('City successfully updated.'));

    // }



    /**

     * Remove the specified resource from storage.

     *

     * @param  \App\Driver  $driver

     * @return \Illuminate\Http\Response

     */

    public function destroy(City $city)

    {

        if($city->active==1){

            $city->active=0;

            $city->update();

            return redirect()->route('city.index')->withStatus(__('City successfully deactivate.'));

        }else{

            $city->active=1;

            $city->update();

            return redirect()->route('city.index')->withStatus(__('City successfully activate.'));

        }

    }



    public function getCity($id=null){

        $data=City::where(['active'=>1]);

        if($id !=""){

            $data=$data->where(['state_id'=>$id]);

        }

        $data=$data->get();

        return response()->json([

            'data' => $data,

            'status' => true,

            'errMsg' => ''

        ]);

    }



    

}


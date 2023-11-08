<?php

namespace App\Http\Controllers;
use App\Order;
use App\User;
use App\Listing;

use App\Categories;
use App\Girls;
use Carbon\Carbon;
use Spatie\Permission\Models\Role;
// use Illuminate\Support\Facades\Auth;
use DB;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        
        $data['months'] = [0 => __('Jan'),1 => __('Feb'),2 => __('Mar'),3 => __('Apr'),4 => __('May'),5 => __('Jun'),6 => __('Jul'),7 => __('Aug'),8 => __('Sep'),9 => __('Oct'),10 => __('Nov'),11 => __('Dec')
        ];
        if(auth()->user()->hasRole('admin')){
            
            $fromdate = date('Y-m-d',strtotime('-30days'))." 00:00:00";
            $todate = date('Y-m-d')." 23:59:59";
            $data['thismonth_user'] = (User::where(['role'=>3])->whereBetween('created_at', [$fromdate, $todate])->count());
            $data['total_user'] = User::where('active','=',1)->where('id','!=',1)->count();
           
            $fromdate = date('Y-m').'-1';
            $todate = date('Y-m').'-31';
         

            $data['users']=User::where('active','=',1)->where('id','!=',1)->orderBy('id','desc')->limit(5)->get();

           
            $data['totalOrders'] ='';
            $data['monthLabels']='';
            $data['salesValue']='';
            return view('dashboard', $data);
        }
    }
}

<?php

namespace App\Http\Controllers;

use App\Bank;
use App\Pages;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;
use App\City;
use App\Status;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use App\Notifications\DriverCreated;

class AboutUsController extends Controller
{
    /**
     * Show the form for editing the profile.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        return view('aboutUs.edit',['pages'=>Pages::where(['title'=>'About Us'])->first()]);
    }

    /**
     * Show the form for editing the profile.
     *
     * @return \Illuminate\View\View
     */
    public function edit()
    {
        return view('aboutUs.edit');
    }

    /**
     * Update the profile
     *
     * @param  \App\Http\Requests\ProfileRequest  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, $id)
    {
        Pages::where(['id'=>$id])->update(['content'=>$request->shyamtrusteditor]);
        return back()->withStatus(__('About Us successfully updated.'));
    }

    public function store(Request $request)
    {
        //Validate
        $request->validate([
            'shyamtrusteditor' => ['required'],
        ]);

        $pages = new Pages;
        $pages->title ='About Us';
        $pages->content = $request->shyamtrusteditor;
        $pages->save();
        // return redirect()->route('customerSupport')->withStatus(__('Contact Support successfully created.'));
        return back()->withStatus(__('About Us successfully created.'));

    }
}

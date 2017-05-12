<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Auth;

use App\helpers;

use App\Locations;

class Settings_Controller extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function newlocation()
    {
        return view('location.new');
    }

    public function storelocation(Request $request)
    {

          $this->validate($request, [
            'name' => 'required',
            'add' => 'required',
            'city' => 'required',
            'state' => 'required',
            'zip' => 'required',
        ]);

        Locations::create([
            'name' => $request['name'],
            'address' => $request['add'],
            'city' => $request['city'],
            'state' => $request['state'],
            'zip' => $request['zip'],
        ]);

        return  redirect("/");


    }
}

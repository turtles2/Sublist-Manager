<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Auth;

use App\helpers;

use App\Locations;

use App\Job_Codes;

class Settings_Controller extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function newlocation()
    {
        return view('settings.location.new');
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

    public function newjob()
    {
        $locations = Locations::all();

        return view('settings.job.new',['locations' => $locations]);
    }

    public function storejob(Request $request)
    {

          $this->validate($request, [
            'name' => 'required',
            'code' => 'required|numeric|min:0|unique:job_codes,code',
            'loc' => 'required|numeric|exists:locations,id',
        ]);

        Job_Codes::create([
            'code' => $request['code'],
            'name' => $request['name'],
            'location' => $request['loc'],
        ]);

      return  redirect("/");


    }
}

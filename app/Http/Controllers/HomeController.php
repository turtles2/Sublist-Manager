<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\User;

use Auth;

use Validator;

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
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('home');
    }
    
    public function delete()
    {
        return view('delete');
    }
    
      public function storedelete(Request $request)
    {
          $this->validate($request, [
            'delete' => 'required|in:Yes',
        ]);
        
        $user = Auth::user()->id;
        
        User::destroy($user);
        
        Auth::logout();
        
         return redirect("/");
        
    }
}

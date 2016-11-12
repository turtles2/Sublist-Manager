<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Validator;

use App\Http\Controllers\Controller;

use Goutte;

use App\Accounts;

use Auth;

class AccountController extends Controller
{
    
      public function __construct()
    {
        $this->middleware('auth');
    }
    
    public function newaccount()
    {
         return view('account.new');
    }
    
    public function storenewaccount(Request $request)
    {
          $this->validate($request, [
            'employer' => 'required|max:255',
            'username' => 'required|max:255',
            'password' => 'required',
        ]);
          
        $username = $request['username'];  
        $password = $request['password'];    
         
       $crawler = Goutte::request('GET', 'http://www.sublistonline.com');
   
       $form = $crawler->selectButton('Log in')->form();
       $crawler = Goutte::submit($form, array('uname' => $username, 'pw' => $password));
   
       $message = $crawler->filterXPath('//body/table')->text();
   
       if(str_contains($message, 'You are not logged in')){
           
           $errors = array (
               'username' => 'Invalid Credentials',
               'password' => 'Invalid Credentials',
               );
           
         return redirect('/newaccount')->withErrors($errors)->withInput();
           
       }elseif(str_contains($message, 'You are logged in as')){
           // Do nothing
       }else{
           abort(500,'Error loging into Sublist');
       }
       
       $userid = Auth::user()->id;
       
         Accounts::create([
            'username' => $username,
            'password' => $password,
            'employer' => $request['employer'],
            'user_id' => $userid,
        ]);
        
        return redirect("/");
      
    }
}

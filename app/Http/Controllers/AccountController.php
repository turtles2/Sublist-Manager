<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Validator;

use App\Http\Controllers\Controller;

use Goutte;

use App\Accounts;

use Auth;

use App\Contacts;

use App\helpers;

use Datatables;

use App\User;

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
       
       $crawler = Goutte::click($crawler->selectLink('[ log out ]')->link());
       
       $userid = Auth::user()->id;
       
         $account = Accounts::create([
            'username' => $username,
            'password' => $password,
            'employer' => $request['employer'],
            'user_id' => $userid,
        ]);
        
        $contacts = helpers::get_contacts($account['username'],$account['password']);
               
               $managers = $contacts['managers'];
               $employes = $contacts['employee'];
               
               foreach($managers as $manager){
                   
                Contacts::updateOrCreate(
                ['fname' => $manager['First Name'], 'join_date' => $manager['Join Date'], 'account_id' => $account['id']],
                ['lname' => $manager['Last Name'], 'phone' => $manager['Phone'], 'email' => $manager['Email'], 
                'status' => 1, 'manager' => true]
                );

               }
               
                foreach($employes as $employe){
                   
                Contacts::updateOrCreate(
                ['fname' => $employe['First Name'], 'join_date' => $employe['Join Date'], 'account_id' => $account['id']],
                ['lname' => $employe['Last Name'], 'phone' => $employe['Phone'], 'email' => $employe['Email'], 
                'status' => 1, 'manager' => false]
                );

               }
        
        return redirect("/");
      
    }
    
       public function viewaccount()
    {
         return view('account.view');
    }
    
       public function viewaccountdata()
    {
        
         $userid = Auth::user()->id;
        
         return Datatables::of(Accounts::where('user_id',"$userid"))->make(true);
    }
    
     public function viewcontacts()
    {
         return view('account.viewcontacts');
    }
    
    
       public function viewcontactsdata()
    {
        
         $userid = Auth::user()->id;
        
         $accounts = Accounts::where('user_id',"$userid")->get();
         
         $user_contacts = array();
         
         foreach($accounts as $account){
             
             $contacts = $account->Contacts()->get();
             
             foreach($contacts as $contact){
                 
                 if($contact['manager'] == true){
                     
                     $contact['manager'] = 'Yes';
                     
                 }elseif($contact['manager'] == false){
                     
                     $contact['manager'] = 'No';
                     
                 }
 
                 array_push($user_contacts, $contact);
                 
             }
         }
         
         $user_contacts = collect($user_contacts);
         
         return Datatables::of($user_contacts)->make(true);
    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Datatables;

use App\Sub_Shifts;

use App\Accounts;

use App\Contacts;

use Carbon\Carbon;

use Auth;

class Shift_Controller extends Controller
{
       public function __construct()
    {
        $this->middleware('auth');
    }
    
    public function viewopendata()
    {
        $current = new Carbon(); 

        $shifts = Sub_Shifts::whereNull('covered')->where('starts', '>=', $current)->whereHas('poster.account.user', function ($query) {
             
            $userid = Auth::user()->id;
             
            $query->where('id', $userid);
            
        })->get();
        
        $openshifts = array();
        
        foreach($shifts as $row){
            
            $poster = $row->poster()->first();
            
            $shift['poster'] = $poster->fname . ' ' . $poster->lname;
            
            $start = Carbon::parse($row['starts']);
            
            $end = Carbon::parse($row['ends']);
            
            $shift['starts'] = $start->toDayDateTimeString();
            $shift['ends'] = $end->toDayDateTimeString();
            
            $shift['posted'] = $row['posted'];
            
            if($row['code'] == 0 or $row['code'] == 3){
                
                $shift['type'] = 'Lifegurad';
                
            }elseif($row['code'] == 1){
                
                  $shift['type'] = 'Swim Instructor';
                
            }else{
                
                $shift['type'] == $row['code'];
                
            }
            
            
            
            array_push($openshifts, $shift);
            
        }
        
        $openshifts = collect($openshifts);
            
        return Datatables::of($openshifts)->make(true);
         
    }
    
    public function viewopen()
    {
        
        return view('shift.open');
      
    }
    
     public function viewcover()
    {
        
        return view('shift.cover');
      
    }
    
        public function viewcoverdata()
    {
        $current = new Carbon(); 
        
        $userid = Auth::user()->id;
        
        $account = Accounts::where([
                    ['user_id', "$userid"],
                    ])->firstorfail();
                    
        $email = Auth::user()->email;
        
        $accountid = $account['id'];
        
        $worker = Contacts::where([
                    ['email', "$email"],
                    ['account_id', "$accountid"],
                    ])->first();
                    
        $workerid = $worker['id'];

        $shifts = Sub_Shifts::where('covered',$workerid)->where('starts', '>=', $current)->whereHas('poster.account.user', function ($query) {
             
            $userid = Auth::user()->id;
             
            $query->where('id', $userid);
            
        })->get();
        
        $openshifts = array();
        
        foreach($shifts as $row){
            
            $poster = $row->poster()->first();
            
            $shift['poster'] = $poster->fname . ' ' . $poster->lname;
            
            $start = Carbon::parse($row['starts']);
            
            $end = Carbon::parse($row['ends']);
            
            $shift['starts'] = $start->toDayDateTimeString();
            $shift['ends'] = $end->toDayDateTimeString();
            
            if($row['code'] == 0 or $row['code'] == 3){
                
                $shift['type'] = 'Lifegurad';
                
            }elseif($row['code'] == 1){
                
                  $shift['type'] = 'Swim Instructor';
                
            }else{
                
                $shift['type'] == $row['code'];
                
            }
            
            
            
            array_push($openshifts, $shift);
            
        }
        
        $openshifts = collect($openshifts);
            
        return Datatables::of($openshifts)->make(true);
         
    }
}
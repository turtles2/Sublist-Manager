<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Datatables;

use App\Sub_Shifts;

use App\Shifts;

use App\Accounts;

use App\Contacts;

use Carbon\Carbon;

use Auth;

use App\helpers;

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
            
            $shift['type'] = helpers::job_name($row['code']);
            
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
            
            $shift['type'] = helpers::job_name($row['code']);
            
            array_push($openshifts, $shift);
            
        }
        
        $openshifts = collect($openshifts);
            
        return Datatables::of($openshifts)->make(true);
         
    }
    
    public function viewworkingdata($start,$end,$type)
    {
        
        $preceding_shits = array();
        
        $following_shifts = array();
        
        $over_laping_shifts = array();
        
        $same_shifts = array();
        
        // Get all shifts between start and end times this includes shifts that end or start at the start and end times
        
        $regular_shifts = Shifts::whereBetween('starts', [$start, $end])->orwhereBetween('ends', [$start, $end])->whereHas('worker.account.user', function ($query) {
             
            // make sure the shifts belong to the user
             
            $userid = Auth::user()->id;
             
            $query->where('id', $userid);
            
        })->get();
        
          // Get all sub shifts between start and end times this includes shifts that end or start at the start and end times
        
        $sub_shifts = Sub_Shifts::whereBetween('starts', [$start, $end])->orwhereBetween('ends', [$start, $end])->whereHas('poster.account.user', function ($query) {
             
            // make sure the shifts belong to the user
             
            $userid = Auth::user()->id;
             
            $query->where('id', $userid);
            
        })->get();
        
        foreach($regular_shifts as $key => $regular_shift)
        {
            
            $workerid = $regular_shift['worker'];
            
            $subed_shift = $sub_shifts->where('poster',$workerid);
            
            if($subed_shift->isEmpty())
            {
                // do nothing
            }else
            {
                // the shift was subed out so we remove it
                
               unset($regular_shifts[$key]);
                
            }
        
            
        }
        
        // sorts out if someone subed out a sub shift
        
        foreach($sub_shifts as $key => $sub_shift)
        {
            
            $worker = $sub_shift['covered'];
            
            $shift_start = $sub_shift['starts'];
            
            $shift_end = $sub_shift['ends'];
            
            $nested_subed_shift = $sub_shifts->where('poster',$worker)->where('starts',$shift_start)->where('ends',$shift_end);
            
            if($nested_subed_shift->isEmpty())
            {
                // do nothing
            }else
            {
                // the shift was subed out so we remove it
                
             unset($sub_shifts[$key]);
                
            }
            
        }
        
        $shifts = $regular_shifts->merge($sub_shifts);
        
        // sort out shifts relative to start and end times
        
        foreach($shifts as $shift)
        {
            
            if(isset($shift->covered))
            {
                
                $shift_data['worker'] = $shift->covered()->first()->fname;
                
                $shift_data['sub'] = 'Yes'; 
                
            }elseif(isset($shift->worker))
            {
                
                $shift_data['worker'] = $shift->worker()->first()->fname;
                
                $shift_data['sub'] = 'No';
                
            }
            
            $starts = Carbon::parse($shift['starts']);
                
            $shift_data['starts'] = $starts->format('g:i A');
                
            $ends = Carbon::parse($shift['ends']);
                
            $shift_data['ends'] = $ends->format('g:i A');
            
            if($shift['ends'] == $start)
            {
                
                array_push($preceding_shits, $shift_data);
               
            }elseif($shift['starts'] == $end)
            {
                
                array_push($following_shifts, $shift_data);
                
            }elseif($shift['starts'] == $start and $shift['ends'] == $end){
                
                array_push($same_shifts, $shift_data);
                
            }else{
                
                array_push($over_laping_shifts, $shift_data);
                
            }
        

        }
        
        if($type == 'pre')
        {
            $preceding_shits = collect($preceding_shits);
            
            return Datatables::of($preceding_shits)->make(true);
            
        }elseif($type == 'id')
        {
            
            $same_shifts = collect($same_shifts);
            
            return Datatables::of($same_shifts)->make(true);
            
        }elseif($type == 'over'){
            
            $over_laping_shifts = collect($over_laping_shifts);
            
            return Datatables::of($over_laping_shifts)->make(true);
            
        }elseif($type == 'after')
        {
            
            $following_shifts = collect($following_shifts);
            
            return Datatables::of($following_shifts)->make(true);
            
        }else{
            
            return redirect("/");
            
        }
      
    }
    
    public function viewworking()
    {
        
        $shift_data = array();
        
        $current = new Carbon(); 
        
        $dayago = $current->subDay()->toDateTimeString(); 
        
        $week_from_now = $current->addWeek()->toDateTimeString(); 
        
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

        $sub_shifts = Sub_Shifts::where('covered',$workerid)->whereBetween('starts', [$dayago, $week_from_now])->get();
        
        $regular_shifts = Shifts::where('worker',$workerid)->whereBetween('starts', [$dayago, $week_from_now])->get();
        
       $shifts = $regular_shifts->merge($sub_shifts);
        
        foreach($shifts as $shift)
        {
            
            $starts = Carbon::parse($shift['starts']);
            
            $ends = Carbon::parse($shift['ends']);
            
            $time = array(
                'start' => $starts->toDateTimeString(),
                'end' => $ends->toDateTimeString()
                );
            
            $data['readable_datetime'] = $starts->format('D') . ' the ' . $starts->format(' j') . 'th ' . $starts->format('g:i A') . ' to ' . $ends->format('g:i A');
            
            $data['time_string'] = encrypt($time);
            
            array_push($shift_data, $data);
            
        }
        
        return view('shift.working_list',['shift_data' => $shift_data]);
      
    }
    
     public function viewworkingbuild(Request $request)
    {
          $this->validate($request, [
            'shift' => 'required',
        ]);
        
        try {
            
            $time = decrypt($request['shift']);
            
        } catch (DecryptException $e) {
            
            abort(500, 'Decryption Error');
    
        }
        
        return view('shift.working_view',['time' => $time]);
    
    }
}

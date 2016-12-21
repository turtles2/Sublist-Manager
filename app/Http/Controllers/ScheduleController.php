<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Validator;

use App\Http\Controllers\Controller;

use Carbon\Carbon;

use App\Shifts;

use App\Sub_Shifts;

use App\Contacts;

use App\Accounts;

use Auth;

use Socialite;

use Google_Client;

use Google_Service_Calendar;

use Google_Service_Calendar_Event;

class ScheduleController extends Controller
{
     public function __construct()
    {
        $this->middleware('auth');
    }
    
    public function newschedule()
    {
         return view('schedule.new');
    }
    
    public function storenewschedule(Request $request)
    {
          $this->validate($request, [
            'type' => 'required|in:Lifeguarding',
            'schedule' => 'required|file',
        ]);
        
        $filename = $request['schedule'];
        
        $lines = array();
        
        $file = fopen("$filename", 'r');
        while (($line = fgetcsv($file)) !== FALSE) {
          array_push($lines, $line);
        }
        fclose($file);
        
        end($lines[0]);
        
        $key = key($lines[0]);
        
        $columns = array();
        
        while($key >= 0){
            
            $columns[$key] = array();
            
            $key --;
        }
        
        $columns = array_reverse($columns);
        
        foreach($lines as $line){
            
            foreach($line as $column => $value){
                
                array_push($columns[$column], $value);
                
            }
        }
        
        $days = array();
        
        foreach($columns as $column){
            
            $lastrowkey = 0;
            
            $column = array_reverse($column);
            
            foreach($column as $rowkey => $row){
                
                if(str_contains($row, ['SUN', 'MON','TUES','WED','THU','FRI','SAT'])){
                    
                    if($lastrowkey == 0){
                        $offset = 0;
                    }else{
                        $offset = $lastrowkey +1;
                    }
                    
                    $length = $rowkey +2 - $offset;
                    
                    $lastrowkey = $rowkey;
                    
                    $day = array_slice($column, $offset,$length);
                    
                    
                    // Clean Up to make each day uniform
                    $pattern = '/\d{1,2}-\w{3}/';
                    foreach($day as $daykey => $dayrow){
                        
                        if(empty($dayrow)){
                            
                            unset($day[$daykey]);
                            
                        } elseif(preg_match($pattern, $dayrow)){
                            
                            unset($day[$daykey]);
                            
                        }else{
                            break;
                        }
                        
                    }
                    
                     $day = array_reverse($day);
                    
                    array_push($days, $day);
                    
                }
                
                
            }
        }
        
        
        $shifts = array();
        
        $current = new Carbon(); 
        
        $year = $current->year;
        
        foreach($days as $day){
            
            $date = $day[0];
            
              $daystart= strpos($date, '-');
                
                $month = trim(substr($date, $daystart +1));
                
                $calday = substr($date, 0,$daystart);
                
                $pattern = '/\d{1,2}(:\d{1,2}-\d{1,2}((\s|)[A,M,P]{2}|:\d{1,2}(\s|)[A,M,P]{2})|-\d{1,2}(:\d{1,2}(\s|)[A,M,P]{2}|(\s|)[A,M,P]{2}))/';
                $daypattern = '/\d{1,2}-\w{3}/';
                
                foreach($day as $key => $row){
                    
                    if(preg_match($pattern, $row)){

                        $lasttimerow = $key;
                        
                    }elseif(empty($row)){
                        
                    }elseif(preg_match($daypattern, $row)){
                        
                    }elseif(str_contains($row, ['SUN', 'MON','TUES','WED','THU','FRI','SAT'])){
                        
                    }else{
                        // Row is not empty ,time, date or day meaning its a name
                        $timepattern = '/[A,M,P]{2}/';
                        
                        $shifttime = $day[$lasttimerow];
                        
                        preg_match($timepattern, $shifttime,$matches);
                        
                        $timeofday = $matches[0];
                        
                        $shifttime = preg_replace($timepattern, '', $shifttime);
                        
                        $endtimestart = strpos($shifttime, '-');
                        
                        $start = substr($shifttime, 0,$endtimestart);
                        
                        $end = trim(substr($shifttime, $endtimestart +1));
                        
                        $endtime = $end . ' ' . $timeofday;
                        
                        if($timeofday == 'AM'){
                            
                            $starttime = $start . ' ' . $timeofday;
                            
                        }elseif($timeofday == 'PM'){
                            
                            $hour = $start;
                            
                            $endhour = $end;
                            
                            if(str_contains($start,':')){
                                
                                $minstart = strpos($start, ':');
                                
                                $hour =  substr($start, 0,$minstart);
                                
                            }
                            
                            if(str_contains($end,':')){
                                
                                $minstart = strpos($end, ':');
                                
                                $endhour =  substr($end, 0,$minstart);
                                
                            }
                            
                            if($hour +12 > $endhour + 12){
                                
                                $starttime = $start . ' AM';
                                
                            }elseif($hour +12 < $endhour + 12){
                                
                                $starttime = $start . ' PM';
                                
                            }else{
                                
                                echo 'Time Error';
                                
                            }
                            
                            
                        }
                        
                        $nametime = '/.{1,}\s(\d{1,2}:\d{1,2})/';
                        
                        if(preg_match($nametime, $row,$matches)){
                            
                            $endtime = $matches[1] . ' ' . $timeofday; 
                            
                            $row = preg_replace('/\d{1,2}:\d{1,2}/', '', $row);
                            
                        }
                        
                        $starttimestring = $month .' '. $calday.' '. $year .' ' . $starttime;
                
                        $endtimestring = $month .' '. $calday.' '. $year .' ' . $endtime;
                
                        $shiftstart = Carbon::parse($starttimestring);
                        
                        $shiftend = Carbon::parse($endtimestring);
                        
                        if($shiftend->isPast()){
                            
                            // Assume all shifts are in future so a year must be added to make that so
                            
                            $shiftend = $shiftend->addYear();
                            
                        }
                        
                        if($shiftstart->isPast()){
                            
                            // Assume all shifts are in future so a year must be added to make that so
                            
                            $shiftstart = $shiftstart->addYear();
                            
                        }
                        
                        $shift = array(
                            "worker" => $row,
                            "start" => $shiftstart,
                            "end" => $shiftend,
                            );
                            
                        array_push($shifts, $shift);
                        
                    }
                    
                }
            
        }
        
        $userid = Auth::user()->id;
        
        $account = Accounts::where([
                    ['user_id', "$userid"],
                    ])->firstorfail();
                    
        $accountid = $account['id'];
        
        foreach($shifts as $shift){
            
            $fname = trim($shift['worker']);
            
            $worker = Contacts::where([
                    ['fname', "$fname"],
                    ['account_id', "$accountid"],
                    ])->first();
                    
            if(empty($worker)){
                
                // Using Like to get a result 
                
                $likefname = '%'. $fname .'%';
                
                 $worker = Contacts::where([
                    ['fname', 'like', "$likefname"],
                    ['account_id', "$accountid"],
                    ])->first();
                
            }
            
            if(empty($worker)){
                
                // Don't enter shift as it is lacking a known worker 
                
            }else{
                // Enter Shift
                
                Shifts::firstOrCreate([
                     'starts' => $shift['start'],
                     'ends' => $shift['end'],
                     'code' => 0,
                     'worker' => $worker['id'],
                     ]);
            
            }
                    
        }
        
        return redirect("/");
        
    }
    
     public function redirectToProvider(Request $request)
    {
        
        $this->validate($request, [
            'sync' => 'required|in:Sync Sub Shifts,Sync Regular Shifts,Sync All Shifts',
        ]);
        
        $sync = $request['sync'];
        
        session(['sync' => "$sync"]);
        
        return Socialite::driver('google')->redirect();
    }

    /**
     * Obtain the user information from GitHub.
     *
     * @return Response
     */
    public function handleProviderCallback(Request $request)
    {
        
        $sync = $request->session()->pull('sync');
        
        $user = Socialite::driver('google')->user();
        
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
        
        $client = new Google_Client();
        
        $accessToken = $user->token;
        
        $client->setAccessToken([
          'access_token' => $accessToken,
          'expires_in'   => 3600,
          'created'      => time(),
        ]);

        $service = new Google_Service_Calendar($client);
        
        $calendarId = 'primary';
        
        $address = env('ADDRESS');
        
        $timezone = config('app.timezone');
        
        
        if($sync == 'Sync Sub Shifts'){
            
            $shifts = Sub_Shifts::where([
                    ['sync', false],
                    ['covered', "$workerid"],])->get();
                    
            foreach($shifts as $shift){
                
                if($shift->code == 0 or $shift->code == 3){
                    
                    $event = 'Lifeguarding Shift';
                    
                }elseif($shift->code == 1){
                    
                    $event = 'Swim Instructor Shift';
                    
                }else{
                    
                    $event = 'Shift';
                    
                }
                
                $start = Carbon::parse($shift->starts);
                
                $end = Carbon::parse($shift->ends);
                
                $start = $start->toIso8601String();
                
                $end = $end->toIso8601String();
                
                $event = new Google_Service_Calendar_Event(array(
                  'summary' => $event,
                  'location' => $address,
                  'description' => 'Shift Created by Sublist Manager',
                  'start' => array(
                    'dateTime' => $start,
                    'timeZone' => $timezone,
                  ),
                  'end' => array(
                    'dateTime' => $end,
                    'timeZone' => $timezone,
                  ),
                   ));
                   
                $event = $service->events->insert($calendarId, $event);
                
            }
            
            Sub_Shifts::where([
                    ['sync', false],
                    ['covered', "$workerid"],])->update(['sync' => true]);
                    
            
        }elseif($sync == 'Sync Regular Shifts'){
            
            $shifts = Shifts::where([
                    ['sync', false],
                    ['worker', "$workerid"],])->get();
                    
            foreach($shifts as $shift){
                
                if($shift->code == 0 or $shift->code == 3){
                    
                    $event = 'Lifeguarding Shift';
                    
                }elseif($shift->code == 1){
                    
                    $event = 'Swim Instructor Shift';
                    
                }else{
                    
                    $event = 'Shift';
                    
                }
                
                $start = Carbon::parse($shift->starts);
                
                $end = Carbon::parse($shift->ends);
                
                $start = $start->toIso8601String();
                
                $end = $end->toIso8601String();
                
                $event = new Google_Service_Calendar_Event(array(
                  'summary' => $event,
                  'location' => $address,
                  'description' => 'Shift Created by Sublist Manager',
                  'start' => array(
                    'dateTime' => $start,
                    'timeZone' => $timezone,
                  ),
                  'end' => array(
                    'dateTime' => $end,
                    'timeZone' => $timezone,
                  ),
                   ));
                   
                $event = $service->events->insert($calendarId, $event);
                
            }
            
            Shifts::where([
                    ['sync', false],
                    ['worker', "$workerid"],])->update(['sync' => true]);
            
        }elseif($sync == 'Sync All Shifts'){
            
            $shifts = Shifts::where([
                    ['sync', false],
                    ['worker', "$workerid"],])->get();
                    
            foreach($shifts as $shift){
                
                if($shift->code == 0 or $shift->code == 3){
                    
                    $event = 'Lifeguarding Shift';
                    
                }elseif($shift->code == 1){
                    
                    $event = 'Swim Instructor Shift';
                    
                }else{
                    
                    $event = 'Shift';
                    
                }
                
                $start = Carbon::parse($shift->starts);
                
                $end = Carbon::parse($shift->ends);
                
                $start = $start->toIso8601String();
                
                $end = $end->toIso8601String();
                
                $event = new Google_Service_Calendar_Event(array(
                  'summary' => $event,
                  'location' => $address,
                  'description' => 'Shift Created by Sublist Manager',
                  'start' => array(
                    'dateTime' => $start,
                    'timeZone' => $timezone,
                  ),
                  'end' => array(
                    'dateTime' => $end,
                    'timeZone' => $timezone,
                  ),
                   ));
                   
                $event = $service->events->insert($calendarId, $event);
                
            }
            
            Shifts::where([
                    ['sync', false],
                    ['worker', "$workerid"],])->update(['sync' => true]);
                    
            $shifts = Sub_Shifts::where([
                    ['sync', false],
                    ['covered', "$workerid"],])->get();
                    
            foreach($shifts as $shift){
                
                if($shift->code == 0 or $shift->code == 3){
                    
                    $event = 'Lifeguarding Shift';
                    
                }elseif($shift->code == 1){
                    
                    $event = 'Swim Instructor Shift';
                    
                }else{
                    
                    $event = 'Shift';
                    
                }
                
                $start = Carbon::parse($shift->starts);
                
                $end = Carbon::parse($shift->ends);
                
                $start = $start->toIso8601String();
                
                $end = $end->toIso8601String();
                
                $event = new Google_Service_Calendar_Event(array(
                  'summary' => $event,
                  'location' => $address,
                  'description' => 'Shift Created by Sublist Manager',
                  'start' => array(
                    'dateTime' => $start,
                    'timeZone' => $timezone,
                  ),
                  'end' => array(
                    'dateTime' => $end,
                    'timeZone' => $timezone,
                  ),
                   ));
                   
                $event = $service->events->insert($calendarId, $event);
                
            }
            
            Sub_Shifts::where([
                    ['sync', false],
                    ['covered', "$workerid"],])->update(['sync' => true]);
            
        }else{
            
            abort(404);

        }
        
        return redirect("/");

    }
    
    public function syncgoogle()
    {
         return view('schedule.syncgoogle');
    }
}

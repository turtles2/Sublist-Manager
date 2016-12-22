<?php 
namespace App;

use Goutte;

use App\Sub_Shifts;

use Carbon\Carbon;

use App\Contacts;

use App\Notifications\NewShift;

use App\Accounts;

use App\User;

class helpers
{
   
    protected static function login($username,$password,$url)
    {
        
        $crawler = Goutte::request('GET', "$url");
		   
		   $form = $crawler->selectButton('Log in')->form();
		   $crawler = Goutte::submit($form, array('uname' => "$username", 'pw' => "$password"));
		   
		   $message = $crawler->filterXPath('//body/table')->text();
   
       if(str_contains($message, 'You are not logged in')){
       	
       	// Will throw email in future
       	
       	Log::alert('Invalid Sublist Username and Password');
           
       }elseif(str_contains($message, 'You are logged in as')){
           // Do nothing
       }else{
           abort(500,'Error loging into Sublist');
       }
       
       return $crawler;
        
    }
    
       protected static function logout($crawler)
    {
        
       $crawler = Goutte::click($crawler->selectLink('[ log out ]')->link());
        
    }
 
		public static function normalize_phone($telephone_number)
    {
         $search_replace_mapping = array(
  		  // country prefix normalization
 		   '+00' => '+', '++' => '+',
 		   // country prefix is always 00
 		   '+' => '00',
 		   // funny user input goulash
 		   'i' => '1', 'I' => '1', 'l' => '1',
 		   'o' => '0', 'O' => '0',
 
 		   // ([^\diIloO\+]*)
 		   // ...brackets
 		   '(' => '', ')' => '',
 		   '[' => '', ']' => '',
  		  '[' => '', ']' => '',
 		  // slashes
    	'/' => '', '\\\\' => '',
   		 // dashes
   		 '-' => '', '_' => '',
   		 // whitespaces
   		 ' ' => ''
 	 );
 	 
 	   // We assume a 10 digit phone number
    // fetch search and replace arrays
    $search = array_keys($search_replace_mapping);
    $replace = array_values($search_replace_mapping);
 
    // simple string replacement
    $telephone_number = str_replace($search, $replace, $telephone_number);
 
    // lets kick out all dutty stuff which is left...
    $telephone_number = preg_replace('~[^\d]~', '', $telephone_number);
 
    return $telephone_number;

       
    }
    
    	public static function get_contacts($username,$password)
    {
    	
    	   $crawler = helpers::login($username,$password,'http://www.sublistonline.com');
    	
		   $crawler = Goutte::click($crawler->selectLink('Contacts')->link());
		   
		   $lasttable = $crawler->filter('body > table')->last();
		   
		   $datatable = $lasttable->filter('table > tr')->first();
		   
		   $mantable = $datatable->filter('center')->eq(1);
		   
		   $emptable = $datatable->filter('center')->eq(2);
		   
		   $manattributes = $mantable
		    ->filterXpath('//center/table/tr')
		    ->extract(array('_text', 'tr'));
		    
		    $empattributes = $emptable
		    ->filterXpath('//center/table/tr')
		    ->extract(array('_text', 'tr'));
		    
		    $managerinfo = array();
		    
		    foreach($manattributes as $manattribute){
		      
		      $manlines = explode("\n", $manattribute[0]);
		      
		      $manager = array(
		        "First Name" => trim($manlines[1]),
		        "Last Name" => trim($manlines[0]),
		        "Phone" => trim($manlines[3]),
		        "Email" => trim($manlines[2]),
		        "Join Date" => trim($manlines[4]),
		        );
		        
		      array_push($managerinfo, $manager);
		      
		    }
		    
		    $empinfo = array();
		    
		    foreach($empattributes as $empattribute){
		      
		      $emplines = explode("\n", $empattribute[0]);
		      
		      $emp = array(
		        "First Name" => trim($emplines[1]),
		        "Last Name" => trim($emplines[0]),
		        "Phone" => trim($emplines[3]),
		        "Email" => trim($emplines[2]),
		        "Join Date" => trim($emplines[4]),
		        );
		        
		      array_push($empinfo, $emp);
      
    }
    
    $results = array(
    	"managers" => $managerinfo,
    	"employee" => $empinfo,
    	);

          helpers::logout($crawler); // Ends the Session

       return $results;
    }
    
    	public static function get_shifts($username,$password,$month,$year)
    {
       
       $url = 'https://www.sublistonline.com/list.php?month='."$month".'&year='."$year";
       
    	 $crawler = helpers::login("$username","$password","$url");
		   
		   $lasttable = $crawler->filter('body > table')->last();
		   
		   $datatable = $lasttable->filter('table > tr > td > table')->first();
		   
		   $opentable = $datatable->filter('table')->eq(1);
		   
		   $takentable = $datatable->filter('table')->eq(2);
		   
           $openshifts = array();
           
           $takenshifts = array();
		   
		   $openshifts = $opentable->filter('tr')->each(function ($node) {
		      
		         try{
		      
		        $day = $node->filter('td')->eq(0)->text();
		        $poster = $node->filter('td')->eq(2)->text();
		        $start = $node->filter('td')->eq(3)->text();
		        $end = $node->filter('td')->eq(4)->text();
		        $code = $node->filter('td')->eq(6)->text();
		        $listed = $node->filter('td')->eq(7)->text();
		        
		        $shift = array(
		            "day" => $day,
		            "poster" => $poster,
		            "start" => $start,
		            "end" => $end,
		            "code" => $code,
		            "listed" => $listed,
		            );

		       
		  }catch(\InvalidArgumentException $e) {
		       
		    $shift = false;
             
       }
		            
		       return $shift;
		        
          });
          
          $takenshifts = $takentable->filter('tr')->each(function ($node) {
		      
		          try {
		      
		        $day = $node->filter('td')->eq(0)->text();
		        $poster = $node->filter('td')->eq(2)->text();
		        $start = $node->filter('td')->eq(3)->text();
		        $end = $node->filter('td')->eq(4)->text();
		        $code = $node->filter('td')->eq(6)->text();
		        $taken = $node->filter('td')->eq(7)->text();
		        
		        $shift = array(
		            "day" => $day,
		            "poster" => $poster,
		            "start" => $start,
		            "end" => $end,
		            "code" => $code,
		            "taken" => $taken,
		            );
		       
          }catch(\InvalidArgumentException $e) {
              
            $shift = false;
             
            }
	            
		       return $shift;
		        
          });
          
        $results = array(
            "open" => $openshifts,
            "taken" => $takenshifts,
            );
            
            helpers::logout($crawler); // Ends the Session
            
         return $results;
		   
    }
    
    	public static function enter_shifts($username,$password,$months,$accountid)
    {
       
       $account = Accounts::find($accountid);
       
       $userid = $account->user->id;
       
       $user = User::find($userid);
       
       foreach($months as $month_of_intrest){

        $current = new Carbon(); 
    
        $intrest = $current->addMonth($month_of_intrest); 
    
        $year = $intrest->year;
    
        $month = Carbon::parse($intrest)->format('F');
    
        $shifts = helpers::get_shifts("$username","$password",$month,$year);
        
        
        if(isset($shifts['open']['0']['listed'])){
           
           $listed = $shifts['open']['0']['listed'];
           
        }else{
           
           $listed = NULL;
           
        }
       
        
        if($shifts['open'] != false and str_contains($listed,'-')){
            // There are open shifts 
            
            $openshifts = $shifts['open'];
            
            foreach($openshifts as $openshift){
                
                $starttimestring = $month .' '. $openshift['day'] .' '. $year .' ' . $openshift['start'];
                
                $endtimestring = $month .' '. $openshift['day'] .' '. $year .' ' . $openshift['end'];
                
                $start = Carbon::parse($starttimestring);
                
                $end = Carbon::parse($endtimestring);
                
                $daystart= strpos($openshift['listed'], '-');
                
                $day = trim(substr($openshift['listed'], $daystart +1));
                
                $nummonth = substr($openshift['listed'], 0,$daystart);
                
                $posted = $year. '-' . $nummonth . '-' . $day;
                
                $lnameend = strpos($openshift['poster'], ',');
                
                $fname = trim(substr($openshift['poster'], $lnameend +1));
                
                $lname = substr($openshift['poster'], 0,$lnameend);
                
                $poster = Contacts::where([
                ['fname', $fname],
                ['lname', $lname],
                ['account_id', "$accountid"],
                ])->firstorfail();
                
                 $shift = Sub_Shifts::firstOrCreate([
                     'starts' => $start,
                     'ends' => $end,
                     'posted' => $posted,
                     'code' => $openshift['code'],
                     'poster' => $poster['id'],
                     ]);
                     
                 $created = Carbon::parse($shift['created_at']);
                 
                 if($current->lte($created)){
                    
                    if($user['auto_email'] == true){
                    
                       Notification::send($user, new NewShift($shift));
                    
                    }
                    
                 }
                     
            }
            
            
        }elseif($shifts['open'] != false){
           
           // In Case of Shift read error
           
             $takenshifts = $shifts['open'];
            
            foreach($takenshifts as $takenshift){
                
                $starttimestring = $month .' '. $takenshift['day'] .' '. $year .' ' . $takenshift['start'];
                
                $endtimestring = $month .' '. $takenshift['day'] .' '. $year .' ' . $takenshift['end'];
                
                $start = Carbon::parse($starttimestring);
                
                $end = Carbon::parse($endtimestring);
                
                $lnameend = strpos($takenshift['poster'], ',');
                
                $fname = trim(substr($takenshift['poster'], $lnameend +1));
                
                $lname = substr($takenshift['poster'], 0,$lnameend);
                
                $tlnameend = strpos($takenshift['listed'], ',');
                
                $tfname = trim(substr($takenshift['listed'], $tlnameend +1));
                
                $tlname = substr($takenshift['listed'], 0,$tlnameend);
                
                $poster = Contacts::where([
                ['fname', $fname],
                ['lname', $lname],
                ['account_id', "$accountid"],
                ])->firstorfail();
                
                $taker = Contacts::where([
                ['fname', $tfname],
                ['lname', $tlname],
                ['account_id', "$accountid"],
                ])->firstorfail();
                
                $date = Carbon::now()->toDateString();    
                
                 Sub_Shifts::updateOrCreate([
                     'starts' => $start,
                     'ends' => $end,
                     'code' => $takenshift['code'],
                     'poster' => $poster['id'],
                     ],
                     [
                     'covered' => $taker['id'],
                     'taken' => $date,
                     ]);
                     
            }
            
        }else{
           
           // No Open Shifts
           
        }
        
        if($shifts['taken'] != false){
            // There are taken shifts
            
            $takenshifts = $shifts['taken'];
            
            foreach($takenshifts as $takenshift){
                
                $starttimestring = $month .' '. $takenshift['day'] .' '. $year .' ' . $takenshift['start'];
                
                $endtimestring = $month .' '. $takenshift['day'] .' '. $year .' ' . $takenshift['end'];
                
                $start = Carbon::parse($starttimestring);
                
                $end = Carbon::parse($endtimestring);
                
                $lnameend = strpos($takenshift['poster'], ',');
                
                $fname = trim(substr($takenshift['poster'], $lnameend +1));
                
                $lname = substr($takenshift['poster'], 0,$lnameend);
                
                $tlnameend = strpos($takenshift['taken'], ',');
                
                $tfname = trim(substr($takenshift['taken'], $tlnameend +1));
                
                $tlname = substr($takenshift['taken'], 0,$tlnameend);
                
                $poster = Contacts::where([
                ['fname', $fname],
                ['lname', $lname],
                ['account_id', "$accountid"],
                ])->firstorfail();
                
                $taker = Contacts::where([
                ['fname', $tfname],
                ['lname', $tlname],
                ['account_id', "$accountid"],
                ])->firstorfail();
                
                $date = Carbon::now()->toDateString();    
                
                 Sub_Shifts::updateOrCreate([
                     'starts' => $start,
                     'ends' => $end,
                     'code' => $takenshift['code'],
                     'poster' => $poster['id'],
                     ],
                     [
                     'covered' => $taker['id'],
                     'taken' => $date,
                     ]);
                     
            }
        }else{
            // No taken shifts
        }
        
    }
		   
    }
    
}

?>
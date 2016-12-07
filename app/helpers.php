<?php 
namespace App;

use Goutte;

class helpers
{
 
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
    	
		   $crawler = Goutte::request('GET', 'http://www.sublistonline.com');
		   
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

       return $results;
    }
    
}

?>
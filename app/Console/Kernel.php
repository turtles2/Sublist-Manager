<?php

namespace App\Console;

use App\Accounts;

use App\Contacts;

use App\helpers;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        //
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->call(function () {
           
           $accounts = Accounts::all();
           
           foreach($accounts as $account){
               
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
        
           }
           
        })->everyThirtyMinutes()->name('Contacts');
        
        $schedule->call(function () {
           
           $accounts = Accounts::all();
           
           $months = array(-1,0,1,2,3); // Gets shifts for the last month as well as the next 3
           
           foreach($accounts as $account){
               
                helpers::enter_shifts($account['username'],$account['password'],$months,$account['id']); 
        
           }
           
        })->everyTenMinutes()->name('Shifts');
    }

    /**
     * Register the Closure based commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        require base_path('routes/console.php');
    }
}

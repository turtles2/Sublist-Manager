<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of the routes that are handled
| by your application. Just tell Laravel the URIs it should respond
| to using a Closure or controller method. Build something great!
|
*/

Route::get('/', function () {
    if (Auth::check())
    {
    return view('home');
    }
    else
    {
    return view('auth/login');
    }
});

Auth::routes();

Route::get('/home', 'HomeController@index');

// Account Management Routes
Route::get('/newaccount', 'AccountController@newaccount');
Route::post('/newaccount', 'AccountController@storenewaccount');

Route::get('/test', function () {
   $crawler = Goutte::request('GET', 'http://www.sublistonline.com');
   
   $form = $crawler->selectButton('Log in')->form();
   $crawler = Goutte::submit($form, array('uname' => 'danwaldron', 'pw' => 'danny9910'));
   
   $message = $crawler->filterXPath('//body/table')->text();
   
   if(str_contains($message, 'You are not logged in')){
       echo 'Loged out';
   }elseif(str_contains($message, 'You are logged in as')){
       echo 'Log in';
   }else{
       echo 'fail';
   }

  dump($message);
});
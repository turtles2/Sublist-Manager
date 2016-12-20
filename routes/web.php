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
// Schedule Management Routes
Route::get('/newschedule', 'ScheduleController@newschedule');
Route::post('/newschedule', 'ScheduleController@storenewschedule');
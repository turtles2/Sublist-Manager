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
Route::get('/delete', 'HomeController@delete');
Route::post('/delete', 'HomeController@storedelete');

// Account Management Routes
Route::get('/newaccount', 'AccountController@newaccount');
Route::post('/newaccount', 'AccountController@storenewaccount');
Route::get('/viewaccount', 'AccountController@viewaccount');
Route::get('/data/viewaccount', 'AccountController@viewaccountdata');
// Schedule Management Routes
Route::get('/newschedule', 'ScheduleController@newschedule');
Route::post('/newschedule', 'ScheduleController@storenewschedule');
Route::get('/syncgoogle', 'ScheduleController@syncgoogle');
Route::post('/syncgoogle', 'ScheduleController@redirectToProvider');
Route::get('/google', 'ScheduleController@handleProviderCallback');

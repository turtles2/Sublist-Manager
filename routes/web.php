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
Route::get('/viewcontacts', 'AccountController@viewcontacts');
Route::get('/data/viewcontacts', 'AccountController@viewcontactsdata');
// Schedule Management Routes
Route::get('/newschedule', 'ScheduleController@newschedule');
Route::post('/newschedule', 'ScheduleController@storenewschedule');
Route::get('/syncgoogle', 'ScheduleController@syncgoogle');
Route::post('/syncgoogle', 'ScheduleController@redirectToProvider');
Route::get('/google', 'ScheduleController@handleProviderCallback');
Route::get('/viewweek', 'ScheduleController@viewweek');
Route::get('/data/viewweek', 'ScheduleController@viewweekdata');
// Shift Management Routes
Route::get('/viewopenshift', 'Shift_Controller@viewopen');
Route::get('/data/viewopenshift', 'Shift_Controller@viewopendata');
Route::get('/viewcovershift', 'Shift_Controller@viewcover');
Route::get('/data/viewcovershift', 'Shift_Controller@viewcoverdata');
Route::get('/data/viewworking/{start}/{end}/{type}', 'Shift_Controller@viewworkingdata');
Route::get('/viewworkingshift', 'Shift_Controller@viewworking');
Route::post('/viewworkingshift', 'Shift_Controller@viewworkingbuild');
// Setting Routes
Route::get('/newloc','Settings_Controller@newlocation');
Route::post('/newloc','Settings_Controller@storelocation');

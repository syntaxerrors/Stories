<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the Closure to execute when that URI is requested.
|
*/


Route::get('logout', function()
{
    Auth::logout();
    return Redirect::to('/')->with('message', 'You have successfully logged out.');
});

// Non-Secure routes
Route::controller('scoreboard', 'ScoreboardController');
Route::get('/rss', 'ScoreboardController@rss');

// Secure routes
Route::group(array('before' => 'auth'), function()
{
    Route::controller('admin', 'AdminController');
	Route::controller('manage', 'ManageController');
    Route::controller('user', 'UserController');
});

// Landing page
Route::controller('/', 'HomeController');

require_once('start/local.php');

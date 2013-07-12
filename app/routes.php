<?php
/**
 * Non-Secure routes
 */

// Landing page
Route::controller('/', 'HomeController');

// Let them logout
Route::get('logout', function()
{
    Auth::logout();
    return Redirect::to('/')->with('message', 'You have successfully logged out.');
});

/**
 * Secure routes
 */
Route::group(array('before' => 'auth'), function()
{
    Route::controller('admin', 'AdminController');
    Route::controller('user', 'UserController');
});



require_once('start/local.php');

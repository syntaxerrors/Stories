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

// Landing page
Route::controller('/', 'HomeController');

Route::get('logout', function()
{
    Auth::logout();
    return Redirect::to('/')->with('message', 'You have successfully logged out.');
});

// Non-Secure routes
Route::group(array('before' => 'auth'), function()
{
	// Route::controller('game/board/', 'GameBoardController');
	// Route::controller('did/', 'DidAdminController');
});

// Secure routes
Route::group(array('before' => 'auth'), function()
{
	// Route::controller('profile'			, 'ProfileController');
	// Route::controller('character'		, 'CharacterController');
	// Route::controller('messages'		, 'MessageController');
	// Route::controller('chat'			, 'ChatController');
	// Route::controller('forum/post'		, 'ForumPostController');
	// Route::controller('forum/board'		, 'ForumBoardController');
	// Route::controller('forum/category'	, 'ForumCategoryController');
	// Route::controller('forum'			, 'ForumController');
	// Route::controller('media'			, 'MediaController');
});
Route::group(array('before' => 'auth|permission:SV_ADMIN'), function()
{
	// Route::controller('admin', 'AdminController');
});
Route::group(array('before' => 'auth|permission:GAME_TEMPLATE_MANAGE'), function()
{
	// Route::controller('game/template'		, 'GameTemplateController');
	// Route::controller('game/template/modify', 'GameTemplateModifyController');
});
Route::group(array('before' => 'auth|permission:GAME_MASTER'), function()
{
	// Route::controller('game'		, 'GameController');
	// Route::controller('game/modify'	, 'GameModifyController');
});
Route::group(array('before' => 'auth|permission:FORUM_ADMIN'), function()
{
	// Route::controller('forum-admin'			, 'ForumAdminController');
	// Route::controller('forum-admin/modify'	, 'ForumAdminModifyController');
});

require_once('start/local.php');

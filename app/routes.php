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

// Non-Secure routes
Route::group(array('before' => 'auth'), function()
{
	// Route::controller('game/board/', 'GameBoardController');
	// Route::controller('did/', 'DidAdminController');
	Route::controller('user', 'UserController');
});

// Secure routes
Route::group(array('before' => 'auth'), function()
{
	Route::controller('profile/{id}'			, 'ProfileController');
	// Route::controller('character'		, 'CharacterController');
	// Route::controller('messages'		, 'MessageController');
	// Route::controller('chat'			, 'ChatController');
	Route::controller('forum/post'		, 'Forum_PostController');
	Route::controller('forum/board'		, 'Forum_BoardController');
	Route::controller('forum/category'	, 'Forum_CategoryController');
	Route::controller('forum'			, 'ForumController');
	// Route::controller('media'			, 'MediaController');
});
Route::group(array('before' => 'auth|permission:SV_ADMIN'), function()
{
	Route::controller('admin', 'AdminController');
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
// Landing page
Route::controller('/', 'HomeController');

require_once('start/local.php');

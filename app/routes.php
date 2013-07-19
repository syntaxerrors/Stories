<?php

// Let them logout
Route::get('logout', function()
{
	Auth::logout();
	return Redirect::to('/')->with('message', 'You have successfully logged out.');
});
// Non-Secure routes
Route::group(array('before' => 'auth'), function()
{
	// Route::controller('did/', 'DidAdminController');
	Route::controller('user'			,'UserController');
	Route::controller('profile/{id}'	, 'ProfileController');
	// Route::controller('character'		, 'CharacterController');
	// Route::controller('messages'		, 'MessageController');
	Route::controller('chat'			, 'ChatController');
	// Route::controller('media'			, 'MediaController');
});

// Secure routes
/********************************************************************
 * Access to gaming system board
 *******************************************************************/
Route::group(array('before' => 'auth|permission:GAME_BOARD'), function()
{
	// Route::controller('game/board/', 'GameBoardController');
});

/********************************************************************
 * Access to the forums
 *******************************************************************/
Route::group(array('before' => 'auth|permission:FORUM_ACCESS'), function()
{
	Route::controller('forum/post'		, 'Forum_PostController');
	Route::controller('forum/board'		, 'Forum_BoardController');
	Route::controller('forum/category'	, 'Forum_CategoryController');
	Route::controller('forum'			, 'ForumController');
});

/********************************************************************
 * Access to the dev panel
 *******************************************************************/
Route::group(array('before' => 'auth|permission:SV_ADMIN'), function()
{
	Route::controller('admin', 'AdminController');
});

/********************************************************************
 * Access to modify game templates
 *******************************************************************/
Route::group(array('before' => 'auth|permission:GAME_TEMPLATE_MANAGE'), function()
{
	// Route::controller('game/template'		, 'GameTemplateController');
	// Route::controller('game/template/modify', 'GameTemplateModifyController');
});

/********************************************************************
 * Access to game master areas
 *******************************************************************/
Route::group(array('before' => 'auth|permission:GAME_MASTER'), function()
{
	// Route::controller('anima'		, 'GameController');
	// Route::controller('game/modify'	, 'GameModifyController');
});

/********************************************************************
 * Access to forum moderation
 *******************************************************************/
Route::group(array('before' => 'auth|permission:FORUM_MOD'), function()
{
	// Route::controller('forum-admin'			, 'ForumAdminController');
	// Route::controller('forum-admin/modify'	, 'ForumAdminModifyController');
});

/********************************************************************
 * Access to forum administration
 *******************************************************************/
Route::group(array('before' => 'auth|permission:FORUM_ADMIN'), function()
{
	// Route::controller('forum-admin'			, 'ForumAdminController');
	// Route::controller('forum-admin/modify'	, 'ForumAdminModifyController');
});

// Landing page
Route::controller('/', 'HomeController');

require_once('start/local.php');

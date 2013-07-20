<?php

/*
|--------------------------------------------------------------------------
| Register The Laravel Class Loader
|--------------------------------------------------------------------------
|
| In addition to using Composer, you may use the Laravel class loader to
| load your controllers and models. This is useful for keeping all of
| your classes in the "global" namespace without Composer updating.
|
*/

ClassLoader::addDirectories(array(

	app_path().'/commands',
	app_path().'/controllers',
	app_path().'/models',
	app_path().'/database/seeds',
	app_path().'/libraries',

));

/*
|--------------------------------------------------------------------------
| Application Error Logger
|--------------------------------------------------------------------------
|
| Here we will configure the error logger setup for the application which
| is built on top of the wonderful Monolog library. By default we will
| build a rotating log file setup which creates a new file each day.
|
*/

$logFile = 'log-'.php_sapi_name().'.txt';

Log::useDailyFiles(storage_path().'/logs/'.$logFile);

/*
|--------------------------------------------------------------------------
| Application Error Handler
|--------------------------------------------------------------------------
|
| Here you may handle any errors that occur in your application, including
| logging them or displaying custom views for specific errors. You may
| even register several error handlers to handle different types of
| exceptions. If nothing is returned, the default error view is
| shown, which includes a detailed stack trace during debug.
|
*/

App::error(function(Exception $exception, $code)
{
	Log::error($exception);
});

/*
|--------------------------------------------------------------------------
| Maintenance Mode Handler
|--------------------------------------------------------------------------
|
| The "down" Artisan command gives you the ability to put an application
| into maintenance mode. Here, you will define what is displayed back
| to the user if maintenace mode is in effect for this application.
|
*/

App::down(function()
{
	return Response::make("Be right back!", 503);
});

/*
|--------------------------------------------------------------------------
| Require The Filters File
|--------------------------------------------------------------------------
|
| Next we will load the filters file for the application. This gives us
| a nice separate location to store our route and application filter
| definitions instead of putting them all in the main routes file.
|
*/

require app_path().'/filters.php';

/********************************************************************
 * Events
 *******************************************************************/
function findExistingReferences($model)
{
	$invalid = true;
	while ($invalid == true) {
		$uniqueString = Str::random(10);

		$existingReferences = $model::where('uniqueId', '=', $uniqueString)->count();

		if ($existingReferences == 0) {
			$invalid = false;
		}
	}

	return $uniqueString;
}

Forum_Category::creating(function($object)
{
	$object->uniqueId = findExistingReferences('Forum_Category');
});
Forum_Board::creating(function($object)
{
	$object->uniqueId = findExistingReferences('Forum_Board');
});
Forum_Post::creating(function($object)
{
	$object->uniqueId = findExistingReferences('Forum_Post');
});
Forum_Reply::creating(function($object)
{
	$object->uniqueId = findExistingReferences('Forum_Reply');
});
User::creating(function($object)
{
	$object->uniqueId = findExistingReferences('User');
});
Chat_Room::creating(function($object)
{
	$object->uniqueId = findExistingReferences('Chat_Room');
});
Game::creating(function($object)
{
	$object->uniqueId = findExistingReferences('Game');
});
Game_Type::creating(function($object)
{
	$object->uniqueId = findExistingReferences('Game_Type');
});
// Anima_Character::creating(function($object)
// {
// 	$object->uniqueId = findExistingReferences('Anima_Character');
// });
// Anima_Entity::creating(function($object)
// {
// 	$object->uniqueId = findExistingReferences('Anima_Entity');
// });
// Anima_Enemy::creating(function($object)
// {
// 	$object->uniqueId = findExistingReferences('Anima_Enemy');
// });
// Anima_Horde::creating(function($object)
// {
// 	$object->uniqueId = findExistingReferences('Anima_Horde');
// });
// Anima_Magic_Type::creating(function($object)
// {
// 	$object->uniqueId = findExistingReferences('Anima_Magic_Type');
// });
// Anima_Game_Event::creating(function($object)
// {
// 	$object->uniqueId = findExistingReferences('Anima_Game_Event');
// });
// Anima_Game_Quest::creating(function($object)
// {
// 	$object->uniqueId = findExistingReferences('Anima_Game_Quest');
// });
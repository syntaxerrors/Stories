<?php

class GameController extends BaseController {

	public function subLinks($gameId)
	{
		$this->addSubMenu('Manage Game','game/manage/'. $gameId);
		$this->addSubMenu('Manage Details', 'game/manageDetails/'. $gameId);
	}

	public function getIndex($gameId = null, $property = null, $value = null)
	{
		// If passed values, handle them first
		if ($gameId != null) {
			$game = Game::find($gameId);
			$game->{$property} = $value;
			$game->save();
			$this->redirect('game', null);
		}

		// Set the template details
		$games = Game::with(array('storytellers', 'storytellers.user', 'forum'))->orderByNameAsc()->get();
		$this->setViewData('games', $games);
	}

	public function getConfig($gameId)
	{
		$game        = Game::find($gameId);
		$configs     = Game_Config::orderByNameAsc()->get();
		$gameConfigs = Game_Config_Game::where('game_id', $gameId)->get();

		$this->setViewData('game', $game);
		$this->setViewData('configs', $configs);
		$this->setViewData('gameConfigs', $gameConfigs);
	}

	public function getAdd()
	{
		// Set the template details
		$types = $this->arrayToSelect(Game_Type::orderByNameAsc()->get(), 'id', 'name', 'Select a game type');
		$this->setViewData('types', $types);
	}

	public function postAdd()
	{
		// Handle any form inputs
		$input = Input::all();
		if ($input != null) {
			$game               = new Game;
			$game->game_type_id = $input['game_type_id'];
			$game->name         = $input['name'];
			$game->keyName      = Str::slug($input['name']);
			$game->description  = $input['description'];
			$game->activeFlag   = (isset($input['activeFlag']) ? 1 : 0);

			$game->save();

			$this->checkErrorsRedirect($game);

			$storyTeller = new Game_StoryTeller(array('game_id' => $game->id, 'user_id' => $this->activeUser->id));

			$game->storytellers()->save($storyTeller);

			return Redirect::to('game')->with('message', $game->name.' has been created.');
		}
	}

	public function action_edit($gameId)
	{
		// Set the template details
		$game      = Game::find($gameId);
		$templates = $this->arrayToSelect(Game\Template::order_by('name', 'asc')->get(), 'id', 'name', 'Select a template');
		$this->setTemplate(array('game' => $game, 'templates' => $templates));

		// Handle any form inputs
		$input = Input::all();
		if ($input != null) {
			$game->game_template_id = $input['game_template_id'];
			$game->name             = $input['name'];
			$game->description      = $input['description'];
			$game->activeFlag       = (isset($input['activeFlag']) ? 1 : 0);
			$game->hitPointsName    = $input['hitPointsName'];
			$game->magicPointsName  = $input['magicPointsName'];

			$game->save();

			if (count($game->errors->all()) > 0){
				return Redirect::to(URI::current())->with_errors($game->errors->all());
			} else {
				return Redirect::to('game')->with('message', $game->name.' has been edited.');
			}
		}
	}

	public function action_delete($gameId)
	{
		$game = Game::find($gameId);
		$game->delete();
		return Redirect::to('game')->with('message', $game->name.' has been deleted.');
	}

	public function action_memberlist($gameId)
	{
		// Get the characters
		$game = Game::find($gameId);
		$characters = Character::where('game_id', $gameId)->active()->where('npcFlag', 0)->orderByNameAsc()->get();

		$this->setViewData('game', $game);
		$this->setViewData('characters', $characters);
	}
}
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

	public function getAccess()
	{
		$gameTypes = Game_Type::orderByNameAsc()->get();
		$this->setViewData('gameTypes', $gameTypes);
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
		// Set up the wizard required settings
		$settings               = new stdClass();
		$settings->viewLocation = 'game.components.create';
		$settings->stepBadges   = array
		(
			1 => 'Game Details',
			2 => 'Forum',
			3 => 'Chat Room',
			4 => 'Story-Tellers',
			5 => 'Configuration',
			6 => 'Confirm',
		);

		// Get any data the pages need
		$types   = $this->arrayToSelect(Game_Type::orderByNameAsc()->get(), 'id', 'name', 'Select a game type');
		$users   = User::orderBy('username', 'asc')->get();
		$configs = Game_Config::orderByNameAsc()->get();

		$this->setViewPath('helpers.wizard');
		$this->setViewData('settings', $settings);
		$this->setViewData('types', $types);
		$this->setViewData('users', $users);
		$this->setViewData('configs', $configs);
	}

	public function postAdd()
	{
		// Handle any form inputs
		$input = Input::all();

		if ($input != null) {

			// Make sure that the needed data exists
			$errors = array();
			if ($input['name'] == null) {
				$errors[] = 'You must set a name for your game to use.';
			}
			if ($input['game_type_id'] == '0') {
				$errors[] = 'You must select a game type.';
			}

			// Redirect if the base requirements are not met
			if (count($errors) > 0) {
				return Redirect::to('game/add')->withInput()->with('errors', $errors);
			}

			// Create the base game
			$game               = new Game;
			$game->game_type_id = $input['game_type_id'];
			$game->name         = $input['name'];
			$game->keyName      = Str::slug($input['name']);
			$game->description  = $input['description'];
			$game->activeFlag   = (isset($input['activeFlag']) ? 1 : 0);

			$game->save();

			if ($this->checkErrors($game)) {
				$errors = array_merge($errors, $game->getErrors()->all());
			}

			// Redirect if the game can't be created
			if (count($errors) > 0) {
				return Redirect::to('game/add')->withInput()->with('errors', $errors);
			}

			// Create anything needed in the forums
			if (isset($input['addCategoryFlag'])) {

				$firstCategory = Forum_Category::orderBy('position', 'desc')->first();
				if ($firstCategory != null) {
					$position = $firstCategory->position + 1;
				} else {
					$position = 1;
				}

				// Create the forum category
				$category                         = new Forum_Category;
				$category->name                   = ($input['addCategoryName'] != null ? $input['addCategoryName'] : $input['name']);
				$category->keyName                = Str::slug($category->name);
				$category->forum_category_type_id = Forum_Category::TYPE_GAME;
				$category->position               = $position;
				$category->game_id                = $game->id;

				$category->save();

				if ($this->checkErrors($category)) {
					$errors = array_merge($errors, $category->getErrors()->all());
				}

				if (isset($input['addApplicationBoardFlag'])) {
					// Create the application board
					$board                      = new Forum_Board;
					$board->name                = ($input['addApplicationBoardName'] != null ? $input['addApplicationBoardName'] : 'Applications');
					$board->forum_category_id   = $category->id;
					$board->forum_board_type_id = Forum_Board::TYPE_APPLICATION;
					$board->keyName             = $board->name;

					$board->save();

					if ($this->checkErrors($board)) {
						$errors = array_merge($errors, $board->getErrors()->all());
					}
				}
			}

			// Create any requested chat rooms
			if ($input['chatRooms'] != null) {
				$chatRooms = explode(',', $input['chatRooms']);

				foreach ($chatRooms as $chatRoom) {
					$room                   = new Chat_Room;
					$room->user_id          = $this->activeUser->id;
					$room->game_id          = $game->id;
					$room->name             = trim($chatRoom);
					$room->activeFlag       = 1;

					$room->save();

					if ($this->checkErrors($room)) {
						$errors = array_merge($errors, $room->getErrors()->all());
					}

				}
			}

			// Add the active user as the default story-teller
			$storyTeller = new Game_StoryTeller;
			$storyTeller->user_id = $this->activeUser->id;
			$storyTeller->game_id = $game->id;

			$storyTeller->save();

			if ($this->checkErrors($storyTeller)) {
				$errors = array_merge($errors, $storyTeller->getErrors()->all());
			}

			// If any other story-tellers need to be added
			if (isset($input['users']) && count($input['users']) > 0) {
				foreach ($input['users'] as $userId => $value) {
					$storyTeller = new Game_StoryTeller;
					$storyTeller->user_id = $userId;
					$storyTeller->game_id = $game->id;

					$storyTeller->save();

					if ($this->checkErrors($storyTeller)) {
						$errors = array_merge($errors, $storyTeller->getErrors()->all());
					}
				}
			}

			// Set any game configs
			if (isset($input['configs']) && count($input['configs']) > 0) {

				foreach ($input['configs'] as $configId => $value) {
					if ($configId == 'CHARACTER_ROLLS' && $value == '') {
						continue;
					}
					$config                 = new Game_Config_Game;
					$config->game_id        = $game->id;
					$config->game_config_id = $configId;
					$config->value          = ($value == 'on' ? 1 : $value);

					$config->save();

					if ($this->checkErrors($config)) {
						$errors = array_merge($errors, $config->getErrors()->all());
					}
				}
			}

			if (count($errors) > 0) {
				return Redirect::to('game/add')->withInput()->with('errors', $errors);
			}

			return Redirect::to('game')->with('message', $game->name.' has been created.');
		}
	}

	public function postSetgameoptions()
	{
		$this->skipView = true;
		$input = Input::all();

		if ($input != null) {
			// Set up the default options
			$gameOptions                       = array();
			$gameOptions['gameName']           = $input['name'];
			$gameOptions['gameType']           = ($input['game_type_id'] != '0' ? Game_Type::find($input['game_type_id'])->name : 'None given.');
			$gameOptions['gameDescription']    = ($input['description'] != null ? $input['description'] : 'No description given.');
			$gameOptions['gameActive']         = (isset($input['activeFlag']) ? 'Active' : 'Inactive');
			$gameOptions['categoryDetails']    = 'No new category will be added to the forums.';
			$gameOptions['applicationDetails'] = 'No application board will be added to the forums.';
			$gameOptions['chatRooms']          = 'No chat rooms will be created for your game.';
			$gameOptions['storyTellers']       = 'You will be the only story teller for this game.';
			$gameOptions['configs']            = 'This will be a default game with no config options turned on.';

			// If adding forum details
			if (isset($input['addCategoryFlag'])) {
				$gameOptions['categoryDetails'] = 'A new game Category will be added to the forums.  The category will be titled: '. ($input['addCategoryName'] != null ? $input['addCategoryName'] : $input['name']) .'.';

				if (isset($input['addApplicationBoardFlag'])) {
					$gameOptions['applicationDetails'] = 'An application board will be created with the title: '. ($input['addApplicationBoardName'] != null ? $input['addApplicationBoardName'] : 'Applications');
				}
			}

			// If creating chat rooms
			if ($input['chatRooms'] != null) {
				$gameOptions['chatRooms'] = 'The following chat rooms will be created:<br />';
				$chatRooms = explode(',', $input['chatRooms']);

				foreach ($chatRooms as $chatRoom) {
					$gameOptions['chatRooms'] .= $chatRoom .'<br />';
				}
			}

			// If setting story tellers
			if (isset($input['users']) && count($input['users']) > 0) {
				$gameOptions['storyTellers'] = 'The following users will be made story-tellers along with yourself.<br />';

				foreach ($input['users'] as $userId => $value) {
					$gameOptions['storyTellers'] .= User::find($userId)->username .'<br />';
				}
			}

			// If specifying any configurations
			if (isset($input['configs']) && count($input['configs']) > 0) {
				$gameOptions['configs'] = 'The following configuration options will be used for this game.<br />';

				foreach ($input['configs'] as $configId => $value) {
					if ($configId == 'CHARACTER_ROLLS' && $value == '') {
						continue;
					}
					$gameOptions['configs'] .= Game_Config::find($configId)->name;
					if ($configId == 'CHARACTER_ROLLS') {
						$gameOptions['configs'] .= ' using '. $value;
					}
					$gameOptions['configs'] .= '<br />';
				}
			}

			// Return as jason to add into the correct places
			return json_encode($gameOptions);
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
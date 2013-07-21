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

	public function action_manageDetails($gameId)
	{
		// Add links
		$this->subLinks($gameId);

		$game = Game::where('slug', '=', $gameId)->first();

		$this->setTemplate(array('gameId' => $game->id));
	}

	public function action_manage($gameId = null)
	{
		// Add links
		$this->subLinks($gameId);

		// Set the template details
		if ($gameId != null) {
			$game        = Game::with(array('storytellers', 'storytellers.user', 'characters', 'notes'))->where('slug', '=', $gameId)->first();
			$forum       = new Forum;
			if ($game->forum != null) {
				$recentPosts = $forum->recentCategoryPosts($game->forum->id);
			} else {
				$recentPosts = array();
			}
			$this->setTemplate(array('game' => $game, 'recentPosts' => $recentPosts));
		} else {
			$this->setTemplate();
		}

		// Handle form input
		$input = Input::all();

		if ($input != null) {
			$character = Character::find($input['character_id']);
			if (isset($input['exp'])) {
				$character->addExperience($input['exp'], $this->activeUser->id, $input['reason']);
			}
			return Redirect::to(URI::current())->with('message', $character->name .' has been granted '. $input['exp'] .' experience points.');
		}
	}

	public function action_update($resourceId, $property, $value, $type = 'character')
	{
		switch ($type) {
			case 'character':
				$resource = Character::find($resourceId);
			break;
			case 'post':
				$resource = Forum\Post::find($resourceId);
			break;
			case 'reply':
				$resource = Forum\Reply::find($resourceId);
			break;
			case 'tree':
				$resource = Game\Template\Magic\Tree::find($resourceId);
			break;
			case 'spell':
				$resource = Game\Template\Spell::find($resourceId);
			break;
			case 'characterSpell':
				$resource = Character\Spell::find($resourceId);
			break;
		}
		$resource->{$property} = $value;
		$resource->save();
		return Redirect::back()->with('message', $resource->name .' successfully updated.');
	}

	public function action_denySpell($spellId)
	{
		$spell = Game\Template\Spell::find($spellId);
		$spell->delete();
		return Redirect::back()->with('message', 'Spell has been denied.');
	}

	public function action_denyCharacterSpell($spellId)
	{
		$spell = Character\Spell::find($spellId);
		$spell->delete();
		return Redirect::back()->with('message', 'Spell has been denied.');
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
		$storyTellers = Game\StoryTeller::where('game_id', '=', $game->id)->get();
		if (count($storyTellers) > 0) {
			foreach ($storyTellers as $storyTeller) {
				$storyTeller->delete();
			}
		}
		$notes = Game\Note::where('game_id', '=', $game->id)->get();
		if (count($notes) > 0) {
			foreach ($notes as $note) {
				$note->delete();
			}
		}
		return Redirect::to('game')->with('message', $game->name.' has been deleted.');
	}

	public function action_denyTree($treeId)
	{
		$tree = Game\Template\Magic\Tree::find($treeId);
		$tree->delete();
		return Redirect::back()->with('message', $tree->name .' has been denied.');
	}

	public function action_memberlist($gameId)
	{
		// Get the characters
		$game = Game::find($gameId);
		$characters = Character::where('game_id', '=', $gameId)->where('activeFlag', '=', 1)->where('npcFlag', '=', 0)->order_by('name', 'ASC')->get();
		$this->setTemplate(array('game' => $game, 'characters' => $characters));
	}
}
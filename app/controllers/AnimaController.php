<?php

class AnimaController extends BaseController {

	public function getIndex($gameId = null)
	{
		// Add links
		// $this->subLinks($gameSlug);

		// Set the template details
		if ($gameId != null) {
			$game        = Game::find($gameId);
			$forum       = new Forum;
			if ($game->forum != null) {
				$recentPosts = $forum->recentCategoryPosts($game->forum->id);
			} else {
				$recentPosts = array();
			}

			$this->setViewData('game', $game);
			$this->setViewData('recentPosts', $recentPosts);
		} else {
			$this->setTemplate();
		}
	}

	public function postIndex($gameId)
	{
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

	public function getCharacters()
	{
		$this->setViewData('test', 'test');
	}

}
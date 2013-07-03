<?php

class ManageController extends BaseController {

	public function getIndex()
	{
		$episodes = Episode::orderBy('date', 'desc')->paginate(20);

		$this->setViewData('episodes', $episodes);
	}

	public function getAdd($seriesId = null, $gameId = null)
	{
		$this->checkPermission('ADD_EPISODES');

		// Get all data
		$series   = $this->arrayToSelect(Series::orderByNameAsc()->get());
		$games    = $this->arrayToSelect(Game::orderByNameAsc()->get());
		$episodes = $this->arrayToSelect(Episode::orderBy('title', 'asc')->get(), 'id', 'title');

		$this->setViewData('series', $series);
		$this->setViewData('games', $games);
		$this->setViewData('episodes', $episodes);
		$this->setViewData('seriesId', $seriesId);
		$this->setViewData('gameId', $gameId);
	}

	public function postAdd($seriesId = null, $gameId = null)
	{
		$this->checkPermission('ADD_EPISODES');

		$input = e_array(Input::all());

		if ($input != null) {
			$episode               = new Episode;
			$episode->series_id    = $input['series_id'];
			$episode->game_id      = $input['game_id'];
			$episode->parentId     = ($input['parentId'] > 0 ? $input['parentId'] : null);
			$episode->seriesNumber = $input['seriesNumber'];
			$episode->title        = $input['title'];

			$link = str_replace(array('http://www.youtube.com/watch?v=', 'https://www.youtube.com/watch?v='), '', $input['link']);

			if (strpos($link, '&') !== false) {
				$link = substr($link, 0, strpos($link, '&'));
			}

			$episode->link = $link;
			$episode->date = date('Y-m-d', strtotime($input['date']));

			$episode->save();

			$this->checkErrorsRedirect($episode);

			if (isset($input['winners'])) {
				return Redirect::to('/manage/winners/'. $episode->id)->with('message', $episode->game->name .' '. $episode->seriesNumber .':'. $episode->title .' has been submitted.');
			} elseif (isset($input['continue'])) {
				return Redirect::to(Request::path())->with('message', $episode->game->name .' '. $episode->seriesNumber .':'. $episode->title .' has been submitted.');
			} else {
				return Redirect::to('/manage')->with('message', $episode->game->name .' '. $episode->seriesNumber .':'. $episode->title .' has been submitted.');
			}
		}
	}

	public function getEdit($episodeId)
	{
		$this->checkPermission('ADD_EPISODES');

		$episode  = Episode::find($episodeId);
		$series   = $this->arrayToSelect(Series::orderByNameAsc()->get());
		$games    = $this->arrayToSelect(Game::orderByNameAsc()->get());
		$episodes = $this->arrayToSelect(Episode::orderBy('title', 'asc')->get(), 'id', 'title');

		$this->setViewData('episode', $episode);
		$this->setViewData('series', $series);
		$this->setViewData('games', $games);
		$this->setViewData('episodes', $episodes);
	}

	public function postEdit($episodeId)
	{
		$this->checkPermission('ADD_EPISODES');

		$input = e_array(Input::all());

		if ($input != null) {
			$episode               = Episode::find($episodeId);
			$episode->series_id    = $input['series_id'];
			$episode->game_id      = $input['game_id'];
			$episode->parentId     = ($input['parentId'] > 0 ? $input['parentId'] : null);
			$episode->seriesNumber = $input['seriesNumber'];
			$episode->title        = $input['title'];

			$link = str_replace(array('http://www.youtube.com/watch?v=', 'https://www.youtube.com/watch?v='), '', $input['link']);

			if (strpos($link, '&') !== false) {
				$link = substr($link, 0, strpos($link, '&'));
			}

			$episode->link = $link;
			$episode->date = date('Y-m-d', strtotime($input['date']));

			$episode->save();

			$this->checkErrorsRedirect($episode);

			return Redirect::to('/manage')->with('message', $episode->game->name .' '. $episode->seriesNumber .':'. $episode->title .' has been submitted.');
		}
	}

	public function getWinners($episodeId)
	{
		$this->checkPermission('ADD_STATS');

		$episode = Episode::find($episodeId);
		$teams   = $this->arrayToSelect(Team::orderByNameAsc()->get());
		$members = $this->arrayToSelect(Member::orderByNameAsc()->get());

		$this->setViewData('episode', $episode);
		$this->setViewData('teams', $teams);
		$this->setViewData('members', $members);
	}

	public function postWinners($episodeId)
	{
		$this->checkPermission('ADD_STATS');

		$input  = Input::all();
		$errors = array();

		// Handle the form data
		if ($input != null) {

			// Get the episode
			$episode = Episode::find($episodeId);

			// Delete all existing records
			$wins = Episode_Win::where('episode_id', '=', $episode->id)->get();

			if (count($wins) > 0) {
				foreach ($wins as $win) {
					$win->delete();
				}
			}

			// Only run if any members are added
			if (count($input['members']) > 0) {

				foreach ($input['members'] as $member) {

					// Skip any with no member selected
					if ($member == 0) {
						continue;
					}

					// Add the win
					$win                = new Episode_Win;
					$win->episode_id    = $episode->id;
					$win->winmorph_id   = $member;
					$win->winmorph_type = 'Member';
					$win->save();

					// Set any errors
					if (count($win->getErrors()->all()) > 0){
						$errors[] = implode('<br />', $win->getErrors()->all());
					}
				}
			}

			// Only run if any teams are added
			if (count($input['teams']) > 0) {

				foreach ($input['teams'] as $team) {
					// Skip any with no team selected
					if ($team == 0) {
						continue;
					}

					// Add the win
					$win                = new Episode_Win;
					$win->episode_id    = $episode->id;
					$win->winmorph_id   = $team;
					$win->winmorph_type = 'Team';
					$win->save();

					// Set any errors
					if (count($win->getErrors()->all()) > 0){
						$errors[] = implode('<br />', $win->getErrors()->all());
					}
				}
			}

			if (count($errors) > 0){
				return Redirect::to(Request::path())->with('errors', $errors);
			} else {
				if (isset($input['nextEpisode'])) {
					$nextEpisode = Episode::where('date', '>=', $episode->date)->where('id', '!=', $episode->id)->where('series_id', '=', $episode->series_id)->orderBy('date', 'asc')->first();

					if (isset($nextEpisode->id)) {
						return Redirect::to('/manage/winners/'. $nextEpisode->id)->with('message', 'Winners added to '. $episode->title.'.');
					} else {
						return Redirect::to('/manage')->with('message', 'Winners added to '. $episode->title.'.');
					}
				} elseif (isset($input['addEpisode'])) {
					return Redirect::to('/manage/add')->with('message', 'Winners added to '. $episode->title.'.');
				} else {
					return Redirect::to('/manage')->with('message', 'Winners added to '. $episode->title.'.');
				}
			}
		}
	}

	public function getGetwins($episodeId)
	{
		$episode = Episode::find($episodeId);

		$this->setViewData('episode', $episode);
	}
}
<?php

class ScoreboardController extends BaseController {

    public function getIndex()
    {
        // Get all data
        $series = Series::all();
        $games = Game::orderByNameAsc()->get();
        $teams = Team::all();
        $members = Member::all();

        $this->setViewData('series', $series);
        $this->setViewData('games', $games);
        $this->setViewData('teams', $teams);
        $this->setViewData('members', $members);
    }
}
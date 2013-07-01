<?php

class ScoreboardController extends BaseController {

    public function getIndex()
    {
        // Get all data
        $series  = Series::all();
        $games   = Game::orderByNameAsc()->get();
        $teams   = Team::all();
        $members = Member::all();

        $this->setViewData('series', $series);
        $this->setViewData('games', $games);
        $this->setViewData('teams', $teams);
        $this->setViewData('members', $members);
    }

    public function postGetscores()
    {
        $input   = Input::all();

        $seriesIds  = array(0);
        $gameIds    = array(0);
        $episodeIds = array(0);

        if (isset($input['series']) && count($input['series']) > 0) {
            $series    = Series::whereIn('keyName', $input['series'])->get();
            $seriesIds = $series->id->toArray();
        }
        if (isset($input['games']) && count($input['games']) > 0) {
            $games   = Game::whereIn('keyName', $input['games'])->get();
            $gameIds = $games->id->toArray();
        }
        if (isset($input['teams']) && count($input['teams']) > 0) {
            $teams      = Episode_Win::whereIn('winmorph_id', $input['teams'])->where('winmorph_type', '=', 'Team')->get();
            $episodeIds = $teams->episode_id->toArray();
        }
        if (isset($input['members']) && count($input['members']) > 0) {
            $members = Episode_Win::whereIn('winmorph_id', $input['members'])->where('winmorph_type', '=', 'Member')->get();
            if ($episodeIds[0] == 0) {
                $episodeIds = $members->episode_id->toArray();
            } else {
                $episodeIds = array_merge($episodeIds, $members->episode_id->toArray());
            }
        }

        $episodeIds = array_unique($episodeIds);

        if ($gameIds[0] == 0 && $seriesIds[0] == 0 && $episodeIds[0] == 0) {
            $episodes = Episode::orderBy('date', 'desc')->get();
        } else {
            $episodes = Episode::whereIn('series_id', $seriesIds)
                                ->orWhereIn('game_id', $gameIds)
                                ->orWhereIn('id', $episodeIds)
                                ->orderBy('date', 'desc')
                                ->get();
        }

        $episodeList = $episodes;

        $baseModel  = new BaseModel;
        $winDetails = $baseModel->winDetails($episodes->wins);
        $playlist   = $baseModel->playlist($episodeList);

        $this->setViewData('episodes', $episodeList);
        $this->setViewData('winDetails', $winDetails);
        $this->setViewData('playlist', $playlist);
    }

    public function rss()
    {
        $episodes = Episode::orderBy('date', 'desc')->get();

        $xml ='<?xml version="1.0"?>' .
        '<rss version="2.0">' .
            '<channel>' .
                '<title>Achievement Hunter Scoreboard</title>' .
                '<description>Tracking wins of the AH guys through Lets Play, Versus and Things to Do In.</description>' .
                '<link>http://ahscoreboard.com/scoreboard</link>';

        foreach ($episodes as $episode) {
            if ($episode->wins->count() == 1) {
                $winner = 'Winner';
            } else {
                $winner = 'Winners';
            }

            $winners = array();
            foreach ($episode->wins as $win) {
                pp($win->winmorph->name);
                $winners[] = $win->winmorph->name;
            }

            $xml .=
                    '<item>' .
                        '<title>'. e($episode->series->name) .' '. e($episode->game->name) .' Episode '. $episode->seriesNumber .':  '. $episode->title .' </title>' .
                        '<description>'. $winner .':<![CDATA['. implode('<br />', $winners) .']]></description>' .
                        '<link>Test</link>' .
                    '</item>';
        }
        $xml .=
            '</channel>' .
        '</rss>';

        ppd($xml);

        $this->setViewData('xml', $xml);
    }
}
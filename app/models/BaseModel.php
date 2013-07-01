<?php

use Awareness\Aware\Model;

class BaseModel extends Model {

    /**
     * Order by created_at ascending scope
     *
     * @param array $query The current query to append to
     */
    public function scopeOrderByCreatedAsc($query)
    {
        return $query->orderBy('created_at', 'asc');
    }

    /**
     * Order by name ascending scope
     *
     * @param array $query The current query to append to
     */
    public function scopeOrderByNameAsc($query)
    {
        return $query->orderBy('name', 'asc');
    }

    /**
     * Get human readable created_at column
     *
     * @return string
     */
    public function getCreatedAtAttribute($value)
    {
        return date('F jS, Y \a\t h:ia', strtotime($value));
    }

    /**
     * Use the custom collection that allows tapping
     * 
     * @return Utility_Collection[]
     */
    public function newCollection(array $models = array())
    {
        return new Utility_Collection($models);
    }

    public function getNameAttribute($value)
    {
        return stripslashes($value);
    }

    /********************************************************************
     * Extra Methods
     *******************************************************************/

    public function winDetails($wins)
    {
        $winners = $wins->winmorph->name->toArray();
        $winners = array_count_values($winners);
        arsort($winners);

        $winnerDetails = new stdClass();

        foreach ($winners as $winner => $count) {
            $winObjects = array();
            $winnerObject = $wins->filter(function($win) use ($winner)
            {
                if ($win->winmorph->name == $winner) {
                    return $win;
                }
            });
            
            foreach ($winnerObject as $win) {
                if (!array_key_exists($win->episode_id, $winObjects)) {
                    $winObjects[$win->episode_id] = $win;
                }
            }

            $winnerDetails->{$winner} = new stdClass();
            $winnerDetails->{$winner}->count = $count;
            $winnerDetails->{$winner}->object = $winObjects;
        }

        return $winnerDetails;
    }

    public function playlist($episodes)
    {
        // Sort oldest to newest
        $episodeSort = $episodes;
        $episodeSort = $episodeSort->sortBy(function($episode)
            {
                return $episode->date;
            }
        );

        // Start the playlist link
        $link = 'http://www.youtube.com/v/';
        $link .= $episodeSort->first()->link;

        // Finish the playlist
        $links = $episodeSort->link->toArray();
        unset($links[0]);

        $link .= '?version=3&autoplay=1&playlist='. implode(',', $links);

        return $link;
    }
}
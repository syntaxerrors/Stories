<?php

class Episode extends BaseModel {
	/********************************************************************
	 * Declarations
	 *******************************************************************/

	/********************************************************************
	 * Aware validation rules
	 *******************************************************************/

    /**
     * Validation rules
     *
     * @static
     * @var array $rules All rules this model must follow
     */
	public static $rules = array(
		'series_id' => 'required|exists:series,id',
		'game_id'   => 'required|exists:games,id',
		'parentId'  => 'exists:episodes,id',
		'title'     => 'required|max:200',
		'link'      => 'required|max:200',
	);

	/********************************************************************
	 * Scopes
	 *******************************************************************/

	/********************************************************************
	 * Relationships
	 *******************************************************************/

    /**
     * Series Relationship
     *
     * @return Series
     */
	public function series()
	{
		return $this->belongsTo('Series');
	}

    /**
     * Game Relationship
     *
     * @return Game
     */
	public function game()
	{
		return $this->belongsTo('Game');
	}

    /**
     * Episode Relationship
     *
     * @return Episode
     */
	public function parent()
	{
		return $this->belongsTo('Episode', 'parentId');
	}

    /**
     * Win Relationship
     *
     * @return Win
     */
	public function wins()
    {
        return $this->hasMany('Episode_Win');
    }

	/********************************************************************
	 * Getter and Setter methods
	 *******************************************************************/
	public function getTitleAttribute($value)
	{
		return stripslashes($value);
	}

	/********************************************************************
	 * Extra Methods
	 *******************************************************************/
}
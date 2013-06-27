<?php

class Episode_Win extends BaseModel {
	/********************************************************************
	 * Declarations
	 *******************************************************************/

	/**
	 * Table declaration
	 *
	 * @var string $table The table this model uses
	 */
	protected $table = 'episode_winners';
	protected $fillable = array('episode_id', 'winmorph_id', 'winmorph_type');

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
		'episode_id'   => 'required|exists:episodes,id',
		'winmorph_id' => 'required',
		'winmorph_type' => 'required',
	);

	/********************************************************************
	 * Scopes
	 *******************************************************************/

	/********************************************************************
	 * Relationships
	 *******************************************************************/

	/**
     * Episode Relationship
     *
     * @return Episode
     */
	public function episode()
	{
		return $this->belongsTo('Episode');
	}

	/**
     * Winner Relationship
     *
     * @return Member|Team
     */
	public function winmorph()
	{
		return $this->morphTo();
	}

	/********************************************************************
	 * Getter and Setter methods
	 *******************************************************************/

	/********************************************************************
	 * Extra Methods
	 *******************************************************************/
}
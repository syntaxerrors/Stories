<?php

class Game_Character_Game extends BaseModel
{
	/********************************************************************
	 * Declarations
	 *******************************************************************/

	/**
	 * Table declaration
	 *
	 * @var string $table The table this model uses
	 */
	protected $table    = 'game_characters';

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
		'game_id'      => 'required|exists:games,uniqueId',
		'character_id' => 'exists:characters,uniqueId',
	);

	/********************************************************************
	 * Scopes
	 *******************************************************************/

	/********************************************************************
	 * Relationships
	 *******************************************************************/

    /**
     * Game Relationship
     *
     * @return Game[]
     */
	public function game()
	{
		return $this->belongsTo('Game');
	}

    /**
     * Character Relationship
     *
     * @return Character[]
     */
	public function character()
	{
		return $this->belongsTo('Character');
	}

	/********************************************************************
	 * Getter and Setter methods
	 *******************************************************************/

	/********************************************************************
	 * Extra Methods
	 *******************************************************************/
}
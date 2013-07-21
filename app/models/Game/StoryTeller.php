<?php
/**
 * Games
 *
 * This class provides the available games
 *
 *
 * @author      Stygian <stygian,warlock.v2@gmail.com>
 * @package     Games System
 * @subpackage	Games
 * @version     0.1
 */

class Game_StoryTeller extends BaseModel
{
	/********************************************************************
	 * Declarations
	 *******************************************************************/

	/**
	 * Table declaration
	 *
	 * @var string $table The table this model uses
	 */
	protected $table = 'game_storytellers';
	protected $fillable = array('game_id', 'user_id');

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
		'game_id' => 'required|exists:games,uniqueId',
		'user_id' => 'required|exists:users,uniqueId',
		'character_id' => 'exists:characters,uniqueId',
	);

	/********************************************************************
	 * Scopes
	 *******************************************************************/

	/********************************************************************
	 * Relationships
	 *******************************************************************/

    /**
     * User Relationship
     *
     * @return User[]
     */
	public function user()
	{
		return $this->belongsTo('User');
	}

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

	/**
	 * Get the name of the user
	 *
	 * @return string
	 */
	public function getUsernameAttribute()
	{
		return $this->user()->first()->username;
	}

	/**
	 * Get the name of the character
	 *
	 * @return string
	 */
	public function getCharacterNameAttribute()
	{
		return $this->character()->first()->name;
	}

	/********************************************************************
	 * Extra Methods
	 *******************************************************************/
}
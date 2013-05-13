<?php

namespace Game\History;
use Laravel;
use Aware;

class Character extends Aware
{
	/********************************************************************
	 * Declarations
	 *******************************************************************/
	public static $table = 'game_history_characters';

	/********************************************************************
	 * Aware validation rules
	 *******************************************************************/
	public static $rules = array(
		'game_history_id' => 'required|exists:game_history,id',
		'character_id'    => 'required|exists:characters,id',
	);

	/********************************************************************
	 * Relationships
	 *******************************************************************/
	public function history()
	{
		return $this->belongs_to('Game\history', 'game_history_id');
	}
	public function character()
	{
		return $this->belongs_to('Character', 'character_id');
	}

	/********************************************************************
	 * Getter and Setter methods
	 *******************************************************************/

	/**
	 * Make the created_at easier to read
	 *
	 * @return string
	 */
	public function get_created_at()
	{
		return date('F jS, Y \a\t h:ia', strtotime($this->get_attribute('created_at')));
	}

	/**
	 * Get the name of the history week
	 *
	 * @return string
	 */
	public function get_history_week()
	{
		return $this->history()->first()->week;
	}

	/**
	 * Get the name of the character
	 *
	 * @return string
	 */
	public function get_character_name()
	{
		return $this->character()->first()->week;
	}

	/********************************************************************
	 * Extra Methods
	 *******************************************************************/
}
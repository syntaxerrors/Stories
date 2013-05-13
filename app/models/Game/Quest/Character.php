<?php

namespace Game\Quest;
use Laravel;
use Aware;

class Character extends Aware
{
	/********************************************************************
	 * Declarations
	 *******************************************************************/
	public static $table = 'game_quest_characters';

	/********************************************************************
	 * Aware validation rules
	 *******************************************************************/
	public static $rules = array(
		'game_quest_id' => 'required|exists:game_quests,id',
		'character_id'  => 'required|exists:characters,id',
	);

	/********************************************************************
	 * Relationships
	 *******************************************************************/
	public function character()
	{
		return $this->belongs_to('Character', 'character_id');
	}
	public function quest()
	{
		return $this->belongs_to('Game\Quest', 'game_quest_id');
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
	 * Get the name of the character
	 *
	 * @return string
	 */
	public function get_character_name()
	{
		return $this->character()->first()->name;
	}

	/**
	 * Get the name of the quest
	 *
	 * @return string
	 */
	public function get_quest_name()
	{
		return $this->quest()->first()->name;
	}

	/********************************************************************
	 * Extra Methods
	 *******************************************************************/
}
<?php

namespace Character;
use Laravel;
use Aware;

class Loot extends Aware
{
	/********************************************************************
	 * Declarations
	 *******************************************************************/
	public static $table = 'character_loot';

	/********************************************************************
	 * Aware validation rules
	 *******************************************************************/
	public static $rules = array(
		'game_item_id'     => 'required|exists:game_quests,id',
		'character_id' => 'required|exists:characters,id',
	);

	/********************************************************************
	 * Relationships
	 *******************************************************************/
	public function character()
	{
		return $this->belongs_to('Character', 'character_id');
	}
	public function item()
	{
		return $this->belongs_to('Game\Item', 'game_item_id');
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
	 * Get the name of the item
	 *
	 * @return string
	 */
	public function get_item_name()
	{
		return $this->item()->first()->name;
	}

	/********************************************************************
	 * Extra Methods
	 *******************************************************************/
}
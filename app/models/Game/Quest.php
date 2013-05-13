<?php

namespace Game;
use Laravel;
use Aware;

class Quest extends Aware
{
	/********************************************************************
	 * Declarations
	 *******************************************************************/
	public static $table = 'game_quests';

	/********************************************************************
	 * Aware validation rules
	 *******************************************************************/
	public static $rules = array(
		'game_id' => 'required|exists:games,id',
		'name'    => 'required|max:200',
		'details' => 'required',
	);

	/********************************************************************
	 * Relationships
	 *******************************************************************/
	public function game()
	{
		return $this->belongs_to('Game', 'game_id');
	}
	public function items()
	{
		return $this->has_many('Game\Quest\Item', 'game_quest_id');
	}
	public function characters()
	{
		return $this->has_many('Game\Quest\Character', 'game_quest_id');
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
	 * Get the name of the game
	 *
	 * @return string
	 */
	public function get_game_name()
	{
		return $this->game()->first()->name;
	}

	/**
	 * Get the count of characters
	 *
	 * @return string
	 */
	public function get_character_count()
	{
		return $this->characters()->count();
	}

	/**
	 * Get the active status
	 *
	 * @return string
	 */
	public function get_active()
	{
		return ($this->get_attribute('activeFlag') == 1 ? 'Active' : 'Inactive');
	}

	/**
	 * Get the completion status
	 *
	 * @return string
	 */
	public function get_completed()
	{
		return ($this->get_attribute('completeFlag') == 1 ? 'Complete' : ($this->get_attribute('incompleteFlag') == 1 ? 'Incomplete' : null));
	}

	/********************************************************************
	 * Extra Methods
	 *******************************************************************/
}
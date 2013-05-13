<?php

namespace Game;
use Laravel;
use Aware;

class History extends Aware
{
	/********************************************************************
	 * Declarations
	 *******************************************************************/
	public static $table = 'game_history';

	/********************************************************************
	 * Aware validation rules
	 *******************************************************************/
	public static $rules = array(
		'game_id' => 'required|exists:games,id',
		'week'    => 'required',
	);

	/********************************************************************
	 * Relationships
	 *******************************************************************/
	public function game()
	{
		return $this->belongs_to('Game', 'game_id');
	}
	public function characters()
	{
		return $this->has_many('Game\History\Character', 'game_history_id');
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
	 * Make the created_at easier to read
	 *
	 * @return string
	 */
	public function get_game_week()
	{
		return date('F jS, Y', strtotime($this->get_attribute('week')));
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
	 * Get the list of characters
	 *
	 * @return string
	 */
	public function get_character_list()
	{
		$characters = '';

		foreach ($this->characters as $character) {
			$characters .= $character->character->name .'<br />';
		}

		return $characters;
	}

	/********************************************************************
	 * Extra Methods
	 *******************************************************************/
}
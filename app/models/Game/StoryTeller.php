<?php

namespace Game;
use Aware;

class StoryTeller extends Aware
{
	/**
	 * Declarations
	 */
	public static $table = 'game_storytellers';

	/********************************************************************
	 * Aware validation rules
	 *******************************************************************/
	public static $rules = array(
		'game_id' => 'required|exists:games,id',
		'user_id' => 'required|exists:users,id',
		'character_id' => 'exists:characters,id',
	);

	/**
	 * Getter and Setter methods
	 */
	public function get_created_at()
	{
		return date('F jS, Y \a\t h:ia', strtotime($this->get_attribute('created_at')));
	}

	/**
	 * Get the name of the user
	 *
	 * @return string
	 */
	public function get_username()
	{
		return $this->user()->first()->username;
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
	 * Relationships
	 */
	public function user()
	{
		return $this->belongs_to('User');
	}

	public function game()
	{
		return $this->belongs_to('Game');
	}

	public function character()
	{
		return $this->belongs_to('Character');
	}

	/**
	 * Extra Methods
	 */
}
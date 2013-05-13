<?php

namespace Game;
use Laravel;
use Aware;

class Note extends Aware
{
	/********************************************************************
	 * Declarations
	 *******************************************************************/
	public static $table = 'game_notes';

	/********************************************************************
	 * Aware validation rules
	 *******************************************************************/
	public static $rules = array(
		'game_id' => 'required|exists:games,id',
		'user_id' => 'required|exists:users,id',
		'title'   => 'required|max:200',
		'content' => 'required',
	);

	/********************************************************************
	 * Relationships
	 *******************************************************************/
	public function game()
	{
		return $this->belongs_to('Game', 'game_id');
	}
	public function user()
	{
		return $this->belongs_to('User', 'user_id');
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
	 * Get the name of the user
	 *
	 * @return string
	 */
	public function get_username()
	{
		return $this->user()->first()->username;
	}

	/**
	 * Get the content in a bootstrap friendly way
	 *
	 * @return string
	 */
	public function get_editablecontent()
	{
		return $this->get_attribute('content');
	}

	/********************************************************************
	 * Extra Methods
	 *******************************************************************/
}
<?php

namespace Character;
use Laravel;
use Aware;

class Note extends Aware
{
	/********************************************************************
	 * Declarations
	 *******************************************************************/
	public static $table = 'character_notes';

	/********************************************************************
	 * Aware validation rules
	 *******************************************************************/
	public static $rules = array(
		'character_id' => 'required|exists:characters,id',
		'user_id'      => 'required|exists:users,id',
		'title'        => 'required|max:200',
		'content'      => 'required',
	);

	/********************************************************************
	 * Relationships
	 *******************************************************************/
	public function character()
	{
		return $this->belongs_to('Character', 'character_id');
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

	/********************************************************************
	 * Extra Methods
	 *******************************************************************/
}
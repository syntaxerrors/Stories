<?php

namespace Character\Experience;
use Laravel;
use Aware;

class History extends Aware
{
	/********************************************************************
	 * Declarations
	 *******************************************************************/
	public static $table = 'character_experience_history';

	/********************************************************************
	 * Aware validation rules
	 *******************************************************************/
	public static $rules = array(
		'character_id' => 'required|exists:characters,id',
		'user_id'      => 'required|exists:users,id',
		'value'        => 'required',
		'reason'       => 'required',
		'balance'      => 'required',
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
	public function post()
	{
		if ($this->get_attribute('reason') == 'reply') {
			return $this->belongs_to('Forum\Reply', 'resource_id');
		} elseif ($this->get_attribute('reason') == 'post') {
			return $this->belongs_to('Forum\Post', 'resource_id');
		}

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
<?php

namespace Forum;
use Aware;

class Moderation extends Aware
{
	/**
	 * Declarations
	 */
	public static $table = 'forum_moderation';

	/**
	 * Aware validation rules
	 */
	public static $rules = array(
		'user_id' => 'required|exists:users,id',
		'reason'  => 'required',
	);

	/**
	 * Getter and Setter methods
	 */
	public function get_created_at()
	{
		return date('F jS, Y \a\t h:ia', strtotime($this->get_attribute('created_at')));
	}

	/**
	 * Relationships
	 */
	public function post()
	{
		if ($this->get_attribute('resource_name') == 'post') {
			return $this->belongs_to('Forum\Post', 'resource_id');
		} else {
			return $this->belongs_to('Forum\Reply', 'resource_id');
		}
	}

	public function user()
	{
		return $this->belongs_to('User', 'user_id');
	}

}
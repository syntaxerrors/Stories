<?php

namespace Forum\Reply;
use Aware;

class Edit extends Aware
{
	/**
	 * Declarations
	 */
	public static $table = 'forum_reply_edits';

	/**
	 * Aware validation rules
	 */
	public static $rules = array(
		'user_id'             => 'required|exists:users,id',
		'forum_reply_id'      => 'required|exists:forum_replies,id',
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
	public function reply()
	{
		return $this->belongs_to('Forum\Reply', 'forum_reply_id');
	}

	public function user()
	{
		return $this->belongs_to('User', 'user_id');
	}

}
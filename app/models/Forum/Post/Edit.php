<?php

namespace Forum\Post;
use Aware;

class Edit extends Aware
{
	/**
	 * Declarations
	 */
	public static $table = 'forum_post_edits';

	/**
	 * Aware validation rules
	 */
	public static $rules = array(
		'user_id'             => 'required|exists:users,id',
		'forum_post_id'       => 'required|exists:forum_posts,id',
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
		return $this->belongs_to('Forum\Post', 'forum_post_id');
	}

	public function user()
	{
		return $this->belongs_to('User', 'user_id');
	}

}
<?php

class Forum_Post_View extends Forum
{
	protected $table = 'forum_user_view_posts';

	/**
	 * Aware validation rules
	 */
	public static $rules = array(
		'user_id'             => 'required|exists:users,uniqueId',
		'forum_post_id'       => 'required|exists:forum_posts,uniqueId',
	);

	public function post()
	{
		return $this->belongsTo('Forum_Post', 'forum_post_id');
	}

	public function user()
	{
		return $this->belongsTo('User');
	}

}
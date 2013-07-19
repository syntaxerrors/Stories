<?php

class Forum_Reply_Edit extends BaseModel
{
	/**
	 * Declarations
	 */
	protected $table = 'forum_reply_edits';

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

	/**
	 * Relationships
	 */
	public function reply()
	{
		return $this->belongsTo('Forum_Reply', 'forum_reply_id');
	}

	public function user()
	{
		return $this->belongsTo('User', 'user_id');
	}

}
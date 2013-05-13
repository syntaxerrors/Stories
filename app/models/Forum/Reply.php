<?php

namespace Forum;
use Aware;

class Reply extends Aware
{
	/**
	 * Declarations
	 */
	public static $table = 'forum_replies';
	const TYPE_ACTION        = 4;
	const TYPE_CONVERSATION  = 2;
	const TYPE_INNER_THOUGHT = 3;
	const TYPE_STANDARD      = 1;

	/**
	 * Aware validation rules
	 */
	public static $rules = array(
		'user_id'             => 'required|exists:users,id',
		'forum_post_id'       => 'required|exists:forum_posts,id',
		'forum_reply_type_id' => 'required|exists:forum_reply_types,id',
		'content'             => 'required',
	);

	/**
	 * Getter and Setter methods
	 */
	public function get_created_at()
	{
		return date('F jS, Y \a\t h:ia', strtotime($this->get_attribute('created_at')));
	}
	public function get_moderationCount()
	{
		return Moderation::where('resource_id', '=', $this->get_attribute('id'))->where('resource_name', '=', 'reply')->count();
	}
	public function get_displayName()
	{
		if ($this->get_attribute('character_id') != null) {
			return $this->character->name;
		} else {
			return $this->author->username;
		}
	}
	public function get_icon()
	{
		switch ($this->get_attribute('forum_reply_type_id')) {
			case Reply::TYPE_ACTION:
				return '<i class="icon-exchange" title="Action"></i>';
			break;
			case Reply::TYPE_CONVERSATION:
				return '<i class="icon-comments" title="Conversation"></i>';
			break;
			case Reply::TYPE_INNER_THOUGHT:
				return '<i class="icon-cloud" title="Inner-Thought"></i>';
			break;
		}
		return false;
	}

	/**
	 * Relationships
	 */
	public function post()
	{
		return $this->belongs_to('Forum\Post', 'forum_post_id');
	}
	public function author()
	{
		return $this->belongs_to('User', 'user_id');
	}
	public function character()
	{
		return $this->belongs_to('Character', 'character_id');
	}
	public function type()
	{
		return $this->belongs_to('Forum\Reply\Type', 'forum_reply_type_id');
	}
	public function quote()
	{
		if ($this->get_attribute('quote_type') == 'post') {
			return $this->belongs_to('Forum\Post', 'quote_id');
		} else {
			return $this->belongs_to('Forum\Reply', 'quote_id');
		}
	}
	public function history()
	{
		return $this->has_many('Forum\Reply\Edit', 'forum_reply_id')->order_by('created_at', 'desc');
	}
	public function lastHistory()
	{
		return $this->has_many('Forum\Reply\Edit', 'forum_reply_id')->order_by('created_at', 'desc');
	}
	public function roll()
	{
		return $this->has_one('Forum\Reply\Roll', 'forum_reply_id');
	}

}
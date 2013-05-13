<?php

class Forum_Reply extends BaseModel
{
	/**
	 * Declarations
	 */
	protected $table = 'forum_replies';
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
		return date('F jS, Y _a_t h:ia', strtotime($this->created_at));
	}
	public function get_moderationCount()
	{
		return Moderation::where('resource_id', '=', $this->id)->where('resource_name', '=', 'reply')->count();
	}
	public function get_displayName()
	{
		if ($this->character_id != null) {
			return $this->character->name;
		} else {
			return $this->author->username;
		}
	}
	public function get_icon()
	{
		switch ($this->forum_reply_type_id) {
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
		return $this->belongsTo('Forum_Post', 'forum_post_id');
	}
	public function author()
	{
		return $this->belongsTo('User', 'user_id');
	}
	public function character()
	{
		return $this->belongsTo('Character', 'character_id');
	}
	public function type()
	{
		return $this->belongsTo('Forum_Reply_Type', 'forum_reply_type_id');
	}
	public function quote()
	{
		if ($this->quote_type == 'post') {
			return $this->belongsTo('Forum_Post', 'quote_id');
		} else {
			return $this->belongsTo('Forum_Reply', 'quote_id');
		}
	}
	public function history()
	{
		return $this->hasMany('Forum_Reply_Edit', 'forum_reply_id')->orderBy('created_at', 'desc');
	}
	public function lastHistory()
	{
		return $this->hasMany('Forum_Reply_Edit', 'forum_reply_id')->orderBy('created_at', 'desc');
	}
	public function roll()
	{
		return $this->hasOne('Forum_Reply_Roll', 'forum_reply_id');
	}

}
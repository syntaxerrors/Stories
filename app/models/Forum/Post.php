<?php
use Awareness\Aware;

class Forum_Post extends Aware
{
	/**
	 * Declarations
	 */
	protected $table = 'forum_posts';
	const TYPE_ANNOUNCEMENT  = 5;
	const TYPE_APPLICATION   = 9;
	const TYPE_CONVERSATION  = 6;
	const TYPE_INNER_THOUGHT = 7;
	const TYPE_LOCKED        = 3;
	const TYPE_POLL          = 2;
	const TYPE_STANDARD      = 1;
	const TYPE_STICKY        = 4;

	/**
	 * Aware validation rules
	 */
	public static $rules = array(
		'name'                => 'required|max:200|unique:forum_posts',
		'keyName'             => 'required|max:200|unique:forum_posts',
		'content'             => 'required',
		'forum_board_id'      => 'required|exists:forum_boards,id',
		'user_id'             => 'required',
	);

	/**
	 * Getter and Setter methods
	 */
	public function get_repliesCount()
	{
		return Reply::where('forum_post_id', '=', $this->get_attribute('id'))->count();
	}
	public function get_lastUpdate()
	{
		$lastReply = Reply::with('author')->where('forum_post_id', '=', $this->get_attribute('id'))->order_by('created_at', 'desc')->first();
		if ($lastReply != null) {
			return $lastReply;
		}
		return $this;
	}
	public function get_created_at()
	{
		return date('F jS, Y \a\t h:ia', strtotime($this->get_attribute('created_at')));
	}
	public function get_moderationCount()
	{
		return Moderation::where('resource_id', '=', $this->get_attribute('id'))->where('resource_name', '=', 'post')->count();
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
		switch ($this->get_attribute('forum_post_type_id')) {
			case Post::TYPE_ANNOUNCEMENT:
				return '<i class="icon-warning-sign" title="Announcement"></i>';
			break;
			case Post::TYPE_APPLICATION:
				return '<i class="icon-inbox" title="Application"></i>';
			break;
			case Post::TYPE_CONVERSATION:
				return '<i class="icon-comments" title="Conversation"></i>';
			break;
			case Post::TYPE_INNER_THOUGHT:
				return '<i class="icon-cloud" title="Inner-Thought"></i>';
			break;
			case Post::TYPE_LOCKED:
				return '<i class="icon-lock" title="Locked"></i>';
			break;
			case Post::TYPE_POLL:
				return '<i class="icon-bar-chart" title="Poll"></i>';
			break;
			case Post::TYPE_STICKY:
				return '<i class="icon-pushpin" title="Sticky"></i>';
			break;
		}
		return false;
	}

	/**
	 * Extra Methods
	 */
	public function delete()
	{
		if (count($this->replies) > 0) {
			foreach ($this->replies as $reply) {
				$reply->delete();
			}
		}
		if (count($this->history) > 0) {
			foreach ($this->history as $history) {
				$history->delete();
			}
		}
		if (count($this->userViews) > 0) {
			foreach ($this->userViews as $view) {
				$view ->delete();
			}
		}
		parent::delete();
	}
	public function incrementViews()
	{
		$this->set_attribute('views', $this->get_attribute('views') + 1);
		$this->save();
	}
	public function userViewed($userId)
	{
		$viewed = Post\View::where('forum_post_id', '=', $this->get_attribute('id'))->where('user_id', '=', $userId)->first();
		if ($viewed == null) {
			$viewed                = new Post\View;
			$viewed->forum_post_id = $this->get_attribute('id');
			$viewed->user_id       = $userId;
			$viewed->save();
		}
	}
	public function checkUserViewed($userId)
	{
		$viewed = Post\View::where('forum_post_id', '=', $this->get_attribute('id'))->where('user_id', '=', $userId)->first();
		if ($viewed != null) {
			return true;
		}
		return false;
	}

	/**
	 * Relationships
	 */
	public function board()
	{
		return $this->belongsTo('Forum_Board', 'forum_board_id');
	}
	public function replies()
	{
		return $this->hasMany('Forum_Reply', 'forum_post_id');
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
		return $this->belongsTo('Forum_Post_Type', 'forum_post_type_id');
	}
	public function rules()
	{
		return $this->hasMany('Forum_Post_Rule', 'forum_post_id');
	}
	public function history()
	{
		return $this->hasMany('Forum_Post_Edit', 'forum_post_id')->order_by('created_at', 'desc');
	}
	public function userViews()
	{
		return $this->hasMany('Forum_Post_View', 'forum_post_id');
	}
	public function status()
	{
		return $this->hasOne('Forum_Post_Status', 'forum_post_id');
	}

}
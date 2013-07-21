<?php

class Forum_Post extends BaseModel
{
	/********************************************************************
	 * Declarations
	 *******************************************************************/

	/**
	 * Table declaration
	 *
	 * @var string $table The table this model uses
	 */
	protected $table      = 'forum_posts';
	protected $primaryKey = 'uniqueId';
	public $incrementing  = false;

	const TYPE_ANNOUNCEMENT  = 4;
	const TYPE_APPLICATION   = 8;
	const TYPE_CONVERSATION  = 5;
	const TYPE_INNER_THOUGHT = 6;
	const TYPE_LOCKED        = 2;
	const TYPE_STANDARD      = 1;
	const TYPE_STICKY        = 3;

	/********************************************************************
	 * Aware validation rules
	 *******************************************************************/

    /**
     * Validation rules
     *
     * @static
     * @var array $rules All rules this model must follow
     */
	public static $rules = array(
		'name'                => 'required|max:200',
		'keyName'             => 'required|max:200',
		'content'             => 'required',
		'forum_board_id'      => 'required|exists:forum_boards,uniqueId',
		'user_id'             => 'required|exists:users,uniqueId',
	);

	/********************************************************************
	 * Relationships
	 *******************************************************************/

    /**
     * Forum Board Relationship
     *
     * @return Forum_Board
     */
	public function board()
	{
		return $this->belongsTo('Forum_Board', 'forum_board_id');
	}

    /**
     * Forum Reply Relationship
     *
     * @return Forum_Reply[]
     */
	public function replies()
	{
		return $this->hasMany('Forum_Reply', 'forum_post_id');
	}

    /**
     * User Relationship
     *
     * @return User
     */
	public function author()
	{
		return $this->belongsTo('User', 'user_id');
	}

    /**
     * Character Relationship
     *
     * @return Character
     */
	public function character()
	{
		return $this->belongsTo('Character', 'character_id');
	}

    /**
     * Forum Post Type Relationship
     *
     * @return Forum_Post_Type
     */
	public function type()
	{
		return $this->belongsTo('Forum_Post_Type', 'forum_post_type_id');
	}

    /**
     * Forum Post Edit Relationship
     *
     * @return Forum_Post_Edit[]
     */
	public function history()
	{
		return $this->hasMany('Forum_Post_Edit', 'forum_post_id')->orderBy('created_at', 'desc');
	}

    /**
     * Forum Post View Relationship
     *
     * @return Forum_Post_View[]
     */
	public function userViews()
	{
		return $this->hasMany('Forum_Post_View', 'forum_post_id');
	}

    /**
     * Forum Post Status Relationship
     *
     * @return Forum_Post_Status
     */
	public function status()
	{
		return $this->hasOne('Forum_Post_Status', 'forum_post_id');
	}

    /**
     * Forum Moderation Relationship
     *
     * @return Forum_Moderation
     */
	public function moderations()
	{
		return $this->morphMany('Forum_Moderation', 'resource');
	}

	/********************************************************************
	 * Model events
	 *******************************************************************/

	public static function boot()
	{
		parent::boot();

		Forum_Post::creating(function($object)
		{
			$object->uniqueId = parent::findExistingReferences('Forum_Post');
		});

		Forum_Post::deleting(function($object)
		{
			$object->replies->each(function($reply)
			{
				$reply->delete();
			});
			$object->history->each(function($history)
			{
				$history->delete();
			});
			$object->userViews->each(function($userView)
			{
				$userView->delete();
			});
			$object->moderations->each(function($moderation)
			{
				$moderation->delete();
			});
			if ($object->status != null) {
				$object->status->delete();
			}
		});
	}

	/********************************************************************
	 * Getter and Setter methods
	 *******************************************************************/

	public function getRepliesCountAttribute()
	{
		return Forum_Reply::where('forum_post_id', '=', $this->id)->count();
	}
	public function getLastUpdateAttribute()
	{
		$lastReply = Forum_Reply::with('author')
			->where('forum_post_id', $this->id)
			->orderBy('created_at', 'desc')
			->first();

		if ($lastReply != null) {
			return $lastReply;
		}
		return $this;
	}
	public function getModerationCountAttribute()
	{
		return $this->moderations->count();
	}
	public function getDisplayNameAttribute()
	{
		if ($this->character_id != null) {
			return $this->character->name;
		} else {
			return $this->author->username;
		}
	}
	public function getIconAttribute()
	{
		switch ($this->forum_post_type_id) {
			case Forum_Post::TYPE_ANNOUNCEMENT:
				return '<i class="icon-warning-sign" title="Announcement"></i>';
			break;
			case Forum_Post::TYPE_APPLICATION:
				return '<i class="icon-inbox" title="Application"></i>';
			break;
			case Forum_Post::TYPE_CONVERSATION:
				return '<i class="icon-comments" title="Conversation"></i>';
			break;
			case Forum_Post::TYPE_INNER_THOUGHT:
				return '<i class="icon-cloud" title="Inner-Thought"></i>';
			break;
			case Forum_Post::TYPE_LOCKED:
				return '<i class="icon-lock" title="Locked"></i>';
			break;
			case Forum_Post::TYPE_STICKY:
				return '<i class="icon-pushpin" title="Sticky"></i>';
			break;
		}
		return false;
	}

	/**
	* Get the next post in order of modified at
	*/
	public function getNextPostAttribute()
	{
		return Forum_Post::where('forum_board_id', '=', $this->forum_board_id)
			->where('modified_at', '<', $this->modified_at)
			->orderBy('modified_at', 'desc')
			->first();
	}

	/**
	* Get the previous post in order of modified at
	*/
	public function getPreviousPostAttribute()
	{
		return Forum_Post::where('forum_board_id', '=', $this->forum_board_id)
			->where('modified_at', '>', $this->modified_at)
			->orderBy('modified_at', 'asc')
			->first();
	}

	/********************************************************************
	 * Extra Methods
	 *******************************************************************/

	public function incrementViews()
	{
		$this->views = $this->views + 1;
		$this->save();
	}

	public function userViewed($userId)
	{
		$viewed = Forum_Post_View::where('forum_post_id', '=', $this->id)
			->where('user_id', '=', $userId)
			->first();

		if ($viewed == null) {
			$viewed                = new Forum_Post_View;
			$viewed->forum_post_id = $this->id;
			$viewed->user_id       = $userId;
			$viewed->save();
		}
	}

	public function checkUserViewed($userId)
	{
		$viewed = Forum_Post_View::where('forum_post_id', '=', $this->id)
			->where('user_id', '=', $userId)
			->first();

		if ($viewed != null) {
			return true;
		}
		return false;
	}

	public function deleteViews()
	{
		$this->userViews->each(function($view)
		{
			$view->delete();
		});
	}

	public function setAttachmentEdit($imageName)
	{
		$edit                = new Forum_Post_Edit;
		$edit->forum_post_id = $this->id;
		$edit->user_id       = $this->activeUser->id;
		$edit->reason        = 'Uploaded File: '. $imageName;

		$edit->save();
	}

	public function setModeration($reason)
	{
		// Create the moderation record
		$report                = new Forum_Moderation;
		$report->resource_type = 'Forum_Post';
		$report->resource_id   = $this->id;
		$report->user_id       = Auth::user()->id;
		$report->reason        = $reason;

		$report->save();

		// Set this as locked for moderation
		$this->moderatorLockedFlag = 1;
		$this->save();
	}

	public function setStatus($statusId)
	{
		$status                          = new Forum_Post_Status;
		$status->forum_post_id           = $this->id;
		$status->forum_support_status_id = $statusId;

		$status->save();

		if (count($status->getErrors()->all()) > 0) {
			ppd($status->getErrors()->all());
		}
	}

}
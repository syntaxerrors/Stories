<?php

class Forum_Reply extends BaseModel
{
	/********************************************************************
	 * Declarations
	 *******************************************************************/

	/**
	 * Table declaration
	 *
	 * @var string $table The table this model uses
	 */
	protected $table      = 'forum_replies';
	protected $primaryKey = 'uniqueId';
	public $incrementing  = false;

	const TYPE_ACTION        = 4;
	const TYPE_CONVERSATION  = 2;
	const TYPE_INNER_THOUGHT = 3;
	const TYPE_STANDARD      = 1;

	/**
	 * Soft Delete users instead of completely removing them
	 *
	 * @var bool $softDelete Whether to delete or soft delete
	 */
	protected $softDelete = true;


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
		'user_id'             => 'required|exists:users,uniqueId',
		'forum_post_id'       => 'required|exists:forum_posts,uniqueId',
		'forum_reply_type_id' => 'required|exists:forum_reply_types,id',
		'content'             => 'required',
	);

	/********************************************************************
	 * Scopes
	 *******************************************************************/

	/********************************************************************
	 * Relationships
	 *******************************************************************/

    /**
     * Forum Post Relationship
     *
     * @return Forum_Post
     */
	public function post()
	{
		return $this->belongsTo('Forum_Post', 'forum_post_id');
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
     * Forum Reply Type Relationship
     *
     * @return Forum_Reply_Type
     */
	public function type()
	{
		return $this->belongsTo('Forum_Reply_Type', 'forum_reply_type_id');
	}

    /**
     * Quoted Post/Reply Relationship
     *
     * @return Forum_Post|Forum_Reply
     */
	public function quote()
	{
		if ($this->quote_type == 'post') {
			return $this->belongsTo('Forum_Post', 'quote_id');
		} else {
			return $this->belongsTo('Forum_Reply', 'quote_id');
		}
	}

    /**
     * Forum Reply Edit Relationship
     *
     * @return Forum_Reply_Edit[]
     */
	public function history()
	{
		return $this->hasMany('Forum_Reply_Edit', 'forum_reply_id')->orderBy('created_at', 'desc');
	}

    /**
     * Forum Reply Edit Relationship
     *
     * @return Forum_Reply_Edit[]
     */
	public function lastHistory()
	{
		return $this->hasMany('Forum_Reply_Edit', 'forum_reply_id')->orderBy('created_at', 'desc');
	}

    /**
     * Forum Reply Roll Relationship
     *
     * @return Forum_Reply_Roll
     */
	public function roll()
	{
		return $this->hasOne('Forum_Reply_Roll', 'forum_reply_id');
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

		Forum_Reply::creating(function($object)
		{
			$object->uniqueId = parent::findExistingReferences('Forum_Reply');
		});

		Forum_Reply::deleting(function($object)
		{
			$object->history->each(function($history)
			{
				$history->delete();
			});
			$object->moderations->each(function($moderation)
			{
				$moderation->delete();
			});
			if ($object->roll != null) {
				$object->roll->delete();
			}
		});
	}

	/********************************************************************
	 * Getter and Setter methods
	 *******************************************************************/

    /**
     * Get count of moderation reports
     *
     * @return int
     */
	public function getModerationCountAttribute()
	{
		return $this->moderations->count();
	}

    /**
     * Get display name (will be character or user)
     *
     * @return string
     */
	public function getDisplayNameAttribute()
	{
		if ($this->character_id != null) {
			return $this->character->name;
		} else {
			return $this->author->username;
		}
	}

    /**
     * Get icon to use for the reply
     *
     * @return string
     */
	public function getIconAttribute()
	{
		switch ($this->forum_reply_type_id) {
			case Forum_Reply::TYPE_ACTION:
				return '<i class="icon-exchange" title="Action"></i>';
			break;
			case Forum_Reply::TYPE_CONVERSATION:
				return '<i class="icon-comments" title="Conversation"></i>';
			break;
			case Forum_Reply::TYPE_INNER_THOUGHT:
				return '<i class="icon-cloud" title="Inner-Thought"></i>';
			break;
		}
		return false;
	}

    /**
     * Get board this reply belongs to
     *
     * @return Forum_Board
     */
    public function getBoardAttribute()
    {
    	return $this->post->board;
    }

	/**
	* Get an easy reference for what model we are dealing with
	*/
	public function getForumTypeAttribute()
	{
		return 'reply';
	}

	/********************************************************************
	 * Extra Methods
	 *******************************************************************/

	public function addEdit($reason)
	{
		$edit                 = new Forum_Reply_Edit;
		$edit->forum_reply_id = $this->id;
		$edit->user_id        = Auth::user()->id;
		$edit->reason         = $reason;

		$edit->save();
	}

}
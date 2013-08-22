<?php

class Forum_Moderation extends BaseModel
{
	/********************************************************************
	 * Declarations
	 *******************************************************************/

	/**
	 * Table declaration
	 *
	 * @var string $table The table this model uses
	 */
	protected $table = 'forum_moderation';

	const REMOVE_REPORT = 'Post removed from moderation';
	const ADMIN_REVIEW  = 'Post moved to admin review';
	const DELETE_POST   = 'Post deleted by an administrator';

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
		'user_id' => 'required|exists:users,uniqueId',
		'reason'  => 'required',
	);

	/********************************************************************
	 * Scopes
	 *******************************************************************/

	/********************************************************************
	 * Relationships
	 *******************************************************************/

    /**
     * Forum Post or Forum Reply Relationship
     *
     * @return Forum_Post|Forum_Reply
     */
	public function resource()
	{
		return $this->morphTo();
	}

    /**
     * User Relationship
     *
     * @return User
     */
	public function user()
	{
		return $this->belongsTo('User', 'user_id');
	}

    /**
     * Moderation Log Relationship
     *
     * @return Forum_Moderation_Log[]
     */
	public function logs()
	{
		return $this->hasMany('Forum_Moderation_Log', 'forum_moderation_id');
	}

    /**
     * Moderation Reply Relationship
     *
     * @return Forum_Moderation_Reply[]
     */
	public function replies()
	{
		return $this->hasMany('Forum_Moderation_Reply', 'forum_moderation_id');
	}

	/********************************************************************
	 * Getter and Setter methods
	 *******************************************************************/

	public function getHistoryAttribute()
	{
		$history = array();
		if ($this->replies->count() > 0) {
			$history = $this->replies;
		}
		if ($this->logs->count() > 0) {
			if (count($history) == 0) {
				$history = $this->logs;
			} else {
				$history->merge($this->logs);
			}
		}

		if (count($history) > 0) {
			$history = $history->sortBy(function($historyObject) {
				return $historyObject->created_at;
			});
		}

		return $history;
	}

	/********************************************************************
	 * Extra Methods
	 *******************************************************************/

}
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

	/********************************************************************
	 * Getter and Setter methods
	 *******************************************************************/

	/********************************************************************
	 * Extra Methods
	 *******************************************************************/

}
<?php

class Message extends BaseModel
{
	/********************************************************************
	 * Declarations
	 *******************************************************************/
	const STANDARD           = 1;
	const EXPERIENCE         = 2;
	const ACTION_APPROVAL    = 4;
	const CHARACTER_APPROVAL = 5;
	const MODERATION         = 3;
	const PERMISSION         = 6;

	/********************************************************************
	 * Aware validation rules
	 *******************************************************************/
	public static $rules = array(
		'title'           => 'required|max:200',
		'content'         => 'required',
		'sender_id'       => 'required|exists:users,uniqueId',
		'receiver_id'     => 'required|exists:users,uniqueId',
		'message_type_id' => 'required|exists:message_types,id',
		'child_id'        => 'exists:messages,uniqueId',
		'parent_id'       => 'exists:messages,uniqueId',
	);

	/********************************************************************
	 * Relationships
	 *******************************************************************/
	public function sender()
	{
		return $this->belongsTo('User', 'sender_id');
	}
	public function receiver()
	{
		return $this->belongsTo('User', 'receiver_id');
	}
	public function child()
	{
		return $this->hasOne('Message', 'child_id');
	}
	public function parent()
	{
		return $this->belongsTo('Message', 'parent_id');
	}
	public function type()
	{
		return $this->belongsTo('Message_Type');
	}
	public function userDeleted()
	{
		return $this->hasMany('Message_User_Delete', 'message_id');
	}
	public function userRead()
	{
		return $this->hasMany('Message_User_Read', 'message_id');
	}

	/********************************************************************
	 * Getter and Setter methods
	 *******************************************************************/

	/**
	 * See if this message has been deleted by the user
	 *
	 * @return boolean
	 */
	public function getDeletedAttribute()
	{
		$deleted = Message_User_Delete::where('message_id', $this->id)->where('user_id', Auth::user()->id)->first();
		return ($deleted == null ? 0 : 1);
	}

	/**
	 * See if this message has been read by the user
	 *
	 * @return boolean
	 */
	public function getReadAttribute()
	{
		$read = Message_User_Read::where('message_id', $this->id)->where('user_id', Auth::user()->id)->first();
		return ($read == null ? 0 : 1);
	}

	/********************************************************************
	 * Extra Methods
	 *******************************************************************/
}
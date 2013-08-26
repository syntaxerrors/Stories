<?php

class Message_User_Delete extends BaseModel
{
	/********************************************************************
	 * Declarations
	 *******************************************************************/
	protected $table = 'message_user_deletes';

	/********************************************************************
	 * Aware validation rules
	 *******************************************************************/
	public static $rules = array(
		'user_id'    => 'required|exists:users,uniqueId',
		'message_id' => 'required|exists:messages,uniqueId',
	);

	/********************************************************************
	 * Relationships
	 *******************************************************************/

	public function user()
	{
		return $this->belongsTo('User', 'user_id');
	}

	public function message()
	{
		return $this->belongsTo('Message', 'message_id');
	}

	/********************************************************************
	 * Getter and Setter methods
	 *******************************************************************/

	/********************************************************************
	 * Extra Methods
	 *******************************************************************/

}
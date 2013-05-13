<?php

class Message extends Aware
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
		'sender_id'       => 'required|exists:users,id',
		'receiver_id'     => 'required|exists:users,id',
		'message_type_id' => 'required|exists:message_types,id',
	);

	/********************************************************************
	 * Relationships
	 *******************************************************************/
	public function sender()
	{
		return $this->belongs_to('User', 'sender_id');
	}
	public function receiver()
	{
		return $this->belongs_to('User', 'receiver_id');
	}
	public function child()
	{
		return $this->has_one('Message', 'child_id');
	}
	public function parent()
	{
		return $this->belongs_to('Message', 'parent_id');
	}
	public function type()
	{
		return $this->belongs_to('Message\Type');
	}

	/********************************************************************
	 * Getter and Setter methods
	 *******************************************************************/

	/**
	 * Make created_at easier to read
	 *
	 * @return string
	 */
	public function get_created_at()
	{
		return date('M j, Y \a\t h:ia', strtotime($this->get_attribute('created_at')));
	}

	/**
	 * See if this message has been deleted by the user
	 *
	 * @return boolean
	 */
	public function get_deleted()
	{
		$deleted = Message\User\Delete::where('message_id', '=', $this->get_attribute('id'))->where('user_id', '=', Auth::user()->id)->first();
		return ($deleted == null ? 0 : 1);
	}

	/********************************************************************
	 * Extra Methods
	 *******************************************************************/
}
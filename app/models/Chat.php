<?php

class Chat extends BaseModel
{
	/********************************************************************
	 * Declarations
	 *******************************************************************/

	/********************************************************************
	 * Aware validation rules
	 *******************************************************************/
	public static $rules = array(
		'user_id'      => 'required|exists:users,id',
		'character_id' => 'exists:characters,id',
		'message'      => 'required',
		'chat_room_id' => 'required|exists:chat_rooms,id',
	);

	/********************************************************************
	 * Relationships
	 *******************************************************************/
	public function user()
	{
		return $this->belongsTo('User', 'user_id');
	}
	public function character()
	{
		return $this->belongsTo('Character', 'character_id');
	}
	public function room()
	{
		return $this->belongsTo('Chat\Room', 'chat_room_id');
	}

	/********************************************************************
	 * Getter and Setter methods
	 *******************************************************************/

	/********************************************************************
	 * Extra Methods
	 *******************************************************************/
}
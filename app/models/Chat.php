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
		'user_id'      => 'required|exists:users,uniqueId',
		'character_id' => 'exists:characters,uniqueId',
		'message'      => 'required',
		'chat_room_id' => 'required|exists:chat_rooms,uniqueId',
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
		return $this->belongsTo('Chat_Room', 'chat_room_id');
	}

	/********************************************************************
	 * Getter and Setter methods
	 *******************************************************************/

	/********************************************************************
	 * Extra Methods
	 *******************************************************************/

	public static function boot()
	{
		parent::boot();

		Chat::created(function ($object) {
			$object->sendToNode($object);
			pp($object);
		});
	}

	private function sendToNode ($messageObject) 
	{
		$newMessage['text'] = "({$messageObject->created_at}) {$messageObject->user->username}: {$messageObject->message} <br />";
		$newMessage['room'] = $messageObject->chat_room_id;

		$node = new SocketIOClient('http://dev-toolbox.com:1337', 'socket.io', 1, false, true, true);
		$node->init();
		$node->send(
			SocketIOClient::TYPE_EVENT,
			null,
			null,
			json_encode(array('name' => 'message', 'args' => $newMessage))
			);
		$node->close();
	}
}
<?php

class Chat_Room extends BaseModel
{
	/********************************************************************
	 * Declarations
	 *******************************************************************/

	/**
	 * Table declaration
	 *
	 * @var string $table The table this model uses
	 */
	protected $table = 'chat_rooms';
	protected $primaryKey = 'uniqueId';

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
		'user_id'          => 'required|exists:users,uniqueId',
		'game_id'          => 'exists:games,uniqueId',
		'game_template_id' => 'exists:game_templates,id',
		'name'             => 'required',
	);

	/********************************************************************
	 * Scopes
	 *******************************************************************/

	/********************************************************************
	 * Relationships
	 *******************************************************************/

    /**
     * User Relationship
     *
     * @return User[]
     */
	public function user()
	{
		return $this->belongsTo('User', 'user_id');
	}
	public function game()
	{
		return $this->belongsTo('Game', 'game_id');
	}
	public function chats()
	{
		return $this->hasMany('Chat', 'chat_room_id')->orderBy('created_at', 'asc');
	}
	public function recentChats()
	{
		return $this->hasMany('Chat', 'chat_room_id')
			->where('created_at', '>', date('Y-m-d H:i:s', strtotime('-30 minutes')))
			->orderBy('created_at', 'desc');
	}

	/********************************************************************
	 * Getter and Setter methods
	 *******************************************************************/
	public function getUsersOnlineAttribute()
	{
		$chats       = $this->recentChats()->get();
		$usersOnline = array();

		foreach ($chats as $chat) {
			if (!in_array($chat->user->username, array_pluck($usersOnline, 'username'))) {
				$status = ($chat->created_at < date('Y-m-d H:i:s', strtotime('-3 minutes'))
					? '[Idle]' : null);
				array_push($usersOnline, array('id' => $chat->user_id, 'username' => $chat->user->username, 'status' => $status));
			}
		}

		$users      = array();
		$gameMaster = null;
		foreach ($usersOnline as $key => $userOnline) {
			if ($userOnline['username'] == $this->user->username) {
				$gameMaster = $userOnline;
				unset($usersOnline[$key]);
			} else {
				$users[$key] = $userOnline['username'];
			}
		}
		array_multisort($users, SORT_ASC, $usersOnline);

		if ($gameMaster != null) {
			array_unshift($usersOnline, $gameMaster);
		}

		return $usersOnline;
	}

	/********************************************************************
	 * Extra Methods
	 *******************************************************************/
}
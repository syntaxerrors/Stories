<?php
namespace Chat;
use aware;

class Room extends Aware
{
	/**
	 * Declarations
	 */
	public static $table = 'chat_rooms';

	/**
	 * Aware validation rules
	 */
	public static $rules = array(
		'user_id'          => 'required|exists:users,id',
		'game_id'          => 'exists:games,id',
		'game_template_id' => 'exists:game_templates,id',
		'name'             => 'required',
	);

	/**
	 * Getter and Setter methods
	 */
	public function get_created_at()
	{
		return date('Y-m-d h:ia', strtotime($this->get_attribute('created_at')));
	}
	public function get_usersOnline()
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

	/**
	 * Relationships
	 */
	public function user()
	{
		return $this->belongs_to('User', 'user_id');
	}
	public function game()
	{
		return $this->belongs_to('Game', 'game_id');
	}
	public function template()
	{
		return $this->belongs_to('Game\Template', 'game_template_id');
	}
	public function chats()
	{
		return $this->has_many('Chat', 'chat_room_id')->order_by('created_at', 'asc');
	}
	public function recentChats()
	{
		return $this->has_many('Chat', 'chat_room_id')
			->where('created_at', '>', date('Y-m-d H:i:s', strtotime('-30 minutes')))
			->order_by('created_at', 'desc');
	}
}
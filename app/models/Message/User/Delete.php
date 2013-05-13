<?php

namespace Message\User;
use Aware;

class Delete extends Aware
{
	public static $table = 'message_user_deleted';

	/**
	 * Aware validation rules
	 */
	public static $rules = array(
		'user_id'    => 'required|exists:users,id',
		'message_id' => 'required|exists:messages,id',
	);

	public function user()
	{
		return $this->belongs_to('User', 'user_id');
	}

	public function message()
	{
		return $this->belongs_to('Message', 'message_id');
	}

}
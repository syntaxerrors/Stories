<?php

class UserTransformer extends Transformer {
	
	public function transform($user)
	{
		return [
			'user_id'       => $user['uniqueId'],
			'username'      => $user['username'],
			'email'         => $user['email'],
			'gravatarEmail' => $user['gravatarEmail'],
			'lastActive'    => $user['lastActive'],
			'created_at'    => date('Y-m-d H:i:s', strtotime($user['created_at'])),
			'updated_at'    => date('Y-m-d H:i:s', strtotime($user['updated_at'])),
		];
	}
}
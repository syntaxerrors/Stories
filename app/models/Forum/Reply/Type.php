<?php

namespace Forum\Reply;
use Aware;

class Type extends Aware
{
	public static $table = 'forum_reply_types';

	public function posts()
	{
		return $this->has_many('Forum\Reply', 'forum_reply_type_id');
	}

}
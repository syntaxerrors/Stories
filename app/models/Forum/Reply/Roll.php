<?php

namespace Forum\Reply;
use Aware;

class Roll extends Aware
{
	public static $table = 'forum_reply_rolls';

	public function reply()
	{
		return $this->belongs_to('Forum\Reply', 'forum_reply_id');
	}

}
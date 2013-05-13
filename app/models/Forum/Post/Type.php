<?php

namespace Forum\Post;
use Aware;

class Type extends Aware
{
	public static $table = 'forum_post_types';

	public function posts()
	{
		return $this->has_many('Forum\Post', 'forum_post_type_id');
	}

}
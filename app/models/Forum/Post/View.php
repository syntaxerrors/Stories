<?php

namespace Forum\Post;
use Aware;

class View extends Aware
{
	public static $table = 'forum_user_view_posts';

	public function post()
	{
		return $this->belongs_to('Forum\Post', 'forum_post_type_id');
	}

	public function user()
	{
		return $this->belongs_to('User');
	}

}
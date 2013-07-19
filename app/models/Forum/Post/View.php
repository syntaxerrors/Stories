<?php

class Forum_Post_View extends BaseModel
{
	protected $table = 'forum_user_view_posts';

	public function post()
	{
		return $this->belongsTo('Forum_Post', 'forum_post_type_id');
	}

	public function user()
	{
		return $this->belongsTo('User');
	}

}
<?php

class Forum_Post_Type extends BaseModel
{
	protected $table = 'forum_post_types';

	public function posts()
	{
		return $this->hasMany('Forum_Post', 'forum_post_type_id');
	}

}
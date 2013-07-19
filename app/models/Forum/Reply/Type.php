<?php

class Forum_Reply_Type extends BaseModel
{
	protected $table = 'forum_reply_types';

	public function posts()
	{
		return $this->hasMany('Forum_Reply', 'forum_reply_type_id');
	}

}
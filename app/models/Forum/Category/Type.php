<?php

class Forum_Category_Type extends Forum
{
	protected $table = 'forum_category_types';

	public function categories()
	{
		return $this->hasMany('Forum_Category', 'forum_categroy_id');
	}

}
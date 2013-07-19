<?php

class Forum_Category_Type extends BaseModel
{
	protected $table = 'forum_category_types';

	public function categories()
	{
		return $this->hasMany('Forum_Category', 'forum_categroy_id');
	}

}
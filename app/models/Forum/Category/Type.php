<?php

namespace Forum\Category;
use Aware;

class Type extends Aware
{
	public static $table = 'forum_category_types';

	public function categories()
	{
		return $this->has_many('Forum\Category', 'forum_categroy_id');
	}

}
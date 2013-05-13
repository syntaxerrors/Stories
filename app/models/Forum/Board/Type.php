<?php

namespace Forum\Board;
use Aware;

class Type extends Aware
{
	public static $table = 'forum_board_types';

	public function boards()
	{
		return $this->has_many('Forum\Board', 'forum_board_type_id');
	}

}
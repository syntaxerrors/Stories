<?php

namespace Character;
use Aware;

class Roll extends Aware
{
	public static $table = 'character_rolls';

	public function character()
	{
		return $this->belongs_to('Character', 'character_id');
	}

}
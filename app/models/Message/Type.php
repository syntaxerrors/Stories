<?php

namespace Message;
use Aware;

class Type extends Aware
{
	public static $table = 'message_types';

	public function messages()
	{
		return $this->has_many('Message', 'message_type_id');
	}

}
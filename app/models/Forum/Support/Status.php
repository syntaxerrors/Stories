<?php

namespace Forum\Support;
use Aware;

class Status extends Aware
{
	/**
	 * Declarations
	 */
	public static $table = 'forum_support_status';
	const TYPE_OPEN        = 1;
	const TYPE_IN_PROGRESS = 2;
	const TYPE_RESOLVED    = 3;
	const TYPE_WONT_FIX    = 4;

}
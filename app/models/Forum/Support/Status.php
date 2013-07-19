<?php

class Forum_Support_Status extends BaseModel
{
	/**
	 * Declarations
	 */
	protected $table = 'forum_support_status';
	const TYPE_OPEN        = 1;
	const TYPE_IN_PROGRESS = 2;
	const TYPE_RESOLVED    = 3;
	const TYPE_WONT_FIX    = 4;

}
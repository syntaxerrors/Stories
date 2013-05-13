<?php

namespace Forum\Post;
use Forum\Support;
use Aware;

class Status extends Aware
{
	/**
	 * Declarations
	 */
	public static $table = 'forum_post_status';

	/**
	 * Getter and Setter methods
	 */
	public function get_created_at()
	{
		return date('F jS, Y \a\t h:ia', strtotime($this->get_attribute('created_at')));
	}

	/**
	 * Getter and Setter methods
	 */
	public function get_icon()
	{
		switch ($this->get_attribute('forum_support_status_id')) {
			case Support\Status::TYPE_OPEN:
				return '<i class="icon-bolt text-info" title="Open" style="font-size: 14px;"></i>';
			break;
			case Support\Status::TYPE_IN_PROGRESS:
				return '<i class="icon-time text-warning" title="In progress" style="font-size: 14px;"></i>';
			break;
			case Support\Status::TYPE_RESOLVED:
				return '<i class="icon-check text-success" title="Resolved" style="font-size: 14px;"></i>';
			break;
			case Support\Status::TYPE_WONT_FIX:
				return '<i class="icon-ban-circle text-error" title="Won\'t fix" style="font-size: 14px;"></i>';
			break;
		}
		return false;
	}

	/**
	 * Relationships
	 */
	public function post()
	{
		return $this->belongs_to('Forum\Post', 'forum_post_id');
	}
	public function status()
	{
		return $this->belongs_to('Forum\Support\Status', 'forum_support_status_id');
	}

}
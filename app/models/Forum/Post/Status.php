<?php

class Forum_Post_Status extends BaseModel
{
	/********************************************************************
	 * Declarations
	 *******************************************************************/
	protected $table    = 'forum_post_status';
	protected $fillable = array('forum_support_status_id');

	/********************************************************************
	 * Aware validation rules
	 *******************************************************************/

    /**
     * Validation rules
     *
     * @static
     * @var array $rules All rules this model must follow
     */
	public static $rules = array(
		'forum_post_id'           => 'required|exists:forum_posts,uniqueId',
		'forum_support_status_id' => 'required|exists:forum_post_status,id',
	);

	/********************************************************************
	 * Relationships
	 *******************************************************************/
	public function post()
	{
		return $this->belongsTo('Forum_Post', 'forum_post_id');
	}
	public function status()
	{
		return $this->belongsTo('Forum_Support_Status', 'forum_support_status_id');
	}

	/********************************************************************
	 * Getter and Setter methods
	 *******************************************************************/
	public function getIconAttribute()
	{
		switch ($this->forum_support_status_id) {
			case Forum_Support_Status::TYPE_OPEN:
				return '<i class="icon-bolt text-info" title="Open" style="font-size: 14px;"></i>';
			break;
			case Forum_Support_Status::TYPE_IN_PROGRESS:
				return '<i class="icon-time text-warning" title="In progress" style="font-size: 14px;"></i>';
			break;
			case Forum_Support_Status::TYPE_RESOLVED:
				return '<i class="icon-check text-success" title="Resolved" style="font-size: 14px;"></i>';
			break;
			case Forum_Support_Status::TYPE_WONT_FIX:
				return '<i class="icon-ban-circle text-error" title="Won\'t fix" style="font-size: 14px;"></i>';
			break;
		}
		return false;
	}

	/********************************************************************
	 * Extra Methods
	 *******************************************************************/

}
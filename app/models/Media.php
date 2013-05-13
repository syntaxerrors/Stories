<?php

class Media extends BaseModel
{
	/********************************************************************
	 * Declarations
	 *******************************************************************/
	protected $table = 'media';

	/********************************************************************
	 * Aware validation rules
	 *******************************************************************/
	public static $rules = array(
		'name'              => 'required|max:200',
		'user_id'           => 'required|exists:users,id',
		'media_category_id' => 'required|exists:media_categories,id',
	);

	/********************************************************************
	 * Relationships
	 *******************************************************************/
	public function user()
	{
		return $this->belongsTo('User');
	}

	public function category()
	{
		return $this->belongsTo('Media_Category', 'media_category_id');
	}

	/********************************************************************
	 * Getter and Setter methods
	 *******************************************************************/

	/**
	 * Make the created_at data easier to read
	 *
	 * @return string
	 */
	public function getCreated_atAttribute()
	{
		return date('F jS, Y \a\t h:ia', strtotime($this->created_at));
	}

	/********************************************************************
	 * Extra Methods
	 *******************************************************************/
}
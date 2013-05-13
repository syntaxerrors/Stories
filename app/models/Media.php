<?php

class Media extends Aware
{
	/********************************************************************
	 * Declarations
	 *******************************************************************/
	public static $table = 'media';

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
		return $this->belongs_to('User');
	}

	public function category()
	{
		return $this->belongs_to('Media\Category', 'media_category_id');
	}

	/********************************************************************
	 * Getter and Setter methods
	 *******************************************************************/

	/**
	 * Make the created_at data easier to read
	 *
	 * @return string
	 */
	public function get_created_at()
	{
		return date('F jS, Y \a\t h:ia', strtotime($this->get_attribute('created_at')));
	}

	/********************************************************************
	 * Extra Methods
	 *******************************************************************/
}
<?php

namespace Message;
use Aware;

class Folder extends Aware
{
	/********************************************************************
	 * Declarations
	 *******************************************************************/
	public static $table = 'message_folders';

	/********************************************************************
	 * Aware validation rules
	 *******************************************************************/
	public static $rules = array(
		'name'        => 'required|max:200',
		'user_id'     => 'required|exists:users,id',
	);

	/********************************************************************
	 * Relationships
	 *******************************************************************/
	public function user()
	{
		return $this->belongs_to('User');
	}
	public function messages()
	{
		return $this->has_many('Message\Folder\Message', 'folder_id');
	}

	/********************************************************************
	 * Getter and Setter methods
	 *******************************************************************/

	/********************************************************************
	 * Extra Methods
	 *******************************************************************/
}
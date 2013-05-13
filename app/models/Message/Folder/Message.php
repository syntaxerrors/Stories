<?php

namespace Message\Folder;
use Aware;

class Message extends Aware
{
	/********************************************************************
	 * Declarations
	 *******************************************************************/
	public static $table = 'message_folder_messages';

	/********************************************************************
	 * Aware validation rules
	 *******************************************************************/
	public static $rules = array(
		'message_id' => 'required|exists:messages,id',
		'folder_id'  => 'required|exists:message_folders,id',
	);

	/********************************************************************
	 * Relationships
	 *******************************************************************/
	public function message()
	{
		return $this->belongs_to('Message');
	}
	public function folder()
	{
		return $this->belongs_to('Message\Folder', 'folder_id');
	}

	/********************************************************************
	 * Getter and Setter methods
	 *******************************************************************/

	/********************************************************************
	 * Extra Methods
	 *******************************************************************/
}
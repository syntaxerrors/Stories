<?php

class Message_Folder_Message extends BaseModel
{
	/********************************************************************
	 * Declarations
	 *******************************************************************/
	protected $table = 'message_folder_messages';

	/********************************************************************
	 * Aware validation rules
	 *******************************************************************/
	public static $rules = array(
		'message_id' => 'required|exists:messages,uniqueId',
		'folder_id'  => 'required|exists:message_folders,id',
	);

	/********************************************************************
	 * Relationships
	 *******************************************************************/
	public function message()
	{
		return $this->belongsTo('Message');
	}
	public function folder()
	{
		return $this->belongsTo('Message_Folder', 'folder_id');
	}

	/********************************************************************
	 * Getter and Setter methods
	 *******************************************************************/

	/********************************************************************
	 * Extra Methods
	 *******************************************************************/
}
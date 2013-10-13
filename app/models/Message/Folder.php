<?php

class Message_Folder extends BaseModel
{
	/********************************************************************
	 * Declarations
	 *******************************************************************/
	protected $table      = 'message_folders';
	protected $primaryKey = 'uniqueId';
	public $incrementing  = false;

	/********************************************************************
	 * Aware validation rules
	 *******************************************************************/
	public static $rules = array(
		'name'        => 'required|max:200',
		'user_id'     => 'required|exists:users,uniqueId',
		'parent_id'   => 'exists:message_folders,uniqueId',
	);

	/********************************************************************
	 * Relationships
	 *******************************************************************/
	public function user()
	{
		return $this->belongsTo('User');
	}
	public function messages()
	{
		return $this->belongsToMany('Message', 'message_folder_messages', 'folder_id', 'message_id');
	}

	/********************************************************************
	 * Model events
	 *******************************************************************/

	public static function boot()
	{
		parent::boot();

		Message_Folder::creating(function($object)
		{
			$object->uniqueId = parent::findExistingReferences('Message_Folder');
		});

		Message_Folder::deleting(function($object)
		{
			$object->messages->each(function($message) use ($object)
			{
				$messageFolder = Message_Folder_Message::where('message_id', $message->id)->where('folder_id', $object->id)->where('user_id', Auth::user()->id)->first();
				if ($messageFolder != null) {
					$messageFolder->folder_id = Auth::user()->inbox;
					$messageFolder->save();
				}
			});
		});
	}

	/********************************************************************
	 * Getter and Setter methods
	 *******************************************************************/

	public function getUnreadMessagesAttribute()
	{
		$messages = Message_Folder_Message::where('folder_id', $this->id)->get();

		$messages = $messages->filter(function ($message) {
			if ($message->message->parent_id == null) {
				return true;
			}
		});

		$messageCount = $messages->count();

		if ($messageCount == 0) {
			$unreadCount = 0;
		} else {
			$messageIds = $messages->message_id->toArray();

			$readCount = Message_User_Read::whereIn('message_id', $messageIds)->where('user_id', Auth::user()->id)->count();

			$unreadCount = $messageCount - $readCount;
		}

		if ($unreadCount > 0) {
			return $unreadCount;
		} else {
			return 0;
		}
	}

	/********************************************************************
	 * Extra Methods
	 *******************************************************************/
}
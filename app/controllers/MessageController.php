<?php

class MessageController extends BaseController {

	public function getIndex() {
		$ignoreMessageIds   = Message_User_Delete::where('user_id', $this->activeUser->id)->get()->message_id->toArray();

		$ignoreFolderIds   = Message_Folder::where('user_id', $this->activeUser->id)->get()->id->toArray();

		if (count($ignoreFolderIds) > 0) {
			$ignoreFolderMessageIds   = Message_Folder_Message::whereIn('folder_id', $ignoreFolderIds)->get()->message_id->toArray();

			$ignoreMessageIds = array_merge($ignoreMessageIds, $ignoreFolderMessageIds);
		}

		if (count($ignoreMessageIds) == 0) {
			$ignoreMessageIds[] = 0;
		}

		$messages = Message::
			  where('receiver_id', $this->activeUser->id)
			->whereNull('child_id')
			->whereNotIn('uniqueId', $ignoreMessageIds)
			->orWhere('sender_id', $this->activeUser->id)
			->whereNull('child_id')
			->whereNotIn('uniqueId', $ignoreMessageIds)
			->orderBy('created_at', 'desc')
			->get();

		$folders = Message_Folder::where('user_id', $this->activeUser->id)->get();
		$displayFolders = new Utility_Collection();

		foreach ($folders as $folder) {
			$newFolder = new stdClass();
			$newFolder->name = $folder->name;
			$newFolder->type = 'folder';
			$newFolder->additionalParameters = array();
			$newFolder->additionalParameters[0] = new stdClass();
			$newFolder->additionalParameters[0]->id = $folder->id;

			$displayFolders[] = $newFolder;
		}

		$this->setViewData('folders', $displayFolders);
	}

}
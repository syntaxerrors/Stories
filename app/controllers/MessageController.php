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

	public function getGetMessagesForFolder($userId)
	{
		$this->skipView();

		$input = Input::all();

		if (!isset($input['node'])) {
			$nodes = array();

			$folders = Message_Folder::where('user_id', $userId)->whereNull('parent_id')->orderByNameAsc()->get(array('uniqueId', 'name'));

			$folders = $folders->each(function($folder) {
				$folder->unreadMessages = $folder->unreadMessages;
			});

			if ($folders->count() > 0) {
				foreach ($folders as $folder) {
					$node    = $this->setUpTreeFolder($folder);
					$nodes[] = $node;
				}
			}

			return Response::json($nodes);
		} else {
			$folderId = $input['node'];

			$children = array();

			// Get any sub-folders
			$subFolders = Message_Folder::where('parent_id', $folderId)->get();
			$subFolders = $subFolders->each(function($folder) {
				$folder->unreadMessages = $folder->unreadMessages;
			});

			if ($subFolders->count() > 0) {
				foreach ($subFolders as $subFolder) {
					$child      = $this->setUpTreeFolder($subFolder);
					$children[] = $child;
				}
			}

			// Get any messages
			$folderMessageIds = Message_Folder_Message::where('folder_id', $folderId)->get()->message_id->toArray();

			// Make sure there are messages in this folder
			if (count($folderMessageIds) > 0) {
				$messages   = Message::whereIn('uniqueId', $folderMessageIds)->whereNull('parent_id')->get();

				// Set up the read/unread icons
				$messages = $messages->each(function ($message) use ($folderId) {
					$message->readIcon = $message->readIcon;
					$message->folderId = $folderId;
				});
			} else {
				$messages = new Message;
			}

			if ($messages->count() > 0 && $messages[0] != null) {
				foreach ($messages as $message) {
					$child           = new stdClass();
					$child->id       = $message->id;
					$child->label    = $message->readIcon .' '. $message->title;
					$child->title    = $message->title;
					$child->type     = 'message';
					$child->folderId = $folderId;

					$children[]      = $child;
				}
			} else {
				$child             = new stdClass();
				$child->id         = 'placeholder'. time();
				$child->label      = 'No messages to display';
				$child->readIcon   = '';
				$child->selectable = false;
				$child->type       = 'placeholder';
				$child->folderId   = $folderId;

				$children[]        = $child;
			}

			// Organize the data for json
			$folderContents = new stdClass();
			$folderContents->folders  = $subFolders->toArray();
			$folderContents->messages = $messages->toArray();

			return Response::json($children);
		}
	}

	protected function setUpTreeFolder($folder)
	{
		$label = $folder->name .' ('. $folder->unreadMessages .')';

		$node                 = new stdClass();
		$node->id             = $folder->id;
		$node->label          = $label;
		$node->title          = $folder->name;
		$node->count          = $folder->unreadMessages;
		$node->type           = 'folder';
		$node->load_on_demand = true;

		return $node;
	}

	public function getGetMessage($messageId)
	{
		$message = Message::find($messageId);

		$folderId = Message_Folder_Message::where('message_id', $messageId)->where('user_id', $this->activeUser->id)->first()->folder_id;

		$this->setViewData('message', $message);
		$this->setViewData('folderId', $folderId);
	}

	public function postMarkRead($read, $messageId)
	{
		$this->skipView();

		// Get the message
		$message            = Message::find($messageId);
		$messageChildrenIds = $message->child->id;

		$messageRead        = Message_User_Read::where('message_id', $message->id)->where('user_id', $this->activeUser->id)->first();

		if ($read == 1 && $messageRead == null) {
			$newMessageRead             = new Message_User_Read;
			$newMessageRead->user_id    = $this->activeUser->id;
			$newMessageRead->message_id = $messageId;

			$this->save($newMessageRead);

			$child = $newMessageRead->message->child;

			while ($child != null) {
				$childRead             = new Message_User_Read;
				$childRead->user_id    = $this->activeUser->id;
				$childRead->message_id = $child->id;

				$this->save($childRead);

				$child = $child->child;
			}
		} elseif ($read == 0 && $messageRead != null) {
			$messageRead->delete();
		}
	}

	public function postMoveMessage($messageId, $previousFolderId, $newFolderId)
	{
		$this->skipView();

		$this->checkMessage($messageId, $previousFolderId, $newFolderId);
	}

	protected function checkMessage($messageId, $previousFolderId, $newFolderId)
	{
		$messageFolderMessage = $this->getMessageFolderMessage($messageId, $previousFolderId);

		if ($messageFolderMessage == null) {
			$newMessageFolderMessage             = new Message_Folder_Message;
			$newMessageFolderMessage->folder_id  = $newFolderId;
			$newMessageFolderMessage->message_id = $messageId;
			$newMessageFolderMessage->user_id    = $this->activeUser->id;

			$this->save($newMessageFolderMessage);

			$child = $newMessageFolderMessage->message->child;

			while ($child != null) {
				$this->checkMessage($child->id, $previousFolderId, $newFolderId);

				$child = $child->child;
			}
		} else {
			$messageFolderMessage->folder_id = $newFolderId;

			$this->save($messageFolderMessage);

			$child = $messageFolderMessage->message->child;

			while ($child != null) {
				$this->checkMessage($child->id, $previousFolderId, $newFolderId);

				$child = $child->child;
			}
		}
	}

	protected function getMessageFolderMessage($messageId, $folderId)
	{
		$messageFolderMessage = Message_Folder_Message::where('folder_id', $folderId)->where('message_id', $messageId)->where('user_id', $this->activeUser->id)->first();

		return $messageFolderMessage;
	}
}
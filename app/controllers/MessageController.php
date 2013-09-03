<?php

class MessageController extends BaseController {

	public function getIndex() {
		$inbox   = Message_Folder::find($this->activeUser->inbox);
		$folders = Message_Folder::where('parent_id', $this->activeUser->inbox)->orderByNameAsc()->get();

		$rootNode = $this->setUpTreeFolder($inbox);

		if ($folders->count() > 0) {
			foreach ($folders as $folder) {
				$folder = $this->setUpTreeFolder($folder);
				$rootNode->children[] = $folder;
			}
		}

		// Only show proper messages
		$inbox->messages = $inbox->messages->filter(function ($message) {
			if ( $message->userDeleted($this->activeUser->id) == 0
					&& ( ($message->parent_id == null && $message->sender_id == $this->activeUser->id && $message->child_id != null)
						|| ($message->sender_id != $this->activeUser->id && $message->parent_id == null) ) ) {
				return true;
			}
		});

		if ($inbox->messages->count() > 0) {
			$messages = $inbox->messages;

			$messages = $messages->sortBy(function ($message) {
				return $message->created_at;
			});
			$messages = $messages->reverse();

			foreach ($messages as $message) {
				$message = $this->setUpTreeMessage($message, $inbox->id);
				$rootNode->children[] = $message;
			}
		} else {
			$rootNode->children[] = $this->setUpTreeMessage(null, $inbox->id, true);
		}

		$this->setViewData('rootNode', $rootNode);
		$this->setViewData('inbox', $inbox->id);
	}

	public function getCompose($replyFlag = 0, $messageId = null, $userId = null)
	{
		$users = User::orderBy('username', 'asc')->get();
		$users = $this->arrayToSelect($users, 'id', 'username', 'Select the recipient');

		$this->setViewData('users', $users);
		$this->setViewData('replyFlag', $replyFlag);

		if ($messageId != null) {
			$message = Message::find($messageId);
			$this->setViewData('message', $message);
		}

		if ($userId != null) {
			$user = User::find($userId);
			$this->setViewData('user', $user);
		}
	}

	public function postCompose()
	{
		$this->skipView();

		$input = e_array(Input::all());

		if ($input != null) {
			$message                  = new Message;
			$message->sender_id       = $this->activeUser->id;
			$message->receiver_id     = $input['receiver_id'];
			$message->title           = $input['title'];
			$message->content         = $input['content'];
			$message->child_id        = (isset($input['child_id']) && strlen($input['child_id']) == 10 ? $input['child_id'] : null);
			$message->message_type_id = 1;

			$this->save($message);

			if ($message->id != null) {
				// Move the message to the receivers's inbox
				$folder             = new Message_Folder_Message;
				$folder->user_id    = $message->receiver->id;
				$folder->message_id = $message->id;
				$folder->folder_id  = $message->receiver->inbox;

				$this->save($folder);

				// Only send to the senders inbox if the sender and receiver are different
				if ($message->receiver->id != $message->sender->id) {
					// Move the message to the senders's inbox
					$folder             = new Message_Folder_Message;
					$folder->user_id    = $this->activeUser->id;
					$folder->message_id = $message->id;
					$folder->folder_id  = $this->activeUser->inbox;

					$this->save($folder);
				}

				// If this is a reply, let the child know
				if ($message->child_id != null) {
					$child            = Message::find($message->child_id);
					$child->parent_id = $message->id;

					$this->save($child);
				}

				// Set as read if this is a reply
				$readMessage             = new Message_User_Read;
				$readMessage->message_id = $message->id;
				$readMessage->user_id    = $this->activeUser->id;

				$this->save($readMessage);
			}

			if ($this->errorCount() > 0) {
				$this->ajaxResponse->addErrors($this->getErrors());
			} else {
				$this->ajaxResponse->setStatus('success');
			}

			// Send the response
			return $this->ajaxResponse->sendResponse();
		}
	}

	public function getAddFolder()
	{
		$folders = Message_Folder::where('user_id', $this->activeUser->id)->orderByNameAsc()->get();
		$folders = $this->arrayToSelect($folders, 'uniqueId', 'name', 'Select a parent folder');

		$this->setViewData('folders', $folders);
		$this->setViewData('inbox', $this->activeUser->inbox);
	}

	public function postAddFolder()
	{
		$input = e_array(Input::all());

		if ($input != null) {
			$messageFolder            = new Message_Folder;
			$messageFolder->parent_id = $input['parent_id'];
			$messageFolder->name      = $input['name'];
			$messageFolder->user_id   = $this->activeUser->id;

			$this->save($messageFolder);

			if ($this->errorCount() > 0) {
				$this->ajaxResponse->addErrors($this->getErrors());
			} else {
				$this->ajaxResponse->setStatus('success');
				$this->ajaxResponse->addData('folder', $this->setUpTreeFolder($messageFolder));
			}

			// Send the response
			return $this->ajaxResponse->sendResponse();
		}
	}

	public function postDeleteMessage($messageId)
	{
		$this->skipView();

		$message             = new Message_User_Delete();
		$message->user_id    = $this->activeUser->id;
		$message->message_id = $messageId;

		$this->save($message);
	}

	public function getGetMessagesForFolder($userId)
	{
		$this->skipView();

		$input    = Input::all();
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

			// Don't get any this user has deleted
			$messages = $messages->filter(function ($message) {
				if ($message->userDeleted($this->activeUser->id) == 0) {
					return true;
				}
			});

			// Set up the read/unread icons
			$messages = $messages->each(function ($message) use ($folderId) {
				$message->readIcon = $message->readIcon;
				$message->folderId = $folderId;
			});
		} else {
			$messages = new Message;
		}

		// Set up the message nodes
		if ($messages->count() > 0 && $messages[0] != null) {
			foreach ($messages as $message) {
				$child      = $this->setUpTreeMessage($message, $folderId);
				$children[] = $child;
			}
		} else {
			$child      = $this->setUpTreeMessage(null, $folderId, true);
			$children[] = $child;
		}

		return Response::json($children);
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

		$messageRead        = Message_User_Read::where('message_id', $message->id)->where('user_id', $this->activeUser->id)->first();

		// If we want it read, but a user read object does not exist
		if ($read == 1 && $messageRead == null) {
			// Set it as read by this user
			$newMessageRead             = new Message_User_Read;
			$newMessageRead->user_id    = $this->activeUser->id;
			$newMessageRead->message_id = $messageId;

			$this->save($newMessageRead);

			$child = $newMessageRead->message->child;

			// Mark any children as read as well
			while ($child != null) {
				$childRead             = new Message_User_Read;
				$childRead->user_id    = $this->activeUser->id;
				$childRead->message_id = $child->id;

				$this->save($childRead);

				$child = $child->child;
			}
		} elseif ($read == 0 && $messageRead != null) {
			$messageRead->delete();

			$child = $messageRead->message->child;

			// Mark any children as read as well
			while ($child != null) {
				$childMessageRead = Message_User_Read::where('message_id', $child->id)->where('user_id', $this->activeUser->id)->first();
				$childMessageRead->delete();

				$child = $child->child;
			}
		}
	}

	public function postMoveMessage($messageId, $previousFolderId, $newFolderId)
	{
		$this->skipView();

		$this->checkMessage($messageId, $previousFolderId, $newFolderId);
	}

	public function postFolderChange($folderId)
	{
		$this->skipView();

		$input = e_array(Input::all());

		if ($input != null) {
			$folder       = Message_Folder::find($folderId);
			$folder->name = $input['name'];

			$this->save($folder);

			if ($this->errorCount() > 0) {
				$this->ajaxResponse->addErrors($this->getErrors());
			} else {
				$this->ajaxResponse->setStatus('success');
			}

			// Send the response
			return $this->ajaxResponse->sendResponse();
		}
	}

	public function postDeleteFolder($folderId)
	{
		$this->skipView();

		$folder = Message_Folder::find($folderId);
		$folder->delete();

		$this->ajaxResponse->setStatus('success');

		// Send the response
		return $this->ajaxResponse->sendResponse();
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
		$node->selectable     = false;
		$node->autoOpen = true;

		return $node;
	}

	protected function setUpTreeMessage($message, $folderId, $placeholder = false)
	{
		if ($placeholder == true) {
			$child             = new stdClass();
			$child->id         = 'placeholder'. time();
			$child->label      = 'No messages to display';
			$child->readIcon   = '';
			$child->selectable = false;
			$child->type       = 'placeholder';
			$child->folderId   = $folderId;
		} else {
			$child           = new stdClass();
			$child->id       = $message->id;
			$child->label    = $message->readIcon .' '. $message->title;
			$child->title    = $message->title;
			$child->readFlag = $message->read;
			$child->type     = 'message';
			$child->folderId = $folderId;
		}

		return $child;
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
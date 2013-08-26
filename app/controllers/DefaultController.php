<?php

class DefaultController extends Controller {

	public function getMenu()
	{
		// Create the default main menu
		$this->addMenu('Home', '/');

		if (Auth::check()) {
			// Forums
			if ($this->hasPermission('FORUM_ACCESS')) {
				$forumArray = array();
				if ($this->hasPermission('FORUM_MOD')) {
					$forumArray['Moderation Panel'] = 'forum/moderation/dashboard';
				}
				if ($this->hasPermission('FORUM_ADMIN')) {
					$forumArray['Admin Panel'] = 'forum/admin/dashboard';
				}
				$postsCount = $this->activeUser->unreadPostCount();
				$forumTitle = ($postsCount > 0 ? 'Forums ('. $postsCount .')' : 'Forums');
				$this->addMenu(
					// $forumTitle,
					$forumTitle,
					'forum',
					$forumArray
				);
			}

			// Chats
			$chatRooms = Chat_Room::active()->orderByNameAsc()->get();
			$rooms = array();
			if (count($chatRooms) > 0) {
				foreach ($chatRooms as $chatRoom) {
					$rooms[$chatRoom->name] = 'chat/room/'. $chatRoom->uniqueId;
				}
			}
			$this->addMenu(
				'Chats',
				'chat',
				$rooms
			);

			// Games
			if ($this->hasPermission('GAME_MASTER')) {
				$games = $this->activeUser->games;
				$gameArray = array();
				if (count($games) > 0) {
					foreach ($games as $game) {
						$gameArray[$game->name] = strtolower($game->type->keyName) .'/'. $game->id;
					}
				}
				$subLinks = array();
				$games = Game::orderByNameAsc()->get();
				foreach ($games as $game) {
					$boardLinks[$game->name] = 'game/board/'. $game->id;
					$addLinks[$game->name]   = 'character/add/'. $game->id;
				}

				$gameArray['Boards']        = $boardLinks;
				$gameArray['Add Character'] = $addLinks;
				$this->addMenu(
					'Games',
					'game',
					$gameArray
				);
			} else {
				$games = Game::orderByNameAsc()->get();
				$subLinks = array();
				foreach ($games as $game) {
					$subLinks[$game->name] = 'game/board/'. $game->id;
				}
				$subLinks['Add Character'] = 
				$this->addMenu(
					'Games',
					'',
					$subLinks
				);
			}

			// Extras
			$this->addMenu('Memberlist', 'memberlist');

			// User Item
			$subLinks = array();
			$subLinks['My Messages... ('. $this->activeUser->unreadMessageCount .')'] = 'messages';
			if ($this->hasPermission('DEVELOPER')) {
				$subLinks['Dev Panel'] = 'admin';
			}
			$subLinks['Logout'] = 'logout';
			$this->addMenu(
				$this->activeUser->username,
				'user/view/'. $this->activeUser->id,
				$subLinks
			);
		} else {
			$this->addMenu('Login', 'login');
			$this->addMenu('Register', 'register');
			$this->addMenu('Forgot Password', 'forgotPassword');
		}
	}

	public function setAreaDetails($area)
	{
		$location = (Request::segment(2) != null ? ': '. ucwords(Request::segment(2)) : '');
		switch ($area) {
			case 'forum':
				$this->pageTitle = 'Forums'. $location;
				if ($this->hasPermission('FORUM_ADMIN')) {
					$this->addSubMenu('Admin Panel','forum-admin', array(
						'Add Category' => 'forum/category/add',
						'Add Board'	=> 'forum/board/add'
					));
				}
			break;
			case 'forum-admin':
				$this->pageTitle = 'Forum Admin'. $location;
				$this->addSubMenu('Forum Admin','forum-admin');
				$this->addSubMenu(
					'Category',
					'javascript:void();',
					array(
						'Manage' => 'forum-admin/category',
						'Types' => 'forum-admin/category/types',
					)
				);
				$this->addSubMenu(
					'Boards',
					'javascript:void();',
					array(
						'Manage' => 'forum-admin/board',
						'Types' => 'forum-admin/board/types',
					)
				);
				$this->addSubMenu(
					'Posts',
					'javascript:void();',
					array(
						'Types' => 'forum-admin/post/types',
					)
				);
				$this->addSubMenu(
					'Replies',
					'javascript:void();',
					array(
						'Types' => 'forum-admin/reply/types',
					)
				);
			break;
			case 'chat':
				$this->pageTitle = 'Chat'. $location;
				if ($this->hasPermission('CHAT_CREATE')) {
					$this->addSubMenu('Add Chat Room', 'chat/add');
				}
			break;
			case 'game':
				$this->pageTitle = 'Games'. $location;
				switch ($location) {
					case ': Template':
						$this->addSubMenu('Add Template','game/template/add');
						$this->addSubMenu('Manage Detail Types','game/template/manage/detailTypes');
					break;
					default:
						$this->addSubMenu('Add Game','game/add');
					break;
				}
			break;
			case 'character':
				$this->pageTitle = 'Character'. $location;
			break;
			case 'admin':
				$this->pageTitle = 'Admin'. $location;
				$this->addSubMenu('Dev Panel','admin');
				if ($this->hasRole('FORUM_ADMIN')) {
					$this->addSubMenu('Forums','forum-admin');
				}
				if ($this->hasRole('DID_ADMIN')) {
					$this->addSubMenu('Dreams in Digital','did_admin');
				}
			break;
			case 'messages':
				$this->pageTitle = 'Messages';

				$subLinks = array();
				// foreach($this->activeUser->folders as $folder) {
				// 	$subLinks[$folder->name] = 'messages/archives/'. $folder->id;
				// }
				$subLinks['Archives'] = 'messages/archives/0';
				$this->addSubMenu('Messages','messages');
				$this->addSubMenu('Send Message','messages/send');
				$this->addSubMenu('Folders','messages/folders', $subLinks);
			break;
			default:
				$this->pageTitle = 'SV'. (Request::segment(1) != null ? ': '.ucwords(Request::segment(1)) : '');
			break;
		}
	}
}
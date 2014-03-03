<?php

class MenuController extends Core_BaseController
{

	public function getMenu()
	{
		Menu::handler('main')
			->add('/', 'Home');

		if (Auth::check()) {
			// Forum access
			if ($this->hasPermission('FORUM_ACCESS')) {
				$postsCount = $this->activeUser->unreadPostCount();
				$forumTitle = ($postsCount > 0 ? 'Forums ('. $postsCount .')' : 'Forums');

				// Forum Options
				if ($this->hasPermission('FORUM_ADMIN')) {
					Menu::handler('main')->add('/forum', $forumTitle, Menu::items()
						->add('/forum/moderation/dashboard', 'Moderation Panel')
						->add('/forum/admin/dashboard', 'Admin Panel', Menu::items()
							->add('/forum/category/add', 'Add Category')
							->add('/forum/board/add', 'Add Board'))
					);
				} elseif ($this->hasPermission('FORUM_MOD')) {
					Menu::handler('main')->add('/forum', $forumTitle, Menu::items()
						->add('/forum/moderation/dashboard', 'Moderation Panel'));
				} else {
					Menu::handler('main')->add('/forum', $forumTitle);
				}
			}

			// Manage Menu
			if ($this->hasPermission('DEVELOPER')) {
				Menu::handler('mainRight')
					->add('javascript:void(0);', 'Management', Menu::items()
						->add('/admin', 'Dev Panel')
						->add('/manage', 'Video Panel')
						->add('/video/add', 'Add Video')
						->add('/video/rss', 'RSS'));
			}

			// User Menu
			Menu::handler('mainRight')
				->add('/user/view/'. $this->activeUser->id, $this->activeUser->username, Menu::items()
					->add('/messages', 'My Messages... ('. $this->activeUser->unreadMessageCount .')')
					->add('/user/account', 'Edit Profile')
					->add('/logout', 'Logout'));
		} else {
			Menu::handler('mainRight')
				->add('/login', 'Login')
				->add('/register', 'Register')
				->add('/forgotPassword', 'Forgot Password');
		}

		Menu::handler('main')
			->add('/memberlist', 'Memberlist');
	}

	public function setAreaDetails($area)
	{
		$location = (Request::segment(2) != null ? ': '. ucwords(Request::segment(2)) : '');

		if ($area != null) {
			$this->pageTitle = ucwords($area).$location;
		} else {
			$this->pageTitle = Config::get('app.siteName'). (Request::segment(1) != null ? ': '.ucwords(Request::segment(1)) : '');
		}
	}
}
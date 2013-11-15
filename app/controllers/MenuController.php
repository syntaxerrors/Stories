<?php

class MenuController extends Core_BaseController
{

	public function getMenu()
	{
		$this->menu->addMenuItem('Home', '/')
				   ->addMenuItem('Memberlist', 'memberlist');

		if (Auth::check()) {
			// Forum access
			if ($this->hasPermission('FORUM_ACCESS')) {
				$postsCount = $this->activeUser->unreadPostCount();
				$forumTitle = ($postsCount > 0 ? 'Forums ('. $postsCount .')' : 'Forums');

				$this->menu->addMenuItem($forumTitle, 'forum', null, 1);

				// Forum Moderation
				if ($this->hasPermission('FORUM_MOD')) {
					$this->menu->addMenuChild($forumTitle, 'Moderation Panel', 'forum/moderation/dashboard');
				}

				// Forum Administration
				if ($this->hasPermission('FORUM_ADMIN')) {
					$this->menu->addMenuChild($forumTitle, 'Admin Panel', 'forum/admin/dashboard')
							   ->addChildChild('Forums', 'Admin Panel', 'Add Category', 'forum/category/add')
							   ->addChildChild('Forums', 'Admin Panel', 'Add Board', 'forum/board/add');
				}
			}

			// User Menu
			$this->menu->addMenuItem($this->activeUser->username, 'user/view/'. $this->activeUser->id, null, null, 'right')
					   ->addMenuChild($this->activeUser->username, 'My Messages... ('. $this->activeUser->unreadMessageCount .')', 'messages')
					   ->addMenuChild($this->activeUser->username, 'Edit Profile', 'user/account')
					   ->addMenuChild($this->activeUser->username, 'Logout', 'logout');

			// Manage Menu
			if ($this->hasPermission('DEVELOPER')) {
				$this->menu->addMenuItem('Management', null, null, null, 'right')
						   ->addMenuChild('Management', 'Dev Panel', 'admin');

				// Github Links
				if ($this->activeUser->githubToken != null) {
					$this->menu->addMenuChild('Management', 'Github Issues', 'github')
							   ->addMenuChild('Management', 'My Github Issues', 'github/user');
				}
			}
		} else {
			$this->menu->addMenuItem('Login', 'login', null, null, 'right');
			$this->menu->addMenuItem('Register', 'register', null, null, 'right');
			$this->menu->addMenuItem('Forgot Password', 'forgotPassword', null, null, 'right');
		}
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
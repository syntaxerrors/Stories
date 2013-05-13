<?php

class BaseController extends Controller {

	/**
	 * Active User
	 *
	 * @var User $activeUser The current user viewing the page
	 */
	public $activeUser;

	public $subMenu = array();

	public $menu    = array();

	public $pageTitle;

	protected $layout;

	/**
	 * Layouts array
	 *
	 * @var string[] $layouts Array of layout templates
	 */
	public $layoutOptions = array(
		'default' => 'layouts.default',
		'ajax'    => 'layouts.ajax'
	);

	/**
	 * Create a new Controller instance.
	 * Assigns the active user
	 *
	 * @return void
	 */
	public function __construct()
	{
		if( !$this->layout ) {
			if ( Request::ajax() ) {
				$this->layout = $this->layoutOptions['ajax'];
			} else {
				$this->layout = $this->layoutOptions['default'];
			}
		}

		// Login required options
		if (Auth::check()) {
			$this->activeUser = Auth::user();
		}

		// Create the default main menu
		$this->addMenu('Home', '');

		// Login required options
		if (Auth::check()) {
			// Forums
			$forumArray = array();
			if ($this->activeUser->can(array('FORUM_MOD'))) {
				$forumArray['Moderation Panel'] = 'forum-admin/moderation';
			}
			if ($this->activeUser->can(array('FORUM_ADMIN'))) {
				$forumArray['Admin Panel'] = 'forum-admin/';
			}
			$forumTitle = ($this->activeUser->unreadPostCount() > 0 ? 'Forums ('. $this->activeUser->unreadPostCount() .')' : 'Forums');
			$this->addMenu(
				$forumTitle,
				'forum',
				$forumArray
			);

			// Media
			$this->addMenu('Media', 'media');

			// Chats
			$chatRooms = Chat_Room::active()->orderBy('name', 'asc')->get();
			$rooms = array();
			if (count($chatRooms) > 0) {
				foreach ($chatRooms as $chatRoom) {
					$rooms[$chatRoom->name] = 'chat/room/'. $chatRoom->id;
				}
			}
			$this->addMenu(
				'Chats',
				'chat',
				$rooms
			);

			// Messages
			$messageName = 'Messages'. ($this->activeUser->unreadMessageCount > 0 ? ' ('. $this->activeUser->unreadMessageCount .')' : null);
			$this->addMenu(
				$messageName,
				'messages',
				array(
					'Send Message' => 'messages/send'
				)
			);

			// Games
			if ($this->hasPermission('GAME_MASTER')) {
				$games = $this->activeUser->games;
				$gameArray = array();
				if (count($games) > 0) {
					foreach ($games as $game) {
						$gameArray[$game->name] = 'game/manage/'. $game->slug;
					}
				}
				if ($this->hasPermission('GAME_TEMPLATE_MANAGE')) {
					$gameArray['Templates'] = 'game/template';
				}

				$games = Game::orderBy('name', 'asc')->get();
				$subLinks = array();
				foreach ($games as $game) {
					$subLinks[$game->name] = 'game/board/'. $game->id;
				}

				$gameArray['Boards'] = $subLinks;
				$this->addMenu(
					'Games',
					'game',
					$gameArray
				);
			} else {
				$games = Game::orderBy('name', 'asc')->get();
				$subLinks = array();
				foreach ($games as $game) {
					$subLinks[$game->name] = 'game/board/'. $game->id;
				}
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
			if ($this->hasPermission('ADMINISTRATION')) {
				$subLinks['Dev Panel'] = 'admin';
			}
			$subLinks['Logout'] = 'logout';
			$this->addMenu(
				$this->activeUser->username,
				'profile/'. $this->activeUser->id,
				$subLinks
			);
		} else {
			$this->addMenu('Login', 'login');
			$this->addMenu('Register', 'register');
			$this->addMenu('Forgot Password', 'forgotPassword');
		}
		// $this->setAreaDetails(Request::segment(1));
	}

	public function missingMethod($parameters)
	{
		if (is_numeric($parameters[0])) {
			$action = 'index';
		} else {
			$action = $parameters[0];
		}
		$route = Route::getContainer()->router->currentRouteAction();
		$route = str_replace('missingMethod', $action, $route);
		$route = $this->cleanRoute($route);
		$this->setTemplate(null, $route);
	}

	/**
	 * Master template method
	 * Sets the template based on location and passes variables to the view.
	 *
	 * @param  string[] $data  An array of variables to pass to the view
	 * @param  string[] $route The route for the view
	 *
	 * @return void
	 */
	public function setTemplate($data = null, $route = null)
	{
		if ($route == null) {
			$route = Route::getContainer()->router->currentRouteAction();
			$route = $this->cleanRoute($route);
		}

		$data['activeUser'] = $this->activeUser;

		$this->layout             = View::make($this->layout);
		$this->layout->pageTitle  = 'SV L4 Test';
		$this->layout->menu       = $this->menu;
		$this->layout->subMenu    = $this->subMenu;
		$this->layout->activeUser = $this->activeUser;
		$this->layout->content    = View::make($route)->with($data);
	}

	protected function cleanRoute($route)
	{
		$routeParts    = explode('@', $route);
		$routeParts[1] = preg_replace('/^get/', '', $routeParts[1]);
		$routeParts[1] = preg_replace('/^post/', '', $routeParts[1]);
		$route         = strtolower(str_replace('Controller', '', implode('.', $routeParts)));

		return $route;
	}

	public function hasPermission($permissions)
	{
		if (Auth::check()) {
			if ($this->activeUser->is('DEVELOPER')) {
				return true;
			}
			$access = $this->activeUser->can($permissions);

			if ($access === true) {
				return true;
			}
		}
		Session::put('pre_login_url', Request::path());
		return false;
	}

	public function hasRole($roles)
	{
		if (Auth::check()) {
			if ($this->activeUser->is('DEVELOPER')) {
				return true;
			}
			$access = $this->activeUser->is($roles);

			if ($access === true) {
				return true;
			}
		}
		Session::put('pre_login_url', Request::path());
		return false;
	}

	public function addSubMenu($text, $link, $subLinks = array())
	{
		$this->subMenu[$text] = array(
			'text'     => $text,
			'link'     => $link,
			'subLinks' => $subLinks,
		);
	}

	public function addMenu($text, $link, $subLinks = array())
	{
		$this->menu[$text] = array(
			'text'     => $text,
			'link'     => $link,
			'subLinks' => $subLinks,
		);
	}

	public function emptySubMenu()
	{
		$this->subMenu = array();
	}

	public function emptyMenu()
	{
		$this->menu = array();
	}

	public function emptyBoth()
	{
		$this->menu = array();
		$this->subMenu = array();
	}

	public function setCSS($css)
	{
		$this->css = $css;
	}

	public static function errorRedirect()
	{
		return Redirect::back()->with_errors(array('You lack the permission(s) required to view this area'))->send();
	}

	public static function redirect($location, $message = null, $back = null)
	{
		if ($message == null) {
			if ($back != null) {
				return Redirect::back()->send();
			}
			return Redirect::to($location)->send();
		} else {
			if ($back != null) {
				return Redirect::back()->with('message', $message)->send();
			}
			return Redirect::to($location)->with('message', $message)->send();
		}
	}

	public static function arrayToSelect($array, $key = 'id', $value = 'name', $first = 'Select One')
	{
		$results = array(
			$first
		);
		foreach ($array as $item) {
			$results[$item->{$key}] = $item->{$value};
		}

		return $results;
	}

}
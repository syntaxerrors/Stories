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
	}

	public function missingMethod($parameters)
	{
		$route = Route::getContainer()->router->currentRouteAction();
		$route = str_replace('missingMethod', $parameters[0], $route);
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
		Session::put('pre_login_url', URL::current());
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
		Session::put('pre_login_url', URL::current());
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
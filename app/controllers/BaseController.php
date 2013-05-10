<?php

class BaseController extends Controller {

	/**
	 * Active User
	 *
	 * @var User $activeUser The current user viewing the page
	 */
	public $activeUser;

	/**
	 * Layouts array
	 *
	 * @var string[] $layouts Array of layout templates
	 */
	public $layouts = array(
		'default' => 'layouts.default',
		'ajax' => 'layouts.ajax'
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
				$this->layout = $this->layouts['ajax'];
			} else {
				$this->layout = $this->layouts['default'];
			}
		}

		// Login required options
		if (Auth::check()) {
			$this->activeUser = Auth::user();
		}
	}

	/**
	 * Master template method
	 * Sets the template based on location and passes variables to the view.
	 *
	 * @param  string[] $data An array of variables to pass to the view
	 *
	 * @return void
	 */
	public function setTemplate($data = null)
	{
		$route = Route::getContainer()->router->currentRouteAction();
		$route = strtolower(str_replace(array('\\','@', 'Controller'), array('.', '.', ''), $route));

		$withVariables = array(
			'activeUser' => $this->activeUser,
		);
		$data['activeUser'] = $this->activeUser;

		$this->layout->nest('content', $route, $data)->with($withVariables);
	}

	protected function separateRoute($route)
	{
		
	}

	/**
	 * Setup the layout used by the controller.
	 *
	 * @return void
	 */
	protected function setupLayout()
	{
		if ( ! is_null($this->layout))
		{
			$this->layout = View::make($this->layout);
		}
	}

}
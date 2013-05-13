<?php

class HomeController extends BaseController {

	public function getIndex()
	{
		$developer = $this->hasRole('DEVELOPER');
		if ($developer) {
			$this->addSubMenu('Add News', 'news/add');
		}
		$newsItems = Forum_Post::with('author')->where('frontPageFlag', '=', 1)->orderBy('created_at', 'DESC')->get();

		$this->setTemplate(array('newsItems' => $newsItems, 'developer' => $developer));
	}

	public function postLogin()
	{
		$input = e_array(Input::all());

		$credentials = array(
			'username' => $input['username'],
			'password' => $input['password'],
		);

		if (Auth::attempt($credentials)) {
			$roles = Auth::user()->roles()->get();

			if (!$roles->contains(1)) {
				$roleKeyNames = array_pluck($roles->toArray(), 'keyName');
				Session::put('roles', $roleKeyNames);

				$permissionKeyNames = array();

				foreach ($roles as $role) {
					$permissions            = $role->permissions()->get();
					$rolePermissionKeyNames = array_pluck($permissions->toArray(), 'keyName');
					$permissionKeyNames     = array_merge($rolePermissionKeyNames);
				}
				Session::put('permissions', $permissionKeyNames);

			} else {
				Session::put('roles', array('DEVELOPER'));
			}
			return Redirect::intended('/');
		} else {
			return Redirect::to('login')->with('login_errors', true);
		}
	}

}
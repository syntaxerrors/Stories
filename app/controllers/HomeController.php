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

	public function getLogin()
	{
		$this->setTemplate();
	}

	public function postLogin($registerInput = null)
	{
		$input = ($registerInput == null ? e_array(Input::all()) : $registerInput);

		$credentials = array(
			'username' => $input['username'],
			'password' => $input['password'],
		);

		if (Auth::attempt($credentials, true)) {
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

	public function postRegister()
	{
		$input = e_array(Input::all());
		if ($input != null) {
			$user             = new User;
			$user->username   = $input['username'];
			$user->password   = $input['password'];
			$user->email      = $input['email'];
			$user->activeFlag = 1;

			$user->save();

			if (count($user->errors->all()) > 0){
				return Redirect::to(Request::path())->with_errors($user->errors->all());
			} else {
				$user->roles()->attach(2); // Add them to StygianVault - Guest by default
				$this->postLogin($input);
			}
		}
	}

	public function getForgotpassword()
	{
		$this->setTemplate();
	}

	public function postForgotpassword()
	{
		$input = e_array(Input::all());
		if ($input != null) {
			$newPassword    = Str::random(15, 'all');
			$user           = User::where('email', '=', $input['email'])->first();
			$user->password = $newPassword;
			$user->save();

			if (count($user->errors) > 0){
				return Redirect::to(Request::path())->with_errors($user->errors);
			} else {
				// Email them the new password
				Mail::send('emails.passwordreset', array('newPassword' => $newPassword), function($m) use ($user) {
					$m->to($user->email, $user->username)->subject('StygianVault Password Reset');
				});

				return Redirect::to('login')->with('message', 'Your new password has been sent to '. $user->email);
			}
		}
	}

}
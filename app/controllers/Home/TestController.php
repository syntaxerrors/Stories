<?php
namespace Home;
use BaseController;

class TestController extends BaseController {

	public function getIndex()
	{
		$user = User::withTrashed()->find(1);
		ppd($user);
	}

	public function getLogin()
	{
		$this->setTemplate();
	}

	public function postLogin()
	{
		$input = e_array(Input::all());

		$credentials = array(
			'username' => $input['username'],
			'password' => $input['password'],
		);

		if (Auth::attempt($credentials)) {
			echo 'Success!';
		}
	}

}
<?php

class ProfileController extends BaseController {

	public function getIndex($userId = null)
	{
		if ($userId == null) {
			$this->redirect('/');
		}
		$user = User::find($userId);
		$this->setTemplate(array('user' => $user));
	}
}
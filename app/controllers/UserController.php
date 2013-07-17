<?php

class UserController extends BaseController {

    public function getIndex()
    {
        
    }

    public function postProfile()
    {
        $input = e_array(Input::all());

        if ($input != null) {
            $user              = User::find($this->activeUser->id);
            $user->password    = $user->password;
            $user->displayName = $input['displayName'];
            $user->firstName   = $input['firstName'];
            $user->lastName    = $input['lastName'];
            $user->email       = $input['email'];
            $user->location    = $input['location'];
            $user->url         = $input['url'];

            $user->save();

            $errors = $this->checkErrors($user);

            if ($errors == true) {
                return $user->getErrors()->toJson();
            }

            return $user->toJson();
        }
    }

    public function postSettings()
    {

    }

    public function getView($userName)
    {

    }

    public function getEdit($username)
    {

    }
}
<?php

class UserController extends BaseController {

    public function getIndex()
    {
        
    }

    public function postProfile()
    {
        $input = e_array(Input::all());

        if ($input != null) {
            $user               = User::find($this->activeUser->id);
            $user->displayName  = $input['displayName'];
            $user->firstName    = $input['firstName'];
            $user->lastName     = $input['lastName'];
            $user->email        = $input['email'];
            $user->location     = $input['location'];
            $user->url          = $input['url'];

            $user->save();

            if ($user == true && count($user->getErrors()->all()) > 0) {
                return Redirect::to(Request::path())->with('errors', $user->getErrors()->all());
            }

            return Redirect::to(Request::path()."#profile");
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
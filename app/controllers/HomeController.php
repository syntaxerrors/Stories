<?php

class HomeController extends BaseController {

    public function postRegistration()
    {
        $input = e_array(Input::all());

        $account = new User;
        $account->username  = $input['username'];
        $account->password  = Hash::make($input['password']);
        $account->email     = $input['email'];
        $account->status_id = 1;

        $account->save();

        $this->checkErrorsRedirect($account);

        return Redirect::to('/');
    }

    public function postLogin()
    {
        $input = e_array(Input::all());
        $userdata = array(
            'username'      => $input['username'],
            'password'      => $input['password']
        );

        if (Auth::attempt($userdata)) {
            $redirect = Session::get('loginRedirect');
            if ( $redirect ) {
               Session::forget('loginRedirect');

               return Redirect::action($redirect);
            }

            return Redirect::to('/scoreboard');
        }
        else {
            return Redirect::to('login')
                ->with('login_errors', true);
        }
    }

    public function getDashboard()
    {
        ppd(Auth::user()->firstName);
    }

}
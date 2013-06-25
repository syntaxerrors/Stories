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

        if (count($account->getErrors()->all()) > 0){
            return Redirect::to(Request::path())->with('errors', $account->getErrors()->all());
        } else {
            return Redirect::to('/');
        }
    }

    public function postLogin()
    {
        $input = e_array(Input::all());
        $userdata = array(
            'username'      => $input['username'],
            'password'      => $input['password']
        );

        if (Auth::attempt($userdata)) {
            $redirect = Cookie::get('loginRedirect');

            if ( $redirect ) {
                Cookie::forget('loginRedirect');

                return Redirect::to('/' . $redirect);
            } 

            return Redirect::to('/dashboard');
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
<?php

class HomeController extends BaseController {

    public function getIndex()
    {
        $developer = $this->hasPermission('DEVELOPER');
        if ($developer) {
            $this->addSubMenu('Add News', 'news/add');
        }
        $newsItems = Forum_Post::with('author')->where('frontPageFlag', 1)->orderBy('created_at', 'DESC')->get();

        $this->setViewData('newsItems', $newsItems);
        $this->setViewData('developer', $developer);
    }

    public function getMemberlist()
    {
        $users = User::orderBy('username', 'asc')->get();

        $this->setViewData('users', $users);
    }

    public function postRegister()
    {
        $input = e_array(Input::all());

        $account = new User;
        $account->username  = $input['username'];
        $account->password  = Hash::make($input['password']);
        $account->email     = $input['email'];
        $account->status_id = 1;

        $account->save();

        $account->roles()->attach(User_Permission_Role::GUEST);

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

            return Redirect::to('/');
        }
        else {
            return Redirect::to('login')
                ->with('login_errors', true);
        }
    }

}
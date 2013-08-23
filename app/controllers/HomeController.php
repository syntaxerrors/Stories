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

        $user            = new User;
        $user->username  = $input['username'];
        $user->password  = Hash::make($input['password']);
        $user->email     = $input['email'];
        $user->status_id = 1;

        $this->checkErrorsSave($user);

        $user->roles()->attach(User_Permission_Role::GUEST);

        return $this->redirect('/');
    }

    public function postLogin()
    {
        $input = e_array(Input::all());
        $userdata = array(
            'username'      => $input['username'],
            'password'      => $input['password']
        );

        if (Auth::attempt($userdata)) {
            $redirect = Session::get('url.intended');
            if ( $redirect ) {
               Session::forget('url.intended');

               return Redirect::to($redirect);
            }

            return Redirect::to('/');
        }
        else {
            return Redirect::to('login')
                ->with('login_errors', true);
        }
    }

}
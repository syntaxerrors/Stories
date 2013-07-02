<?php

class AdminController extends BaseController {
	public function getIndex() {}

    public function getUsers()
    {
        $users = User::orderBy('username', 'asc')->get();

        // Set up the one page crud
        $settings                 = new stdClass();
        $settings->title          = 'Users';
        $settings->sort           = 'username';
        $settings->deleteLink     = '/admin/userdelete/';
        $settings->deleteProperty = 'id';
        $settings->buttons        = array
        (
            'resetPassword' => HTML::link('/admin/resetPassword/--id--', 'Reset Password', array('class' => 'confirm-continue btn btn-mini btn-primary'))
        );
        $settings->displayFields  = array
        (
            'username'    => array('link' => '/profile/user/', 'linkProperty' => 'id'),
            'email'       => array('link' => 'mailto'),
        );
        $settings->formFields     = array
        (
            'username'    => array('field' => 'text',  'required' => true),
            'email'       => array('field' => 'email', 'required' => true),
            'firstName'   => array('field' => 'text'),
            'lastName'    => array('field' => 'text'),
        );

        $this->setViewPath('helpers.crud');
        $this->setViewData('resources', $users);
        $this->setViewData('settings', $settings);
    }

    public function postUsers()
    {
        // Set the input data
        $input = Input::all();

        if ($input != null) {
            // Get the object
            $newPassword       = Str::random(15, 'all');
            $user              = (isset($input['id']) && $input['id'] != null ? User::find($input['id']) : new User);
            $user->username    = $input['username'];
            $user->password    = (isset($input['id']) && $input['id'] != null ? $user->password : $newPassword);
            $user->email       = $input['email'];
            $user->firstName   = $input['firstName'];
            $user->lastName    = $input['lastName'];
            $user->activeFlag  = 1;
            $user->save();

            $user->attributes['fullname'] = $user->fullname;

            if (count($user->getErrors()->all()) > 0){
                return implode('<br />', $user->getErrors()->all());
            } else {
                if (!isset($input['id']) || $input['id'] == null) {
                    // Add new users to StygianVault - Guest by default
                    $roleUser         = new Role_User(array('role_id' => Role::SV_GUEST));
                    $user->roles()->insert($roleUser);

                    // Send them their details
                    $mailer          = IoC::resolve('phpmailer');
                    $mailer->AddAddress($user->email, $user->username);
                    $mailer->Subject = 'Welcome to StygianVault!';
                    $mailer->Body    = 'Your username is '. $user->username .' and your password is '. $newPassword;
                    $mailer->Send();
                }
                return json_encode($user->attributes);
            }
        }
    }

    public function getUserdelete($userId)
    {
        $this->skipView = true;

        $user = User::find($userId);
        $user->activeFlag = 0;
        $user->save();

        return Redirect::back();
    }

    public function getResetpassword($userId)
    {
        $newPassword = Str::random(15, 'all');
        $user = User::find($userId);
        $user->password = $newPassword;
        $user->save();

        // Email them the new password
        $mailer          = IoC::resolve('phpmailer');
        $mailer->AddAddress($user->email, $user->username);
        $mailer->Subject = 'Password reset';
        $mailer->Body    = 'Your password has been reset for StygianVault.  Your new password is  '. $newPassword .'.  Once you log in, go to your profile to change this.';
        $mailer->Send();

        return Redirect::back();
    }

    public function getActions()
    {
        $actions = User_Permission_Action::orderBy('name', 'asc')->get();

        // Set up the one page crud
        $settings                 = new stdClass();
        $settings->title          = 'Actions';
        $settings->sort           = 'name';
        $settings->deleteLink     = '/admin/actiondelete/';
        $settings->deleteProperty = 'id';
        $settings->displayFields  = array
        (
            'name'    => array(),
            'keyName' => array(),
        );
        $settings->formFields     = array
        (
            'name'        => array('field' => 'text', 'required' => true),
            'keyName'     => array('field' => 'text', 'required' => true),
            'description' => array('field' => 'textarea'),
        );

        $this->setViewPath('helpers.crud');
        $this->setViewData('resources', $actions);
        $this->setViewData('settings', $settings);
    }

    public function postActions()
    {
        // Set the input data
        $input = Input::all();

        if ($input != null) {
            // Get the object
            $action              = (isset($input['id']) && $input['id'] != null ? User_Permission_Action::find($input['id']) : new User_Permission_Action);
            $action->name        = $input['name'];
            $action->keyName     = $input['keyName'];
            $action->description = $input['description'];

            $action->save();

            if (count($action->getErrors()->all()) > 0){
                return implode('<br />', $action->getErrors()->all());
            } else {
                return json_encode($action->attributes);
            }
        }
    }

    public function getActiondelete($actionId)
    {
        $this->skipView = true;

        $action = User_Permission_Action::find($actionId);
        $action->roles()->detach();
        $action->delete();

        return Redirect::to('/admin#actions');
    }

    public function getRoles()
    {
        $roles = User_Permission_Role::orderBy('name', 'asc')->get();

        // Set up the one page crud
        $settings                 = new stdClass();
        $settings->title          = 'Roles';
        $settings->sort           = 'name';
        $settings->deleteLink     = '/admin/roledelete/';
        $settings->deleteProperty = 'id';
        $settings->displayFields  = array
        (
            'name'    => array(),
        );
        $settings->formFields     = array
        (
            'name'        => array('field' => 'text',    'required' => true),
            'description' => array('field' => 'textarea'),
        );

        $this->setViewPath('helpers.crud');
        $this->setViewData('resources', $roles);
        $this->setViewData('settings', $settings);
    }

    public function postRoles()
    {
        // Set the input data
        $input = Input::all();

        if ($input != null) {
            // Get the object
            $role              = (isset($input['id']) && $input['id'] != null ? User_Permission_Role::find($input['id']) : new User_Permission_Role);
            $role->name        = $input['name'];
            $role->description = $input['description'];

            $role->save();

            if ($this->checkErrors($role) !== false){
                return implode('<br />', $role->getErrors()->all());
            } else {
                return json_encode($role->attributes);
            }
        }
    }

    public function getRoledelete($roleId)
    {
        $this->skipView = true;

        $role = User_Permission_Role::find($roleId);
        $role->actions()->detach();
        $role->users()->detach();
        $role->delete();

        return Redirect::to('/admin#roles');
    }

    public function getRoleusers()
    {
        $roleUsers = User_Permission_Role_User::orderBy('user_id', 'asc')->orderBy('role_id', 'asc')->get();
        $users     = User::orderBy('username', 'asc')->get();
        $roles     = User_Permission_Role::orderBy('name', 'asc')->get();

        // Set up the one page crud
        $settings                 = new stdClass();
        $settings->title          = 'Role Users';
        $settings->sort           = 'username';
        $settings->deleteLink     = '/admin/roleuserdelete/';
        $settings->deleteProperty = 'id';
        $settings->displayFields  = array
        (
            'username'  => array(),
            'role_name' => array(),
        );
        $settings->formFields     = array
        (
            'user_id' => array('field' => 'select', 'selectArray' => $this->arrayToSelect($users, 'id', 'username', 'Select a user')),
            'role_id' => array('field' => 'select', 'selectArray' => $this->arrayToSelect($roles, 'id', 'name', 'Select a role')),
        );

        $this->setViewPath('helpers.crud');
        $this->setViewData('resources', $roleUsers);
        $this->setViewData('settings', $settings);
    }

    public function postRoleusers()
    {
        // Set the input data
        $input = Input::all();

        if ($input != null) {
            // Get the object
            $roleUser          = (isset($input['id']) && $input['id'] != null ? User_Permission_Role_User::find($input['id']) : new User_Permission_Role_User);
            $roleUser->user_id = $input['user_id'];
            $roleUser->role_id = $input['role_id'];

            $roleUser->save();

            $roleUser->attributes['username']  = $roleUser->username;
            $roleUser->attributes['role_name'] = $roleUser->role_name;

            if ($this->checkErrors($roleUser) !== false){
                return implode('<br />', $roleUser->getErrors()->all());
            } else {
                return json_encode($roleUser->attributes);
            }
        }
    }

    public function getRoleuserdelete($roleUserId)
    {
        $this->skipView = true;

        $roleUser = User_Permission_Role_User::find($roleUserId);
        $roleUser->delete();

        return Redirect::to('/admin#roleusers');
    }

    // public function getRules()
    // {
    //     $rules = Rule::orderBy('role_id', 'asc')->orderBy('permission_id', 'asc')->get();

    //     // Set up the one page crud
    //     $settings                 = new stdClass();
    //     $settings->title          = 'Rules';
    //     $settings->sort           = 'permission_name';
    //     $settings->deleteLink     = '/admin/crud/ruleDelete/';
    //     $settings->deleteProperty = 'id';
    //     $settings->displayFields  = array
    //     (
    //         'permission_name' => array(),
    //         'role_name'       => array(),
    //     );
    //     $settings->formFields     = array
    //     (
    //         'permission_id' => array('field' => 'select', 'selectArray' => $this->arrayToSelect(Permission::orderBy('name', 'asc')->get(), 'id', 'name', 'Select a permission')),
    //         'role_id'       => array('field' => 'select', 'selectArray' => $this->arrayToSelect(Role::orderBy('group', 'asc')->orderBy('value', 'asc')->get(), 'id', 'fullName', 'Select a role')),
    //     );

    //     $this->setViewPath('helpers.crud');
    //     $this->setViewData('resources', $rules);
    //     $this->setViewData('settings', $settings);
    // }

    // public function postRules()
    // {
    //     // Set the input data
    //     $input = Input::all();

    //     if ($input != null) {
    //         // Get the object
    //         $rule                = (isset($input['id']) && $input['id'] != null ? Rule::find($input['id']) : new Rule);
    //         $rule->permission_id = $input['permission_id'];
    //         $rule->role_id       = $input['role_id'];

    //         $rule->save();

    //         $rule->attributes['permission_name'] = $rule->permission_name;
    //         $rule->attributes['role_name']       = $rule->role_name;

    //         if (count($rule->errors->all()) > 0){
    //             return implode('<br />', $rule->errors->all());
    //         } else {
    //             if (count($rule->role->users) > 0) {
    //                 foreach($rule->role->users as $user) {
    //                     $message                  = new Message;
    //                     $message->sender_id       = $this->activeUser->id;
    //                     $message->receiver_id     = $user->user_id;
    //                     $message->message_type_id = Message::PERMISSION;
    //                     $message->title           = 'You have been assigned new permissions.';
    //                     $message->content         = 'Please click the "Update Permissions" button to get access to your new areas.';
    //                     $message->readFlag        = 0;
    //                     $message->save();
    //                 }
    //             }
    //             return json_encode($rule->attributes);
    //         }
    //     }
    // }

    // public function getRuledelete($ruleId)
    // {
    //     $rule = Rule::find($ruleId);
    //     $rule->delete();

    //     if (count($rule->role->users) > 0) {
    //         foreach($rule->role->users as $user) {
    //             $message                  = new Message;
    //             $message->sender_id       = $this->activeUser->id;
    //             $message->receiver_id     = $user->user_id;
    //             $message->message_type_id = Message::PERMISSION;
    //             $message->title           = 'You have been assigned new permissions.';
    //             $message->content         = 'Please click the "Update Permissions" button to get access to your new areas.';
    //             $message->readFlag        = 0;
    //             $message->save();
    //         }
    //     }

    //     return Redirect::to('/admin#Rules');
    // }

    public function getSeries()
    {
        $series = Series::orderBy('name', 'asc')->get();

        // Set up the one page crud
        $settings                 = new stdClass();
        $settings->title          = 'Series';
        $settings->sort           = 'name';
        $settings->deleteLink     = '/admin/seriesdelete/';
        $settings->deleteProperty = 'id';
        $settings->displayFields  = array
        (
            'name'    => array(),
            'keyName' => array(),
        );
        $settings->formFields     = array
        (
            'name'        => array('field' => 'text',    'required' => true),
            'keyName'     => array('field' => 'text',    'required' => true),
        );

        $this->setViewPath('helpers.crud');
        $this->setViewData('resources', $series);
        $this->setViewData('settings', $settings);
    }

    public function postSeries()
    {
        // Set the input data
        $input = Input::all();
        $this->skipView = true;

        if ($input != null) {
            // Get the object
            $series              = (isset($input['id']) && $input['id'] != null ? Series::find($input['id']) : new Series);
            $series->name        = $input['name'];
            $series->keyName     = $input['keyName'];

            $series->save();

            if (count($series->getErrors()->all()) > 0){
                return implode('<br />', $series->errors->getErrors()->all());
            } else {
                return $series->toJson();
            }
        }
    }

    public function getSeriesdelete($seriesId)
    {
        $series = Series::find($seriesId);
        $series->delete();

        $this->skipView = true;
        return Redirect::to('/admin#series');
    }

    public function getGames()
    {
        $games = Game::orderBy('name', 'asc')->get();

        // Set up the one page crud
        $settings                 = new stdClass();
        $settings->title          = 'Games';
        $settings->sort           = 'name';
        $settings->deleteLink     = '/admin/gamedelete/';
        $settings->deleteProperty = 'id';
        $settings->displayFields  = array
        (
            'name'    => array(),
            'keyName' => array(),
        );
        $settings->formFields     = array
        (
            'name'        => array('field' => 'text',    'required' => true),
            'keyName'     => array('field' => 'text',    'required' => true),
        );

        $this->setViewPath('helpers.crud');
        $this->setViewData('resources', $games);
        $this->setViewData('settings', $settings);
    }

    public function postGames()
    {
        // Set the input data
        $input = Input::all();
        $this->skipView = true;

        if ($input != null) {
            // Get the object
            $game              = (isset($input['id']) && $input['id'] != null ? Game::find($input['id']) : new Game);
            $game->name        = $input['name'];
            $game->keyName     = $input['keyName'];

            $game->save();

            if (count($game->getErrors()->all()) > 0){
                return implode('<br />', $game->errors->getErrors()->all());
            } else {
                return $game->toJson();
            }
        }
    }

    public function getGamedelete($gameId)
    {
        $game = Game::find($gameId);
        $game->delete();

        $this->skipView = true;
        return Redirect::to('/admin#games');
    }

    public function getMembers()
    {
        $members = Member::orderBy('name', 'asc')->get();

        // Set up the one page crud
        $settings                 = new stdClass();
        $settings->title          = 'Members';
        $settings->sort           = 'name';
        $settings->deleteLink     = '/admin/memberdelete/';
        $settings->deleteProperty = 'id';
        $settings->displayFields  = array
        (
            'name'    => array(),
            'keyName' => array(),
        );
        $settings->formFields     = array
        (
            'name'        => array('field' => 'text',    'required' => true),
            'keyName'     => array('field' => 'text',    'required' => true),
        );

        $this->setViewPath('helpers.crud');
        $this->setViewData('resources', $members);
        $this->setViewData('settings', $settings);
    }

    public function postMembers()
    {
        // Set the input data
        $input = Input::all();
        $this->skipView = true;

        if ($input != null) {
            // Get the object
            $member              = (isset($input['id']) && $input['id'] != null ? Member::find($input['id']) : new Member);
            $member->name        = $input['name'];
            $member->keyName     = $input['keyName'];

            $member->save();

            if (count($member->getErrors()->all()) > 0){
                return implode('<br />', $member->errors->getErrors()->all());
            } else {
                return $member->toJson();
            }
        }
    }

    public function getMemberdelete($gameId)
    {
        $member = Member::find($gameId);
        $member->delete();

        $this->skipView = true;
        return Redirect::to('/admin#members');
    }

    public function getTeams()
    {
        $teams = Team::orderBy('name', 'asc')->get();

        // Set up the one page crud
        $settings                 = new stdClass();
        $settings->title          = 'Teams';
        $settings->sort           = 'name';
        $settings->deleteLink     = '/admin/teamdelete/';
        $settings->deleteProperty = 'id';
        $settings->displayFields  = array
        (
            'name'    => array(),
            'keyName' => array(),
        );
        $settings->formFields     = array
        (
            'name'        => array('field' => 'text',    'required' => true),
            'keyName'     => array('field' => 'text',    'required' => true),
        );

        $this->setViewPath('helpers.crud');
        $this->setViewData('resources', $teams);
        $this->setViewData('settings', $settings);
    }

    public function postTeams()
    {
        // Set the input data
        $input = Input::all();
        $this->skipView = true;

        if ($input != null) {
            // Get the object
            $team              = (isset($input['id']) && $input['id'] != null ? Team::find($input['id']) : new Team);
            $team->name        = $input['name'];
            $team->keyName     = $input['keyName'];

            $team->save();

            if (count($team->getErrors()->all()) > 0){
                return implode('<br />', $team->errors->getErrors()->all());
            } else {
                return $team->toJson();
            }
        }
    }

    public function getTeamdelete($gameId)
    {
        $team = Team::find($gameId);
        $team->delete();

        $this->skipView = true;
        return Redirect::to('/admin#teams');
    }
}

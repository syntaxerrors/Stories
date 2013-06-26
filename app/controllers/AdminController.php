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
        $action = User_Permission_Action::find($actionId);
        $action->delete();

        return Redirect::to('/admin#Actions');
    }

    public function getRoles()
    {
        $roles = User_Permission_Role::orderBy('name', 'asc')->get();

        // Set up the one page crud
        $settings                 = new stdClass();
        $settings->title          = 'Roles';
        $settings->sort           = 'groupValue';
        $settings->deleteLink     = '/admin/roledelete/';
        $settings->deleteProperty = 'id';
        $settings->displayFields  = array
        (
            'group'   => array(),
            'name'    => array(),
            'keyName' => array(),
            'value'   => array(),
        );
        $settings->formFields     = array
        (
            'group'       => array('field' => 'text',    'required' => true),
            'name'        => array('field' => 'text',    'required' => true),
            'keyName'     => array('field' => 'text',    'required' => true),
            'value'       => array('field' => 'text',    'required' => true),
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
            $role->group       = $input['group'];
            $role->name        = $input['name'];
            $role->keyName     = $input['keyName'];
            $role->value       = $input['value'];
            $role->description = $input['description'];

            $role->save();

            $role->attributes['groupValue'] = $role->groupValue;

            if (count($role->getErrors()->all()) > 0){
                return implode('<br />', $role->getErrors()->all());
            } else {
                return json_encode($role->attributes);
            }
        }
    }

    public function getRoledelete($roleId)
    {
        $role = User_Permission_Role::find($roleId);
        $role->delete();

        return Redirect::to('/admin#Roles');
    }

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

<?php

class AdminController extends BaseController {

    public function __construct()
    {
        parent::__construct();
        // $this->checkPermission('DEVELOPER');
    }
	public function getIndex() {}

    public function getUsers()
    {
        // $this->checkPermission('DEVELOPER');

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
        $this->skipView = true;
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
            $user->status_id   = 1;
            $user->save();

            $user->fullname = $user->fullname;

            $errors = $this->checkErrors($user);

            if ($errors == true) {
                return $user->getErrors()->toJson();
            }

            return $user->toJson();
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
        $this->skipView = true;
        // Set the input data
        $input = Input::all();

        if ($input != null) {
            // Get the object
            $action              = (isset($input['id']) && $input['id'] != null ? User_Permission_Action::find($input['id']) : new User_Permission_Action);
            $action->name        = $input['name'];
            $action->keyName     = $input['keyName'];
            $action->description = $input['description'];

            $action->save();

            $errors = $this->checkErrors($action);

            if ($errors == true) {
                return $action->getErrors()->toJson();
            }

            return $action->toJson();
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
            'group'   => array(),
            'name'    => array(),
            'keyName' => array(),
        );
        $settings->formFields     = array
        (
            'group'       => array('field' => 'text',    'required' => true),
            'name'        => array('field' => 'text',    'required' => true),
            'keyName'     => array('field' => 'text',    'required' => true),
            'description' => array('field' => 'textarea'),
        );

        $this->setViewPath('helpers.crud');
        $this->setViewData('resources', $roles);
        $this->setViewData('settings', $settings);
    }

    public function postRoles()
    {
        $this->skipView = true;
        // Set the input data
        $input = Input::all();

        if ($input != null) {
            // Get the object
            $role              = (isset($input['id']) && $input['id'] != null ? User_Permission_Role::find($input['id']) : new User_Permission_Role);
            $role->group       = $input['group'];
            $role->name        = $input['name'];
            $role->keyName     = $input['keyName'];
            $role->description = $input['description'];

            $role->save();

            $errors = $this->checkErrors($role);

            if ($errors == true) {
                return $role->getErrors()->toJson();
            }

            return $role->toJson();
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
        $this->skipView = true;
        // Set the input data
        $input = Input::all();

        if ($input != null) {
            // Get the object
            $roleUser          = (isset($input['id']) && $input['id'] != null ? User_Permission_Role_User::find($input['id']) : new User_Permission_Role_User);
            $roleUser->user_id = $input['user_id'];
            $roleUser->role_id = $input['role_id'];

            $roleUser->save();

            $roleUser->username  = $roleUser->username;
            $roleUser->role_name = $roleUser->role_name;

            $errors = $this->checkErrors($roleUser);

            if ($errors == true) {
                return $roleUser->getErrors()->toJson();
            }

            return $roleUser->toJson();
        }
    }

    public function getRoleuserdelete($roleUserId)
    {
        $this->skipView = true;

        $roleUser = User_Permission_Role_User::find($roleUserId);
        $roleUser->delete();

        return Redirect::to('/admin#roleusers');
    }

    public function getActionroles()
    {
        $actionRoles = User_Permission_Action_Role::orderBy('role_id', 'asc')->orderBy('action_id', 'asc')->get();
        $actions     = User_Permission_Action::orderBy('name', 'asc')->get();
        $roles       = User_Permission_Role::orderBy('name', 'asc')->get();

        // Set up the one page crud
        $settings                 = new stdClass();
        $settings->title          = 'Action Roles';
        $settings->sort           = 'action_name';
        $settings->deleteLink     = '/admin/actionroledelete/';
        $settings->deleteProperty = 'id';
        $settings->displayFields  = array
        (
            'action_name' => array(),
            'role_name'   => array(),
        );
        $settings->formFields     = array
        (
            'action_id' => array('field' => 'select', 'selectArray' => $this->arrayToSelect($actions, 'id', 'name', 'Select a permission')),
            'role_id'   => array('field' => 'select', 'selectArray' => $this->arrayToSelect($roles, 'id', 'name', 'Select a role')),
        );

        $this->setViewPath('helpers.crud');
        $this->setViewData('resources', $actionRoles);
        $this->setViewData('settings', $settings);
    }

    public function postActionroles()
    {
        $this->skipView = true;
        // Set the input data
        $input = Input::all();

        if ($input != null) {
            // Get the object
            $actionRole            = (isset($input['id']) && $input['id'] != null ? User_Permission_Action_Role::find($input['id']) : new User_Permission_Action_Role);
            $actionRole->action_id = $input['action_id'];
            $actionRole->role_id   = $input['role_id'];

            $actionRole->save();

            $actionRole->action_name = $actionRole->action_name;
            $actionRole->role_name   = $actionRole->role_name;

            $errors = $this->checkErrors($actionRole);

            if ($errors == true) {
                return $actionRole->getErrors()->toJson();
            }

            return $actionRole->toJson();
        }
    }

    public function getActionroledelete($actionRoleId)
    {
        $this->skipView = true;

        $actionRole = User_Permission_Action_Role::find($actionRoleId);
        $actionRole->delete();

        return Redirect::to('/admin#actionroles');
    }
}

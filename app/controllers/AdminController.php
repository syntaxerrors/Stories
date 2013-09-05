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
        $roles = User_Permission_Role::orderBy('group', 'asc')->orderBy('priority', 'asc')->get();

        // Set up the one page crud
        $settings                 = new stdClass();
        $settings->title          = 'Roles';
        $settings->sort           = 'name';
        $settings->deleteLink     = '/admin/roledelete/';
        $settings->deleteProperty = 'id';
        $settings->displayFields  = array
        (
            'group'    => array(),
            'name'     => array(),
            'keyName'  => array(),
            'priority' => array(),
        );
        $settings->formFields     = array
        (
            'group'       => array('field' => 'text',    'required' => true),
            'name'        => array('field' => 'text',    'required' => true),
            'keyName'     => array('field' => 'text',    'required' => true),
            'description' => array('field' => 'textarea'),
            'priority'    => array('field' => 'text'),
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
            $role->priority    = $input['priority'];

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
        $users     = User::orderBy('username', 'asc')->get();
        $roles     = User_Permission_Role::orderBy('name', 'asc')->get();

        // Set up the one page crud
        $settings                 = new stdClass();
        $settings->title          = 'Role Users';
        $settings->sort           = 'username';
        $settings->noDelete       = true;
        $settings->multi          = true;
        $settings->multiObject    = 'roles->name';
        $settings->multiTitle     = 'roles';
        $settings->multiData      = array
        (
            'user_id' => 'id',
            'role_id' => 'roles->id'
        );
        $settings->displayFields  = array
        (
            'username'  => array(),
        );
        $settings->formFields     = array
        (
            'user_id' => array
            (
                'field' => 'select',
                'selectArray' => $this->arrayToSelect($users, 'id', 'username', 'Select a user'),
                'selectValue' => 'id'
            ),
            'role_id' => array
            (
                'field' => 'multiselect',
                'selectArray' => $this->arrayToSelect($roles, 'id', 'name'),
                'selectValue' => 'roles->id'
            ),
        );

        $this->setViewPath('helpers.crud');
        $this->setViewData('resources', $users);
        $this->setViewData('settings', $settings);
    }

    public function postRoleusers()
    {
        $this->skipView();

        // Set the input data
        $input = e_array(Input::all());

        if ($input != null) {
            $user = User::find($input['user_id']);

            if (count($input['role_id']) > 0) {
                $user->roles()->detach();
                $user->roles()->sync($input['role_id']);

                $this->save($user);

                // Handle errors
                if ($this->errorCount() > 0) {
                    $this->ajaxResponse->addErrors($this->getErrors());
                } else {
                   $this->ajaxResponse->setStatus('success');
                }

                // Send the response
                return $this->ajaxResponse->sendResponse();
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

    public function getMessagetypes()
    {
        $messageTypes = Message_Type::orderByNameAsc()->get();

        // Set up the one page crud
        $settings                 = new stdClass();
        $settings->title          = 'Message Types';
        $settings->sort           = 'name';
        $settings->deleteLink     = '/admin/messagetypedelete/';
        $settings->deleteProperty = 'id';
        $settings->displayFields  = array
        (
            'name' => array(),
        );
        $settings->formFields     = array
        (
            'name' => array('field' => 'text'),
        );

        $this->setViewPath('helpers.crud');
        $this->setViewData('resources', $messageTypes);
        $this->setViewData('settings', $settings);
    }

    public function postMessagetypes()
    {
        $this->skipView = true;
        // Set the input data
        $input = Input::all();

        if ($input != null) {
            // Get the object
            $messageType          = (isset($input['id']) && $input['id'] != null ? Message_Type::find($input['id']) : new Message_Type);
            $messageType->name    = $input['name'];
            $messageType->keyName = Str::slug($input['name']);

            $messageType->save();

            $errors = $this->checkErrors($messageType);

            if ($errors == true) {
                return $messageType->getErrors()->toJson();
            }

            return $messageType->toJson();
        }
    }

    public function getMessagetypedelete($messageTypeId)
    {
        $this->skipView = true;

        $messageType = Message_Type::find($messageTypeId);
        $messageType->delete();

        return Redirect::to('/admin#messagetypes');
    }

    public function getGameconfigs()
    {
        $gameConfigs = Game_Config::orderByNameAsc()->get();

        // Set up the one page crud
        $settings                 = new stdClass();
        $settings->title          = 'Game Configs';
        $settings->sort           = 'name';
        $settings->deleteLink     = '/admin/gameconfigdelete/';
        $settings->deleteProperty = 'id';
        $settings->displayFields  = array
        (
            'name'     => array(),
            'uniqueId' => array(),
            'value'    => array(),
        );
        $settings->formFields     = array
        (
            'name'        => array('field' => 'text', 'required' => true),
            'uniqueId'    => array('field' => 'text', 'required' => true),
            'value'       => array('field' => 'text', 'required' => true),
            'description' => array('field' => 'textarea', 'required' => true),
        );

        $this->setViewPath('helpers.crud');
        $this->setViewData('resources', $gameConfigs);
        $this->setViewData('settings', $settings);
    }

    public function postGameconfigs()
    {
        $this->skipView = true;
        // Set the input data
        $input = Input::all();

        if ($input != null) {
            // Get the object
            $gameConfig              = (isset($input['id']) && $input['id'] != null ? Game_Config::find($input['id']) : new Game_Config);
            $gameConfig->name        = $input['name'];
            $gameConfig->uniqueId    = $input['uniqueId'];
            $gameConfig->description = $input['description'];
            $gameConfig->value       = $input['value'];

            $gameConfig->save();

            $errors = $this->checkErrors($gameConfig);

            if ($errors == true) {
                return $gameConfig->getErrors()->toJson();
            }

            return $gameConfig->toJson();
        }
    }

    public function getGameconfigdelete($gameConfigId)
    {
        $this->skipView = true;

        $gameConfig = Game_Config::find($gameConfigId);
        $gameConfig->delete();

        return Redirect::to('/admin#gameconfigs');
    }

    public function getGametypes()
    {
        $gameTypes = Game_Type::orderByNameAsc()->get();

        // Set up the one page crud
        $settings                 = new stdClass();
        $settings->title          = 'Game Types';
        $settings->sort           = 'name';
        $settings->deleteLink     = '/admin/gametypedelete/';
        $settings->deleteProperty = 'id';
        $settings->displayFields  = array
        (
            'name' => array(),
            'keyName'   => array(),
        );
        $settings->formFields     = array
        (
            'name'        => array('field' => 'text', 'required' => true),
            'keyName'     => array('field' => 'text', 'required' => true),
            'description' => array('field' => 'textarea'),
        );

        $this->setViewPath('helpers.crud');
        $this->setViewData('resources', $gameTypes);
        $this->setViewData('settings', $settings);
    }

    public function postGametypes()
    {
        $this->skipView = true;
        // Set the input data
        $input = Input::all();

        if ($input != null) {
            // Get the object
            $gameType              = (isset($input['id']) && $input['id'] != null ? Game_Type::find($input['id']) : new Game_Type);
            $gameType->name        = $input['name'];
            $gameType->keyName     = $input['keyName'];
            $gameType->description = $input['description'];

            $gameType->save();

            $errors = $this->checkErrors($gameType);

            if ($errors == true) {
                return $gameType->getErrors()->toJson();
            }

            return $gameType->toJson();
        }
    }

    public function getGametypedelete($gameTypeId)
    {
        $this->skipView = true;

        $gameType = Game_Type::find($gameTypeId);
        $gameType->delete();

        return Redirect::to('/admin#gametypes');
    }

    public function getCategorytypes()
    {
        $categoryTypes = Forum_Category_Type::orderByNameAsc()->get();

        // Set up the one page crud
        $settings                 = new stdClass();
        $settings->title          = 'Category Types';
        $settings->sort           = 'name';
        $settings->deleteLink     = '/admin/categorytypedelete/';
        $settings->deleteProperty = 'id';
        $settings->displayFields  = array
        (
            'name' => array(),
            'keyName'   => array(),
        );
        $settings->formFields     = array
        (
            'name'        => array('field' => 'text', 'required' => true),
            'keyName'     => array('field' => 'text', 'required' => true),
        );

        $this->setViewPath('helpers.crud');
        $this->setViewData('resources', $categoryTypes);
        $this->setViewData('settings', $settings);
    }

    public function postCategorytypes()
    {
        $this->skipView = true;
        // Set the input data
        $input = Input::all();

        if ($input != null) {
            // Get the object
            $categoryType          = (isset($input['id']) && $input['id'] != null ? Forum_Category_Type::find($input['id']) : new Forum_Category_Type);
            $categoryType->name    = $input['name'];
            $categoryType->keyName = $input['keyName'];

            $categoryType->save();

            $errors = $this->checkErrors($categoryType);

            if ($errors == true) {
                return $categoryType->getErrors()->toJson();
            }

            return $categoryType->toJson();
        }
    }

    public function getCategorytypedelete($categoryTypeId)
    {
        $this->skipView = true;

        $categoryType = Forum_Category_Type::find($categoryTypeId);
        $categoryType->delete();

        return Redirect::to('/admin#categorytypes');
    }

    public function getBoardtypes()
    {
        $boardTypes = Forum_Board_Type::orderByNameAsc()->get();

        // Set up the one page crud
        $settings                 = new stdClass();
        $settings->title          = 'Board Types';
        $settings->sort           = 'name';
        $settings->deleteLink     = '/admin/boardtypedelete/';
        $settings->deleteProperty = 'id';
        $settings->displayFields  = array
        (
            'name' => array(),
            'keyName'   => array(),
        );
        $settings->formFields     = array
        (
            'name'        => array('field' => 'text', 'required' => true),
            'keyName'     => array('field' => 'text', 'required' => true),
        );

        $this->setViewPath('helpers.crud');
        $this->setViewData('resources', $boardTypes);
        $this->setViewData('settings', $settings);
    }

    public function postBoardtypes()
    {
        $this->skipView = true;
        // Set the input data
        $input = Input::all();

        if ($input != null) {
            // Get the object
            $boardType          = (isset($input['id']) && $input['id'] != null ? Forum_Board_Type::find($input['id']) : new Forum_Board_Type);
            $boardType->name    = $input['name'];
            $boardType->keyName = $input['keyName'];

            $boardType->save();

            $errors = $this->checkErrors($boardType);

            if ($errors == true) {
                return $boardType->getErrors()->toJson();
            }

            return $boardType->toJson();
        }
    }

    public function getBoardtypedelete($boardTypeId)
    {
        $this->skipView = true;

        $boardType = Forum_Board_Type::find($boardTypeId);
        $boardType->delete();

        return Redirect::to('/admin#boardtypes');
    }

    public function getPosttypes()
    {
        $postTypes = Forum_Post_Type::orderByNameAsc()->get();

        // Set up the one page crud
        $settings                 = new stdClass();
        $settings->title          = 'Post Types';
        $settings->sort           = 'name';
        $settings->deleteLink     = '/admin/posttypedelete/';
        $settings->deleteProperty = 'id';
        $settings->displayFields  = array
        (
            'name' => array(),
            'keyName'   => array(),
        );
        $settings->formFields     = array
        (
            'name'        => array('field' => 'text', 'required' => true),
            'keyName'     => array('field' => 'text', 'required' => true),
        );

        $this->setViewPath('helpers.crud');
        $this->setViewData('resources', $postTypes);
        $this->setViewData('settings', $settings);
    }

    public function postPosttypes()
    {
        $this->skipView = true;
        // Set the input data
        $input = Input::all();

        if ($input != null) {
            // Get the object
            $postType          = (isset($input['id']) && $input['id'] != null ? Forum_Post_Type::find($input['id']) : new Forum_Post_Type);
            $postType->name    = $input['name'];
            $postType->keyName = $input['keyName'];

            $postType->save();

            $errors = $this->checkErrors($postType);

            if ($errors == true) {
                return $postType->getErrors()->toJson();
            }

            return $postType->toJson();
        }
    }

    public function getPosttypedelete($postTypeId)
    {
        $this->skipView = true;

        $postType = Forum_Post_Type::find($postTypeId);
        $postType->delete();

        return Redirect::to('/admin#posttypes');
    }

    public function getReplytypes()
    {
        $replyTypes = Forum_Reply_Type::orderByNameAsc()->get();

        // Set up the one page crud
        $settings                 = new stdClass();
        $settings->title          = 'Reply Types';
        $settings->sort           = 'name';
        $settings->deleteLink     = '/admin/replytypedelete/';
        $settings->deleteProperty = 'id';
        $settings->displayFields  = array
        (
            'name' => array(),
            'keyName'   => array(),
        );
        $settings->formFields     = array
        (
            'name'        => array('field' => 'text', 'required' => true),
            'keyName'     => array('field' => 'text', 'required' => true),
        );

        $this->setViewPath('helpers.crud');
        $this->setViewData('resources', $replyTypes);
        $this->setViewData('settings', $settings);
    }

    public function postReplytypes()
    {
        $this->skipView = true;
        // Set the input data
        $input = Input::all();

        if ($input != null) {
            // Get the object
            $replyType          = (isset($input['id']) && $input['id'] != null ? Forum_Reply_Type::find($input['id']) : new Forum_Reply_Type);
            $replyType->name    = $input['name'];
            $replyType->keyName = $input['keyName'];

            $replyType->save();

            $errors = $this->checkErrors($replyType);

            if ($errors == true) {
                return $replyType->getErrors()->toJson();
            }

            return $replyType->toJson();
        }
    }

    public function getReplytypedelete($replyTypeId)
    {
        $this->skipView = true;

        $replyType = Forum_Reply_Type::find($replyTypeId);
        $replyType->delete();

        return Redirect::to('/admin#replytypes');
    }
}

<?php

class AdminController extends BaseController {

	public function getIndex() {}

    public function getUsers()
    {
        $users = User::orderBy('username', 'asc')->get();

        // Set up the one page crud main details
        $settings = new Utility_Crud();
        $settings->setTitle('Users')
                 ->setSortProperty('username')
                 ->setDeleteLink('/admin/userdelete/')
                 ->setDeleteProperty('id')
                 ->setResources($users);

        // Add any new buttons
        $settings->addButton('resetPassword', '/admin/resetPassword/--id--', 'Reset Password', array('class' => 'confirm-continue btn btn-mini btn-primary'));

        // Add the display columns
        $settings->addDisplayField('username', '/profile/user/', 'id')
                 ->addDisplayField('email', 'mailto');

        // Add the form fields
        $settings->addFormField('username', 'text', null, true)
                 ->addFormField('email', 'email', null, true)
                 ->addFormField('firstName', 'text')
                 ->addFormField('lastName', 'text');

        // Set the view data
        $this->setViewPath('helpers.crud');
        $this->setViewData('settings', $settings);
    }

    public function postUsers()
    {
        $this->skipView();
        // Set the input data
        $input = e_array(Input::all());

        if ($input != null) {
            // Get the object
            $newPassword       = Str::random(15, 'all');
            $user              = (isset($input['id']) && strlen($input['id']) == 10 ? User::find($input['id']) : new User);
            $user->username    = $input['username'];
            $user->password    = (isset($input['id']) && strlen($input['id']) == 10 ? $user->password : $newPassword);
            $user->email       = $input['email'];
            $user->firstName   = $input['firstName'];
            $user->lastName    = $input['lastName'];
            $user->status_id   = 1;

            // Attempt to save the object
            $this->save($user);

            // Handle errors
            if ($this->errorCount() > 0) {
                $this->ajaxResponse->addErrors($this->getErrors());
            } else {
               $this->ajaxResponse->setStatus('success')->addData('resource', $user->toArray());
            }

            // Send the response
            return $this->ajaxResponse->sendResponse();
        }
    }

    public function getUserdelete($userId)
    {
        $this->skipView();

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
        $settings = new Utility_Crud();
        $settings->setTitle('Actions')
                 ->setSortProperty('name')
                 ->setDeleteLink('/admin/actiondelete/')
                 ->setDeleteProperty('id')
                 ->setResources($actions);

        // Add the display columns
        $settings->addDisplayField('name')
                 ->addDisplayField('keyName');

        // Add the form fields
        $settings->addFormField('name', 'text')
                 ->addFormField('keyName', 'text')
                 ->addFormField('description', 'textarea');

        $this->setViewPath('helpers.crud');
        $this->setViewData('settings', $settings);
    }

    public function postActions()
    {
        $this->skipView();
        // Set the input data
        $input = e_array(Input::all());

        if ($input != null) {
            // Get the object
            $action              = (isset($input['id']) && $input['id'] != null ? User_Permission_Action::find($input['id']) : new User_Permission_Action);
            $action->name        = $input['name'];
            $action->keyName     = $input['keyName'];
            $action->description = $input['description'];

            // Attempt to save the object
            $this->save($action);

            // Handle errors
            if ($this->errorCount() > 0) {
                $this->ajaxResponse->addErrors($this->getErrors());
            } else {
               $this->ajaxResponse->setStatus('success')->addData('resource', $action->toArray());
            }

            // Send the response
            return $this->ajaxResponse->sendResponse();
        }
    }

    public function getActiondelete($actionId)
    {
        $this->skipView();

        $action = User_Permission_Action::find($actionId);
        $action->roles()->detach();
        $action->delete();

        return Redirect::to('/admin#actions');
    }

    public function getRoles()
    {
        $roles = User_Permission_Role::orderBy('group', 'asc')->orderBy('priority', 'asc')->get();

        // Set up the one page crud
        $settings = new Utility_Crud();
        $settings->setTitle('Roles')
                 ->setSortProperty('name')
                 ->setDeleteLink('/admin/roledelete/')
                 ->setDeleteProperty('id')
                 ->setResources($roles);

        // Add the display columns
        $settings->addDisplayField('group')
                 ->addDisplayField('name')
                 ->addDisplayField('keyName')
                 ->addDisplayField('priority');

        // Add the form fields
        $settings->addFormField('group', 'text', null, true)
                 ->addFormField('name', 'text', null, true)
                 ->addFormField('keyName', 'text', null, true)
                 ->addFormField('description', 'textarea')
                 ->addFormField('priority', 'text');

        $this->setViewPath('helpers.crud');
        $this->setViewData('settings', $settings);
    }

    public function postRoles()
    {
        $this->skipView();
        // Set the input data
        $input = e_array(Input::all());

        if ($input != null) {
            // Get the object
            $role              = (isset($input['id']) && $input['id'] != null ? User_Permission_Role::find($input['id']) : new User_Permission_Role);
            $role->group       = $input['group'];
            $role->name        = $input['name'];
            $role->keyName     = $input['keyName'];
            $role->description = $input['description'];
            $role->priority    = $input['priority'];

            // Attempt to save the object
            $this->save($role);

            // Handle errors
            if ($this->errorCount() > 0) {
                $this->ajaxResponse->addErrors($this->getErrors());
            } else {
               $this->ajaxResponse->setStatus('success')->addData('resource', $role->toArray());
            }

            // Send the response
            return $this->ajaxResponse->sendResponse();
        }
    }

    public function getRoledelete($roleId)
    {
        $this->skipView();

        $role = User_Permission_Role::find($roleId);
        $role->actions()->detach();
        $role->users()->detach();
        $role->delete();

        return Redirect::to('/admin#roles');
    }

    public function getRoleusers()
    {
        $users     = User::orderBy('username', 'asc')->get();
        $roles     = User_Permission_Role::orderByNameAsc()->get();

        $usersArray = $this->arrayToSelect($users, 'id', 'username', 'Select a user');
        $rolesArray = $this->arrayToSelect($roles, 'id', 'name', 'None');

        // Set up the one page crud
        $settings = new Utility_Crud();
        $settings->setTitle('Role Users')
                 ->setSortProperty('username')
                 ->setMulti($users, 'roles')
                 ->setMultiColumns(array('Users', 'Roles'))
                 ->setMultiDetails(array('name' => 'username', 'field' => 'user_id'))
                 ->setMultiPropertyDetails(array('name' => 'name', 'field' => 'role_id'));

        // Add the form fields
        $settings->addFormField('user_id', 'select', $usersArray)
                 ->addFormField('role_id', 'multiselect', $rolesArray);

        $this->setViewPath('helpers.crud');
        $this->setViewData('settings', $settings);
    }

    public function postRoleusers()
    {
        $this->skipView();

        // Set the input data
        $input = e_array(Input::all());

        if ($input != null) {
            // Remove all existing roles
            $roleUsers = User_Permission_Role_User::where('user_id', $input['user_id'])->get();

            if ($roleUsers->count() > 0) {
                foreach ($roleUsers as $roleUser) {
                    $roleUser->delete();
                }
            }

            // Add any new roles
            if (count($input['role_id']) > 0) {
                foreach ($input['role_id'] as $roleId) {
                    if ($roleId == '0') continue;

                    $roleUser = new User_Permission_Role_User;
                    $roleUser->user_id = $input['user_id'];
                    $roleUser->role_id = $roleId;

                    $this->save($roleUser);
                }
            }

            // Handle errors
            if ($this->errorCount() > 0) {
                $this->ajaxResponse->addErrors($this->getErrors());
            } else {
                $user = User::find($input['user_id']);

                $main = $user->toArray();
                $main['multi'] = $user->roles->id->toJson();

                $this->ajaxResponse->setStatus('success')
                                    ->addData('resource', $user->roles->toArray())
                                    ->addData('main', $main);
            }

            // Send the response
            return $this->ajaxResponse->sendResponse();
        }
    }

    public function getActionroles()
    {
        $actions = User_Permission_Action::orderByNameAsc()->get();
        $roles   = User_Permission_Role::orderByNameAsc()->get();

        // Set up the one page crud
        $settings = new Utility_Crud();
        $settings->setTitle('Action Roles')
                 ->setSortProperty('name')
                 ->setMulti($roles, 'actions')
                 ->setMultiColumns(array('Roles', 'Actions'))
                 ->setMultiDetails(array('name' => 'name', 'field' => 'role_id'))
                 ->setMultiPropertyDetails(array('name' => 'name', 'field' => 'action_id'));

        // Add the form fields
        $settings->addFormField('role_id', 'select', $this->arrayToSelect($roles, 'id', 'name', 'Select a role'))
                 ->addFormField('action_id', 'multiselect', $this->arrayToSelect($actions, 'id', 'name', 'None'));

        $this->setViewPath('helpers.crud');
        $this->setViewData('settings', $settings);
    }

    public function postActionroles()
    {
        $this->skipView();

        // Set the input data
        $input = e_array(Input::all());

        if ($input != null) {
            // Remove all existing roles
            $actionRoles = User_Permission_Action_Role::where('role_id', $input['role_id'])->get();

            if ($actionRoles->count() > 0) {
                foreach ($actionRoles as $actionRole) {
                    $actionRole->delete();
                }
            }

            // Add any new roles
            if (count($input['action_id']) > 0) {
                foreach ($input['action_id'] as $actionId) {
                    if ($actionId == '0') continue;

                    $actionRole            = new User_Permission_Action_Role;
                    $actionRole->role_id   = $input['role_id'];
                    $actionRole->action_id = $actionId;

                    $this->save($actionRole);
                }
            }

            // Handle errors
            if ($this->errorCount() > 0) {
                $this->ajaxResponse->addErrors($this->getErrors());
            } else {
                $role = User_Permission_Role::find($input['role_id']);

                $main = $role->toArray();
                $main['multi'] = $role->actions->id->toJson();

                $this->ajaxResponse->setStatus('success')
                                   ->addData('resource', $role->actions->toArray())
                                   ->addData('main', $main);
            }

            // Send the response
            return $this->ajaxResponse->sendResponse();
        }
    }

    public function getTheme()
    {
        $masterLess = public_path() .'/css/master_css.less';

        $lines = file($masterLess);

        $colors = array();

        $colors['grey']    = array('title' => 'Background Color',          'hex' => substr(explode('@grey: ',            $lines[4])[1],  0, -2));
        $colors['primary'] = array('title' => 'Primary Color',             'hex' => substr(explode('@primaryColor: ',    $lines[6])[1],  0, -2));
        $colors['info']    = array('title' => 'Information Color',         'hex' => substr(explode('@infoColor: ',       $lines[10])[1],  0, -2));
        $colors['success'] = array('title' => 'Success Color',             'hex' => substr(explode('@successColor: ',    $lines[13])[1], 0, -2));
        $colors['warning'] = array('title' => 'Warning Color',             'hex' => substr(explode('@warningColor: ',    $lines[16])[1], 0, -2));
        $colors['error']   = array('title' => 'Error Color',               'hex' => substr(explode('@errorColor: ',      $lines[19])[1], 0, -2));
        $colors['menu']    = array('title' => 'Active Menu Link Color',    'hex' => substr(explode('@menuColor: ',       $lines[22])[1], 0, -2));

        $this->setViewData('colors', $colors);
    }

    public function postTheme()
    {
        $input = e_array(Input::all());

        if ($input != null) {
            $masterLess = public_path() .'/css/master_css.less';
            $masterCss  = public_path() .'/css/master.css';

            $lines = file($masterLess);

            // Set the new colors
            $lines[4]  = '@grey: '. $input['grey'] .";\n";
            $lines[6]  = '@primaryColor: '. $input['primary'] .";\n";
            $lines[10]  = '@infoColor: '. $input['info'] .";\n";
            $lines[13] = '@successColor: '. $input['success'] .";\n";
            $lines[16] = '@warningColor: '. $input['warning'] .";\n";
            $lines[19] = '@errorColor: '. $input['error'] .";\n";
            $lines[22] = '@menuColor: '. $input['menu'] .";\n";

            File::delete($masterLess);
            File::delete($masterCss);

            File::put($masterLess, implode($lines));

            $less = new lessc;
            $less->compileFile($masterLess, $masterCss);

            $this->ajaxResponse->setStatus('success');
            return $this->ajaxResponse->sendResponse();
        }
    }

    public function getMessagetypes()
    {
        $messageTypes = Message_Type::orderByNameAsc()->get();

        // Set up the one page crud
        $settings = new Utility_Crud();
        $settings->setTitle('Message Types')
                 ->setSortProperty('name')
                 ->setDeleteLink('/admin/messagetypedelete/')
                 ->setDeleteProperty('id')
                 ->setResources($messageTypes);

        // Add the display columns
        $settings->addDisplayField('name');

        // Add the form fields
        $settings->addFormField('name', 'text');

        $this->setViewPath('helpers.crud');
        $this->setViewData('settings', $settings);
    }

    public function postMessagetypes()
    {
        $this->skipView();
        // Set the input data
        $input = e_array(Input::all());

        if ($input != null) {
            // Get the object
            $messageType          = (isset($input['id']) && $input['id'] != null ? Message_Type::find($input['id']) : new Message_Type);
            $messageType->name    = $input['name'];
            $messageType->keyName = Str::slug($input['name']);

            // Attempt to save the object
            $this->save($messageType);

            // Handle errors
            if ($this->errorCount() > 0) {
                $this->ajaxResponse->addErrors($this->getErrors());
            } else {
               $this->ajaxResponse->setStatus('success')->addData('resource', $messageType->toArray());
            }

            // Send the response
            return $this->ajaxResponse->sendResponse();
        }
    }

    public function getMessagetypedelete($messageTypeId)
    {
        $this->skipView();

        $messageType = Message_Type::find($messageTypeId);
        $messageType->delete();

        return Redirect::to('/admin#messagetypes');
    }

    public function getGameconfigs()
    {
        $gameConfigs = Game_Config::orderByNameAsc()->get();

        // Set up the one page crud
        $settings = new Utility_Crud();
        $settings->setTitle('Game Configs')
                 ->setSortProperty('name')
                 ->setDeleteLink('/admin/gameconfigdelete/')
                 ->setDeleteProperty('id')
                 ->setResources($gameConfigs);

        // Add the display columns
        $settings->addDisplayField('name')
                 ->addDisplayField('uniqueId')
                 ->addDisplayField('value');

        // Add the form fields
        $settings->addFormField('name', 'text', null, true)
                 ->addFormField('uniqueId', 'text', null, true)
                 ->addFormField('value', 'text', null, true)
                 ->addFormField('description', 'textarea', null, true);

        $this->setViewPath('helpers.crud');
        $this->setViewData('settings', $settings);
    }

    public function postGameconfigs()
    {
        $this->skipView();
        // Set the input data
        $input = e_array(Input::all());

        if ($input != null) {
            // Get the object
            $gameConfig              = (isset($input['id']) && $input['id'] != null ? Game_Config::find($input['id']) : new Game_Config);
            $gameConfig->name        = $input['name'];
            $gameConfig->uniqueId    = $input['uniqueId'];
            $gameConfig->description = $input['description'];
            $gameConfig->value       = $input['value'];

            // Attempt to save the object
            $this->save($gameConfig);

            // Handle errors
            if ($this->errorCount() > 0) {
                $this->ajaxResponse->addErrors($this->getErrors());
            } else {
               $this->ajaxResponse->setStatus('success')->addData('resource', $gameConfig->toArray());
            }

            // Send the response
            return $this->ajaxResponse->sendResponse();
        }
    }

    public function getGameconfigdelete($gameConfigId)
    {
        $this->skipView();

        $gameConfig = Game_Config::find($gameConfigId);
        $gameConfig->delete();

        return Redirect::to('/admin#gameconfigs');
    }

    public function getGametypes()
    {
        $gameTypes = Game_Type::orderByNameAsc()->get();

        // Set up the one page crud
        $settings = new Utility_Crud();
        $settings->setTitle('Game Types')
                 ->setSortProperty('name')
                 ->setDeleteLink('/admin/gametypedelete/')
                 ->setDeleteProperty('id')
                 ->setResources($gameTypes);

        // Add the display columns
        $settings->addDisplayField('name')
                 ->addDisplayField('keyName');

        // Add the form fields
        $settings->addFormField('name', 'text', null, true)
                 ->addFormField('keyName', 'text', null, true)
                 ->addFormField('description', 'textarea');

        $this->setViewPath('helpers.crud');
        $this->setViewData('settings', $settings);
    }

    public function postGametypes()
    {
        $this->skipView();
        // Set the input data
        $input = e_array(Input::all());

        if ($input != null) {
            // Get the object
            $gameType              = (isset($input['id']) && $input['id'] != null ? Game_Type::find($input['id']) : new Game_Type);
            $gameType->name        = $input['name'];
            $gameType->keyName     = $input['keyName'];
            $gameType->description = $input['description'];

            // Attempt to save the object
            $this->save($gameType);

            // Handle errors
            if ($this->errorCount() > 0) {
                $this->ajaxResponse->addErrors($this->getErrors());
            } else {
               $this->ajaxResponse->setStatus('success')->addData('resource', $gameType->toArray());
            }

            // Send the response
            return $this->ajaxResponse->sendResponse();
        }
    }

    public function getGametypedelete($gameTypeId)
    {
        $this->skipView();

        $gameType = Game_Type::find($gameTypeId);
        $gameType->delete();

        return Redirect::to('/admin#gametypes');
    }

    public function getCategorytypes()
    {
        $categoryTypes = Forum_Category_Type::orderByNameAsc()->get();

        // Set up the one page crud
        $settings = new Utility_Crud();
        $settings->setTitle('Category Types')
                 ->setSortProperty('name')
                 ->setDeleteLink('/admin/categorytypedelete/')
                 ->setDeleteProperty('id')
                 ->setResources($categoryTypes);

        // Add the display columns
        $settings->addDisplayField('name')
                 ->addDisplayField('keyName');

        // Add the form fields
        $settings->addFormField('name', 'text', null, true)
                 ->addFormField('keyName', 'text', null, true);

        $this->setViewPath('helpers.crud');
        $this->setViewData('settings', $settings);
    }

    public function postCategorytypes()
    {
        $this->skipView();
        // Set the input data
        $input = e_array(Input::all());

        if ($input != null) {
            // Get the object
            $categoryType          = (isset($input['id']) && $input['id'] != null ? Forum_Category_Type::find($input['id']) : new Forum_Category_Type);
            $categoryType->name    = $input['name'];
            $categoryType->keyName = $input['keyName'];

            // Attempt to save the object
            $this->save($categoryType);

            // Handle errors
            if ($this->errorCount() > 0) {
                $this->ajaxResponse->addErrors($this->getErrors());
            } else {
               $this->ajaxResponse->setStatus('success')->addData('resource', $categoryType->toArray());
            }

            // Send the response
            return $this->ajaxResponse->sendResponse();
        }
    }

    public function getCategorytypedelete($categoryTypeId)
    {
        $this->skipView();

        $categoryType = Forum_Category_Type::find($categoryTypeId);
        $categoryType->delete();

        return Redirect::to('/admin#categorytypes');
    }

    public function getBoardtypes()
    {
        $boardTypes = Forum_Board_Type::orderByNameAsc()->get();

        // Set up the one page crud
        $settings = new Utility_Crud();
        $settings->setTitle('Board Types')
                 ->setSortProperty('name')
                 ->setDeleteLink('/admin/boardtypedelete/')
                 ->setDeleteProperty('id')
                 ->setResources($boardTypes);

        // Add the display columns
        $settings->addDisplayField('name')
                 ->addDisplayField('keyName');

        // Add the form fields
        $settings->addFormField('name', 'text', null, true)
                 ->addFormField('keyName', 'text', null, true);

        $this->setViewPath('helpers.crud');
        $this->setViewData('settings', $settings);
    }

    public function postBoardtypes()
    {
        $this->skipView();
        // Set the input data
        $input = e_array(Input::all());

        if ($input != null) {
            // Get the object
            $boardType          = (isset($input['id']) && $input['id'] != null ? Forum_Board_Type::find($input['id']) : new Forum_Board_Type);
            $boardType->name    = $input['name'];
            $boardType->keyName = $input['keyName'];

            // Attempt to save the object
            $this->save($boardType);

            // Handle errors
            if ($this->errorCount() > 0) {
                $this->ajaxResponse->addErrors($this->getErrors());
            } else {
               $this->ajaxResponse->setStatus('success')->addData('resource', $boardType->toArray());
            }

            // Send the response
            return $this->ajaxResponse->sendResponse();
        }
    }

    public function getBoardtypedelete($boardTypeId)
    {
        $this->skipView();

        $boardType = Forum_Board_Type::find($boardTypeId);
        $boardType->delete();

        return Redirect::to('/admin#boardtypes');
    }

    public function getPosttypes()
    {
        $postTypes = Forum_Post_Type::orderByNameAsc()->get();

        // Set up the one page crud
        $settings = new Utility_Crud();
        $settings->setTitle('Post Types')
                 ->setSortProperty('name')
                 ->setDeleteLink('/admin/posttypedelete/')
                 ->setDeleteProperty('id')
                 ->setResources($postTypes);

        // Add the display columns
        $settings->addDisplayField('name')
                 ->addDisplayField('keyName');

        // Add the form fields
        $settings->addFormField('name', 'text', null, true)
                 ->addFormField('keyName', 'text', null, true);

        $this->setViewPath('helpers.crud');
        $this->setViewData('settings', $settings);
    }

    public function postPosttypes()
    {
        $this->skipView();
        // Set the input data
        $input = e_array(Input::all());

        if ($input != null) {
            // Get the object
            $postType          = (isset($input['id']) && $input['id'] != null ? Forum_Post_Type::find($input['id']) : new Forum_Post_Type);
            $postType->name    = $input['name'];
            $postType->keyName = $input['keyName'];

            // Attempt to save the object
            $this->save($postType);

            // Handle errors
            if ($this->errorCount() > 0) {
                $this->ajaxResponse->addErrors($this->getErrors());
            } else {
               $this->ajaxResponse->setStatus('success')->addData('resource', $postType->toArray());
            }

            // Send the response
            return $this->ajaxResponse->sendResponse();
        }
    }

    public function getPosttypedelete($postTypeId)
    {
        $this->skipView();

        $postType = Forum_Post_Type::find($postTypeId);
        $postType->delete();

        return Redirect::to('/admin#posttypes');
    }

    public function getReplytypes()
    {
        $replyTypes = Forum_Reply_Type::orderByNameAsc()->get();

        // Set up the one page crud
        $settings = new Utility_Crud();
        $settings->setTitle('Reply Types')
                 ->setSortProperty('name')
                 ->setDeleteLink('/admin/replytypedelete/')
                 ->setDeleteProperty('id')
                 ->setResources($replyTypes);

        // Add the display columns
        $settings->addDisplayField('name')
                 ->addDisplayField('keyName');

        // Add the form fields
        $settings->addFormField('name', 'text', null, true)
                 ->addFormField('keyName', 'text', null, true);

        $this->setViewPath('helpers.crud');
        $this->setViewData('settings', $settings);
    }

    public function postReplytypes()
    {
        $this->skipView();
        // Set the input data
        $input = e_array(Input::all());

        if ($input != null) {
            // Get the object
            $replyType          = (isset($input['id']) && $input['id'] != null ? Forum_Reply_Type::find($input['id']) : new Forum_Reply_Type);
            $replyType->name    = $input['name'];
            $replyType->keyName = $input['keyName'];

            // Attempt to save the object
            $this->save($replyType);

            // Handle errors
            if ($this->errorCount() > 0) {
                $this->ajaxResponse->addErrors($this->getErrors());
            } else {
               $this->ajaxResponse->setStatus('success')->addData('resource', $replyType->toArray());
            }

            // Send the response
            return $this->ajaxResponse->sendResponse();
        }
    }

    public function getReplytypedelete($replyTypeId)
    {
        $this->skipView();

        $replyType = Forum_Reply_Type::find($replyTypeId);
        $replyType->delete();

        return Redirect::to('/admin#replytypes');
    }
}

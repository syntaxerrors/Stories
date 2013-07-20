<?php

class Admin_CrudController extends BaseController {

    public function getSetErsatz()
    {
        $ersatzClient = new Ersatz(null);

        // Set all the characters up
        $characters = Character::all();
        foreach ($characters as $character) {
            $character->sendErsatzTo($ersatzClient);
        }

        // Handle any chats
        $chatRooms = Chat_Room::all();

        foreach ($chatRooms as $chatRoom) {
            $chats = Chat::where('chat_room_id', '=', $chatRoom->id)->orderBy('created_at', 'desc')->take(30)->get();
            if (count($chats) > 0) {
                foreach ($chats as $chat) {
                    $chat->sendErsatzTo($ersatzClient);
                }
            }
        }
        $ersatzClient->flush();

        return Redirect::back();
    }

    public function getUsers()
    {
        $users = User::orderBy('username', 'asc')->get();

        // Set up the one page crud
        $settings                 = new stdClass();
        $settings->title          = 'Users';
        $settings->sort           = 'username';
        $settings->deleteLink     = '/admin/crud/userDelete/';
        $settings->deleteProperty = 'uniqueId';
        $settings->buttons        = array
        (
            'resetPassword' => HTML::link('/admin/crud/resetPassword/--uniqueId--', 'Reset Password', array('class' => 'confirm-continue btn btn-mini btn-primary'))
        );
        $settings->displayFields  = array
        (
            'username'    => array('link' => '/profile/user/', 'linkProperty' => 'uniqueId'),
            'fullname'    => array(),
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

            if (count($user->errors->all()) > 0){
                return implode('<br />', $user->errors->all());
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

    public function getPermissions()
    {
        $permissions = Permission::orderBy('name', 'asc')->get();

        // Set up the one page crud
        $settings                 = new stdClass();
        $settings->title          = 'Permissions';
        $settings->sort           = 'name';
        $settings->deleteLink     = '/admin/crud/permissionDelete/';
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
        $this->setViewData('resources', $permissions);
        $this->setViewData('settings', $settings);
    }

    public function postPermissions()
    {
        // Set the input data
        $input = Input::all();

        if ($input != null) {
            // Get the object
            $permission              = (isset($input['id']) && $input['id'] != null ? Permission::find($input['id']) : new Permission);
            $permission->name        = $input['name'];
            $permission->keyName     = $input['keyName'];
            $permission->description = $input['description'];

            $permission->save();

            if (count($permission->errors->all()) > 0){
                return implode('<br />', $permission->errors->all());
            } else {
                return json_encode($permission->attributes);
            }
        }
    }

    public function getPermissiondelete($permissionId)
    {
        $permission = Permission::find($permissionId);
        $permission->delete();

        return Redirect::to('/admin#Permissions');
    }

    public function getRoles()
    {
        $roles = Role::orderBy('name', 'asc')->get();

        // Set up the one page crud
        $settings                 = new stdClass();
        $settings->title          = 'Roles';
        $settings->sort           = 'groupValue';
        $settings->deleteLink     = '/admin/crud/roleDelete/';
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
            $role              = (isset($input['id']) && $input['id'] != null ? Role::find($input['id']) : new Role);
            $role->group       = $input['group'];
            $role->name        = $input['name'];
            $role->keyName     = $input['keyName'];
            $role->value       = $input['value'];
            $role->description = $input['description'];

            $role->save();

            $role->attributes['groupValue'] = $role->groupValue;

            if (count($role->errors->all()) > 0){
                return implode('<br />', $role->errors->all());
            } else {
                return json_encode($role->attributes);
            }
        }
    }

    public function getRoledelete($roleId)
    {
        $role = Role::find($roleId);
        $role->delete();

        return Redirect::to('/admin#Roles');
    }

    public function getMemberships()
    {
        $memberships = Membership::with(array('User', 'Role'))->orderBy('user_id', 'asc')->orderBy('role_id', 'asc')->get();

        // Set up the one page crud
        $settings                 = new stdClass();
        $settings->title          = 'Memberships';
        $settings->sort           = 'username';
        $settings->deleteLink     = '/admin/crud/membershipDelete/';
        $settings->deleteProperty = 'id';
        $settings->displayFields  = array
        (
            'username'  => array(),
            'role_name' => array(),
        );
        $settings->formFields     = array
        (
            'user_id' => array('field' => 'select', 'selectArray' => $this->arrayToSelect(User::orderBy('username', 'asc')->get(), 'id', 'username', 'Select a user')),
            'role_id' => array('field' => 'select', 'selectArray' => $this->arrayToSelect(Role::orderBy('group', 'asc')->orderBy('value', 'asc')->get(), 'id', 'fullName', 'Select a role')),
        );

        $this->setViewPath('helpers.crud');
        $this->setViewData('resources', $memberships);
        $this->setViewData('settings', $settings);
    }

    public function postMemberships()
    {
        // Set the input data
        $input = Input::all();

        if ($input != null) {
            // Get the object
            $membership          = (isset($input['id']) && $input['id'] != null ? Membership::find($input['id']) : new Membership);
            $membership->user_id = $input['user_id'];
            $membership->role_id = $input['role_id'];

            $membership->save();

            $membership->attributes['username']  = $membership->username;
            $membership->attributes['role_name'] = $membership->role_name;

            if (count($membership->errors->all()) > 0){
                return implode('<br />', $membership->errors->all());
            } else {
                if (count($membership->role->users) > 0) {
                    foreach($membership->role->users as $user) {
                        $message                  = new Message;
                        $message->sender_id       = $this->activeUser->id;
                        $message->receiver_id     = $user->user_id;
                        $message->message_type_id = Message::PERMISSION;
                        $message->title           = 'You have been assigned new roles.';
                        $message->content         = 'Please click the "Update Permissions" button to get access to your new areas.';
                        $message->readFlag        = 0;
                        $message->save();
                    }
                }
                return json_encode($membership->attributes);
            }
        }
    }

    public function getMembershipdelete($membershipId)
    {
        $membership = Membership::find($membershipId);
        $membership->delete();

        if (count($membership->role->users) > 0) {
            foreach($membership->role->users as $user) {
                $message                  = new Message;
                $message->sender_id       = $this->activeUser->id;
                $message->receiver_id     = $user->user_id;
                $message->message_type_id = Message::PERMISSION;
                $message->title           = 'You have been assigned new permissions.';
                $message->content         = 'Please click the "Update Permissions" button to get access to your new areas.';
                $message->readFlag        = 0;
                $message->save();
            }
        }

        return Redirect::to('/admin#Memberships');
    }

    public function getRules()
    {
        $rules = Rule::orderBy('role_id', 'asc')->orderBy('permission_id', 'asc')->get();

        // Set up the one page crud
        $settings                 = new stdClass();
        $settings->title          = 'Rules';
        $settings->sort           = 'permission_name';
        $settings->deleteLink     = '/admin/crud/ruleDelete/';
        $settings->deleteProperty = 'id';
        $settings->displayFields  = array
        (
            'permission_name' => array(),
            'role_name'       => array(),
        );
        $settings->formFields     = array
        (
            'permission_id' => array('field' => 'select', 'selectArray' => $this->arrayToSelect(Permission::orderBy('name', 'asc')->get(), 'id', 'name', 'Select a permission')),
            'role_id'       => array('field' => 'select', 'selectArray' => $this->arrayToSelect(Role::orderBy('group', 'asc')->orderBy('value', 'asc')->get(), 'id', 'fullName', 'Select a role')),
        );

        $this->setViewPath('helpers.crud');
        $this->setViewData('resources', $rules);
        $this->setViewData('settings', $settings);
    }

    public function postRules()
    {
        // Set the input data
        $input = Input::all();

        if ($input != null) {
            // Get the object
            $rule                = (isset($input['id']) && $input['id'] != null ? Rule::find($input['id']) : new Rule);
            $rule->permission_id = $input['permission_id'];
            $rule->role_id       = $input['role_id'];

            $rule->save();

            $rule->attributes['permission_name'] = $rule->permission_name;
            $rule->attributes['role_name']       = $rule->role_name;

            if (count($rule->errors->all()) > 0){
                return implode('<br />', $rule->errors->all());
            } else {
                if (count($rule->role->users) > 0) {
                    foreach($rule->role->users as $user) {
                        $message                  = new Message;
                        $message->sender_id       = $this->activeUser->id;
                        $message->receiver_id     = $user->user_id;
                        $message->message_type_id = Message::PERMISSION;
                        $message->title           = 'You have been assigned new permissions.';
                        $message->content         = 'Please click the "Update Permissions" button to get access to your new areas.';
                        $message->readFlag        = 0;
                        $message->save();
                    }
                }
                return json_encode($rule->attributes);
            }
        }
    }

    public function getRuledelete($ruleId)
    {
        $rule = Rule::find($ruleId);
        $rule->delete();

        if (count($rule->role->users) > 0) {
            foreach($rule->role->users as $user) {
                $message                  = new Message;
                $message->sender_id       = $this->activeUser->id;
                $message->receiver_id     = $user->user_id;
                $message->message_type_id = Message::PERMISSION;
                $message->title           = 'You have been assigned new permissions.';
                $message->content         = 'Please click the "Update Permissions" button to get access to your new areas.';
                $message->readFlag        = 0;
                $message->save();
            }
        }

        return Redirect::to('/admin#Rules');
    }

}
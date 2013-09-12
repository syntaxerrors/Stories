<?php

class UserController extends BaseController {

    public function getIndex()
    {

    }

    public function getView($userId = null)
    {
        if ($userId == null) {
            $this->redirect('/');
        }

        $user = User::find($userId);

        $this->setViewData('user', $user);
    }

    public function postProfile()
    {
        $input = e_array(Input::all());

        if ($input != null) {
            // Create the object
            $user              = User::find($this->activeUser->id);
            $user->displayName = $input['displayName'];
            $user->firstName   = $input['firstName'];
            $user->lastName    = $input['lastName'];
            $user->email       = $input['email'];
            $user->location    = $input['location'];
            $user->url         = $input['url'];

            // Attempt to save the object
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

    public function postPassword()
    {
        $input = e_array(Input::all());

        if ($input != null) {

            $user = User::find($this->activeUser->id);

            if ($input['newPassword'] != $input['newPasswordAgain']) {
                $this->ajaxResponse->addError('newPassword', 'Your new passwords did not match.');
            }

            if (!Hash::check($input['oldPassword'], $user->password)) {
                $this->ajaxResponse->addError('oldPassword', 'Please enter your current password.');
            }

            if ($this->ajaxResponse->errorCount() > 0) {
                return $this->ajaxResponse->sendResponse();
            }

            $user->password = $input['newPassword'];
            $this->save($user);

            if ($this->errorCount() > 0) {
                $this->ajaxResponse->addErrors($this->getErrors());
            } else {
               $this->ajaxResponse->setStatus('success');
            }

            return $this->ajaxResponse->sendResponse();
        }
    }

    public function getRules()
    {
        $messageTypes = Message_Type::orderByNameAsc()->get();
        $users        = User::orderBy('username', 'asc')->get();
        $inbox        = Message_Folder::find($this->activeUser->inbox);
        $folders      = Message_Folder::where('uniqueId', '!=', $this->activeUser->inbox)->where('user_id', $this->activeUser->id)->orderByNameAsc()->get();

        $this->setViewData('messageTypes', $messageTypes);
        $this->setViewData('users', $users);
        $this->setViewData('inbox', $inbox);
        $this->setViewData('folders', $folders);
    }

    public function getPreferences()
    {
        ppd($this->activeUser->preferences);
    }

    public function postPreferences()
    {
        
    }

    public function postAvatar()
    {
        // if image upload then move to temp spot and send back temp image id
        // 
        ppd(Input::file('file'));
    }

    public function getCropAvatar($tempImageId)
    {
        // set image path. verify it is an image.
    }

    public function postCropAvatar()
    {
        // take new croped image and save it to the public dir
        // return to orignal settings page
    }
}
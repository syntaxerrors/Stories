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
        $this->skipView();

        $avatar = Input::file('file');

        // ppd($avatar);
        if ($avatar != null) {
            $mime = $avatar->getMimeType();
            $mime = explode('/', $mime);
            $extension = $mime[1];

            $imageName = Str::studly($this->activeUser->username) .'.'. $extension;

            $avatar->move('img/avatars', $imageName);

            // Convert to PNG
            $newImage = Image::make('img/avatars/'. $imageName);
            $newImage->save('img/avatars/'. Str::studly($this->activeUser->username) .'.jpg', 90);

            File::delete('img/avatars/'. $imageName);

            return 'Avatar uploaded!';
        }

        return 'Please select an image to upload';
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

    public function getTheme()
    {
        $masterLess = public_path() .'/css/master_css.less';
        $userLess   = public_path() .'/css/users/'. Str::studly($this->activeUser->username) .'_css.less';

        // Make a copy of the less file
        if (!File::exists($userLess)) {
            File::copy($masterLess, $userLess);
        }

        $lines = file($userLess);

        $colors = array();

        $colors['grey']    = array('title' => 'Background Color',          'hex' => substr(explode('@grey: ',            $lines[4])[1],  0, -2));
        $colors['primary'] = array('title' => 'Primary Color',             'hex' => substr(explode('@primaryColor: ',    $lines[6])[1],  0, -2));
        $colors['info']    = array('title' => 'Information Color',         'hex' => substr(explode('@infoColor: ',       $lines[9])[1],  0, -2));
        $colors['success'] = array('title' => 'Success Color',             'hex' => substr(explode('@successColor: ',    $lines[12])[1], 0, -2));
        $colors['warning'] = array('title' => 'Warning Color',             'hex' => substr(explode('@warningColor: ',    $lines[15])[1], 0, -2));
        $colors['error']   = array('title' => 'Error Color',               'hex' => substr(explode('@errorColor: ',      $lines[18])[1], 0, -2));
        $colors['menu']    = array('title' => 'Active Menu Link Color',    'hex' => substr(explode('@menuColor: ',       $lines[21])[1], 0, -2));

        $this->setViewData('colors', $colors);
    }

    public function postTheme()
    {
        $input = e_array(Input::all());

        if ($input != null) {
            $userLess = public_path() .'/css/users/'. Str::studly($this->activeUser->username) .'_css.less';
            $userCss  = public_path() .'/css/users/'. Str::studly($this->activeUser->username) .'.css';

            $lines = file($userLess);

            // Set the new colors
            $lines[4]  = '@grey: '. $input['grey'] .";\n";
            $lines[6]  = '@primaryColor: '. $input['primary'] .";\n";
            $lines[9]  = '@infoColor: '. $input['info'] .";\n";
            $lines[12] = '@successColor: '. $input['success'] .";\n";
            $lines[15] = '@warningColor: '. $input['warning'] .";\n";
            $lines[18] = '@errorColor: '. $input['error'] .";\n";
            $lines[21] = '@menuColor: '. $input['menu'] .";\n";

            File::delete($userLess);
            File::delete($userCss);

            File::put($userLess, implode($lines));

            $less = new lessc;
            $less->compileFile($userLess, $userCss);

            ppd(File::get($userLess));
        }
    }
}
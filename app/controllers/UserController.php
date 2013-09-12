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

        // Demonic Pagan
        // $characterId   = 401681;
        // Lloire Peace
        $characterIds = array(140413, 144839);
        $characters   = array();
        // Stygian Cogitatio
        // $characterId   = 144839;

        // FFXIV api
        foreach ($characterIds as $characterId) {
            if (!Cache::has($characterId)) {
                $api = new Lodestone_API();
                $api->parseProfile($characterId);
                $character = $api->getCharacterByID($characterId);

                Cache::put($characterId, $character, 60);
            } else {
                $character = Cache::get($characterId);
            }

            $possibleGear = array('head', 'body', 'hands', 'waist', 'legs', 'feet', 'shield', 'necklace', 'earrings', 'bracelets', 'ring', 'ring2', 'soul crystal');
            $equippedGear = $character->getEquipped('slots');
            $character->fullGear = array();

            foreach ($possibleGear as $gear) {
                if (isset($equippedGear[$gear])) {
                    $character->fullGear[$gear] = HTML::image($equippedGear[$gear]['icon'], null, array('title' => $equippedGear[$gear]['name'], 'style' => 'width: 40px;', 'class' => 'img-rounded'));
                } else {
                    $character->fullGear[$gear] = HTML::image('img/ffxiv/'. strtolower(str_replace('2', '', str_replace(' ', '_', $gear))) .'.png');
                }
            }

            $characters[] = $character;
        }

        $this->setViewData('characters', $characters);
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
}
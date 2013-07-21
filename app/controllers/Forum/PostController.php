<?php

class Forum_PostController extends BaseController {

    public function getView($postId)
    {
        // Get the post
        $post = Forum_Post::find($postId);

        // If the board is GM only, make sure they are a GM
        if ($post->board->forum_board_type_id == Forum_Board::TYPE_GM && !$this->hasPermission('GAME_MASTER')) {
            $this->redirect('/', 'You must be a game master to access posts in this board.', true);
        }

        // Get the replies and reply types
        $replies    = Forum_Reply::where('forum_post_id', $post->id)->orderByCreatedAsc()->paginate(30);
        $replyTypes = Forum_Reply_Type::orderByNameAsc()->get();
        $types      = $this->arrayToSelect($replyTypes , 'id', 'name', 'Select Reply Type');

        // If it is a support post, get the status array
        if ($post->board->category->forum_category_type_id == Forum_Category::TYPE_SUPPORT && $post->forum_post_type_id != Forum_Post::TYPE_ANNOUNCEMENT) {
            $statuses = Forum_Support_Status::where('id', '!=', $post->status->id)->get();
            $statuses = $this->arrayToSelect($statuses, 'id', 'name', 'Select a Status');
        } else {
            $statuses = array();
        }

        if ($post->board->category->type->keyName == 'game') {
            // Get all this user's characters they can post as
            $characters = Character::where('user_id', $this->activeUser->id)->orderByNameAsc()->get();
            $characters = $this->arrayToSelect($characters, 'id', 'name', 'Select Character');

            // If this is an RP board, set a primary character to auto post as
            if ($post->board->forum_board_type_id == Forum_Board::TYPE_ROLEPLAYING) {
                $primaryCharacter = Character::where('user_id', $this->activeUser->id)->npc(0)->creature(0)->orderByNameAsc()->first();
            } else {
                $primaryCharacter     = new Character;
                $primaryCharacter->id = 0;
            }
        } else {
            $characters = array();
            $primaryCharacter = null;
        }

        if ($this->hasPermission('FORUM_POST')) {
            // Add quick link to post
            $this->addSubMenu('Add Post','forum/post/add/'. $post->board->id);
        }

        // Handle viewing
        $post->incrementViews();
        $post->userViewed($this->activeUser->id);

        // Get any attachments
        $directory   = public_path() .'/img/forum/attachments/'. $postId .'/';
        $attachments = glob($directory . '*');

        // Set the template
        $this->setViewData('post', $post);
        $this->setViewData('replies', $replies);
        $this->setViewData('types', $types);
        $this->setViewData('characters', $characters);
        $this->setViewData('primaryCharacter', $primaryCharacter);
        $this->setViewData('statuses', $statuses);
        $this->setViewData('attachments', $attachments);
    }

    public function postView($postId)
    {
        // Handle form input
        $input = e_array(Input::all());

        if ($input != null) {
            $post = Forum_Post::find($postId);

            // Handle the attachment
            if (isset($input['image']) && $input['image']['name'] != null) {

                // Upload the image
                Input::upload('image', public_path() .'/img/forum/attachments/'. $postId, $input['image']['name']);

                // Add the upload to edit history
                $post->setAttachmentEdit($input['image']['name']);

            }
            if (isset($input['report_resource_id']) && $input['report_resource_id'] != null) {

                // Get the correct resource
                if ($input['report_resource_name'] == 'post') {
                    $resource = Forum_Post::find($input['report_resource_id']);
                } else {
                    $resource = Forum_Reply::find($input['report_resource_id']);
                }

                // Create the moderator record and lock the resource
                $resource->setModeration($input['reason']);

                return Redirect::to('forum/post/view/'. $postId)->with('message', 'Your report has been submitted to our moderators.');

            } elseif (isset($input['exp_resource_id']) && $input['exp_resource_id'] != null) {

                // Get the resource record and set up the redirect link
                switch ($input['exp_resource_name']) {
                    case 'post':
                        $resource = Forum_Post::find($input['exp_resource_id']);
                        $link     = HTML::link('forum/post/view/'. $postId, 'here');
                    break;
                    case 'reply':
                        $resource = Forum_Reply::find($input['exp_resource_id']);
                        $link     = HTML::link('forum/post/view/'. $postId .'#reply:'. $resource->id, 'here');
                    break;
                }

                // Get the character and add the experience
                $character = $resource->character;
                if (isset($input['exp'])) {
                    $character->addExperience($input['exp'], $this->activeUser->id, $input['exp_resource_name'], $link, $resource->id);
                }

                return Redirect::to('forum/post/view/'. $postId)->with('message', $character->name .' has been granted '. $input['exp'] .' experience points.');

            } elseif (isset($input['content']) && $input['content'] != null) {

                $message = e($input['content']);

                // We are adding a reply
                $reply                      = new Forum_Reply;
                $reply->forum_post_id       = $post->id;
                $reply->forum_reply_type_id = ($input['forum_reply_type_id'] == 9999 ? Forum_Reply::TYPE_ACTION : $input['forum_reply_type_id']);
                $reply->user_id             = $this->activeUser->id;
                $reply->character_id        = (isset($input['character_id']) && $input['character_id'] != 0 ? $input['character_id'] : null);
                $reply->name                = (isset($input['name']) && $input['name'] != null ? $input['name'] : 'Re: '. $post->name);
                $reply->keyName             = Str::slug($reply->name);
                $reply->content             = $message;
                $reply->quote_id            = (isset($input['quote_id']) && $input['quote_id'] != 0 ? $input['quote_id'] : null);
                $reply->moderatorLockedFlag = 0;
                $reply->approvedFlag        = ($input['forum_reply_type_id'] == 9999 ? 1 : 0);

                $reply->save();

                $reply->post->modified_at = date('Y-m-d H:i:s');
                $reply->post->save();

                $this->checkErrorsRedirect($reply);

                // Remove all user views so the post shows as updated
                $post->deleteViews();

                // See if we need to roll
                if ($reply->forum_reply_type_id == Forum_Reply::TYPE_ACTION || $reply->forum_reply_type_id == 9999){
                    $roll                      = $this->roll();
                    $replyRoll                 = new Forum_Reply_Roll;
                    $replyRoll->forum_reply_id = $reply->id;
                    $replyRoll->die            = 100;
                    $replyRoll->roll           = ($reply->forum_reply_type_id == 9999 ? 9999 : $roll);

                    $replyRoll->save();
                }

                // See if we are updating the status
                if (isset($input['forum_support_status_id']) && $input['forum_support_status_id'] != 0) {
                    $status                          = Forum_Post_Status::where('forum_post_id', '=', $post->id)->first();
                    $status->forum_support_status_id = $input['forum_support_status_id'];
                    $status->save();
                }
                return Redirect::to('forum/post/view/'. $postId .'#reply:'. $reply->id);
            }
        }
    }

    public function roll1()
    {
        $roll = rand(1,100);
        $overallRoll = $roll;
        $class = 'text-success';
        while ($roll >= 90) {
            $roll = rand(1,100);
            $overallRoll = $overallRoll + $roll;
            $class = 'text-warning';
        }

        if ($overallRoll == 9999) {
            $overallRoll = 10000;
        }

        return '[dice][spanClass='. $class .']'. $overallRoll .'[/spanClass]';
    }

    public function rollGm()
    {
        if (!$this->game->isStoryteller($this->activeUser->id)) {
            return $this->roll() .'Gm';
        }
        $roll = rand(1,100);
        $overallRoll = $roll;
        $class = 'text-success';
        while ($roll <= 80) {
            $roll = rand(1,100);
            $overallRoll = $overallRoll + $roll;
            $class = 'text-warning';
        }

        if ($overallRoll == 9999) {
            $overallRoll = 10000;
        }

        return '[dice][spanClass='. $class .']'. $overallRoll .'[/spanClass]';
    }

    public function roll2()
    {
        $roll = rand(91,150);
        $overallRoll = $roll;
        $class = 'text-warning';

        return '[dice][spanClass='. $class .']'. $overallRoll .'[/spanClass]';
    }

    public function roll()
    {
        $roll = rand(1,100);
        $overallRoll = $roll;
        while ($roll >= 90) {
            $roll = rand(1,100);
            $overallRoll = $overallRoll + $roll;
        }

        if ($overallRoll == 9999) {
            $overallRoll = 10000;
        }

        return $overallRoll;
    }

    public function getEditpost($postId)
    {
        // Make sure they can access this whole area
        $this->checkPermission('FORUM_POST');

        // Get the information
        $post       = Forum_Post::where('uniqueId', '=', $postId)->first();

        // Verify the user
        if (!$this->activeUser->checkPermission(array('DEVELOPER', 'FORUM_MOD', 'FORUM_ADMIN'))) {
            if ($post->user_id != $this->activeUser->id) {
                $this->redirect('forum/post/'. $postId, 'You must be a moderator or the post author to edit a post.');
            }
        }
        $types      = $this->arrayToSelect(Forum_Post_Type::orderByNameAsc()->get(), 'id', 'name', 'Select Post Type');

        if ($post->board->category->type->keyName == 'game') {
            $characters = $this->arrayToSelect(Character::where('user_id', '=', $post->user_id)->orderByNameAsc()->get(), 'id', 'name', 'Select Character');
        } else {
            $characters = array();
        }

        // Set the template
        $this->setViewData('types', $types);
        $this->setViewData('characters', $characters);
        $this->setViewData('post', $post);
    }

    public function postEditpost($postId)
    {
        // Handle any form data
        $input = e_array(Input::all());

        if ($input != null) {
            $post                     = Forum_Post::find($postId);
            $post->forum_post_type_id = (isset($input['forum_post_type_id']) && $input['forum_post_type_id'] != 0 ? $input['forum_post_type_id'] : null);
            $post->character_id       = (isset($input['character_id']) && $input['character_id'] != 0 ? $input['character_id'] : $post->character_id);
            $post->name               = $input['name'];
            $post->keyName            = Str::slug($input['name']);
            $post->content            = $input['content'];

            $post->save();

            $edit                = new Forum_Post_Edit;
            $edit->forum_post_id = $post->id;
            $edit->user_id       = $this->activeUser->id;
            $edit->reason        = (isset($input['reason']) && $input['reason'] != null ? $input['reason'] : null);

            $edit->save();

            if (count($post->getErrors()->all()) > 0){
                return Redirect::to(Request::path())->with('errors', $post->getErrors()->all());
            } else {
                return Redirect::to('forum/post/view/'. $post->uniqueId)->with('message', $post->name.' has been submitted.');
            }
        }
    }

    public function getEditreply($replyId)
    {
        // Make sure they can access this whole area
        $this->checkPermission('FORUM_POST');

        // Get the information
        $reply      = Forum_Reply::find($replyId);

        // Verify the user
        if (!$this->activeUser->checkPermission(array('DEVELOPER', 'FORUM_MOD', 'FORUM_ADMIN'))) {
            if ($reply->user_id != $this->activeUser->id) {
                $this->redirect('forum/post/'. $reply->post->uniqueId, 'You must be a moderator or the post author to edit a post.');
            }
        }
        $types      = $this->arrayToSelect(Forum_Reply_Type::orderByNameAsc()->get(), 'id', 'name', 'Select Post Type');

        if ($reply->post->board->category->type->keyName == 'game') {
            $characters = Character::where('user_id', $reply->user_id)->where('game_id', $reply->post->board->category->game_id)->orderByNameAsc()->get();
            $characters = $this->arrayToSelect($characters, 'id', 'name', 'Select Character');
        } else {
            $characters = array();
        }

        if ($reply->post->board->category->game != null) {
            if ($reply->post->board->category->game->isStoryteller($activeUser->id)) {
                $types[9999] = 'Action without Roll';
            }
        }

        // Set the template
        $this->setViewData('types', $types);
        $this->setViewData('characters', $characters);
        $this->setViewData('reply', $reply);
    }

    public function postEditreply($replyId)
    {
        // Handle any form data
        $input = e_array(Input::all());

        if ($input != null) {
            $reply                      = Forum_Reply::find($replyId);
            $reply->forum_reply_type_id = (isset($input['forum_reply_type_id']) && $input['forum_reply_type_id'] != 0 ? $input['forum_reply_type_id'] : null);
            $reply->character_id        = (isset($input['character_id']) && $input['character_id'] != 0 ? $input['character_id'] : null);
            $reply->name                = $input['name'];
            $reply->keyName             = Str::slug($input['name']);
            $reply->content             = $input['content'];

            $reply->save();

            $edit                 = new Forum_Reply_Edit;
            $edit->forum_reply_id = $reply->id;
            $edit->user_id        = $this->activeUser->id;
            $edit->reason         = (isset($input['reason']) && $input['reason'] != null ? $input['reason'] : null);

            $edit->save();

            if (count($reply->getErrors()->all()) > 0){
                return Redirect::to(Request::path())->with('errors', $reply->getErrors()->all());
            } else {
                // See if we need to roll
                if ($reply->forum_reply_type_id == Forum_Reply::TYPE_ACTION || $reply->forum_reply_type_id == 9999){
                    $oldRoll = Forum_Reply_Roll::where('forum_reply_id', '=', $reply->id)->first();
                    // If this was originally a normal roll and has become an ST roll, change it
                    if ($oldRoll->roll != 9999 && $reply->forum_reply_type_id == 9999) {
                        $oldRoll->roll = 9999;
                        $oldRoll->save();
                    } elseif ($oldRoll == null) {
                        // If no roll exists, we add one
                        $roll                      = $this->roll();
                        $replyRoll                 = new Forum_Reply_Roll;
                        $replyRoll->forum_reply_id = $reply->id;
                        $replyRoll->die            = 100;
                        $replyRoll->roll           = ($reply->forum_reply_type_id == 9999 ? 9999 : $roll);

                        $replyRoll->save();
                    }
                }
                return Redirect::to('forum/post/view/'. $reply->post->uniqueId .'#reply:'. $reply->id)->with('message', $reply->name.' has been submitted.');
            }
        }
    }

    public function getModify($id, $property, $value, $type = 'post')
    {
        switch ($type) {
            case 'post':
                $resource = Forum_Post::find($id);
            break;
            case 'reply':
                $resource = Forum_Reply::find($id);
            break;
        }
        $resource->{$property} = $value;
        $resource->save();

        // Send mail if approving
        if ($resource->type->keyName == 'application' && $property == 'approvedFlag') {
            $message                  = new Message;
            $message->message_type_id = Message::CHARACTER_APPROVAL;
            $message->sender_id       = $this->activeUser->id;
            $message->receiver_id     = $resource->user_id;
            $message->title           = 'Your application has been approved!';
            $message->content         = $resource->character->name .' has been approved to play in '. $resource->character->game->name .'.<br /><br />We look forward to seeing you in game!';
            $message->readFlag        = 0;
            $message->save();
        } elseif ($resource->type->keyName == 'action' && $property == 'approvedFlag') {
            $message                  = new Message;
            $message->message_type_id = Message::ACTION_APPROVAL;
            $message->sender_id       = $this->activeUser->id;
            $message->receiver_id     = $resource->user_id;
            $message->title           = 'Your action post has been approved!';
            $message->content         = 'Your action post has been approved.<br /><br />Click '. HTML::link('forum/post/view/'. $resource->post->uniqueId .'#reply:'. $resource->id, 'here') .' to view your post.';
            $message->readFlag        = 0;
            $message->save();
        }

        $this->skipView = true;
        return Redirect::back()->with('message', $resource->name.' has been modified.');
    }

    public function getAdd($boardId = null)
    {
        // Make sure they can access this
        $this->checkPermission('FORUM_POST');

        // Get the information
        $board      = Forum_Board::where('uniqueId', $boardId)->first();
        $types      = $this->arrayToSelect(Forum_Post_Type::orderByNameAsc()->get(), 'id', 'name', 'Select Post Type');

        if ($board->category->type->keyName == 'game') {
            $characters = $this->arrayToSelect(Character::where('user_id', $this->activeUser->id)->orderByNameAsc()->get(), 'id', 'name', 'Select Character');
            if ($board->forum_board_type_id == Forum_Board::TYPE_ROLEPLAYING) {
                $primaryCharacter = Character::where('user_id', $this->activeUser->id)->where('npcFlag', 0)->where('creatureFlag', 0)->orderByNameAsc()->first()->id;
            } else {
                $primaryCharacter = new Character;
                $primaryCharacter->id = 0;
            }
        } else {
            $characters = array();
            $primaryCharacter = null;
        }

        // Set the template
        $this->setViewData('types', $types);
        $this->setViewData('characters', $characters);
        $this->setViewData('primaryCharacter', $primaryCharacter);
        $this->setViewData('board', $board);
    }

    public function postAdd($boardId)
    {
        // Handle any form data
        $input = e_array(Input::all());

        if ($input != null) {
            $board      = Forum_Board::where('uniqueId', '=', $boardId)->first();
            $message = e($input['content']);

            $post                      = new Forum_Post;
            $post->forum_board_id      = $board->id;
            $post->forum_post_type_id  = (isset($input['forum_post_type_id']) && $input['forum_post_type_id'] != 0 ? $input['forum_post_type_id'] : null);
            $post->user_id             = $this->activeUser->id;
            $post->character_id        = (isset($input['character_id']) && $input['character_id'] != 0 ? $input['character_id'] : null);
            $post->name                = $input['name'];
            $post->keyName             = Str::slug($input['name']);
            $post->content             = $message;
            $post->moderatorLockedFlag = 0;
            $post->approvedFlag        = 0;
            $post->modified_at         = date('Y-m-d H:i:s');

            $post->save();

            $this->checkErrorsRedirect($post);

            // Set this user as already having viewed the post
            $post->userViewed($this->activeUser->id);

            // Set status if a support post
            if ($post->board->category->forum_category_type_id == Forum_Category::TYPE_SUPPORT) {
                $status = new Forum_Post_Status(array('forum_support_status_id' => Forum_Support_Status::TYPE_OPEN));

                $post->status()->save($status);
                pp($this->checkErrors($post));
                ppd($post->status);
            }

            return Redirect::to('forum/post/view/'. $post->id)->with('message', $post->name.' has been submitted.');
        }
    }

    public function postUpdate($resourceId, $property, $value, $type = 'post')
    {
        $this->skipView = true;
        switch ($type) {
            case 'post':
                $resource = Forum_Post::find($resourceId);
            break;
            case 'status':
                $resource = Forum_Post_Status::where('forum_post_id', $resourceId)->first();
                $property = 'forum_support_status_id';
            break;
        }
        $resource->{$property} = $value;
        $resource->save();
    }


    public function getDelete($resourceId, $type = 'post', $attachment = null)
    {
        // Don't load a page
        $this->skipView = true;

        // Make sure they can access this
        $this->checkPermission('FORUM_POST');

        if ($type == 'attachment') {
            $attachment = str_replace('%7C', '/', $attachment);
            File::delete($attachment);

            return Redirect::to('forum/post/view/'. $resourceId)->with('message', 'Attachment deleted.');

        } elseif ($type == 'post') {
            $post    = Forum_Post::find($resourceId);

            // Verify the user
            if (!$this->activeUser->checkPermission(array('DEVELOPER', 'FORUM_MOD', 'FORUM_ADMIN'))) {
                if ($post->user_id != $this->activeUser->id) {
                    $this->authFailed('forum/post/'. $resourceId, 'You must be a moderator or the post author to delete a post.');
                }
            }

            // Delete the post
            $post->delete();

            return Redirect::to('forum/board/view/'. $post->board->id)->with('message', 'Post '. $post->name.' has been deleted.');
        } else {
            $reply = Forum_Reply::find($resourceId);

            // Verify the user
            if (!$this->activeUser->checkPermission(array('DEVELOPER', 'FORUM_MOD', 'FORUM_ADMIN'))) {
                if ($reply->user_id != $this->activeUser->id) {
                    $this->redirect('forum/post/view/'. $reply->post->id .'#reply:'. $reply->id, 'You must be a moderator or the post author to delete a post.');
                }
            }

            // Delete the reply
            $reply->delete();

            return Redirect::to('forum/post/view/'. $reply->post->id)->with('message', 'Reply '. $reply->name.' has been deleted.');
        }
    }
}
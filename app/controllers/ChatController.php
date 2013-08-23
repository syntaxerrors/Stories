<?php

class ChatController extends BaseController {

	public $game;

	public $type = 'chat';

	public function getIndex()
	{
		$chatRooms         = Chat_Room::active()->orderByNameAsc()->get();
		$inactiveChatRooms = Chat_Room::inactive()->orderByNameAsc()->get();

		$this->setViewData('chatRooms', $chatRooms);
		$this->setViewData('inactiveChatRooms', $inactiveChatRooms);
	}

	public function getAdd()
	{
		// Check permission to create a chat room
		$this->checkPermission('CHAT_CREATE');

		// Get the information
		// $games     = $this->arrayToSelect(Game::orderByNameAsc()->get(), 'id', 'name', 'Select a Game');
		$games = $this->arrayToSelect(array(), 'id', 'name', 'Select a Game');

		$this->setViewData('games', $games);
	}

	public function postAdd()
	{
		// Handle the form input
		$input = e_array(Input::all());

		if ($input != null) {
			$room                   = new Chat_Room;
			$room->user_id          = $this->activeUser->id;
			$room->game_id          = ($input['game_id'] != 0 ? $input['game_id'] : null);
			$room->name             = $input['name'];
			$room->activeFlag       = (isset($input['activeFlag']) ? 1 : 0);

			$this->checkErrorsSave($room);

			return $this->redirect('chat', $room->name .' has been created.');
		}
	}

	public function getRoom($chatRoomId = null, $message = null, $characterId = null)
	{
		if ($chatRoomId == null || Chat_room::where('uniqueId', $chatRoomId)->first() == null) {
			return Redirect::back()->with('errors', array('The requested chat room does not exist.'));
		}
		// Get the chat room
        $chatRoom = Chat_room::where('uniqueId', $chatRoomId)->first();
        if ($chatRoom->activeFlag == 0 && $this->activeUser->id != $chatRoom->user_id && !$this->hasPermission('DEVELOPER')) {
        	return Redirect::back()->with('errors', array('The requested chat room is not active at this time.'));
        }

        // Get the data
        $chats = Chat::where('chat_room_id', $chatRoomId)->orderBy('created_at', 'desc')->take(30)->get();
        $lastChatTimes = $chatRoom->chats->created_at->toArray();

        // Get the popover data
		$attributes  = array();
		$secondaries = array();
		$skills      = array();
		$spells      = array();
        if ($chatRoom->game_id != null) {
        	$template = Game\Template::with('gameAttributes')->find($chatRoom->game->game_template_id);
        	foreach ($template->gameAttributes as $key => $attribute) {
        		if ($key < 11) {
        			array_push($attributes, $attribute->name);
        		}
        	}
        	foreach ($template->secondaryAttributes as $key => $attribute) {
        		if ($key < 11) {
        			array_push($secondaries, $attribute->name);
        		}
        	}
        	foreach ($template->skills as $key => $skill) {
        		if ($key < 11) {
        			array_push($skills, $skill->name);
        		}
        	}
        	foreach ($template->spells as $key => $spell) {
        		if ($key < 11) {
        			array_push($spells, $spell->name);
        		}
        	}
        }
        $this->setViewData('chatRoom', $chatRoom);
        $this->setViewData('chats', $chats->reverse());
        $this->setViewData('attributes', $attributes);
        $this->setViewData('secondaries', $secondaries);
        $this->setViewData('skills', $skills);
        $this->setViewData('spells', $spells);
	}

	public function action_getChatLog($chatRoomId, $lastId)
	{
        $this->setLayout('null');
        // Get the data
        $chats = Chat::where('chat_room_id', '=', $chatRoomId)->where('id', '>', $lastId)->order_by('created_at', 'desc')->get();
		$this->setTemplate(array('chats' => array_reverse($chats)));
	}

	public function action_getUsersOnline($chatRoomId)
	{
        $this->setLayout('null');
        // Get the data
        $chatRoom = Chat_room::find($chatRoomId);
		$this->setTemplate(array('chatRoom' => $chatRoom));
	}

	public function action_fullChat($chatRoomId)
	{
		if ($chatRoomId == null || Chat_room::find($chatRoomId) == null) {
			return Redirect::back()->with('errors', array('The requested chat room does not exist.'));
		}

        // Get the data
        $chatRoom = Chat_room::with('chats')->find($chatRoomId);
		$this->setTemplate(array('chatRoom' => $chatRoom));
	}

	public function postAddmessage()
	{
        $this->skipView = true;

       	// Handle the form input
		$input = Input::all();

		if ($input != null) {
			$message = e($input['message']);
			$message            = preg_replace_callback('/\/roll/', array($this, 'roll'), $message);
			$chat               = new Chat;
			$chat->user_id      = $this->activeUser->id;
			$chat->character_id = ($input['character_id'] == 0 ? null : $input['character_id']);
			$chat->chat_room_id = $input['chat_room_id'];
			$chat->message      = $message;
			$chat->save();
			// $chat->sendErsatz();

            $errors = $this->checkErrors($chat);

            if ($errors == true) {
                return $chat->getErrors()->toJson();
            }
		}
	}

	public function action_update($chatRoomId, $property, $value)
	{
		if(!$this->hasPermission('CHAT_CREATE')) {
			$this->errorRedirect();
		}

		$chatRoom = Chat_room::find($chatRoomId);
		$chatRoom->{$property} = $value;

		$this->checkErrorsSave($chatRoom);

		return $this->redirect('back', $chatRoom->name .' has been updated.');
	}

	public function action_delete($chatRoomId)
	{
		if(!$this->hasPermission('CHAT_CREATE')) {
			$this->errorRedirect();
		}

		$chatRoom = Chat_room::find($chatRoomId);
		$chatRoom->delete();

		return $this->redirect('back', $chatRoom->name .' has been deleted.');
	}

	public function action_clear($chatRoomId)
	{
		if(!$this->hasPermission('CHAT_CREATE')) {
			$this->errorRedirect();
		}

		$ersatzClient = new Ersatz(null);
		$chats = Chat::where('chat_room_id', '=', $chatRoomId)->get();

		if (count($chats) > 0) {
			foreach ($chats as $chat) {
				$chat->removeErsatzFrom($ersatzClient);
				$chat->delete();
			}
		}

		$ersatzClient->flush();

		return $this->redirect('back', 'Chat room cleared.');
	}

	public function getUsercount($chatRoomId)
	{
		$this->skipView = true;

		$rawData = substr(Chat::getUserCount($chatRoomId), 4);
		$jsonData = json_decode($rawData);

		return $jsonData->args[0];
	}
}

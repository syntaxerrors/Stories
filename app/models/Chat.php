<?php

class Chat extends Aware
{
	/********************************************************************
	 * Declarations
	 *******************************************************************/

	/********************************************************************
	 * Aware validation rules
	 *******************************************************************/
	public static $rules = array(
		'user_id'      => 'required|exists:users,id',
		'character_id' => 'exists:characters,id',
		'message'      => 'required',
		'chat_room_id' => 'required|exists:chat_rooms,id',
	);

	/********************************************************************
	 * Relationships
	 *******************************************************************/
	public function user()
	{
		return $this->belongs_to('User', 'user_id');
	}
	public function character()
	{
		return $this->belongs_to('Character', 'character_id');
	}
	public function room()
	{
		return $this->belongs_to('Chat\Room', 'chat_room_id');
	}

	/********************************************************************
	 * Getter and Setter methods
	 *******************************************************************/

	/********************************************************************
	 * Extra Methods
	 *******************************************************************/
    public function sendErsatz()
    {
        $ersatzClient = new Ersatz(null);
        $this->sendErsatzTo($ersatzClient);
        $ersatzClient->flush();
    }

    public function sendErsatzTo($ersatzClient)
    {
    	if ($this->character_id != null) {
			$character         = $this->character()->first();
			$characterName     = $character->name;
			$characterCreature = $character->creatureFlag;
			$characterNpc      = $character->npcFlag;
			$characterColor    = $character->color;
    	} else {
			$character         = null;
			$characterName     = null;
			$characterCreature = null;
			$characterNpc      = null;
			$characterColor    = null;
    	}
		$keys = array($this->room()->first()->name);
		$ersatzClient->send(
            "Chat:". $this->id,
            "Chat/create",
            $keys,
            array(
				'id'                => (int)$this->id,
				'userId'            => (int)$this->user_id,
				'userName'          => $this->user()->first()->username,
				'character_id'      => (int)$this->character_id,
				'characterName'     => $characterName,
				'characterCreature' => (int)$characterCreature,
				'characterNpc'      => (int)$characterNpc,
				'characterColor'    => $characterColor,
				'roomId'            => (int)$this->chat_room_id,
				'roomName'          => $this->room()->first()->name,
				'gameId'            => (int)$this->room()->first()->game_id,
				'gameName'          => $this->room()->first()->game->name,
				'message'           => BBCode::parse($this->message),
				'created_at'        => $this->formatErsatzDate($this->created_at)
            )
        );
    }
    public function removeErsatz()
    {
        $ersatzClient = new Ersatz(null);
        $this->removeErsatzFrom($ersatzClient);
        $ersatzClient->flush();
    }

    public function removeErsatzFrom($ersatzClient)
    {
    	if ($this->character_id != null) {
			$character         = $this->character()->first();
			$characterName     = $character->name;
			$characterCreature = $character->creatureFlag;
			$characterNpc      = $character->npcFlag;
			$characterColor    = $character->color;
    	} else {
			$character         = null;
			$characterName     = null;
			$characterCreature = null;
			$characterNpc      = null;
			$characterColor    = null;
    	}

    	$ersatzClient->send(
            "Chat:". $this->id,
            "Chat/delete",
            array(),
            array(
				'id'                => (int)$this->id,
				'userId'            => (int)$this->user_id,
				'userName'          => $this->user()->first()->username,
				'character_id'      => (int)$this->character_id,
				'characterName'     => $characterName,
				'characterCreature' => (int)$characterCreature,
				'characterNpc'      => (int)$characterNpc,
				'characterColor'    => $characterColor,
				'roomId'            => (int)$this->chat_room_id,
				'roomName'          => $this->room()->first()->name,
				'gameId'            => (int)$this->room()->first()->game_id,
				'gameName'          => $this->room()->first()->game->name,
				'message'           => BBCode::parse($this->message),
				'created_at'        => $this->formatErsatzDate($this->created_at)
            )
        );
    }

    private function formatErsatzDate($datetime)
    {
        $tz = new DateTimeZone("America/Chicago");
        if ($datetime == null || $datetime == "sysdate") {
            $datetime = new DateTime('now', $tz);
        }
        if (is_string($datetime)) {
            $datetime = new DateTime($datetime, $tz);
        }
        return $datetime->format("Y-m-d H:i:s");
    }


	// public function save()
	// {
	// 	parent::save();
	// 	$ersatzClient = new Ersatz(null);
	// 	$this->sendErsatzTo($ersatzClient);
	// 	$ersatzClient->flush();
	// }
}
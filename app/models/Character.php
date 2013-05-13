<?php

class Character extends Aware
{
	/********************************************************************
	 * Declarations
	 *******************************************************************/

	/********************************************************************
	 * Aware validation rules
	 *******************************************************************/
	public static $rules = array(
		'name'      => 'required|max:200',
		'user_id'   => 'required|exists:users,id',
		'game_id'   => 'required|exists:games,id',
		'parent_id' => 'exists:characters,id',
	);

	/********************************************************************
	 * Relationships
	 *******************************************************************/
	public function user()
	{
		return $this->belongs_to('User');
	}

	public function game()
	{
		return $this->belongs_to('Game');
	}

	public function template()
	{
		return $this->game()->first()->template;
	}

	public function parent()
	{
		return $this->belongs_to('Character', 'parent_id');
	}

	public function characterClass()
	{
		return $this->has_one('Character\CharacterClass');
	}

	public function appearances()
	{
		return $this->has_many('Character\Appearance');
	}

	public function stats()
	{
		return $this->has_many('Character\BaseStat');
	}

	public function traits()
	{
		return $this->has_many('Character\Trait');
	}

	public function characterAttributes()
	{
		return $this->has_many('Character\Attribute');
	}

	public function secondaryAttributes()
	{
		return $this->has_many('Character\SecondaryAttribute');
	}

	public function skills()
	{
		return $this->has_many('Character\Skill');
	}

	public function spells()
	{
		return $this->has_many('Character\Spell');
	}

	public function inventory()
	{
		return $this->has_many('Character\Inventory');
	}

	public function currency()
	{
		return $this->has_many('Character\Currency');
	}

	public function rolls()
	{
		return $this->has_many('Character\Roll')->order_by('roll', 'desc');
	}

	public function notes()
	{
		return $this->has_many('Character\Note');
	}

	public function experienceHistory()
	{
		return $this->has_many('Character\Experience\History');
	}

	public function loot()
	{
		return $this->has_many('Character\Loot');
	}

	/********************************************************************
	 * Getter and Setter methods
	 *******************************************************************/

	/**
	 * Make created_at easier to read
	 *
	 * @return string
	 */
	public function get_created_at()
	{
		return date('F jS, Y \a\t h:ia', strtotime($this->get_attribute('created_at')));
	}

	/**
	 * The count of posts made by this character (not it's user)
	 *
	 * @return int
	 */
	public function get_postsCount()
	{
		$postCount  = Forum\Post::where('character_id', '=', $this->get_attribute('id'))->count();
		$replyCount = Forum\Reply::where('character_id', '=', $this->get_attribute('id'))->count();
		return $postCount + $replyCount;
	}

	/**
	 * See if this character is approved
	 *
	 * @return boolean
	 */
	public function get_approved()
	{
		// All NPCs are approved
		if ($this->get_attribute('npcFlag') == 1) {
			return 1;
		}

		// All ST characters are approved
		$game = Game::find($this->get_attribute('game_id'));
		if ($game->isStoryteller($this->get_attribute('user_id'))) {
			return 1;
		}

		// All table top games are approved
		if ($this->get_attribute('game_id') == 10) {
			return 1;
		}

		// Otherwise, find the post to check
		return Forum\Post::where('forum_post_type_id', '=', Forum\Post::TYPE_APPLICATION)->where('character_id', '=', $this->get_attribute('id'))->first()->approvedFlag;
	}

	/********************************************************************
	 * Extra Methods
	 *******************************************************************/

	/**
	 * Get the value of a game stat for this character
	 *
	 * @param string $type The type of stat to get the value of
	 * @param int $id The id of the stat
	 *
	 * @return string
	 */
	public function getValue($type, $id)
	{
		switch ($type) {
			case 'Appearance':
				$resource = Character\Appearance::where('character_id', '=', $this->get_attribute('id'))->where('game_template_appearance_id', '=', $id)->first();
			break;
			case 'BaseStat':
				$resource = Character\BaseStat::where('character_id', '=', $this->get_attribute('id'))->where('game_template_base_stat_id', '=', $id)->first();
			break;
			case 'Attribute':
				$resource = Character\Attribute::where('character_id', '=', $this->get_attribute('id'))->where('game_template_attribute_id', '=', $id)->first();
			break;
			case 'AttributeMod':
				$resource = Character\Attribute::where('character_id', '=', $this->get_attribute('id'))->where('game_template_attribute_id', '=', $id)->first();
			break;
			case 'SecondaryAttribute':
				$resource = Character\SecondaryAttribute::where('character_id', '=', $this->get_attribute('id'))->where('game_template_secondary_attribute_id', '=', $id)->first();
			break;
			case 'Skill':
				$resource = Character\Skill::where('character_id', '=', $this->get_attribute('id'))->where('game_template_skill_id', '=', $id)->first();
			break;
			case 'Trait':
				$resource = Character\Trait::where('character_id', '=', $this->get_attribute('id'))->where('game_template_trait_id', '=', $id)->first();
			break;
			case 'Inventory':
				$resource = Character\Inventory::where('character_id', '=', $this->get_attribute('id'))->where('game_template_inventory_id', '=', $id)->first();
			break;
			case 'Currency':
				$resource = Character\Currency::where('character_id', '=', $this->get_attribute('id'))->where('game_template_currency_id', '=', $id)->first();
			break;
		}
		if ($resource != null) {
			if ($type == 'AttributeMod') {
				return $resource->value .' ('. ($resource->modifier > 0 ? '+'. $resource->modifier : $resource->modifier).')';
			} else {
				return $resource->value;
			}
		}
		return null;
	}

	/**
	 * Get all os this user's spells for a certain magic type
	 *
	 * @param int $type The id of the magic type wanted
	 *
	 * @return string
	 */
	public function getSpells($typeId)
	{
		$trees = Game\Template\Magic\Tree::where('game_template_magic_type_id', '=', $typeId)->get('id');

		if (count($trees) > 0) {
			$treeIds      = array_pluck($trees, 'id');
			$gameSpells   = Game\Template\Spell::where_in('game_template_magic_tree_id', $treeIds)->get('id');

			if (count($gameSpells) > 0) {
				$gameSpellIds = array_pluck($gameSpells, 'id');
				return Character\Spell::where_in('game_template_spell_id', $gameSpellIds)->where('character_id', '=', $this->get_attribute('id'))->order_by('game_template_spell_id', 'asc')->get();
			}
		}
		return array();
	}

	/**
	 * Add experience to this character and send a message to them while updating the history
	 *
	 * @param int $exp The amount of exp being added
	 * @param int $userId The id of the user giving the exp
	 * @param string $reason The reason the exp is being granted
	 * @param int $post The post this is being granted for
	 *
	 * @return null
	 */
	public function addExperience($exp, $userId, $reason, $post = null, $postId = null)
	{
		// Set the new experience value
		$this->set_attribute('experience', $this->get_attribute('experience') + $exp);
		$this->save();

		// Send the user a message
		if ($post == null) {
			$message                  = new Message;
			$message->message_type_id = Message::EXPERIENCE;
			$message->sender_id       = $userId;
			$message->receiver_id     = $this->get_attribute('user_id');
			$message->title           = 'You gained experience points!';
			$message->content         = 'You were granted '. $exp .' experience points. <br /><br /> '.$reason .'<br /><br />Your character now has '. $this->get_attribute('experience') .' experience points total';
			$message->readFlag        = 0;
	        $message->save();
	    } else {
            $message                  = new Message;
            $message->message_type_id = Message::EXPERIENCE;
            $message->sender_id       = $userId;
            $message->receiver_id     = $this->get_attribute('user_id');
            $message->title           = 'You gained experience points!';
            $message->content         = $this->get_attribute('name') .' was granted '. $exp .' experience points. <br /><br /> This was given out for your post found '. $post .'.<br /><br />Your character now has '. $this->get_attribute('experience') .' experience points total';
            $message->readFlag        = 0;
            $message->save();
		}

        // Add the exp to the log
		$expHistory               = new Character\Experience\History;
		$expHistory->character_id = $this->get_attribute('id');
		$expHistory->user_id      = $userId;
		$expHistory->value        = $exp;
		$expHistory->reason       = $reason;
		$expHistory->resource_id  = $postId;
		$expHistory->balance      = $this->get_attribute('experience');
		$expHistory->save();

		return true;
	}

    public function sendErsatz()
    {
        $ersatzClient = new Ersatz(null);
        $this->sendErsatzTo($ersatzClient);
        $ersatzClient->flush();
    }

    public function sendErsatzTo($ersatzClient)
    {
		$keys = array();
		$keys[] = $this->name;
		$ersatzClient->send(
            "Character:". $this->id,
            "Character/modify",
            $keys,
            array(
				'id'                 => (int)$this->id,
				'name'               => $this->name,
				'userId'             => (int)$this->user_id,
				'userName'           => $this->user()->first()->username,
				'level'              => (int)$this->level,
				'experience'         => (int)$this->experience,
				'hitPoints'          => (int)$this->hitPoints,
				'tempHitPoints'      => (int)$this->tempHitPoints,
				'hitPointsPercent'   => (int)percent($this->tempHitPoints, $this->hitPoints),
				'magicPoints'        => (int)$this->magicPoints,
				'tempMagicPoints'    => (int)$this->tempMagicPoints,
				'magicPointsPercent' => (int)percent($this->tempMagicPoints, $this->magicPoints),
				'npcFlag'            => (int)$this->npcFlag,
				'creatureFlag'       => (int)$this->creatureFlag,
				'loot'               => $this->loot,
            )
        );
    }
}
<?php

class Character extends BaseModel
{
	/********************************************************************
	 * Declarations
	 *******************************************************************/
	protected $primaryKey = 'uniqueId';
	public $incrementing  = false;

	/********************************************************************
	 * Aware validation rules
	 *******************************************************************/

    /**
     * Validation rules
     *
     * @static
     * @var array $rules All rules this model must follow
     */
	public static $rules = array(
		'name'      => 'required|max:200',
		'user_id'   => 'required|exists:users,uniqueId',
		'game_id'   => 'required|exists:games,uniqueId',
		'parent_id' => 'exists:characters,uniqueId',
	);

	/********************************************************************
	 * Relationships
	 *******************************************************************/

    /**
     * User Relationship
     *
     * @return User
     */
	public function user()
	{
		return $this->belongsTo('User');
	}

    /**
     * Game Relationship
     *
     * @return Game
     */
	public function game()
	{
		return $this->belongsTo('Game');
	}

    /**
     * Template Relationship
     *
     * @return Template
     */
	public function template()
	{
		return $this->game()->first()->template;
	}

    /**
     * User Relationship
     *
     * @return User[]
     */
	public function parent()
	{
		return $this->belongsTo('Character', 'parent_id');
	}

    /**
     * Class Relationship
     *
     * @return Game_Template_Class
     */
	public function characterClass()
	{
		return $this->belongsTo('Game_Template_Class', 'character_classes');
	}

    /**
     * Appearance Relationship
     *
     * @return Game_Template_Appearance[]
     */
	public function appearances()
	{
		return $this->belongsToMany('Game_Template_Appearance', 'character_appearances');
	}

    /**
     * Stat Relationship
     *
     * @return Character_Stat[]
     */
	public function stats()
	{
		return $this->hasMany('Character_Stat');
	}

    /**
     * Trait Relationship
     *
     * @return Character_Trait[]
     */
	public function traits()
	{
		return $this->hasMany('Character_Trait');
	}

    /**
     * Attribute Relationship
     *
     * @return Character_Attribute[]
     */
	public function characterAttributes()
	{
		return $this->hasMany('Character_Attribute');
	}

    /**
     * Secondary Attribute Relationship
     *
     * @return Character_SecondaryAttribute[]
     */
	public function secondaryAttributes()
	{
		return $this->hasMany('Character_SecondaryAttribute');
	}

    /**
     * Skill Relationship
     *
     * @return Character_Skill[]
     */
	public function skills()
	{
		return $this->hasMany('Character_Skill');
	}

    /**
     * Spell Relationship
     *
     * @return Character_Spell[]
     */
	public function spells()
	{
		return $this->hasMany('Character_Spell');
	}

    /**
     * Inventory Relationship
     *
     * @return Character_Inventory[]
     */
	public function inventory()
	{
		return $this->hasMany('Character_Inventory');
	}

    /**
     * Currency Relationship
     *
     * @return Character_Currency[]
     */
	public function currency()
	{
		return $this->hasMany('Character_Currency');
	}

    /**
     * Roll Relationship
     *
     * @return Character_Roll[]
     */
	public function rolls()
	{
		return $this->hasMany('Character_Roll')->orderBy('roll', 'desc');
	}

    /**
     * Note Relationship
     *
     * @return Character_Note[]
     */
	public function notes()
	{
		return $this->hasMany('Character_Note');
	}

    /**
     * Experience History Relationship
     *
     * @return Character_Experience_History[]
     */
	public function experienceHistory()
	{
		return $this->hasMany('Character_Experience_History');
	}

	/********************************************************************
	 * Model events
	 *******************************************************************/

	public static function boot()
	{
		parent::boot();

		Character::creating(function($object)
		{
			$object->uniqueId = parent::findExistingReferences('Character');
		});
	}

	/********************************************************************
	 * Getter and Setter methods
	 *******************************************************************/

	/**
	 * Get the character's avatar
	 *
	 * @return string
	 */
	public function getAvatarAttribute()
	{
		if (file_exists( public_path() .'/img/forum/avatars/'. classify($this->game->name) . '_'. classify($this->name) .'.png')) {
			return HTML::linkImage(
				Request::path(),
				HTML::image(
					'img/forum/avatars/'. classify($this->game->name) . '_'. classify($this->name) .'.png',
					null,
					array('style' => 'width: 100px;', 'class'=> 'media-object img-polaroid')
				), 
				array('class' => 'pull-left')
			);
		} else {
			HTML::image($this->user->gravitar, null, array('class'=> 'media-object pull-left', 'style' => 'width: 100px;'));
		}
	}

	/**
	 * The count of posts made by this character (not it's user)
	 *
	 * @return int
	 */
	public function getPostsCountAttribute()
	{
		$postCount  = Forum_Post::where('character_id', '=', $this->id)->count();
		$replyCount = Forum_Reply::where('character_id', '=', $this->id)->count();
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
		if ($this->npcFlag == 1) {
			return 1;
		}

		// All ST characters are approved
		$game = Game::find($this->game_id);
		if ($game->isStoryteller($this->user_id)) {
			return 1;
		}

		// All table top games are approved
		if ($this->game_id == 10) {
			return 1;
		}

		// Otherwise, find the post to check
		return Forum_Post::where('forum_post_type_id', '=', Forum_Post::TYPE_APPLICATION)->where('character_id', '=', $this->id)->first()->approvedFlag;
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
				$resource = Character_Appearance::where('character_id', '=', $this->id)->where('game_template_appearance_id', '=', $id)->first();
			break;
			case 'BaseStat':
				$resource = Character_BaseStat::where('character_id', '=', $this->id)->where('game_template_base_stat_id', '=', $id)->first();
			break;
			case 'Attribute':
				$resource = Character_Attribute::where('character_id', '=', $this->id)->where('game_template_attribute_id', '=', $id)->first();
			break;
			case 'AttributeMod':
				$resource = Character_Attribute::where('character_id', '=', $this->id)->where('game_template_attribute_id', '=', $id)->first();
			break;
			case 'SecondaryAttribute':
				$resource = Character_SecondaryAttribute::where('character_id', '=', $this->id)->where('game_template_secondary_attribute_id', '=', $id)->first();
			break;
			case 'Skill':
				$resource = Character_Skill::where('character_id', '=', $this->id)->where('game_template_skill_id', '=', $id)->first();
			break;
			case 'Trait':
				$resource = Character_Trait::where('character_id', '=', $this->id)->where('game_template_trait_id', '=', $id)->first();
			break;
			case 'Inventory':
				$resource = Character_Inventory::where('character_id', '=', $this->id)->where('game_template_inventory_id', '=', $id)->first();
			break;
			case 'Currency':
				$resource = Character_Currency::where('character_id', '=', $this->id)->where('game_template_currency_id', '=', $id)->first();
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
		$trees = Game_Template_Magic_Tree::where('game_template_magic_type_id', '=', $typeId)->get('id');

		if (count($trees) > 0) {
			$treeIds      = array_pluck($trees, 'id');
			$gameSpells   = Game_Template_Spell::where_in('game_template_magic_tree_id', $treeIds)->get('id');

			if (count($gameSpells) > 0) {
				$gameSpellIds = array_pluck($gameSpells, 'id');
				return Character_Spell::where_in('game_template_spell_id', $gameSpellIds)->where('character_id', '=', $this->id)->order_by('game_template_spell_id', 'asc')->get();
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
		$this->set_attribute('experience', $this->experience + $exp);
		$this->save();

		// Send the user a message
		if ($post == null) {
			$message                  = new Message;
			$message->message_type_id = Message::EXPERIENCE;
			$message->sender_id       = $userId;
			$message->receiver_id     = $this->user_id;
			$message->title           = 'You gained experience points!';
			$message->content         = 'You were granted '. $exp .' experience points. <br /><br /> '.$reason .'<br /><br />Your character now has '. $this->experience .' experience points total';
			$message->readFlag        = 0;
	        $message->save();
	    } else {
            $message                  = new Message;
            $message->message_type_id = Message::EXPERIENCE;
            $message->sender_id       = $userId;
            $message->receiver_id     = $this->user_id;
            $message->title           = 'You gained experience points!';
            $message->content         = $this->name .' was granted '. $exp .' experience points. <br /><br /> This was given out for your post found '. $post .'.<br /><br />Your character now has '. $this->experience .' experience points total';
            $message->readFlag        = 0;
            $message->save();
		}

        // Add the exp to the log
		$expHistory               = new Character_Experience_History;
		$expHistory->character_id = $this->id;
		$expHistory->user_id      = $userId;
		$expHistory->value        = $exp;
		$expHistory->reason       = $reason;
		$expHistory->resource_id  = $postId;
		$expHistory->balance      = $this->experience;
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
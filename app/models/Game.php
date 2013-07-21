<?php

class Game extends BaseModel
{
	/********************************************************************
	 * Declarations
	 *******************************************************************/

	/**
	 * Table declaration
	 *
	 * @var string $table The table this model uses
	 */
	protected $table = 'games';
	protected $primaryKey = 'uniqueId';

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
		'name'         => 'required|max:200',
		'game_type_id' => 'required|exists:game_types,uniqueId',
	);

	/********************************************************************
	 * Relationships
	 *******************************************************************/

    /**
     * Game Type Relationship
     *
     * @return Game_Type
     */
	public function type()
	{
		return $this->belongsTo('Game_Type', 'game_type_id');
	}

    /**
     * Story-Tellers Relationship
     *
     * @return Anima_StoryTeller[]
     */
	public function storytellers()
	{
		return $this->hasMany('Game_StoryTeller');
	}

    /**
     * Character Relationship
     *
     * @return Anima_Character[]
     */
	public function characters()
	{
		return $this->hasMany('Character');
	}

    /**
     * Entity Relationship
     *
     * @return Anima_Entity[]
     */
	public function entities()
	{
		return $this->hasMany('Anima_Entity');
	}

    /**
     * Enemy Relationship
     *
     * @return Anima_Enemy[]
     */
	public function enemies()
	{
		return $this->hasMany('Anima_Enemy');
	}

    /**
     * Horde Relationship
     *
     * @return Anima_Horde[]
     */
	public function hordes()
	{
		return $this->hasMany('Anima_Horde');
	}

    /**
     * Forum Category Relationship
     *
     * @return Forum_Category[]
     */
	public function forum()
	{
		return $this->hasOne('Forum_Category', 'game_id');
	}

    /**
     * Game Note Relationship
     *
     * @return Anima_Game_Note[]
     */
	public function notes()
	{
		return $this->hasMany('Anima_Game_Note', 'game_id');
	}

    /**
     * Game Item Relationship
     *
     * @return Anima_Game_Item[]
     */
	public function items()
	{
		return $this->hasMany('Anima_Game_Item', 'game_id');
	}

    /**
     * Game Quest Relationship
     *
     * @return Anima_Game_Quest[]
     */
	public function quests()
	{
		return $this->hasMany('Anima_Game_Quest', 'game_id');
	}

    /**
     * Game Event Relationship
     *
     * @return Anima_Game_Event[]
     */
	public function events()
	{
		return $this->hasMany('Anima_Game_Event', 'game_id');
	}

	/********************************************************************
	 * Getter and Setter methods
	 *******************************************************************/

	/**
	 * Get human readable active flag
	 *
	 * @return string
	 */
	public function getActiveStatusAttribute()
	{
		return ($this->activeFlag == 1 ? 'Active' : 'Inactive');
	}

	/**
	 * Find all unapproved characters for this game
	 *
	 * @return array
	 */
	public function getCharactersAwaitingApprovalAttribute()
	{
		// Get the games forum category
		$category = $this->forum()->first();

		if (!is_null($category)) {
			// Find the application board
			$applicationBoard = Forum_Board::where('forum_category_id', '=', $category->id)->where('forum_board_type_id', '=', Forum_Board::TYPE_APPLICATION)->first();

			if ($applicationBoard != null) {
				// Get all unapproved applications
				return Forum_Post::where('forum_board_id', '=', $applicationBoard->id)->where('approvedFlag', '=', 0)->whereNotNull('character_id')->get();
			}
		}
		return array();
	}

	/**
	 * Find all unapproved actions for this game
	 *
	 * @return array
	 */
	public function getActionsAwaitingApprovalAttribute()
	{
		// Get the game category
		$category = $this->forum()->first()->boards();

		if (count($boards) > 0) {
			// Get all the posts in the boards
			$boardIds = array_pluck($boards, 'id');
			$posts = Forum_Post::where_in('forum_board_id', $boardIds)->get();

			if (count($posts) > 0) {
				// Get all unapproved action replies
				$postIds  = array_pluck($posts, 'id');
				$replies  = Forum_Reply::where_in('forum_post_id', $postIds)->where('forum_reply_type_id', '=', Forum_Reply::TYPE_ACTION)->where('approvedFlag', '=', 0)->get();
				return $replies;
			}
		}

		return array();
	}

	/**
	 * Get all characters (Player, NPC, Creature, etc)
	 *
	 * @return array
	 */
	public function getFullCharactersAttribute()
	{
		return $this->characters()->order_by('name', 'asc')->get();
	}

	/**
	 * Get all characters (Player, NPC, Creature, etc)
	 *
	 * @return array
	 */
	public function getCharacterSubscriptionsAttribute()
	{
		$characters = $this->characters()->order_by('name', 'asc')->where('activeFlag', '=', 1)->get('name');

		$subscriptions = array();

		if (count($characters) > 0) {
			foreach ($characters as $character) {
				$subscriptions[] = $character->name;
			}
		}

		return $subscriptions;
	}

	/**
	 * Get all player characters
	 *
	 * @return array
	 */
	public function getAllCharactersAttribute()
	{
		return $this->characters()->where('npcFlag', '=', 0)->where('creatureFlag', '=', 0)->order_by('name', 'asc')->get();
	}

	/**
	 * Get all NPC characters
	 *
	 * @return array
	 */
	public function getAllNpcsAttribute()
	{
		return $this->characters()->where('npcFlag', '=', 1)->where('creatureFlag', '=', 0)->order_by('name', 'asc')->get();
	}

	/**
	 * Get all creature characters
	 *
	 * @return array
	 */
	public function getAllCreaturesAttribute()
	{
		return $this->characters()->where('creatureFlag', '=', 1)->order_by('name', 'asc')->get();
	}

	/**
	 * Get all characters that are approved
	 *
	 * @return array
	 */
	public function getApprovedCharactersAttribute()
	{
		return $this->characters()->where('approvedFlag', '=', 1)->get();
	}

	/**
	 * Get all unapproved characters
	 *
	 * @return array
	 */
	public function getNonApprovedCharactersAttribute()
	{
		return $this->characters()->where('approvedFlag', '=', 0)->get();
	}

	/**
	 * Get all unapproved user trees
	 *
	 * @return array
	 */
	public function getUnApprovedTreesAttribute()
	{
		// Get all characters
		$characters = $this->getFullCharactersAttribute();

		if (count($characters) > 0) {
			// Get any unapproved spells
			$characterIds = array_pluck($characters, 'id');
			return Game_Template_Magic_Tree::where_in('character_id', $characterIds)->where('approvedFlag', '=', 0)->get();
		}
		return array();
	}

	/**
	 * Get all unapproved user spells
	 *
	 * @return array
	 */
	public function getUnApprovedSpellsAttribute()
	{
		// Get all characters
		$characters = $this->getFullCharactersAttribute();

		if (count($characters) > 0) {
			// Get any unapproved spells
			$characterIds = array_pluck($characters, 'id');
			return Game_Template_Spell::where_in('character_id', $characterIds)->where('approvedFlag', '=', 0)->get();
		}
		return array();
	}

	/**
	 * Get all unapproved character spells
	 *
	 * @return array
	 */
	public function getUnApprovedCharacterSpellsAttribute()
	{
		// Get all characters
		$characters = $this->getFullCharactersAttribute();

		if (count($characters) > 0) {
			// Get any unapproved spells
			$characterIds = array_pluck($characters, 'id');
			return Character_Spell::where_in('character_id', $characterIds)->where('approvedFlag', '=', 0)->get();
		}
		return array();
	}

	/**
	 * Get the last 5 posts in this game's category
	 *
	 * @return array
	 */
	public function getRecentPostsAttribute()
	{
		// Get this game's category
		$boards = $this->forum()->first()->boards();

		if (count($boards) > 0) {
			// Get the last 5 posts
			$boardIds = array_pluck($boards, 'id');
			return Forum_Post::where_in('forum_board_id', $boardIds)->order_by('modified_at', 'desc')->take(5)->get();
		}

		return array();
	}

	/**
	 * Get all user spells
	 *
	 * @return array
	 */
	public function getUserSpellsAttribute()
	{
		// Get all the characters
		$characters = $this->characters()->get();
		$spells = array();

		foreach ($characters as $character) {
			// If the character has spells, add it to the array
			if (!is_null($character->spells)) {
				foreach ($character->spells as $spell) {
					$spells[] = $spell;
				}
			}
		}
		return $spells;
	}

	/********************************************************************
	 * Extra Methods
	 *******************************************************************/

	/**
	 * See if the user is a story-teller for this game
	 *
	 * @param int $userId The user Id being tested
	 *
	 * @return boolean
	 */
	public function isStoryteller($userId)
	{
		$userSt = Game_StoryTeller::where('user_id', '=', $userId)->where('game_id', '=', $this->id)->first();
		if ($userSt != null || Auth::user()->can('DEVELOPMENT')) {
			return true;
		}
		return false;
	}
}
<?php
use Awareness\Aware;

class Game extends Aware
{
	/********************************************************************
	 * Declarations
	 *******************************************************************/

	/********************************************************************
	 * Aware validation rules
	 *******************************************************************/
	public static $rules = array(
		'name' => 'required|max:200',
	);

	/********************************************************************
	 * Relationships
	 *******************************************************************/
	public function template()
	{
		return $this->belongs_to('Game\Template', 'game_template_id');
	}

	public function storytellers()
	{
		return $this->has_many('Game\StoryTeller');
	}

	public function characters()
	{
		return $this->has_many('Character');
	}

	public function forum()
	{
		return $this->has_one('Forum\Category', 'game_id');
	}

	public function notes()
	{
		return $this->has_many('Game\Note', 'game_id');
	}

	public function items()
	{
		return $this->has_many('Game\Item', 'game_id');
	}

	/********************************************************************
	 * Getter and Setter methods
	 *******************************************************************/

	/**
	 * Get human readable active flag
	 *
	 * @return string
	 */
	public function get_activeStatus()
	{
		return ($this->get_attribute('activeFlag') == 1 ? 'Active' : 'Inactive');
	}

	/**
	 * Make the created_at data easier to read
	 *
	 * @return string
	 */
	public function get_created_at()
	{
		return date('F jS, Y \a\t h:ia', strtotime($this->get_attribute('created_at')));
	}

	/**
	 * Find all unapproved characters for this game
	 *
	 * @return array
	 */
	public function get_charactersAwaitingApproval()
	{
		// Get the games forum category
		$category = $this->forum()->first();

		if (!is_null($category)) {
			// Find the application board
			$applicationBoard = Forum\Board::where('forum_category_id', '=', $category->id)->where('forum_board_type_id', '=', Forum\Board::TYPE_APPLICATION)->first();

			if ($applicationBoard != null) {
				// Get all unapproved applications
				return Forum\Post::where('forum_board_id', '=', $applicationBoard->id)->where('approvedFlag', '=', 0)->where_not_null('character_id')->get();
			}
		}
		return array();
	}

	/**
	 * Find all unapproved actions for this game
	 *
	 * @return array
	 */
	public function get_actionsAwaitingApproval()
	{
		// Get the game category
		$category = $this->forum()->first();

		if (!is_null($category)) {
			// Get all bards in this category
			$boards = $category->boards;

			if (count($boards) > 0) {
				// Get all the posts in the boards
				$boardIds = array_pluck($boards, 'id');
				$posts = Forum\Post::where_in('forum_board_id', $boardIds)->get();

				if (count($posts) > 0) {
					// Get all unapproved action replies
					$postIds  = array_pluck($posts, 'id');
					$replies  = Forum\Reply::where_in('forum_post_id', $postIds)->where('forum_reply_type_id', '=', Forum\Reply::TYPE_ACTION)->where('approvedFlag', '=', 0)->get();
					return $replies;
				}
			}
		}
		return array();
	}

	/**
	 * Get all characters (Player, NPC, Creature, etc)
	 *
	 * @return array
	 */
	public function get_fullCharacters()
	{
		return Character::where('game_id', '=', $this->get_attribute('id'))->order_by('name', 'asc')->get();
	}

	/**
	 * Get all characters (Player, NPC, Creature, etc)
	 *
	 * @return array
	 */
	public function get_characterSubscriptions()
	{
		$characters = Character::where('game_id', '=', $this->get_attribute('id'))->order_by('name', 'asc')->where('activeFlag', '=', 1)->get('name');

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
	public function get_allCharacters()
	{
		return Character::where('game_id', '=', $this->get_attribute('id'))->where('npcFlag', '=', 0)->where('creatureFlag', '=', 0)->order_by('name', 'asc')->get();
	}

	/**
	 * Get all NPC characters
	 *
	 * @return array
	 */
	public function get_allNpcs()
	{
		return Character::where('game_id', '=', $this->get_attribute('id'))->where('npcFlag', '=', 1)->where('creatureFlag', '=', 0)->order_by('name', 'asc')->get();
	}

	/**
	 * Get all creature characters
	 *
	 * @return array
	 */
	public function get_allCreatures()
	{
		return Character::where('game_id', '=', $this->get_attribute('id'))->where('creatureFlag', '=', 1)->order_by('name', 'asc')->get();
	}

	/**
	 * Get all characters that are approved
	 *
	 * @return array
	 */
	public function get_approvedCharacters()
	{
		return Character::where('game_id', '=', $this->get_attribute('id'))->where('approvedFlag', '=', 1)->get();
	}

	/**
	 * Get all unapproved characters
	 *
	 * @return array
	 */
	public function get_nonApprovedCharacters()
	{
		return Character::where('game_id', '=', $this->get_attribute('id'))->where('approvedFlag', '=', 0)->get();
	}

	/**
	 * Get all unapproved user trees
	 *
	 * @return array
	 */
	public function get_unApprovedTrees()
	{
		// Get all characters
		$characters = $this->get_fullCharacters();

		if (count($characters) > 0) {
			// Get any unapproved spells
			$characterIds = array_pluck($characters, 'id');
			return Game\Template\Magic\Tree::where_in('character_id', $characterIds)->where('approvedFlag', '=', 0)->get();
		}
		return array();
	}

	/**
	 * Get all unapproved user spells
	 *
	 * @return array
	 */
	public function get_unApprovedSpells()
	{
		// Get all characters
		$characters = $this->get_fullCharacters();

		if (count($characters) > 0) {
			// Get any unapproved spells
			$characterIds = array_pluck($characters, 'id');
			return Game\Template\Spell::where_in('character_id', $characterIds)->where('approvedFlag', '=', 0)->get();
		}
		return array();
	}

	/**
	 * Get all unapproved character spells
	 *
	 * @return array
	 */
	public function get_unApprovedCharacterSpells()
	{
		// Get all characters
		$characters = $this->get_fullCharacters();

		if (count($characters) > 0) {
			// Get any unapproved spells
			$characterIds = array_pluck($characters, 'id');
			return Character\Spell::where_in('character_id', $characterIds)->where('approvedFlag', '=', 0)->get();
		}
		return array();
	}

	/**
	 * Get the last 5 posts in this game's category
	 *
	 * @return array
	 */
	public function get_recentPosts()
	{
		// Get this game's category
		$category = $this->forum()->first();

		if (count($category) > 0) {
			// Get all boards in the category
			$boards      = $category->boards;

			if (count($boards) > 0) {
				// Get the last 5 posts
				$boardIds = array_pluck($boards, 'id');
				return Forum\Post::where_in('forum_board_id', $boardIds)->order_by('modified_at', 'desc')->take(5)->get();
			}
		}
		return array();
	}

	/**
	 * Get all user spells
	 *
	 * @return array
	 */
	public function get_userSpells()
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
		$userSt = Game\StoryTeller::where('user_id', '=', $userId)->where('game_id', '=', $this->get_attribute('id'))->first();
		if ($userSt != null || Auth::user()->can('DEVELOPMENT')) {
			return true;
		}
		return false;
	}
}
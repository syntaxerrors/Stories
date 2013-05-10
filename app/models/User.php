<?php
use Awareness\Aware;

class User extends Aware
{
	/********************************************************************
	 * Declarations
	 *******************************************************************/

	/**
	 * Table declaration
	 *
	 * @var string $table The table this model uses
	 */
	protected $table = 'users';

	/**
	 * Soft Delete users instead of completely removing them
	 *
	 * @var bool $softDelete Whether to delete or soft delete
	 */
	protected $softDelete = true;

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
		'username' => 'required|max:200',
		'password' => 'required|max:200',
		'email'    => 'required|email'
	);

	/********************************************************************
	 * Scopes
	 *******************************************************************/

    /**
     * Active scope
     *
     * @param array $query The current query to append to
     */
	public function scopeActive($query)
	{
		return $query->where('activeFlag', '=', 1);
	}

    /**
     * Visible user scope
     *
     * @param array $query The current query to append to
     */
	public function scopeVisible($query)
	{
		return $query->where('hiddenFlag', '=', 0);
	}

	/********************************************************************
	 * Relationships
	 *******************************************************************/

    /**
     * Role Relationship
     *
     * @return Role[]
     */
	public function roles()
	{
		return $this->belongsToMany('Role', 'role_users');
	}

    /**
     * Character Relationship
     *
     * @return Character[]
     */
	public function characters()
	{
		return $this->hasMany('Character');
	}

    /**
     * Visible Characters Relationship
     *
     * @return Character[]
     */
	public function visibleCharacters()
	{
		return $this->hasMany('Character')->visible();
	}

    /**
     * Active Character Relationship
     *
     * @return Character[]
     */
	public function activeCharacters()
	{
		return $this->hasMany('Character')->active();
	}

    /**
     * Game Relationship
     *
     * @return Game[]
     */
	public function games()
	{
		return $this->belongsToMany('Game', 'game_storytellers');
	}

    /**
     * Read Forum Posts Relationship
     *
     * @return Forum_Post[]
     */
	public function readPosts()
	{
		return $this->belongsToMany('Forum_Post', 'forum_user_view_posts');
	}

    /**
     * Forum Post Relationship
     *
     * @return Forum_Post[]
     */
	public function posts()
	{
		return $this->hasMany('Forum_Post', 'user_id');
	}

    /**
     * Forum Reply Relationship
     *
     * @return Forum_Reply[]
     */
	public function replies()
	{
		return $this->hasMany('Forum_Reply', 'user_id');
	}

    /**
     * Message Folder Relationship
     *
     * @return Message_Folder[]
     */
	public function folders()
	{
		return $this->hasMany('Message_Folder', 'user_id')->order_by('name', 'asc');
	}

    /**
     * Media Relationship
     *
     * @return Media[]
     */
	public function media()
	{
		return $this->hasMany('Media', 'user_id');
	}

	/********************************************************************
	 * Getter and Setter methods
	 *******************************************************************/

	/**
	 * Make sure to hash the user's password on save
	 *
	 * @param string $value The value of the attribute (Auto Set)
	 */
	public function setPasswordAttribute($value)
	{
	    $this->attributes['password'] = Hash::make($value);
	}

	/**
	 * Check for an avatar uploaded to the site, resort to gravatar if none exists, resort to no user image if no gravatar exists
	 *
	 * @return string
	 */
	public function getGravitarAttribute()
	{
		// If the user has uploaded an avatar, always use that
		if (file_exists('/home/stygian/public_html/new_site2/public/img/avatars/'. Str::classify($this->get_attribute('username')) .'.png')) {
			return 'img/avatars/'. Str::classify($this->get_attribute('username')) .'.png';
		}

		// Check for valid gravatar
		$gravCheck = 'http://www.gravatar.com/avatar/'. md5( strtolower( trim( $this->email ) ) ) .'.png?d=404';
		$response  = get_headers($gravCheck);

		// If a valid gravatar URL is found, use the gravatar image
		if ($response[0] != "HTTP/1.0 404 Not Found"){
			return 'http://www.gravatar.com/avatar/'. md5( strtolower( trim( $this->email ) ) ) .'.png?s=200&d=blank';
		}

		// If no other image is set, use the default
		return 'img/no_user.png';
	}

	/**
	 * Combine the user's first and last name to produce a full name
	 *
	 * @return string
	 */
	public function getFullNameAttribute()
	{
		return $this->firstName .' '. $this->lastName;
	}

	/**
	 * Always uppercase a user's username
	 *
	 * @return string
	 */
	public function getUsernameAttribute($value)
	{
		return ucwords($value);
	}

	/**
	 * Make the join date easier to read
	 *
	 * @return string
	 */
	public function getJoinDateAttribute($value)
	{
		return date('F jS, Y \a\t h:ia', strtotime($value));
	}

	/**
	 * Create a version of last active that is readable
	 *
	 * @return string
	 */
	public function getLastActiveReadableAttribute()
	{
		return date('F jS, Y \a\t h:ia', strtotime($this->lastActive));
	}

	/**
	 * Get the count of all posts and replies
	 *
	 * @return int
	 */
	public function getPostsCountAttribute()
	{
		$postCount  = Forum_Post::where('user_id', '=', $this->id)->count();
		$replyCount = Forum_Reply::where('user_id', '=', $this->id)->count();

		return $postCount + $replyCount;
	}

	/**
	 * Get the count of unread messages
	 *
	 * @return int
	 */
	public function getUnreadMessageCountAttribute()
	{
		return Message::where('receiver_id', '=', $this->id)->where('readFlag', '=', 0)->count();
	}

	/********************************************************************
	 * Extra Methods
	 *******************************************************************/

	/**
	 * Get the user's first characters in a particular game
	 *
	 * @param  int $gameId The id for the game
	 *
	 * @return Character
	 */
	public function getGameCharacter($gameId)
	{
		return Character::where('user_id', '=', $this->id)->where('game_id', '=', $gameId)->first();
	}

	/**
	 * Get the count of all posts and replies
	 *
	 * @param int|null $characterId The character for the post count
	 *
	 * @return int
	 */
	public function characterPostsCount($characterId = null)
	{
		$postCount  = Forum_Post::where('character_id', '=', $characterId)->count();
		$replyCount = Forum_Reply::where('character_id', '=', $characterId)->count();
		return $postCount + $replyCount;
	}

	/**
	 * Get the user's characters in a particular game
	 *
	 * @param  int $gameId The id for the game
	 *
	 * @return Character
	 */
	public function getGameCharacters($gameId)
	{
		return Character::where('user_id', '=', $this->id)->where('game_id', '=', $gameId)->get();
	}

	/**
	 * Get the user's characters in a particular game template
	 *
	 * @param  int $templateId The id for the game template
	 *
	 * @return Character
	 */
	public function getTemplateCharacters($templateId)
	{
		$games   = Game::where('game_template_id', '=', $templateId)->get('id');
		$gameIds = array_pluck($games, 'id');
		return Character::where('user_id', '=', $this->id)->where_in('game_id', $gameIds)->get();
	}

	/**
	 * Get the first role for this user in a particular role group
	 *
	 * @param  string $group The group name of the role
	 *
	 * @return string
	 */
	public function getRole($group)
	{
		$roles   = Role::where('group', '=', $group)->get('id');
		$roleIds = array_pluck($roles, 'id');
		return Role_User::where('user_id', '=', $this->id)->where_in('role_id', $roleIds)->first();
	}

	/**
	 * Get the full object for the user's highest role in a particular role group
	 *
	 * @param  string $group The group name of the role
	 *
	 * @return Role
	 */
	public function getHighestRoleObject($group)
	{
		// Get all user/role xrefs for this user
		$roles   = Role_User::where('user_id', '=', $this->id)->get('role_id');
		$roleIds = array_pluck($roles, 'role_id');

		// If the user does not have the developer role
		if (!in_array(Role::DEVELOPER, $roleIds)) {
			// Make sure they have at least one role
			if (count($roleIds) > 0) {
				// Look for any role that matches the group that this user has and get the highest value
				$role = Role::where_in('id', $roleIds)->where('group', '=', $group)->order_by('value', 'desc')->first();

				// If it exists, return it
				if ($role != null) {
					return $role;
				}
			}
		} else {
			// For a developer, return the highest role in the requested group
			return Role::where('group', '=', $group)->order_by('value', 'desc')->first();
		}

		// Otherwise, they are a guest
		return Role::find(Role::FORUM_GUEST);
	}

	/**
	 * Get the user's highest role in a particular role group
	 *
	 * @param  string $group The group name of the role
	 *
	 * @return string
	 */
	public function getHighestRole($group)
	{
		return $this->getHighestRoleObject($group)->fullName;
	}

	/**
	 * Update this user's last active time.  Used for determining if they are online
	 */
	public function updateLastActive()
	{
		$this->set_attribute('lastActive', date('Y-m-d H:i:s'));
		$this->save();
	}

	/**
	 * See if there are unread posts in a certain forum board
	 *
	 * @param  int $boardId A forum board Id
	 *
	 * @return boolean
	 */
	public function checkUnreadBoard($boardId)
	{
		// Future version
		// return Forum_Board::where('id', '=', $boardId)->or_where('parent_id', '=', $boardId)->get()->unreadFlagForUser($this->id);

		// Get all parent and child boards matching the id
		$boards   = Forum_Board::where('id', '=', $boardId)->or_where('parent_id', '=', $boardId)->get('id');
		$boardIds = array_pluck($boards, 'id');

		// Get any posts within those boards
		$posts    = Forum_Post::where_in('forum_board_id', $boardIds)->get('id');

		// Make sure there are posts
		if (count($posts) > 0) {
			$postIds = array_pluck($posts, 'id');

			// See which of these posts the user has already viewed
			$viewedPosts = Forum_Post_View::where('user_id', '=', $this->id)->where_in('forum_post_id', $postIds)->get();

			// If the posts are greater than the viewed, there are new posts
			if (count($posts) > count($viewedPosts)) {
				return true;
			}
		}
		return false;
	}

	/**
	 * Get the number of unread posts
	 *
	 * @return int
	 */
	public function unreadPostCount()
	{
		// Future version
		// return Forum_Post::unreadCountForUser($this->id);

		// Get the id of all posts
		$posts = Forum_Post::all();
		if (count($posts) > 0) {
			foreach ($posts as $key => $post) {
				if ($post->board->forum_board_type_id == Forum_Board::TYPE_GM && !$this->can('GAME_MASTER')) {
					unset($posts[$key]);
				}
			}
			$postIds = array_pluck($posts, 'id');

			// See which of these the user has viewed
			$viewedPosts = Forum_Post_View::where('user_id', '=', $this->id)->where_in('forum_post_id', $postIds)->get();

			// If there are more posts than viewed posts, return the remainder
			if (count($posts) > count($viewedPosts)) {
				return count($posts) - count($viewedPosts);
			}
		}
		return 0;
	}

	/**
	 * Can the User do something
	 *
	 * @param  array|string $permissions Single permission or an array or permissions
	 *
	 * @return boolean
	 */
	public function can($permissions)
	{
		// Check if they are logged in and active
		if (Auth::check()) {
			// If the user is a developer, the answer is always true
			if (Auth::user()->is('DEVELOPER')) {
				return true;
			}

			// If any permission is not in the user's permissions, fail
			return in_array($permissions, Session::get('permissions') );
		}

		return false;
	}

	/**
	 * Is the User a Role
	 *
	 * @param  array|string  $roles A single role or an array of roles
	 *
	 * @return boolean
	 */
	public function is($roles)
	{
		// Check if they are logged in and active
		if (Auth::check()) {
			// If any role is not in the user's roles, fail
			return in_array($roles, Session::get('roles') );
		}

		return false;
	}

	/**
	 * Is the User a Role (any true)
	 *
	 * @param  array|string  $roles A single role or an array of roles
	 *
	 * @return boolean
	 */
	public function isOr($roles)
	{
	    if (Auth::check()) {
	    	// If any role is in the user's roles, pass
	        return (bool) array_intersect( (array) $roles, Session::get('roles') );
	    }

	    return false;
	}
}
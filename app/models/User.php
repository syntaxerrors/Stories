<?php

use Illuminate\Auth\UserInterface;
use Illuminate\Auth\Reminders\RemindableInterface;

class User extends BaseModel implements UserInterface, RemindableInterface
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

	/**
	 * The attributes excluded from the model's JSON form.
	 *
	 * @var array
	 */
	protected $hidden = array('password');

	/**
	 * Get the unique identifier for the user.
	 *
	 * @return mixed
	 */
	public function getAuthIdentifier()
	{
		return $this->getKey();
	}

	/**
	 * Get the password for the user.
	 *
	 * @return string
	 */
	public function getAuthPassword()
	{
		return $this->password;
	}

	/**
	 * Get the e-mail address where password reminders are sent.
	 *
	 * @return string
	 */
	public function getReminderEmail()
	{
		return $this->email;
	}

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
		return $this->belongsToMany('User_Permission_Role', 'role_users', 'user_id', 'role_id');
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
		return $this->hasMany('Message_Folder', 'user_id')->orderBy('name', 'asc');
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
     * Actions of the user through the Role Relationship
     *
     * @return Action[]
     */
	public function getActionsAttribute()
	{
		// ppd($this->roles);
		return $this->roles->actions;
	}

	/**
	 * Check for an avatar uploaded to the site, resort to gravatar if none exists, resort to no user image if no gravatar exists
	 *
	 * @return string
	 */
	public function getGravitarAttribute()
	{
		// If the user has uploaded an avatar, always use that
		if (file_exists(public_path() .'/img/avatars/'. Str::studly($this->username) .'.png')) {
			return 'img/avatars/'. Str::studly($this->username) .'.png';
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
	public function getJoinDateAttribute()
	{
		return date('F jS, Y \a\t h:ia', strtotime($this->create_date));
	}

	/**
	 * Make the last active date easier to read
	 *
	 * @return string
	 */
	public function getLastActiveReadableAttribute()
	{
		return ($this->lastActive == '0000-00-00 00:00:00' ? 'Never' : date('F jS, Y \a\t h:ia', strtotime($this->lastActive)));
	}

	/**
	 * Check if a user has a permission
	 *
	 * @param $keyName The keyname of the action you are checking
	 * @return bool
	 */
	public function checkPermission($actions)
	{
		if (Auth::user()->roles->contains(User_Permission_Role::DEVELOPER)) {
			return true;
		}

		// If the user has the permission or is a developer return true.
		return in_array($actions, $this->actions->keyName->toArray() );

		return false;
	}

	// old permission system

	/**
	 * Get the first role for this user in a particular role group
	 *
	 * @param  string $group The group name of the role
	 *
	 * @return string
	 */
	public function getFirstRole($group)
	{
		$roles   = Role::where('group', '=', $group)->get('id');
		$roleIds = array_pluck($roles, 'id');
		return Role_User::where('user_id', '=', $this->id)->whereIn('role_id', $roleIds)->first();
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
		$roles   = $this->roles;

		// If the user does not have the developer role
		if (!$roles->contains(User_Permission_Role::DEVELOPER)) {

			$roleIds = User_Permission_Role::where('group', '=', $group)->get()->id->toArray();
			// Make sure they have at least one role
			if (count($roleIds) > 0) {

				// Look for any role that matches the group that this user has and get the highest value
				$role = User_Permission_Role_User::whereIn('role_id', $roleIds)->where('user_id', $this->id)->first();

				// If it exists, return it
				if ($role != null) {
					return $role->role;
				}
			}
		} else {
			// For a developer, return the highest role in the requested group
			return User_Permission_Role::find(User_Permission_Role::DEVELOPER);
		}

		// Otherwise, they are a guest
		return User_Permission_Role::find(User_Permission_Role::GUEST);
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
		return $this->getHighestRoleObject($group)->name;
	}

	/**
	 * Update this user's last active time.  Used for determining if they are online
	 */
	public function updateLastActive()
	{
		$this->lastActive = date('Y-m-d H:i:s');
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
		$boardIds   = Forum_Board::where('id', '=', $boardId)->orWhere('parent_id', '=', $boardId)->get()->id->toArray();

		// Get any posts within those boards
		$posts    = Forum_Post::whereIn('forum_board_id', $boardIds)->get();
		$postIds  = $posts->id->toArray();

		// Make sure there are posts
		if (count($postIds) > 0) {

			// See which of these posts the user has already viewed
			$viewedPosts = Forum_Post_View::where('user_id', '=', $this->id)->whereIn('forum_post_id', $postIds)->get();

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
		// Get the id of all posts
		$posts      = Forum_Post::all();
		$postsCount = $posts->count();

		if ($postsCount > 0) {
			foreach ($posts as $key => $post) {
				if ($post->board->forum_board_type_id == Forum_Board::TYPE_GM && !$this->checkPermission('GAME_MASTER')) {
					unset($posts[$key]);
				}
			}
			$postIds = $posts->id->toArray();

			// See which of these the user has viewed
			$viewedPostCount = Forum_Post_View::where('user_id', $this->id)->whereIn('forum_post_id', $postIds)->count();

			// If there are more posts than viewed posts, return the remainder
			if ($postsCount > $viewedPostCount) {
				return $postsCount - $viewedPostCount;
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
			return in_array($permissions, (array) Session::get('permissions') );
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
			return in_array($roles, (array) Session::get('roles') );
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
	        return (bool) array_intersect( (array) $roles, (array) Session::get('roles') );
	    }

	    return false;
	}
}
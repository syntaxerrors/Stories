<?php

use Illuminate\Auth\UserInterface;
use Illuminate\Auth\Reminders\RemindableInterface;

class User extends BaseModel implements UserInterface, RemindableInterface
{
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
	 * Check if a user has a permission
	 * 
	 * @param $keyName The keyname of the action you are checking
	 * @return bool
	 */
	public function checkPermission($keyName)
	{
		$isDeveloper = false; // $this->roles->name->has('Developer'); This is for test and later use.

		// If the user has the permission or is a developer return true.
		if ($this->actions->keyName->has($keyName) || $isDeveloper) {
			return true;
		}

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
		if (!$roles->contains(1)) {
			// Make sure they have at least one role
			if (count($roleIds) > 0) {
				$roleIds = array_pluck($roles, 'id');
				// Look for any role that matches the group that this user has and get the highest value
				$role = Role::whereIn('id', $roleIds)->where('group', '=', $group)->orderBy('value', 'desc')->first();

				// If it exists, return it
				if ($role != null) {
					return $role;
				}
			}
		} else {
			// For a developer, return the highest role in the requested group
			return Role::where('group', '=', $group)->orderBy('value', 'desc')->first();
		}

		// Otherwise, they are a guest
		return Role::where('group', '=', $group)->where('name', '=', 'Guest')->first();
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
		return $this->getHighestRoleObject($group)->fullname;
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
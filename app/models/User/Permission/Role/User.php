<?php

class User_Permission_Role_User extends BaseModel
{
	/********************************************************************
	 * Declarations
	 *******************************************************************/

	/**
	 * Table declaration
	 *
	 * @var string $table The table this model uses
	 */
	protected $table = 'role_users';

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
		'user_id'       => 'required|exists:users,id',
		'role_id'       => 'required|exists:roles,id',
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
		return $this->belongsTo('User', 'user_id');
	}

    /**
     * Role Relationship
     *
     * @return Role
     */
	public function role()
	{
		return $this->belongsTo('User_Permission_Role', 'role_id');
	}

	/********************************************************************
	 * Getter and Setter methods
	 *******************************************************************/

    /**
     * Get username
     *
     * @return string
     */
	public function getUsernameAttribute()
	{
		return ucwords($this->user()->first()->username);
	}

    /**
     * Get role name
     *
     * @return string
     */
	public function getRoleNameAttribute()
	{
		return ucwords($this->role()->first()->name);
	}

	/********************************************************************
	 * Extra Methods
	 *******************************************************************/
}
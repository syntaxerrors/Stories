<?php
use Awareness\Aware;

class Membership extends Aware
{
	/********************************************************************
	 * Declarations
	 *******************************************************************/

	/**
	 * Table declaration
	 *
	 * @var string $table The table this model uses
	 */
	public static $table = 'role_users';

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
		'user_id'       => 'required|exists:users,id|composite_unique:role_users,user_id,role_id',
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
		return $this->belongsTo('Role', 'role_id');
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
	public function getRole_nameAttribute()
	{
		return ucwords($this->role()->first()->group) .' - '. ucwords($this->role()->first()->name);
	}

	/********************************************************************
	 * Extra Methods
	 *******************************************************************/
}
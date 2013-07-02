<?php

class User_Permission_Action_Role extends BaseModel
{
	/********************************************************************
	 * Declarations
	 *******************************************************************/

	/**
	 * Table declaration
	 *
	 * @var string $table The table this model uses
	 */
	protected $table = 'action_roles';

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
		'action_id' => 'required|exists:actions,id',
		'role_id'   => 'required|exists:roles,id',
	);

	/********************************************************************
	 * Relationships
	 *******************************************************************/

    /**
     * User_Permission_Action Relationship
     *
     * @return User_Permission_Action
     */
	public function action()
	{
		return $this->belongsTo('User_Permission_Action', 'permission_id');
	}

    /**
     * User_Permission_Role Relationship
     *
     * @return User_Permission_Role
     */
	public function role()
	{
		return $this->belongsTo('User_Permission_Role', 'role_id');
	}

	/********************************************************************
	 * Getter and Setter methods
	 *******************************************************************/

    /**
     * Get action name
     *
     * @return string
     */
	public function getAction_nameAttribute()
	{
		return ucwords($this->action()->first()->name);
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
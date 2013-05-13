<?php

class Role extends BaseModel
{
	/********************************************************************
	 * Declarations
	 *******************************************************************/

	/**
	 * Table declaration
	 *
	 * @var string $table The table this model uses
	 */
	protected $table = 'permissions';

	const DEVELOPER   = 1;
	const SV_GUEST    = 2;
	const FORUM_GUEST = 5;

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
		'group'       => 'required|max:200',
		'name'        => 'required|max:200',
		'keyName'     => 'required|max:200',
		'value'       => 'required',
	);

	/********************************************************************
	 * Relationships
	 *******************************************************************/

    /**
     * User Relationship
     *
     * @return User[]
     */
	public function users()
	{
		return $this->belongsToMany('User', 'role_users');
	}

    /**
     * Membership Relationship
     *
     * @return Membership[]
     */
	public function memberships()
	{
		return $this->hasMany('Membership', 'role_id');
	}

    /**
     * Permission Relationship
     *
     * @return Permission[]
     */
	public function permissions()
	{
		return $this->belongsToMany('Permission', 'permission_roles');
	}

    /**
     * Rule Relationship
     *
     * @return Rule[]
     */
	public function rules()
	{
		return $this->hasMany('Rule', 'role_id');
	}

	/********************************************************************
	 * Getter and Setter methods
	 *******************************************************************/

	/**
	 * Combine the group name and role name to create a full role name
	 *
	 * @return string
	 */
	public function getFullNameAttribute()
	{
		return $this->group .' - '. $this->name;
	}

	/**
	 * Combine the group name and value to create a sortable string
	 *
	 * @return string
	 */
	public function getGroupValueAttribute()
	{
		return $this->group .' - '. $this->value;
	}

	/********************************************************************
	 * Extra Methods
	 *******************************************************************/
	/**
	 * Used to delete any attached data
	 */
	public function delete()
	{
		if (count($this->memberships) > 0) {
			foreach ($this->memberships as $user) {
				$user->delete();
			}
		}
		if (count($this->rules) > 0) {
			foreach ($this->rules as $permission) {
				$permission->delete();
			}
		}

		return parent::delete();
	}
}
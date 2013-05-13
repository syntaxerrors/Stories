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
	public static $table = 'permission_roles';

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
		'permission_id' => 'required|exists:permissions,id|composite_unique:permission_roles,permission_id,role_id',
		'role_id'       => 'required|exists:roles,id',
	);

	/********************************************************************
	 * Relationships
	 *******************************************************************/

    /**
     * Permission Relationship
     *
     * @return Permission
     */
	public function permission()
	{
		return $this->belongsTo('Permission', 'permission_id');
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
	public function getPermission_nameAttribute()
	{
		return ucwords($this->permission()->first()->name);
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
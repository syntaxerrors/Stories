<?php

class Permission extends BaseModel
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
		'name'        => 'required|max:200',
		'keyName'     => 'required|max:200',
	);

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
		return $this->belongsToMany('Role', 'permission_roles');
	}

	/********************************************************************
	 * Getter and Setter methods
	 *******************************************************************/

	/********************************************************************
	 * Extra Methods
	 *******************************************************************/
	/**
	 * Used to delete any attached data
	 */
	public function delete()
	{
		if (count($this->roles) > 0) {
			foreach ($this->roles as $role) {
				$role->delete();
			}
		}

		return parent::delete();
	}
}
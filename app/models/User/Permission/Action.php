<?php

class User_Permission_Action extends BaseModel
{
    /**
     * Table declaration
     *
     * @var string $table The table this model uses
     */
    protected $table = 'actions';

    protected $guarded = array();

    public static $rules = array();

    public function roles()
    {
        return $this->belongsToMany('User_Permission_Role', 'action_roles', 'action_id', 'role_id');
    }
}
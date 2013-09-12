<?php

class User_Preference extends BaseModel 
{
    /********************************************************************
     * Declarations
     *******************************************************************/

    /**
     * Table declaration
     *
     * @var string $table The table this model uses
     */
    protected $table = 'preferences';


    /********************************************************************
     * Relationships
     *******************************************************************/

    /**
     * User Relationship
     *
     * @return User
     */
    public function users()
    {
        return $this->belongsToMany('User', 'preferences_users', 'user_id', 'preference_id');
    }
}
<?php

class User_Preference_User extends BaseModel
{
    /********************************************************************
     * Declarations
     *******************************************************************/

    /**
     * Table declaration
     *
     * @var string $table The table this model uses
     */
    protected $table = 'preferences_users';


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
     * Preference Relationship
     *
     * @return User_Preference
     */
    public function preference()
    {
        return $this->belongsTo('User_Preference', 'preference_id');
    }
}

<?php

use Awareness\Aware\Model;

class BaseModel extends Model {

    /**
     * Order by created_at ascending scope
     *
     * @param array $query The current query to append to
     */
    public function scopeOrderByCreatedAsc($query)
    {
        return $query->orderBy('created_at', 'asc');
    }

    /**
     * Order by name ascending scope
     *
     * @param array $query The current query to append to
     */
    public function scopeOrderByNameAsc($query)
    {
        return $query->orderBy('name', 'asc');
    }

    /**
     * Get human readable created_at column
     *
     * @return string
     */
    public function getCreatedAtAttribute($value)
    {
        return date('F jS, Y \a\t h:ia', strtotime($value));
    }

    /**
     * Use the custom collection that allows tapping
     * 
     * @return Utility_Collection[]
     */
    public function newCollection(array $models = array())
    {
        return new Utility_Collection($models);
    }

    public function getNameAttribute($value)
    {
        return stripslashes($value);
    }

    /********************************************************************
     * Extra Methods
     *******************************************************************/
}
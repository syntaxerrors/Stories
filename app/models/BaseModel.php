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
     * Get only active rows
     *
     * @param array $query The current query to append to
     */
    public function scopeActive($query)
    {
        return $query->where('activeFlag', 1);
    }

    /**
     * Get only inactive rows
     *
     * @param array $query The current query to append to
     */
    public function scopeInactive($query)
    {
        return $query->where('activeFlag', 0);
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

    /**
     * Allow id to be called
     *
     * @return int|string
     */
    public function getIdAttribute($value)
    {
        if (isset($this->uniqueId)) {
            return $this->uniqueId;
        }

        return $value;
    }

    /********************************************************************
     * Extra Methods
     *******************************************************************/
}
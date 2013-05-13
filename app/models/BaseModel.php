<?php

use Awareness\Aware;

class BaseModel extends Aware {

	/********************************************************************
	 * Scopes
	 *******************************************************************/

    /**
     * Active scope
     *
     * @param array $query The current query to append to
     */
	public function scopeActive($query)
	{
		return $query->where('activeFlag', '=', 1);
	}

	/********************************************************************
	 * Getter and Setter methods
	 *******************************************************************/

	public function getCreatedAtAttribute($value)
	{
		return date('F jS, Y \a\t h:ia', strtotime($value));
	}
}
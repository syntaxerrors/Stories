<?php

class Team_Member extends BaseModel {
	/********************************************************************
	 * Declarations
	 *******************************************************************/

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
		'team_id'   => 'required|exists:teams,id|composite_unique:team_members,team_id,member_id',
		'member_id' => 'required|exists:members,id',
	);

	/********************************************************************
	 * Scopes
	 *******************************************************************/

	/********************************************************************
	 * Relationships
	 *******************************************************************/

    /**
     * Team Relationship
     *
     * @return Team
     */
	public function team()
	{
		return $this->belongsTo('Team');
	}

    /**
     * Member Relationship
     *
     * @return Member
     */
	public function member()
	{
		return $this->belongsTo('Member');
	}

	/********************************************************************
	 * Getter and Setter methods
	 *******************************************************************/

	/********************************************************************
	 * Extra Methods
	 *******************************************************************/
}
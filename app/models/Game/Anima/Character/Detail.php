<?php

class Game_Anima_Character_Detail extends BaseModel
{
	/********************************************************************
	 * Declarations
	 *******************************************************************/
	public static $table  = 'anima_character_details';

	/********************************************************************
	 * Aware validation rules
	 *******************************************************************/
	public static $rules = array(
		'characterMorphId'    => 'required',
		'characterMorphType'  => 'required',
		'anima_magic_type_id' => 'required|exists:anima_magic_types,uniqueId',
	);

	/********************************************************************
	 * Relationships
	 *******************************************************************/
	public function characterable()
	{
		return $this->morphTo();
	}
	public function magicType()
	{
		return $this->belongsTo('Game_Anima_Magic_Type', 'anima_magic_type_id');
	}

	/********************************************************************
	 * Getter and Setter methods
	 *******************************************************************/

	/********************************************************************
	 * Extra Methods
	 *******************************************************************/
}
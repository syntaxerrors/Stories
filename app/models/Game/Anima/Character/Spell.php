<?php

class Game_Anima_Character_Spell extends BaseModel
{
	/********************************************************************
	 * Declarations
	 *******************************************************************/
	public static $table  = 'anima_character_details';

	/********************************************************************
	 * Aware validation rules
	 *******************************************************************/
	public static $rules = array(
		'characterMorphId'     => 'required',
		'characterMorphType'   => 'required',
		'anima_magic_spell_id' => 'required|exists:anima_magic_spells,uniqueId',
	);

	/********************************************************************
	 * Relationships
	 *******************************************************************/
	public function characterable()
	{
		return $this->morphTo();
	}
	public function spell()
	{
		return $this->belongsTo('Game_Anima_Magic_Spell', 'anima_magic_spell_id');
	}

	/********************************************************************
	 * Getter and Setter methods
	 *******************************************************************/

	/********************************************************************
	 * Extra Methods
	 *******************************************************************/
}
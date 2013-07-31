<?php

class Game_Anima_Magic_Tree extends BaseModel
{
	/********************************************************************
	 * Declarations
	 *******************************************************************/
	public static $table  = 'anima_magic_trees';
	protected $primaryKey = 'uniqueId';
	public $incrementing  = false;

	/********************************************************************
	 * Aware validation rules
	 *******************************************************************/
	public static $rules = array(
		'name'                => 'required|max:200',
		'anima_magic_type_id' => 'required|exists:anima_magic_types,uniqueId',
		'character_id'        => 'required|exists:characters,uniqueId',
	);

	/********************************************************************
	 * Relationships
	 *******************************************************************/
	public function type()
	{
		return $this->belongsTo('Game_Anima_Magic_Type', 'anima_magic_type_id');
	}
	public function character()
	{
		return $this->belongsTo('Game_Character', 'character_id');
	}
	public function spells()
	{
		return $this->hasMany('Game_Anima_Magic_Spell', 'anima_magic_tree_id');
	}

	/********************************************************************
	 * Getter and Setter methods
	 *******************************************************************/

	/********************************************************************
	 * Extra Methods
	 *******************************************************************/
}
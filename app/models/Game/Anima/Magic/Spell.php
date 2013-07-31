<?php

class Game_Anima_Magic_Spell extends BaseModel
{
	/********************************************************************
	 * Declarations
	 *******************************************************************/
	public static $table  = 'anima_magic_spells';
	protected $primaryKey = 'uniqueId';
	public $incrementing  = false;

	/********************************************************************
	 * Aware validation rules
	 *******************************************************************/
	public static $rules = array(
		'name'                => 'required|max:200',
		'anima_magic_tree_id' => 'required|exists:anima_magic_trees,uniqueId',
		'character_id'        => 'required|exists:characters,uniqueId',
		'game_attribute_id'   => 'required|exists:game_attributes,id',
	);

	/********************************************************************
	 * Relationships
	 *******************************************************************/
	public function tree()
	{
		return $this->belongsTo('Game_Anima_Magic_Tree', 'anima_magic_tree_id');
	}
	public function attr()
	{
		return $this->belongsTo('Game_Attribute', 'game_attribute_id');
	}
	public function character()
	{
		return $this->belongsTo('Game_Character', 'character_id');
	}
	public function characters()
	{
		return $this->hasMany('Game_Anima_Character_Spell', 'anima_magic_spell_id');
	}

	/********************************************************************
	 * Getter and Setter methods
	 *******************************************************************/

	/********************************************************************
	 * Extra Methods
	 *******************************************************************/
}
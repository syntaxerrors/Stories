<?php

class Game_Item_Npc extends BaseModel
{
	/********************************************************************
	 * Declarations
	 *******************************************************************/
	public static $table  = 'npc_items';

	/********************************************************************
	 * Aware validation rules
	 *******************************************************************/
	public static $rules = array(
		'npcMorphId'   => 'required',
		'npcMorphType' => 'required',
		'game_item_id' => 'required|exists:game_items,uniqueId',
	);

	/********************************************************************
	 * Relationships
	 *******************************************************************/
	public function npcable()
	{
		return $this->morphTo();
	}
	public function item()
	{
		return $this->belongsTo('Game_Item', 'game_item_id');
	}

	/********************************************************************
	 * Getter and Setter methods
	 *******************************************************************/

	/********************************************************************
	 * Extra Methods
	 *******************************************************************/
}
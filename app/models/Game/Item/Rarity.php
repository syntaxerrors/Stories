<?php

class Game_Item_Rarity extends BaseModel
{
	/********************************************************************
	 * Declarations
	 *******************************************************************/
	public static $table = 'game_item_rarities';

	/********************************************************************
	 * Aware validation rules
	 *******************************************************************/
	public static $rules = array(
		'name'  => 'required|max:200',
		'color' => 'required|max:200',
	);

	/********************************************************************
	 * Relationships
	 *******************************************************************/
	public function items()
	{
		return $this->hasMany('Game_Item', 'game_item_rarity_id');
	}

	/********************************************************************
	 * Getter and Setter methods
	 *******************************************************************/

	/**
	 * Get the name of the rarity
	 *
	 * @return string
	 */
	public function getColorExampleAttribute()
	{
		return '<span style="background: '. $this->color .'; padding: 2px;"> '. $this->color .'</span>';
	}
	/********************************************************************
	 * Extra Methods
	 *******************************************************************/
}
<?php

class Game_Anima_Magic_Type extends BaseModel
{
	/********************************************************************
	 * Declarations
	 *******************************************************************/
	public static $table  = 'anima_magic_types';
	protected $primaryKey = 'uniqueId';
	public $incrementing  = false;

	/********************************************************************
	 * Aware validation rules
	 *******************************************************************/
	public static $rules = array(
		'name' => 'required|max:200',
	);

	/********************************************************************
	 * Relationships
	 *******************************************************************/
	public function trees()
	{
		return $this->hasMany('Game_Anima_Magic_Tree', 'anima_magic_type_id');
	}
	public function characterDetails()
	{
		return $this->hasMany('Game_Anima_Character_Detail', 'anima_magic_type_id');
	}

	/********************************************************************
	 * Getter and Setter methods
	 *******************************************************************/

	/**
	 * Human readable version of the userCreatedTreesFlag
	 *
	 * @return string
	 */
	public function getUserCreatedTreesAttribute()
	{
		return ($this->userCreatedTreesFlag == 1 ? 'Yes' : 'No');
	}
	
	/**
	 * Get the total number of spells in this type
	 *
	 * @return array
	 */
	public function getSpellCountAttribute()
	{
		// Get all the trees for this type
		$trees  = $this->trees()->get();
		$spells = array();

		if (count($trees) > 0) {
			foreach ($trees as $tree) {
				if (count($tree->spells) > 0) {
					foreach ($tree->spells as $spell) {
						array_push($spells, $spell);
					}
				}
			}
		}
		return count($spells);
	}

	/********************************************************************
	 * Extra Methods
	 *******************************************************************/
}
<?php

namespace Game\Template\Magic;
use Laravel;
use Aware;

class Type extends Aware
{
	/********************************************************************
	 * Declarations
	 *******************************************************************/
	public static $table = 'game_template_magic_types';

	/********************************************************************
	 * Aware validation rules
	 *******************************************************************/
	public static $rules = array(
		'game_template_id'             => 'required|exists:game_templates,id',
		'name'                         => 'required|max:200',
	);

	/********************************************************************
	 * Relationships
	 *******************************************************************/
	public function template()
	{
		return $this->belongs_to('Game\Template', 'game_template_id');
	}
	public function trees()
	{
		return $this->has_many('Game\Template\Magic\Tree', 'game_template_magic_type_id')->order_by('name', 'asc');
	}

	/********************************************************************
	 * Getter and Setter methods
	 *******************************************************************/
	
	/**
	 * Make the created_at easier to read
	 *
	 * @return string
	 */
	public function get_created_at()
	{
		return date('F jS, Y \a\t h:ia', strtotime($this->get_attribute('created_at')));
	}

	/**
	 * Human readable version of the userCreatedTreesFlag
	 *
	 * @return string
	 */
	public function get_user_created_trees()
	{
		return ($this->get_attribute('userCreatedTreesFlag') == 1 ? 'Yes' : 'No');
	}
	
	/**
	 * Get the total number of spells in this type
	 *
	 * @return array
	 */
	public function get_spellCount()
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
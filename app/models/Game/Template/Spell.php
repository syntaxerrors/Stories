<?php

namespace Game\Template;
use Laravel;
use Aware;

class Spell extends Aware
{
	/********************************************************************
	 * Declarations
	 *******************************************************************/
	public static $table = 'game_template_spells';

	/********************************************************************
	 * Aware validation rules
	 *******************************************************************/
	public static $rules = array(
		'game_template_id'             => 'required|exists:game_templates,id',
		'game_template_magic_tree_id'  => 'required|exists:game_template_magic_trees,id',
		'game_template_attribute_id'   => 'required|exists:game_template_attributes,id',
		'character_id'                 => 'exists:characters,id',
		'name'                         => 'required|max:200',
	);

	/********************************************************************
	 * Relationships
	 *******************************************************************/
	public function template()
	{
		return $this->belongs_to('Game\Template', 'game_template_id');
	}
	public function tree()
	{
		return $this->belongs_to('Game\Template\Magic\Tree', 'game_template_magic_tree_id');
	}
	public function type()
	{
		return $this->tree()->type;
	}
	public function gameAttribute()
	{
		return $this->belongs_to('Game\Template\Attribute', 'game_template_attribute_id');
	}
	public function character()
	{
		return $this->belongs_to('Character', 'character_id');
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
	 * Get the magic tree name
	 *
	 * @return string
	 */
	public function get_tree_name()
	{
		return $this->tree()->first()->name;
	}

	/**
	 * Get the attribute name
	 *
	 * @return string
	 */
	public function get_attribute_name()
	{
		return $this->gameAttribute()->first()->name;
	}

	/********************************************************************
	 * Extra Methods
	 *******************************************************************/
}
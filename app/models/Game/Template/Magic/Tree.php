<?php

namespace Game\Template\Magic;
use Laravel;
use Aware;

class Tree extends Aware
{
	/********************************************************************
	 * Declarations
	 *******************************************************************/
	public static $table = 'game_template_magic_trees';

	/********************************************************************
	 * Aware validation rules
	 *******************************************************************/
	public static $rules = array(
		'game_template_id'             => 'required|exists:game_templates,id',
		'game_template_magic_type_id'  => 'required|exists:game_template_magic_types,id',
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
	public function type()
	{
		return $this->belongs_to('Game\Template\Magic\Type', 'game_template_magic_type_id');
	}
	public function character()
	{
		return $this->belongs_to('Character', 'character_id');
	}
	public function spells()
	{
		return $this->has_many('Game\Template\Spell', 'game_template_magic_tree_id');
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
	 * Get the name of the type
	 *
	 * @return string
	 */
	public function get_type_name()
	{
		return $this->type()->first()->name;
	}

	/********************************************************************
	 * Extra Methods
	 *******************************************************************/
}
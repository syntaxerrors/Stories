<?php

namespace Game;
use Laravel;
use Aware;

class Template extends Aware
{
	/********************************************************************
	 * Declarations
	 *******************************************************************/
	public static $table = 'game_templates';

	/********************************************************************
	 * Aware validation rules
	 *******************************************************************/
	public static $rules = array(
		'name' => 'required|max:200',
	);

	/********************************************************************
	 * Relationships
	 *******************************************************************/
	public function games()
	{
		return $this->has_many('Game', 'id');
	}

	public function classes()
	{
		return $this->has_many('Game\Template\GameClass', 'game_template_id');
	}

	public function traits()
	{
		return $this->has_many('Game\Template\Trait', 'game_template_id')->order_by('name', 'asc');
	}

	public function appearances()
	{
		return $this->has_many('Game\Template\Appearance', 'game_template_id');
	}

	public function stats()
	{
		return $this->has_many('Game\Template\BaseStat', 'game_template_id');
	}

	public function gameAttributes()
	{
		return $this->has_many('Game\Template\Attribute', 'game_template_id');
	}

	public function attributeModifiers()
	{
		return $this->has_many('Game\Template\Attribute\Modifier', 'game_template_id');
	}

	public function secondaryAttributes()
	{
		return $this->has_many('Game\Template\SecondaryAttribute', 'game_template_id');
	}

	public function skills()
	{
		return $this->has_many('Game\Template\Skill', 'game_template_id');
	}

	public function magicTypes()
	{
		return $this->has_many('Game\Template\Magic\Type', 'game_template_id');
	}

	public function magicTrees()
	{
		return $this->has_many('Game\Template\Magic\Tree', 'game_template_id');
	}

	public function spells()
	{
		return $this->has_many('Game\Template\Spell', 'game_template_id');
	}

	public function inventory()
	{
		return $this->has_many('Game\Template\Inventory', 'game_template_id');
	}

	public function currency()
	{
		return $this->has_many('Game\Template\Currency', 'game_template_id');
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

	/********************************************************************
	 * Extra Methods
	 *******************************************************************/
}
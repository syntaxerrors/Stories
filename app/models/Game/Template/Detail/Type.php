<?php

namespace Game\Template\Detail;
use Laravel;
use Aware;

class Type extends Aware
{
	/********************************************************************
	 * Declarations
	 *******************************************************************/
	public static $table = 'game_template_detail_types';

	/********************************************************************
	 * Aware validation rules
	 *******************************************************************/
	public static $rules = array(
		'name' => 'required|max:200',
		'type' => 'required|max:200',
	);

	/********************************************************************
	 * Relationships
	 *******************************************************************/
	public function appearances()
	{
		return $this->has_many('Game\Template\Appearance', 'game_template_detail_type_id');
	}
	public function baseStats()
	{
		return $this->has_many('Game\Template\BaseStat', 'game_template_detail_type_id');
	}
	public function gameAttributes()
	{
		return $this->has_many('Game\Template\Attribute', 'game_template_detail_type_id');
	}
	public function secondaryAttributes()
	{
		return $this->has_many('Game\Template\SecondaryAttribute', 'game_template_detail_type_id');
	}
	public function skills()
	{
		return $this->has_many('Game\Template\Skill', 'game_template_detail_type_id');
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
<?php

namespace Game\Template;
use Laravel;
use Aware;

class Trait extends Aware
{
	/********************************************************************
	 * Declarations
	 *******************************************************************/
	public static $table = 'game_template_traits';

	/********************************************************************
	 * Aware validation rules
	 *******************************************************************/
	public static $rules = array(
		'game_template_id' => 'required|exists:game_templates,id',
		'name'             => 'required|max:200',
		'minimumValue'     => 'required',
		'maximumValue'     => 'required',
	);

	/********************************************************************
	 * Relationships
	 *******************************************************************/
	public function template()
	{
		return $this->belongs_to('Game\Template', 'game_template_id');
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

	/**
	 * Human readable version of the advantageFlag
	 *
	 * @return string
	 */
	public function get_type()
	{
		return ($this->get_attribute('advantageFlag') == 1 ? 'Advantage' : 'Disadvantage');
	}

	/**
	 * Used to make the headers look nice on Template/Manage
	 *
	 * @return string
	 */
	public function get_minimum()
	{
		return $this->get_attribute('minimumValue');
	}

	/**
	 * Used to make the headers look nice on Template/Manage
	 *
	 * @return string
	 */
	public function get_maximum()
	{
		return $this->get_attribute('maximumValue');
	}

	/********************************************************************
	 * Extra Methods
	 *******************************************************************/
}
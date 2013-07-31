<?php

namespace Character;
use Laravel;
use Aware;

class Trait extends Aware
{
	/********************************************************************
	 * Declarations
	 *******************************************************************/
	public static $table = 'character_traits';

	/********************************************************************
	 * Aware validation rules
	 *******************************************************************/
	public static $rules = array(
		'character_id'           => 'required|exists:characters,id',
		'game_template_trait_id' => 'required|exists:game_template_traits,id',
	);

	/********************************************************************
	 * Relationships
	 *******************************************************************/
	public function character()
	{
		return $this->belongs_to('Character', 'character_id');
	}
	public function gameTrait()
	{
		return $this->belongs_to('Game\Template\Trait', 'game_template_trait_id');
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
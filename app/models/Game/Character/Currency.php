<?php

namespace Character;
use Laravel;
use Aware;

class Currency extends Aware
{
	/********************************************************************
	 * Declarations
	 *******************************************************************/
	public static $table = 'character_currency';

	/********************************************************************
	 * Aware validation rules
	 *******************************************************************/
	public static $rules = array(
		'character_id'               => 'required|exists:characters,id',
		'game_template_currency_id'  => 'required|exists:game_template_currency,id',
	);

	/********************************************************************
	 * Relationships
	 *******************************************************************/
	public function character()
	{
		return $this->belongs_to('Character', 'character_id');
	}
	public function gameCurrency()
	{
		return $this->belongs_to('Game\Template\Currency', 'game_template_currency_id');
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
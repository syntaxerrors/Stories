<?php

namespace Character;
use Game;
use Aware;

class Attribute extends Aware
{
	/**
	 * Declarations
	 */
	public static $table = 'character_attributes';

	/**
	 * Aware validation rules
	 */
	public static $rules = array(
		'character_id'      => 'required|exists:characters,id',
		'game_template_attribute_id' => 'required|exists:game_template_attributes,id',
	);

	/**
	 * Getter and Setter methods
	 */
	public function get_created_at()
	{
		return date('F jS, Y \a\t h:ia', strtotime($this->get_attribute('created_at')));
	}
	public function get_modifier()
	{
		if ($this->get_attribute('value') > 0) {
			return Game\Template\Attribute\Modifier::where('value', '=', $this->get_attribute('value'))->first()->modifier;
		}

		return null;
	}

	/**
	 * Relationships
	 */
	public function gameAttribute()
	{
		return $this->belongs_to('Game\Template\Attribute', 'game_template_attribute_id');
	}

	public function character()
	{
		return $this->belongs_to('Character', 'character_id');
	}

	/**
	 * Extra Methods
	 */
}
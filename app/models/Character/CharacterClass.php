<?php

namespace Character;
use Aware;

class CharacterClass extends Aware
{
	/**
	 * Declarations
	 */
	public static $table = 'character_class';

	/**
	 * Aware validation rules
	 */
	public static $rules = array(
		'character_id'           => 'required|exists:characters,id',
		'game_template_class_id' => 'required|exists:game_template_classes,id',
	);

	/**
	 * Getter and Setter methods
	 */
	public function get_created_at()
	{
		return date('F jS, Y \a\t h:ia', strtotime($this->get_attribute('created_at')));
	}

	/**
	 * Relationships
	 */
	public function gameClass()
	{
		return $this->belongs_to('Game\Template\GameClass', 'game_template_class_id');
	}

	public function character()
	{
		return $this->belongs_to('Character', 'character_id');
	}

	/**
	 * Extra Methods
	 */
}
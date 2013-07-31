<?php

namespace Character;
use Aware;

class Spell extends Aware
{
	/**
	 * Declarations
	 */
	public static $table = 'character_spells';

	/**
	 * Aware validation rules
	 */
	public static $rules = array(
		'character_id'           => 'required|exists:characters,id',
		'game_template_spell_id' => 'required|exists:game_template_spells,id',
		'buyCost'                => 'required',
		'description'            => 'required',
	);

	/**
	 * Getter and Setter methods
	 */
	public function get_created_at()
	{
		return date('F jS, Y \a\t h:ia', strtotime($this->get_attribute('created_at')));
	}

	/**
	 * Get the name of the spell
	 *
	 * @return string
	 */
	public function get_spell_name()
	{
		return $this->gameSpell()->first()->name;
	}

	/**
	 * Get the name of the character
	 *
	 * @return string
	 */
	public function get_character_name()
	{
		return $this->character()->first()->name;
	}

	/**
	 * Relationships
	 */
	public function gameSpell()
	{
		return $this->belongs_to('Game\Template\Spell', 'game_template_spell_id');
	}
	public function character()
	{
		return $this->belongs_to('Character', 'character_id');
	}

	/**
	 * Extra Methods
	 */
}
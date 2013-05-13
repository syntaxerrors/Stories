<?php

namespace Character;
use Aware;

class Skill extends Aware
{
	/**
	 * Declarations
	 */
	public static $table = 'character_skills';

	/**
	 * Aware validation rules
	 */
	public static $rules = array(
		'character_id'  => 'required|exists:characters,id',
		'game_template_skill_id' => 'required|exists:game_template_skills,id',
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
	public function gameSkill()
	{
		return $this->belongs_to('Game\Template\Skill', 'game_template_skill_id');
	}

	public function character()
	{
		return $this->belongs_to('Character', 'character_id');
	}

	/**
	 * Extra Methods
	 */
}
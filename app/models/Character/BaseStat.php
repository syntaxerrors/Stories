<?php

namespace Character;
use Laravel;
use Aware;

class BaseStat extends Aware
{
	/**
	 * Declarations
	 */
	public static $table = 'character_stats';

	/**
	 * Aware validation rules
	 */
	public static $rules = array(
		'character_id'               => 'required|exists:characters,id',
		'game_template_base_stat_id' => 'required|exists:game_template_base_stats,id',
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
	public function gameStat()
	{
		return $this->belongs_to('Game\Template\BaseStat', 'game_template_base_stat_id');
	}

	public function character()
	{
		return $this->belongs_to('Character', 'character_id');
	}

	/**
	 * Extra Methods
	 */
}
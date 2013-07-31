<?php

class Character_Appearance extends BaseModel
{
	/**
	 * Declarations
	 */
	protected $table = 'character_appearance';

	/**
	 * Aware validation rules
	 */
	public static $rules = array(
		'character_id'                => 'required|exists:characters,id',
		'game_template_appearance_id' => 'required|exists:game_template_appearances,id',
		'value'                       => 'required',
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
	public function gameAppearance()
	{
		return $this->belongsTo('Game_Template_Appearance', 'game_template_appearance_id');
	}

	public function character()
	{
		return $this->belongsTo('Character', 'character_id');
	}

	/**
	 * Extra Methods
	 */
}
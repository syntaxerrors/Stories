<?php

class Character_Class extends BaseModel
{
	/********************************************************************
	 * Declarations
	 *******************************************************************/

	/**
	 * Table declaration
	 *
	 * @var string $table The table this model uses
	 */
	protected $table = 'character_class';

	/********************************************************************
	 * Aware validation rules
	 *******************************************************************/

    /**
     * Validation rules
     *
     * @static
     * @var array $rules All rules this model must follow
     */
	public static $rules = array(
		'character_id'           => 'required|exists:characters,id',
		'game_template_class_id' => 'required|exists:game_template_classes,id',
	);

	/**
	 * Relationships
	 */
	public function gameClass()
	{
		return $this->belongsTo('Game_Template_GameClass', 'game_template_class_id');
	}

	public function character()
	{
		return $this->belongsTo('Character', 'character_id');
	}

	/**
	 * Extra Methods
	 */
}
<?php

class Game_Template_Magic_Tree extends BaseModel
{
	/********************************************************************
	 * Declarations
	 *******************************************************************/

	/**
	 * Table declaration
	 *
	 * @var string $table The table this model uses
	 */
	protected $table = 'game_template_magic_trees';

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
		'game_template_id'             => 'required|exists:game_templates,id',
		'game_template_magic_type_id'  => 'required|exists:game_template_magic_types,id',
		'character_id'                 => 'exists:characters,id',
		'name'                         => 'required|max:200',
	);

	/********************************************************************
	 * Relationships
	 *******************************************************************/
	public function template()
	{
		return $this->belongsTo('Game_Template', 'game_template_id');
	}
	public function type()
	{
		return $this->belongsTo('Game_Template_Magic_Type', 'game_template_magic_type_id');
	}
	public function character()
	{
		return $this->belongsTo('Character', 'character_id');
	}
	public function spells()
	{
		return $this->hasMany('Game_Template_Spell', 'game_template_magic_tree_id');
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
		return date('F jS, Y _a_t h:ia', strtotime($this->get_attribute('created_at')));
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

	/********************************************************************
	 * Extra Methods
	 *******************************************************************/
}
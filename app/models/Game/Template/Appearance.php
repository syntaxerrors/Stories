<?php

class Game_Template_Appearance extends BaseModel
{
	/********************************************************************
	 * Declarations
	 *******************************************************************/
	protected $table = 'game_template_appearances';

	/********************************************************************
	 * Aware validation rules
	 *******************************************************************/
	public static $rules = array(
		'game_template_id'             => 'required|exists:game_templates,id',
		'game_template_detail_type_id' => 'required|exists:game_template_detail_types,id',
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
		return $this->belongsTo('Game_Template_Detail_Type', 'game_template_detail_type_id');
	}

	/********************************************************************
	 * Getter and Setter methods
	 *******************************************************************/

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
	public function inputType($name, $value = null)
	{
		switch ($this->game_template_detail_type_id) {
			case Game_Template::TYPE_INT:
				return Form::number($name, $value, array('id' => $name, 'placeholder' => ucwords($name), 'class' => 'span10'));
			break;
			case Game_Template::TYPE_STRING:
				return Form::text($name, $value, array('id' => $name, 'placeholder' => ucwords($name), 'class' => 'span10'));
			break;
			case Game_Template::TYPE_TEXT:
				return Form::textarea($name, $value, array('id' => $name, 'placeholder' => ucwords($name), 'class' => 'span10'));
			break;
			default:
				$type         = Game_Template_Detail_Type::find($this->game_template_detail_type_id);
				$options      = explode('|', $type->type);
				$optionsArray = array();
				foreach ($options as $option) {
					$optionsArray[$option] = ucwords($option);
				}
				return Form::select($name, $optionsArray, array($value), array('id' => $name, 'class' => 'span10'));
			break;
		}
	}
}
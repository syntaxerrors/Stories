<?php

namespace Game\Template;
use Laravel;
use Aware;

class Attribute extends Aware
{
	/********************************************************************
	 * Declarations
	 *******************************************************************/
	public static $table = 'game_template_attributes';

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
		return $this->belongs_to('Game\Template', 'game_template_id');
	}
	public function type()
	{
		return $this->belongs_to('Game\Template\Detail\Type', 'game_template_detail_type_id');
	}
	public function skills()
	{
		return $this->has_many('Game\Template\Skill', 'game_template_attribute_id');
	}
	public function secondaries()
	{
		return $this->has_many('Game\Template\SecondaryAttribute', 'game_template_attribute_id');
	}
	public function spells()
	{
		return $this->has_many('Game\Template\Spell', 'game_template_attribute_id');
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
		switch ($this->get_attribute('game_template_detail_type_id')) {
			case Appearance::TYPE_INT:
				return Laravel\Form::number($name, $value, array('id' => $name, 'placeholder' => Laravel\Str::title($name)));
			break;
			case Appearance::TYPE_STRING:
				return Laravel\Form::text($name, $value, array('id' => $name, 'placeholder' => Laravel\Str::title($name)));
			break;
			case Appearance::TYPE_TEXT:
				return Laravel\Form::textarea($name, $value, array('id' => $name, 'placeholder' => Laravel\Str::title($name)));
			break;
			default:
				$type = Detail\Type::find($this->get_attribute('game_template_detail_type_id'));
				$options = explode('|', $type->type);
				$optionsArray = array();
				foreach ($options as $option) {
					$optionsArray[$option] = Laravel\Str::title($option);
				}
				return Laravel\Form::select($name, $optionsArray, array($value), array('id' => $name));
			break;
		}
	}
}
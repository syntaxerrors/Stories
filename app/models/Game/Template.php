<?php

class Game_Template extends BaseModel
{
	/********************************************************************
	 * Declarations
	 *******************************************************************/
	protected $table = 'game_templates';

	/********************************************************************
	 * Aware validation rules
	 *******************************************************************/
	public static $rules = array(
		'name' => 'required|max:200',
	);

	/********************************************************************
	 * Relationships
	 *******************************************************************/
	public function games()
	{
		return $this->hasMany('Game', 'id');
	}

	public function classes()
	{
		return $this->hasMany('Game_Template_GameClass', 'game_template_id');
	}

	public function traits()
	{
		return $this->hasMany('Game_Template_Trait', 'game_template_id')->orderBy('name', 'asc');
	}

	public function appearances()
	{
		return $this->hasMany('Game_Template_Appearance', 'game_template_id');
	}

	public function stats()
	{
		return $this->hasMany('Game_Template_BaseStat', 'game_template_id');
	}

	public function gameAttributes()
	{
		return $this->hasMany('Game_Template_Attribute', 'game_template_id');
	}

	public function attributeModifiers()
	{
		return $this->hasMany('Game_Template_Attribute_Modifier', 'game_template_id');
	}

	public function secondaryAttributes()
	{
		return $this->hasMany('Game_Template_SecondaryAttribute', 'game_template_id');
	}

	public function skills()
	{
		return $this->hasMany('Game_Template_Skill', 'game_template_id');
	}

	public function magicTypes()
	{
		return $this->hasMany('Game_Template_Magic_Type', 'game_template_id');
	}

	public function magicTrees()
	{
		return $this->hasMany('Game_Template_Magic_Tree', 'game_template_id');
	}

	public function spells()
	{
		return $this->hasMany('Game_Template_Spell', 'game_template_id');
	}

	public function inventory()
	{
		return $this->hasMany('Game_Template_Inventory', 'game_template_id');
	}

	public function currency()
	{
		return $this->hasMany('Game_Template_Currency', 'game_template_id');
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

	/********************************************************************
	 * Extra Methods
	 *******************************************************************/

    /**
     * Get the input type for a template value
     *
     * @param string      $name          The name and id the input will use.  Also used for the placeholder title
     * @param string|null $value         The default value the input will use
     * @param int|null    $detailTypeId  The Game_Template_Detail_Type id this input uses
     *
     * @return string
     */
    public function inputType($name, $value = null, $detailTypeId = null)
    {
        switch ($detailTypeId) {
            case Game_Template_Detail_Type::TYPE_INT:
                return Form::number($name, $value, array('id' => $name, 'placeholder' => ucwords($name), 'class' => 'span10'));
            break;
            case Game_Template_Detail_Type::TYPE_STRING:
                return Form::text($name, $value, array('id' => $name, 'placeholder' => ucwords($name), 'class' => 'span10'));
            break;
            case Game_Template_Detail_Type::TYPE_TEXT:
                return Form::textarea($name, $value, array('id' => $name, 'placeholder' => ucwords($name), 'class' => 'span10'));
            break;
            default:
                $type         = Game_Template_Detail_Type::find($detailTypeId);
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
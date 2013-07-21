<?php

class Game_Type extends BaseModel
{
	/********************************************************************
	 * Declarations
	 *******************************************************************/

	/**
	 * Table declaration
	 *
	 * @var string $table The table this model uses
	 */
	protected $table      = 'game_types';
	protected $primaryKey = 'uniqueId';
	public $incrementing  = false;

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
		'name' => 'required|max:200',
	);

	/********************************************************************
	 * Relationships
	 *******************************************************************/

    /**
     * Game Relationship
     *
     * @return Game[]
     */
	public function gamea()
	{
		return $this->hasMany('Game', 'game_type_id');
	}

	/********************************************************************
	 * Model events
	 *******************************************************************/

	public static function boot()
	{
		parent::boot();

		Game_Type::creating(function($object)
		{
			$object->uniqueId = parent::findExistingReferences('Game_Type');
		});
	}

	/********************************************************************
	 * Getter and Setter methods
	 *******************************************************************/

	/********************************************************************
	 * Extra Methods
	 *******************************************************************/
}
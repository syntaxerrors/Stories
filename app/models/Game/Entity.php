<?php

class Game_Entity extends BaseModel
{
	/********************************************************************
	 * Declarations
	 *******************************************************************/
	protected $primaryKey = 'uniqueId';
	public $incrementing  = false;

	/**
	 * Soft Delete users instead of completely removing them
	 *
	 * @var bool $softDelete Whether to delete or soft delete
	 */
	protected $softDelete = true;

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
		'name'         => 'required|max:200',
		'user_id'      => 'required|exists:users,uniqueId',
		'game_type_id' => 'required|exists:game_types,uniqueId',
	);

	/********************************************************************
	 * Relationships
	 *******************************************************************/

    /**
     * User Relationship
     *
     * @return User
     */
	public function user()
	{
		return $this->belongsTo('User', 'user_id');
	}

    /**
     * Game Relationship
     *
     * @return Game_Type
     */
	public function gameType()
	{
		return $this->belongsTo('Game_Type', 'game_type_id');
	}

    /**
     * Character Details Relationship
     *
     * @return Character_Detail[]
     */
	public function details()
	{
		switch ($this->gameType->keyName) {
			case 'ANIMA':
				return $this->morphMany('Game_Anima_Character_Detail', 'characterable');
			break;
		}
	}

    /**
     * Character Details Relationship
     *
     * @return Character_Detail[]
     */
	public function spells()
	{
		switch ($this->gameType->keyName) {
			case 'ANIMA':
				return $this->morphMany('Game_Anima_Character_Spells', 'characterable');
			break;
		}
	}

	/********************************************************************
	 * Model events
	 *******************************************************************/

	public static function boot()
	{
		parent::boot();

		Game_Entity::creating(function($object)
		{
			$object->uniqueId = parent::findExistingReferences('Game_Entity');
		});
	}

	/********************************************************************
	 * Getter and Setter methods
	 *******************************************************************/

	/********************************************************************
	 * Extra Methods
	 *******************************************************************/
}
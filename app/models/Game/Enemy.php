<?php

class Game_Enemy extends BaseModel
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
		'horde_id'     => 'exists:hordes,uniqueId',
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
     * Horde Relationship
     *
     * @return Game_Horde
     */
	public function horde()
	{
		return $this->belongsTo('Game_Horde', 'horde_id');
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

    /**
     * NPC Item Relationship
     *
     * @return Game_Item_Npc[]
     */
	public function loot()
	{
		return $this->morphMany('Game_Item_Npc', 'npcable');
	}

	/********************************************************************
	 * Model events
	 *******************************************************************/

	public static function boot()
	{
		parent::boot();

		Game_Enemy::creating(function($object)
		{
			$object->uniqueId = parent::findExistingReferences('Game_Enemy');
		});
	}

	/********************************************************************
	 * Getter and Setter methods
	 *******************************************************************/

	/********************************************************************
	 * Extra Methods
	 *******************************************************************/
}
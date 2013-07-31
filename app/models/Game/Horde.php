<?php

class Game_Horde extends BaseModel
{
	/********************************************************************
	 * Declarations
	 *******************************************************************/
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
		'name'         => 'required|max:200',
		'game_type_id' => 'required|exists:game_types,uniqueId',
	);

	/********************************************************************
	 * Relationships
	 *******************************************************************/

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
     * @return Horde
     */
	public function enemies()
	{
		return $this->hasMany('Game_Enemy', 'horde_id');
	}

    /**
     * NPC Item Relationship
     *
     * @return Npc_Item[]
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

		Game_Horde::creating(function($object)
		{
			$object->uniqueId = parent::findExistingReferences('Game_Horde');
		});
	}

	/********************************************************************
	 * Getter and Setter methods
	 *******************************************************************/

	/********************************************************************
	 * Extra Methods
	 *******************************************************************/
}
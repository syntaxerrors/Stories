<?php

class Game_Config extends BaseModel
{
	/********************************************************************
	 * Declarations
	 *******************************************************************/

	/**
	 * Table declaration
	 *
	 * @var string $table The table this model uses
	 */
	protected $table      = 'game_configs';
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
		'uniqueId'    => 'required|max:200',
		'name'        => 'required|max:200',
		'description' => 'required',
		'value'       => 'required',
	);

	/********************************************************************
	 * Relationships
	 *******************************************************************/

    /**
     * Game Configurations Relationship
     *
     * @return Game_Config_Game[]
     */
	public function gameConfigs()
	{
		return $this->hasMany('Game_Config_Game', 'game_config_id');
	}

	/********************************************************************
	 * Model events
	 *******************************************************************/

	public static function boot()
	{
		parent::boot();

		Game_Config::deleting(function($object)
		{
			$object->gameConfigs->each(function($gameConfig)
			{
				$gameConfig->delete();
			});
		});
	}

	/********************************************************************
	 * Getter and Setter methods
	 *******************************************************************/

	/********************************************************************
	 * Extra Methods
	 *******************************************************************/
}
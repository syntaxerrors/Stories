<?php

class Game_Config_Game extends BaseModel
{
	/********************************************************************
	 * Declarations
	 *******************************************************************/

	/**
	 * Table declaration
	 *
	 * @var string $table The table this model uses
	 */
	protected $table      = 'game_config_games';

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
		'game_id'        => 'required|exists:games,uniqueId',
		'game_config_id' => 'required|exists:game_configs,uniqueId',
		'value'          => 'required',
	);

	/********************************************************************
	 * Relationships
	 *******************************************************************/

    /**
     * Game Relationship
     *
     * @return Game[]
     */
	public function game()
	{
		return $this->belongsTo('Game', 'game_id');
	}

    /**
     * Game Config Relationship
     *
     * @return Game_Config[]
     */
	public function config()
	{
		return $this->belongsTo('Game_Config', 'game_config_id');
	}

	/********************************************************************
	 * Model events
	 *******************************************************************/

	public static function boot()
	{
		parent::boot();
	}

	/********************************************************************
	 * Getter and Setter methods
	 *******************************************************************/

	/********************************************************************
	 * Extra Methods
	 *******************************************************************/
}
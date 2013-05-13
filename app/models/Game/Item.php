<?php

namespace Game;
use Laravel;
use Aware;

class Item extends Aware
{
	/********************************************************************
	 * Declarations
	 *******************************************************************/
	public static $table = 'game_items';

	/********************************************************************
	 * Aware validation rules
	 *******************************************************************/
	public static $rules = array(
		'game_id'             => 'required|exists:games,id',
		'game_item_rarity_id' => 'required|exists:game_item_rarities,id',
		'name'                => 'required|max:200',
	);

	/********************************************************************
	 * Relationships
	 *******************************************************************/
	public function game()
	{
		return $this->belongs_to('Game', 'game_id');
	}
	public function rarity()
	{
		return $this->belongs_to('Game\Item\Rarity', 'game_item_rarity_id');
	}
	public function quests()
	{
		return $this->has_many('Game\Quest\Item', 'game_item_id');
	}
	public function character()
	{
		return $this->has_many('Chracter\Loot', 'game_item_id');
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
	 * Get the name of the game
	 *
	 * @return string
	 */
	public function get_game_name()
	{
		return $this->game()->first()->name;
	}

	/**
	 * Get the name of the rarity
	 *
	 * @return string
	 */
	public function get_rarity_name()
	{
		return '<span style="color: '. $this->rarity()->first()->color .';">'. $this->rarity()->first()->name .'</span>';
	}

	/********************************************************************
	 * Extra Methods
	 *******************************************************************/
}
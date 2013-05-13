<?php

namespace Game\Item;
use Laravel;
use Aware;

class Rarity extends Aware
{
	/********************************************************************
	 * Declarations
	 *******************************************************************/
	public static $table = 'game_item_rarities';

	/********************************************************************
	 * Aware validation rules
	 *******************************************************************/
	public static $rules = array(
		'name'  => 'required|max:200',
		'color' => 'required|max:200',
	);

	/********************************************************************
	 * Relationships
	 *******************************************************************/
	public function items()
	{
		return $this->has_many('Game\Item', 'game_item_rarity_id');
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
	 * Get the name of the rarity
	 *
	 * @return string
	 */
	public function get_color_example()
	{
		return '<span style="background: '. $this->get_attribute('color') .'; padding: 2px;"> '. $this->get_attribute('color') .'</span>';
	}
	/********************************************************************
	 * Extra Methods
	 *******************************************************************/
}
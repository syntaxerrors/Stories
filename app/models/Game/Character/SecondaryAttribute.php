<?php

namespace Character;
use Auth;
use Laravel;
use Aware;

class SecondaryAttribute extends Aware
{
	/**
	 * Declarations
	 */
	public static $table = 'character_secondary_attributes';

	/**
	 * Aware validation rules
	 */
	public static $rules = array(
		'character_id'                         => 'required|exists:characters,id',
		'game_template_secondary_attribute_id' => 'required|exists:game_template_secondary_attributes,id',
	);

	/**
	 * Getter and Setter methods
	 */
	public function get_created_at()
	{
		return date('F jS, Y \a\t h:ia', strtotime($this->get_attribute('created_at')));
	}

	/**
	 * Relationships
	 */
	public function gameSecondaryAttribute()
	{
		return $this->belongs_to('Game\Template\SecondaryAttribute', 'game_template_secondary_attribute_id');
	}

	public function character()
	{
		return $this->belongs_to('Character', 'character_id');
	}

	/**
	 * Extra Methods
	 */
	public function checkDirty()
	{
	}
	public function onSave()
	{
		if ($this->changed('value')) {
			$contents = 'Changed on: '. date('F jS, Y \a\t g:ia') ."\n";
			$contents .= 'Character: '. $this->get_attribute('chaacter_id') .' '. $this->character()->first()->name ."\n";
			$contents .= 'User: '. Auth::user()->username ."\n";
			$contents .= $this->gameSecondaryAttribute()->first()->name .': '. $this->get_attribute('value') ."\n\n";

			Laravel\File::append('/home/stygian/public_html/new_site2/secondaryAttributesLog.txt', $contents);
		}

		return true;

	}
}
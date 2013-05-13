<?php
/**
 * Nuclear Today Custom Validation
 *
 * Extra laravel validation
 *
 *
 * @author      Nuclear Today <nuke@nucleartoday.com>
 * @package     Fire Suppression
 * @subpackage	Rods
 * @version     0.1
 */

class Validator extends Laravel\Validator {

	/**
	 * Validate the uniqueness of more than one field
	 *
	 * validate a composite unique in a table
	 * Usage: composite_unique:table_name,col_1,col_2,[ignore_id]
	 * Usage: composite_unique:table_name,col_1,col_2,[ignore_id_col,ignore_id]
	 *
	 * @param string   $attribute
	 * @param string   $value
	 * @param string[] $parameters
	 *
	 * @return bool
	 */
	protected function validate_composite_unique( $attribute, $value, $parameters ){

		$table = $parameters[0];

		$comp_name_1 = $parameters[1];
		$comp_value_1 = $value;

		$comp_name_2 = $parameters[2];
		$comp_value_2 = $this->attributes[$comp_name_2];

		if ( !isset( $parameters[4] ) && isset( $parameters[3] ) ){
				$ignore_value = $parameters[3];
				$ignore_id = 'id';
		} else {
			$ignore_value = (isset($parameters[4]) ? $parameters[4] : null);
			$ignore_id = (isset($parameters[3]) ? $parameters[3] : null);
		}

		$query = $this->db()->table($table)->where($comp_name_1, '=', $comp_value_1)->where($comp_name_2, '=', $comp_value_2);

		if ($ignore_value)
		{
			$query->where($ignore_id, '<>', $ignore_value);
		}

		return $query->count() == 0;

	}

	/**
	 * Used to display error text properly
	 *
	 * @param string   $message
	 * @param string   $attribute
	 * @param string   $rule
	 * @param string[] $parameters
	 *
	 * @return string
	 */
	protected function replace_composite_unique( $message, $attribute, $rule, $parameters ){
		$message = str_replace(':composite_attribute', $parameters[2], $message);
		return str_replace(':attribute', $parameters[1], $message);
	}
}
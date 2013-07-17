<?php
/**
 * Nuclear Today Custom HTML helpers
 *
 * Extra laravel HTML methods
 *
 *
 * @author      Stygian <stygian@nucleartoday.com>
 * @package     Fire Suppression
 * @subpackage	Rods
 * @version     0.1
 */

class HTML extends Illuminate\Support\Facades\HTML {

	/**
	 * Create a link including an image
	 *
	 * @static
	 *
	 * @param string The URL the link will point to
	 * @param string The image to use (HTML::image)
	 * @param array Any attributes the link itself should have
	 * @param boolen Determine if the link is https or http
	 *
	 * @return string
	*/
	public static function linkImage($url, $imagesrc, $attributes = array(), $https = null)
	{
		$url = URL::to($url, $https);

		return '<a href="'.$url.'"'.static::attributes($attributes).'>'.$imagesrc.'</a>';
	}

	/**
	 * Create a link including twitter bootstrap icons
	 *
	 * @static
	 *
	 * @param string The URL the link will point to
	 * @param string Any classes the icon should have (icon-white, icon-ok, etc)
	 * @param string Text to show after the icon
	 * @param array Any attributes the link itself should have
	 * @param boolen Determine if the link is https or http
	 *
	 * @return string
	*/
	public static function linkIcon($url, $iconClasses, $iconText = null, $attributes = array(), $https = null)
	{
		$url = URL::to($url, $https);

		return '<a href="'.$url.'"'.static::attributes($attributes).'><i class="'.$iconClasses.'"></i> '. $iconText .'</a>';
	}

	/**
	 * Generate a HTML span.
	 *
	 * @param  string  $value
	 * @param  array   $attributes
	 * @return string
	 */
	public static function span($value, $attributes = array())
	{
		return '<span'.static::attributes($attributes).'>'.$value.'</span>';
	}
	
	/**
	 * Generate a strong element.
	 *
	 * @access	public
	 * @param	string	$data
	 * @return	string
	 */
	public static function strong($data) {
		return '<span style="font-weight: bold;">' . $data . '</span>';
	}

	/**
	 * Generate an em element.
	 *
	 * @access	public
	 * @param	string	$data
	 * @return	string
	 */
	public static function em($data) {
		return '<span style="font-style: italic;">' . $data . '</span>';
	}

	/**
	 * Generate a code element.
	 *
	 * @access	public
	 * @param	string	$data
	 * @return	string
	 */
	public static function code($data) {
		return '<code>' . e($data) . '</code>';
	}

	/**
	 * Generate a blockquote element.
	 *
	 * @access	public
	 * @param	string	$data
	 * @return	string
	 */
	public static function quote($data) {
		return '<blockquote><p>' . $data . '</p></blockquote>';
	}

	/**
	 * Generate a del element.
	 *
	 * @access	public
	 * @param	string	$data
	 * @return	string
	 */
	public static function del($data) {
		return '<del>' . $data . '</del>';
	}
	/**
	 * Generate an iframe element.
	 *
	 * @access	public
	 * @param	string	$url
	 * @param	array	$attributes
	 * @return	string
	 */
	public static function iframe($url, $attributes = array()) {
		return '<iframe src="' . $url . '"' . static::attributes($attributes) . '></iframe>';
	}
}
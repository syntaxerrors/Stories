<?php
/**
 * Helper Methods
 *
 * This class provides function helpful for development of the site.
 *
 *
 * @author      Stygian <stygian.warlock.v2@gmail.com>
 * @package     Helpers
 * @subpackage  Global
 * @version     0.1
 */

/**
 * Print Pre data.
 *
 * @param mixed $data   The data you would like to display
 * @param bool  $return Return the data instad of echoing it.
 *
 * @return string $output The data to display wrapped in pre tags.
 */
function pp($data, $return = false)
{
    $output = '<pre>';
    $output .= print_r($data, true);
    $output .= "</pre>";

    if ($return == false) {
        return $output;
    } else {
        echo $output;
    }
}

/**
 * Print Pre and die.
 *
 * @param mixed $data The data you would like to display
 *
 * @return void
 */
function ppd($data)
{
    $output = '<pre>';
    $output .= print_r($data, true);
    $output .= "</pre>";

    echo $output;
    die;
}

/**
 * Convert HTML characters to entities.
 *
 * The encoding specified in the application configuration file will be used.
 *
 * @param string[] $array
 *
 * @return string[]
 */
function e_array($array)
{
    foreach ($array as $key => $value) {
        $array[$key] = Laravel\HTML::entities($value);
    }

    return $array;
}

/**
 * Add the active class to an element if the url matchs the arguments.
 *
 * @param  string[] $controller An array of controller then action arguments to check for.
 * @param  bool     $justActive Return class="active" or just active.
 *
 * @return string
 */
function routeIs($controller, $justActive = false)
{
    if (!is_array($controller)) {
        if ($controller == '/' && URI::segment(1) == null) {
            return "class='active'";
        }
        if ( URI::segment(1) == $controller ) {
            if ($justActive) {
                return "active";
            } else {
                return "class='active'";
            }
        }
    } else {
        if ( URI::segment(1) == $controller[0]
                && URI::segment(2) == $controller[1]) {
            if ($justActive) {
                return "active";
            } else {
                return "class='active'";
            }
        }
    }

    return false;
}
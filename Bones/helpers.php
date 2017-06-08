<?php
/**
* Escape a string
*
* @param string $string
* @return string
*/
function e($string) {
    return htmlspecialchars(trim($string), ENT_QUOTES, 'UTF-8');
}

/**
* Returns the data held inside the 'with' session
*
* @param string $key
* @return mixed
*/
function old($key) {
    return isset($_SESSION['with'][$key]) ? $_SESSION['with'][$key] : null;
}

/**
 * Used to generate URl routes
 *
 * @param string $route
 * @return string
 */
function url($route) {
    return URL.$route;
}

/**
 * Prints out array/object data in a easy-to-read format
 *
 * @param mixed $data
 * @param boolean $die
 */
function dump($data, $die = false) {
    echo "<pre>", print_r($data), "</pre>";

    if($die) die;
}

/**
 * Load config data from the App container
 *
 * @param string $key
 * @return array
 */
function config($key) {
    return \Bones\Core\App::get($key);
}

<?php
/**
 * Used to flash messages to users
 *
 * @author Mehmet Uyanik <mehmet.uyanik@live.com.au>
 */

namespace Bones\Core;

class Flash {
    /**
     * Add the flash box into a view
     */
    public static function display() {
        if(isset($_SESSION['flash']) && !empty($_SESSION['flash'])) {
            include "Bones/Views/partials/flash.php";
        }
    }

    /**
     * Get the message stored in the session
     *
     * @param type $type
     * @return type
     */
    public static function get($type) {
        if(isset($_SESSION['flash'][$type]) && !empty($_SESSION['flash'][$type])) {
            return $_SESSION['flash'][$type];
        }
    }

    /**
     * Set a message to be flashed
     *
     * @param type $type
     * @param string|array $data
     */
    public static function set($type, $data) {
        if(is_array($data)) {
            foreach($data as $d) {
                $_SESSION['flash'][$type][] = $d;
            }
        } else {
            $_SESSION['flash'][$type][] = $data;
        }
    }

    /**
     * Checks to see if a message has been set
     *
     * @param type $type
     * @param type $subtype
     * @return type
     */
    public static function has($type, $subtype = null) {
        if($subtype)
            return isset($_SESSION[$type][$subtype]) && !empty($_SESSION[$type][$subtype]);
        else
            return isset($_SESSION[$type]) && !empty($_SESSION[$type]);
    }
}

<?php
/**
 * Manage session data
 *
 * @author Mehmet Uyanik <mehmet.uyanik@live.com.au>
 */

namespace Bones\Core;

use App\Controller\Controller;

class Session {
    /**
     * Check if a session exists
     *
     * @param string $type
     * @return mixed
     */
    public static function has($type) {
        return isset($_SESSION[$type]);
    }

    /**
     * Returns session data
     *
     * @param string $type
     * @return mixed
     */
    public static function get($type) {
        return $_SESSION[$type] ?: null;
    }

    /**
     * Destroy a session or multiple sessions
     *
     * @param array|string $key
     */
    public function destroy($key) {
        if(is_array($key)) {
            foreach($key as $value) {
                unset($_SESSION[$value]);
            }
        } else {
            unset($_SESSION[$key]);
        }
    }
}

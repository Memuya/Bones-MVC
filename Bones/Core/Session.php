<?php
/**
 * Manage session data
 * 
 * @author Mehmet Uyanik <mehmet.uyanik@live.com.au>
 */

namespace Bones\Core;

use App\Controller\Controller;
use App\Config\Util;

class Session {
    public static function has($type) {
        return isset($_SESSION[$type]);
    }
    
    public static function get($type) {
        return $_SESSION[$type];
    }
    
    /**
     * Checks if a user if allowed on a certain page (permissions)
     * 
     * @param type $type    Name of session I.E. $_SESSION['name']
     * @param type $value   Value of the session
     * @return boolean
     */
    public static function permission($type, $value, Controller $controller) {
        if(self::has('logged')) {
            if(self::has($type)) {
                if(!empty($value) && self::get($type) != $value) {
                    $controller->permissionDenied();
                }
            } else {
                $controller->permissionDenied();
            }
        } else {
            $controller->permissionDenied();
        }
    }
    
    /**
     * Deny access to logged in users
     * Useful for login pages
     * 
     * @param type $controller
     */
    public static function loggedIn(Controller $controller, $redirect = null) {
        if(self::has('logged')) {
            if(isset($redirect)) {
                Util::redirect($redirect);
            } else {
                $controller->permissionDenied();
            }
        }
    }
}
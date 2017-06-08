<?php
/**
 * Connect to a database via PDO
 *
 * @author Mehmet Uyanik <mehmet.uyanik@live.com.au>
 */

namespace Bones\Core;

use Bones\Core\App;

class DB {
    protected static $connected = false;
    public static $db;

    /**
    * Calls the connect() method to connect to the database when the DB object is created
    */
    public function __construct() {
        try {
            $this->connect();
        } catch(\PDOException $ex) {
            die("We could not contact the database. We apologize for any inconvenience.");
        }
    }

    /**
    * Connects to database via PDO
    */
    public function connect() {
        //allows only one active connection to the database to exist no matter how many times it is called
        if(!self::$connected) {
            //create a PDO connection to the database
            self::$db = new \PDO("mysql:host=".config('db')['host'].";dbname=".config('db')['name'], config('db')['user'], config('db')['pass'] );
            self::$db->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
            self::$db->setAttribute(\PDO::ATTR_DEFAULT_FETCH_MODE, \PDO::FETCH_OBJ);
            //set connection to true so we don't re-connect
            self::$connected = true;
        }
    }
}

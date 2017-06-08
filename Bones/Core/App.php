<?php
namespace Bones\Core;

class App {
    protected static $registry = [];

    /**
     * Bind config to the registry
     *
     * @param string $key
     * @param array $value
     */
    public static function bind($key, $value) {
        static::$registry[$key] = $value;
    }

    /**
     * Return config from the registry array
     *
     * @param string $key
     */
    public static function get($key) {
        if(!array_key_exists($key, static::$registry)) {
            throw new \Exception("Key {$key} is not bound in the container.");
        }
        return static::$registry[$key];
    }
}

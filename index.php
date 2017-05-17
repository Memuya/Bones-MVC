<?php
spl_autoload_register(function($class) {
    require_once str_replace("\\", DIRECTORY_SEPARATOR, $class).'.php';
});

use Bones\Core\DB;
use Bones\Core\Router;

session_start();
session_regenerate_id(true);
ob_start();

// Generate constants for each config value
$config = json_encode(parse_ini_file('config.ini', true));
foreach(json_decode($config) as $key => $value) {
    define(strtoupper($key), $value);
}

$db = new DB;

require_once 'Bones/helpers.php';

$route = isset($_GET['route']) && !empty($_GET['route']) ? $_GET['route'] : 'home';

try {

    $router = new Router;
    require_once 'routes.php';
    $router->dispatch($route);

} catch(\Bones\Exception\RouteNotFoundException $ex) {
    die($ex->getMessage());
} catch(\Exception $ex) {
    die($ex->getMessage());
}

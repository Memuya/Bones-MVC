<?php
use Bones\Core\DB;
use Bones\Core\App;
use Bones\Core\Router;

session_start();
session_regenerate_id(true);
ob_start();

require_once 'vendor/autoload.php';
require_once 'Bones/helpers.php';

// Load config
App::bind('db', require 'config/db.php');
App::bind('app', require 'config/app.php');

// Load database connection
$db = new DB;

$route = isset($_GET['route']) && !empty($_GET['route']) ? $_GET['route'] : 'home';

try {
    Router::load('routes.php')->dispatch($route);
} catch(\Bones\Exception\RouteNotFoundException $ex) {
    die($ex->getMessage());
} catch(\Bones\Exception\MiddlewareNotFoundException $ex) {
    die($ex->getMessage());
} catch(\Exception $ex) {
    die($ex->getMessage());
}

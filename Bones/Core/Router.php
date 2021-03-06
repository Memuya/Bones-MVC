<?php
/**
 * Routes URI requests
 *
 * @author Mehmet Uyanik <mehmet.uyanik@live.com.au>
 */
namespace Bones\Core;

use Bones\Exception\MiddlewareNotFoundException;
use Bones\Exception\RouteNotFoundException;

class Router {
    private $routes = [];

    /**
     * Load the route list
     *
     * @param string $file
     */
    public static function load($file) {
        // Required to load $router inside $file
        $router = new static;

        require_once $file;

        return $router;
    }

    /**
     * Add a route
     *
     * @param string $method        GET|POST|PUT|DELETE
     * @param string $route         The route given in the URL
     * @param string $callback      PageController@methodName
     * @param string $middleware    Name of the middleware class to check
     */
    public function add($method, $route, $callback, $middleware = null) {
        $params = [];
        $route = trim($route, '/');

        if(strpos($callback, '@') !== false) {
            $_callback = explode('@', $callback);
            $controller = $_callback[0];
            $action = $_callback[1];
        }

        /**
         * Get all parameters from route and replace
         * them with a regex pattern that matches
         * anything within the route.
         */
        preg_match_all('/{.*?}/', $route, $params);

        if(!empty($params[0])) {
            $params = str_replace(['{', '}'], '', $params[0]);
        }

        $route = preg_replace('/\/{.*?}/', '/(.+)', $route);

        // This is the only way I could get the namespace
        // to work for middleware! I don't know why..
        if(!empty($middleware)) {
            $namespace = '\\Bones\\Middleware\\';
            $namespace .= $middleware;
            $middleware = $namespace;
        }

        $this->routes[$method][$route]['controller'] = '\\Bones\\Controller\\'.$controller;
        $this->routes[$method][$route]['action']     = $action;
        $this->routes[$method][$route]['params']     = $params;
        $this->routes[$method][$route]['middleware'] = $middleware;
    }

    /**
     * Dispatch the route
     *
     * @param string $route     The route taken from the URL
     */
    public function dispatch($route) {
        $route = trim($route, '/');
        $params = [];

        // Loop through all our routes
        foreach($this->routes[$_SERVER['REQUEST_METHOD']] as $key => $routes) {

            // Check for an exact match to the route name
            if($key === $route && !count($routes['params'])) {
                $matched_route = $this->routes[$_SERVER['REQUEST_METHOD']][$key];
                break;
            }

            // Check if the amount of parameters are the same (including the route name)
            $_route = explode('/', $route);
            $_key   = explode('/', $key);

            $pattern = "/".str_replace('/', '\/', $key)."/";
            preg_match($pattern, $route, $match);

            /**
            * Found a match. Route must have the same
            * amount of parameters (that is not equal to zero,
            * or else it would have be caught in the loop above)
            */
            if(!empty($match) && count($routes['params']) !== 0 && (count($_route) === count($_key))) {
                $matched_route = $this->routes[$_SERVER['REQUEST_METHOD']][$key];

                // Remove the route name so we're left with the paramters
                 array_shift($match);
                 $params = $match;

                break;
            }
        }

        // No route found
        if(!isset($matched_route)) {
            throw new RouteNotFoundException('Route not found.');
        }

        // Check if any middleware has been registered
        $this->checkMiddleware($matched_route['middleware']);

        $controller = new $matched_route['controller'];

        return $controller->$matched_route['action'](...$params);
    }

    /**
     * Checks for any middleware registered to the route
     *
     * @param string $middleware
     */
    private function checkMiddleware($middleware) {
        if(!empty($middleware) && !class_exists($middleware, false)) {
            throw new MiddlewareNotFoundException('Middleware not found.');
        } else if(!empty($middleware)) {
            return (new $middleware())->handle();
        }
    }

    /**
     * Add a GET route
     *
     * @param string $route         The route given in the URL
     * @param string $callback      PageController@methodName
     * @param string $middleware    Name of the middleware class to check
     */
    public function get($route, $callback, $middleware = null) {
        $this->add('GET', $route, $callback, $middleware = null);
    }

    /**
     * Add a POST route
     *
     * @param string $route         The route given in the URL
     * @param string $callback      PageController@methodName
     * @param string $middleware    Name of the middleware class to check
     */
    public function post($route, $callback, $middleware = null) {
        $this->add('POST', $route, $callback, $middleware = null);
    }

    /**
     * Add a PUT route
     *
     * @param string $route         The route given in the URL
     * @param string $callback      PageController@methodName
     * @param string $middleware    Name of the middleware class to check
     */
    public function put($route, $callback, $middleware = null) {
        $this->add('PUT', $route, $callback, $middleware = null);
    }

    /**
     * Add a DELETE route
     *
     * @param string $route         The route given in the URL
     * @param string $callback      PageController@methodName
     * @param string $middleware    Name of the middleware class to check
     */
    public function delete($route, $callback, $middleware = null) {
        $this->add('DELETE', $route, $callback, $middleware = null);
    }
}

<?php
namespace App\Routes;

use App\Exceptions\RouteNotFoundException;
use Exception;

class Router{

    protected static $routes = [];


    /**
     * Get the route
     * 
     * @param String $uri
     * @param String $controller
     * @param String $method
     * @return void
     */
    public static function get($uri, $controller, $method){
        static::$routes['GET'][$uri] = [$controller, $method];
    }

    /**
     * Post the route
     * 
     * @param String $uri
     * @param String $controller
     * @param String $method
     * @return void
     */

    public static function post($uri, $controller, $method){
        static::$routes['POST'][$uri] = [$controller, $method];
    }

    /**
     * Put the route
     * 
     * @param String $uri
     * @param String $controller
     * @param String $method
     * @return void
     */

    public static function delete($uri, $controller, $method){
        static::$routes['DELETE'][$uri] = [$controller, $method];
    }
    
    

    /**
     * Run the route
     * 
     * @return void
     */
    public static function run(){

        # fetch only url without query string
        $uri = request()->uri();
        $method = request()->getRealMethod();

        
        $route = static::$routes[$method][$uri] ?? throw new RouteNotFoundException("$method / Page not found");
        $controller = $route[0] ?? throw new RouteNotFoundException("$method / Controller not found");
        $method = $route[1] ?? throw new RouteNotFoundException("$method / Controller Method not found");

        $controller = new $controller;
        
        return $controller->$method();



    }

 
}
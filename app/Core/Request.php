<?php
namespace App\Core;

class Request{

    protected $all = [];

    protected $files = [];

    protected $attributes = [];

    public function __construct()
    {
        $this->all = array_merge($_GET, $_POST);

        $this->files = $_FILES;

        foreach($this->all as $key => $value){
            $this->attributes[$key] = $value;
        }

    }

    /**
     * Get all request
     * 
     * @return Array
     */

    public function all()
    {
       return $this->all;
    }

    /**
     * Get all files
     * 
     * @return Array
     */
    public function files(String|Null $name = null)
    {
        return (is_null($name)) ?  $this->files : ($this->files[$name] ?? null);
    }

    /**
     * Get the request value
     * 
     * @param String $key
     * @return String
     */

    public function get(String $key)
    {
        return $this->all[$key] ?? null;
    }

    /**
     * Check if the request has the key
     * 
     * @param String $key
     * @return Bool
     */

    public function has(String $key): Bool
    {
        return $this->get($key) != null;
    }

    /**
     * Get the request uri
     * 
     * @return String
     */

    public function uri(){
       return explode('?', $_SERVER['REQUEST_URI'])[0];
    }

    /**
     * Check if the request uri is equal to the given path
     * 
     * @param String $path
     * @return Bool
     */

    public function is($path){
        return $this->uri() == $path;
    }

    /**
     * Check if the request wants json
     * 
     * @return Bool
     */


    public function wantsJson(): Bool
    {
        return $_SERVER['HTTP_ACCEPT'] === 'application/json';
    }

    /**
     * Check if the request is ajax
     * 
     * @return Bool
     */

    public function isAjax(): Bool
    {
        return (getallheaders()['X-Requested-With'] ?? null) == 'XMLHttpRequest';
    }

    /**
     * Get the request method
     * 
     * @return Array
     */

    public function only(Array $keys) : Array
    {
        $only = [];
        foreach($keys as $key){
            if($this->has($key)){
                $only[$key] = $this->get($key);
            }
        }
        return $only;
    }

    /**
     * Get the request method
     * 
     * @return Array
     */

    public function except(Array $keys): Array
    {
        $except = [];
        foreach($this->all as $key => $value){
            if(!in_array($key, $keys)){
                $except[$key] = $value;
            }
        }
        return $except;
    }

    /**
     * Get the request method
     * 
     * @return String
     */

    public function getRealMethod(): String
    {
        return strtoupper($_SERVER['REQUEST_METHOD']);
    }

    /**
     * Check if the request method is equal to the given method
     * 
     * @param String $method
     * @return Bool
     */

    public function isMethod(String $method): Bool
    {
        return $this->getRealMethod() === strtoupper($method);
    }


    public function __get($key)
    {
        return $this->attributes[$key] ?? null;
    }



}
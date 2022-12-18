<?php

/**
 * Get the view
 * 
 * @param String $viewname
 * @param Array $vars
 * @return void
 */

if(!function_exists('view')){
    function view($viewname, Array $vars = []){
        $view = app_path("views/{$viewname}.php", true);

        if(!file_exists($view)) throw new Exception("View not found");
        
        extract($vars);
        require_once $view;
    }
}

/**
 * Get the app path
 * 
 * @param String $cont_path
 * @param Boolean $raw
 * @return String
 */

 if(!function_exists('app_path')){
    function app_path($cont_path, $raw = false){

        $root_path = __DIR__.DIRECTORY_SEPARATOR."..".DIRECTORY_SEPARATOR;

        if($raw == true){
            return $root_path.$cont_path;
        }
        return "/".$cont_path;
    }
}

/**
 * Get the upload path
 * 
 * @param String $cont_path
 * @param Boolean $raw
 * @return String
 */

if(!function_exists('upload_path')){
    function upload_path($cont_path, $raw = false){
        return public_path("uploads/{$cont_path}", $raw);
    }
}


/**
 * Get the public path
 * 
 * @param String $cont_path
 * @param Boolean $raw
 * @return String
 */

if(!function_exists('public_path')){
    function public_path($cont_path, $raw = false){
        return app_path("public/{$cont_path}", $raw);
    }
}

/**
 * Get the asset path
 * 
 * @param String $cont_path
 * @return String
 */

if(!function_exists('asset')){
    function asset($cont_path){
        return app_path("uploads/{$cont_path}");
    }
}

/**
 * Get the request
 * 
 * @return Request
 */

if(!function_exists('request')){
    function request(){
        return new \App\Core\Request;
    }
}

/**
 * Get the response
 * 
 * @return Response
 */
if(!function_exists('response')){
    function response(){
        return new \App\Core\Response;
    }
}
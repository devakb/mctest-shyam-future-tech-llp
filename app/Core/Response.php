<?php
namespace App\Core;


class Response {


    /**
     * Redirect to the given path
     * 
     * @param String $path
     * @return void
     */
    public function json(Array $data, Int $status = 200)
    {
        header("Content-Type: application/json");
        http_response_code($status);
        echo json_encode($data);
        exit;
    }

    /**
     * Redirect to the given path
     * 
     * @param String $path
     * @return void
     */

    public function view(String $path, Array $data = [], Int $status = 200)
    {
        http_response_code($status);
        return view($path, $data);
    }


}
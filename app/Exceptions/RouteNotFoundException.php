<?php
namespace App\Exceptions;

use App\Core\Request;

class RouteNotFoundException extends \Exception
{

    public function __construct($message) {

        error_reporting(0);
        
        $this->message = $message;

    }


    public function __toString() {

        if(request()->wantsJson()){
            return response()->json([
                'message' => $this->message ?? "Page not found",
            ], 404);
        }else{
            return response()->view('errors/404', [
                'heading' => 'Page not found',
                'message' => "The page you're looking for doesn't exist.",
                'code' => 404,
            ], 404);
        }
    }


}
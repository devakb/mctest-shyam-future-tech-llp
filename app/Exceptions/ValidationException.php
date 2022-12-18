<?php
namespace App\Exceptions;

use App\Core\Request;

class ValidationException extends \Exception
{
    protected static Array|String|Null $messages = null;

    protected static Array|Null $errors = null;


    public function __construct($exmsg = null, $val = 0, \Exception $old = null) {
        error_reporting(0);
        $exmsg = 'Default';
        parent::__construct($exmsg, $val, $old);
    }

    public function __toString() {
        return response()->json([
            'message' => static::$messages ?? "Validation Error",
            'errors' => static::$errors
        ], 422);
    }

    public static function withMessages(Array|String $messages)
    {
        static::$messages = $messages;
        return new static;
    }

    public static function withErrors(Array $errors)
    {
        static::$errors = $errors;
        return new static;
    }

    public function addError(String $key, String $message)
    {
        static::$errors[$key] = $message;
        return $this;
    }

    public function hasErrors(): Bool
    {
        return static::$errors != null;
    }

    public function getErrors(): Array
    {
        return static::$errors;
    }


}
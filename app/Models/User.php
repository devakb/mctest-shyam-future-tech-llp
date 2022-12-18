<?php

namespace App\Models;

class User extends CoreModel{


    protected $fields = [
        'id',
        'name',
        'image',
        'address',
        'gender',
    ];


    /**
     * Get the file location
     * 
     * @return String
     */

     public function getFileLoc(): String
     {
         return __DIR__.DIRECTORY_SEPARATOR."csv/users.csv";
     }
   
    

}
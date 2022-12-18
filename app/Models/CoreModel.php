<?php
namespace App\Models;

abstract class CoreModel{

    protected $fields = [];
    
    public function __construct(){

        // create a csv file if not exists
        if(!file_exists($this->getFileLoc())){

            $file = fopen($this->getFileLoc(), "w");

            fputcsv($file, $this->fields);

            fclose($file);

        }

    }
    

    /**
     * Get the file location (abstract method)
     * 
     * @return String
     */

    abstract public function getFileLoc(): String;

    /**
     * Get all users
     * 
     * @param String $orderby
     * @param String $order_dir
     * @return Array
     */
    
    public function get(String $orderby = "id", String $order_dir = "asc") : Array
    {

        $file = fopen($this->getFileLoc(), "r");

        $users = [];

        while($row = fgetcsv($file)){

            $users[] = array_combine($this->fields, $row);

        }

        fclose($file);

        // remove the first row
        array_shift($users);


        // sort the array
        usort($users, function($a, $b) use ($orderby, $order_dir){

            if($order_dir == "asc"){

                return $a[$orderby] <=> $b[$orderby];

            }else{

                return $b[$orderby] <=> $a[$orderby];

            }

        });

        return $users;

    }

    /**
     * Find a user by id
     * 
     * @param Int $id
     * @return Array|null
     */

    public function find(Int $id) : Array|null
    {

        $users = $this->get();

        foreach($users as $user){

            if($user['id'] == $id){

                return $user;

            }

        }

        return null;
        

    }

    /**
     * Create a new user
     * 
     * @param Array $items
     * @return Array
     */

    public function create(Array $items) : Array
    {
            
        $file = fopen($this->getFileLoc(), "a+");

        // get the last id
        $lastId = isset($this->get("id", "desc")[0]) ? $this->get("id", "desc")[0]['id'] : 0;

        // build user according to the fields
        $items = array_intersect_key($items, array_flip($this->fields));

        // set the new id
        $items = array_merge([
            'id' => $lastId + 1,
        ], $items);

        // add the new user
        fputcsv($file, $items);

        fclose($file);

        return array_combine($this->fields, $items);
    
    }

    /**
     * Update a user
     * 
     * @param Int $id
     * @param Array $items
     * @return Array|null
     */

    public function update(Int $id, Array $items) : Array|null
    {
        $users = $this->get();
    
        $file = fopen($this->getFileLoc(), "w+");

        fputcsv($file, $this->fields);

        $updatedUser = null;

        foreach($users as $user){

            if($user['id'] == $id){

                // build user according to the fields
                $items = array_intersect_key($items, array_flip($this->fields));

                // set the new id
                $items = array_merge([
                    'id' => $id,
                ], $items);

                $user = $items;
                
                $updatedUser = $user;

            }

            fputcsv($file, $user);

        }

        fclose($file);

        return $updatedUser;
    
    }

    /**
     * Delete a user
     * 
     * @param Int $id
     * @return Array|null
     */

    public function delete(Int $id)
    {

        $users = $this->get();
    
        $file = fopen($this->getFileLoc(), "w+");

        fputcsv($file, $this->fields);

        foreach($users as $user){

            if($user['id'] != $id){
                fputcsv($file, $user);
            }

        }

        fclose($file);

        return null;

    }
}
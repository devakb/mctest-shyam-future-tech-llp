<?php

namespace App\Controllers;

use App\Models\User;
use App\Exceptions\ValidationException;

class UserController {


    /**
     * Validate the request
     *
     * @return Array
     */

    public function validated(){

        $request = request();

        $name = $request->name;
        $image = $request->files("image");
        $address = $request->address;
        $gender = $request->gender;


        $validationException = new ValidationException();

        if(!$name) $validationException->addError('name', 'The Name field is required.');
        if(!$address) $validationException->addError('address', 'The Address field is required.');
        if(!$gender) $validationException->addError('gender', 'The Gender field is required.');

        if(request()->is("/store")) {
            if(!$image) $validationException->addError('image', 'The Image field is required.');
        }else{
            if(!$request->id) $validationException->addError('id', 'The id field is required.');
        }
   
     
        if($validationException->hasErrors()){
            throw $validationException;
        }

      
        return compact('name', 'image', 'address', 'gender');
        

    }

    /**
     * Get all users
     * @return Response
     */
    public function index(){

        // To check if the request is ajax or not


        if((getallheaders()['X-Requested-With'] ?? null) == 'XMLHttpRequest'){

            $sort_field = $_REQUEST['sort_field'] ?? 'id';
            $sort_dir = $_REQUEST['sort_dir'] ?? 'desc';
           

            echo json_encode((new User())->get($sort_field, $sort_dir));
            return;
        }

        return view("users/index");
     

    }

    /**
     * Create a new user
     * @return Response
     */

    public function store(){

        $data = $this->validated();
        

        if($data['image']){
            $fileName = uniqid(floor(microtime(true) * 1000)."_")."_".$data['image']['name'];
            $fileTmpName = $data['image']['tmp_name'];

            move_uploaded_file($fileTmpName, upload_path($fileName, true));

            $data['image'] = asset($fileName);
        }


        $user = (new User)->create($data);

        return response()->json([
            'status' => true,
            'message' => 'User created successfully',
        ], 201);

    }

    /**
     * Get a user by id
     * @return Response
     */

    public function update(){

        $data = $this->validated();
        $id = $_REQUEST['id'] ?? null;

        $user = (new User)->find($id);

        if(isset($_FILES['image']) && $_FILES['image']['name'] != ""){

            if(file_exists(app_path("public/" . $user['image'], true))){ unlink(app_path("public/" .$user['image'], true)); }

            $fileName = uniqid(floor(microtime(true) * 1000)."_")."_".$data['image']['name'];
            $fileTmpName = $data['image']['tmp_name'];
            move_uploaded_file($fileTmpName, upload_path($fileName, true));
    
            $data['image'] = asset($fileName);
        }else{
            $data['image'] = $user['image'];
        }

        $user = (new User)->update($id, $data);

        return response()->json([
            'status' => true,
            'message' => 'User updated successfully',
        ], 200);
    
    }

    /**
     * Delete a user by id
     * @return Response
     */

    public function destroy(){

        $id = $_REQUEST['id'] ?? null;

        if($id == null){
            echo json_encode([
                'status' => false,
                'message' => 'Please provide user id',
            ]);
            return;
        }

        $user = (new User)->find((int) $id);

        if(file_exists(app_path("public/" . $user['image'], true))){ unlink(app_path("public/" .$user['image'], true)); }

        (new User)->delete($id);

        return response()->json([
            'status' => true,
            'message' => 'User deleted successfully',
        ], 200);

    }

}
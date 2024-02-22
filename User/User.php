<?php

require_once 'utilities/Utilities.php';
require_once 'UserModel.php';
require_once 'UserController.php';

/*
User - will handle all data involving the user
With uthentication - this will handle the user's data and send it to controller for error checking and data sending to the model
whatever the result, this will display the message to the front end
*/
class User
{

    private $contr;
    private $model;
    public $errors = [];
    public $success = [];

    // instantiation of two classes - UserController and AuthModel
    public function __construct()
    {
        $this->contr = new UserController();
        $this->model = new UserModel();
    }

    // this is a register function
    public function register(string $name, string $email, string $password)
    {
        $name = Utilities::sanitizeInput($name);
        $email = Utilities::sanitizeInput($email);
        $password = Utilities::sanitizeInput($password);


        try {
            if (UserController::emptyRegisterInput($name, $email, $password)) {
                $this->errors['error'] = "Please fill all the fields!";
            } elseif (UserController::isShort($name, $email)) {
                $this->errors['error'] = "Name and Email should be more than 2 characters!";
            } elseif (UserController::invalidEmail($email)) {
                $this->errors['error'] = "Enter a valid Email!";
            } elseif (UserController::passwordError($password)) {
                $this->errors['error'] = "Password must have at least 8 characters with Uppercase,lowercase and special characters ";
            } else {

                $user = $this->contr->isEmailExist($email);

                if ($user) {
                    $this->errors['error'] = "Email is already taken";
                } else {
                    // we will add cost for brute force prevention
                    $options = ['cost' => 12];
                    $hashed_password = password_hash($password, PASSWORD_DEFAULT, $options);
                    $this->contr->create_user($name, $email, $hashed_password);
                    $this->success["success"] = "You are now officially registered! Reloading...";
                }
            }
        } catch (PDOException $e) {
            echo json_encode("Query failed " . $e->getMessage());
        }
    }

    public function login(string $email, string $password)
    {

        $email = Utilities::sanitizeInput($email);
        $password = Utilities::sanitizeInput($password);

        try {
            if (UserController::emptyLogInput($email, $password)) {
                $this->errors["error"] = "Please fill all the fields!";
            }

            $result = $this->model->getUser($email);

            if ($this->contr->isEmailNotFound($result)) {
                $this->errors["error"] = "Incorrect credential!";
            } else if (!$this->contr->isEmailNotFound($result) && $this->contr->isPasswordWrong($password, $result["password"])) {
                $this->errors["error"] = "Incorrect credential!";
            } else {
                $this->success = array(
                    'success' => 'Login successful! Redirecting...',
                    'data' => array(
                        'name' => htmlspecialchars($result['name']),
                        "id" => $result['id']
                    ),

                );
            }
        } catch (PDOException $e) {
            echo json_encode("Query failed " . $e->getMessage());
        }
    }

    /*
    Some of the error handlers that the register function used will also be used by the updateUser function

*/
    public function update(int $id, string $name, string $email)
    {
        $id = Utilities::sanitizeInput($id);
        $name = Utilities::sanitizeInput($name);
        $email = Utilities::sanitizeInput($email);

        try {
            if (UserController::emptyUpdateInput($name, $email)) {
                $this->errors['error'] = "Please fill all the fields!";
            } elseif (UserController::isShort($name, $email)) {
                $this->errors['error'] = "Name and Email should be more than 2 characters!";
            } elseif (UserController::invalidEmail($email)) {
                $this->errors['error'] = "Enter a valid Email!";
            } else {
                $this->contr->update_user($id, $name, $email);
                $this->success["success"] = "Data Edited! Reloading...";
            }
        } catch (PDOException $e) {
            echo json_encode("Query failed " . $e->getMessage());
        }
    }

    /*
    Get User's Info by ID
    */

    public function getUserData(int $id)
    {
        $id = Utilities::sanitizeInput($id);
        try {
            $result = $this->model->getUserDataById($id);
            if ($result) {
                $this->success = $result;
            } else {
                $this->errors['error'] = "Error fetching your data";
            }
        } catch (PDOException $e) {
            echo json_encode("Query failed " . $e->getMessage());
        }
    }

    public function addUpdateUserPhoto(int $id, $photo)
    {
        $error = "";
        if (isset($photo)) {
            $id = $id;
            $photo = $photo['name'];
            $photo_temp = $_FILES['photo']['tmp_name'];
            $destination = $_SERVER['DOCUMENT_ROOT'] . '/php_files/user_uploads' . "/" . $photo;
            $fileType = strtolower(pathinfo($destination, PATHINFO_EXTENSION));
            $check = getimagesize($_FILES["photo"]["tmp_name"]);
            $status = 1;

            if ($check) {
                $status = 1;
            } else {
                // $this->error = ["status" => "invalid", "message" => "File is not an image"];
                $error = "The file is not an image";
                $status = 0;
            }
        }


        if ($_FILES["photo"]["size"] > 500000) {
            // $this->errors["error"] = "The file is too large";
            $error = "The file should not exceed 5mb";
            $status = 0;
        }
        $allowedExtensions = ["jpg", "jpeg", "png"];
        if (!in_array($fileType, $allowedExtensions)) {
            // $this->errors["error"] = "Please upload jpg,jpeg, and png files";
            $error = "The file must be jpeg, png and jpg";
            $status = 0;
        }

        if ($status == 0) {
            if ($error) {
                $this->errors["error"] = $error;
            }
        } else {
            if (move_uploaded_file($photo_temp, $destination)) {
                $this->model->updateUserPhoto($id, $photo);
                $this->success["success"] =  "Photo update success";
            } else {
                $this->errors["error"] = "Failed to update photo";
            }
        }




        // return $this->model->updateUserPhoto($id, $photo);
    }

    public function __destruct()
    {
        if ($this->errors) {
            echo json_encode($this->errors);
        } else {
            echo json_encode($this->success);
        }
    }
}

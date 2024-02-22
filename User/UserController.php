<?php

require_once 'UserModel.php';
/*
    UserController handles and send Request to the modal
    This is also where we check any possible errors
*/
class UserController
{

    private $model;

    public function __construct()
    {
        $objModel = new UserModel;
        $this->model = $objModel;
    }

    // REGISTER and UPDATE VALIDATION INPUT
    // This is for Register and Update function
    public static function emptyRegisterInput(string $name, string $email, string $password)
    {
        if (empty($name) || empty($email) || empty($password)) {
            return true;
        } else {
            return false;
        }
    }
    public static function emptyUpdateInput(string $name, string $email)
    {
        if (empty($name) || empty($email)) {
            return true;
        } else {
            return false;
        }
    }
    public static function isShort(string $name, string $email)
    {

        if (strlen($name) < 2 || strlen($email) < 2) {
            return true;
        } else {
            return false;
        }
    }

    public function isEmailExist(string $email)
    {
        if ($this->model->checkEmail($email)) {
            return true;
        } else {
            return false;
        }
    }

    public static function invalidEmail(string $email)
    {
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return true;
        } else {
            return false;
        }
    }

    public static function passwordError(string $password)
    {
        if (strlen($password) < 8 || !preg_match('/[A-Z]/', $password) || !preg_match('/[a-z]/', $password) || !preg_match('/[!@#$%^&*()_+{}|":<>?~\-=\[\];\',.\/]/', $password)) {
            return true;
        } else {
            return false;
        }
    }

    function create_user(string $name, string $email, string $password)
    {
        return $this->model->register($name, $email, $password);
    }

    // LOGIN VALIDATION INPUT
    public static function emptyLogInput(string $email, string $password)
    {
        if (empty($email) || empty($password)) {
            return true;
        } else {
            return false;
        }
    }
    // check whether an email exists before logging in
    public static function isEmailNotFound(bool|array $result)
    {
        if (!$result) {
            return true;
        } else {
            return false;
        }
    }
    // check whether password matched
    public static function isPasswordWrong(string $password, string $hashedPwd)
    {
        if (!password_verify($password, $hashedPwd)) {
            return true;
        } else {
            return false;
        }
    }

    public function get_user_data($id)
    {
        return $this->model->getUserDataById($id);
    }

    function update_user(int $id, string $name, string $email)
    {
        return $this->model->updateUser($id, $name, $email);
    }

    public function update_user_photo($id, $photo)
    {
        return $this->model->updateUserPhoto($id, $photo);
    }
}

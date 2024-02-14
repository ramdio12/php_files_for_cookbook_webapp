<?php

/*
this is where we receive our data from the frontend which was uploaded on vercel
our data will undergo checking whether there will be errors else it will be sent to the database
*/

include 'headers.php';
include 'DbConnect.php';
include_once 'register/register.model.php';
include_once 'register/register.controller.php';
include_once 'utilities/utility.php';

$objDb = new DbConnect();
$conn = $objDb->connect();


if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $name = sanitize($_POST["name"]);
    $email = sanitize($_POST["email"]);
    $password = sanitize($_POST["password"]);

    $errors = [];
    $success=[];


    if (isEmpty($name, $email, $password)) {
        $errors["error"] = "Fill all the fields required!";
    }else if(isShort($name, $email)){
        $errors["error"] = "A field or two is still empty!";
    } else if (invalidEmail($email)) {
        $errors["error"] = "Email is Invalid!";
    } else if (passwordError($password)) {
        $errors["error"] = "Password must have at least eight characters, one uppercase letter, one lowercase letter, and one special character!";
    } else if (isEmailTaken($conn, $email)) {
        $errors["error"] = "Email is already taken";
    } else {
        create_user($conn, $name, $email, $password);
        $success["success"] = "You registered successfully, Reloading...";
    }

    if ($errors) {
        echo json_encode($errors);
        
    } elseif($success) {
        
       echo json_encode($success);
    }

    
}

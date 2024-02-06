<?php

/*
this is where we receive our data from the frontend which was uploaded on vercel
our data will undergo checking whether there will be errors else it will be sent to the database
*/

include 'headers.php';
include 'DbConnect.php';
include_once 'register/register.model.php';
include_once 'register/register.controller.php';

$objDb = new DbConnect();
$conn = $objDb->connect();


if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $user = json_decode(file_get_contents('php://input'));
    $name = htmlspecialchars($_POST["name"]);
    $username = htmlspecialchars($_POST["username"]);
    $email = htmlspecialchars($_POST["email"]);
    $password = htmlspecialchars($_POST["password"]);

    $errors = [];
    $success=[];

    if (isEmpty($name, $username, $email, $password)) {
        $errors["error"] = "Fill all the fields required!";
    }else if(isShort($name, $username, $email, $password)){
        $errors["error"] = "A field or two is still empty!";
    } else if (invalidEmail($email)) {
        $errors["error"] = "Email is Invalid!";
    } else if (passwordError($password)) {
        $errors["error"] = "Password must have at least eight characters, one uppercase letter, one lowercase letter, and one special character!";
    } else if (isUsernameTaken($conn, $username)) {
        $errors["error"] = "Username is already taken!";
    } else if (isEmailTaken($conn, $email)) {
        $errors["error"] = "Email is already taken";
    } else {
        create_user($conn, $name, $username, $email, $password);
        $success["success"] = "You registered successfully, Reloading...";
    }

    if ($errors) {
        $response = $errors;
        
    } else {
        
        $response = $success;
    }

    echo json_encode($response);
}

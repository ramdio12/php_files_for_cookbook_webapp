<?php

include 'Database/headers.php';
include 'utilities/Utilities.php';
include 'User/UserController.php';

$userContr = new UserController;

$errors = [];
$success = [];

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $name = Utilities::sanitizeInput($_POST["name"]);
    $email = Utilities::sanitizeInput($_POST["email"]);
    $password = Utilities::sanitizeInput($_POST["password"]);

    try {
        if ($userContr->emptyRegisterInput($name, $email, $password)) {
            $errors['error'] = "Please fill all the fields!";
        } elseif ($userContr->isShort($name, $email)) {
            $errors['error'] = "Name and Email should be more than 2 characters!";
        } elseif ($userContr->invalidEmail($email)) {
            $errors['error'] = "Enter a valid Email!";
        } elseif ($userContr->passwordError($password)) {
            $errors['error'] = "Password must have at least 8 characters with Uppercase,lowercase and special characters ";
        } else {

            $user = $userContr->isEmailExist($email);

            if ($user) {
                $errors['error'] = "Email is already taken";
            } else {
                // we will add cost for brute force prevention
                $options = ['cost' => 12];
                $hashed_password = password_hash($password, PASSWORD_DEFAULT, $options);
                $userContr->create_user($name, $email, $hashed_password);
                $success["success"] = "You are now officially registered! Reloading...";
            }
        }
    } catch (PDOException $e) {
        echo json_encode("Query failed " . $e->getMessage());
    }
    if ($errors) {
        echo json_encode($errors);
    } elseif ($success) {
        echo json_encode($success);
    }
}

<?php

/*

after the user enter an email\
This file will fetch data from the data base. If the result returns 1 or more, then the user will be warned
*/
include 'Database/headers.php';
include 'utilities/Utilities.php';
include 'User/UserController.php';

$userContr = new UserController;


$errors = [];

// checking email, if the result is greater than 0, it means the email is already registered
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email = Utilities::sanitizeInput($_POST['email']);

    $user = $userContr->isEmailExist($email);

    if ($user) {
        $errors['error'] = "Email is already taken";
    }

    if ($errors) {
        echo json_encode($errors);
    }
}

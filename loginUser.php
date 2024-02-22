<?php

include 'Database/headers.php';
include 'utilities/Utilities.php';
require_once 'User/UserModel.php';
include 'User/UserController.php';

$model = new UserModel;
$userContr = new UserController;

$errors = [];
$success = [];

if ($_SERVER["REQUEST_METHOD"] === "POST") {


    $email = Utilities::sanitizeInput($_POST["email"]);
    $password = Utilities::sanitizeInput($_POST["password"]);

    try {
        if ($userContr->emptyLogInput($email, $password)) {
            $errors["error"] = "Please fill all the fields!";
        }

        $result = $model->getUser($email);

        if ($userContr->isEmailNotFound($result)) {
            $errors["error"] = "Incorrect credential!";
        } else if (!$userContr->isEmailNotFound($result) && $userContr->isPasswordWrong($password, $result["password"])) {
            $errors["error"] = "Incorrect credential!";
        } else {
            $success = array(
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

    if ($errors) {
        echo json_encode($errors);
    } elseif ($success) {
        echo json_encode($success);
    }
}

// if ($_SERVER["REQUEST_METHOD"] === "POST") {


//     $email = Utilities::sanitizeInput($_POST["email"]);
//     $password = Utilities::sanitizeInput($_POST["password"]);

//     try {
//         $auth->login($email, $password);
//     } catch (PDOException $e) {
//         echo json_encode("Query failed " . $e->getMessage());
//     }
// }

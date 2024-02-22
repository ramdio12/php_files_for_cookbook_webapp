<?php

include 'Database/headers.php';
include 'utilities/Utilities.php';
include 'User/UserController.php';

$userContr = new UserController;
$method = $_SERVER['REQUEST_METHOD'];

switch ($method) {
    case 'POST':
        $errors = [];
        $success = [];
        $id = Utilities::sanitizeInput($_POST["id"]);
        $name = Utilities::sanitizeInput($_POST["name"]);
        $email = Utilities::sanitizeInput($_POST["email"]);

        try {
            if ($userContr->emptyUpdateInput($name, $email)) {
                $errors['error'] = "Please fill all the fields!";
            } elseif ($userContr->isShort($name, $email)) {
                $errors['error'] = "Name and Email should be more than 2 characters!";
            } elseif ($userContr->invalidEmail($email)) {
                $errors['error'] = "Enter a valid Email!";
            } else {
                $userContr->update_user($id, $name, $email);
                $success["success"] = "Data successfully edited! Reloading...";
            }
        } catch (PDOException $e) {
            echo json_encode("Query failed " . $e->getMessage());
        }

        if ($errors) {
            echo json_encode($errors);
        } elseif ($success) {
            echo json_encode($success);
        }
        break;

    case 'GET':
        $errors = [];
        $success = [];
        $id = $_GET['id'];
        try {
            $result = $userContr->get_user_data($id);
            if ($result) {
                $success = $result;
            } else {
                $errors['error'] = "Error fetching your data";
            }
        } catch (PDOException $e) {
            echo json_encode("Query failed " . $e->getMessage());
        }

        if ($errors) {
            echo json_encode($errors);
        } elseif ($success) {
            echo json_encode($success);
        }
        break;

    default:
        break;
}

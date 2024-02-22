<?php

/*

This file is to create and get the user's recipe
*/

include 'Database/headers.php';
include 'utilities/Utilities.php';
include 'Recipe/RecipeController.php';

$recipeContr = new RecipeController;

$method = $_SERVER['REQUEST_METHOD'];

switch ($method) {
    case 'POST':
        $errors = "";
        $title = Utilities::sanitizeInput($_POST['title']);
        $description = Utilities::sanitizeInput($_POST['description']);
        $ingredients = Utilities::sanitizeInput($_POST['ingredients']);
        $instructions = Utilities::sanitizeInput($_POST['instructions']);
        $users_id = Utilities::sanitizeInput($_POST['users_id']);
        $photo = $_FILES['photo'];

        $errors = [];
        $success = [];

        if (RecipeController::emptyInput($title, $description, $ingredients, $instructions, $photo)) {
            $error = "Please fill all the fields";
        } else {
            if (isset($photo)) {
                $photo = $_FILES['photo']['name'];
                $photo_temp = $_FILES['photo']['tmp_name'];
                $destination = $_SERVER['DOCUMENT_ROOT'] . '/php_files/recipe_uploads' . "/" . $photo;
                $status = 1;
                $imageFileType = strtolower(pathinfo($destination, PATHINFO_EXTENSION));
                $check = getimagesize($_FILES["photo"]["tmp_name"]);
                if ($check) {
                    $status = 1;
                } else {
                    $error = "The file is not an image";
                    $status = 0;
                }
            }

            if ($_FILES["photo"]["size"] > 500000) {
                $error = "The file size must not exceed 5mb";
                $status = 0;
            }

            $allowedExtensions = ["jpg", "jpeg", "png", "webp"];
            if (!in_array($imageFileType, $allowedExtensions)) {
                $error = "Please upload jpg, jpeg, png and webp";
                $status = 0;
            }


            if ($status == 0) {
                $errors['error'] = $error;
            } else {
                if (move_uploaded_file($photo_temp, $destination)) {
                    $recipeContr->create_recipe($users_id, $title, $description, $ingredients, $instructions, $photo);
                    $success['success'] = "Recipe Created Successfully";
                } else {
                    $errors['error'] = "Recipe failed to create";
                }
            }
        }
        if ($errors) {
            echo json_encode($errors);
        } elseif ($success) {
            echo json_encode($success);
        }

        break;

    case 'GET':

        $id = Utilities::sanitizeInput($_GET['id']);
        $response = $recipeContr->get_recipe_by_Id($id);
        if ($response) {
            echo json_encode($response);
        }
        break;
    default:

        break;
}

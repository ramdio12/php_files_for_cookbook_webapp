<?php

include 'Database/headers.php';
include 'Recipe/RecipeController.php';


$recipeContr = new RecipeController;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $id = $_POST['id'];
    $photo = $_FILES['photo'];

    $errors = [];
    $success = [];

    if (isset($photo)) {
        $id = $id;
        $photo = $photo['name'];
        $photo_temp = $_FILES['photo']['tmp_name'];
        $destination = $_SERVER['DOCUMENT_ROOT'] . '/php_files/recipe_uploads' . "/" . $photo;
        $fileType = strtolower(pathinfo($destination, PATHINFO_EXTENSION));
        $check = getimagesize($_FILES["photo"]["tmp_name"]);
        $status = 1;

        if ($check) {
            $status = 1;
        } else {
            $error = "The file is not an image";
            $status = 0;
        }
    }


    if ($_FILES["photo"]["size"] > 500000) {
        $error = "The file should not exceed 5mb";
        $status = 0;
    }
    $allowedExtensions = ["jpg", "jpeg", "png"];
    if (!in_array($fileType, $allowedExtensions)) {
        $error = "The file must be jpeg, png and jpg";
        $status = 0;
    }

    if ($status == 0) {
        if ($error) {
            $errors["error"] = $error;
        }
    } else {
        // IF EVERYTHING IS FINE THEN WE WILL UPLOAD AND UPDATE THE CURRENT IMAGE
        if (move_uploaded_file($photo_temp, $destination)) {
            $recipeContr->update_recipe_pic($id, $photo);
            $success["success"] =  "Recipe photo update success";
        } else {
            $errors["error"] =  "Recipe photo update failed";
        }
    }

    if ($errors) {
        echo json_encode($errors);
    } elseif ($success) {
        echo json_encode($success);
    }
}

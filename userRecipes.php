<?php

include 'Database/headers.php';
include 'utilities/Utilities.php';
include 'Recipe/RecipeController.php';


$recipeContr = new RecipeController;
$method = $_SERVER['REQUEST_METHOD'];

switch ($method) {
        // this case will get all the recipe made by the user and display it on my recipe table on front end
    case 'GET':
        $userId = Utilities::sanitizeInput($_GET['users_id']);
        $response = $recipeContr->get_all_user_recipe($userId);
        if ($response) {
            echo json_encode($response);
        }
        break;

    case 'POST':
        /*
        This method is to update recipe
        */
        $errors = [];
        $success = [];

        $id = Utilities::sanitizeInput($_POST["id"]);
        $title = Utilities::sanitizeInput($_POST["title"]);
        $description = Utilities::sanitizeInput($_POST["description"]);
        $ingredients = Utilities::sanitizeInput($_POST["ingredients"]);
        $instructions = Utilities::sanitizeInput($_POST["instructions"]);

        try {
            if ($recipeContr->emptyUpdateInput($title, $description, $ingredients, $instructions)) {
                $errors['error'] = "Please fill all the fields!";
            } else {
                $recipeContr->update_recipe($id, $title, $description, $ingredients, $instructions);
                $success["success"] = "Recipe Edited! Reloading...";
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
}

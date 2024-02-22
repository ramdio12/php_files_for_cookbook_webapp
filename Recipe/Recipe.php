<?php

require_once 'utilities/Utilities.php';
require_once 'RecipeModel.php';
require_once 'RecipeController.php';

// NO LONGER USED BUT CAN BE USEFUL FOR FUTURE REFERENCE - RecipeController and RecipeModel were used in this project
class Recipe
{
    private $contr;
    private $model;
    public $errors = [];
    public $success = [];

    // instantiation of two classes - RecipeController and RecipeModel

    public function __construct()
    {
        $this->model = new RecipeModel();
        $this->contr = new RecipeController();
    }

    public function createRecipe($users_id, string $title, string $description, string $ingredients, string $instructions, $photo)
    {
        $error = "";
        if (RecipeController::emptyInput($title, $description, $ingredients, $instructions, $photo)) {
            $this->errors['error'] = "Please fill all the fields!";
        } else {
            if (isset($photo)) {
                $users_id = Utilities::sanitizeInput($users_id);
                $title = Utilities::sanitizeInput($title);
                $description = Utilities::sanitizeInput($description);
                $ingredients = Utilities::sanitizeInput($ingredients);
                $instructions = Utilities::sanitizeInput($instructions);
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

            $allowedExtensions = ["jpg", "jpeg", "png"];
            if (!in_array($imageFileType, $allowedExtensions)) {
                $error = "Please upload jpg, jpeg, png";
                $status = 0;
            }

            if ($status == 0) {
                $this->errors['error'] = $error;
            } else {
                if (move_uploaded_file($photo_temp, $destination)) {
                    $this->contr->create_recipe($users_id, $title, $description, $ingredients, $instructions, $photo);
                    $this->success = ['success' => "Recipe Created Successfully"];
                } else {
                    $this->errors = ['error' => "Recipe failed to create"];
                }
            }
        }
    }

    public function getAllRecipe()
    {
        $this->success = $this->model->getAllRecipe();
    }

    public function getRecipeById($id)
    {
        $this->success = $this->model->recipeById($id);
    }
    public function getAllUserRecipe($id)
    {
        $this->success = $this->model->userRecipes($id);
    }

    public function updateUserRecipePic($id, $photo)
    {
        $error = "";
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
                $this->errors["error"] = $error;
            }
        } else {
            // IF EVERYTHING IS FINE THEN WE WILL UPLOAD AND UPDATE THE CURRENT IMAGE
            if (move_uploaded_file($photo_temp, $destination)) {
                $this->model->updateRecipePic($id, $photo);
                $this->success["success"] =  "Recipe photo update success";
            } else {
                $this->errors["error"] =  "Recipe photo update failed";
            }
        }
    }

    public function updateRecipe($id, string $title, string $description, string $ingredients, string $instructions)
    {
        $id = Utilities::sanitizeInput($id);
        $title = Utilities::sanitizeInput($title);
        $description = Utilities::sanitizeInput($description);
        $ingredients = Utilities::sanitizeInput($ingredients);
        $instructions = Utilities::sanitizeInput($instructions);

        try {
            if (RecipeController::emptyUpdateInput($title, $description, $ingredients, $instructions)) {
                $this->errors['error'] = "Please fill all the fields!";
            } else {
                $this->contr->update_recipe($id, $title, $description, $ingredients, $instructions);
                $this->success["success"] = "Recipe Edited! Reloading...";
            }
        } catch (PDOException $e) {
            echo json_encode("Query failed " . $e->getMessage());
        }
    }


    public function __destruct()
    {
        if ($this->errors) {
            echo json_encode($this->errors);
        } else {
            echo json_encode($this->success);
        }
    }
}

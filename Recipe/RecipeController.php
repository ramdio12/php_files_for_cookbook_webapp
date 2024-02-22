<?php
/*
        
    */
require_once 'RecipeModel.php';


class RecipeController
{
    private $model;

    public function __construct()
    {
        $objModel = new RecipeModel;
        $this->model = $objModel;
    }

    public static function emptyInput(string $title, string $description, string $ingredients, string $instructions, $photo)
    {
        if (empty($title) || empty($description) || empty($ingredients) || empty($instructions) || !isset($photo)) {
            return true;
        } else {
            return false;
        }
    }



    public static function emptyUpdateInput(string $title, string $description, string $ingredients, string $instructions)
    {
        if (empty($title) || empty($description) || empty($ingredients) || empty($instructions)) {
            return true;
        } else {
            return false;
        }
    }

    public function create_recipe($users_id, string $title, string $description, string $ingredients, string $instructions, $photo)
    {
        return $this->model->insertRecipe($users_id, $title, $description, $ingredients, $instructions, $photo);
    }

    public function get_all_recipe()
    {
        return $this->model->getAllRecipe();
    }

    public function get_recipe_by_Id($id)
    {
        return $this->model->recipeById($id);
    }
    public function get_all_user_recipe($id)
    {
        return $this->model->userRecipes($id);
    }

    public function update_recipe($id, string $title, string $description, string $ingredients, string $instructions)
    {
        return $this->model->updateUserRecipe($id, $title, $description, $ingredients, $instructions);
    }
    public function update_recipe_pic($id, $photo)
    {
        return $this->model->updateRecipePic($id, $photo);
    }
}

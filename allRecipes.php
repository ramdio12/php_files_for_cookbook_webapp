<?php

/*
This file is to get all recipes that the users made so that anyone could see it
*/


include 'Database/headers.php';
include 'Recipe/RecipeController.php';

$recipeContr = new RecipeController;

if ($_SERVER["REQUEST_METHOD"] === "GET") {

    $response =  $recipeContr->get_all_recipe();
    if ($response) {
        echo json_encode($response);
    }
}

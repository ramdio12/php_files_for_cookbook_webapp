<?php


// THIS IS THE CRUD FOR OUR RECIPE = BUT SINCE DELETE METHOD IS SOMEWHAT LIMITED TO PREMIUM USERS = ONLY DELETE METHOD IS NOT INCLUDED HERE

include 'headers.php';
include 'DbConnect.php';
$objDb = new DbConnect();
$conn = $objDb->connect();

$method = $_SERVER['REQUEST_METHOD'];

switch ($method) {
    case "POST":
        $user = json_decode(file_get_contents('php://input'));
        if (empty($user->title) && empty($user->description) && empty($user->ingredients) && empty($user->instructions) && !isset($_FILES['photo'])) {
            $response = array(
                'status' => 'empty',
                'message' => 'Please fill all the fields!'
            );
        } else {
            if (isset($_FILES['photo'])) {
                $title = $_POST['title'];
                $description = $_POST['description'];
                $ingredients = $_POST['ingredients'];
                $instructions = $_POST['instructions'];
                $users_id = $_POST['users_id'];
                $photo = $_FILES['photo']['name'];
                $photo_temp = $_FILES['photo']['tmp_name'];
                $destination = $_SERVER['DOCUMENT_ROOT'] . '/uploads' . "/" . $photo;
                $uploadOk = 1;
                $imageFileType = strtolower(pathinfo($destination, PATHINFO_EXTENSION));

                $check = getimagesize($_FILES["photo"]["tmp_name"]);
                if ($check !== false) {
                    $uploadOk = 1;
                } else {
                    $error = ["status" => "invalid", "message" => "File is not an image"];
                    $uploadOk = 0;
                }
            }

            if ($_FILES["photo"]["size"] > 500000) {
                $error = ["status" => "large_file", "message" => "File is too large"];
                $uploadOk = 0;
            }

            $allowedExtensions = ["jpg", "jpeg", "png"];
            if (!in_array($imageFileType, $allowedExtensions)) {
                $error = ["status" => "incompatible", "message" => "Please upload jpg, jpeg, and png files"];
                $uploadOk = 0;
            }

            if ($uploadOk == 0) {
                $response = $error;
            } else {
                if (move_uploaded_file($photo_temp, $destination)) {
                    $sql = "INSERT INTO posts (title,description,ingredients,instructions,photo,users_id) VALUES (:title,:description,:ingredients,:instructions,:photo,:users_id)";
                    $stmt = $conn->prepare($sql);
                    $stmt->bindParam(':title', $title);
                    $stmt->bindParam(':description', $description);
                    $stmt->bindParam(':ingredients', $ingredients);
                    $stmt->bindParam(':instructions', $instructions);
                    $stmt->bindParam(':photo', $photo);
                    $stmt->bindParam(':users_id', $users_id);
                    $stmt->execute();
                    $response = ['status' => "success", 'message' => "success"];
                } else {
                    $response = ['status' => "failed", 'message' => 'Failed to create recipes'];
                }
            }
        }

        echo json_encode($response);
        break;

    case "GET":
        $id = $_GET['id'];
        $sql = "SELECT posts.id, posts.title,posts.description,posts.ingredients,posts.instructions,posts.photo,users.username FROM users RIGHT JOIN posts ON users.id = posts.users_id WHERE posts.id=:id";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        $recipes = $stmt->fetch(PDO::FETCH_ASSOC);
        echo json_encode($recipes);
        break;

    case "POST":
    // the update file for recipe is on update_my_recipe.php 
    // this is for practice only and also to avoid conflict since POST method was used above for creating recipe
        $user = json_decode(file_get_contents('php://input'));
        if (empty($user->title) && empty($user->description) && empty($user->ingredients) && empty($user->instructions)) {
            $response = array(
                'status' => 'empty',
                'kahon' => $user,
                'message' => 'Please fill all the fields!'
            );
        } else {
            $sql = "UPDATE posts SET title = :title, description = :description, ingredients = :ingredients,instructions = :instructions WHERE id = :id";

            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':id', $user->id);
            $stmt->bindParam(':title', $user->title);
            $stmt->bindParam(':description', $user->description);
            $stmt->bindParam(':ingredients', $user->ingredients);
            $stmt->bindParam(':instructions', $user->instructions);
            if ($stmt->execute()) {
                $response = ['status' => "success", 'message' => "Edit Info success"];
            } else {
                $response = ['status' => "success", 'message' => "Edit Info failed"];
            }
        }

        echo json_encode($response);
        break;
}

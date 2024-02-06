<?php

include 'headers.php';
include 'DbConnect.php';
$objDb = new DbConnect();
$conn = $objDb->connect();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user = json_decode(file_get_contents('php://input'));
    $id = $_POST["id"];
    $title = $_POST["title"];
    $description = $_POST["description"];
    $ingredients = $_POST["ingredients"];
    $instructions = $_POST["instructions"];
    
    if (empty($title) && empty($description) && empty($ingredients) && empty($instructions)) {
        $response = array(
            'status' => 'empty',
            'kahon' => $user,
            'message' => 'Please fill all the fields!'
        );
    } else {

        $sql = "UPDATE posts SET title = :title, description = :description, ingredients = :ingredients,instructions = :instructions WHERE id = :id";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':id', $id);
        $stmt->bindParam(':title', $title);
        $stmt->bindParam(':description', $description);
        $stmt->bindParam(':ingredients', $ingredients);
        $stmt->bindParam(':instructions', $instructions);
        $stmt->execute();
        $response = ['status' => "success", 'message' => "Recipe edited success! Reloading..."];
    }

    echo json_encode($response);
}



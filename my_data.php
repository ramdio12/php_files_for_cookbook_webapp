<?php

// this is for fetching and updating the user's data

include 'headers.php';
include 'DbConnect.php';
$objDb = new DbConnect();
$conn = $objDb->connect();


if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $id = $_GET['id'];
    $sql = "SELECT * from users WHERE id = :id";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt->execute();
    $recipes = $stmt->fetch(PDO::FETCH_ASSOC);
    echo json_encode($recipes);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST["id"];
    $name = $_POST["name"];
    $username = $_POST["username"];
    $email = $_POST["email"];


    if (empty($name) && empty($username) && empty($email)) {
        $response = array(
            'status' => 'empty',
            'message' => 'Please fill all the fields!'
        );
    } else {

        $sql = "UPDATE users SET name = :name, username = :username, email = :email WHERE id = :id";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':id', $id);
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        $response = ['status' => "success", 'message' => "success"];
    }

    echo json_encode($response);
}

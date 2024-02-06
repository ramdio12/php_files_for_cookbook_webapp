<?php

// this fetch the user's one recipe

include 'headers.php';
include 'DbConnect.php';
$objDb = new DbConnect();
$conn = $objDb->connect();

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $userId = $_GET['users_id'];
    $sql = "SELECT * from posts WHERE users_id = :users_id ORDER BY created_at DESC";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':users_id', $userId, PDO::PARAM_INT);
    $stmt->execute();
    $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode($users);
}

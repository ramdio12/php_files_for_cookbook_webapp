<?php

include 'headers.php';
include 'DbConnect.php';
$objDb = new DbConnect();
$conn = $objDb->connect();


if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $id = $_GET['id'];
    $sql = "SELECT * from posts WHERE id = :id";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt->execute();
    $recipes = $stmt->fetch(PDO::FETCH_ASSOC);
    echo json_encode($recipes);
}


// FETCH ALL USER'S RECIPES
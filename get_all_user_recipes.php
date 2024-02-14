<?php

// FETCHING ALL RECIPES = we can get all recipes with get method
include 'headers.php';
include 'DbConnect.php';
$objDb = new DbConnect();
$conn = $objDb->connect();


if ($_SERVER['REQUEST_METHOD'] === 'GET') {
        $sql = "SELECT posts.id, posts.title,posts.photo,posts.created_at,users.name FROM users RIGHT JOIN posts ON users.id = posts.users_id ORDER BY created_at DESC";
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $recipes = $stmt->fetchAll(PDO::FETCH_ASSOC);

        echo json_encode($recipes);
}

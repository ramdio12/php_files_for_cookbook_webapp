<?php
/*
        User Model is responsible for database connection and query
    */
require_once 'Database/DatabaseConnect.php';


class RecipeModel
{
    private $conn;

    public function __construct()
    {
        $objDb = new DatabaseConnect;
        $this->conn = $objDb->connect();
    }

    public function insertRecipe($users_id, string $title, string $description, string $ingredients, string $instructions, $photo)
    {
        $sql = "INSERT INTO posts (title,description,ingredients,instructions,photo,users_id) VALUES (:title,:description,:ingredients,:instructions,:photo,:users_id)";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':title', $title);
        $stmt->bindParam(':description', $description);
        $stmt->bindParam(':ingredients', $ingredients);
        $stmt->bindParam(':instructions', $instructions);
        $stmt->bindParam(':photo', $photo);
        $stmt->bindParam(':users_id', $users_id);
        $stmt->execute();
    }

    public function getAllRecipe()
    {
        $sql = "SELECT posts.id, posts.title,posts.photo,posts.created_at,users.name FROM users RIGHT JOIN posts ON users.id = posts.users_id ORDER BY created_at DESC";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }
    public function recipeById($id)
    {
        $sql = "SELECT posts.id, posts.title,posts.description,posts.ingredients,posts.instructions,posts.photo,users.name FROM users RIGHT JOIN posts ON users.id = posts.users_id WHERE posts.id=:id";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result;;
    }

    public function userRecipes($id)
    {
        $sql = "SELECT * from posts WHERE users_id = :users_id ORDER BY created_at DESC";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':users_id', $id, PDO::PARAM_INT);
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }

    public function updateRecipePic($id, $photo)
    {
        $sql = "UPDATE posts SET photo = :photo WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':id', $id);
        $stmt->bindParam(':photo', $photo);
        $stmt->execute();
    }
    public function updateUserRecipe($id, $title, $description, $ingredients, $instructions)
    {
        $sql = "UPDATE posts SET title = :title, description = :description, ingredients = :ingredients,instructions = :instructions WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':id', $id);
        $stmt->bindParam(':title', $title);
        $stmt->bindParam(':description', $description);
        $stmt->bindParam(':ingredients', $ingredients);
        $stmt->bindParam(':instructions', $instructions);
        $stmt->execute();
    }
}

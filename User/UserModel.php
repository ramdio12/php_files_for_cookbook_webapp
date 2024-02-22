<?php
/*
        User Model is responsible for database connection and query
    */
require_once 'Database/DatabaseConnect.php';

class UserModel
{

    private $conn;

    public function __construct()
    {
        $objDb = new DatabaseConnect;
        $this->conn = $objDb->connect();
    }

    /*
        Register Section
    */
    // check if email already exists
    public function checkEmail($email)
    {
        $sql = "SELECT * FROM users WHERE email =:email";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute(['email' => $email]);
        $user = $stmt->fetch();
        return $user;
    }

    // method to register a user
    public function register($name, $email, $password)
    {
        $sql = "INSERT INTO users (name,email,password) VALUES (:name,:email,:password)";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([
            'name' => $name,
            'email' => $email,
            'password' => $password
        ]);
    }




    /* Login Section */
    // log in a user
    public function login(string $email, string $password)
    {
        $sql = "SELECT * FROM users WHERE email = :email";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute(['email' => $email]);
        $user = $stmt->fetch();

        if ($user) {
            if (password_verify($password, $user['password'])) {
                return $user;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }
    public function getUser(string $email)
    {
        $sql = "SELECT * FROM users WHERE email = :email";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute(['email' => $email]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result;
    }
    public function updateUser(int $id, string $name, string $email)
    {
        $sql = "UPDATE users SET name = :name,email = :email WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([
            'id' => $id,
            'name' => $name,
            'email' => $email,
        ]);
    }
    public function getUserDataById(int $id)
    {
        $sql = "SELECT * from users WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result;
    }

    public function updateUserPhoto(int $id, $photo)
    {
        $sql = "UPDATE users SET photo = :photo WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':id', $id);
        $stmt->bindParam(':photo', $photo);
        $stmt->execute();
    }
}


 //     $sql = "UPDATE users SET name = :name, username = :username, email = :email WHERE id = :id";
        //     $stmt = $conn->prepare($sql);
        //     $stmt->bindParam(':id', $id);
        //     $stmt->bindParam(':name', $name);
        //     $stmt->bindParam(':username', $username);
        //     $stmt->bindParam(':email', $email);
        //     $stmt->execute();
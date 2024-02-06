<?php

/*

    This is where we communicate with the database
    we get username and email on the database, if it returns 0 or nothing , it means the username and email that the user submitted is new and not taken by others
    lastly, all data will be sent to the database with our set_user function once there will be no error
*/

declare(strict_types=1);

function getUsername(object $conn, string $username)
{
    $query = "SELECT username FROM users WHERE username= :username;";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(":username", $username);
    $stmt->execute();

    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    return $result;
}

function getEmail(object $conn, string $email)
{
    $query = "SELECT email FROM users WHERE email=:email;";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(":email", $email);
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    return $result;
}

// we will be using password encryption for security and to prevent brute forcing

function set_user(object $conn, string $name, string $username, string $email, string $password)
{
    $query = "INSERT INTO users (name,username,email,password) VALUES (:name,:username,:email,:password);";
    $stmt = $conn->prepare($query);
    // BRUTE FORCING PREVENTION
    $options = [
        'cost' => 12
    ];
    $hashedPwd = password_hash($password, PASSWORD_BCRYPT, $options);
    $stmt->bindParam(":name", $name);
    $stmt->bindParam(":username", $username);
    $stmt->bindParam(":email", $email);
    $stmt->bindParam(":password", $hashedPwd);
    $stmt->execute();
}

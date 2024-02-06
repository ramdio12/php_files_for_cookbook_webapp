<?php

/*

    This is the login model
    this will communicate to the database
*/

declare(strict_types=1);


function get_user(object $conn, string $username)

{
    $query = "SELECT * FROM users WHERE username=:username;";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(":username", $username);
    $stmt->execute();

    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    return $result;
}

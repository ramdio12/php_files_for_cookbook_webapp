<?php

/*

    This is the login model
    this will communicate to the database
*/

declare(strict_types=1);


function get_user(object $conn, string $email)

{
    $query = "SELECT * FROM users WHERE email=:email;";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(":email", $email);
    $stmt->execute();

    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    return $result;
}

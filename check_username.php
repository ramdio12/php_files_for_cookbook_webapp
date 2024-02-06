<?php

// checking username = if the row count is greater than 0, the username is already registered

include 'headers.php';
include 'DbConnect.php';
$objDb = new DbConnect();
$conn = $objDb->connect();


if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $user = json_decode(file_get_contents('php://input'));
    $username = $_POST['username'];

    if (!empty($username)) {
        $sql = "SELECT * FROM users WHERE username=:username";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':username', $username);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            $response = ['status' => "duplicate", 'message' => 'Username already taken!'];
        }
    }

    echo json_encode($response);
}

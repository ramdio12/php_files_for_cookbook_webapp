<?php
// login = this is where we get the users data and send to its controller for error checking and model for sending it to the database

include 'headers.php';
include 'DbConnect.php';
include_once 'login/login.controller.php';
include_once 'login/login.model.php';

$objDb = new DbConnect();
$conn = $objDb->connect();



if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $user = json_decode(file_get_contents('php://input'));
    $username = htmlspecialchars($_POST["username"]);
    $password = htmlspecialchars($_POST["password"]);

    try {

        $errors = [];
        $success = [];

        if (is_input_empty($username, $password)) {
            $errors["error"] = "Fill  all the fields";
        }

        $result = get_user($conn, $username);

        if (is_username_wrong($result)) {
            $errors["error"] = "Incorrect credential!";
        } else if (!is_username_wrong($result) && is_password_wrong($password, $result["password"])) {
            $errors["error"] = "Incorrect credential!";
        } else {
            $success = array(
                'success' => 'Login successful! Redirecting...',
                'data' => array(
                    'email' => htmlspecialchars($result['email']),
                    'username' => htmlspecialchars($result['username']),
                    "id" => $result['id']
                ),

            );
        }

        if ($errors) {
            $response = $errors;
        } else {

            $response = $success;
        }


        echo json_encode($response);
    } catch (PDOException $e) {
        die("Query failed " . $e->getMessage());
    }
}

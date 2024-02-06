<?php



include 'headers.php';
include 'DbConnect.php';
$objDb = new DbConnect();
$conn = $objDb->connect();

// checking email, if the result is greater than 0, it means the email is already registered
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $user = json_decode(file_get_contents('php://input'));
    $email = $_POST['email'];


    if (!empty($email)) {
        $sql = "SELECT * FROM users WHERE email=:email";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':email', $email);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            $response = ['status' => "duplicate", 'message' => 'Email already registered!'];
        }
    }

    echo json_encode($response);
}

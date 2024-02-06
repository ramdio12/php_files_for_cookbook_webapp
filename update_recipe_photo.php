<?php

// this is to update the recipe photo

include 'headers.php';
include 'DbConnect.php';
$objDb = new DbConnect();
$conn = $objDb->connect();


if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    if (isset($_FILES['photo'])) {
        // get the photo from the input and the recipe id
        $id = $_POST['id'];
        $photo = $_FILES['photo']['name'];
        $photo_temp = $_FILES['photo']['tmp_name'];
        $destination = $_SERVER['DOCUMENT_ROOT'] . '/uploads' . "/" . $photo;
        $uploadOk = 1;
        $imageFileType = strtolower(pathinfo($destination, PATHINFO_EXTENSION));

        // PHOTO UPDATE VALIDATIONS HERE
        $check = getimagesize($_FILES["photo"]["tmp_name"]);
        if ($check !== false) {
            $uploadOk = 1;
        } else {
            $error = ["status" => "invalid", "message" => "File is not an image"];
            $uploadOk = 0;
        }
    }

    if ($_FILES["photo"]["size"] > 500000) {
        $error = ["status" => "large_file", "message" => "File is too large"];
        $uploadOk = 0;
    }

    $allowedExtensions = ["jpg", "jpeg", "png"];
    if (!in_array($imageFileType, $allowedExtensions)) {
        $error = ["status" => "incompatible", "message" => "Please upload jpg, jpeg, and png files"];
        $uploadOk = 0;
    }

    if ($uploadOk == 0) {
        $response = $error;
    } else {
        // IF EVERYTHING IS FINE THEN WE WILL UPLOAD AND UPDATE THE CURRENT IMAGE
        if (move_uploaded_file($photo_temp, $destination)) {
            $sql = "UPDATE posts SET photo = :photo WHERE id = :id";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':id', $id);
            $stmt->bindParam(':photo', $photo);
            $stmt->execute();

            $response = ['status' => "success", 'message' => "Photo update success"];
        } else {
            $response = ['status' => "failed", 'message' => 'Failed to update photo'];
        }
    }


    echo json_encode($response);
}

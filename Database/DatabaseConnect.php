<?php

require_once 'config.php';

class DatabaseConnect
{
    private const DSN = 'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME;


    // method to connect to the database
    public function connect()
    {
        try {
            $conn = new PDO(self::DSN, DB_USER, DB_PASS);
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $conn->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
            return $conn;
        } catch (PDOException $e) {
            echo json_encode("Connection Failure: " . $e->getMessage());
        }
    }
}

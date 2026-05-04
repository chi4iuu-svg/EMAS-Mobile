<?php
// db_config.php — Include this in every PHP file that needs DB access
// Place this file OUTSIDE your public web folder if possible, or protect it with .htaccess

define('DB_HOST', 'localhost');
define('DB_USER', 'root');       // Change to your MySQL username
define('DB_PASS', '');           // Change to your MySQL password
define('DB_NAME', 'emas_db');    // Change to your database name

function getDB() {
    $conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
    if ($conn->connect_error) {
        http_response_code(500);
        die(json_encode(['error' => 'Database connection failed.']));
    }
    $conn->set_charset('utf8mb4');
    return $conn;
}
?>

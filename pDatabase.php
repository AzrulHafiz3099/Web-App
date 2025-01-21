<?php
// Database Configuration
define('DB_HOST', 'localhost');       // Replace with your host (e.g., 127.0.0.1 or your hosting IP)
define('DB_USER', 'etourmer_adminmedrecapp');  // Replace with your MySQL username
define('DB_PASS', 'MedreC0g@pp2024');  // Replace with your MySQL password
define('DB_NAME', 'etourmer_medrecapp');  // Replace with your database name

// Create a connection
$conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

// Check the connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Optional: Set character encoding to avoid issues with special characters
$conn->set_charset("utf8");
?>

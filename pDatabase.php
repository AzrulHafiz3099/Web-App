<?php 

error_reporting(E_ALL);
ini_set('display_errors', 1);

/*error_reporting(0);*/

$dbHost = "localhost";  // Ensure this is "localhost"
$dbUser = "root";       // Your MySQL username
$dbPass = "";           // Your MySQL password
$dbName = "medrecapp";     // Database name

// Create connection
$conn = new mysqli($dbHost, $dbUser, $dbPass, $dbName);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}


?>

<?php
// Database connection
$conn = new mysqli('localhost', 'root', '', 'medrecapp');
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch form data
$username = $_POST['username'];
$password = $_POST['password'];

// Query admin_login
$sql = "SELECT * FROM admin_login WHERE username=?";
$stmt = $conn->prepare($sql);
$stmt->bind_param('s', $username);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $user = $result->fetch_assoc();
    if (password_verify($password, $user['password_hash'])) {
        session_start();
        $_SESSION['loggedin'] = true;
        header('Location: index.html');
    } else {
        echo "Invalid credentials.";
    }
} else {
    echo "User not found.";
}

$conn->close();
?>


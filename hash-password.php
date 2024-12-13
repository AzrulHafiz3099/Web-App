<?php
// Example password to hash
$plainPassword = "admin123";

// Hash the password using bcrypt
$hashedPassword = password_hash($plainPassword, PASSWORD_BCRYPT);

// Display the hashed password
echo "Hashed Password: " . $hashedPassword;

if (password_verify($password, $user['password_hash'])) {
    session_start();
    $_SESSION['loggedin'] = true;
    header('Location: dashboard.php');
} else {
    echo "Invalid credentials.";
}

?>

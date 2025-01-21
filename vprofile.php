<?php
session_start();
require_once "./pDatabase.php";

// Fetch user details from the database
$username = $_SESSION['username'];
$sql = "SELECT * FROM vendor WHERE Username='$username' OR Email='$username'";
$result = mysqli_query($conn, $sql);

if (mysqli_num_rows($result) > 0) {
    $user = mysqli_fetch_assoc($result);
    // Don't overwrite $_SESSION['username'], as it already stores the username or email
} else {
    echo '<script>alert("User data not found!");</script>';
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <link rel="stylesheet" type="text/css" href="./style_profile.css">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Profile</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
</head>

<body>
    <div class="profile-card">
        <h2><?= htmlspecialchars($user['Fullname']); ?></h2>
        <p><strong>Username:</strong> <?= htmlspecialchars($user['Username']); ?></p>
        <p><strong>Address:</strong> <?= htmlspecialchars($user['Address']); ?></p>
        <p><strong>Contact Number:</strong> <?= htmlspecialchars($user['ContactNumber']); ?></p>
        <p><strong>Email:</strong> <?= htmlspecialchars($user['Email']); ?></p>
        <a href="./vDashboard.php" class="button">Back to Dashboard</a>
    </div>
</body>

</html>      
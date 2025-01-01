<?php
session_start();
@include 'db_connection.php';

// Check if the user is logged in
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header('Location: login.php');
    exit;
}

// Fetch user details from the database securely
$UserID = $_SESSION['UserID'];
$sql = "SELECT * FROM user_account WHERE UserID = ?";
if ($stmt = mysqli_prepare($conn, $sql)) {
    mysqli_stmt_bind_param($stmt, "s", $UserID);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if (mysqli_num_rows($result) > 0) {
        $user = mysqli_fetch_assoc($result);
    } else {
        echo '<script>alert("User data not found!");</script>';
        exit;
    }

    mysqli_stmt_close($stmt);
} else {
    echo "Error preparing statement: " . mysqli_error($conn);
    exit;
}

// Regenerate session ID for security after successful login
session_regenerate_id();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Profile</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            margin: 0;
            padding: 0;
            background: linear-gradient(135deg, #fdfcfb, #f3c623);
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .profile-card {
            background: white;
            padding: 30px;
            border-radius: 20px;
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.2);
            text-align: center;
            max-width: 400px;
            width: 100%;
        }

        .profile-card img {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            margin-bottom: 15px;
        }

        .profile-card h2 {
            font-weight: bold;
            margin-bottom: 10px;
            color: #f83600;
        }

        .profile-card p {
            margin: 5px 0;
            font-size: 1em;
            color: #6c757d;
        }

        .button {
            background: linear-gradient(135deg, #ff9a3c, #f83600);
            border: none;
            border-radius: 25px;
            padding: 10px 20px;
            color: white;
            font-size: 1em;
            cursor: pointer;
            transition: background 0.3s ease;
        }

        .button:hover {
            background: linear-gradient(135deg, #f83600, #ff9a3c);
        }
    </style>
</head>

<body>
    <div class="profile-card">
        <!-- Check if the user has a profile picture -->
        <img src="<?= !empty($user['ProfilePicture']) ? $user['ProfilePicture'] : 'default-profile.png'; ?>" alt="Profile Picture">
        <h2><?= htmlspecialchars($user['Fullname']); ?></h2>
        <p><strong>Email:</strong> <?= htmlspecialchars($user['Email']); ?></p>
        <p><strong>Phone:</strong> <?= htmlspecialchars($user['Phonenumber']); ?></p>
        <p><strong>Date of Birth:</strong> <?= htmlspecialchars($user['DateOFBirth']); ?></p>
        <p><strong>Role:</strong> <?= htmlspecialchars($user['Role']); ?></p>
        <a href="index.php" class="button">Back to Dashboard</a>
    </div>
</body>

</html>

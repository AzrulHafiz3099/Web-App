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
            background: linear-gradient(135deg, #f9f9f9, #f3c623);
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            color: #333;
        }

        .profile-container {
            background: white;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            max-width: 500px;
            width: 100%;
            padding: 30px;
            text-align: center;
            transition: all 0.3s ease-in-out;
        }

        .profile-container:hover {
            box-shadow: 0 15px 40px rgba(0, 0, 0, 0.2);
        }

        .profile-container img {
            width: 120px;
            height: 120px;
            border-radius: 50%;
            margin-bottom: 15px;
            object-fit: cover;
            border: 4px solid #f83600;
        }

        .profile-container h2 {
            font-size: 1.8em;
            color: #f83600;
            margin-bottom: 10px;
        }

        .profile-container p {
            margin: 5px 0;
            font-size: 1.1em;
            color: #555;
        }

        .profile-container p strong {
            color: #333;
        }

        .profile-container .button {
            display: inline-block;
            background: linear-gradient(135deg, #ff9a3c, #f83600);
            color: white;
            border: none;
            border-radius: 25px;
            padding: 12px 25px;
            font-size: 1em;
            text-transform: uppercase;
            text-decoration: none;
            font-weight: bold;
            margin-top: 20px;
            transition: background 0.3s ease, transform 0.2s ease;
        }

        .profile-container .button:hover {
            background: linear-gradient(135deg, #f83600, #ff9a3c);
            transform: translateY(-3px);
        }
    </style>
</head>

<body>
    <div class="profile-container">
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

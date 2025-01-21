<?php
@include 'db_connection.php';
session_start();

$errorMsg = ''; // To store error messages

if (isset($_POST['login'])) {
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = $_POST['password'];

    // Prepare the SQL statement
    $sql = "SELECT * FROM user_account WHERE Email = ?";
    if ($stmt = mysqli_prepare($conn, $sql)) {
        mysqli_stmt_bind_param($stmt, "s", $username);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        if ($row = mysqli_fetch_assoc($result)) {
            // Debugging - Only display these if you are troubleshooting
            echo "Input Password: $password<br>";
            echo "Stored Hashed Password: " . $row['Password'] . "<br>";

            // Verify password
            if (password_verify($password, $row['Password'])) {
                // Successful login
                $_SESSION['loggedin'] = true;
                $_SESSION['UserID'] = $row['UserID'];
                $_SESSION['Role'] = $row['Role'];

                // Regenerate session ID to prevent session fixation attacks
                session_regenerate_id();

                header('Location: index.php');
                exit;
            } else {
                $errorMsg = "Incorrect username or password!";
            }
        } else {
            $errorMsg = "Incorrect username or password!";
        }

        mysqli_stmt_close($stmt);
    } else {
        $errorMsg = "Error preparing statement: " . mysqli_error($conn);
    }
}
?>




<!DOCTYPE html>
<html lang="en">
<head>

    <link rel="stylesheet" type="text/css" href="./style_Alogin.css">

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
</head>

<body>
    <div class="login-card">
        <div class="icon">
            <i class="fas fa-user-shield"></i>
        </div>
        <h2>Admin Login</h2>
        <form action="" method="post">
            <input type="text" name="username" class="form-control" placeholder="Enter your username" required>
            <input type="password" name="password" class="form-control" placeholder="Enter your password" required>
            <button type="submit" class="btn" name="login">Login</button>
        </form>
        
        <!-- Display error message -->
        <?php if (!empty($errorMsg)): ?>
            <div class="error-msg"><?php echo $errorMsg; ?></div>
        <?php endif; ?>
    </div>
</body>

</html>


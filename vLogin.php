<?php
require_once "./pDatabase.php";
session_start();

$username = $password = "";

function storeData($data)
{
    if(isset($data))
    {
        return $data;
    }
    else
    {
        return false;
    }
}

if($_SERVER['REQUEST_METHOD'] === "POST")
{
    if(isset($_POST['login']))
    {
        $username = storeData($_POST['username']);
        $password = storeData($_POST['password']);

        if ($username && $password) {
            // Query to fetch the user by username or email
            $sql = "SELECT * FROM vendor WHERE Username = ? OR Email = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ss", $username, $username);
            $stmt->execute();
            $result = $stmt->get_result();
            $rowCount = $result->num_rows;
        
            if ($rowCount > 0) { // User found
                $row = $result->fetch_assoc();
                // Verify the provided password against the hashed password
                if (password_verify($password, $row['Password'])) {
                    session_start();
                    $_SESSION['vendor_id'] = $row['VendorID'];
                    $_SESSION['username'] = $row['Username'];
                    header("location: ./vDashboard.php");
                    exit;
                } else {
                    $error['password'] = "WRONG PASSWORD";
                }
            } else {
                $error['username'] = "WRONG USERNAME";
            }
            $stmt->close();
        }        
    }
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>

    <link rel="stylesheet" type="text/css" href="./style_login.css">

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
            <input type="text" id="username" name="username" class="form-control <?php echo isset($error['username']) ? 'is-invalid' : '';?>" placeholder="Enter your username" value="<?php echo htmlspecialchars($username);?>" required>
            <div class="error-msg">
                <?php echo $error['username'] ?? '';?>
            </div>
            <input type="password" id="password" name="password" class="form-control <?php echo isset($error['password']) ? 'is-invalid' : '';?>" placeholder="Enter your password" value="<?php echo htmlspecialchars($password);?>"  required>
            <div class="error-msg">
                <?php echo $error['password'] ?? '';?>
            </div>
            <button type="submit" class="btn" name="login">Login</button>
            <a href="./vRegister.php" style="text-decoration: none;"> 
				<p style="text-align: center;clear: both;margin-top: 25px;color: #f83600;text-decoration: underline;">Don't have an account? Click here!</p>
			</a>
        </form>
    </div>
</body>
</html>

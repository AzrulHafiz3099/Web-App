<?php
@include 'db.php';
session_start();

$id = $_GET['id'];
$query = "SELECT * FROM pharmacy_accounts WHERE id = $id";
$result = mysqli_query($conn, $query);
$pharmacy = mysqli_fetch_assoc($result);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $location = $_POST['location'];
    $contact = $_POST['contact'];

    $update_query = "UPDATE pharmacy_accounts SET name = '$name', location = '$location', contact = '$contact' WHERE id = $id";
    if (mysqli_query($conn, $update_query)) {
        header('Location: index.php');
        exit();
    } else {
        echo 'Error: ' . mysqli_error($conn);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Pharmacy</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f2f2f2;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 600px;
            margin: 50px auto;
            padding: 20px;
            background-color: #fff;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
        }
        h2 {
            text-align: center;
            color: #333;
        }
        input, textarea {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border-radius: 4px;
            border: 1px solid #ddd;
        }
        button {
            width: 100%;
            padding: 12px;
            background-color: #007bff;
            color: white;
            font-size: 16px;
            border: none;
            border-radius: 4px;
        }
        button:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <div class="container mt-5">
        <h1>Edit Pharmacy</h1>
        <form method="POST">
            <div class="form-group">
                <label>Pharmacy Name</label>
                <input type="text" name="name" class="form-control" value="<?php echo htmlspecialchars($pharmacy['name']); ?>" required>
            </div>
            <div class="form-group">
                <label>Location</label>
                <input type="text" name="location" class="form-control" value="<?php echo htmlspecialchars($pharmacy['location']); ?>" required>
            </div>
            <div class="form-group">
                <label>Contact</label>
                <input type="text" name="contact" class="form-control" value="<?php echo htmlspecialchars($pharmacy['contact']); ?>" required>
            </div>
            <button type="submit" class="btn btn-warning">Update Pharmacy</button>
            <a href="index.php" class="btn btn-secondary">Cancel</a>
        </form>
    </div>
</body>
</html>

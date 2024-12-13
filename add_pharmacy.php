<?php
@include 'db.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $location = $_POST['location'];
    $contact = $_POST['contact'];

    $query = "INSERT INTO pharmacy_accounts (name, location, contact) VALUES ('$name', '$location', '$contact')";
    if (mysqli_query($conn, $query)) {
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
    <title>Add Pharmacy</title>
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
            background-color: #28a745;
            color: white;
            font-size: 16px;
            border: none;
            border-radius: 4px;
        }
        button:hover {
            background-color: #218838;
        }
    </style>
</head>
<body>

<div class="container">
    <h2>Add Pharmacy</h2>
    <form method="POST">
        <label for="pharmacy_name">Pharmacy Name</label>
        <input type="text" name="pharmacy_name" required>

        <label for="address">Address</label>
        <textarea name="address" rows="4" required></textarea>

        <label for="contact">Contact</label>
        <input type="text" name="contact" required>

        <button type="submit">Add Pharmacy</button>
    </form>
</div>

</body>
</html>
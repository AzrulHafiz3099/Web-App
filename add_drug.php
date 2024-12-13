<?php
@include 'db.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $drug_name = $_POST['drug_name'];
    $stock = $_POST['stock'];
    $price = $_POST['price'];

    $query = "INSERT INTO drug_inventory (drug_name, stock, price) VALUES ('$drug_name', '$stock', '$price')";
    if (mysqli_query($conn, $query)) {
        header('Location: drug_inventory.php');
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
    <title>Add Drug</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f9;
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
        label {
            font-size: 16px;
            margin-bottom: 8px;
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

<div class="container">
    <h2>Add Drug</h2>
    <form method="POST">
        <label for="drug_name">Drug Name</label>
        <input type="text" name="drug_name" required>

        <label for="stock">Stock</label>
        <input type="number" name="stock" required>

        <label for="price">Price</label>
        <input type="number" step="0.01" name="price" required>

        <button type="submit">Add Drug</button>
    </form>
</div>

</body>
</html>

<?php
@include 'db_connection.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $drugID = $_POST['drug_id'];
    $currentStock = $_POST['current_stock'];
    $reorderLevel = $_POST['reorder_level'];
    $lastRestockDate = $_POST['last_restock_date'];
    $expirationDate = $_POST['expiration_date'];

    $sql = "INSERT INTO drug_inventory (DrugID, CurrentStock, ReorderLevel, LastRestockDate, ExpirationDate) 
            VALUES ('$drugID', '$currentStock', '$reorderLevel', '$lastRestockDate', '$expirationDate')";

    if (mysqli_query($conn, $sql)) {
        $_SESSION['message'] = "Drug added successfully!";
    } else {
        $_SESSION['message'] = "Error adding drug: " . mysqli_error($conn);
    }

    header("Location: drug_inventory.php");
    exit();
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
            background: linear-gradient(135deg, #ff9a3c, #f83600);
            margin: 0;
            padding: 0;
        }

        .container {
            width: 50%;
            margin: 50px auto;
            background-color: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        h1 {
            font-size: 2em;
            text-align: center;
            color: #f83600;
        }

        form {
            display: flex;
            flex-direction: column;
            gap: 15px;
        }

        label {
            font-weight: bold;
        }

        input[type="text"],
        input[type="number"],
        input[type="date"] {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 1em;
        }

        .btn {
            padding: 10px 15px;
            border-radius: 5px;
            text-decoration: none;
            color: white;
            font-size: 1em;
            border: none;
            cursor: pointer;
        }

        .btn-primary {
            background-color: #28a745;
        }

        .btn-primary:hover {
            background-color: #218838;
        }

        .btn-danger {
            background-color: #dc3545;
        }

        .btn-danger:hover {
            background-color: #c82333;
        }
    </style>
</head>

<body>
    <div class="container">
        <h1>Add Drug</h1>
        <form method="post" action="add_drug.php">
            <label>Drug ID:</label>
            <input type="text" name="drug_id" required>

            <label>Current Stock:</label>
            <input type="number" name="current_stock" required>

            <label>Reorder Level:</label>
            <input type="number" name="reorder_level" required>

            <label>Last Restock Date:</label>
            <input type="date" name="last_restock_date">

            <label>Expiration Date:</label>
            <input type="date" name="expiration_date">

            <button type="submit" class="btn btn-primary">Add Drug</button>
            <a href="drug_inventory.php" class="btn btn-danger">Cancel</a>
        </form>
    </div>
</body>

</html>


<?php
@include 'db_connection.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $supplyID = $_POST['supply_id'];
    $drugHeaderID = $_POST['drug_header_id'];
    $vendorID = $_POST['vendor_id'];
    $quantity = $_POST['quantity'];
    $manufactureDate = $_POST['manufacture_date'];
    $expiryDate = $_POST['expiry_date'];

    $sql = "INSERT INTO drug_supply (SupplyID, DrugHeaderID, VendorID, Quantity, Manufacture_Date, ExpiryDate) 
            VALUES ('$supplyID', '$drugHeaderID', '$vendorID', '$quantity', '$manufactureDate', '$expiryDate')";

    if (mysqli_query($conn, $sql)) {
        $_SESSION['message'] = "Supply record added successfully!";
    } else {
        $_SESSION['message'] = "Error adding supply record: " . mysqli_error($conn);
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
    <title>Add Drug Supply</title>
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
        <h1>Add Drug Supply</h1>
        <form method="post" action="add_inventory.php">
            <label>Supply ID:</label>
            <input type="text" name="supply_id" required>

            <label>Drug Header ID:</label>
            <input type="text" name="drug_header_id">

            <label>Vendor ID:</label>
            <input type="text" name="vendor_id">

            <label>Quantity:</label>
            <input type="number" name="quantity">

            <label>Manufacture Date:</label>
            <input type="date" name="manufacture_date">

            <label>Expiry Date:</label>
            <input type="date" name="expiry_date">

            <button type="submit" class="btn btn-primary">Add Supply</button>
            <a href="drug_inventory.php" class="btn btn-danger">Cancel</a>
        </form>
    </div>
</body>

</html>



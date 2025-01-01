<<?php
@include 'db_connection.php';
session_start();

// Check if the 'id' parameter is provided and valid
if (!isset($_GET['id']) || empty($_GET['id'])) {
    $_SESSION['message'] = "Invalid request. Supply ID not provided.";
    header("Location: drug_supply.php");
    exit();
}

$supplyID = $_GET['id'];

// Fetch the supply record
$query = "SELECT * FROM drug_supply WHERE SupplyID = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("s", $supplyID);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    $_SESSION['message'] = "Supply record not found.";
    header("Location: drug_supply.php");
    exit();
}

$supply = $result->fetch_assoc();
$stmt->close();

// Handle the form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Sanitize and validate user inputs
    $drugHeaderID = $_POST['drug_header_id'];
    $vendorID = $_POST['vendor_id'];
    $quantity = intval($_POST['quantity']);
    $manufactureDate = $_POST['manufacture_date'];
    $expiryDate = $_POST['expiry_date'];

    // Update the supply record
    $sql = "UPDATE drug_supply 
            SET DrugHeaderID = ?, VendorID = ?, Quantity = ?, Manufacture_Date = ?, ExpiryDate = ? 
            WHERE SupplyID = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssisss", $drugHeaderID, $vendorID, $quantity, $manufactureDate, $expiryDate, $supplyID);

    if ($stmt->execute()) {
        $_SESSION['message'] = "Supply record updated successfully!";
    } else {
        $_SESSION['message'] = "Error updating supply record: " . $conn->error;
    }

    $stmt->close();
    header("Location: drug_inventory.php");
    exit();
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Drug</title>
    <style>
        /* General body styles */
        body {
            font-family: Arial, sans-serif;
            background: #f5f5f5;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .container {
            width: 400px;
            background-color: white;
            padding: 20px 30px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            text-align: left;
        }

        h1 {
            font-size: 1.8em;
            text-align: center;
            color: #333;
            margin-bottom: 20px;
        }

        form {
            display: flex;
            flex-direction: column;
            gap: 15px;
        }

        label {
            font-weight: bold;
            margin-bottom: 5px;
        }

        input[type="text"],
        input[type="number"],
        input[type="date"] {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 1em;
            box-sizing: border-box;
        }

        .btn {
            width: 48%;
            padding: 10px;
            border-radius: 5px;
            text-decoration: none;
            color: white;
            font-size: 1em;
            text-align: center;
            border: none;
            cursor: pointer;
            display: inline-block;
        }

        .btn-green {
            background-color: #28a745;
        }

        .btn-green:hover {
            background-color: #218838;
        }

        .btn-red {
            background-color: #dc3545;
        }

        .btn-red:hover {
            background-color: #c82333;
        }

        .button-group {
            display: flex;
            justify-content: space-between;
        }
    </style>
</head>

<body>
    <div class="container">
        <h1>Edit Drug Supply</h1>
        <form method="post" action="">
            <label>Drug Header ID:</label>
            <input type="text" name="drug_header_id" value="<?php echo htmlspecialchars($supply['DrugHeaderID']); ?>">

            <label>Vendor ID:</label>
            <input type="text" name="vendor_id" value="<?php echo htmlspecialchars($supply['VendorID']); ?>">

            <label>Quantity:</label>
            <input type="number" name="quantity" value="<?php echo htmlspecialchars($supply['Quantity']); ?>" required>

            <label>Manufacture Date:</label>
            <input type="date" name="manufacture_date" value="<?php echo htmlspecialchars($supply['Manufacture_Date']); ?>">

            <label>Expiry Date:</label>
            <input type="date" name="expiry_date" value="<?php echo htmlspecialchars($supply['ExpiryDate']); ?>">

            <div class="button-group">
                <button type="submit" class="btn btn-green">Update Supply</button>
                <a href="drug_inventory.php" class="btn btn-red">Cancel</a>
            </div>
        </form>
    </div>
</body>

</html>




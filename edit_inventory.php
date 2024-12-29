<?php
@include 'db_connection.php';
session_start();

// Check if the 'id' parameter is provided and valid
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    $_SESSION['message'] = "Invalid request. Drug ID not provided or invalid.";
    header("Location: drug_inventory.php");
    exit();
}

$inventoryID = $_GET['id'];

// Fetch the drug record
$query = "SELECT * FROM drug_inventory WHERE InventoryID = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $inventoryID);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    $_SESSION['message'] = "Drug not found.";
    header("Location: drug_inventory.php");
    exit();
}

$drug = $result->fetch_assoc();
$stmt->close();

// Handle the form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Sanitize and validate user inputs
    $inventoryID = $_POST['inventory_id'];
    $currentStock = intval($_POST['current_stock']);
    $reorderLevel = intval($_POST['reorder_level']);
    $lastRestockDate = $_POST['last_restock_date'];
    $expirationDate = $_POST['expiration_date'];

    // Update the drug record
    $sql = "UPDATE drug_inventory 
            SET CurrentStock = ?, ReorderLevel = ?, LastRestockDate = ?, ExpirationDate = ? 
            WHERE InventoryID = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("iissi", $currentStock, $reorderLevel, $lastRestockDate, $expirationDate, $inventoryID);

    if ($stmt->execute()) {
        $_SESSION['message'] = "Drug updated successfully!";
    } else {
        $_SESSION['message'] = "Error updating drug: " . $conn->error;
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
        <h1>Edit Drug</h1>
        <form method="post" action="">
            <input type="hidden" name="inventory_id" value="<?php echo htmlspecialchars($drug['InventoryID']); ?>">

            <label>Current Stock:</label>
            <input type="number" name="current_stock" value="<?php echo htmlspecialchars($drug['CurrentStock']); ?>" required>

            <label>Reorder Level:</label>
            <input type="number" name="reorder_level" value="<?php echo htmlspecialchars($drug['ReorderLevel']); ?>" required>

            <label>Last Restock Date:</label>
            <input type="date" name="last_restock_date" value="<?php echo htmlspecialchars($drug['LastRestockDate']); ?>">

            <label>Expiration Date:</label>
            <input type="date" name="expiration_date" value="<?php echo htmlspecialchars($drug['ExpirationDate']); ?>">

            <div class="button-group">
                <button type="submit" class="btn btn-green">Update Drug</button>
                <a href="drug_inventory.php" class="btn btn-red">Cancel</a>
            </div>
        </form>
    </div>
</body>

</html>




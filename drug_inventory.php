<?php
// Include database connection
@include 'db_connection.php';
session_start();

// Handle form submissions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['add_inventory'])) {
        // Add a new drug to the inventory
        $drugID = $_POST['drug_id']; // Assuming you want to use a drug ID instead of the drug name for identification
        $currentStock = $_POST['current_stock'];
        $reorderLevel = $_POST['reorder_level'];
        $lastRestockDate = $_POST['last_restock_date'];
        $expirationDate = $_POST['expiration_date'];

        $sql = "INSERT INTO drug_inventory (DrugID, CurrentStock, ReorderLevel, LastRestockDate, ExpirationDate) 
                VALUES ('$drugID', '$currentStock', '$reorderLevel', '$lastRestockDate', '$expirationDate')";
        if (mysqli_query($conn, $sql)) {
            echo "<script>alert('Drug added successfully!'); window.location.href = 'drug_inventory.php';</script>";
        } else {
            echo "<script>alert('Error adding drug: " . mysqli_error($conn) . "');</script>";
        }
    } elseif (isset($_POST['update_stock'])) {
        // Update the stock of an existing drug
        $inventoryID = $_POST['inventory_id'];
        $newStock = $_POST['new_stock'];

        $sql = "UPDATE drug_inventory SET CurrentStock = '$newStock' WHERE InventoryID = '$inventoryID'";
        if (mysqli_query($conn, $sql)) {
            echo "<script>alert('Stock updated successfully!'); window.location.href = 'drug_inventory.php';</script>";
        } else {
            echo "<script>alert('Error updating stock: " . mysqli_error($conn) . "');</script>";
        }
    }
}

// Fetch drug inventory data from the database
$query = "SELECT * FROM drug_inventory";
$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Drug Inventory</title>
    <style>
        /* General body styles */
        body {
            font-family: Arial, sans-serif;
            background: linear-gradient(135deg, #ff9a3c, #f83600); /* Gradient background */
            margin: 0;
            padding: 0;
        }

        .container {
            width: 80%;
            margin: 20px auto;
            background-color: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        h1 {
            font-size: 2.5em;
            text-align: center;
            color: #f83600; /* Dark orange color */
            font-weight: bold;
        }

        .btn {
            padding: 10px 15px;
            margin: 5px;
            border-radius: 5px;
            text-decoration: none;
            color: white;
            font-size: 0.9em;
            transition: background-color 0.3s ease;
        }

        .btn-primary {
            background-color: #28a745;
        }

        .btn-primary:hover {
            background-color: #218838;
        }

        .btn-warning {
            background-color: #ffc107;
        }

        .btn-warning:hover {
            background-color: #e0a800;
        }

        .btn-danger {
            background-color: #dc3545;
        }

        .btn-danger:hover {
            background-color: #c82333;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        table th,
        table td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        table th {
            background-color: #f1f1f1;
        }

        table tr:hover {
            background-color: #f9f9f9;
        }
    </style>
</head>

<body>
    <div class="container">
        <h1>Drug Inventory Management</h1>
        <a href="#addDrugModal" class="btn btn-primary" onclick="document.getElementById('addDrugModal').style.display='block'">Add Drug</a>
        <table>
            <thead>
                <tr>
                    <th>Inventory ID</th>
                    <th>Drug ID</th>
                    <th>Current Stock</th>
                    <th>Reorder Level</th>
                    <th>Last Restock Date</th>
                    <th>Expiration Date</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = mysqli_fetch_assoc($result)) { ?>
                    <tr>
                        <td><?php echo htmlspecialchars($row['InventoryID']); ?></td>
                        <td><?php echo htmlspecialchars($row['DrugID']); ?></td>
                        <td><?php echo htmlspecialchars($row['CurrentStock']); ?></td>
                        <td><?php echo htmlspecialchars($row['ReorderLevel']); ?></td>
                        <td><?php echo htmlspecialchars($row['LastRestockDate']); ?></td>
                        <td><?php echo htmlspecialchars($row['ExpirationDate']); ?></td>
                        <td>
                            <a href="edit_drug.php?id=<?php echo $row['InventoryID']; ?>" class="btn btn-warning">Edit</a>
                            <a href="delete_drug.php?id=<?php echo $row['InventoryID']; ?>" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this drug stock?');">Delete</a>
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>

    <!-- Add Drug Modal -->
    <div id="addDrugModal" style="display:none; background-color: rgba(0, 0, 0, 0.5); position: fixed; top: 0; left: 0; width: 100%; height: 100%; padding: 50px;">
        <div style="background: white; padding: 20px; border-radius: 10px; max-width: 500px; margin: auto;">
            <h2>Add Drug</h2>
            <form method="post" action="">
                <label>Drug ID:</label><br>
                <input type="text" name="drug_id" required><br><br>
                <label>Current Stock:</label><br>
                <input type="number" name="current_stock" required><br><br>
                <label>Reorder Level:</label><br>
                <input type="number" name="reorder_level" required><br><br>
                <label>Last Restock Date:</label><br>
                <input type="date" name="last_restock_date"><br><br>
                <label>Expiration Date:</label><br>
                <input type="date" name="expiration_date"><br><br>
                <button type="submit" name="add_inventory" class="btn btn-primary">Add Drug</button>
                <button type="button" class="btn btn-danger" onclick="document.getElementById('addDrugModal').style.display='none'">Cancel</button>
            </form>
        </div>
    </div>
</body>

</html>

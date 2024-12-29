<?php
// Include database connection
@include 'db_connection.php';
session_start();

// Handle form submissions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['add_inventory'])) {
        // Add a new drug to the inventory
        $drugID = $_POST['drug_id'];
        $currentStock = $_POST['current_stock'];
        $reorderLevel = $_POST['reorder_level'];
        $lastRestockDate = $_POST['last_restock_date'];
        $expirationDate = $_POST['expiration_date'];

        // Prepare the SQL statement to avoid SQL injection
        $stmt = $conn->prepare("INSERT INTO drug_inventory (DrugID, CurrentStock, ReorderLevel, LastRestockDate, ExpirationDate) 
                                VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("siiis", $drugID, $currentStock, $reorderLevel, $lastRestockDate, $expirationDate);

        if ($stmt->execute()) {
            echo "<script>alert('Drug added successfully!'); window.location.href = 'drug_inventory.php';</script>";
        } else {
            echo "<script>alert('Error adding drug: " . mysqli_error($conn) . "');</script>";
        }
        $stmt->close();
    } elseif (isset($_POST['edit_inventory'])) {
        // Update an existing drug in the inventory
        $inventoryID = $_POST['inventory_id'];
        $drugID = $_POST['drug_id'];
        $currentStock = $_POST['current_stock'];
        $reorderLevel = $_POST['reorder_level'];
        $lastRestockDate = $_POST['last_restock_date'];
        $expirationDate = $_POST['expiration_date'];

        // Prepare SQL for updating
        $stmt = $conn->prepare("UPDATE drug_inventory SET DrugID = ?, CurrentStock = ?, ReorderLevel = ?, LastRestockDate = ?, ExpirationDate = ? 
                                WHERE InventoryID = ?");
        $stmt->bind_param("siiisi", $drugID, $currentStock, $reorderLevel, $lastRestockDate, $expirationDate, $inventoryID);

        if ($stmt->execute()) {
            echo "<script>alert('Drug updated successfully!'); window.location.href = 'drug_inventory.php';</script>";
        } else {
            echo "<script>alert('Error updating drug: " . mysqli_error($conn) . "');</script>";
        }
        $stmt->close();
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
    <title>Drug Inventory Management</title>
    <style>
        /* General body styles */
        body {
            font-family: Arial, sans-serif;
            background: linear-gradient(135deg, #ff9a3c, #f83600);
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
            color: #f83600;
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
        <a href="#" class="btn btn-primary" onclick="openAddDrugModal()">Add Drug</a>
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
                            <a href="#" class="btn btn-warning" onclick='openEditDrugModal(<?php echo json_encode($row); ?>)'>Edit</a>
                            <a href="delete_inventory.php?id=<?php echo $row['InventoryID']; ?>" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this drug stock?');">Delete</a>
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>

    <!-- Add/Edit Drug Modal -->
    <div id="drugModal" style="display:none; background-color: rgba(0, 0, 0, 0.5); position: fixed; top: 0; left: 0; width: 100%; height: 100%; padding: 50px;">
        <div style="background: white; padding: 20px; border-radius: 10px; max-width: 500px; margin: auto;">
            <h2 id="modalTitle">Add Drug</h2>
            <form id="drugForm" method="post" action="">
                <input type="hidden" name="inventory_id" id="inventoryId">

                <label>Drug ID:</label><br>
                <input type="text" name="drug_id" id="drugId" required><br><br>

                <label>Current Stock:</label><br>
                <input type="number" name="current_stock" id="currentStock" required><br><br>

                <label>Reorder Level:</label><br>
                <input type="number" name="reorder_level" id="reorderLevel" required><br><br>

                <label>Last Restock Date:</label><br>
                <input type="date" name="last_restock_date" id="lastRestockDate"><br><br>

                <label>Expiration Date:</label><br>
                <input type="date" name="expiration_date" id="expirationDate"><br><br>

                <button type="submit" id="submitButton" name="add_inventory" class="btn btn-primary">Add Drug</button>
                <button type="button" class="btn btn-danger" onclick="closeModal()">Cancel</button>
            </form>
        </div>
    </div>

    <script>
        function openAddDrugModal() {
            document.getElementById('modalTitle').innerText = 'Add Drug';
            document.getElementById('submitButton').innerText = 'Add Drug';
            document.getElementById('submitButton').name = 'add_inventory';
            clearForm();
            document.getElementById('drugModal').style.display = 'block';
        }

        function openEditDrugModal(drug) {
            document.getElementById('modalTitle').innerText = 'Edit Drug';
            document.getElementById('submitButton').innerText = 'Update Drug';
            document.getElementById('submitButton').name = 'edit_inventory';
            populateForm(drug);
            document.getElementById('drugModal').style.display = 'block';
        }

        function closeModal() {
            document.getElementById('drugModal').style.display = 'none';
        }

        function clearForm() {
            document.getElementById('inventoryId').value = '';
            document.getElementById('drugId').value = '';
            document.getElementById('currentStock').value = '';
            document.getElementById('reorderLevel').value = '';
            document.getElementById('lastRestockDate').value = '';
            document.getElementById('expirationDate').value = '';
        }

        function populateForm(drug) {
            document.getElementById('inventoryId').value = drug.InventoryID;
            document.getElementById('drugId').value = drug.DrugID;
            document.getElementById('currentStock').value = drug.CurrentStock;
            document.getElementById('reorderLevel').value = drug.ReorderLevel;
            document.getElementById('lastRestockDate').value = drug.LastRestockDate;
            document.getElementById('expirationDate').value = drug.ExpirationDate;
        }
    </script>
</body>

</html>






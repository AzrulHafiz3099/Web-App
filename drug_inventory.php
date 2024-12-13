<?php
// Assuming you have a database connection setup in a separate file
@include 'db.php';
session_start();

// Fetch drug inventory data from the database
$query = "SELECT * FROM drug_stock";
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

        /* Container for the main content */
        .container {
            width: 80%;
            margin: 20px auto;
            background-color: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        /* Main header */
        h1 {
            font-size: 2.5em;
            color: #333;
            margin-bottom: 20px;
            text-align: center;
            color: #f83600; /* Dark orange color */
            font-weight: bold;
        }

        /* Button styling */
        .btn {
            padding: 10px 15px;
            margin: 5px;
            border-radius: 5px;
            text-decoration: none;
            color: white;
            font-size: 0.9em;
            transition: background-color 0.3s ease;
        }

        /* Add Drug button */
        .btn-primary {
            background-color: #28a745;
        }

        .btn-primary:hover {
            background-color: #218838;
        }

        /* Edit button */
        .btn-warning {
            background-color: #ffc107;
        }

        .btn-warning:hover {
            background-color: #e0a800;
        }

        /* Delete button */
        .btn-danger {
            background-color: #dc3545;
        }

        .btn-danger:hover {
            background-color: #c82333;
        }

        /* Table styles */
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
        <h1>Drug Inventory</h1>
        <a href="add_drug.php" class="btn btn-primary">Add Drug</a>
        <table>
            <thead>
                <tr>
                    <th>Drug ID</th>
                    <th>Drug Name</th>
                    <th>Current Stock</th>
                    <th>Reorder Level</th>
                    <th>Last Updated</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = mysqli_fetch_assoc($result)) { ?>
                    <tr>
                        <td><?php echo htmlspecialchars($row['DrugID']); ?></td>
                        <td><?php echo htmlspecialchars($row['drug_name']); ?></td>
                        <td><?php echo htmlspecialchars($row['Current_Stock']); ?></td>
                        <td><?php echo htmlspecialchars($row['Reorder_Level']); ?></td>
                        <td><?php echo htmlspecialchars($row['Last_Updated']); ?></td>
                        <td>
                            <a href="edit_drug.php?id=<?php echo $row['StockID']; ?>" class="btn btn-warning">Edit</a>
                            <a href="delete_drug.php?id=<?php echo $row['StockID']; ?>" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this drug stock?');">Delete</a>
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
</body>

</html>

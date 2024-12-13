<?php
@include 'db.php';
session_start();

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $drug_id = $_POST['drug_id'];
    $current_stock = $_POST['current_stock'];
    $reorder_level = $_POST['reorder_level'];

    // Insert the drug stock data into the database
    $query = "INSERT INTO drug_stock (DrugID, Current_Stock, Reorder_Level) VALUES ('$drug_id', '$current_stock', '$reorder_level')";
    if (mysqli_query($conn, $query)) {
        echo '<script>alert("Drug added successfully!"); window.location.href = "drug_inventory.php";</script>';
    } else {
        echo '<script>alert("Error: ' . mysqli_error($conn) . '");</script>';
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Drug</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            margin: 0;
            padding: 0;
            background: linear-gradient(135deg, #fdfcfb, #f3c623);
        }

        .sidebar {
            width: 250px;
            height: 100vh;
            background: linear-gradient(135deg, #ff9a3c, #f83600);
            color: white;
            position: fixed;
            top: 0;
            left: 0;
            display: flex;
            flex-direction: column;
            box-shadow: 2px 0 5px rgba(0, 0, 0, 0.1);
            border-radius: 0 20px 20px 0;
        }

        .sidebar a {
            text-decoration: none;
            color: white;
            padding: 15px 20px;
            display: block;
            font-size: 1.1em;
            transition: background-color 0.3s ease, color 0.3s ease;
        }

        .sidebar a:hover {
            background-color: rgba(255, 255, 255, 0.2);
            color: #ffd700;
        }

        .sidebar .sidebar-brand {
            font-size: 1.5em;
            text-align: center;
            margin: 20px 0;
        }

        .content-wrapper {
            margin-left: 250px;
            padding: 20px;
            min-height: 100vh;
        }

        .topbar {
            background-color: white;
            padding: 15px 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
            border-radius: 12px;
        }

        .header-title {
            font-size: 2em;
            color: #f83600;
            font-weight: bold;
            margin-bottom: 20px;
            border-left: 5px solid #ff9a3c;
            padding-left: 10px;
        }

        .form-container {
            background-color: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .form-group {
            margin-bottom: 15px;
        }

        label {
            font-size: 1em;
            margin-bottom: 5px;
        }

        input[type="text"], input[type="number"] {
            width: 100%;
            padding: 8px;
            font-size: 1em;
            border-radius: 5px;
            border: 1px solid #ddd;
        }

        button {
            width: 100%;
            padding: 10px;
            background-color: #28a745;
            color: white;
            font-size: 1.2em;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        button:hover {
            background-color: #218838;
        }
    </style>
</head>
<body>
    <!-- Sidebar -->
    <div class="sidebar">
        <div class="sidebar-brand">
            <i class="fas fa-pills"></i> Pharmacy Manager
        </div>
        <a href="profile.php">Profile</a>
        <a href="index.php">Dashboard</a>
        <a href="pharmacy_accounts.php">Pharmacy Accounts</a>
        <a href="drug_inventory.php">Drug Inventory</a>
        <a href="logout.php">Logout</a>
    </div>

    <!-- Content Wrapper -->
    <div class="content-wrapper">
        <!-- Topbar -->
        <div class="topbar">
            <div class="user-info">
                <i class="fas fa-user-circle"></i>
                <span>Welcome, User</span>
            </div>
        </div>

        <!-- Main Content -->
        <div class="header-title">Add Drug</div>
        <div class="form-container">
            <form action="add_drug.php" method="POST">
                <div class="form-group">
                    <label for="drug_id">Drug ID</label>
                    <input type="number" id="drug_id" name="drug_id" required>
                </div>
                <div class="form-group">
                    <label for="current_stock">Current Stock</label>
                    <input type="number" id="current_stock" name="current_stock" required>
                </div>
                <div class="form-group">
                    <label for="reorder_level">Reorder Level</label>
                    <input type="number" id="reorder_level" name="reorder_level" value="10" required>
                </div>
                <button type="submit">Add Drug</button>
            </form>
        </div>
    </div>
</body>
</html>

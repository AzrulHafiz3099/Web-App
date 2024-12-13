<?php
@include 'db.php';
session_start();

// Check if the drug ID is provided
if (!isset($_GET['id'])) {
    echo '<script>alert("No drug ID provided."); window.location.href = "drug_inventory.php";</script>';
    exit;
}

$drug_id = $_GET['id'];

// Fetch the existing drug stock data from the database
$query = "SELECT * FROM drug_stock WHERE DrugID = '$drug_id'";
$result = mysqli_query($conn, $query);
$drug = mysqli_fetch_assoc($result);

// Check if the drug exists
if (!$drug) {
    echo '<script>alert("Drug not found."); window.location.href = "drug_inventory.php";</script>';
    exit;
}

// Update drug stock data if the form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $current_stock = $_POST['current_stock'];
    $reorder_level = $_POST['reorder_level'];

    // Update the drug stock data in the database
    $update_query = "UPDATE drug_stock SET Current_Stock = '$current_stock', Reorder_Level = '$reorder_level' WHERE DrugID = '$drug_id'";
    if (mysqli_query($conn, $update_query)) {
        echo '<script>alert("Drug updated successfully!"); window.location.href = "drug_inventory.php";</script>';
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
    <title>Edit Drug</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <style>
        /* Same styles as in add_drug.php */
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
        <div class="header-title">Edit Drug Stock</div>
        <div class="form-container">
            <form action="edit_drug.php?id=<?php echo $drug['DrugID']; ?>" method="POST">
                <div class="form-group">
                    <label for="drug_name">Drug ID</label>
                    <input type="text" id="drug_name" name="drug_name" value="<?php echo htmlspecialchars($drug['DrugID']); ?>" disabled>
                </div>
                <div class="form-group">
                    <label for="current_stock">Current Stock</label>
                    <input type="number" id="current_stock" name="current_stock" value="<?php echo htmlspecialchars($drug['Current_Stock']); ?>" required>
                </div>
                <div class="form-group">
                    <label for="reorder_level">Reorder Level</label>
                    <input type="number" id="reorder_level" name="reorder_level" value="<?php echo htmlspecialchars($drug['Reorder_Level']); ?>" required>
                </div>
                <button type="submit">Update Drug Stock</button>
            </form>
        </div>
    </div>
</body>
</html>


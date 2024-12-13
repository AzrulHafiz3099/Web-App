<?php
session_start();
@include 'db.php';

// Check if the user is logged in
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header('Location: login.php');
    exit;
}

// Fetch logged-in user's details
$UserID = $_SESSION['UserID'];
$sql = "SELECT Fullname, Role FROM user_account WHERE UserID = '$UserID'";
$result = mysqli_query($conn, $sql);

if (mysqli_num_rows($result) > 0) {
    $user = mysqli_fetch_assoc($result);
} else {
    echo '<script>alert("User data not found!");</script>';
    exit;
}

// Fetch counts for dashboard
$pharmacy_accounts = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM user_account"))['total'];
$drug_inventory = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM Drug_Details"))['total'];
$supply_orders = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM Drug_Orders"))['total'];
$current_stock = mysqli_fetch_assoc(mysqli_query($conn, "SELECT SUM(Current_Stock) as total FROM Drug_Stock"))['total'];
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pharmacy Management Dashboard</title>
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

        .topbar .user-info {
            display: flex;
            align-items: center;
            color: #f83600;
        }

        .topbar .user-info i {
            margin-right: 10px;
            font-size: 1.5em;
        }

        .header-title {
            font-size: 2em;
            color: #f83600;
            font-weight: bold;
            margin-bottom: 20px;
            border-left: 5px solid #ff9a3c;
            padding-left: 10px;
        }

        .card {
            background-color: white;
            border-radius: 20px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            padding: 20px;
            margin-bottom: 20px;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex: 1;
            min-width: 200px;
        }

        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.15);
        }

        .card-body {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .text-xs {
            font-size: 0.9em;
            color: #6c757d;
            margin-bottom: 10px;
            text-transform: uppercase;
            font-weight: bold;
        }

        .h5 {
            font-size: 1.8em;
            margin: 0;
            color: #f83600;
        }

        .card i {
            font-size: 2em;
            color: #ddd;
        }

        .row {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
            justify-content: flex-start;
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
                <span>Welcome, <?= htmlspecialchars($user['Fullname']); ?> (<?= htmlspecialchars($user['Role']); ?>)</span>
            </div>
        </div>

        <!-- Dashboard Overview -->
        <div class="header-title">Dashboard Overview</div>

        <div class="row">
            <!-- Pharmacy Accounts -->
            <div class="col">
                <div class="card">
                    <div class="card-body">
                        <div>
                            <div class="text-xs">Pharmacy Accounts</div>
                            <div class="h5"><?= $pharmacy_accounts; ?></div>
                        </div>
                        <i class="fas fa-user"></i>
                    </div>
                </div>
            </div>

            <!-- Drug Inventory -->
            <div class="col">
                <div class="card">
                    <div class="card-body">
                        <div>
                            <div class="text-xs">Drug Inventory</div>
                            <div class="h5"><?= $drug_inventory; ?></div>
                        </div>
                        <i class="fas fa-boxes"></i>
                    </div>
                </div>
            </div>

            <!-- Supply Orders -->
            <div class="col">
                <div class="card">
                    <div class="card-body">
                        <div>
                            <div class="text-xs">Supply Orders</div>
                            <div class="h5"><?= $supply_orders; ?></div>
                        </div>
                        <i class="fas fa-truck"></i>
                    </div>
                </div>
            </div>

            <!-- Current Stock -->
            <div class="col">
                <div class="card">
                    <div class="card-body">
                        <div>
                            <div class="text-xs">Total Stock</div>
                            <div class="h5"><?= $current_stock ? $current_stock : 0; ?></div>
                        </div>
                        <i class="fas fa-cubes"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

</html>
              

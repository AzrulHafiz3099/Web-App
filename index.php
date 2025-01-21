<?php
session_start();
@include 'db_connection.php';

// Check if the user is logged in
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header('Location: login.php');
    exit;
}

// Fetch logged-in user's details securely
$UserID = $_SESSION['UserID'];
$sql = "SELECT Fullname, Role FROM user_account WHERE UserID = ?";
if ($stmt = mysqli_prepare($conn, $sql)) {
    mysqli_stmt_bind_param($stmt, "s", $UserID);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    
    if ($result && mysqli_num_rows($result) > 0) {
        $user = mysqli_fetch_assoc($result);
    } else {
        echo '<script>alert("User data not found!");</script>';
        exit;
    }
    mysqli_stmt_close($stmt);
} else {
    echo "Error preparing statement: " . mysqli_error($conn);
    exit;
}

// Fetch counts for dashboard
$pharmacy_accounts = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM vendor"))['total'];
$drug_inventory = mysqli_fetch_assoc(mysqli_query($conn, "SELECT SUM(Quantity) as total FROM drug_supply"))['total'];
$supply_orders = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM `order`"))['total'];
$drug_details = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM `drug_details`"))['total'];

// Fetch low-stock drugs (Quantity below 10)
$low_stock_query = "SELECT DrugName, Quantity FROM drug_supply WHERE Quantity < 10";
$low_stock_result = mysqli_query($conn, $low_stock_query);

$low_stock_drugs = [];
if ($low_stock_result && mysqli_num_rows($low_stock_result) > 0) {
    while ($row = mysqli_fetch_assoc($low_stock_result)) {
        $low_stock_drugs[] = $row;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pharmacy Manager</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
    <!-- Include Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&family=Poppins:wght@500;700&display=swap" rel="stylesheet">
    <style>
        /* General Styling */
        body {
            font-family: 'Poppins', sans-serif;
            margin: 0;
            padding: 0;
            background: linear-gradient(135deg, #fdfcfb, #f3c623);
        }

        /* Sidebar */
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

        /* Logo Styling */
        .logo {
            display: flex;
            align-items: center;
            gap: 10px;
            margin: 20px;
        }

        .logo-icon {
            background: linear-gradient(135deg, #ff9a3c, #f83600);
            width: 40px;
            height: 40px;
            display: flex;
            justify-content: center;
            align-items: center;
            border-radius: 50%;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .logo-icon i {
            color: white;
            font-size: 1.5em;
        }

        .logo h2 {
            font-size: 1.7em;
            font-weight: bold;
            color: #fdfcfb;
            margin: 0;
            font-family: 'Roboto', sans-serif; /* Apply the custom font */
            text-shadow: 1px 1px 5px rgba(0, 0, 0, 0.2);
        }

        /* Main Content Area */
        .content-wrapper {
            margin-left: 250px;
            padding: 20px;
            min-height: 100vh;
        }

        /* User Info Section */
        .user-info {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 20px;
            background-color: #fdfcfb;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
        }

        .user-info h2 {
            font-size: 1.8em;
            color: #f83600;
            font-weight: bold;
            margin: 0;
        }

        .user-info span {
            font-size: 1.2em;
            color: #555;
        }

        /* Box Layout */
        .content-boxes {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
        }

        .box {
            flex: 1 1 calc(25% - 20px); /* Boxes take 25% width, minus gap */
            background: white;
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            text-align: center;
            color: #333;
            font-size: 1.2em;
            font-weight: bold;
        }

        .box p {
            margin: 0;
            font-size: 1.5em;
            color: #f83600;
        }

        .box i {
            font-size: 2em;
            color: #ff9a3c;
            margin-bottom: 10px;
        }

        /* Button Styling */
        .report-button {
            margin-top: 20px;
            text-align: center;
        }

        .report-button a button {
            background: #ff9a3c;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            font-size: 1em;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .report-button a button:hover {
            background-color: #f83600;
        }

        /* Low Stock Section */
        .low-stock {
            background: white;
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
        }

        .low-stock ul {
            list-style: none;
            padding: 0;
        }

        .low-stock li {
            background: #fff4e0;
            margin-bottom: 10px;
            padding: 10px;
            border-radius: 5px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
    </style>
</head>
<body>
    <div class="sidebar">
        <!-- Logo -->
        <div class="logo">
            <div class="logo-icon">
                <i class="fas fa-pills"></i>
            </div>
            <h2>Pharmacy Manager</h2>
        </div>

        <!-- Sidebar Navigation -->
        <a href="#">Home</a>
        <a href="manage_drug.php">Manage Drugs</a>
        <a href="drug_inventory.php">Inventory</a>
        <a href="vendor.php">Vendor</a>
        <a href="report.php">Report</a>
        <a href="logout.php">Logout</a>
    </div>

    <div class="content-wrapper">
        <!-- User Info Section -->
        <div class="user-info">
            <div>
                <h2>Welcome, <?php echo $user['Fullname']; ?>!</h2>
                <span>Role: <?php echo $user['Role']; ?></span>
            </div>
            <div>
                <a href="profile.php" title="View Profile">
                    <i class="fas fa-user-circle" style="font-size: 3em; color: #ff9a3c; cursor: pointer;"></i>
                </a>
            </div>
        </div>

        <!-- Low Stock Alert -->
        <div class="low-stock">
            <h2 style="color: #f83600;">Low Stock Alert</h2>
            <?php if (!empty($low_stock_drugs)) { ?>
                <ul>
                    <?php foreach ($low_stock_drugs as $drug) { ?>
                        <li>
                            <strong><?php echo $drug['DrugName']; ?></strong>: <?php echo $drug['Quantity']; ?> left
                        </li>
                    <?php } ?>
                </ul>
            <?php } else { ?>
                <p style="color: #555;">All drug inventory levels are sufficient.</p>
            <?php } ?>
        </div>

        <!-- Content Boxes -->
        <div class="content-boxes">
            <div class="box">
                <i class="fas fa-user-md"></i>
                <p><?php echo $pharmacy_accounts; ?></p>
                Vendor
            </div>
            <div class="box">
                <i class="fas fa-pills"></i>
                <p><?php echo $drug_details; ?></p>
                Drug Details
            </div>
            <div class="box">
                <i class="fas fa-truck"></i>
                <p><?php echo $supply_orders; ?></p>
                Supply Orders
            </div>
            <div class="box">
                <i class="fas fa-box-open"></i>
                <p><?php echo $drug_inventory; ?></p>
                Drug Stock 
            </div>
        </div>

        <!-- Download Report Button -->
        <div class="report-button">
            <a href="generate_report.php">
                <button>Download Dashboard Report</button>
            </a>
        </div>
    </div>
</body>
</html>

<?php
session_start();
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

        .sidebar .sidebar-brand-icon {
            margin-right: 10px;
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

        .card {
            background-color: white;
            border-radius: 20px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            padding: 20px;
            margin-bottom: 20px;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.15);
        }

        .card .card-body {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .card .text-xs {
            font-size: 0.9em;
            color: #6c757d;
            margin-bottom: 10px;
            text-transform: uppercase;
            font-weight: bold;
        }

        .card .h5 {
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
        }

        .col {
            flex: 1;
            min-width: 200px;
        }

        .header-title {
            font-size: 2em;
            color: #f83600;
            font-weight: bold;
            margin-bottom: 20px;
            border-left: 5px solid #ff9a3c;
            padding-left: 10px;
        }

        .button {
            background: linear-gradient(135deg, #ff9a3c, #f83600);
            border: none;
            border-radius: 25px;
            padding: 10px 20px;
            color: white;
            font-size: 1em;
            cursor: pointer;
            transition: background 0.3s ease;
        }

        .button:hover {
            background: linear-gradient(135deg, #f83600, #ff9a3c);
        }

        @media (max-width: 768px) {
            .sidebar {
                width: 100%;
                height: auto;
                position: relative;
            }

            .content-wrapper {
                margin-left: 0;
            }

            .topbar {
                flex-direction: column;
                align-items: flex-start;
            }

            .row {
                flex-direction: column;
            }
        }
    </style>
</head>

<body>
    <!-- Sidebar -->
    <div class="sidebar">
        <div class="sidebar-brand">
            <i class="fas fa-pills"></i> Pharmacy Manager
        </div>
        <a href="index.php">Dashboard</a>
        <a href="pharmacy_accounts.php">Pharmacy Accounts</a>
        <a href="drug_inventory.php">Drug Inventory</a>
    </div>

    <!-- Content Wrapper -->
    <div class="content-wrapper">
        <!-- Topbar -->
        <div class="topbar">
            <div class="user-info">
                <i class="fas fa-user-circle"></i>
                <span>Welcome, Admin</span>
            </div>
        </div>

        <!-- Main Dashboard Content -->
        <div class="header-title">Dashboard Overview</div>

        <div class="row">
            <div class="col">
                <div class="card">
                    <div class="card-body">
                        <div>
                            <div class="text-xs">Pharmacy Accounts</div>
                            <div class="h5">5</div>
                        </div>
                        <i class="fas fa-user"></i>
                    </div>
                </div>
            </div>

            <div class="col">
                <div class="card">
                    <div class="card-body">
                        <div>
                            <div class="text-xs">Drug Inventory</div>
                            <div class="h5">20</div>
                        </div>
                        <i class="fas fa-boxes"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

</html>
              
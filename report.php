<?php
// Include database connection
@include 'db_connection.php';
session_start();

// Default date range: Last 30 days
$startDate = isset($_GET['start_date']) ? $_GET['start_date'] : date('Y-m-d', strtotime('-30 days'));
$endDate = isset($_GET['end_date']) ? $_GET['end_date'] : date('Y-m-d');

// Grouping (Day, Month, Year)
$grouping = isset($_GET['grouping']) ? $_GET['grouping'] : 'Day';

// SQL query based on grouping
$dateFormat = $grouping === 'Month' ? '%Y-%m' : ($grouping === 'Year' ? '%Y' : '%Y-%m-%d');
$query = "
    SELECT 
        DATE_FORMAT(OrderDate, '$dateFormat') AS GroupedDate, 
        SUM(TotalPrice) AS TotalSales, 
        COUNT(OrderID) AS TotalOrders 
    FROM `order`
    WHERE OrderDate BETWEEN ? AND ?
    GROUP BY GroupedDate
    ORDER BY GroupedDate ASC
";
$stmt = $conn->prepare($query);
$stmt->bind_param('ss', $startDate, $endDate);
$stmt->execute();
$result = $stmt->get_result();

// Prepare data for the chart
$dates = [];
$sales = [];
$orderCounts = [];

while ($row = $result->fetch_assoc()) {
    $dates[] = $row['GroupedDate'];
    $sales[] = $row['TotalSales'];
    $orderCounts[] = $row['TotalOrders'];
}

// Summary metrics
$summaryQuery = "
    SELECT 
        SUM(TotalPrice) AS TotalSales, 
        COUNT(OrderID) AS TotalOrders, 
        AVG(TotalPrice) AS AverageSales 
    FROM `order`
    WHERE OrderDate BETWEEN ? AND ?
";
$stmt = $conn->prepare($summaryQuery);
$stmt->bind_param('ss', $startDate, $endDate);
$stmt->execute();
$summaryResult = $stmt->get_result();
$summary = $summaryResult->fetch_assoc();

// Query to fetch total drugs purchased from each vendor
$vendorQuery = "
    SELECT 
        v.Fullname AS VendorName,
        SUM(ds.Quantity) AS TotalDrugsSupplied
    FROM 
        vendor v
    JOIN 
        drug_supply ds ON v.VendorID = ds.VendorID
    WHERE ds.Manufacture_Date BETWEEN ? AND ?
    GROUP BY v.VendorID
    ORDER BY TotalDrugsSupplied DESC
";
$stmt = $conn->prepare($vendorQuery);
$stmt->bind_param('ss', $startDate, $endDate);
$stmt->execute();
$vendorResult = $stmt->get_result();

// Prepare data for the vendor chart
$vendorNames = [];
$totalDrugs = [];

while ($row = $vendorResult->fetch_assoc()) {
    $vendorNames[] = $row['VendorName'];
    $totalDrugs[] = $row['TotalDrugsSupplied'];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Advanced Sales and Vendor Report</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&family=Poppins:wght@500;700&display=swap" rel="stylesheet">
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
            text-shadow: 1px 1px 5px rgba(0, 0, 0, 0.2);
        }

        .content-wrapper {
            margin-left: 250px;
            padding: 20px;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        h1 {
            text-align: center;
            font-size: 2em;
            color: #f83600;
            font-weight: bold;
            margin-bottom: 20px;
        }

        .filters {
            display: flex;
            gap: 10px;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }

        .filters input, .filters select, .filters button {
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 1em;
        }

        .filters button {
            background: #ff9a3c;
            color: white;
            border: none;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .filters button:hover {
            background-color: #f83600;
        }

        .summary-cards {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
            margin-bottom: 20px;
        }

        .card {
            flex: 1 1 calc(33.33% - 20px);
            background: linear-gradient(135deg, #fdfcfb, #f3c623);
            border-radius: 10px;
            padding: 20px;
            text-align: center;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .card h3 {
            margin-bottom: 10px;
            font-size: 1.5em;
            color: #333;
        }

        .card p {
            font-size: 2em;
            color: #f83600;
            margin: 0;
        }

        canvas {
            margin-top: 20px;
        }
    </style>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
    <div class="sidebar">
        <div class="logo">
            <div class="logo-icon">
                <i class="fas fa-pills"></i>
            </div>
            <h2>Pharmacy Manager</h2>
        </div>
        <a href="index.php">Home</a>
        <a href="manage_drug.php">Manage Drugs</a>
        <a href="drug_inventory.php">Inventory</a>
        <a href="vendor.php">Vendor</a>
        <a href="report.php">Report</a>
        <a href="logout.php">Logout</a>
    </div>

    <div class="content-wrapper">
        <div class="container">
            <h1>Advanced Sales Report</h1>

            <form method="GET" class="filters">
                <label>Start Date:
                    <input type="date" name="start_date" value="<?php echo htmlspecialchars($startDate); ?>">
                </label>

                <label>End Date:
                    <input type="date" name="end_date" value="<?php echo htmlspecialchars($endDate); ?>">
                </label>

                <label>Group By:
                    <select name="grouping">
                        <option value="Day" <?php echo $grouping === 'Day' ? 'selected' : ''; ?>>Day</option>
                        <option value="Month" <?php echo $grouping === 'Month' ? 'selected' : ''; ?>>Month</option>
                        <option value="Year" <?php echo $grouping === 'Year' ? 'selected' : ''; ?>>Year</option>
                    </select>
                </label>

                <button type="submit">Apply Filters</button>
            </form>

            <div class="summary-cards">
                <div class="card">
                    <h3>Total Sales</h3>
                    <p>RM<?php echo number_format($summary['TotalSales'], 2); ?></p>
                </div>
                <div class="card">
                    <h3>Total Orders</h3>
                    <p><?php echo $summary['TotalOrders']; ?></p>
                </div>
                <div class="card">
                    <h3>Average Sales</h3>
                    <p>RM<?php echo number_format($summary['AverageSales'], 2); ?></p>
                </div>
            </div>

            <canvas id="salesChart" width="400" height="200"></canvas>
        </div>

        <div class="container">
            <h1>Total Drugs Purchased from Each Vendor</h1>
            <canvas id="vendorChart" width="400" height="200"></canvas>
        </div>
    </div>

    <script>
        const salesCtx = document.getElementById('salesChart').getContext('2d');
        new Chart(salesCtx, {
            type: 'line',
            data: {
                labels: <?php echo json_encode($dates); ?>,
                datasets: [
                    {
                        label: 'Total Sales',
                        data: <?php echo json_encode($sales); ?>,
                        borderColor: '#007bff',
                        backgroundColor: 'rgba(0, 123, 255, 0.2)',
                        borderWidth: 2,
                        fill: true,
                        tension: 0.1,
                    },
                    {
                        label: 'Total Orders',
                        data: <?php echo json_encode($orderCounts); ?>,
                        borderColor: '#28a745',
                        backgroundColor: 'rgba(40, 167, 69, 0.2)',
                        borderWidth: 2,
                        fill: true,
                        tension: 0.1,
                    }
                ]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'top',
                    },
                    tooltip: {
                        mode: 'index',
                        intersect: false,
                    }
                },
                scales: {
                    x: {
                        title: {
                            display: true,
                            text: 'Date',
                        }
                    },
                    y: {
                        title: {
                            display: true,
                            text: 'Values',
                        }
                    }
                }
            }
        });

        const vendorCtx = document.getElementById('vendorChart').getContext('2d');
        new Chart(vendorCtx, {
            type: 'bar',
            data: {
                labels: <?php echo json_encode($vendorNames); ?>,
                datasets: [
                    {
                        label: 'Total Drugs Supplied',
                        data: <?php echo json_encode($totalDrugs); ?>,
                        backgroundColor: 'rgba(54, 162, 235, 0.2)',
                        borderColor: 'rgba(54, 162, 235, 1)',
                        borderWidth: 1,
                    }
                ]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'top',
                    },
                    tooltip: {
                        mode: 'index',
                        intersect: false,
                    }
                },
                scales: {
                    x: {
                        title: {
                            display: true,
                            text: 'Vendors',
                        }
                    },
                    y: {
                        title: {
                            display: true,
                            text: 'Total Drugs Supplied',
                        }
                    }
                }
            }
        });
    </script>
</body>
</html>

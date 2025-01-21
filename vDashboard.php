<?php
require_once "./pDatabase.php";
session_start();

// Check if the user is logged in
if (!isset($_SESSION['username'])) {
    header("Location: ./vLogin.php");
    exit();
}

$username = $_SESSION['username'];
$sql = "SELECT VendorID, Fullname FROM vendor WHERE Username=? OR Email=?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ss", $username, $username);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $vendor = $result->fetch_assoc();
    $vendorID = $vendor['VendorID'];
    $vendorName = htmlspecialchars($vendor['Fullname']);
} else {
    $vendorID = null;
    $vendorName = 'Unknown Vendor';
}

// Fetch counts for dashboard
$pharmacy_accounts = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM vendor"))['total'] ?? 0;
$drug_supplies = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM drug_details"))['total'] ?? 0;

$supply_low = 0;
if ($vendorID) {
    $sql = "SELECT COUNT(*) as total FROM drug_supply WHERE Quantity <= 10 AND VendorID=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $vendorID);
    $stmt->execute();
    $result = $stmt->get_result();
    $supply_low = $result->fetch_assoc()['total'] ?? 0;
}

$supply_orders = 0;
if ($vendorID) {
    $sql = "SELECT COUNT(*) as total FROM drug_supply WHERE VendorID=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $vendorID);
    $stmt->execute();
    $result = $stmt->get_result();
    $supply_orders = $result->fetch_assoc()['total'] ?? 0;
}

// Fetching total drug purchases for vendor
$query = "SELECT GenericNames, SUM(Quantities) AS total_purchases 
          FROM receipt 
          WHERE Vendor = ? 
          GROUP BY GenericNames";

$stmt = $conn->prepare($query);
$stmt->bind_param("s", $vendorName);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="stylesheet" type="text/css" href="./style_dashboard.css">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Supply Manager</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&family=Poppins:wght@500;700&display=swap" rel="stylesheet">
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <script>
        function confirmLogout(event) {
            event.preventDefault();
            const userConfirmed = confirm("Are you sure you want to log out?");
            if (userConfirmed) {
                window.location.href = "./pLogout.php";
            }
        }

        function confirmGeneratePDF(event) {
            event.preventDefault();
            const userConfirmed = confirm("Do you want to generate the PDF?");
            if (userConfirmed) {
                window.location.href = "vgenerate_pdf.php";
            }
        }
    </script>
    </head>
    <body>
    <div class="sidebar">
        <div class="logo">
            <div class="logo-icon">
                <i class="fas fa-pills"></i>
            </div>
            <h2>Supply Manager</h2>
        </div>
        <a href="#">Dashboard</a>
        <a href="./vSupply.php">Drug Supply</a>
        <a href="./vOrder.php">Order Details</a>
        <a href="#" onclick="confirmLogout(event)">Logout</a>
    </div>

    <div class="content-wrapper">
        <div class="header-title">Supply Manager</div>
        
        <div class="user-info">
            <div>
                <h2>Welcome, <?php echo $vendorName; ?>!</h2>
            </div>
            <div>
                <a href="vprofile.php" title="View Profile">
                    <i class="fas fa-user-circle" style="font-size: 3em; color: #ff9a3c; cursor: pointer;"></i>
                </a>
            </div>
        </div>
        
        <div class="content-boxes">
            <div class="box">
                <i class="fas fa-pills"></i>
                <p><?php echo $drug_supplies; ?></p>
                Total Drug Brands in the System 
            </div>
            <div class="box">
                <i class="fas fa-truck"></i>
                <p><?php echo $supply_orders; ?></p>
                Total Supplied Orders from this Pharmacy
            </div>
            <div class="box">
                <i class="fas fa-boxes"></i>
                <p><?php echo $supply_low; ?></p>
                Supplies Need to be Restocked (Less than 10 Stocks)
            </div>
        </div>

        <div class="content-box">
            <h3>Total Drug Purchases</h3>
            <div class="box">
                <div id="piechart" style="min-height: 400px; width:800px;"></div>
            </div>
        </div>

        <a href="#" class="export-button" onclick="confirmGeneratePDF(event)">Export to PDF</a>
    </div>

    <script>
        google.charts.load('current', {'packages':['corechart']});
        google.charts.setOnLoadCallback(drawChart);

        function drawChart() {
            var data = google.visualization.arrayToDataTable([
                ['GenericName', 'Total Purchases'],  
                <?php  
                while ($row = mysqli_fetch_array($result)) {  
                    echo "['" . $row["GenericNames"] . "', " . $row["total_purchases"] . "],"; 
                }  
                ?>
            ]);

            var options = {  
                title: 'Total Drug Sold',                      
                is3D: true,  
                pieHole: 0.4  
            };

            var chart = new google.visualization.PieChart(document.getElementById('piechart'));  
            chart.draw(data, options);  

            var chartImageData = chart.getImageURI();
            var xhr = new XMLHttpRequest();
            xhr.open('POST', 'vsave_chart_image.php', true);
            xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
            xhr.send('chartImageData=' + encodeURIComponent(chartImageData));
        }
    </script>
</body>
</html>

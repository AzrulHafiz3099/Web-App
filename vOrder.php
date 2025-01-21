<?php
session_start();
require_once "./pDatabase.php";

// Check if the user is logged in
if (!isset($_SESSION['username'])) {
    // If the user is not logged in, redirect to the login page
    header("Location: ./vLogin.php");
    exit();
}

$username = $_SESSION['username'];
$sql = "SELECT Fullname FROM vendor WHERE Username=? OR Email=?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ss", $username, $username);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $vendor = $result->fetch_assoc();
    $vendorName = htmlspecialchars($vendor['Fullname']);
} else {
    $vendorName = 'Unknown Vendor';
}

// Query to fetch data from the table
$sql = "SELECT u.Fullname, r.ReceiptID, r.Quantities, r.TotalAmount, r.PaymentDate, r.PaymentMethod, r.GenericNames, r.Vendor AS VendorName
        FROM user_account u 
        JOIN receipt r ON u.UserID = r.UserID
        JOIN `order` o ON r.OrderID = o.OrderID
        JOIN cart c ON o.CartID = c.CartID
        JOIN cart_item ci ON c.CartID = ci.CartID
        JOIN drug_details d ON ci.DrugID = d.DrugID
        JOIN drug_supply s ON d.SupplyID = s.SupplyID
        JOIN vendor v ON s.VendorID = v.VendorID 
        WHERE r.Vendor LIKE ?
        GROUP BY r.ReceiptID;";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $vendorName);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <link rel="stylesheet" type="text/css" href="./style_supply.css">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Supply Manager</title>
    <script>
        function confirmLogout(event) {
            event.preventDefault(); // Prevent the default link behavior
            const userConfirmed = confirm("Are you sure you want to log out?");
            if (userConfirmed) {
                // Redirect to the logout page
                window.location.href = ".//pLogout.php";
            }
        }
    </script>
</head>

<body>
    <div class="sidebar">
        <!-- Logo -->
        <div class="logo">
            <div class="logo-icon">
                <i class="fas fa-pills"></i>
            </div>
            <h2>Supply Manager</h2>
        </div>

        <!-- Sidebar Navigation -->
        <a href="./vDashboard.php">Dashboard</a>
        <a href="./vSupply.php">Drug Supply</a>
        <a href="#">Order Details</a>
        <a href="#" onclick="confirmLogout(event)">Logout</a>
    </div>

    <div class="container">
        <h1>Order Details</h1>
        <table>
            <thead>
                <tr>
                    <th>Number</th>
                    <th>Cust Name</th>
                    <th>Receipt ID</th>
                    <th>Drug Generic Name</th>
                    <th>Quantities</th>
                    <th>Total Price (RM)</th>
                    <th>Payment Date</th>
                    <th>Payment Method</th>
                    <th>Vendor</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $i = 1; // Initialize row number
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>" . $i . ".</td>";
                    echo "<td>" . htmlspecialchars($row['Fullname']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['ReceiptID']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['GenericNames']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['Quantities']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['TotalAmount']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['PaymentDate']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['PaymentMethod']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['VendorName']) . "</td>";
                    echo "</tr>";
                    $i++; // Increment row number
                }
                ?>
            </tbody>
        </table>
    </div>
</body>

</html>

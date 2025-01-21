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

// Retrieve VendorID and Fullname of the logged-in vendor
$sql = "SELECT VendorID, Fullname FROM vendor WHERE Username=? OR Email=?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ss", $username, $username);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $vendor = $result->fetch_assoc();
    $vendorID = $vendor['VendorID']; // Store VendorID
    $vendorName = htmlspecialchars($vendor['Fullname']);
} else {
    die("Vendor not found.");
}

// Query to fetch data specific to the logged-in vendor
$sql = "SELECT s.SupplyID, v.Fullname, s.Quantity, s.Manufacture_Date, s.ExpiryDate, d.BrandName, d.Price 
        FROM drug_details d 
        JOIN drug_supply s ON d.DrugHeaderID = s.DrugHeaderID
        JOIN vendor v ON s.VendorID = v.VendorID 
        WHERE s.VendorID = ? 
        GROUP BY s.SupplyID";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $vendorID);
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
                window.location.href = "./pLogout.php";
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
        <a href="#">Drug Supply</a>
        <a href="./vOrder.php">Order Details</a>
        <a href="#" onclick="confirmLogout(event)">Logout</a>
    </div>

    <div class="container">
        <h1>Supply Details</h1>
        <a href="./vAddUpdate.php" class="btn btn-primary">Add Drug</a>
        <table>
            <thead>
                <tr>
                  <th> Number </th>
                  <th> Vendor Name </th>
                  <th> Brand Name </th>
                  <th> Quantity </th>
                  <th> Price (RM) </th>
                  <th> Manufacture Date </th>
                  <th> Expiry Date </th>
                  <th colspan="2"> Action </th>
                </tr>
            </thead>
            <tbody>
                <?php 
                $i = 1;
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()):
                        // Format dates
                        $expiryDate = DateTime::createFromFormat('Y-m-d', $row['ExpiryDate'])->format('d/m/Y');
                        $manufactureDate = DateTime::createFromFormat('Y-m-d', $row['Manufacture_Date'])->format('d/m/Y');
                ?>
                <tr>
                    <td><?php echo $i . ". "; ?></td>
                    <td><?php echo $row['Fullname']; ?></td>
                    <td><?php echo $row['BrandName']; ?></td>
                    <td><?php echo $row['Quantity']; ?></td>
                    <td><?php echo $row['Price']; ?></td>
                    <td><?php echo $manufactureDate; ?></td>
                    <td><?php echo $expiryDate; ?></td>
                    <td>
                        <a href="./vAddUpdate.php?update=<?php echo $row['SupplyID']; ?>" title="Update Supply">
                            <button class="btn btn-primary action-btn">Update</button>
                        </a>
                        <br />
                        <a href="./pTableProcess.php?delete=<?php echo $row['SupplyID']; ?>" title="Delete Supply">
                            <button class="btn btn-danger action-btn">Delete</button>
                        </a>
                    </td>
                </tr>
                <?php
                    $i++;
                    endwhile;
                } else {
                    echo "<tr><td colspan='10'>No records found</td></tr>";
                }
                $conn->close();
                ?>
            </tbody>
        </table>
    </div>
</body>

</html>

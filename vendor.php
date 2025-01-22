<?php
// Include database connection
@include 'db_connection.php';
session_start();

// Enable MySQL error reporting for debugging
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

// Handle form submissions for Edit and Delete actions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'];

    if ($action === 'edit') {
        // Edit existing vendor
        $vendorID = $_POST['vendorID']; // Vendor ID is required for editing
        $fullname = $_POST['fullname'];
        $address = $_POST['address'];
        $contactNumber = $_POST['contactNumber'];
        $email = $_POST['email'];

        $query = "UPDATE vendor SET Fullname=?, Address=?, ContactNumber=?, Email=? WHERE VendorID=?";
        $stmt = $conn->prepare($query);

        if ($stmt === false) {
            die("Error in query preparation: " . $conn->error);
        }

        $stmt->bind_param('sssss', $fullname, $address, $contactNumber, $email, $vendorID);

        if ($stmt->execute()) {
            header("Location: vendor.php");
            exit;
        } else {
            die("Execution failed: " . $stmt->error);
        }
    } elseif ($action === 'delete') {
        // Delete a vendor
        $vendorID = $_POST['vendorID'];

        $query = "DELETE FROM vendor WHERE VendorID=?";
        $stmt = $conn->prepare($query);

        if ($stmt === false) {
            die("Error in query preparation: " . $conn->error);
        }

        $stmt->bind_param('s', $vendorID);

        if ($stmt->execute()) {
            header("Location: vendor.php");
            exit;
        } else {
            die("Execution failed: " . $stmt->error);
        }
    }
}

// Fetch vendor data for listing
$query = "SELECT * FROM vendor";
$result = $conn->query($query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vendor Management</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <style>
        /* General Styling */
        body {
            font-family: 'Poppins', sans-serif;
            margin: 0;
            padding: 0;
            background: linear-gradient(135deg, #ff9a3c, #f83600);
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
            font-family: 'Roboto', sans-serif;
            text-shadow: 1px 1px 5px rgba(0, 0, 0, 0.2);
        }

        /* Main Content Area */
        .content-wrapper {
            margin-left: 250px;
            padding: 20px;
            min-height: 100vh;
            background: white;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        /* Buttons */
        .btn {
            padding: 10px 15px;
            margin: 5px;
            border-radius: 5px;
            text-decoration: none;
            color: white;
            font-size: 0.9em;
            transition: background-color 0.3s ease;
        }

        .btn-primary {
            background-color: #28a745;
        }

        .btn-primary:hover {
            background-color: #218838;
        }

        .btn-warning {
            background-color: #ffc107;
        }

        .btn-warning:hover {
            background-color: #e0a800;
        }

        .btn-danger {
            background-color: #dc3545;
        }

        .btn-danger:hover {
            background-color: #c82333;
        }

        /* Table */
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
        <h1>Vendor Management</h1>
        <table>
            <thead>
                <tr>
                    <th>Vendor ID</th>
                    <th>Fullname</th>
                    <th>Address</th>
                    <th>Contact</th>
                    <th>Email</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
    <?php while ($row = $result->fetch_assoc()) { ?>
        <tr>
            <td><?php echo htmlspecialchars($row['VendorID']); ?></td>
            <td><?php echo htmlspecialchars($row['Fullname']); ?></td>
            <td><?php echo htmlspecialchars($row['Address']); ?></td>
            <td><?php echo htmlspecialchars($row['ContactNumber']); ?></td>
            <td><?php echo htmlspecialchars($row['Email']); ?></td>
            <td>
                <form action="vendor.php" method="POST" style="display:inline;">
                    <input type="hidden" name="vendorID" value="<?php echo $row['VendorID']; ?>">
                    <input type="hidden" name="fullname" value="<?php echo $row['Fullname']; ?>">
                    <input type="hidden" name="address" value="<?php echo $row['Address']; ?>">
                    <input type="hidden" name="contactNumber" value="<?php echo $row['ContactNumber']; ?>">
                    <input type="hidden" name="email" value="<?php echo $row['Email']; ?>">
                    <input type="hidden" name="action" value="edit">
                    <button type="submit" class="btn btn-warning">Edit</button>
                </form>

                <form action="vendor.php" method="POST" style="display:inline;" onsubmit="return confirm('Are you sure you want to delete this vendor?');">
                    <input type="hidden" name="vendorID" value="<?php echo $row['VendorID']; ?>">
                    <input type="hidden" name="action" value="delete">
                    <button type="submit" class="btn btn-danger">Delete</button>
                </form>
            </td>
        </tr>
    <?php } ?>
</tbody>

        </table>
    </div>
</body>
</html>

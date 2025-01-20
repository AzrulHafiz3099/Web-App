<?php
// Include database connection
@include 'db_connection.php';
session_start();

// Handle form submissions for Add, Edit, and Delete actions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'];

    if ($action === 'add') {
        // Add a new vendor
        $vendorID = uniqid('V_'); // Unique ID generation
        $fullname = $_POST['fullname'];
        $address = $_POST['address'];
        $contactNumber = $_POST['contactNumber'];
        $email = $_POST['email'];

        $query = "INSERT INTO vendor (VendorID, Fullname, Address, ContactNumber, Email) 
                  VALUES (?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($query);
        $stmt->bind_param('sssss', $vendorID, $fullname, $address, $contactNumber, $email);
        $stmt->execute();
        header("Location: vendor.php");
        exit;
    } elseif ($action === 'edit') {
        // Edit existing vendor
        $vendorID = $_POST['vendorID'];
        $fullname = $_POST['fullname'];
        $address = $_POST['address'];
        $contactNumber = $_POST['contactNumber'];
        $email = $_POST['email'];

        $query = "UPDATE vendor SET Fullname=?, Address=?, ContactNumber=?, Email=? 
                  WHERE VendorID=?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param('sssss', $fullname, $address, $contactNumber, $email, $vendorID);
        $stmt->execute();
        header("Location: vendor.php");
        exit;
    } elseif ($action === 'delete') {
        // Delete a vendor
        $vendorID = $_POST['vendorID'];
        $query = "DELETE FROM vendor WHERE VendorID=?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param('s', $vendorID);
        $stmt->execute();
        header("Location: vendor.php");
        exit;
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
    <style>
        /* General Body Styling */
        body {
            font-family: Arial, sans-serif;
            background: linear-gradient(135deg, #ff9a3c, #f83600);
            margin: 0;
            padding: 0;
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

        /* Container Styling */
        .container {
            width: 80%;
            margin: 20px auto;
            background-color: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            margin-left: 270px;
        }

        /* Heading Styling */
        h1 {
            font-size: 2.5em;
            text-align: center;
            color: #f83600;
            font-weight: bold;
        }

        /* Button Styling */
        button {
            padding: 10px 15px;
            font-size: 0.9em;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease;
            margin: 5px;
        }

        button.add-btn {
            background-color: #28a745;
            color: white;
        }

        button.add-btn:hover {
            background-color: #218838;
        }

        button.edit-btn {
            background-color: #ffc107;
            color: white;
        }

        button.edit-btn:hover {
            background-color: #e0a800;
        }

        button.delete-btn {
            background-color: #dc3545;
            color: white;
        }

        button.delete-btn:hover {
            background-color: #c82333;
        }

        /* Table Styling */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        table th,
        table td {
            padding: 12px;
            text-align: center;
            border-bottom: 1px solid #ddd;
        }

        table th {
            background-color: #f1f1f1;
            font-weight: bold;
        }

        /* Modal Styling */
        .modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            justify-content: center;
            align-items: center;
            z-index: 1000;
        }

        .modal-content {
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.2);
            max-width: 400px;
            width: 100%;
            position: relative;
            text-align: left;
        }

        .modal-content .close {
            position: absolute;
            top: 10px;
            right: 10px;
            font-size: 1.5em;
            color: #333;
            cursor: pointer;
        }

        label {
            display: block;
            margin: 10px 0 5px;
            font-weight: bold;
        }

        /* Responsive styles */
        @media (max-width: 768px) {
            .container {
                width: 100%;
                margin-left: 0;
            }

            .sidebar {
                width: 200px;
            }

            table th,
            table td {
                font-size: 14px;
            }
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

    <div class="container">
        <h1>Vendor Management</h1>
        <button class="add-btn" onclick="openModal('add')">Add Vendor</button>
        <table>
            <thead>
                <tr>
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
                        <td><?php echo htmlspecialchars($row['Fullname']); ?></td>
                        <td><?php echo htmlspecialchars($row['Address']); ?></td>
                        <td><?php echo htmlspecialchars($row['ContactNumber']); ?></td>
                        <td><?php echo htmlspecialchars($row['Email']); ?></td>
                        <td>
                            <button class="edit-btn" onclick="editVendor(<?php echo htmlspecialchars(json_encode($row)); ?>)">Edit</button>
                            <form method="POST" style="display:inline;">
                                <input type="hidden" name="action" value="delete">
                                <input type="hidden" name="vendorID" value="<?php echo $row['VendorID']; ?>">
                                <button type="submit" class="delete-btn" onclick="return confirm('Are you sure you want to delete this vendor?');">Delete</button>
                            </form>
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>

    <!-- Modal for Add/Edit -->
    <div id="vendorModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeModal()">&times;</span>
            <h2 id="modalTitle">Add Vendor</h2>
            <form id="vendorForm" method="POST" action="vendor.php">
                <input type="hidden" name="action" id="formAction">
                <input type="hidden" name="vendorID" id="vendorID">

                <label for="fullname">Fullname:</label>
                <input type="text" name="fullname" id="fullname" required>

                <label for="address">Address:</label>
                <input type="text" name="address" id="address" required>

                <label for="contactNumber">Contact:</label>
                <input type="text" name="contactNumber" id="contactNumber" required>

                <label for="email">Email:</label>
                <input type="email" name="email" id="email" required>

                <div style="margin-top: 20px; text-align: center;">
                    <button type="submit" class="add-btn">Add Vendor</button>
                    <button type="button" class="delete-btn" onclick="closeModal()">Cancel</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function openModal(action) {
            const modal = document.getElementById('vendorModal');
            const formAction = document.getElementById('formAction');
            const modalTitle = document.getElementById('modalTitle');
            const form = document.getElementById('vendorForm');

            modal.style.display = 'flex'; // Show the modal
            formAction.value = action;

            if (action === 'add') {
                modalTitle.textContent = 'Add Vendor';
                form.reset(); // Reset the form for adding a new vendor
            }
        }

        function editVendor(vendor) {
            openModal('edit');
            document.getElementById('modalTitle').textContent = 'Edit Vendor';

            document.getElementById('fullname').value = vendor.Fullname;
            document.getElementById('address').value = vendor.Address;
            document.getElementById('contactNumber').value = vendor.ContactNumber;
            document.getElementById('email').value = vendor.Email;
            document.getElementById('vendorID').value = vendor.VendorID;
        }

        function closeModal() {
            const modal = document.getElementById('vendorModal');
            modal.style.display = 'none'; // Hide the modal
        }
    </script>
</body>
</html>
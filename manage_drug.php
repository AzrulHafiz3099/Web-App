<?php
session_start();
@include 'db_connection.php';

// Check if the user is logged in
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header('Location: login.php');
    exit;
}

// Handle Add Drug
if (isset($_POST['add_drug'])) {
    $drugID = uniqid('drug_'); // Generate a unique ID for DrugID
    $brandName = $_POST['brand_name'];
    $genericName = $_POST['generic_name'];
    $activeIngredient = $_POST['active_ingredient'];
    $dosage = $_POST['dosage'];
    $dosageForm = $_POST['dosage_form'];
    $manufacturer = $_POST['manufacturer'];
    $manufactureDate = $_POST['manufacture_date'];
    $sideEffects = $_POST['side_effects'];
    $price = $_POST['price'];
    $drugImage = 'default_image.jpg'; // Placeholder for image, update as per your image upload logic

    $sql = "INSERT INTO drug_details (DrugID, BrandName, GenericName, Active_Ingredient, Dosage, Dosage_Form, Manufacturer, Manufacture_Date, SideEffects, Price, DrugImage) 
            VALUES ('$drugID', '$brandName', '$genericName', '$activeIngredient', '$dosage', '$dosageForm', '$manufacturer', '$manufactureDate', '$sideEffects', '$price', '$drugImage')";
    if (mysqli_query($conn, $sql)) {
        echo '<script>alert("Drug added successfully!");</script>';
    } else {
        echo '<script>alert("Error adding drug: ' . mysqli_error($conn) . '");</script>';
    }
}

// Handle Delete Drug
if (isset($_GET['delete'])) {
    $drugID = $_GET['delete'];
    $sql = "DELETE FROM drug_details WHERE DrugID = '$drugID'";
    if (mysqli_query($conn, $sql)) {
        echo '<script>alert("Drug deleted successfully!");</script>';
    } else {
        echo '<script>alert("Error deleting drug: ' . mysqli_error($conn) . '");</script>';
    }
}

// Handle Edit Drug
if (isset($_POST['edit_drug'])) {
    $drugID = $_POST['drug_id'];
    $brandName = $_POST['brand_name'];
    $genericName = $_POST['generic_name'];
    $activeIngredient = $_POST['active_ingredient'];
    $dosage = $_POST['dosage'];
    $dosageForm = $_POST['dosage_form'];
    $manufacturer = $_POST['manufacturer'];
    $manufactureDate = $_POST['manufacture_date'];
    $sideEffects = $_POST['side_effects'];
    $price = $_POST['price'];

    $sql = "UPDATE drug_details 
            SET BrandName='$brandName', GenericName='$genericName', Active_Ingredient='$activeIngredient', Dosage='$dosage', 
                Dosage_Form='$dosageForm', Manufacturer='$manufacturer', Manufacture_Date='$manufactureDate', 
                SideEffects='$sideEffects', Price='$price' 
            WHERE DrugID='$drugID'";
    if (mysqli_query($conn, $sql)) {
        echo '<script>alert("Drug updated successfully!");</script>';
    } else {
        echo '<script>alert("Error updating drug: ' . mysqli_error($conn) . '");</script>';
    }
}

// Fetch Drugs
$drugs = mysqli_query($conn, "SELECT * FROM drug_details");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Drugs</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&family=Poppins:wght@500;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            margin: 0;
            padding: 0;
            background: linear-gradient(135deg, #ff9a3c, #f83600);
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
            font-family: 'Roboto', sans-serif;
            text-shadow: 1px 1px 5px rgba(0, 0, 0, 0.2);
        }

        .content-wrapper {
            margin-left: 250px;
            padding: 20px;
            min-height: 100vh;
            background: white;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .add-form {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            margin-bottom: 20px;
        }

        .add-form input, .add-form button {
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
            width: calc(50% - 10px);
        }

        .add-form button {
            width: 100%;
            background: #f83600;
            color: white;
            cursor: pointer;
            transition: background 0.3s ease;
        }

        .add-form button:hover {
            background: #ff9a3c;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        table, th, td {
            border: 1px solid #ddd;
        }

        th, td {
            padding: 10px;
            text-align: center;
        }

        th {
            background: #f83600;
            color: white;
        }

        .edit-button, .delete-button {
            background: #007bff;
            color: white;
            padding: 5px 10px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            text-decoration: none;
            display: inline-block;
            transition: background 0.3s ease;
        }

        .delete-button {
            background: red;
        }

        .edit-button:hover {
            background: #0056b3;
        }

        .delete-button:hover {
            background: darkred;
        }

        @media (max-width: 768px) {
            .add-form input, .add-form button {
                width: 100%;
            }

            table, th, td {
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

    <div class="content-wrapper">
        <h1>Manage Drugs</h1>

        <form class="add-form" method="POST">
            <input type="hidden" name="drug_id" value="<?php echo isset($_GET['edit']) ? $_GET['edit'] : ''; ?>">
            <input type="text" name="brand_name" placeholder="Brand Name" value="<?php echo $editDrug['BrandName'] ?? ''; ?>" required>
            <input type="text" name="generic_name" placeholder="Generic Name" value="<?php echo $editDrug['GenericName'] ?? ''; ?>" required>
            <input type="text" name="active_ingredient" placeholder="Active Ingredient" value="<?php echo $editDrug['Active_Ingredient'] ?? ''; ?>">
            <input type="text" name="dosage" placeholder="Dosage" value="<?php echo $editDrug['Dosage'] ?? ''; ?>">
            <input type="text" name="dosage_form" placeholder="Dosage Form" value="<?php echo $editDrug['Dosage_Form'] ?? ''; ?>">
            <input type="text" name="manufacturer" placeholder="Manufacturer" value="<?php echo $editDrug['Manufacturer'] ?? ''; ?>">
            <input type="date" name="manufacture_date" placeholder="Manufacture Date" value="<?php echo $editDrug['Manufacture_Date'] ?? ''; ?>">
            <input type="text" name="side_effects" placeholder="Side Effects" value="<?php echo $editDrug['SideEffects'] ?? ''; ?>">
            <input type="number" step="0.01" name="price" placeholder="Price" value="<?php echo $editDrug['Price'] ?? ''; ?>" required>
            <button type="submit" name="<?php echo isset($_GET['edit']) ? 'edit_drug' : 'add_drug'; ?>">
                <?php echo isset($_GET['edit']) ? 'Update Drug' : 'Add Drug'; ?>
            </button>
        </form>

        <table>
            <thead>
                <tr>
                    <th>Drug ID</th>
                    <th>Brand Name</th>
                    <th>Generic Name</th>
                    <th>Active Ingredient</th>
                    <th>Dosage</th>
                    <th>Dosage Form</th>
                    <th>Manufacturer</th>
                    <th>Manufacture Date</th>
                    <th>Price</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = mysqli_fetch_assoc($drugs)): ?>
                    <tr>
                        <td><?php echo $row['DrugID']; ?></td>
                        <td><?php echo $row['BrandName']; ?></td>
                        <td><?php echo $row['GenericName']; ?></td>
                        <td><?php echo $row['Active_Ingredient']; ?></td>
                        <td><?php echo $row['Dosage']; ?></td>
                        <td><?php echo $row['Dosage_Form']; ?></td>
                        <td><?php echo $row['Manufacturer']; ?></td>
                        <td><?php echo $row['Manufacture_Date']; ?></td>
                        <td><?php echo $row['Price']; ?></td>
                        <td>
                            <a href="manage_drug.php?edit=<?php echo $row['DrugID']; ?>" class="edit-button">Edit</a>
                            <a href="manage_drug.php?delete=<?php echo $row['DrugID']; ?>" class="delete-button">Delete</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</body>
</html>
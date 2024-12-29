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
    <style>
        body {
            font-family: Arial, sans-serif;
            background: linear-gradient(135deg, #ff9a3c, #f83600);
            margin: 0;
            padding: 0;
        }

        .container {
            max-width: 1200px;
            margin: 20px auto;
            padding: 20px;
            background: white;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        h1 {
            text-align: center;
            margin-bottom: 20px;
            color: #f83600;
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
        }

        .add-form button:hover {
            background: #ff9a3c;
        }

        .delete-button {
            background: red;
            color: white;
            padding: 5px 10px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        .delete-button:hover {
            background: darkred;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Manage Drugs</h1>

        <!-- Add Drug Form -->
        <form class="add-form" method="POST">
            <input type="text" name="brand_name" placeholder="Brand Name" required>
            <input type="text" name="generic_name" placeholder="Generic Name" required>
            <input type="text" name="active_ingredient" placeholder="Active Ingredient">
            <input type="text" name="dosage" placeholder="Dosage">
            <input type="text" name="dosage_form" placeholder="Dosage Form">
            <input type="text" name="manufacturer" placeholder="Manufacturer">
            <input type="date" name="manufacture_date" placeholder="Manufacture Date">
            <input type="text" name="side_effects" placeholder="Side Effects">
            <input type="number" step="0.01" name="price" placeholder="Price" required>
            <button type="submit" name="add_drug">Add Drug</button>
        </form>

        <!-- Drugs Table -->
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
                            <a href="manage_drug.php?delete=<?php echo $row['DrugID']; ?>" class="delete-button">Delete</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</body>
</html>


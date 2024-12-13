<?php
@include 'db.php';
session_start();

if (isset($_GET['id'])) {
    $drug_id = $_GET['id'];

    // Fetch drug details for the given drug ID
    $query = "SELECT * FROM drug_inventory WHERE id = $drug_id";
    $result = mysqli_query($conn, $query);
    $drug = mysqli_fetch_assoc($result);

    if (!$drug) {
        echo "Drug not found!";
        exit;
    }
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $drug_name = $_POST['drug_name'];
    $stock = $_POST['stock'];
    $price = $_POST['price'];

    // Update drug details in the database
    $query = "UPDATE drug_inventory SET drug_name = '$drug_name', stock = '$stock', price = '$price' WHERE id = $drug_id";
    if (mysqli_query($conn, $query)) {
        header('Location: drug_inventory.php');
        exit();
    } else {
        echo 'Error: ' . mysqli_error($conn);
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Drug</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f9;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 600px;
            margin: 50px auto;
            padding: 20px;
            background-color: #fff;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
        }
        h2 {
            text-align: center;
            color: #333;
        }
        label {
            font-size: 16px;
            margin-bottom: 8px;
        }
        input, textarea {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border-radius: 4px;
            border: 1px solid #ddd;
        }
        button {
            width: 100%;
            padding: 12px;
            background-color: #ffc107;
            color: white;
            font-size: 16px;
            border: none;
            border-radius: 4px;
        }
        button:hover {
            background-color: #e0a800;
        }
    </style>
</head>

<body>

<div class="container">
    <h2>Edit Drug</h2>
    <form method="POST">
        <label for="drug_name">Drug Name</label>
        <input type="text" name="drug_name" value="<?php echo htmlspecialchars($drug['drug_name']); ?>" required>

        <label for="stock">Stock</label>
        <input type="number" name="stock" value="<?php echo htmlspecialchars($drug['stock']); ?>" required>

        <label for="price">Price</label>
        <input type="number" step="0.01" name="price" value="<?php echo htmlspecialchars($drug['price']); ?>" required>

        <button type="submit">Update Drug</button>
    </form>
</div>

</body>
</html>


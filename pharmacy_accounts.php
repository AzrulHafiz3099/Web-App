<?php
// Assuming you have a database connection setup in a separate file
// Include the database connection (e.g., db.php)
@include 'db.php';
session_start();

// Fetch pharmacy data from the database
$query = "SELECT * FROM pharmacy_accounts";
$result = mysqli_query($conn, $query);

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pharmacy Accounts</title>
    <link href="vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="css/sb-admin-2.min.css" rel="stylesheet">
</head>

<body>
    <div class="container mt-5">
        <h1>Pharmacy Accounts</h1>
        <a href="add_pharmacy.php" class="btn btn-primary mb-3">Add Pharmacy</a>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Pharmacy Name</th>
                    <th>Location</th>
                    <th>Contact</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = mysqli_fetch_assoc($result)) { ?>
                    <tr>
                        <td><?php echo htmlspecialchars($row['name']); ?></td>
                        <td><?php echo htmlspecialchars($row['location']); ?></td>
                        <td><?php echo htmlspecialchars($row['contact']); ?></td>
                        <td>
                            <a href="edit_pharmacy.php?id=<?php echo $row['id']; ?>" class="btn btn-warning btn-sm">Edit</a>
                            <a href="delete_pharmacy.php?id=<?php echo $row['id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this pharmacy?');">Delete</a>
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
</body>

</html>
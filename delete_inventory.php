<?php
@include 'db.php';
session_start();

// Check if the drug ID is provided
if (!isset($_GET['id'])) {
    echo '<script>alert("No drug ID provided."); window.location.href = "drug_inventory.php";</script>';
    exit;
}

$drug_id = $_GET['id'];

// Delete the drug stock entry from the database
$query = "DELETE FROM drug_stock WHERE DrugID = '$drug_id'"; // Change 'id' to 'DrugID'
if (mysqli_query($conn, $query)) {
    echo '<script>alert("Drug deleted successfully!"); window.location.href = "drug_inventory.php";</script>';
} else {
    echo '<script>alert("Error: ' . mysqli_error($conn) . '"); window.location.href = "drug_inventory.php";</script>';
}
?>


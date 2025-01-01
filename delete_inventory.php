<?php
@include 'db_connection.php';
session_start();

// Check if the Supply ID is provided
if (!isset($_GET['id'])) {
    echo '<script>alert("No Supply ID provided."); window.location.href = "drug_inventory.php";</script>';
    exit;
}

$supply_id = $_GET['id'];

// Use a prepared statement to prevent SQL injection
$query = "DELETE FROM drug_supply WHERE SupplyID = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("s", $supply_id);

if ($stmt->execute()) {
    echo '<script>alert("Supply record deleted successfully!"); window.location.href = "drug_inventory.php";</script>';
} else {
    echo '<script>alert("Error: ' . htmlspecialchars($stmt->error) . '"); window.location.href = "drug_inventory.php";</script>';
}

$stmt->close();
$conn->close();
?>


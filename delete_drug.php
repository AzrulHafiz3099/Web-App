<?php
@include 'db.php';
session_start();

if (isset($_GET['id'])) {
    $drug_id = $_GET['id'];

    // Delete drug from the database
    $query = "DELETE FROM drug_inventory WHERE id = $drug_id";
    if (mysqli_query($conn, $query)) {
        header('Location: drug_inventory.php');
        exit();
    } else {
        echo 'Error: ' . mysqli_error($conn);
    }
}
?>

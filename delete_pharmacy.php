<?php
@include 'db.php';
session_start();

$id = $_GET['id'];

$query = "DELETE FROM pharmacy_accounts WHERE id = $id";
if (mysqli_query($conn, $query)) {
    header('Location: index.php');
    exit();
} else {
    echo 'Error: ' . mysqli_error($conn);
}
?>

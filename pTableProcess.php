<?php
require_once "./pDatabase.php";
session_start();

$id = 0;

define('REQUIRE_ERROR', 'THIS FIELD IS REQUIRED');
$error = [];

$drug_id = '';
$drug_headerid = '';
$drug_vendorid = '';
$drug_quantity = '';
$drug_manudate = '';
$drug_expiry = '';

$update = false;

function storeData($data) {
    return isset($data) ? htmlspecialchars(stripslashes($data)) : false;
}

function getDrugIDs($conn) {
    $sql = "SELECT DrugID FROM drug_details";
    $result = mysqli_query($conn, $sql);
    $drug_ids = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $drug_ids[] = $row['DrugID'];
    }
    return $drug_ids;
}

function getDrugHeaderID($conn, $drug_id) {
    $sql = "SELECT DrugHeaderID FROM drug_details WHERE DrugID = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $drug_id);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($row = $result->fetch_assoc()) {
        return $row['DrugHeaderID'];
    }
    return null; // Return null if no match
}

function getVendorIDs($conn) {
    $sql = "SELECT VendorID FROM vendor";
    $result = mysqli_query($conn, $sql);
    $vendor_ids = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $vendor_ids[] = $row['VendorID'];
    }
    return $vendor_ids;
}

function getManufactureDates($conn, $drug_id = null) {
    $sql = "SELECT DISTINCT Manufacture_Date FROM drug_details";
    if ($drug_id) {
        $sql .= " WHERE DrugID = ?";
    }
    $stmt = $conn->prepare($sql);
    if ($drug_id) {
        $stmt->bind_param("s", $drug_id);
    }
    $stmt->execute();
    $result = $stmt->get_result();
    $manufacture_dates = [];
    while ($row = $result->fetch_assoc()) {
        $manufacture_dates[] = $row['Manufacture_Date'];
    }
    return $manufacture_dates;
}

$manufactureDates = getManufactureDates($conn, $drug_id);
$drug_manudate = !empty($manufactureDates) ? $manufactureDates[0] : null;

if (!$drug_manudate) {
    $error['manufacture_date'] = REQUIRE_ERROR; // Add error handling
}

function addSupply($conn, $drug_vendorid, $drug_quantity, $drug_expiry, $drug_id) {
    $sql = "
        INSERT INTO drug_supply (DrugHeaderID, VendorID, Quantity, Manufacture_Date, ExpiryDate)
        SELECT d.DrugHeaderID, ?, ?, d.Manufacture_Date, ?
        FROM drug_details d
        WHERE d.DrugID = ?
    ";
    $stmt = $conn->prepare($sql);
    if ($stmt) {
        $stmt->bind_param('ssss', $drug_vendorid, $drug_quantity, $drug_expiry, $drug_id);
        return $stmt->execute();
    }
    return false;
}

if (isset($_POST['back'])) {
    header("location: ./vSupply.php");
    exit();
}

if (isset($_GET['delete'])) {
    $id = storeData($_GET['delete']); // Ensure $id is treated as a string

    // Debug: Log the SupplyID being deleted
    error_log("Deleting SupplyID: $id");

    // Prepare the DELETE query
    $sql = "DELETE FROM drug_supply WHERE SupplyID = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $id);

    if ($stmt->execute()) {
        $_SESSION['message'] = "Supply Successfully Deleted";
        $_SESSION['message_type'] = "danger";
        header("location: ./vSupply.php");
        exit();
    } else {
        // Log the error for debugging
        error_log("Error executing DELETE query: " . $stmt->error);
        echo "Error: Could not delete record. Please check your query or database.";
    }
}

if (isset($_POST['save']) || isset($_POST['update'])) {

    $drug_id = storeData($_POST['drug_id']);
    $drug_headerid = storeData($_POST['drug_headerid']);
    $drug_vendorid = storeData($_POST['vendor_id']);
    $drug_quantity = storeData($_POST['quantity']);
    $drug_manudate = storeData($_POST['manufacture_date']);
    $drug_expiry = storeData($_POST['expiry_date']);

    if (!$drug_id) $error['drug_id'] = REQUIRE_ERROR;
    if (!$drug_quantity) $error['quantity'] = REQUIRE_ERROR;
    if (!$drug_expiry) $error['expiry_date'] = REQUIRE_ERROR;

    if (empty($error)) {
        if (isset($_POST['save'])) {
            if (isset($_POST['save'])) {
                // Debug: Check the data being passed
                error_log("VendorID: $drug_vendorid, Quantity: $drug_quantity, Expiry: $drug_expiry, DrugID: $drug_id");
                
                if (addSupply($conn, $drug_vendorid, $drug_quantity, $drug_expiry, $drug_id)) {
                    $_SESSION['message'] = "Supply Successfully Added";
                    $_SESSION['message_type'] = "success";
                    header("location: ./vSupply.php");
                    exit();
                } else {
                    $_SESSION['message'] = "Failed to Add Supply";
                    $_SESSION['message_type'] = "danger";
                }
            }
            
        if (isset($_POST['update'])) {
            $id = storeData($_POST['id']); // Ensure $id is treated as a string
            $sql = "UPDATE drug_supply SET DrugHeaderID = '$drug_headerid', VendorID = '$drug_vendorid', Quantity = '$drug_quantity', Manufacture_Date = '$drug_manudate', ExpiryDate = '$drug_expiry' WHERE SupplyID = '$id'";
            if (mysqli_query($conn, $sql)) {
                $_SESSION['message'] = "Supply Successfully Updated";
                $_SESSION['message_type'] = "success";
                header("location: ./vSupply.php");
                exit();
            } else {
                echo "Error: " . $sql . "<br>" . mysqli_error($conn);
            }
        }
    }
}

if (isset($_GET['update'])) {
    $id = storeData($_GET['update']); // Ensure $id is treated as a string
    $sql = "SELECT * FROM drug_supply WHERE SupplyID = '$id'";
    $result = mysqli_query($conn, $sql);
    if ($row = mysqli_fetch_array($result)) {
        $update = true;
        $drug_headerid = $row['DrugHeaderID'];
        $drug_vendorid = $row['VendorID'];
        $drug_quantity = $row['Quantity'];
        $drug_manudate = $row['Manufacture_Date'];
        $drug_expiry = $row['ExpiryDate'];
    }
}}
?>

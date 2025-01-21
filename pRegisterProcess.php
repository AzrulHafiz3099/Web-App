<?php
// vRegisterProcess.php
require_once "./pDatabase.php";
session_start();

if (isset($_POST['login'])) {
    header("location: ./vLogin.php");
    exit;
}

define('REQUIRE_ERROR', 'THIS FIELD IS REQUIRED');

$error = [];

function storeData($data) {
    return htmlspecialchars(stripcslashes(trim($data)));
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['create'])) {
    $vfullname = isset($_POST['fullname']) ? storeData($_POST['fullname']) : '';
    $vusername = isset($_POST['username']) ? storeData($_POST['username']) : '';
    $vpassword = isset($_POST['password']) ? storeData($_POST['password']) : '';
    $vaddress = isset($_POST['address']) ? storeData($_POST['address']) : '';
    $vcontact = isset($_POST['contact_num']) ? storeData($_POST['contact_num']) : '';
    $vemail = isset($_POST['email']) ? storeData($_POST['email']) : '';
    $vlatitude = isset($_POST['latitude']) ? storeData($_POST['latitude']) : '';
    $vlongitude = isset($_POST['longitude']) ? storeData($_POST['longitude']) : '';

    if (!$vfullname) $error['fullname'] = REQUIRE_ERROR;
    if (!$vusername) $error['username'] = REQUIRE_ERROR;

    // Check if the username is already taken
    $sql = "SELECT Username FROM vendor WHERE Username = '$vusername';";
    $result = mysqli_query($conn, $sql);
    if (mysqli_num_rows($result) > 0) {
        $error['username'] = "USERNAME IS TAKEN";
    }

    if (!$vpassword) $error['password'] = REQUIRE_ERROR;
    if (!$vaddress) $error['address'] = REQUIRE_ERROR;
    if (!$vcontact) $error['contact_num'] = REQUIRE_ERROR;
    if (!$vemail) $error['email'] = REQUIRE_ERROR;
    if (!$vlatitude) $error['latitude'] = REQUIRE_ERROR;
    if (!$vlongitude) $error['longitude'] = REQUIRE_ERROR;

    if (empty($error)) {
        // Hash the password before saving to the database
        $hashedPassword = password_hash($vpassword, PASSWORD_DEFAULT);

        // Insert the hashed password into the database
        $query = "INSERT INTO vendor (Fullname, Username, Password, Address, ContactNumber, Email, Latitude, Longitude) 
                  VALUES ('$vfullname', '$vusername', '$hashedPassword', '$vaddress', '$vcontact', '$vemail', '$vlatitude', '$vlongitude');";

        if (mysqli_query($conn, $query)) {
            $_SESSION['message'] = "Registration successful!";
        } else {
            $_SESSION['message'] = "Error: " . mysqli_error($conn);
        }
        header("location: ./vRegister.php");
        exit;
    } else {
        $_SESSION['error'] = $error;
        header("location: ./vRegister.php");
        exit;
    }
}
?>

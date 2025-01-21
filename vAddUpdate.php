<?php
require_once "./pTableProcess.php";
$drugIDs = getDrugIDs($conn); // Fetch Drug IDs from the database

// Check if the user is logged in
if (!isset($_SESSION['username'])) {
    // If the user is not logged in, redirect to the login page
    header("location: ./vLogin.php");
    exit();
}

$username = $_SESSION['username'];

// Retrieve VendorID and Fullname of the logged-in vendor
$sql = "SELECT VendorID, Fullname FROM vendor WHERE Username=? OR Email=?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ss", $username, $username);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $vendor = $result->fetch_assoc();
    $vendorID = $vendor['VendorID']; 
} else {
    die("Vendor not found.");
}

$manufactureDates = isset($drug_id) ? getManufactureDates($conn, $drug_id) : [];
?>

<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" type="text/css" href="./style_addUpdate.css">
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" integrity="sha512-Fo3rlrQkzgmt48uP4RTyZQlKMYbF89FidElA2EsJg1UqPBWxxJEuDQxzqAATC2wZQ6vYQweNOgmo7uLzw5c8Jw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <title>Vendor Manager | <?php echo $update ? "Update Supply" : "Add Supply"; ?></title>
</head>
<body>

    <?php if(isset($_SESSION['message'])): ?>
    <div class="alert alert-<?php echo $_SESSION['message_type']; ?>" role="alert">
        <?php
            echo $_SESSION['message'] . "<br>";
            unset($_SESSION['message']);
        ?>
    </div>
    <?php endif; ?>

    <center>
        <form action="./pTableProcess.php" method="post" enctype="multipart/form-data"> 
            <div class="title">
                <?php echo $update ? "Update Supply" : "Add Supply"; ?>
            </div>
            <div class="container">
                <input type="hidden" name="id" value="<?php echo $id; ?>">

                <div class="col-sm-10">
                    <label><i class="drug_id"></i> Drug ID : </label>   
                    <div class="select-container">
                        <select name="drug_id" class="form-control">
                            <option value="">Select Drug ID</option>
                            <?php foreach ($drugIDs as $id): ?>
                                <option value="<?php echo $id; ?>" <?php echo ($drug_id == $id) ? 'selected' : ''; ?>><?php echo $id; ?></option>
                            <?php endforeach; ?>
                        </select>
                        <i class="fa fa-chevron-down"></i>
                    </div>
                    <?php if(isset($error['drug_id'])): ?>
                        <div class="text-danger"><?php echo $error['drug_id']; ?></div>
                    <?php endif; ?>
                </div>

                <div class="col-sm-10">
                    <label>Vendor ID:</label>
                    <input type="text" name="vendor_id" class="form-control" value="<?php echo $vendorID; ?>" readonly>
                </div>

                <div class="col-sm-10">
                    <label>Drug Header ID:</label>
                    <input type="text" id="drug_headerid" name="drug_headerid" class="form-control" value="<?php echo isset($drug_headerid) ? $drug_headerid : ''; ?>" readonly>
                </div>

                <div class="col-sm-10">
                    <label><i class="quantity"></i> Quantity : </label>   
                    <input type="text" name="quantity" class="form-control" value="<?php echo $drug_quantity; ?>">
                    <?php if(isset($error['quantity'])): ?>
                        <div class="text-danger"><?php echo $error['quantity']; ?></div>
                    <?php endif; ?>
                </div>

                <div class="col-sm-10">
                    <label>Manufacture Date:</label>
                    <input type="text" id="drug_manudate" name="manufacture_date" class="form-control" 
                        value="<?php echo isset($manufactureDates[0]) ? htmlspecialchars($manufactureDates[0]) : ''; ?>" readonly>
                </div>

                <div class="col-sm-10">
                    <label><i class="expiry_date"></i> Expiry Date : </label>   
                    <input type="date" name="expiry_date" class="form-control" value="<?php echo $drug_expiry; ?>">
                    <?php if(isset($error['expiry_date'])): ?>
                        <div class="text-danger"><?php echo $error['expiry_date']; ?></div>
                    <?php endif; ?>
                </div>

                <div class="col-sm-10" style="text-align: center; padding-top: 20px;">
                    <?php if($update): ?>
                        <button type="submit" class="btn btn-primary" name="update"><i class="fa fa-save"></i> Update</button>
                    <?php else: ?>
                        <button type="submit" class="btn btn-primary" name="save"><i class="fa fa-save"></i> Save</button>
                    <?php endif; ?>
                </div>

                <div class="col-sm-10" style="text-align: center; padding-top: 20px;">
                    <button type="submit" class="btn btn-danger" name="back"><i class="fa fa-arrow-left"></i> Back</button>
                </div>
            </div>
        </form>
    </center>
</body>
</html>

<script>
    document.querySelector('select[name="drug_id"]').addEventListener('change', function () {
    const drugID = this.value; // Get selected DrugID
    const headerIDField = document.getElementById('drug_headerid'); // DrugHeaderID field
    const manufactureDateField = document.getElementById('drug_manudate'); // Manufacture Date field

    // Fetch and update DrugHeaderID
    if (drugID) {
        fetch(`./pGetDrugHeader.php?drug_id=${drugID}`)
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    headerIDField.value = data.drug_headerid; // Update DrugHeaderID
                } else {
                    headerIDField.value = ''; // Clear if no match
                    console.error(data.message);
                }
            })
            .catch(err => {
                console.error('Error fetching DrugHeaderID:', err);
                headerIDField.value = ''; // Clear on error
            });

        // Fetch and update Manufacture Date
        fetch(`./pGetManuDates.php?drug_id=${drugID}`)
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    manufactureDateField.value = data.manufacture_date; // Update Manufacture Date
                } else {
                    manufactureDateField.value = ''; // Clear if no match
                    console.error(data.message);
                }
            })
            .catch(err => {
                console.error('Error fetching Manufacture Date:', err);
                manufactureDateField.value = ''; // Clear on error
            });
    } else {
        headerIDField.value = ''; // Clear if no DrugID is selected
        manufactureDateField.value = ''; // Clear Manufacture Date
    }
});
</script>


<?php
require('./zfpdf/fpdf.php');  
require_once "./pDatabase.php";
// require_once "../Vendorsite/save_chart_image.php";    
session_start();

// Check if the user is logged in
if (!isset($_SESSION['username'])) {
    header("Location: ./vLogin.php");
    exit();
}

// Retrieve the saved chart image path from the session
$chartImagePath = $_SESSION['chartImagePath'] ?? null;

// Fetching user details
$username = $_SESSION['username'];
$sql = "SELECT VendorID, Fullname, Address, ContactNumber, Email FROM vendor WHERE Username=? OR Email=?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ss", $username, $username);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $vendor = $result->fetch_assoc();
    $vendorID = htmlspecialchars($vendor['VendorID']);
    $vendorName = htmlspecialchars($vendor['Fullname']);
    $vendorAddress = htmlspecialchars($vendor['Address']);
    $vendorContact = htmlspecialchars($vendor['ContactNumber']);
    $vendorEmail = htmlspecialchars($vendor['Email']);
} else {
    $vendorName = 'Unknown Vendor';
    $vendorAddress = 'Unknown Address';
    $vendorContact = 'Unknown Contact';
    $vendorEmail = 'Unknown Email';
}

// Fetch counts for dashboard
$drug_supplies = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM drug_details"))['total'] ?? 0;

$supply_low = 0;
if ($vendorID) {
    $sql = "SELECT COUNT(*) as total FROM drug_supply WHERE Quantity <= 10 AND VendorID=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $vendorID);
    $stmt->execute();
    $result = $stmt->get_result();
    $supply_low = $result->fetch_assoc()['total'] ?? 0;
}

$supply_orders = 0;
if ($vendorID) {
    $sql = "SELECT COUNT(*) as total FROM drug_supply WHERE VendorID=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $vendorID);
    $stmt->execute();
    $result = $stmt->get_result();
    $supply_orders = $result->fetch_assoc()['total'] ?? 0;
}

// Get current date and time for footer and header
$current_datetime = date("Y-m-d H:i:s");

// Create a new PDF document
$pdf = new FPDF();
$pdf->AddPage();
$pdf->SetFont('Arial', 'B', 16);

// Title
$pdf->Cell(0, 10, 'Dashboard Report', 0, 1, 'C');
$pdf->Ln(10);

// User Information
$pdf->SetFont('Arial', 'I', 12);
$pdf->Cell(0, 10, 'User Information:', 0, 1);
$pdf->Ln(5);
$pdf->Cell(0, 10, 'Full Name: ' . $vendorName, 0, 1);
$pdf->Cell(0, 10, 'Username: ' . $username, 0, 1);
// Use MultiCell for the address to wrap text
$pdf->Cell(0, 10, 'Address:', 0, 1);
$pdf->MultiCell(0, 10, $vendorAddress);
$pdf->Cell(0, 10, 'Contact: ' . $vendorContact, 0, 1);
$pdf->Cell(0, 10, 'Email: ' . $vendorEmail, 0, 1);
$pdf->Ln(10);

// Dashboard Data
$pdf->SetFont('Arial', '', 12);
$pdf->Cell(0, 10, 'Total Drug Brands in the System: ' . $drug_supplies, 0, 1);
$pdf->Cell(0, 10, 'Total Drug Supplies that Need to Be Restocked (<10): ' . $supply_low, 0, 1);
$pdf->Cell(0, 10, 'Total Supplied Orders from this Pharmacy: ' . $supply_orders, 0, 1);

// Include both charts if they exist and are saved properly
if ($chartImagePath && file_exists($chartImagePath)) {
    $pdf->Image($chartImagePath, 10, 160, 180);  // Adjust dimensions as needed
} else {
    $pdf->Cell(0, 10, 'Pie chart image not found or path is incorrect.', 0, 1);
}

$pdf->Ln(10);

// Footer - Display current date/time
$pdf->SetY(-15);
$pdf->SetFont('Arial', 'I', 10);
$pdf->Cell(0, 10, 'Generated on: ' . $current_datetime, 0, 0, 'C');

// Output the PDF
$pdf->Output('D', 'dashboard_report.pdf');
?>

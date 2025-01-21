<?php
session_start();
require_once('tcpdf/tcpdf.php'); // Include TCPDF library
@include 'db_connection.php';

// Check if the user is logged in
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header('Location: login.php');
    exit;
}

// Fetch dashboard data
$pharmacy_accounts = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM vendor"))['total'];
$drug_inventory = mysqli_fetch_assoc(mysqli_query($conn, "SELECT SUM(Quantity) as total FROM drug_supply"))['total'];
$supply_orders = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM `order`"))['total'];
$drug_details = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM `drug_details`"))['total'];

// Create a new PDF document
$pdf = new TCPDF();

// Set document properties
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('Pharmacy Manager');
$pdf->SetTitle('Dashboard Report');
$pdf->SetSubject('Pharmacy Dashboard');

// Add a page
$pdf->AddPage();

// Set font
$pdf->SetFont('helvetica', '', 12);

// Add content
$html = "
    <h1 style='text-align:center;'>Pharmacy Dashboard Report</h1>
    <h3>Summary</h3>
    <table border='1' cellspacing='3' cellpadding='4' style='width:100%;'>
        <tr style='background-color:#f2f2f2;'>
            <th>Metric</th>
            <th>Value</th>
        </tr>
        <tr>
            <td>Pharmacy Accounts</td>
            <td>{$pharmacy_accounts}</td>
        </tr>
        <tr>
            <td>Drug Details</td>
            <td>{$drug_details}</td>
        </tr>
        <tr>
            <td>Supply Orders</td>
            <td>{$supply_orders}</td>
        </tr>
        <tr>
            <td>Drug Stock</td>
            <td>{$drug_inventory}</td>
        </tr>
    </table>
    <p>Generated on: " . date('Y-m-d H:i:s') . "</p>
";

// Write HTML to the PDF
$pdf->writeHTML($html, true, false, true, false, '');

// Output the PDF (download)
$pdf->Output('dashboard_report.pdf', 'D');
?>

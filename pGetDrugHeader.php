<?php
require_once "./pDatabase.php";
require_once "./pTableProcess.php"; // Include helper functions if not already included

if (isset($_GET['drug_id']) && !empty($_GET['drug_id'])) {
    $drug_id = htmlspecialchars($_GET['drug_id']);
    $drug_headerid = getDrugHeaderID($conn, $drug_id); // Use the function from vTableProcess.php

    if ($drug_headerid) {
        echo json_encode(['success' => true, 'drug_headerid' => $drug_headerid]);
    } else {
        echo json_encode(['success' => false, 'message' => 'No Drug Header ID found']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid Drug ID']);
}
?>

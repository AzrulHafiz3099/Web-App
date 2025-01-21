<?php
require_once "./pDatabase.php";

if (isset($_GET['drug_id']) && !empty($_GET['drug_id'])) {
    $drug_id = htmlspecialchars($_GET['drug_id']);
    $sql = "SELECT DISTINCT Manufacture_Date FROM drug_details WHERE DrugID = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $drug_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $manufacture_dates = [];
    
    while ($row = $result->fetch_assoc()) {
        $manufacture_dates[] = $row['Manufacture_Date'];
    }

    if (!empty($manufacture_dates)) {
        echo json_encode(['success' => true, 'manufacture_date' => $manufacture_dates[0]]);
    } else {
        echo json_encode(['success' => false, 'message' => 'No manufacture date found']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid Drug ID']);
}
?>

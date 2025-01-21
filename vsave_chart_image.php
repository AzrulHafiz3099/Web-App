<?php
session_start();

if (isset($_POST['chartImageData'])) {
    $chartData = $_POST['chartImageData'];
    $fileName = time() . '.png';
    $filePath = './Image/' . $fileName;

    // Decode base64 image and save it as a file
    $chartData = str_replace('data:image/png;base64,', '', $chartData);
    $chartData = str_replace(' ', '+', $chartData);
    $imageData = base64_decode($chartData);

    if (file_put_contents($filePath, $imageData)) {
        $_SESSION['chartImagePath'] = $filePath; // Store path in session
        echo json_encode(['status' => 'success', 'filePath' => $filePath]);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Failed to save the image.']);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'No image data received.']);
}
?>

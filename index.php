<?php
header("Content-Type: application/json; charset=UTF-8");

$uploadDir = __DIR__ . '/uploads/';
if (!file_exists($uploadDir)) {
    mkdir($uploadDir, 0777, true);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['image'])) {
    $uploadedFile = $_FILES['image'];

    $extension = pathinfo($uploadedFile['name'], PATHINFO_EXTENSION);

    $fileName = uniqid() . '.' . $extension;

    $filePath = $uploadDir . $fileName;
    if (move_uploaded_file($uploadedFile['tmp_name'], $filePath)) {
        $response = ['status' => 'success', 'message' => 'File uploaded successfully', 'url' => 'uploads/' . $fileName];
    } else {
        $response = ['status' => 'error', 'message' => 'Failed to move uploaded file'];
    }

    echo json_encode($response);
} else if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['filename'])) {
    $filename = $_GET['filename'];

    $filePath = $uploadDir . $filename;

    if (file_exists($filePath)) {
        header("Content-Type: image");
        header("Content-Disposition: inline; filename=$filename");
        readfile($filePath);
    } else {
        header("HTTP/1.1 404 Not Found");
        echo $filename.'File not found';
    }
} else {
    header("HTTP/1.1 400 Bad Request");
    echo 'Invalid request';
}
?>

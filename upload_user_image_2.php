<?php

session_start();

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);


require 'db_connection.php';

if (!isset($_SESSION['loggedin'])) {
    header('Location: Login.php');
    exit;
}

if(isset($_SESSION['id'])) {
    echo 'Session ID is set with a value of: ' . $_SESSION['id'];
} else {
    echo 'Session ID is not set.';
}


if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['file'])) {
    $file = $_FILES['file'];
    
    $userId = $_SESSION['id'];
    
    // Validate file
    if ($file['error'] === UPLOAD_ERR_OK) {
        $tempFilePath = $file['tmp_name'];
        $fileName = $file['name'];
        $fileSize = $file['size'];
        $fileType = $file['type'];
        
        // Define the upload directory and move the file
        $uploadDirectory = '/var/www/html/uploads/';
        $targetFilePath = $uploadDirectory . $fileName;
        move_uploaded_file($tempFilePath, $targetFilePath);
        
        // Insert the file information into the database
        $insertQuery = $conn->prepare("INSERT INTO images (user_id, file_name, uploaded_on) VALUES (?, ?, NOW())");
        $insertQuery->bind_param('is', $userId, $fileName);
        $insertQuery->execute();
        $insertQuery->close();
        
        // Redirect back to the user page
        header("Location: User.php");
    }
}
?>

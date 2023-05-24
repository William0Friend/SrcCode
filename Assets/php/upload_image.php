<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
// Check if the file was uploaded without errors
if ($_FILES["imageFile"]["error"] === UPLOAD_ERR_OK) {
  $tempName = $_FILES["imageFile"]["tmp_name"];
  $fileName = $_FILES["imageFile"]["name"];
  $targetPath = "/var/www/html/uploads/" . $fileName;

  // Check if the uploaded file is an image (you can add additional checks if needed)
  if (getimagesize($tempName) !== false) {
    // Move the uploaded file to the target location
    if (move_uploaded_file($tempName, $targetPath)) {
      // File upload successful
      echo "File uploaded successfully.";
    } else {
      // File upload failed
      echo "Failed to upload the file.";
    }
  } else {
    echo "Invalid file format. Only image files are allowed.";
  }
} else {
  echo "Error occurred during file upload.";
}

require 'db_connection.php';

// Check the connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get the user ID or any other identifier for the user
$userID = $_SESSION["id"];

// Insert or update the image path in the database
$imagePath = "/uploads/" . $fileName;
$sql = "UPDATE users SET image_path = '$imagePath' WHERE id = $userID";
$conn->query($sql);

// Close the database connection
$conn->close();

?>
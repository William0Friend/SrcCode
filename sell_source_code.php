<?php
// sell_source_code.php

// Start the session
session_start();

// Database connection
require 'db_connection.php';

// Check if the user is logged in
if (!isset($_SESSION['userId'])) {
    echo json_encode(['success' => false, 'message' => 'Not logged in']);
    exit;
}

$userId = $_SESSION['userId']; // Get the user ID from the session

// Check if file was uploaded
if (!isset($_FILES['sourceCode'])) {
    echo json_encode(['success' => false, 'message' => 'No file uploaded']);
    exit;
}

$sourceCode = file_get_contents($_FILES['sourceCode']['tmp_name']);

$sql = "INSERT INTO source_code (user_id, source_code) VALUES (?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("is", $userId, $sourceCode);
$success = $stmt->execute();

echo json_encode(['success' => $success]);
?>
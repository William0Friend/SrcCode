<?php
// Start the session
session_start();

// Check if the user is logged in
if (!isset($_SESSION['userId'])) {
    echo json_encode(['success' => false]);
    exit;
}

// Get the question and bounty from the request
$question = $_POST['question'];
$bounty = $_POST['bounty'];

// TODO: Validate and sanitize the question and bounty

// Get the user ID from the session
$userId = $_SESSION['userId'];

// Database connection
require 'db_connection.php';

// Insert the question into the database
$sql = "INSERT INTO questions (user_id, question, bounty) VALUES (?, ?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("isi", $userId, $question, $bounty);

if ($stmt->execute()) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false]);
}
?>
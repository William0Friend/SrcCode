<?php
// submit_answer.php

// Start the session
session_start();

// Database connection
require 'db_connection.php';

// Check if the user is logged in
if (!isset($_SESSION['userId'])) {
    echo json_encode(['success' => false, 'message' => 'Not logged in']);
    exit;
}

$questionId = $_POST['questionId'];
$answer = $_POST['answer'];
$userId = $_SESSION['userId']; // Get the user ID from the session

$sql = "INSERT INTO answers (question_id, user_id, answer) VALUES (?, ?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("iis", $questionId, $userId, $answer);
$success = $stmt->execute();

echo json_encode(['success' => $success]);
?>
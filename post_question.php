<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();

if (!isset($_SESSION['loggedin'])) {
    header('Location: Login.php');
    exit;
}
// TODO: Validate and sanitize the title, body and bounty

// Get the title, body, and bounty from the request
$title = htmlspecialchars($_POST['title']);
$body = htmlspecialchars($_POST['body']);
$bounty = htmlspecialchars($_POST['bounty']);
// Get the user ID from the session
$userId = $_SESSION['id'];

// Database connection
require 'db_connection.php';

// Insert the question into the database
$sql = "INSERT INTO Questions (user_id, title, body, bounty) VALUES (?, ?, ?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("issi", $userId, $title, $body, $bounty);
$s = $stmt->execute();
if ($s) {
    echo json_encode(['success' => true, 'message' => 'Question posted successfully.']);
} else {
    echo json_encode(['success' => false, 'message' => 'Failed to post question. Error: '.$conn->error]);
}
$stmt->close();
$conn->close();
?>

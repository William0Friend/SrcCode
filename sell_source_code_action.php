<?php
header('Content-Type: application/json');

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// sell_source_code.php
session_start();

require 'db_connection.php';

// Check if the user is logged in
if (!isset($_SESSION['id'])) {
    echo json_encode(['success' => false, 'message' => 'Not logged in']);
    exit;
}

$userId = $_SESSION['id']; // Get the user ID from the session

// Check if file was uploaded
if (!isset($_FILES['sourceCode'])) {
    echo json_encode(['success' => false, 'message' => 'No file uploaded']);
    exit;
}

// Check if question title was provided
if (!isset($_POST['questionTitle']) || !isset($_POST['body']) || !isset($_POST['filePath'])) {
    echo json_encode(['success' => false, 'message' => 'Missing required fields']);
    exit;
}

//if it was povided move on
$questionTitle = $_POST['questionTitle'];
$body = $_POST['body'];
$filePath = $_POST['filePath'];

// Get the question ID from the database
$stmt = $conn->prepare("SELECT id FROM Questions WHERE title = ?");
$stmt->bind_param("s", $questionTitle);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();

if (!$row) {
    echo json_encode(['success' => false, 'message' => 'Invalid question title']);
    exit;
}

$questionId = $row['id'];

$sourceCode = file_get_contents($_FILES['sourceCode']['tmp_name']);

$sql = "INSERT INTO Answers (question_id, user_id, body, file, file_path) VALUES (?, ?, ?, ?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("iisss", $questionId, $userId, $body, $sourceCode, $filePath);

$success = $stmt->execute();
if ($success === false) {
    die("Execute failed: " . $stmt->error);
}

 echo json_encode(['success' => $success]);
 header("Location: Sell_Source_Code.php"); // Redirect user to same page, to clean out the form
 
 $stmt->close();
 $conn->close();
 
 
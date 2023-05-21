<?php
session_start();

require 'db_connection.php'; // include the database connection

if (!isset($_SESSION['loggedin'])) {
    header('Location: Login.php');
    exit;
}

// Get the question id from POST data
$question_id = $_POST['question_id'];

// Check if update button was clicked
if (isset($_POST['update'])) {
    $new_title = $_POST['new_title'];
    $new_body = $_POST['new_body'];
    
    // Update the question with the new title and body
    $query = $conn->prepare('UPDATE Questions SET title = ?, body = ? WHERE id = ? AND user_id = ?');
    $query->bind_param('ssii', $new_title, $new_body, $question_id, $_SESSION['id']);
    $query->execute();
    
    header('Location: Users.php');
    exit;
}

// Check if delete button was clicked
if (isset($_POST['delete'])) {
    // Delete the question
    $query = $conn->prepare('DELETE FROM Questions WHERE id = ? AND user_id = ?');
    $query->bind_param('ii', $question_id, $_SESSION['id']);
    $query->execute();
    
    header('Location: Users.php');
    exit;
}
?>

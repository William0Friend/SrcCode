<?php
// get_question.php

// Database connection
require 'db_connection.php';

$questionId = $_GET['id'];

// Fetch question
$questionSql = "SELECT * FROM questions WHERE id = ?";
$stmt = $conn->prepare($questionSql);
$stmt->bind_param("i", $questionId);
$stmt->execute();

$result = $stmt->get_result();
$question = $result->fetch_assoc();

// Fetch answers
$answersSql = "SELECT * FROM answers WHERE question_id = ?";
$stmt = $conn->prepare($answersSql);
$stmt->bind_param("i", $questionId);
$stmt->execute();

$answers = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

echo json_encode(['question' => $question, 'answers' => $answers]);

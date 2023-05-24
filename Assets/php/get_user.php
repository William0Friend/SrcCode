<?php
// get_user.php

// Database connection
require 'db_connection.php';

$userId = $_GET['id'];

// Fetch user details
$userSql = "SELECT * FROM users WHERE id = ?";
$stmt = $conn->prepare($userSql);
$stmt->bind_param("i", $userId);
$stmt->execute();

$user = $stmt->get_result()->fetch_assoc();

// Fetch user activity
$activitySql = "SELECT * FROM activity WHERE user_id = ?";
$stmt = $conn->prepare($activitySql);
$stmt->bind_param("i", $userId);
$stmt->execute();

$activity = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

echo json_encode(['user' => $user, 'activity' => $activity]);
